<template>
    <nav>
        <div class="col-md-6 text-left" v-if="showMeta">
            <span>
                {{meta.from}} to {{meta.to}} of {{ meta.total }}
            </span>
        </div>
        <ul :class="pagination.container">
            <li>
                <button
                    :disabled="!links.prev"
                    :class="firstButttonClass"
                    @click="$emit('selectPage', links.prev)"
                    v-html="prev">
                </button>
            </li>
            <li v-for="(link, i) in range">
                <button :class="linkClass(link.active)" v-html="link.label" @click="$emit('selectPage', link.url)"></button>
            </li>
            <li>
                <button
                    :disabled="!links.next"
                    :class="lastButttonClass"
                    @click="$emit('selectPage', links.next)"
                    v-html="next">
                </button>
            </li>
        </ul>      
    </nav>
</template>
<script>
    export default {
        data() {
            return {
                key: 0
            };
        },
        props: {
            showMeta: {
                type: Boolean,
                default: false
            },
            meta: {
                type: Object,
                default() {
                    return {};
                }
            },
            links: {
                type: Object,
                default() {
                    return {};
                }
            },
            prev: {
                type: String,
                default: 'Previuos'
            },
            next: {
                type: String,
                default: 'Next'
            }
        },
        methods: {
            linkClass(active = false) {
                return Object.assign({
                    "bg-primary text-white": active
                }, this.pagination.button);
            },
        },
        computed: {
            range() {
                let items = [];
                for (let i = 1; i <= this.meta.last_page; i++) {
                    items.push(this.meta.links[i]);
                }
                return items;
            },
            pagination() {
                return {
                    container: {
                        'text-center bg-white px-8 py-4 mx-auto shadow-sm flex flex-row': true
                    },
                    button: {
                        'relative items-center px-2 py-2': true,
                        'min-w-12 min-h-12 border border-gray-300 bg-white text-sm font-medium': true
                    },
                    firstButoon: {

                    }
                };
            },
            firstButttonClass() {
                return Object.assign({
                    'rounded-l-lg': true
                }, this.pagination.button);
            },
            lastButttonClass() {
                return Object.assign({
                    'rounded-r-lg': true
                }, this.pagination.button);
            }
        }
    }
</script>
