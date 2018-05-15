<template>
    <div class="row">
        <div class="col">

            <div class="form-group">
                <label for="ont_id">ONT</label>
                <select class="form-control" name="ont_id" @change="ontWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="ont in onts" :value="ont.id">{{ ont.model_number }}</option>
                </select>
            </div>

            <div v-show="ont_software.length" class="form-group">
                <label for="ont_software_id">ONT Software</label>
                <select class="form-control" name="ont_software_id" @change="softwareWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="software in ont_software" :value="software.id">Version {{ software.version }}</option>
                </select>
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
                axios.get('/api/onts/ont_software/'+softwareId+'/ont_profiles').then(response => {
                    this.ont_profiles = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchOntSoftware: function(ontId) {
                axios.get('/api/onts/'+ontId+'/software').then(response => {
                    this.ont_software = response.data;
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
                console.log('Profile '+profileId+' was selected.');
                EventBus.$emit('provisioning-profile-was-selected', profileId);
                // this is the id value we need so do something usefull with it
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
