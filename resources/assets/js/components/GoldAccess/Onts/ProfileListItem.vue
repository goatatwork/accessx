<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    {{ profile.name }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            {{ profile.notes }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <modal :title="profile.file.file_name" :to-be-deleted="profile" @delete-object="deleteObject()">
                                <div slot="body">
                                    <p>Are you sure you wish to delete <strong>{{ profile.file.file_name }}</strong>?</p>
                                </div>
                            </modal>
                        </div>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
</template>

<script>
    var DeleteModal = Vue.extend(require('../Core/DeleteModal.vue'));

    export default {
        props: {
            profile: {},
        },

        components: {
            'modal': DeleteModal,
        },

        computed: {
            modalId: function() {
                return 'modal-id'+this.profile.id;
            },
            modalRef: function() {
                return 'modal-ref'+this.profile.id;
            }
        },

        methods: {
            deleteObject: function() {
                axios.delete('/api/onts/ont_profiles/'+this.profile.id).then( (response) => {
                    EventBus.$emit('ont-profile-was-deleted', this.profile);
                }).catch((error) => {
                    console.log(error);
                });
            }
        }

    }
</script>
