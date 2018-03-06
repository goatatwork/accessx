<template>
    <div class="card">
        <div class="card-header">
            {{ locationTitle }}
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
                <li>
                    <strong>POC:</strong> {{ location.poc_name }}
                    <a v-show="location.poc_email" :href="mailTo">
                        <i class="fa fa-envelope"></i>
                    </a>
                </li>
                <li>{{ location.address1 }}</li>
                <li v-show="location.address2">{{ location.address2 }}</li>
                <li>
                    {{ location.city }}, {{ location.state }}  {{ location.zip }}
                </li>
                <li v-show="location.phone1">
                    Phone: {{ location.phone1 }}
                </li>
                <li v-show="location.phone2">
                    Alt Phone: {{ location.phone2 }}
                </li>
                <li v-show="location.notes">
                    {{ location.notes }}
                </li>
            </ul>

            <ul v-show="showControls" class="list-unstyled">
                <li v-show="location.has_provisioning_records">
                    <div class="flex-center-column">
                        <span>This location is provisioned.</span>
                        <a :href="showLink" class="btn btn-default form-control">VIEW SERVICES</a>
                    </div>
                </li>
                <li v-show="!location.has_provisioning_records" style="display:flex;justify-content:center;align-items:center;">
                    <div class="flex-center-column">
                        <span>There is nothing provisioned here.</span>
                        <a :href="provisioningLink" class="btn btn-default form-control">SET UP SERVICE!</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            location: {},
        },

        computed: {
            locationTitle: function() {
                return (this.location.name == 'Default') ? this.location.address1 : this.location.name;
            },
            mailTo: function() {
                return (this.location.poc_email) ? 'mailto:'+this.location.poc_email : '';
            },
            provisioningLink: function() {
                return '/provisioning/service_locations/'+this.location.id+'/create';
            },
            showControls: function() {
                return (_.startsWith(window.location.pathname, '/provisioning/service_locations')) ? false : true;
            },
            showLink: function() {
                return '/provisioning/service_locations/'+this.location.id+'/show';
            }
        },
    }
</script>
