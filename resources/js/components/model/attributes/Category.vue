<template>
    <div>
        <tree ref="tree" :custom-options="options" :custom-styles="styles" :nodes="items" v-if="true"></tree>
    </div>        
</template>
<script>
    import Tree from 'vuejs-tree';

    export default {
        components: {
            Tree
        },
        data() {
            return {
                value: '',
                nodes: [],
            };
        },
        props: {
            items: {
                type: Array,
                default() {
                    return  [];
                }
            },
            single: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            onChecked(id, checked) {
                const node = this.$refs.tree.findNode(id);
                const { tree } = this.$refs;
                
                
//                if (this.single && checked) {
//                    this.nodes = [];
//                    tree.uncheckAllNodes();
//                    
//                }
//                
                if (checked) {
                    tree.checkNode(id);
                } else {
                    tree.uncheckNode(id);
                }
////                
////               
//                //this.value = id;
//                console.log(tree.getCheckedNodes('id'));
                this.$emit('update:modelValue', tree.getCheckedNodes('id'));
            },
            addNode(id) {
                const index = this.nodes.indexOf(id);
                if (index === -1) {
                    this.nodes.push(id);
                }
            },
            removeNode(id) {
                const index = this.nodes.indexOf(id);
                if (index > -1) {
                    this.nodes.slice(index, 1);
                }
            }
        },
        watch: {
            nodes(n, o) {

                this.$emit('update:modelValue', n, o);
            }
        },
        computed: {
            styles() {
                return {
                    tree: {
                        height: 'auto',
                        maxHeight: '300px',
                        overflowY: 'visible',
                        display: 'inline-block'
                    },
                    row: {
                        width: '500px',
                        cursor: 'pointer',
                        child: {
                            height: '35px'
                        }
                    },
                    addNode: {
                        class: 'custom_class',
                        style: {
                            color: '#007AD5'
                        }
                    },
                    editNode: {
                        class: 'custom_class',
                        style: {
                            color: '#007AD5'
                        }
                    },
                    deleteNode: {
                        class: 'custom_class',
                        style: {
                            color: '#EE5F5B'
                        }
                    },
                    selectIcon: {
                        class: 'custom_class',
                        style: {
                            color: '#007AD5'
                        },
                        active: {
                            class: 'custom_class',
                            style: {
                                color: '#2ECC71'
                            }
                        }
                    },
                    text: {
                        style: {},
                        active: {
                            style: {
                                'font-weight': 'bold',
                                color: '#2ECC71'
                            }
                        }
                    }
                };
            },
            options() {

                return {
                    treeEvents: {
                        expanded: {
                            state: true,
                            fn: null
                        },
                        collapsed: {
                            state: true,
                            fn: null
                        },
                        selected: {
                            state: true,
                            fn: null
                        },
                        checked: {
                            state: true,
                            fn: this.onChecked
                        }
                    },
                    events: {
                        expanded: {
                            state: true,
                            fn: null
                        },
                        selected: {
                            state: false,
                            fn: null
                        },
                        checked: {
                            state: true,
                            fn: null
                        },
                        editableName: {
                            state: false,
                            fn: null,
                            calledEvent: null
                        }
                    },
                    addNode: {state: false, fn: null, appearOnHover: false},
                    editNode: {state: true, fn: null, appearOnHover: true},
                    deleteNode: {state: true, fn: null, appearOnHover: true},
                    showTags: true,
                };
            }
        },
        created() {
            console.log(this.items);
            //this.nodes = this.items.map(item => item.id);
        }
    }
</script>
