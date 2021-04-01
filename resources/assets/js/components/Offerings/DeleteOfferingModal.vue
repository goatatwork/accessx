<template>
    <div class="modal fade"
        :id="modalId"
        tabindex="-1"
        role="dialog"
        :aria-labelledby="'delete-modal-for-'+offering.id+'-label'"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="'delete-modal-for-'+offering.id+'-label'">Remove Offering</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you really sure you wish to remove the {{offering.name}} package?
                </div>
                <div class="modal-footer">
                    <form method="POST" :action="deleteUrl">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <input type="hidden" name="_method" value="DELETE">

                        <button class="btn btn-sm btn-dark" data-dismiss="modal">Close</button>
                        <button class="btn btn-sm btn-outline-success" @click.prevent="submit">Delete</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            offering: {}
        },

        computed: {
            csrfToken() {
                return (window.Laravel.csrf_token) ? window.Laravel.csrf_token : '';
            },
            deleteUrl() {
                return '/api/packages/'+this.offering.id;
            },
            modalId() {
                return 'delete-modal-for-'+this.offering.id;
            }
        },

        methods: {
            submit() {
                axios.delete(this.deleteUrl).then( (response) => {
                    $('#'+this.modalId).modal('hide');
                    this.$emit('offering-deleted', this.offering);
                }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
