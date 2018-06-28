<template>
    <div class="row">
        <div class="col">

            <div class="form-group">
                <label for="ont_id">ONT</label>
                <select class="form-control" name="ont_id" @change="ontWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="ont in onts" :value="ont.id">{{ ont.model_number }}</option>
                </select>
                <span v-show="fetchingSoftware" class="text-danger">Fetching ONT Software...</span>
            </div>

            <div v-show="ont_software.length" class="form-group">
                <label for="ont_software_id">ONT Software</label>
                <select class="form-control" name="ont_software_id" @change="softwareWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="software in ont_software" :value="software.id">Version {{ software.version }}</option>
                </select>
                <span v-show="fetchingProfiles" class="text-danger">Fetching ONT Profiles...</span>
            </div>

            <div v-show="ont_profiles.length" class="form-group">
                <label for="ont_profile_id">ONT Profile</label>
                <select class="form-control" name="ont_profile_id" @change="profileWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="profile in ont_profiles" :value="profile.id">{{ profile.name }}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                fetchingProfiles: false,
                fetchingSoftware: false,
                onts: {},
                ont_software: {},
                ont_profiles: {},
            }
        },

        created: function() {
            this.fetchOnts();
        },

        methods: {
            fetchOnts: function() {
                axios.get('/api/onts').then(response => {
                    this.onts = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchOntProfiles: function(softwareId) {
                this.fetchingProfiles = true;
                axios.get('/api/onts/ont_software/'+softwareId+'/ont_profiles').then(response => {
                    this.ont_profiles = response.data;
                    this.fetchingProfiles = false;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchOntSoftware: function(ontId) {
                this.fetchingSoftware = true;
                axios.get('/api/onts/'+ontId+'/software').then(response => {
                    this.ont_software = response.data;
                    this.fetchingSoftware = false;
                }).catch(error => {
                    console.log(error);
                });
            },
            ontWasSelected: function(ontId) {
                this.ont_software = {};
                this.ont_profiles = {};
                if (ontId == 0) {
                    return;
                }
                this.fetchOntSoftware(ontId);
            },
            profileWasSelected: function(profileId) {
                EventBus.$emit('provisioning-profile-was-selected', profileId);
            },
            softwareWasSelected: function(softwareId) {
                this.ont_profiles = {};
                if (softwareId == 0) {
                    return;
                }
                this.fetchOntProfiles(softwareId);
            }
        }
    }
</script>
