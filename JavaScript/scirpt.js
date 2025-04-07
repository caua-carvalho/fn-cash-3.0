document.addEventListener('DOMContentLoaded', () => {
    const sidebarContainer = document.getElementById('sidebar');
    if (sidebarContainer) {
        sidebarContainer.innerHTML = `
            <h2 class="text-center py-3">Menu</h2>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Accounts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Settings</a>
                </li>
            </ul>
        `;
    }
});