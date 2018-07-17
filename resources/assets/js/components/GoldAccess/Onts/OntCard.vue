<template>
    <div class="col-4">
        <div class="card mt-3 mb-3">

            <a :href="showUrl" class="text-dark">
                <div class="card-header text-center">
                    <span class="font-weight-bold"><i class="material-icons">person</i>{{ ont.model_number }}</span>
                </div>
            </a>


            <div class="card-body">

                <div class="row">
                    <div class="col text-center">
                        <ul class="list-unstyled">
                            <li><strong>Manufacturer:</strong> {{ ont.manufacturer }}</li>

                            <li v-if="ont.indoor"><strong>indoor</strong></li>
                            <li v-if="!ont.indoor"><strong>outdoor</strong></li>

                            <li v-if="ont.wifi"><strong>wifi</strong></li>
                            <li v-if="!ont.wifi"><strong>no wifi</strong></li>

                            <li><strong>Ethernet ports:</strong> {{ ont.number_of_ethernet_ports }}</li>
                            <li><strong>POTS lines:</strong> {{ ont.number_of_pots_lines }}</li>
                            <li><strong>Notes:</strong> {{ ont.notes }}</li>
                            <li><strong>Files:</strong> {{ numberOfFiles }}</li>
                            <li>{{ ont.number_of_software_versions }} software versions</li>
                        </ul>
                    </div>
                </div>


                <div class="row mt-5 align-self-end">
                    <div class="col text-center">
                        <a :href="showUrl" class="btn btn-sm btn-outline-dark">Show</a>
                    </div>
                    <div class="col text-center">
                        <a :href="editUrl" class="btn btn-sm btn-outline-dark">Edit</a>
                    </div>
                    <div class="col text-center">
                        <delete-modal :title="ont.model_number" :to-be-deleted="ont" @delete-the-object="deleteObject()">
                            <div slot="button">
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    :class="deleteButtonClass"
                                    data-toggle="modal"
                                    :data-target="modalRef"
                                    :disabled="ont.has_provisioning_records"
                                >
                                    Delete
                                </button>
                            </div>
                            <div slot="body">
                                <p>Are you sure you wish to delete <strong>{{ ont.model_number }}</strong>?</p>
                            </div>
                        </delete-modal>
                    </div>
                </div>

            </div><!-- /card-body -->

        </div>
    </div>
</template>

<script>
    var DeleteModal = Vue.extend(require('../Core/DeleteModal.vue'));

    export default {
        props: {
            ont: {},
        },

        components: {
            'delete-modal': DeleteModal,
        },

        computed: {
            deleteButtonClass: function() {
                return this.ont.has_provisioning_records ? 'btn-outline-light' : 'btn-outline-dark';
            },
            modalRef: function() {
                return '#deleteModal-'+this.ont.id;
            },
            editUrl: function() {
                return '/onts/'+this.ont.id+'/edit';
            },
            showUrl: function() {
                return '/onts/'+this.ont.id;
            }
        },

        data: function() {
            return {
                numberOfFiles: this.ont.number_of_files,
            }
        },

        created: function() {
            EventBus.$on('media-file-was-deleted', function() {
                this.numberOfFiles = this.numberOfFiles - 1;
            }.bind(this));
            EventBus.$on('ont-file-was-added', function(payload) {
                this.numberOfFiles = this.numberOfFiles + 1;
            }.bind(this));
        },

        methods: {
            deleteObject: function() {
                axios.delete('/api/onts/'+this.ont.id).then( (response) => {
                    window.location.href = '/onts';
                }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
