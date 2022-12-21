<template>
    <div>
        <label :for="fieldId" class="block text-sm font-medium text-gray-700" v-if="$slots.title">
            <slot name="title"></slot>
        </label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <slot><input :id="fieldId" :type="type" v-model="value"
                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"/>
            </slot>
        </div>
    </div>
</template>
<script>

    export default {
            data() {
                return {
                    value: '',
                    key: ''
                }
            },
            props: {
                id: {
                    type: String,
                    default: ''
                },
                placeholder: {
                    type: String,
                    default: ''
                },
                type: {
                    type: String,
                    default: 'text'
                }
            },
            computed: {
                fieldId() {
                    return this.id
                }
            },
            watch: {
                value(n, o) {
                    this.$emit('input', n, o);
                }
            }
    }
</script>
