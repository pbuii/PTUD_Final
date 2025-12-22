<?php
require_once __DIR__ . '/../Database.php';

class Address {
    public static function layTheoUser(int $userId): array {
        $pdo = Database::pdo();
        $stm = $pdo->prepare("SELECT * FROM dia_chi WHERE nguoi_dung_id = :uid ORDER BY mac_dinh DESC, id DESC");
        $stm->execute(['uid' => $userId]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function taoMoi(int $userId, array $data): bool {
        $pdo = Database::pdo();
        
        // Nếu địa chỉ mới là mặc định, bỏ mặc định các địa chỉ cũ
        if (!empty($data['mac_dinh'])) {
            $pdo->prepare("UPDATE dia_chi SET mac_dinh = 0 WHERE nguoi_dung_id = :uid")
                ->execute(['uid' => $userId]);
        }

        $stm = $pdo->prepare("
            INSERT INTO dia_chi (nguoi_dung_id, ten_nguoi_nhan, so_dien_thoai, tinh_thanh, quan_huyen, phuong_xa, dia_chi_cu_the, mac_dinh)
            VALUES (:uid, :ten, :sdt, :tinh, :quan, :phuong, :dc, :md)
        ");

        return $stm->execute([
            'uid'    => $userId,
            'ten'    => $data['ten_nguoi_nhan'],
            'sdt'    => $data['so_dien_thoai'],
            'tinh'   => $data['tinh_thanh'],
            'quan'   => $data['quan_huyen'],
            'phuong' => $data['phuong_xa'],
            'dc'     => $data['dia_chi_cu_the'],
            'md'     => !empty($data['mac_dinh']) ? 1 : 0
        ]);
    }

    public static function xoa(int $id, int $userId): bool {
    $pdo = Database::pdo();
    $stm = $pdo->prepare("DELETE FROM dia_chi WHERE id = :id AND nguoi_dung_id = :uid");
    return $stm->execute(['id' => $id, 'uid' => $userId]);
}
}