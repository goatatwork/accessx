<template>
    <div class="modal fade" :id="modalId" tabindex="-1" role="dialog" :aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="modalLabel">Deleting {{ user.name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col">
                                Are you sure you want to remove {{ user.name }}?
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" @click.prevent="submitForm">DELETE USER</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: {},
        },

        computed: {
            modalId: function() {
                return 'user-delete-modal-'+this.user.id;
            },
            modalLabel: function() {
                return 'user-delete-modal-'+this.user.id+'-label';
            },
        },

        methods: {
            submitForm: function() {
                axios.delete('/api/authorization/users/'+this.user.id).then( (response) => {
                    $('#'+this.modalId).modal('hide');
                    this.announceDelete(response.data);
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            },
            announceDelete: function(x) {
                EventBus.$emit('user-was-deleted', this.user);
            }
        }

    }
</script>
