// Display Current Date
document.getElementById('current-date').textContent = new Date().toDateString();

// Simple Sidebar Toggle (Optional for mobile)
const navItems = document.querySelectorAll('.nav-links li');
navItems.forEach(item => {
    item.addEventListener('click', function() {
        navItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});