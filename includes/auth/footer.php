<?php
?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButtons = document.querySelectorAll(".toggle-password-btn, #toggle-password");
            toggleButtons.forEach(btn => {
                btn.addEventListener("click", function () {
                    const targetId = this.getAttribute("data-target") || "password";
                    const input = document.getElementById(targetId) || this.parentElement.querySelector("input");
                    const icon = this.querySelector(".material-symbols-outlined") || document.getElementById("eye-icon");

                    if (input) {
                        const isPassword = input.getAttribute("type") === "password";
                        input.setAttribute("type", isPassword ? "text" : "password");
                        if (icon) {
                            icon.textContent = isPassword ? "visibility_off" : "visibility";
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
