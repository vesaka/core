/* global Function */
import Alert from '@core/components/global/Alert.vue';
import I18nMixin from './i18n-mixin';
export default {
    data() {
        return {
            errorBag: {},
            errors: {},
            rules: {},
            inputs: {},
            form: null,
            key: 0,
            valid: true
        };
    },
    props: {
        model: {
            type: Object,
            default() {
                return {};
            }
        },
        errorReferenceName: {
            type: Function,
            default() {
                return function (name) {
                    return `error_${name}`;
                };
            }
        },
        specialRules: {
            type: Array,
            default() {
                return ['raw', 'bail', 'file'];
            }

        },
        bail: {
            type: Boolean,
            default: false
        },
        depth: {
            type: Number,
            default: 4
        },
        validated: {
            type: Function,
            default() {
                return () => {
                };
            }
        },
        regexp: {
            type: Object,
            default() {
                return  {
                    rule: /^(.+?)\[(.+)\]$/,
                    numeric: /^[0-9]+$/,
                    integer: /^\-?[0-9]+$/,
                    decimal: /^\-?[0-9]*\.?[0-9]+$/,
                    email: /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
                    alpha: /^[a-z]+$/i,
                    alphaNumeric: /^[a-z0-9]+$/i,
                    alphaDash: /^[a-z0-9_\-\s]+$/i,
                    alphaDashCyrilic: /^[a-zа-я0-9_\-\s\.,]+$/i,
                    natural: /^[0-9]+$/i,
                    naturalNoZero: /^[1-9][0-9]*$/i,
                    ip: /^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$/i,
                    base64: /[^a-zA-Z0-9\/\+=]/i,
                    numericDash: /^[\d\-\s]+$/,
                    greek: /^[a-zA-Z0-9Ά-ωΑ-ώ\s]+$/i,
                    phoneNumber: /^([99|96|22])(\s{1})([0-9]{3})(\s{1})([0-9]{3})$/,
                    url: /^((http|https):\/\/(\w+:{0,1}\w*@)?(\S+)|)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/,
                    date: /(^(((0[1-9]|1[0-9]|2[0-8])[-](0[1-9]|1[012]))|((29|30|31)[-](0[13578]|1[02]))|((29|30)[-](0[4,6,9]|11)))[-](19|[2-9][0-9])\d\d$)|(^29[-]02[-](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)/

                };
            }

        }
    },
    directives: {
        validate: {
            bind(el, binding, node, oldNode) {
                let rules = {}, params,
                        name = binding.rawName.replace('v-validate:', ''), $vue = node.context;
                el.setAttribute('name', name);
                if (typeof binding.value === 'string') {
                    let _rules = binding.value.split('|'), message;
                    rules = {};

                    _rules.forEach(rule => {
                        const parts = rule.replace(/\\:/g, '').split(':');
                        const _rule = parts[0];
                        let param = $vue.parseStringRule(parts[1], _rule);
                        param.attribute = name;
                        message = parts[2] || '';

                        if (message.startsWith('i18n\.')) {
                            message = $vue.translate(message.replace(/^i18n./, ''), typeof param === 'object' ? param : {});
                        }

                        if (typeof $vue[_rule] === 'function') {
                            rules[_rule] = {
                                param,
                                message
                            };
                        } else if ($vue.hasSpecialRule(_rule)) {
                            if (!$vue.inputs[name]) {
                                $vue.inputs[name] = [];
                            }
                            $vue.inputs[name].push(_rule);
                        }
                    });

                    $vue.rules[name] = rules;
                } else if (typeof binding.value === 'object') {
                    let params = null, message;
                    for (let rule in binding.value) {
                        params = binding.value[rule] || null;
                        message = binding.value[rule].message || '';
                        if (message.startsWith('i18n\.')) {
                            message = $vue.translate(message.replace(/^i18n./, ''), typeof params === 'object' ? params : {});
                        }
                        rules[rule] = {
                            param: binding.value[rule].param || null,
                            message: $vue.translate(message || `validations.${rule}`, params)
                        };
                    }

                    $vue.rules[name] = rules;
                }


                if (true === $vue.validateNow) {
                    $vue.$watch(name, function (newValue, oldValue) {
                        $vue.validateSingleField(name, newValue, oldValue);
                    });
                }


            },
            inserted(el, binding, node, oldNode) {
                let $vue = node.context, input, name = binding.rawName.replace('v-validate:', '');
                const errorBox = $vue.errorRefName(name);
                if (!$vue.$refs[errorBox]) {
                    $vue.$refs[errorBox] = el;
                }

            },
            update(el, binding, node) {
            },
            updatedComponent(el, binding, node) {
            }
        }
    },
    mixins: [I18nMixin],
    methods: {
        validateSingleField(name, value, oldValue = null) {
            let errors = [],
                    rules = this.rules[name],
                    errorBox = this.errorRefName(name),
                    stopOnFirstError = this.hasSpecialRule('bail'),
                    val = this.sanitize(value, name);

            for (let method in rules) {
                if (false === this[method].apply(this, [val, rules[method].param.value])) {
                    errors.push(this.sprintf(rules[method].message, rules[method].param));
                    if (stopOnFirstError) {
                        break;
                    }
                }
            }
            this.errors[name] = errors;
            if (this.$refs[errorBox]) {
                this.$refs[errorBox].innerHTML = errors[0] || '';
        }
        },
        errorRefName(name) {
            return `error_${name}`;
        },
        sanitize(value, name) {
            if (this.fieldHasSpecialRule('raw', name) || (typeof value !== 'string')) {
                return value;
            }
            return value.replace(/(<([^>]+)>)/gi, "");
            ;
        },
        hasSpecialRule(rule) {
            return this.specialRules.indexOf(rule) > -1;
        },
        fieldHasSpecialRule(name, rule) {
            return Array.isArray(this.inputs[name]) && this.inputs[name].indexOf(rule) > -1;
        },
        mapErorrs(fn, name = null) {
            const errors = Array.isArray(this.erors[name]) ? this.erors[name] : Object.value(this.errors);
            for (let i in errors) {
                fn(errors[i]);
        }
        },
        sprintf(str, args) {

            for (var i in args) {
                if (str.indexOf('{' + i + '}') > -1) {
                    str = str.replace('{' + i + '}', args[i]);
                }
            }
            return str;
        },
        required(a) {
            if (typeof a === 'string') {
                return a.length > 0;
            }

            if (typeof a === 'number') {
                return a > 0;
            }

            if (a instanceof File) {
                return true;
            }

            return undefined !== a;
        },
        required_with(a, attribute) {
            if (this.required(this.dot(attribute))) {
                return this.required(a);
            }

            return true;
        },
        required_without(a, attribute) {

            if (true === this.required(this.dot(attribute))) {
                return true;
            }

            return this.required(a);
        },
        email(a) {
            var pattern = new RegExp(this.$props.regexp.email);
            return pattern.test(a);
        },
        equals(a, b) {
            return a === b;
        },
        different(a, b) {
            return a !== b;
        },
        minLength(a, b) {
            return a.length >= parseInt(b);
        },
        maxLength(a, b) {
            return a.length <= b;
        },
        length(a, b) {
            return a.length === b;
        },
        min(a, b) {
            return a >= b;
        },
        max(a, b) {
            return a <= b;
        },
        range(a, b) {
            var _b = b.split('-');
            return a >= parseInt(_b[0]) && a <= parseInt(_b[1]);
        },
        between(a, b) {
            var _b = b.split('-');
            return a.length >= _b[0] && a.length <= _b[1];
        },
        same(a, b) {
            const data = this.flatten();
            for (var index in data) {
                if (index === b) {
                    return data[index] !== a;
                }
            }
            return true;
        },
        notSame(a, b) {
            const data = this.flatten();
            for (var index in data) {
                if (index === a) {
                    return data[index] !== b;
                }
            }
            return true;
        },
        gt(a, b) {
            return a > b;
        },
        lt(a, b) {
            return a < b;
        },
        gte(a, b) {
            return a >= b;
        },
        lte(a, b) {
            return a <= b;
        },
        dimmensions(a, b) {
            let valid = false;

            if (b.hasOwnProperty('min_width') && a >= b.min_width) {

            }

            if (b.hasOwnProperty('max_width') && a <= b.max_width) {

            }

            if (b.hasOwnProperty('min_height')) {

            }

            if (b.hasOwnProperty('max_height') && a <= b.max_height) {

            }


            return valid;
        },
        regex(a, b) {
            var pattern = new RegExp(this.regexp[b]);
            return pattern.test(a);
        },
        hasExtension(a, b) {
            if (a.length === 0)
                return true;

            var array = b.replace("\s*", "").trim().split(','),
                    extension = a.substr(a.lastIndexOf('.') + 1).trim();
            return array.indexOf(extension) > -1;
        },
        mime(a, b) {
            if (a instanceof File) {
                var array = b.replace(/\s*/g, "").trim().split(',');
                return array.indexOf(a.type) > -1;
            }

            return true;
        },
        list(a, b) {
            var array = b.replace(/\s*/g, "").trim().split(',');
            return array.indexOf(a) > -1;
        },
        json(text) {
            try {
                var o = JSON.parse(text);
                if (o && typeof o === "object" && o !== null) {
                    true;
                }
            } catch (e) {
                return false;
            }
        },
        onSubmit(ev) {
            if (this.validate()) {

                if (typeof this.submit === 'function') {
                    this.submit(ev);
                }
                ;
            }
        },
        validate(options = {}) {
            let val, errors = {}, valid = true, $this = this;

            for (let name in this.rules) {
                val = this.dot(name);
                this.validateSingleField(name, val);
            }

            return new Promise((done, fail) => {
                if ($this.errors) {
                    for (let key in $this.errors) {
                        if ($this.errors[key].length > 0) {
                            this.valid = false;
                            fail($this.errors);
                            throw 'Invalid Input';
                            return;
                        }
                    }
                }
                this.valid = true;
                done();
            });




        },
        setErrors(errors) {
            this.errors = errors;
        },
        parseStringRule(param, rule) {
            if (typeof param === 'string') {
                param = param.replace('\\=', '').replace('\\,');

                let params = param.split(','), parseMethod = `parse_${rule}`;
                if (typeof this[parseMethod] === 'function') {
                    return this[parseMethod](params);
                } else {
                    return {value: param};
                }

            }

            return {value: param};
        },
        parseObjectRule(rule) {
            return JSON.parse(`{"${rule.replace(/\=/g, '":"').replace(/\,/g, '","')}"}`);
        },
        parse_dimmensions(param) {
            let dimmension = this.parseObjectRule(param);

            for (let key in dimmension) {
                dimmension[key] = parseInt(dimmension[key]);
            }
            return dimmension;
        },
        parse_between(params) {
            return {
                min: params[0],
                max: params[1]
            };
        },
        flatten(prefix, obj = null, depth = 1) {
            var propName = (prefix) ? prefix + '.' : '',
                    dotAttribute,
                    ret = {},
                    obj = Object.assign({}, obj || this.$data);
            for (var attr in obj) {
                dotAttribute = propName + attr;

                if (Array.isArray(obj[attr])) {
                    ret[attr] = obj[attr].join(',');
                } else if (typeof obj[attr] === 'object' && (depth < this.depth) && !(obj[attr] instanceof File)) {
                    ret[dotAttribute] = this.flatten(dotAttribute, obj[attr], depth + 1);
                    // Object.assign(ret, this.flatten(propName + attr, obj[attr], depth + 1));
                } else {
                    ret[dotAttribute] = obj[attr];
                }

//                if (!this.rules[dotAttribute]) {
//                    delete ret[dotAttribute];
//                }
            }
            return ret;
        },
        first(attribute = null) {

            if (Array.isArray(this.errors[attribute]) && this.errors[attribute].length > 0) {
                return this.errors[attribute][0];
            }

            return '';
        },
        dot(path, o = null) {
            let i, key, keys = path.split('.'), obj = Object.assign({}, o || this.$data);
            for (i = 0; i < keys.length; i++) {
                key = keys[i];
                if (!obj || !obj.hasOwnProperty(key)) {
                    obj = undefined;
                    break;
                }
                obj = obj[key];
            }
            return obj;
        }
    },
    computed: {
        has(name = '') {
            const errors = this.erors[name] ? Object.value(this.errors) : [];
            for (let i in errors) {
                if (errors[i]) {
                }
        }
        }
    }
}
