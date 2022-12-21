<template lang="html">
    <div :id="id" :class="rootClass">
        <ul class='flex mb-0 list-none flex-wrap pt-3 flex-row'>
            <li v-for="(tab, key) in tabs" @click="selectTab(key)" :class="navItemClass(key)" :key="key" :active="isActive(key)">
            <span v-html="tab.title"></span>
            </li>
        </ul>
        <div :class="panelClass">
            <slot :data-tab="activeTab"></slot>
        </div>
        
    </div>
</template>
<script>
    export default {
        data() {
            return {
                
                activeTab: null,
                tabs: {}
            };
        },
        props: {
            id: {
                type: String,
                default() {
                    return 'tab' + new Date().getTime();
                }
            },
            classes: {
                type: [String, Object],
                default() {

                    return {
                        root: {
                            'w-full': true
                        },
                        navItem: {
                            'mr-2 last:mr-0 flex-auto text-center p-2 cursor-pointer': true,
                            'hidden': false
                        },
                        panel: {
                            'w-full shadow-md': true,
                        }
                    };
                }
            },
            classList: {
                type: [String, Object],
                default() {
                    return {
                        panel: {}
                    };
                }
            },
            activeClass: {
                type: String,
                default: 'active'
            }
        },
        methods: {
            selectTab(key) {
                this.activeTab = key;
                
                this.tabs.forEach((tab, index) => {
                    tab.active = (index === key);
                });
                
                
            },
            isActive(index) {
                return index === this.selected;
            },
            navItemClass(key) {
                let classList = Object.assign({}, this.classes.navItem, this.classList.navItem || {});

                classList[this.activeClass] = this.selected === key;
                return classList;
            },
        },
        computed: {
            rootClass() {
                return Object.assign({}, this.classes.root);
            },
            className() {
                let obj;
                if (typeof this.classes === 'string') {
                    let classes = this.classes.split('\s+'), obj = {};
                    for (let i in classes) {
                        obj[i] = true;
                    }


                } else {
                    obj = Object.assign({}, this.classes);
                }

                return obj;
            },
            panelClass() {
                return Object.assign({} , this.classList.panel, this.classes.panel);
            },
            selected() {
                if (this.activeTab) {
                    return this.activeTab;
                }

                if (this.$store && this.$store.getters.activeTab) {
                    return this.$store.getters.activeTab;
                }
                return Object.values(this.tabs)[0].key;
            }
        },
        watch: {
            activeTab(n, o) {
                this.$emit('changeTab', n, o);
            }
        },
        created() {
            
            this.tabs = this.$children;
        },
        mounted() {
            const key = this.selected;
            this.tabs.forEach((tab, index) => {
                tab.active = (index === key);
            });
        }
    }
</script>

