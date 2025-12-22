<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

final class Order
{
    private static function taoMaDonHang(): string
    {
        // VD: DH20251217-AB12CD
        $rand = strtoupper(bin2hex(random_bytes(3)));
        return 'DH' . date('Ymd') . '-' . $rand;
    }

    private static function layGioHangId(int $uid): int
    {
        $pdo = Database::pdo();
        $stm = $pdo->prepare("SELECT id FROM gio_hang WHERE nguoi_dung_id = :uid LIMIT 1");
        $stm->execute(['uid' => $uid]);
        $row = $stm->fetch();
        return $row ? (int)$row['id'] : 0;
    }

    public static function taoTuGioHang(int $uid, array $payload): array
    {
        $pdo = Database::pdo();
        $gio_hang_id = self::layGioHangId($uid);
        if ($gio_hang_id <= 0) return ['ok' => false, 'code' => 400, 'error' => 'Giỏ hàng trống'];

        $pdo->beginTransaction();
        try {
            $sql = "
            SELECT
                ct.id AS chi_tiet_id,
                ct.san_pham_id,
                ct.so_luong,
                ct.don_gia,
                sp.ten_san_pham,
                sp.trang_thai,
                ct.sku_id AS sku_id,
                sku.ma_sku,
                sku.gia_ban AS sku_gia_ban,
                sku.so_luong_ton AS sku_ton,
                sku.kich_co_id,
                sku.mau_sac_id
            FROM chi_tiet_gio_hang ct
            JOIN san_pham sp ON sp.id = ct.san_pham_id
            LEFT JOIN sku_san_pham sku ON sku.id = ct.sku_id
            WHERE ct.gio_hang_id = :gid
            FOR UPDATE
            ";
            $stm = $pdo->prepare($sql);
            $stm->execute(['gid' => $gio_hang_id]);
            $items = $stm->fetchAll();

            if (!$items) {
                $pdo->rollBack();
                return ['ok' => false, 'code' => 400, 'error' => 'Giỏ hàng trống'];
            }

            // Validate + tính tiền
            $tam_tinh = 0.0;

            foreach ($items as $it) {
                if (($it['trang_thai'] ?? '') !== 'DANG_BAN') {
                    $pdo->rollBack();
                    return ['ok' => false, 'code' => 403, 'error' => 'Có sản phẩm không còn bán trong giỏ'];
                }

                $qty = (int)$it['so_luong'];
                if ($qty <= 0) {
                    $pdo->rollBack();
                    return ['ok' => false, 'code' => 400, 'error' => 'Số lượng không hợp lệ trong giỏ'];
                }

                // Giá/tồn theo SKU (nếu có), fallback theo sp
                $gia = (float)($it['sku_gia_ban'] ?? $it['don_gia'] ?? 0);
                $ton = isset($it['sku_id']) && $it['sku_id']
                    ? (int)($it['sku_ton'] ?? 0)
                    : (int)($it['sp_ton'] ?? 0);

                if ($gia <= 0) {
                    $pdo->rollBack();
                    return ['ok' => false, 'code' => 400, 'error' => 'Giá sản phẩm không hợp lệ'];
                }

                if ($qty > $ton) {
                    $pdo->rollBack();
                    $skuText = $it['ma_sku'] ? (" (SKU {$it['ma_sku']})") : '';
                    return ['ok' => false, 'code' => 409, 'error' => "Không đủ tồn kho{$skuText}. Chỉ còn {$ton}."];
                }

                $tam_tinh += $gia * $qty;
            }

            $phi_ship = (float)($payload['phi_van_chuyen'] ?? 30000);
            $giam_gia = (float)($payload['giam_gia'] ?? 0);
            if ($phi_ship < 0) $phi_ship = 0;
            if ($giam_gia < 0) $giam_gia = 0;

            $tong = max(0, $tam_tinh + $phi_ship - $giam_gia);

            // Insert don_hang
            $ma = self::taoMaDonHang();

            // Lấy mã khuyến mãi từ payload
            $ma_km = isset($payload['ma_khuyen_mai']) ? (string)$payload['ma_khuyen_mai'] : null;
            if ($ma_km === '') $ma_km = null; 

            $stm = $pdo->prepare("
                INSERT INTO don_hang
                  (ma_don_hang, nguoi_dung_id, ma_khuyen_mai, trang_thai, phuong_thuc_thanh_toan, trang_thai_thanh_toan,
                   tam_tinh, phi_van_chuyen, giam_gia, tong_tien,
                   nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao_hang, ghi_chu)
                VALUES
                  (:ma, :uid, :mkm, 'CHO_XU_LY', :pttt, 'CHUA_THANH_TOAN',
                   :tam, :ship, :giam, :tong,
                   :nn, :sdt, :dc, :gc)
            ");
            $stm->execute([
                'ma' => $ma,
                'uid' => $uid,
                'pttt' => (string)($payload['phuong_thuc_thanh_toan'] ?? 'COD'),
                'tam' => $tam_tinh,
                'ship' => $phi_ship,
                'giam' => $giam_gia,
                'mkm' => $ma_km,
                'tong' => $tong,
                'nn' => (string)$payload['nguoi_nhan'],
                'sdt' => (string)$payload['sdt_nguoi_nhan'],
                'dc' => (string)$payload['dia_chi_giao_hang'],
                'gc' => $payload['ghi_chu'] ?? null,
            ]);

            $don_hang_id = (int)$pdo->lastInsertId();

            // Insert chi_tiet_don_hang + trừ kho
            // === SỬA: THÊM CỘT sku_id VÀO INSERT ===
            $stmCt = $pdo->prepare("
                INSERT INTO chi_tiet_don_hang
                  (don_hang_id, san_pham_id, sku_id, ten_san_pham, don_gia, so_luong, thanh_tien)
                VALUES
                  (:dhid, :spid, :sku_id, :ten, :gia, :qty, :tt)
            ");

            foreach ($items as $it) {
                $qty = (int)$it['so_luong'];
                $gia = (float)($it['sku_gia_ban'] ?? $it['don_gia'] ?? 0);
                $tt  = $gia * $qty;

                // “Snapshot” tên sản phẩm + SKU
                $ten = (string)$it['ten_san_pham'];
                if (!empty($it['ma_sku'])) $ten .= " ({$it['ma_sku']})";
                
                // Lấy sku_id (nếu có thì lấy, ko thì null)
                $sku_id_val = !empty($it['sku_id']) ? (int)$it['sku_id'] : null;

                $stmCt->execute([
                    'dhid'   => $don_hang_id,
                    'spid'   => (int)$it['san_pham_id'],
                    'sku_id' => $sku_id_val, // <--- QUAN TRỌNG: Lưu sku_id vào DB
                    'ten'    => $ten,
                    'gia'    => $gia,
                    'qty'    => $qty,
                    'tt'     => $tt,
                ]);

                // Trừ kho: ưu tiên SKU nếu có
                if (!empty($it['sku_id'])) {
                    $stmUp = $pdo->prepare("
                        UPDATE sku_san_pham
                        SET so_luong_ton = so_luong_ton - :qty_sub
                        WHERE id = :id AND so_luong_ton >= :qty_check
                    ");
                    $stmUp->execute([
                        'qty_sub'   => $qty,
                        'qty_check' => $qty,
                        'id'        => (int)$it['sku_id'],
                    ]);
                    if ($stmUp->rowCount() <= 0) {
                        $pdo->rollBack();
                        return ['ok' => false, 'code' => 409, 'error' => 'Tồn kho thay đổi, vui lòng thử lại'];
                    }
                } else {
                    // fallback theo bảng san_pham
                    $stmUp = $pdo->prepare("
                        UPDATE san_pham
                        SET so_luong_ton = so_luong_ton - :qty_sub
                        WHERE id = :id AND so_luong_ton >= :qty_check
                    ");
                    $stmUp->execute([
                        'qty_sub'   => $qty,
                        'qty_check' => $qty,
                        'id'        => (int)$it['san_pham_id'],
                    ]);

                    if ($stmUp->rowCount() <= 0) {
                        $pdo->rollBack();
                        return ['ok' => false, 'code' => 409, 'error' => 'Tồn kho thay đổi, vui lòng thử lại'];
                    }
                }
            }

            // Clear cart
            $stm = $pdo->prepare("DELETE FROM chi_tiet_gio_hang WHERE gio_hang_id = :gid");
            $stm->execute(['gid' => $gio_hang_id]);

            $pdo->commit();

            return [
                'ok' => true,
                'don_hang_id' => $don_hang_id,
                'ma_don_hang' => $ma,
                'tam_tinh' => $tam_tinh,
                'phi_van_chuyen' => $phi_ship,
                'giam_gia' => $giam_gia,
                'tong_tien' => $tong,
            ];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return ['ok' => false, 'code' => 500, 'error' => 'Lỗi server: ' . $e->getMessage()];
        }
    }

    public static function danhSachTheoNguoiDung(int $uid): array
    {
        $pdo = Database::pdo();
        
        // Lấy danh sách đơn hàng (giữ nguyên)
        $stm = $pdo->prepare("
            SELECT id, ma_don_hang, trang_thai, trang_thai_thanh_toan, tong_tien, tao_luc AS ngay_dat
            FROM don_hang
            WHERE nguoi_dung_id = :uid
            ORDER BY tao_luc DESC
        ");
        $stm->execute(['uid' => $uid]);
        $orders = $stm->fetchAll(PDO::FETCH_ASSOC);

        // Với mỗi đơn hàng, lấy thêm chi tiết sản phẩm VỚI ẢNH CHÍNH XÁC
        foreach ($orders as &$order) {
            // CÂU SQL MỚI: JOIN với cả 3 bảng để lấy ảnh đúng
            $stmCt = $pdo->prepare("
                SELECT 
                    ct.san_pham_id,
                    ct.sku_id,
                    ct.ten_san_pham,
                    ct.so_luong,
                    ct.don_gia,
                    -- Ưu tiên 1: Ảnh từ SKU (nếu có và có ảnh)
                    COALESCE(
                        sku.anh_url,
                        -- Ưu tiên 2: Ảnh từ bảng anh_san_pham thông qua ID
                        (SELECT url_anh FROM anh_san_pham WHERE id = CAST(sp.anh_dai_dien_url AS UNSIGNED) LIMIT 1),
                        -- Fallback: Trả về ID để frontend xử lý
                        sp.anh_dai_dien_url
                    ) AS hinh_anh
                FROM chi_tiet_don_hang ct
                LEFT JOIN san_pham sp ON ct.san_pham_id = sp.id
                LEFT JOIN sku_san_pham sku ON sku.id = ct.sku_id
                WHERE ct.don_hang_id = :dhid
                ORDER BY ct.id ASC
            ");
            $stmCt->execute(['dhid' => $order['id']]);
            $order['chi_tiet_don_hang'] = $stmCt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }

    public static function chiTietTheoNguoiDung(int $uid, int $don_hang_id): array
    {
        $pdo = Database::pdo();

        $stm = $pdo->prepare("
            SELECT *
            FROM don_hang
            WHERE id = :id AND nguoi_dung_id = :uid
            LIMIT 1
        ");
        $stm->execute(['id' => $don_hang_id, 'uid' => $uid]);
        $dh = $stm->fetch();
        if (!$dh) return ['ok' => false, 'code' => 404, 'error' => 'Không tìm thấy đơn hàng'];

        // Hàm này dùng SELECT * nên tự động lấy được sku_id nếu DB đã có cột đó
        $stm = $pdo->prepare("
            SELECT *
            FROM chi_tiet_don_hang
            WHERE don_hang_id = :id
            ORDER BY id ASC
        ");
        $stm->execute(['id' => $don_hang_id]);
        $ct = $stm->fetchAll();

        return ['ok' => true, 'don_hang' => $dh, 'chi_tiet' => $ct];
    }
}