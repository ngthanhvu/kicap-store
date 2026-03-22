<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loadingSpinner = document.getElementById("loading-spinner");
        if (!loadingSpinner) return;

        function showLoading() {
            loadingSpinner.classList.add("show");
        }

        function hideLoading() {
            loadingSpinner.classList.remove("show");
        }

        function shouldShowLoadingForLink(link) {
            const href = link.getAttribute("href");

            if (!href || href.startsWith("#") || href.startsWith("javascript:")) {
                return false;
            }

            if (link.hasAttribute("download") || link.classList.contains("no-loading") || link.target === "_blank") {
                return false;
            }

            const url = new URL(href, window.location.origin);

            if (url.origin !== window.location.origin) {
                return false;
            }

            return url.href !== window.location.href;
        }

        document.addEventListener("click", function(event) {
            const link = event.target.closest("a");

            if (!link || event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                return;
            }

            if (shouldShowLoadingForLink(link)) {
                showLoading();
            }
        });

        document.addEventListener("submit", function(event) {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || event.defaultPrevented) {
                return;
            }

            if (form.classList.contains("no-loading")) {
                return;
            }

            showLoading();
        });

        window.addEventListener("beforeunload", showLoading);
        window.addEventListener("pageshow", hideLoading);
        window.addEventListener("load", hideLoading);

        // Xử lý toggle menu
        document.querySelectorAll(".menu-toggle").forEach(toggle => {
            toggle.addEventListener("click", function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
                const submenu = this.nextElementSibling; // Lấy submenu ngay sau menu-toggle
                const icon = this.querySelector(".toggle-icon");

                // Toggle class active để hiển thị/ẩn submenu
                submenu.classList.toggle("active");
                icon.classList.toggle("active");
            });
        });
    });
</script>
