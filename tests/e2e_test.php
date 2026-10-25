<?php
// ============================================================
//  PREVO - Comprehensive End-to-End Test Suite
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../services/ItemService.php';
require_once __DIR__ . '/../services/TransactionService.php';
require_once __DIR__ . '/../services/ReviewService.php';
require_once __DIR__ . '/../services/AdminService.php';

echo "====================================================\n";
echo "   PREVO END-TO-END AUTOMATED VERIFICATION SUITE    \n";
echo "====================================================\n\n";

$db = get_db();
$authService = new AuthService();
$itemService = new ItemService();
$txService = new TransactionService();
$reviewService = new ReviewService();
$adminService = new AdminService();

$passed = 0;
$failed = 0;

function assert_test(string $name, bool $condition, string $details = '') {
    global $passed, $failed;
    if ($condition) {
        echo "[PASS] " . $name . "\n";
        $passed++;
    } else {
        echo "[FAIL] " . $name . ($details ? " (" . $details . ")" : "") . "\n";
        $failed++;
    }
}

// -----------------------------------------------------------------
// 1. UJI AUTENTIKASI & VERIFIKASI 2-TAHAP
// -----------------------------------------------------------------
echo "--- 1. Testing Auth & 2-Step Verification ---\n";

// Register akun mahasiswa baru untuk testing E2E
$testNim = '254107020999';
$testEmail = 'test_e2e_student@student.polinema.ac.id';
$testWa = '081299998888';

// Bersihkan data lama jika ada
$oldUserIds = $db->query("SELECT id FROM users WHERE nim = '{$testNim}' OR email = '{$testEmail}'")->fetchAll(PDO::FETCH_COLUMN);
if (!empty($oldUserIds)) {
    $idList = implode(',', array_map('intval', $oldUserIds));
    $db->exec("DELETE FROM reviews WHERE seller_id IN ({$idList}) OR reviewer_id IN ({$idList})");
    $db->exec("DELETE FROM transactions WHERE seller_id IN ({$idList}) OR buyer_id IN ({$idList})");
    $db->exec("DELETE FROM favorites WHERE user_id IN ({$idList})");
    $db->exec("DELETE FROM reports WHERE reporter_id IN ({$idList})");
    $db->exec("DELETE FROM items WHERE seller_id IN ({$idList})");
    $db->exec("DELETE FROM users WHERE id IN ({$idList})");
}

$regRes = $authService->register([
    'name'      => 'Siswa Penguji E2E',
    'email'     => $testEmail,
    'password'  => 'Test@12345',
    'nim'       => $testNim,
    'whatsapp'  => $testWa,
    'jurusan'   => 'Teknologi Informasi',
    'prodi'     => 'D-IV Teknik Informatika'
]);

assert_test("1.1 Registrasi mahasiswa baru", $regRes['success'], $regRes['error'] ?? '');

// Ambil user baru dari DB
$newUser = $authService->getUserByEmail($testEmail);
assert_test("1.2 User tersimpan dengan token email dan OTP WA", 
    !empty($newUser['verification_token']) && !empty($newUser['whatsapp_otp'])
);

// Verifikasi email via token
$verifyEmailRes = $authService->verifyEmailToken($newUser['verification_token']);
assert_test("1.3 Verifikasi email kampus via token", $verifyEmailRes['success'], $verifyEmailRes['error'] ?? '');

// Login sebelum verifikasi WA
$loginRes = $authService->login($testEmail, 'Test@12345');
assert_test("1.4 Login berhasil pasca verifikasi email", $loginRes['success'], $loginRes['error'] ?? '');
$loggedUser = $authService->getUserByEmail($testEmail);
assert_test("1.5 Status WA belum terverifikasi saat baru login", empty($loggedUser['whatsapp_verified_at']));

// Verifikasi WhatsApp dengan OTP
$otpRes = $authService->verifyWhatsAppOtp((int)$loggedUser['id'], $newUser['whatsapp_otp']);
assert_test("1.6 Verifikasi WhatsApp dengan OTP 6-digit", $otpRes['success'], $otpRes['error'] ?? '');

$verifiedUser = $authService->getUserById((int)$loggedUser['id']);
assert_test("1.7 User kini berstatus aktif penuh (Email & WA verified)", 
    !empty($verifiedUser['email_verified_at']) && !empty($verifiedUser['whatsapp_verified_at'])
);

// -----------------------------------------------------------------
// 2. UJI PASANG BARANG & MODERASI ADMIN
// -----------------------------------------------------------------
echo "\n--- 2. Testing Listing Submission & Admin Moderation ---\n";

// Mahasiswa memasang barang jualan
$catElektronik = (int)$db->query("SELECT id FROM categories WHERE name ILIKE 'Elektronik' LIMIT 1")->fetchColumn();

$createItemRes = $itemService->createItem((int)$verifiedUser['id'], [
    'category_id'   => $catElektronik,
    'title'         => 'Arduino Uno R3 Starter Kit Asli',
    'description'   => 'Kit praktikum mikrokontroler lengkap dengan sensor dan kabel jumper.',
    'price'         => 175000,
    'condition'     => 'bekas_baik',
    'location_info' => 'Gedung TI Lantai 3',
    'image_url'     => 'sample-arduino.jpg'
]);

