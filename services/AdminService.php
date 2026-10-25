<?php
// ============================================================
//  PREVO - Service Administrator & Eksekutif SIM
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

class AdminService {
    private PDO $db;

    public function __construct() {
        $this->db = get_db();
    }

    /**
     * Mengambil ringkasan metrik KPI untuk Dashboard SIM
     */
    public function getDashboardSummary(): array {
        // Total Mahasiswa Terdaftar
        $userCount = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa'")->fetchColumn();

        // Total Listing Aktif (Tersedia)
        $activeItems = (int)$this->db->query("SELECT COUNT(*) FROM items WHERE status = 'tersedia'")->fetchColumn();

        // Listing Menunggu Persetujuan Admin
        $pendingItems = (int)$this->db->query("SELECT COUNT(*) FROM items WHERE status = 'menunggu_persetujuan'")->fetchColumn();

        // Transaksi Selesai & Total Volume COD
        $txStats = $this->db->query("SELECT COUNT(*) AS total_selesai, COALESCE(SUM(agreed_price), 0) AS total_volume FROM transactions WHERE status = 'selesai'")->fetch();

        // Total Laporan Menunggu Ditindak (Status enum: 'pending')
        $pendingReports = (int)$this->db->query("SELECT COUNT(*) FROM reports WHERE status = 'pending'")->fetchColumn();

        // Ringkasan Aktivitas Bulanan (View: monthly_summary)
        $monthly = $this->db->query("SELECT * FROM monthly_summary LIMIT 12")->fetchAll();

        // Statistik Transaksi per Kategori (View: category_stats)
        $categories = $this->db->query("SELECT * FROM category_stats")->fetchAll();

        // Reputasi Penjual Teratas (View: seller_ratings)
        $topSellers = $this->db->query("SELECT * FROM seller_ratings WHERE total_reviews > 0 ORDER BY avg_rating DESC, total_reviews DESC LIMIT 5")->fetchAll();

        return [
            'total_students'   => $userCount,
            'active_items'     => $activeItems,
            'pending_items'    => $pendingItems,
            'completed_tx'     => (int)($txStats['total_selesai'] ?? 0),
            'total_volume'     => (float)($txStats['total_volume'] ?? 0),
            'pending_reports'  => $pendingReports,
            'monthly_summary'  => $monthly,
            'category_stats'   => $categories,
            'top_sellers'      => $topSellers
        ];
    }

