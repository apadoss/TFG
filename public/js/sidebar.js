document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
});

// Cerrar sidebar al hacer clic fuera
// document.addEventListener('click', function(event) {
//     const sidebar = document.querySelector('.sidebar');
//     const sidebarToggle = document.getElementById('sidebarToggle');
//     const accordion = document.querySelector('#componentesAccordion, #filtrosAccordion'); //Target both accordions

//     if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && !accordion.contains(event.target)) {
//         sidebar.classList.remove('active');
//     }
// });

// Cerrar sidebar con ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelector('.sidebar').classList.remove('active');
    }
});