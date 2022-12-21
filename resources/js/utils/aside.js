export const createExpandableSidebar = (selector) => {
    const menus = document.querySelectorAll(selector + ' [data-expanded]');
    for (let i = 0; i < menus.length; i++) {
        menus[i].addEventListener('click', (ev) => {

            let target = ev.target.closest('[data-expanded]'),
                    icon = target.querySelector('[data-role="icon"]'),
                    content = target.querySelector('[data-role="content"]');

            if (target.dataset.expanded === 'yes') {
                target.dataset.expanded = 'no';
                icon.classList.remove('rotate-180');
                content.classList.add('h-0');
            } else {
                target.dataset.expanded = 'yes';
                icon.classList.add('rotate-180');
                content.classList.remove('h-0');
            }
        });
    }
};

