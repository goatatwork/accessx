<template>
    <div class="d-inline mr-2">
        <a href="#" :class="linkClasses" @click.prevent="change_number_of_records_per_page">{{number_of_records}}</a>
        <span v-if="number_of_records != 100">|</span>
    </div>
</template>

<script>
    export default {
        props: {
            number_of_records: '',
            paginator_meta: {}
        },

        computed: {
            linkClasses() {
                return {
                    'text-dark': true,
                    'font-weight-bold': this.thisNumber == this.paginator_meta.records_per_page
                }
            },
            thisNumber() {
                return ''+this.number_of_records
            }
        },

        methods: {
            change_number_of_records_per_page() {
                this.$emit('change_number_of_records_per_page', this.number_of_records)
            }
        }
    }
</script>
