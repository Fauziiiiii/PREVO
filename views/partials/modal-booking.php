<!-- Modal Konfirmasi Booking 1-Klik -->
<div id="bookingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="text-xl">🔒</span>
                <h3 class="font-bold text-slate-950 text-base">Konfirmasi Booking 1-Klik</h3>
            </div>
            <button type="button" onclick="closeBookingModal()" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
        </div>

        <div class="py-4">
            <p class="text-sm text-slate-600 leading-relaxed">
                Anda akan mengunci barang <strong id="modalItemTitle" class="text-slate-900">-</strong> seharga <strong id="modalItemPrice" class="text-emerald-700">-</strong>.
            </p>

            <div class="mt-3 bg-emerald-50 border border-emerald-200/80 rounded-xl p-3 text-xs text-emerald-900 leading-relaxed">
                <div class="font-bold mb-1 flex items-center gap-1">
                    <span>⏱️</span> Masa Booking Aktif 24 Jam
                </div>
                Barang akan diamankan di database dari pembeli lain. Setelah booking dikonfirmasi, <strong>kontak WhatsApp penjual akan langsung dibuka</strong> agar Anda dapat janjian titik temu COD di kampus.
            </div>
        </div>

        <form id="modalBookingForm" action="<?= BASE_URL ?>/transaksi/proses_booking.php" method="POST" class="mt-2 flex gap-2">
            <?= csrf_field() ?>
            <input type="hidden" name="item_id" id="modalItemId" value="">

            <button type="button" onclick="closeBookingModal()" 
                    class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">
                Batal
            </button>
            <button type="submit" 
                    class="flex-1 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-sm transition-all">
                Kunci & Buka WA
            </button>
        </form>

    </div>
</div>

<script>
function openBookingModal(itemId, title, priceFormatted) {
    document.getElementById('modalItemId').value = itemId;
    document.getElementById('modalItemTitle').textContent = title;
    document.getElementById('modalItemPrice').textContent = priceFormatted;
    document.getElementById('bookingModal').classList.remove('hidden');
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}
</script>
