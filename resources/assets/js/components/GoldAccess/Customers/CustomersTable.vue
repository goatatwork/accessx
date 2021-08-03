<template>
    <div class="row">
        <div class="col">

            <div class="row" v-show="!customerCollection.paginated">
              <div class="col">
                <span class="fas fa-spinner fa-spin"></span> ...fetching customers
              </div>
            </div>

            <div class="row" v-if="customerCollection.data">
              <div class="col">

                <div class="row mb-5">
                  <div class="col">
                    <div class="table-responsive">
                        <table class="table table">
                            <thead>
                                <tr>
                                    <td colspan="4">
                                        <paginator
                                            :paginator_meta="paginator_meta"
                                            @clicked="change_page"
                                            @change_number_of_records_per_page="change_number_of_records_per_page"
                                        ></paginator>
                                    </td>
                                </tr>
                               <tr>
                                    <td colspan="4">
                                        <div class="row">
                                            <div class="col">
                                                <label for="searchQuery" class="sr-only">Search Query</label>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="fas fa-search">
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" v-model="searchQuery" placeholder="Search">
                                                </div>
                                            </div>
                                            <div class="col text-right">
                                                <small>
                                                    <a href="#" 
                                                        class="text-dark" 
                                                        @click.prevent="sort_by_suspended"
                                                    >Click here to sort by suspended status</a>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th @click="sortBy('created_at')" class="text-left" style="width:20%;">
                                        Created <span class="fas fa-sort"></span>
                                    </th>
                                    <th @click="sortBy('customer_name')" class="text-left">
                                        Customer <span class="fas fa-sort"></span>
                                    </th>
                                    <th></th>
                                    <th>
                                        <span class="float-right" v-show="!fetch_in_progress">
                                            <small>Sorted by
                                                <span class="font-weight-bold font-italic">
                                                    {{display_sort_key}} ({{display_sort_order}})
                                                </span>
                                            </small>
                                        </span>
                                        <span class="float-right" v-show="fetch_in_progress">
                                            <span class="fas fa-spinner fa-spin text-dark"></span>
                                            <small>...sorting</small>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr is="customer-table-row" v-for="customer in customerList" :key="customer.id" :the-customer="customer">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                  </div>
                </div>

              </div>
            </div>

        </div>
    </div>
</template>

<script>
    var CustomerTableRow = Vue.extend(require('./CustomerTableRow.vue'));
    var CustomerPaginator = Vue.extend(require('./CustomerPaginator.vue'));

    export default {
        components: {
            'paginator': CustomerPaginator,
            'customer-table-row': CustomerTableRow,
        },

        mounted() {
            this.initCustomers();
        },

        data: function() {
            return {
                customerCollection: {},
                customersUrl: '/api/customers',
                default_query_params: {
                    page: 1,
                    records_per_page: 25,
                    sort_key: 'customer_name',
                    sort_order: 'asc'
                },
                fetch_in_progress: false,
                query_params: {
                    page: 1,
                    records_per_page: 0,
                    sort_key: 'customer_name',
                    sort_order: 'asc'
                },
                searchQuery: '',
            }
        },

        computed: {
            customerList() {
                return this.searchQuery ? this.customerListFiltered : this.customerListSorted;
            },
            customerListFiltered() {
                let self = this;
                return _.filter(this.customerCollection.data, function(customer) {
                    var searchRegex = new RegExp(self.searchQuery, 'i');
                    return searchRegex.test(customer.customer_name);
                })
            },
            customerListSorted() {
                let chunk = this.query_params.page - 1;
                let page_data = this.customerCollection.paginated[chunk];
                return _.orderBy(page_data, this.customerCollection.sort_key, this.customerCollection.sort_order);
                // return this.customerCollection.paginated[chunk];
            },
            display_sort_key() {
                return (this.customerCollection.sort_key) ? this.customerCollection.sort_key : this.query_params.sort_key;
            },
            display_sort_order() {
                let order = (this.customerCollection.sort_order) ? this.customerCollection.sort_order : this.query_params.sort_order;
                return (order == 'asc') ? 'ascending' : 'descending';
            },
            paginator_meta() {
                return this.customerCollection.paginator_meta;
            }
            // customerListSorted: function() {
            //     return _.orderBy(this.customerCollection.data, this.sortKey, this.sortOrder);
            // },
        },

        methods: {
            change_number_of_records_per_page(n) {
                this.query_params.records_per_page = n;
                this.fetch_customers();
            },
            change_page(page) {
                this.query_params.page = page;
                this.customerCollection.paginator_meta.page = page;
            },
            fetch_customers() {
                this.fetch_in_progress = true;
                axios.get(this.customersUrl, {params:this.query_params}).then(response => {
                    this.customerCollection = response.data;
                    this.fetch_in_progress = false;
                }).catch(error => {
                    console.log(error);
                    this.fetch_in_progress = false;
                });
            },
            initCustomers() {
                this.set_query_params();
                this.fetch_customers();
            },
            set_page_from_query_params() {
                this.query_params.page = window.Laravel.query_params.page ?
                    window.Laravel.query_params.page :
                    this.default_query_params.page
            },
            set_sort_key_from_query_params() {
                this.query_params.sort_key = window.Laravel.query_params.sort_key ?
                    window.Laravel.query_params.sort_key :
                    this.default_query_params.sort_key
            },
            set_sort_order_from_query_params() {
                this.query_params.sort_order = window.Laravel.query_params.sort_order ?
                    window.Laravel.query_params.sort_order :
                    this.default_query_params.sort_order
            },
            set_query_params() {
                if (window.Laravel) {
                    if (window.Laravel.query_params) {
                        this.set_page_from_query_params();
                        this.set_records_per_page_from_query_params();
                        this.set_sort_key_from_query_params();
                        this.set_sort_order_from_query_params();
                    }
                }
            },
            set_records_per_page_from_query_params() {
                this.query_params.records_per_page = window.Laravel.query_params.records_per_page ?
                    window.Laravel.query_params.records_per_page :
                    this.default_query_params.records_per_page
            },
            sortBy: function(field) {
                if (field == this.query_params.sort_key) {
                    this.query_params.sort_order = (this.query_params.sort_order == 'asc') ? 'desc' : 'asc';
                } else {
                    this.query_params.sort_key = field;
                    this.query_params.sort_order = 'asc';
                }
                this.fetch_customers();
            },
            sort_by_suspended: function() {
                this.query_params.sort_order = 'desc';
                this.query_params.sort_key = 'has_suspended_services';
                this.fetch_customers();
            }
        }
    }
</script>