assert_test("2.1 Mahasiswa berhasil pasang barang jualan", $createItemRes['success'], $createItemRes['error'] ?? '');
$itemId = $createItemRes['item_id'] ?? 0;

// Cek status barang baru harus 'menunggu_persetujuan'
$itemCheck = $itemService->getItemDetail($itemId);
assert_test("2.2 Status barang baru adalah 'menunggu_persetujuan'", $itemCheck['status'] === 'menunggu_persetujuan');

// Pastikan barang BELUM muncul di katalog publik
$catalogPublic = $itemService->getCatalogItems(['q' => 'Arduino Uno R3']);
$foundInPublic = false;
foreach ($catalogPublic as $it) {
    if ((int)$it['id'] === $itemId) { $foundInPublic = true; break; }
}
assert_test("2.3 Barang belum muncul di katalog publik sebelum disetujui", !$foundInPublic);

// Cek antrean moderasi admin
$adminUser = $authService->getUserByEmail('admin@prevo.polinema.ac.id');
$pendingList = $adminService->getPendingItems();
$inPending = false;
foreach ($pendingList as $p) {
    if ((int)$p['id'] === $itemId) { $inPending = true; break; }
}
assert_test("2.4 Barang terdaftar di antrean moderasi admin", $inPending);

// Admin menyetujui barang
$approveRes = $adminService->approveItem($itemId, (int)$adminUser['id']);
assert_test("2.5 Admin menyetujui listing barang", $approveRes['success'], $approveRes['error'] ?? '');

$itemApproved = $itemService->getItemDetail($itemId);
assert_test("2.6 Status barang kini 'tersedia' dan tertera approved_by", 
    $itemApproved['status'] === 'tersedia' && (int)$itemApproved['approved_by'] === (int)$adminUser['id']
);

// Pastikan SEKARANG muncul di katalog publik
$catalogApproved = $itemService->getCatalogItems(['q' => 'Arduino Uno R3']);
$foundNow = false;
foreach ($catalogApproved as $it) {
    if ((int)$it['id'] === $itemId) { $foundNow = true; break; }
}
assert_test("2.7 Barang kini muncul di katalog pencarian publik", $foundNow);

// -----------------------------------------------------------------
// 3. UJI BOOKING 1-KLIK & ROW LOCK
// -----------------------------------------------------------------
echo "\n--- 3. Testing 1-Click Booking & Transaction Lifecycle ---\n";

// Ambil buyer lain: Fauzi (ID 2)
$buyerFauzi = $authService->getUserByEmail('fauzi@student.polinema.ac.id');

// Penjual coba booking barang sendiri (harus dicegah oleh SP)
$selfBookRes = $txService->createBooking($itemId, (int)$verifiedUser['id']);
assert_test("3.1 Penjual dicegah membooking barang milik sendiri", !$selfBookRes['success']);

// Buyer Fauzi membooking barang (SP create_booking)
$bookingRes = $txService->createBooking($itemId, (int)$buyerFauzi['id']);
assert_test("3.2 Pembeli berhasil membooking barang (SP create_booking)", $bookingRes['success'], $bookingRes['error'] ?? '');

// Cek status barang otomatis berubah jadi 'booking'
$itemBooked = $itemService->getItemDetail($itemId);
assert_test("3.3 Status item otomatis berubah ke 'booking' via trigger", $itemBooked['status'] === 'booking');

// Pembeli lain coba booking barang yang sama saat status 'booking' (harus gagal)
$otherBuyer = $authService->getUserByEmail('reyhandhika@student.polinema.ac.id');
$doubleBookRes = $txService->createBooking($itemId, (int)$otherBuyer['id']);
assert_test("3.4 Pembeli lain dicegah membooking barang yang sedang di-hold", !$doubleBookRes['success']);

// Ambil transaksi yang baru dibuat
$txRow = $db->query("SELECT * FROM transactions WHERE item_id = {$itemId} AND status = 'pending' ORDER BY id DESC LIMIT 1")->fetch();
assert_test("3.5 Record transaksi dibuat dengan status 'pending'", !empty($txRow));
$txId = (int)$txRow['id'];

// -----------------------------------------------------------------
// 4. UJI UPDATE DEAL & KESEPAKATAN COD
// -----------------------------------------------------------------
echo "\n--- 4. Testing WhatsApp Deal Update & COD Settlement ---\n";

// Penjual mengupdate harga kesepakatan nego WhatsApp
$updateDealRes = $txService->updateBookingDeal($txId, (int)$verifiedUser['id'], 160000, 'Kantin Graha Polinema', '2026-10-27 12:00:00');
assert_test("4.1 Penjual berhasil update harga kesepakatan dan lokasi COD", $updateDealRes['success'], $updateDealRes['error'] ?? '');

