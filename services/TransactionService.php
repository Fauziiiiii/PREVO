<?php
// ============================================================
//  PREVO - Service Transaksi COD Atomik
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

class TransactionService {
    private PDO $db;

    public function __construct() {
        $this->db = get_db();
    }

    /**
     * Membuat transaksi booking 1-klik via Stored Procedure create_booking
     */
    public function createBooking(int $itemId, int $buyerId): array {
        try {
            $stmt = $this->db->prepare("CALL create_booking(:item_id, :buyer_id)");
            $stmt->execute([
                'item_id'  => $itemId,
                'buyer_id' => $buyerId
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'Barang tidak tersedia')) {
                return ['success' => false, 'error' => 'Maaf, barang ini baru saja dibooking atau sudah tidak tersedia.'];
            }
            if (str_contains($msg, 'Penjual tidak dapat')) {
                return ['success' => false, 'error' => 'Anda tidak dapat membooking barang jualan Anda sendiri.'];
            }
            return ['success' => false, 'error' => 'Gagal membooking barang: ' . $msg];
        }
    }

    /**
     * Penjual memperbarui harga kesepakatan nego WhatsApp / lokasi COD
     */
    public function updateBookingDeal(int $txId, int $sellerId, float $agreedPrice, ?string $location, ?string $meetupTime): array {
        try {
            $stmt = $this->db->prepare("CALL update_booking_deal(:tx_id, :seller_id, :agreed_price, :location, :meetup_time)");
            $stmt->execute([
                'tx_id'         => $txId,
                'seller_id'     => $sellerId,
                'agreed_price'  => $agreedPrice,
                'location'      => $location ?: null,
                'meetup_time'   => $meetupTime ?: null
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal memperbarui kesepakatan: ' . $e->getMessage()];
        }
    }

    /**
     * Konfirmasi COD selesai via Stored Procedure complete_transaction
     */
    public function completeTransaction(int $txId, int $actorId): array {
        try {
            $stmt = $this->db->prepare("CALL complete_transaction(:tx_id, :actor_id)");
            $stmt->execute([
                'tx_id'    => $txId,
                'actor_id' => $actorId
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menyelesaikan transaksi: ' . $e->getMessage()];
        }
    }

    /**
     * Batalkan transaksi pending via Stored Procedure cancel_transaction
     */
    public function cancelTransaction(int $txId, int $actorId, ?string $reason = null): array {
        try {
            $stmt = $this->db->prepare("CALL cancel_transaction(:tx_id, :actor_id, :reason)");
            $stmt->execute([
                'tx_id'    => $txId,
                'actor_id' => $actorId,
                'reason'   => $reason ?: null
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal membatalkan transaksi: ' . $e->getMessage()];
        }
    }

    /**
     * Mengambil riwayat transaksi user (terpisah sebagai pembeli & penjual)
     */
    public function getUserTransactions(int $userId): array {
        $sql = "SELECT t.*, 
                       i.title AS item_title, 
                       i.image_url AS item_image,
                       i.price AS original_price,
                       c.name AS category_name,
                       buyer.name AS buyer_name,
                       buyer.whatsapp AS buyer_whatsapp,
                       buyer.nim AS buyer_nim,
                       seller.name AS seller_name,
                       seller.whatsapp AS seller_whatsapp,
                       seller.nim AS seller_nim,
                       r.id AS review_id,
                       r.rating AS review_rating
                FROM transactions t
                JOIN items i ON i.id = t.item_id
                JOIN categories c ON c.id = i.category_id
                JOIN users buyer ON buyer.id = t.buyer_id
                JOIN users seller ON seller.id = t.seller_id
                LEFT JOIN reviews r ON r.transaction_id = t.id
                WHERE t.buyer_id = :uid OR t.seller_id = :uid
                ORDER BY t.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        $rows = $stmt->fetchAll();

        $buying = [];
        $selling = [];

        foreach ($rows as $r) {
            if ((int)$r['buyer_id'] === $userId) {
                $buying[] = $r;
            } else {
                $selling[] = $r;
            }
        }

        return ['buying' => $buying, 'selling' => $selling];
    }

    /**
     * Mengambil detail transaksi tunggal
     */
    public function getTransactionById(int $txId): ?array {
        $sql = "SELECT t.*, 
                       i.title AS item_title, 
                       i.image_url AS item_image,
                       seller.name AS seller_name,
                       seller.whatsapp AS seller_whatsapp,
                       buyer.name AS buyer_name,
                       r.id AS review_id
                FROM transactions t
                JOIN items i ON i.id = t.item_id
                JOIN users seller ON seller.id = t.seller_id
                JOIN users buyer ON buyer.id = t.buyer_id
                LEFT JOIN reviews r ON r.transaction_id = t.id
                WHERE t.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $txId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }
}
