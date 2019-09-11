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
