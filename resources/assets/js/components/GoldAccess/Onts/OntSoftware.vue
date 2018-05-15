<template>
    <div class="row">
        <div class="col pt-5">

            <div class="card border-light">

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col text-dark">
                            These are the software images available for the {{ ont.model_number }}. This is where the list of <span class="font-weight-bold">Software Versions</span> comes from when provisioning an ONT.
                        </div>
                    </div>

                    <div v-if="!software.length" class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="well flex-center">
                                THERE IS NO SOFTWARE HERE
                            </div>
                        </div>
                    </div>

                    <file-uploader :upload-url="fileUploadUrl" :dropzone-id="dropzoneId"></file-uploader>

                    <div class="row mt-2">
                        <div class="col">
                            <software-list-item v-for="version in software" :software="version" :key="version.id"></software-list-item>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var SoftwareListItem = Vue.extend(require('./SoftwareListItem.vue'));
    var SoftwareFileUploader = Vue.extend(require('./SoftwareFileUploader.vue'));

    export default {
        props: {
            ont:{},
        },

        components: {
            'software-list-item': SoftwareListItem,
            'file-uploader': SoftwareFileUploader,
        },

        data: function() {
            return {
                software: {},
                fileUploadUrl: '/api/onts/'+this.ont.id+'/software',
                dropzoneId: 'dropzone-for-ont-software-'+this.ont.id,
            }
        },

        created: function() {
            this.fetchSoftware();
            EventBus.$on('ont-software-was-added', function(response) {
                this.software.push(response);
            }.bind(this));
            EventBus.$on('ont-software-was-deleted', function(software) {
                let index = this.software.indexOf(software);
                this.software.splice(index, 1);
            }.bind(this));
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        methods: {
            fetchSoftware: function() {
                axios.get('/api/onts/'+this.ont.id+'/software').then(response => {
                    this.software = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
        }
    }
</script>
