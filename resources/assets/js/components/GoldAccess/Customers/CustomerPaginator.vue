<template>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col-auto">
                    Page <span class="font-weight-bold">{{ paginator_meta.page }}</span> of {{ paginator_meta.total_pages}}
                </div>
                <div class="col d-flex justify-content-center">
                    <paginator-link v-for="link in paginator_meta.total_pages"
                        :key="link"
                        :current_page=paginator_meta.page
                        :destination_page="link"
                        :records_per_page="paginator_meta.records_per_page"
                        @clicked="clicked"
                    >
                    </paginator-link>
                </div>
                <div class="col-auto">
                    Per Page:
                        <records-per-page-option v-for="number in records_per_page_options"
                            :key="number"
                            :number_of_records="number"
                            :paginator_meta="paginator_meta"
                            @change_number_of_records_per_page="change_number_of_records_per_page"
                        ></records-per-page-option>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var CustomerPaginatorLink = Vue.extend(require('./CustomerPaginatorLink.vue'));
    var RecordsPerPageOption = Vue.extend(require('./RecordsPerPageOption.vue'));

    export default {
        props: {
            paginator_meta: {}
        },

        components: {
            'paginator-link': CustomerPaginatorLink,
            'records-per-page-option': RecordsPerPageOption,
        },

        data() {
            return {
                records_per_page_options: [25, 50, 100]
            }
        },

        methods: {
            change_number_of_records_per_page(n) {
                this.$emit('change_number_of_records_per_page', n);
            },
            clicked(page) {
                this.$emit('clicked', page);
            },
            records_per_page_option(n) {
                return {
                    'font-weight-bold': (n == this.paginator_meta.records_per_page) ? true : false,
                }
            }
        }
    }
</script>
