<template>
    <button type="button" :class="buttonClasses" @click="toggle">
        {{ name }}
    </button>
</template>

<script>
    export default {
        props: {
            name: String,
            isActive: Boolean,
        },

        computed: {
            buttonClasses() {
                return {
                    'btn': true,
                    'btn-sm': true,
                    'rounded-0': true,
                    'btn-dark': (this.isActive) ? true : false,
                    'btn-outline-secondary': (this.isActive) ? false : true,
                    'font-italic': (this.isActive) ? false : true,
                }
            }
        },

        methods: {
            toggle() {
                this.$emit('toggled', _.snakeCase(this.name));
            }
        }
    }
</script>
