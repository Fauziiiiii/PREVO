  </main>

  <footer class="bg-surface-container-low dark:bg-surface-container-low border-t border-outline-variant dark:border-outline-variant mt-space-xl">
    <div class="w-full max-w-7xl mx-auto px-gutter-desktop py-space-xl flex flex-col md:flex-row items-center justify-between gap-space-md">
      <!-- Brand Logo & Copyright -->
      <div class="flex flex-col items-center md:items-start gap-1">
        <div class="font-headline-md text-headline-md font-bold text-primary dark:text-primary flex items-center gap-2">
          <span class="material-symbols-outlined text-[20px]" data-icon="shopping_bag">shopping_bag</span>
          <span>PREVO</span>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant dark:text-on-surface-variant text-center md:text-left">
          © <?= date('Y'); ?> PREVO Polinema. Marketplace Preloved Mahasiswa Terverifikasi Politeknik Negeri Malang.
        </p>
      </div>
      <!-- Links Cluster -->
      <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
        <a class="text-on-surface-variant dark:text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors" href="#">Syarat &amp; Ketentuan</a>
        <a class="text-on-surface-variant dark:text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
        <a class="text-on-surface-variant dark:text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors" href="#">Panduan COD Kampus</a>
        <a class="text-on-surface-variant dark:text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors" href="#">Pusat Bantuan Mahasiswa</a>
      </div>
    </div>
  </footer>

  <!-- Script Aplikasi -->
  <script src="<?= BASE_URL; ?>assets/js/app.js"></script>
</body>

</html>
