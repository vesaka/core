export default {
    directives: {
        i18n: {
            bind(el, binding, node) {
                const str = node.context.translate(binding.value);
                
                if ('placeholder' === binding.arg) {
                    el.setAttribute('placeholder', str);
                } else {
                    el.innerHTML = str;
                }
            }
        },
    },
    methods: {
        translate(name, params = {}, current = true) {
            let o = this.i18n,
                    s = name.replace(/\[(\w+)\]/g, '.$1').replace(/^\./, ''),
                    a = s.split('.');

            for (var i = 0, n = a.length; i < n; ++i) {
                var k = a[i];
                if (k in o) {
                    o = o[k];
                } else {
                    return current ? this.translate(name, params, false) : '';
                }
            }

            return o.sprintf(params);
        }
    },
    computed: {
        i18n() {
            return this.$store.state.i18n;
        }
    }
}