$txUpdated = $txService->getTransactionById($txId);
assert_test("4.2 Transaksi merekam agreed_price Rp 160.000 dan lokasi", 
    (float)$txUpdated['agreed_price'] === 160000.0 && $txUpdated['meetup_location'] === 'Kantin Graha Polinema'
);

// Selesaikan transaksi COD (SP complete_transaction)
$completeRes = $txService->completeTransaction($txId, (int)$buyerFauzi['id']);
assert_test("4.3 Konfirmasi COD selesai dieksekusi", $completeRes['success'], $completeRes['error'] ?? '');

// Cek status transaksi dan barang
$txCompleted = $txService->getTransactionById($txId);
$itemSold = $itemService->getItemDetail($itemId);
assert_test("4.4 Transaksi berstatus 'selesai' dan item berstatus 'terjual'", 
    $txCompleted['status'] === 'selesai' && $itemSold['status'] === 'terjual'
);

// -----------------------------------------------------------------
// 5. UJI ULASAN BINTANG & VIEW SIM
// -----------------------------------------------------------------
echo "\n--- 5. Testing Review & SIM Management Views ---\n";

// Pembeli memberi rating 5 bintang dan testimoni
$reviewRes = $reviewService->addReview($txId, (int)$buyerFauzi['id'], 5, 'Barang sangat bagus, fungsi normal semua, penjual ramah dan tepat waktu COD!');
assert_test("5.1 Pembeli berhasil memberikan ulasan bintang 5", $reviewRes['success'], $reviewRes['error'] ?? '');

// Pastikan tidak bisa mengulas 2 kali
$doubleReviewRes = $reviewService->addReview($txId, (int)$buyerFauzi['id'], 4, 'Coba ulas lagi');
assert_test("5.2 Transaksi yang sudah diulas dicegah dari duplikasi ulasan", !$doubleReviewRes['success']);

// Cek View: seller_ratings
$ratingRow = $db->query("SELECT * FROM seller_ratings WHERE seller_id = {$verifiedUser['id']}")->fetch();
assert_test("5.3 View seller_ratings mengagregasi rating penjual dengan benar", 
    !empty($ratingRow) && (int)$ratingRow['total_reviews'] >= 1 && (float)$ratingRow['avg_rating'] == 5.0
);

// Cek View: monthly_summary
$monthlyRows = $db->query("SELECT * FROM monthly_summary")->fetchAll();
assert_test("5.4 View monthly_summary merekam transaksi bulanan", count($monthlyRows) > 0);

// Cek View: category_stats
$catStatsRows = $db->query("SELECT * FROM category_stats WHERE category_id = {$catElektronik}")->fetch();
assert_test("5.5 View category_stats mengagregasi penjualan kategori elektronik", 
    !empty($catStatsRows) && (int)$catStatsRows['completed_transactions'] >= 1
);

// -----------------------------------------------------------------
// 6. UJI SIKLUS PEMBATALAN TRANSAKSI & TRIGGER RESET ITEM
// -----------------------------------------------------------------
echo "\n--- 6. Testing Cancellation & Auto-Reset Trigger ---\n";

// Buat barang kedua untuk diuji batal
$createItem2Res = $itemService->createItem((int)$verifiedUser['id'], [
    'category_id'   => $catElektronik,
    'title'         => 'Multimeter Digital DT-830B',
    'description'   => 'Alat ukur tegangan dan arus listrik praktikum.',
    'price'         => 45000,
    'condition'     => 'bekas_baik',
    'location_info' => 'Kantin Gedung Sipil'
]);
$item2Id = $createItem2Res['item_id'];
$adminService->approveItem($item2Id, (int)$adminUser['id']);

// Booking barang kedua
$txService->createBooking($item2Id, (int)$buyerFauzi['id']);
$item2Booked = $itemService->getItemDetail($item2Id);
assert_test("6.1 Barang kedua berstatus 'booking'", $item2Booked['status'] === 'booking');

// Ambil transaksi barang kedua
$tx2 = $db->query("SELECT id FROM transactions WHERE item_id = {$item2Id} AND status = 'pending' LIMIT 1")->fetch();

// Batalkan transaksi
$cancelRes = $txService->cancelTransaction((int)$tx2['id'], (int)$buyerFauzi['id'], 'Tidak jadi butuh alat ini.');
assert_test("6.2 Pembeli membatalkan transaksi pending", $cancelRes['success'], $cancelRes['error'] ?? '');

$item2Reset = $itemService->getItemDetail($item2Id);
assert_test("6.3 Trigger otomatis mengembalikan status barang kedua menjadi 'tersedia'", 
    $item2Reset['status'] === 'tersedia'
);

// -----------------------------------------------------------------
// HASIL AKHIR
// -----------------------------------------------------------------
echo "\n====================================================\n";
echo "TEST RESULTS SUMMARY: " . $passed . " PASSED, " . $failed . " FAILED\n";
echo "====================================================\n";

if ($failed === 0) {
    echo "🎉 ALL TESTS PASSED! PREVO FULL-STACK REBUILD IS 100% OPERATIONAL.\n";
} else {
    echo "⚠️ SOME TESTS FAILED. PLEASE REVIEW LOG ABOVE.\n";
}
