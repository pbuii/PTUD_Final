<?php
require_once __DIR__ . '/../Database.php';

class User {
  public static function timTheoEmail(string $email): ?array {
    $pdo = Database::pdo();
    $stm = $pdo->prepare("SELECT * FROM nguoi_dung WHERE email = :email LIMIT 1");
    $stm->execute(['email' => $email]);
    $row = $stm->fetch();
    return $row ?: null;
  }

  public static function taoMoi(array $data): int {
    $pdo = Database::pdo();
    $stm = $pdo->prepare("
      INSERT INTO nguoi_dung (email, mat_khau_bam, ho_ten, ngay_sinh, so_dien_thoai, vai_tro, trang_thai)
      VALUES (:email, :mat_khau_bam, :ho_ten, :ngay_sinh, :so_dien_thoai, :vai_tro, :trang_thai)
    ");
    $stm->execute([
      'email' => $data['email'],
      'mat_khau_bam' => $data['mat_khau_bam'],
      'ho_ten' => $data['ho_ten'] ?? null,
      'ngay_sinh' => $data['ngay_sinh'] ?? null, // ✅ thêm
      'so_dien_thoai' => $data['so_dien_thoai'] ?? null,
      'vai_tro' => $data['vai_tro'] ?? 'NGUOI_DUNG',
      'trang_thai' => $data['trang_thai'] ?? 'HOAT_DONG',
    ]);
    return (int)$pdo->lastInsertId();
  }


  public static function capNhatLanDangNhapGanNhat(int $id): void {
    $pdo = Database::pdo();
    $stm = $pdo->prepare("UPDATE nguoi_dung SET lan_dang_nhap_gan_nhat = NOW() WHERE id = :id");
    $stm->execute(['id' => $id]);
  }

  public static function capNhatThongTin(int $id, array $data): bool {
    $pdo = Database::pdo();
    $sql = "UPDATE nguoi_dung 
            SET ho_ten = :ho_ten, 
                ngay_sinh = :ngay_sinh, 
                so_dien_thoai = :so_dien_thoai 
            WHERE id = :id";
            
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'ho_ten' => $data['ho_ten'],
        'ngay_sinh' => $data['ngay_sinh'] ?: null,
        'so_dien_thoai' => $data['so_dien_thoai'],
        'id' => $id
    ]);
  }  

  // Lấy thông tin user (bao gồm mật khẩu) để kiểm tra
    public static function timTheoId(int $id): ?array {
        $pdo = Database::pdo();
        $stm = $pdo->prepare("SELECT * FROM nguoi_dung WHERE id = :id LIMIT 1");
        $stm->execute(['id' => $id]);
        $row = $stm->fetch();
        return $row ?: null;
    }

    // Cập nhật mật khẩu mới
    public static function capNhatMatKhau(int $id, string $matKhauMoiBam): bool {
        $pdo = Database::pdo();
        $stm = $pdo->prepare("UPDATE nguoi_dung SET mat_khau_bam = :mk WHERE id = :id");
        return $stm->execute([
            'mk' => $matKhauMoiBam,
            'id' => $id
        ]);
    }
}