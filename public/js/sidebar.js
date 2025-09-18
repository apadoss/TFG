document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".sidebar");
    const sidebarToggle = document.getElementById("sidebarToggle");
    const filtrosCollapse = document.getElementById("filtrosCollapse");

    // ---------------------------
    // SIDEBAR
    // ---------------------------
    const sidebarState = localStorage.getItem("sidebarState");
    if (sidebarState === "open") {
        sidebar.classList.add("active");
    }

    sidebarToggle.addEventListener("click", function () {
        sidebar.classList.toggle("active");
        localStorage.setItem(
            "sidebarState",
            sidebar.classList.contains("active") ? "open" : "closed"
        );
    });

    // Cerrar sidebar con ESC
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            sidebar.classList.remove("active");
            localStorage.setItem("sidebarState", "closed");
        }
    });

    // ---------------------------
    // ACORDEÓN FILTROS
    // ---------------------------
    if (filtrosCollapse) {
        const filtrosState = localStorage.getItem("filtrosState");
        if (filtrosState === "open") {
            filtrosCollapse.classList.add("show");
        } else {
            filtrosCollapse.classList.remove("show");
        }

        // Detectar cambios (cuando se abre/cierra el acordeón)
        filtrosCollapse.addEventListener("shown.bs.collapse", function () {
            localStorage.setItem("filtrosState", "open");
        });

        filtrosCollapse.addEventListener("hidden.bs.collapse", function () {
            localStorage.setItem("filtrosState", "closed");
        });
    }
});