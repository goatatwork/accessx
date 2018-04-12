<template>
    <tr>
        <td>

            <i class="material-icons">{{ customerTypeIcon }}</i>

            <a :href="customerHref">{{ theCustomer.customer_name }}</a>

        </td>

        <td>
            <i class="material-icons text-success" style="font-size: 28px">{{ provisionedStatusIcon }}</i>
        </td>

        <td>
            <div v-if="singleServiceLocation">
                {{ theCustomer.service_locations[0].address1 }}
            </div>
            <div v-if="!singleServiceLocation">
                <span class="small font-italic">Multiple Service Locations</span>
            </div>
        </td>
        <td>
            <div v-if="theCustomer.service_locations">
                {{ theCustomer.service_locations[0].poc_name }}
                <a v-if="theCustomer.service_locations[0].poc_email" :href="customerMailto"><i class="material-icons">email</i></a>
                <a v-if="theCustomer.service_locations[0].phone1" :href="customerTel"><i class="material-icons">phone</i></a>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            theCustomer: {},
        },

        computed: {
            customerHref: function() {
                return '/customers/'+this.theCustomer.id;
            },
            customerMailto: function() {
                return 'mailto:';
            },
            customerTel: function() {
                return 'tel:';
            },
            customerTypeIcon: function() {
                return this.theCustomer.customer_type == 'Business' ? 'business' : 'person';
            },
            provisionedStatusIcon: function() {
                return (this.theCustomer.has_provisioning_records) ? 'verified_user' : '';
            },
            singleServiceLocation: function() {
                return this.theCustomer.number_of_service_locations == 1;
            }
        }
    }
</script>
