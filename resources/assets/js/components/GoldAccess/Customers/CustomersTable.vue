<template>
    <div class="row">
        <div class="col">

            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Provisioned</th>
                        <th>Service Address</th>
                        <th>POC</th>
                    </tr>
                </thead>
                <tbody>
                    <tr is="customer-table-row" v-for="customer in customersList" :the-customer="customer">
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
        }
    }
</script>
