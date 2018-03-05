<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header" role="button" data-toggle="collapse" :data-target="collapseHref" aria-expanded="true">
                    Version {{ software.version }}
                </div>

                <div class="collapse" :id="collapseId">
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <div class="media">
                                    <span class="fas fa-2x fa-cloud-download-alt mr-3"></span>
                                    <div class="media-body">
                                        <div class="row">
                                            <div class="col">
                                                <a :href="software.file.url">{{ software.file.file_name }}</a>
                                                <small><span class="font-italic">{{ software.file.human_readable_size }}</span></small>
                                            </div>
                                            <div class="col">
                                                {{ software.notes }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <delete-modal :title="software.version" :to-be-deleted="software" v-on:delete-the-object="deleteTheObject()">
                                            <div slot="body">
                                                <p>Are you sure you wish to delete <strong>{{ software.file.file_name }}</strong>?</p>
                                            </div>
                                        </delete-modal>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ont-software-profiles :software="software"></ont-software-profiles>

                    </div><!-- /card-body -->
                </div><!-- /collapse -->

            </div>
        </div>
    </div>
</template>

<script>
    var OntSoftwareProfiles = Vue.extend(require('./OntSoftwareProfiles.vue'));

    export default {
        props: {
            software: {},
        },

        components: {
            'ont-software-profiles': OntSoftwareProfiles,
        },

        computed: {
            collapseHref: function() {
                return '#collapse-software-'+this.software.id;
            },
            collapseId: function() {
                return 'collapse-software-'+this.software.id;
            },
            deleteModalId: function() {
                return 'delete-modal-software-'+this.software.id;
            },
            deleteModalRef: function() {
                return 'delete-modal-ref-software-'+this.software.id;
            },
            headingId: function() {
                return 'heading-software-'+this.software.id;
            }
        },

        methods: {
            deleteTheObject: function() {
                axios.delete('/api/onts/ont_software/'+this.software.id).then( (response) => {
                    EventBus.$emit('ont-software-was-deleted', this.software);
                }).catch((error) => {
                    console.log(error);
                });
            }
        }

    }
</script>
