document.addEventListener("DOMContentLoaded", function () {
    const userMenuButton = document.getElementById("userMenuButton");
    const userMenuDropdown = document.getElementById("userMenuDropdown");
    const userMenuArrow = document.getElementById("userMenuArrow");
    const userMenuContainer = document.getElementById("userMenuContainer");

    if (userMenuButton && userMenuDropdown) {
        // Toggle saat tombol trigger profil diklik
        userMenuButton.addEventListener("click", function (event) {
            event.stopPropagation();
            const isExpanded = userMenuButton.getAttribute("aria-expanded") === "true";
            
            if (isExpanded) {
                closeUserDropdown();
            } else {
                openUserDropdown();
            }
        });

        // Tutup dropdown jika pengguna mengklik di luar area menu
        document.addEventListener("click", function (event) {
            if (userMenuContainer && !userMenuContainer.contains(event.target)) {
                closeUserDropdown();
            }
        });

        // Aksesibilitas: Tutup dropdown dengan tombol Escape (ESC)
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" || event.key === "Esc") {
                if (userMenuDropdown && !userMenuDropdown.classList.contains("hidden")) {
                    closeUserDropdown();
                    userMenuButton.focus();
                }
            }
        });

        function openUserDropdown() {
            userMenuDropdown.classList.remove("hidden");
            userMenuButton.setAttribute("aria-expanded", "true");
            if (userMenuArrow) {
                userMenuArrow.classList.add("rotate-180");
            }
        }

        function closeUserDropdown() {
            userMenuDropdown.classList.add("hidden");
            userMenuButton.setAttribute("aria-expanded", "false");
            if (userMenuArrow) {
                userMenuArrow.classList.remove("rotate-180");
            }
        }
    }

    // Auto-dismiss Alert Notifikasi Flash
    const flashAlerts = document.querySelectorAll("[data-flash-alert]");
    flashAlerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = "opacity 0.4s ease, transform 0.4s ease";
            alert.style.opacity = "0";
            alert.style.transform = "translateY(-8px)";
            setTimeout(function () {
                alert.remove();
            }, 400);
        }, 5000);
    });
});
