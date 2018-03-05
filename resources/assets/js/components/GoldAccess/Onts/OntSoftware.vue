<template>
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <div class="col-md-12">

                </div>
            </div>

            <div v-if="!software.length" class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="well flex-center">
                        THERE ARE IS NO SOFTWARE HERE
                    </div>
                </div>
            </div>

            <file-uploader :upload-url="fileUploadUrl" :dropzone-id="dropzoneId"></file-uploader>


            <software-list-item v-for="version in software" :software="version" :key="version.id"></software-list-item>

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
