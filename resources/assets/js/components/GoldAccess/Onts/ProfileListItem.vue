<template>
    <div class="row">
        <div class="col">
            <div class="card rounded-0">
                <div class="card-header" role="button" data-toggle="collapse" :data-target="collapseHref" aria-expanded="true">
                    {{ profile.name }}
                </div>

                <div class="collapse" :id="collapseId">
                    <div class="card-body">

                        <div class="media">
                            <span class="fas fa-2x fa-cloud-upload-alt mr-3"></span>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col">
                                        <a :href="profile.file.url">{{ profile.file.file_name }}</a>
                                        <small><span class="font-italic">{{ profile.file.human_readable_size }}</span></small>
                                    </div>
                                    <div class="col">
                                        {{ profile.notes }}
                                    </div>
                                </div>
                            </div>
                            <div class="media-right">
                                <delete-modal :title="profile.file.file_name" :to-be-deleted="profile" v-on:delete-the-object="deleteTheObject()">
                                    <div slot="body">
                                        <p>Are you sure you wish to delete <strong>{{ profile.file.file_name }}</strong>?</p>
                                    </div>
                                </delete-modal>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            profile: {},
        },

        computed: {
            collapseHref: function() {
                return '#collapse-profile-'+this.profile.id;
            },
            collapseId: function() {
                return 'collapse-profile-'+this.profile.id;
            },
            modalId: function() {
                return 'modal-id-profile'+this.profile.id;
            },
            modalRef: function() {
                return 'modal-ref-profile'+this.profile.id;
            }
        },

        methods: {
            deleteTheObject: function() {
                axios.delete('/api/onts/ont_profiles/'+this.profile.id).then( (response) => {
                    EventBus.$emit('ont-profile-was-deleted', this.profile);
                }).catch((error) => {
                    console.log(error);
                });
            }
        }

    }
</script>
