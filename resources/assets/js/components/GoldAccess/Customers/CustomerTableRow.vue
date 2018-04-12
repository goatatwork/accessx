<template>
    <tr>
        <td class="text-center">
            <span class="font-italic small">{{ theCustomer.created_at_for_humans }}</span>
        </td>
        <td class="text-left">
            <i class="material-icons">{{ customerTypeIcon }}</i>
            <a :href="showCustomerHref">
                {{ theCustomer.customer_name }}
            </a>
        </td>
        <td class="text-center">
            {{ theCustomer.number_of_service_locations }}
        </td>
        <td class="text-center">
            {{ theCustomer.number_of_provisioning_records }}
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            theCustomer: {},
        },

        computed: {
            showCustomerHref: function() {
                return '/customers/'+this.theCustomer.id;
            },
            customerTypeIcon: function() {
                return (this.theCustomer.customer_type == 'Business') ? 'business' : 'person';
            }
        },
    }
</script>
