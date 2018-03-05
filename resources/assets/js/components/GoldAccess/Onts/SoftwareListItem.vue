<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Version {{ software.version }}
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col">
                            <ul class="list-unstyled">
                                <li>
                                    <a :href="software.file.url">
                                        <i class="fa fa-cloud-download-alt"></i> Download {{ software.file.file_name }}
                                    </a>
                                    <span class="label label-default">{{ software.file.human_readable_size }}</span>
                                </li>
                                <li>
                                    <div v-show="software.notes" class="well">
                                        {{ software.notes }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <modal :title="software.version" :to-be-deleted="software" @delete-object="deleteObject()">
                                <div slot="body">
                                    <p>Are you sure you wish to delete <strong>{{ software.file.file_name }}</strong>?</p>
                                </div>
                            </modal>
                        </div>
                    </div>

                    <ont-software-profiles :software="software"></ont-software-profiles>

                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
</template>

<script>
    var DeleteModal = Vue.extend(require('../Core/DeleteModal.vue'));
    var OntSoftwareProfiles = Vue.extend(require('./OntSoftwareProfiles.vue'));


    export default {
        props: {
            software: {},
        },

        components: {
            'modal': DeleteModal,
            'ont-software-profiles': OntSoftwareProfiles,
        },

        computed: {
            collapseHref: function() {
                return '#collapse-'+this.software.id;
            },
            collapseId: function() {
                return 'collapse-'+this.software.id;
            },
            deleteModalId: function() {
                return 'delete-modal-'+this.software.id;
            },
            deleteModalRef: function() {
                return 'delete-modal-ref-'+this.software.id;
            },
            headingId: function() {
                return 'heading-'+this.software.id;
            }
        },

        methods: {
            deleteObject: function() {
                axios.delete('/api/onts/ont_software/'+this.software.id).then( (response) => {
                    EventBus.$emit('ont-software-was-deleted', this.software);
                }).catch((error) => {
                    console.log(error);
                });
            }
        }

    }
</script>
