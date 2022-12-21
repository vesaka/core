export default {
    directives: {
        collapsable: {
            mounted(el, binding, vnode) {
                var $ref = vnode.context;
                el.addEventListener('click', (ev) => {

                    let target = ev.target.closest('[data-expanded]'),
                        icon = target.querySelector('[data-role="icon"]'),
                        content = target.querySelector('[data-role="content"]');
                        
                        console.log(target);
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
            },
        },
        hamburger: {
            bind(el, binding, vnode) {
                var $ref = vnode.context,
                        action,
                        classes = ['w-0', 'h-0', 'md:w-full', 'md:h-full'];

                el.addEventListener('click', (ev) => {
                    const $block = document.querySelector('#' + binding.value);
                    if ($block.classList.contains('w-0')) {
                        action = 'remove';
                    } else {
                        action = 'add';
                    }
                    
                    for(let i in classes) {
                        $block.classList[action](classes[i]);
                    }
                });
            }
        }
    }
}
