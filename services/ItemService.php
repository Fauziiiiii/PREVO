<?php
// ============================================================
//  PREVO - Service Pengelolaan & Katalog Barang
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

class ItemService {
    private PDO $db;

    public function __construct() {
        $this->db = get_db();
    }

    /**
     * Mengambil daftar barang publik untuk katalog (hanya status 'tersedia' dan 'booking')
     */
    public function getCatalogItems(array $filters = []): array {
        $sql = "SELECT i.*, 
                       c.name AS category_name, 
                       u.name AS seller_name, 
                       u.nim  AS seller_nim,
                       u.prodi AS seller_prodi,
                       COALESCE(sr.avg_rating, 5.0) AS avg_rating,
                       COALESCE(sr.total_reviews, 0) AS total_reviews
                FROM items i
                JOIN categories c ON c.id = i.category_id
                JOIN users u      ON u.id = i.seller_id
                LEFT JOIN seller_ratings sr ON sr.seller_id = u.id
                WHERE i.status IN ('tersedia', 'booking')";

        $params = [];

        // Filter Kategori
        if (!empty($filters['category_id']) && is_numeric($filters['category_id'])) {
            $sql .= " AND i.category_id = :cat_id";
            $params['cat_id'] = (int)$filters['category_id'];
        }

        // Filter Kondisi
        if (!empty($filters['condition']) && in_array($filters['condition'], ['baru', 'bekas_baik', 'bekas_rusak'])) {
            $sql .= " AND i.condition = :condition";
            $params['condition'] = $filters['condition'];
        }

        // Filter Pencarian Teks (FTS)
        if (!empty($filters['q'])) {
            $term = trim($filters['q']);
            $sql .= " AND (
                to_tsvector('simple', i.title || ' ' || i.description) @@ plainto_tsquery('simple', :query_fts)
                OR i.title ILIKE :query_like
                OR c.name ILIKE :query_like
                OR i.location_info ILIKE :query_like
            )";
            $params['query_fts'] = $term;
            $params['query_like'] = '%' . $term . '%';
        }

