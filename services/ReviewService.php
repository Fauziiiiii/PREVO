<?php
// ============================================================
//  PREVO - Service Ulasan & Penilaian Bintang
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

class ReviewService {
    private PDO $db;

    public function __construct() {
        $this->db = get_db();
    }

    /**
     * Menambahkan ulasan dan rating untuk transaksi yang telah selesai
     */
    public function addReview(int $transactionId, int $reviewerId, int $rating, ?string $comment): array {
        // Validasi rating 1 - 5
        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'error' => 'Rating harus bernilai antara 1 sampai 5 bintang.'];
        }

        // Ambil data transaksi
        $stmt = $this->db->prepare("SELECT id, seller_id, buyer_id, status FROM transactions WHERE id = :id");
        $stmt->execute(['id' => $transactionId]);
        $tx = $stmt->fetch();

        if (!$tx) {
            return ['success' => false, 'error' => 'Transaksi tidak ditemukan.'];
        }

        if ($tx['status'] !== 'selesai') {
            return ['success' => false, 'error' => 'Ulasan hanya dapat diberikan setelah transaksi COD berstatus selesai.'];
        }

        if ((int)$tx['buyer_id'] !== $reviewerId) {
            return ['success' => false, 'error' => 'Hanya pembeli yang berhak memberikan ulasan pada transaksi ini.'];
        }

        // Cek apakah sudah pernah diulas
        $check = $this->db->prepare("SELECT id FROM reviews WHERE transaction_id = :tx_id");
        $check->execute(['tx_id' => $transactionId]);
        if ($check->fetch()) {
            return ['success' => false, 'error' => 'Anda sudah memberikan ulasan untuk transaksi ini.'];
        }

        try {
            $insert = $this->db->prepare("INSERT INTO reviews (transaction_id, reviewer_id, seller_id, rating, comment)
                                          VALUES (:tx_id, :rev_id, :seller_id, :rating, :comment)");
            $insert->execute([
                'tx_id'     => $transactionId,
                'rev_id'    => $reviewerId,
                'seller_id' => $tx['seller_id'],
                'rating'    => $rating,
                'comment'   => trim($comment ?? '') ?: null
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menyimpan ulasan: ' . $e->getMessage()];
        }
    }
}
