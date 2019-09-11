<template>
    <div class="row">
        <div class="col">

            <table class="table">
                <thead>
                    <tr>
                        <th @click="sortBy('created_at')" class="text-center">Created <span class="fas fa-sort"></span></th>
                        <th @click="sortBy('customer_name')" class="text-left">Customer <span class="fas fa-sort"></span></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="customer-table-row" v-for="customer in customerListSorted" :key="customer.id" :the-customer="customer">
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    var CustomerTableRow = Vue.extend(require('./CustomerTableRow.vue'));

    export default {
        props: {
            customersList: {},
        },

        components: {
            'customer-table-row': CustomerTableRow,
        },

        data: function() {
            return {
                sortKey: 'id',
                sortOrder: 'asc'
            }
        },

        computed: {
            customerListSorted: function() {
                return _.orderBy(this.customersList, this.sortKey, this.sortOrder);
            }
        },

        methods: {
            sortBy: function(field) {
                if (field == this.sortKey) {
                    this.sortOrder = (this.sortOrder == 'asc') ? 'desc' : 'asc';
                } else {
                    this.sortKey = field;
                    this.sortOrder = 'asc';
                }
            }
        }
    }
</script>
