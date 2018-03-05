<template>
    <div>
        <slot name="button">
            <button
                type="button"
                class="btn btn-link text-dark"
                data-toggle="modal"
                :data-target="modalRef"
            >
                Delete
            </button>
        </slot>

        <!-- START OF deleteFile MODAL -->
        <div class="modal fade"
            tabindex="-1"
            role="dialog"
            :id="modalId"
            :aria-labelledby="modalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5  :id="modalLabel" class="modal-title">{{ title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                        </slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                            <button type="submit" class="btn btn-link text-dark float-right" data-dismiss="modal" @click="confirm()">Delete</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF deleteFile MODAL -->

    </div>
</template>

<script>
    // <modal :title="titlestring" :to-be-deleted="someObject" @delete-object="deleteObject()"></modal>
    // When the delete is confirmed, the modal will emit 'delete-object' event. You must catch
    // @delete-object in the partent and execute whatever you need to there.
    export default {
        props: {
            title: {
                type: String,
            },
            toBeDeleted: {
                type: Object,
            }
        },

        computed: {
            modalLabel: function() {
                return 'deleteModalLabel-'+this.toBeDeleted.id;
            },
            modalId: function() {
                return 'deleteModal-'+this.toBeDeleted.id;
            },
            modalRef: function() {
                return '#deleteModal-'+this.toBeDeleted.id;
            },
        },

        methods: {
            confirm: function() {
                this.$emit('delete-the-object');
            }
        }
    }
</script>
