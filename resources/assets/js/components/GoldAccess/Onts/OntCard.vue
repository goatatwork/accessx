<template>
    <div class="col-3">
        <div class="card mt-3 mb-3">
            <div class="card-header">

                <span class="float-left">
                    <a :href="showUrl" class="text-dark">{{ ont.model_number }}</a>
                </span>

                <span class="float-right">

                </span>

            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col">
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
                        </ul>
                    </div>
                </div>

<!--                         <div class="row">
                    <div class="col">
                        <div class="media mt-3">
                            <span class="fas fa-2x fa-circle text-dark mr-3"></span>
                            <div class="media-body">
                                {{$aggregator->platform->number_of_slots}} slots
                                <ul>
                                    <li>{{$aggregator->slots()->populatedOnly()->count()}} populated</li>
                                    <li>{{$aggregator->slots()->unpopulatedOnly()->count()}} unpopulated</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> -->

<!--                         <div class="row">
                    <div class="col">
                        <div class="media mt-3">
                            <span class="fas fa-2x fa-circle text-dark mr-3"></span>
                            <div class="media-body">
                                {{$aggregator->ports()->count()}} ports
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="row mt-5">
                    <div class="col text-center">
                        <a :href="editUrl" class="btn btn-link text-dark">Edit</a>
                    </div>
                    <div class="col text-center">
                        <a :href="showUrl" class="btn btn-link text-dark">Show</a>
                    </div>
                    <div class="col text-center">

                        <delete-modal :title="ont.model_number" :to-be-deleted="ont" @delete-the-object="deleteObject()">
                            <div slot="button">
                                <button
                                    type="button"
                                    class="btn btn-link text-dark"
                                    data-toggle="modal"
                                    :data-target="modalRef"
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

            </div>
            <div class="card-footer">
<!--                         <small>
                    <span class="font-italic">
                        Created on {{ $aggregator->created_at->toFormattedDateString() }} at {{ $aggregator->created_at->toTimeString() }}
                    </span>
                </small> -->
            </div>
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
