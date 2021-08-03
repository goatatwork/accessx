<template>
    <tr :class="rowClasses">
        <td class="text-left" style="width:20%;">
            <span class="font-italic small">{{ theCustomer.created_at }}</span>
        </td>
        <td>
            <div class="row">
                <div class="col col-auto">
                    <i class="material-icons">{{ customerTypeIcon }}</i>
                    <a :href="showCustomerHref">
                        {{ customer_name }}
                    </a>
                </div>
                <div class="col text-center">
                    <div v-show="theCustomer.has_suspended_services">
                        <i class="material-icons">hide_source</i>
                        <span class="ml-3">SERVICES SUSPENDED</span>
                    </div>
                </div>
            </div>

        </td>
        <td></td>
        <td class="text-right">
            <delete-modal :title="theCustomer.customer_name" :to-be-deleted="theCustomer" @delete-the-object="deleteObject()">
                <div slot="button">
                    <button
                        type="button"
                        class="btn btn-sm"
                        :class="deleteButtonClass"
                        data-toggle="modal"
                        :data-target="modalRef"
                        :disabled="theCustomer.has_provisioning_records"
                    >
                        Delete
                    </button>
                </div>
                <div slot="body">
                    <p>Are you sure you wish to delete <strong>{{ theCustomer.customer_name }}</strong>?</p>
                </div>
            </delete-modal>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            theCustomer: {},
        },

        computed: {
            customer_name: function() {
                return (this.theCustomer.customer_name.length < 25) ?
                    _.padEnd(this.theCustomer.customer_name, 25, ' ') :
                    _.truncate(this.theCustomer.customer_name, { 'length': 25 });
            },
            deleteButtonClass: function() {
                return this.theCustomer.has_provisioning_records ? 'btn-outline-light' : 'btn-outline-dark';
            },
            modalRef: function() {
                return '#deleteModal-'+this.theCustomer.id;
            },
            showCustomerHref: function() {
                return '/customers/'+this.theCustomer.id;
            },
            customerTypeIcon: function() {
                return (this.theCustomer.customer_type == 'Business') ? 'business' : 'person';
            },
            rowClasses: function() {
                return {
                    'table-secondary': this.theCustomer.has_suspended_services
                }
            }
        },

        methods: {
            deleteObject: function() {
                axios.delete('/api/customers/'+this.theCustomer.id).then( (response) => {
                    window.location.href = '/customers';
                }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