    /**
     * Mengambil daftar barang yang menunggu persetujuan (Antrean Moderasi)
     */
    public function getPendingItems(): array {
        $sql = "SELECT i.*, 
                       c.name AS category_name, 
                       u.name AS seller_name, 
                       u.nim AS seller_nim,
                       u.jurusan AS seller_jurusan,
                       u.prodi AS seller_prodi,
                       u.whatsapp AS seller_whatsapp
                FROM items i
                JOIN categories c ON c.id = i.category_id
                JOIN users u ON u.id = i.seller_id
                WHERE i.status = 'menunggu_persetujuan'
                ORDER BY i.created_at ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Menyetujui barang jualan oleh admin
     */
    public function approveItem(int $itemId, int $adminId): array {
        try {
            $stmt = $this->db->prepare("UPDATE items 
                                        SET status = 'tersedia', 
                                            approved_by = :admin_id, 
                                            approved_at = NOW(), 
                                            rejection_reason = NULL,
                                            updated_at = NOW() 
                                        WHERE id = :id AND status = 'menunggu_persetujuan'");
            $stmt->execute([
                'admin_id' => $adminId,
                'id'       => $itemId
            ]);

            if ($stmt->rowCount() > 0) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'Barang tidak ditemukan atau sudah diproses.'];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menyetujui barang: ' . $e->getMessage()];
        }
    }

    /**
     * Menolak barang jualan dengan alasan spesifik
     */
    public function rejectItem(int $itemId, int $adminId, string $reason): array {
        $reason = trim($reason);
        if (empty($reason)) {
            return ['success' => false, 'error' => 'Alasan penolakan wajib diisi untuk edukasi mahasiswa.'];
        }

        try {
            $stmt = $this->db->prepare("UPDATE items 
                                        SET status = 'ditolak', 
                                            approved_by = :admin_id, 
                                            approved_at = NOW(), 
                                            rejection_reason = :reason,
                                            updated_at = NOW() 
                                        WHERE id = :id AND status = 'menunggu_persetujuan'");
            $stmt->execute([
                'admin_id' => $adminId,
                'reason'   => $reason,
                'id'       => $itemId
            ]);

            if ($stmt->rowCount() > 0) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'Barang tidak ditemukan atau sudah diproses.'];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menolak barang: ' . $e->getMessage()];
        }
    }

    /**
     * Mengambil daftar laporan pelanggaran
     */
    public function getReports(?string $statusFilter = null): array {
        $sql = "SELECT r.*, 
                       i.title AS item_title, 
                       i.image_url AS item_image,
                       i.status AS item_status,
                       reporter.name AS reporter_name,
                       reporter.nim AS reporter_nim,
                       seller.name AS seller_name,
                       seller.nim AS seller_nim,
                       admin.name AS handler_name
                FROM reports r
                JOIN items i ON i.id = r.item_id
                JOIN users reporter ON reporter.id = r.reporter_id
                JOIN users seller ON seller.id = i.seller_id
                LEFT JOIN users admin ON admin.id = r.handled_by";

        if ($statusFilter && in_array($statusFilter, ['pending', 'ditangani', 'ditolak'])) {
            $sql .= " WHERE r.status = " . $this->db->quote($statusFilter);
        }

        $sql .= " ORDER BY r.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Menangani laporan pelanggaran
     */
    public function handleReport(int $reportId, int $adminId, string $newStatus, ?string $adminNote, bool $takedownItem = false): array {
        if (!in_array($newStatus, ['ditangani', 'ditolak'])) {
            return ['success' => false, 'error' => 'Status laporan tidak valid.'];
        }

        try {
            $this->db->beginTransaction();

            // Ambil item_id
            $stmt = $this->db->prepare("SELECT item_id FROM reports WHERE id = :id");
            $stmt->execute(['id' => $reportId]);
            $itemId = $stmt->fetchColumn();

            if (!$itemId) {
                $this->db->rollBack();
                return ['success' => false, 'error' => 'Laporan tidak ditemukan.'];
            }

            // Update laporan
            $updateReport = $this->db->prepare("UPDATE reports 
                                                SET status = :status, 
                                                    handled_by = :admin_id, 
                                                    admin_note = :note,
                                                    handled_at = NOW(),
                                                    updated_at = NOW() 
                                                WHERE id = :id");
            $updateReport->execute([
                'status'   => $newStatus,
                'admin_id' => $adminId,
                'note'     => $adminNote ?: null,
                'id'       => $reportId
            ]);

            // Jika pilih takedown item
            if ($takedownItem) {
                $takedown = $this->db->prepare("UPDATE items SET status = 'dihapus', updated_at = NOW() WHERE id = :item_id");
                $takedown->execute(['item_id' => $itemId]);
            }

            $this->db->commit();
            return ['success' => true];
        } catch (PDOException $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => 'Gagal memproses laporan: ' . $e->getMessage()];
        }
    }

    /**
     * Mengambil daftar master kategori beserta jumlah item
     */
    public function getCategories(): array {
        $sql = "SELECT c.*, COUNT(i.id) AS total_items
                FROM categories c
                LEFT JOIN items i ON i.category_id = c.id
                GROUP BY c.id
                ORDER BY c.name ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Menambah kategori baru
     */
    public function addCategory(string $name, ?string $description = null, ?string $icon = null): array {
        $name = trim($name);
        if (empty($name)) {
            return ['success' => false, 'error' => 'Nama kategori wajib diisi.'];
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO categories (name, description, icon) VALUES (:name, :description, :icon)");
            $stmt->execute([
                'name'        => $name,
                'description' => trim($description ?? '') ?: null,
                'icon'        => trim($icon ?? '') ?: null
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menambah kategori (mungkin nama sudah ada): ' . $e->getMessage()];
        }
    }

    /**
     * Memperbarui kategori
     */
    public function updateCategory(int $id, string $name, ?string $description = null, ?string $icon = null): array {
        $name = trim($name);
        if (empty($name)) {
            return ['success' => false, 'error' => 'Nama kategori wajib diisi.'];
        }

        try {
            $stmt = $this->db->prepare("UPDATE categories SET name = :name, description = :description, icon = :icon WHERE id = :id");
            $stmt->execute([
                'name'        => $name,
                'description' => trim($description ?? '') ?: null,
                'icon'        => trim($icon ?? '') ?: null,
                'id'          => $id
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal mengubah kategori: ' . $e->getMessage()];
        }
    }

    /**
     * Menghapus kategori (hanya jika belum ada barang)
     */
    public function deleteCategory(int $id): array {
        $count = (int)$this->db->query("SELECT COUNT(*) FROM items WHERE category_id = {$id}")->fetchColumn();
        if ($count > 0) {
            return ['success' => false, 'error' => "Kategori tidak dapat dihapus karena masih digunakan oleh {$count} barang."];
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal menghapus kategori: ' . $e->getMessage()];
        }
    }
}
