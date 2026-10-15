document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    if (!menuToggle || !sidebar) {
        return;
    }

    menuToggle.addEventListener("click", function (event) {

        event.stopPropagation();

        sidebar.classList.toggle("active");

        if (sidebarOverlay) {
            sidebarOverlay.classList.toggle("active");
        }

    });

    document.addEventListener("click", function (event) {

        if (sidebar.classList.contains("active") && !sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove("active");
            
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove("active");
            }
        }
    });

});