        // Pengurutan
        $sort = $filters['sort'] ?? 'terbaru';
        if ($sort === 'termurah') {
            $sql .= " ORDER BY (i.status = 'tersedia') DESC, i.price ASC, i.created_at DESC";
        } elseif ($sort === 'termahal') {
            $sql .= " ORDER BY (i.status = 'tersedia') DESC, i.price DESC, i.created_at DESC";
        } else {
            $sql .= " ORDER BY (i.status = 'tersedia') DESC, i.created_at DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil detail satu barang secara lengkap
     */
    public function getItemDetail(int $itemId): ?array {
        $sql = "SELECT i.*, 
                       c.name AS category_name, 
                       u.id   AS seller_id,
                       u.name AS seller_name, 
                       u.nim  AS seller_nim,
                       u.jurusan AS seller_jurusan,
                       u.prodi AS seller_prodi,
                       u.whatsapp AS seller_whatsapp,
                       u.instagram AS seller_instagram,
                       u.telegram AS seller_telegram,
                       COALESCE(sr.avg_rating, 5.0) AS avg_rating,
                       COALESCE(sr.total_reviews, 0) AS total_reviews
                FROM items i
                JOIN categories c ON c.id = i.category_id
                JOIN users u      ON u.id = i.seller_id
                LEFT JOIN seller_ratings sr ON sr.seller_id = u.id
                WHERE i.id = :id AND i.status <> 'dihapus'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $itemId]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    /**
     * Mengambil seluruh master kategori
     */
    public function getCategories(): array {
        $stmt = $this->db->query("SELECT c.*, COUNT(i.id) FILTER (WHERE i.status = 'tersedia') AS active_count
                                  FROM categories c
                                  LEFT JOIN items i ON i.category_id = c.id
                                  GROUP BY c.id
                                  ORDER BY c.name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Memeriksa apakah barang di-bookmark pengguna
     */
    public function isFavorited(int $userId, int $itemId): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM favorites WHERE user_id = :uid AND item_id = :iid");
        $stmt->execute(['uid' => $userId, 'iid' => $itemId]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Toggle bookmark barang
     */
    public function toggleFavorite(int $userId, int $itemId): bool {
        if ($this->isFavorited($userId, $itemId)) {
            $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = :uid AND item_id = :iid");
            $stmt->execute(['uid' => $userId, 'iid' => $itemId]);
            return false; // Dihapus dari favorit
        } else {
            $stmt = $this->db->prepare("INSERT INTO favorites (user_id, item_id) VALUES (:uid, :iid) ON CONFLICT DO NOTHING");
            $stmt->execute(['uid' => $userId, 'iid' => $itemId]);
            return true; // Ditambahkan ke favorit
        }
    }

    /**
     * Mengambil daftar barang favorit user
     */
    public function getUserFavorites(int $userId): array {
        $sql = "SELECT i.*, 
                       c.name AS category_name, 
                       u.name AS seller_name, 
                       COALESCE(sr.avg_rating, 5.0) AS avg_rating
                FROM favorites f
                JOIN items i ON i.id = f.item_id
                JOIN categories c ON c.id = i.category_id
                JOIN users u ON u.id = i.seller_id
                LEFT JOIN seller_ratings sr ON sr.seller_id = u.id
                WHERE f.user_id = :uid AND i.status <> 'dihapus'
                ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Pelaporan barang pelanggaran
     */
    public function reportItem(int $reporterId, int $itemId, string $reason, string $description): array {
        if (empty($reason)) {
            return ['success' => false, 'error' => 'Alasan pelaporan wajib dipilih.'];
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO reports (reporter_id, item_id, reason, description, status)
                                        VALUES (:rid, :iid, :reason, :desc, 'pending')
                                        ON CONFLICT (reporter_id, item_id) 
                                        DO UPDATE SET reason = EXCLUDED.reason, description = EXCLUDED.description, updated_at = NOW()");
            $stmt->execute([
                'rid'    => $reporterId,
                'iid'    => $itemId,
                'reason' => $reason,
                'desc'   => $description
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal mengirim laporan: ' . $e->getMessage()];
        }
    }

    /**
     * Mengambil seluruh listing milik penjual tertentu (termasuk pending & ditolak)
     */
    public function getSellerItems(int $sellerId): array {
        $sql = "SELECT i.*, c.name AS category_name
                FROM items i
                JOIN categories c ON c.id = i.category_id
                WHERE i.seller_id = :sid AND i.status <> 'dihapus'
                ORDER BY i.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['sid' => $sellerId]);
        return $stmt->fetchAll();
    }

    /**
     * Menambah listing barang baru (status awal: 'menunggu_persetujuan')
     */
    public function createItem(int $sellerId, array $data, ?array $file = null): array {
        $title = trim($data['title'] ?? '');
        $categoryId = (int)($data['category_id'] ?? 0);
        $price = (float)($data['price'] ?? 0);
        $condition = $data['condition'] ?? '';
        $description = trim($data['description'] ?? '');
        $locationInfo = trim($data['location_info'] ?? '');

        if (empty($title) || empty($categoryId) || $price < 0 || empty($condition) || empty($description)) {
            return ['success' => false, 'error' => 'Mohon lengkapi seluruh informasi barang dengan benar.'];
        }

        if (!in_array($condition, ['baru', 'bekas_baik', 'bekas_rusak'])) {
            return ['success' => false, 'error' => 'Kondisi barang tidak valid.'];
        }

        $imageUrl = null;
        if (!empty($file) && !empty($file['tmp_name'])) {
            $uploadRes = upload_item_photo($file);
            if (!$uploadRes['success']) {
                return ['success' => false, 'error' => $uploadRes['error']];
            }
            $imageUrl = $uploadRes['path'];
        }

        $sql = "INSERT INTO items (
                    seller_id, category_id, title, description, price, condition, 
                    location_info, image_url, status
                ) VALUES (
                    :sid, :cid, :title, :desc, :price, :cond, 
                    :loc, :img, 'menunggu_persetujuan'
                ) RETURNING id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'sid'   => $sellerId,
                'cid'   => $categoryId,
                'title' => $title,
                'desc'  => $description,
                'price' => $price,
                'cond'  => $condition,
                'loc'   => $locationInfo,
                'img'   => $imageUrl
            ]);
            $itemId = $stmt->fetchColumn();
            return ['success' => true, 'item_id' => $itemId];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menyimpan barang: ' . $e->getMessage()];
        }
    }

    /**
     * Memperbarui informasi barang
     */
    public function updateItem(int $itemId, int $sellerId, array $data, ?array $file = null): array {
        $current = $this->getItemDetail($itemId);
        if (!$current || (int)$current['seller_id'] !== $sellerId) {
            return ['success' => false, 'error' => 'Anda tidak memiliki hak akses untuk mengedit barang ini.'];
        }

        if ($current['status'] === 'terjual' || $current['status'] === 'dihapus') {
            return ['success' => false, 'error' => 'Barang yang sudah terjual atau dihapus tidak dapat diubah.'];
        }

        $title = trim($data['title'] ?? '');
        $categoryId = (int)($data['category_id'] ?? 0);
        $price = (float)($data['price'] ?? 0);
        $condition = $data['condition'] ?? '';
        $description = trim($data['description'] ?? '');
        $locationInfo = trim($data['location_info'] ?? '');

        if (empty($title) || empty($categoryId) || $price < 0 || empty($condition) || empty($description)) {
            return ['success' => false, 'error' => 'Mohon lengkapi seluruh kolom wajib.'];
        }

        $imageUrl = $current['image_url'];
        if (!empty($file) && !empty($file['tmp_name'])) {
            $uploadRes = upload_item_photo($file);
            if (!$uploadRes['success']) {
                return ['success' => false, 'error' => $uploadRes['error']];
            }
            $imageUrl = $uploadRes['path'];
        }

        // State machine: Jika sebelumnya 'ditolak', kembalikan ke 'menunggu_persetujuan' dan bersihkan alasan penolakan
        $newStatus = $current['status'];
        $rejectionReason = $current['rejection_reason'];
        if ($current['status'] === 'ditolak') {
            $newStatus = 'menunggu_persetujuan';
            $rejectionReason = null;
        }

        $sql = "UPDATE items 
                SET title = :title, 
                    category_id = :cid, 
                    price = :price, 
                    condition = :cond, 
                    description = :desc, 
                    location_info = :loc, 
                    image_url = :img, 
                    status = :status, 
                    rejection_reason = :rej,
                    updated_at = NOW()
                WHERE id = :id AND seller_id = :sid";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'title'  => $title,
                'cid'    => $categoryId,
                'price'  => $price,
                'cond'   => $condition,
                'desc'   => $description,
                'loc'    => $locationInfo,
                'img'    => $imageUrl,
                'status' => $newStatus,
                'rej'    => $rejectionReason,
                'id'     => $itemId,
                'sid'    => $sellerId
            ]);
            return ['success' => true, 'new_status' => $newStatus];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal memperbarui barang: ' . $e->getMessage()];
        }
    }

    /**
     * Soft delete barang (status diubah ke 'dihapus')
     */
    public function deleteItem(int $itemId, int $sellerId): array {
        $current = $this->getItemDetail($itemId);
        if (!$current || (int)$current['seller_id'] !== $sellerId) {
            return ['success' => false, 'error' => 'Anda tidak memiliki hak akses untuk menghapus barang ini.'];
        }

        if ($current['status'] === 'booking') {
            return ['success' => false, 'error' => 'Barang yang sedang dalam proses booking tidak dapat dihapus. Batalkan booking terlebih dahulu.'];
        }

        try {
            $stmt = $this->db->prepare("UPDATE items SET status = 'dihapus', updated_at = NOW() WHERE id = :id AND seller_id = :sid");
            $stmt->execute(['id' => $itemId, 'sid' => $sellerId]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menghapus barang: ' . $e->getMessage()];
        }
    }
}
