document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('sidebarOverlay');
    const sidebars = document.querySelectorAll('.global-sidebar');
    const closeBtns = document.querySelectorAll('.close-sidebar');

    function openSidebar(id) {
        const target = document.getElementById(id);
        if (target) {
            target.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeAllSidebars() {
        sidebars.forEach(sidebar => sidebar.classList.remove('active'));
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Assign triggers to navbar icons
    const triggers = {
        'profileTrigger': 'profileSidebar',
        'messageTrigger': 'messageSidebar',
        'ordersTrigger': 'ordersSidebar',
        'cartTrigger': 'cartSidebar',
        'mProfileTrigger': 'profileSidebar',
        'mCartTrigger': 'cartSidebar'
    };

    Object.keys(triggers).forEach(triggerId => {
        const btn = document.getElementById(triggerId);
        if (btn) {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openSidebar(triggers[triggerId]);
            });
        }
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', closeAllSidebars);
    });

    if (overlay) {
        overlay.addEventListener('click', closeAllSidebars);
    }
});
