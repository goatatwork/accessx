<template>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <h3>Profiles for {{ software.version }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <file-uploader :upload-url="fileUploadUrl" :dropzone-id="dropzoneId"></file-uploader>
                </div>
            </div>

            <profile-list-item v-for="profile in profiles" :profile="profile" :key="profile.id"></profile-list-item>

            <div v-if="!profiles.length" class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="well flex-center">
                        THERE ARE NO PROFILES HERE
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var ProfileListItem = Vue.extend(require('./ProfileListItem.vue'));
    var ConfigFileUploader = Vue.extend(require('./ConfigFileUploader.vue'));

    export default {
        props: {
            software: {},
        },

        components: {
            'profile-list-item': ProfileListItem,
            'file-uploader': ConfigFileUploader,
        },

        data: function() {
            return {
                profiles: {},
                fileUploadUrl: '/api/onts/ont_software/'+this.software.id+'/ont_profiles',
                dropzoneId: 'dropzone-for-profile-'+this.software.id,
            }
        },

        created: function() {
            this.fetchProfiles();
            EventBus.$on('ont-software-profile-was-added', function(response) {
                this.profiles.push(response);
            }.bind(this));
            EventBus.$on('ont-profile-was-deleted', function(profile) {
                let index = this.profiles.indexOf(profile);
                this.profiles.splice(index, 1);
            }.bind(this));
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        methods: {
            fetchProfiles: function() {
                axios.get('/api/onts/ont_software/'+this.software.id+'/ont_profiles').then(response => {
                    this.profiles = response.data;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>
