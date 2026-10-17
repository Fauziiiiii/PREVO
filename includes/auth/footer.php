<?php
?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButtons = document.querySelectorAll(".toggle-password-btn, #toggle-password, #togglePasswordBtn");
            toggleButtons.forEach(btn => {
                btn.addEventListener("click", function () {
                    const targetId = this.getAttribute("data-target") || "password";
                    const input = document.getElementById(targetId) || this.parentElement.querySelector("input");
                    const icon = this.querySelector(".material-symbols-outlined") || document.getElementById("eye-icon");
                    const eyeSvg = document.getElementById("eyeIcon") || this.querySelector("svg");

                    if (input) {
                        const isPassword = input.getAttribute("type") === "password";
                        input.setAttribute("type", isPassword ? "text" : "password");

                        if (icon) {
                            icon.textContent = isPassword ? "visibility_off" : "visibility";
                        }

                        if (eyeSvg) {
                            if (isPassword) {
                                eyeSvg.innerHTML = `
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                `;
                            } else {
                                eyeSvg.innerHTML = `
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                `;
                            }
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
