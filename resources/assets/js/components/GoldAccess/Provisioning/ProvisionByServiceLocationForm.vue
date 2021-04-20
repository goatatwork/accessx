<template>
    <div class="row">
        <div class="col">

            <div class="row" :class="ontSelectorClasses">
                <div class="col">
                    <ont-selector
                        @ont-was-selected="clearOntErrors"
                        @software-was-selected="clearOntErrors"
                        @profile-was-selected="clearOntErrors"
                    ></ont-selector>
                </div>
            </div>

            <div v-show="ontSelectorHasErrors" class="row">
                <div class="col text-danger">
                    You must select an <strong>ONT</strong>, an <strong>ONT Software</strong>, and an <strong>ONT Profile</strong>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <hr>
                </div>
            </div>

            <div class="row" :class="aggregatorSelectorClasses">
                <div class="col">
                    <aggregator-selector
                        @aggregator-was-selected="clearAggregatorErrors"
                        @slot-was-selected="clearAggregatorErrors"
                        @port-was-selected="clearAggregatorErrors"
                    ></aggregator-selector>
                </div>
            </div>

            <div v-show="aggregatorSelectorHasErrors" class="row">
                <div class="col text-danger">
                    You must provide an <strong>Aggregator</strong>, a <strong>Slot</strong>, and a <strong>Port</strong>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <hr>
                </div>
            </div>

            <div class="row" :class="dhcpSelectorClasses">
                <div class="col">
                    <dhcp-management-network-selector
                        @dhcp-was-selected="clearDhcpErrors"
                        @ip-was-selected="clearDhcpErrors"
                    ></dhcp-management-network-selector>
                </div>
            </div>

            <div v-show="dhcpSelectorHasErrors" class="row">
                <div class="col text-danger">
                    You must provide a <strong>Management Network</strong> and an <strong>IP Address</strong>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <hr>
                </div>
            </div>

            <div class="row">
              <div class="col">
                <speed-selector @speed-was-selected="speedWasSelected"></speed-selector>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="len">LEN</label>
                        <input type="text" name="len" class="form-control" v-model="form.len">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="circuit_id">Circuit ID</label>
                        <input type="text" name="circuit_id" class="form-control" v-model="form.circuit_id">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="body" class="form-control" v-model="form.notes"></textarea>
                    </div>
                    <div class="form-group pull-right">
                        <a href="#" class="cancel-button" @click="cancelForm()">Cancel</a>
                        <button class="btn btn-sm btn-success" :disabled="submitIsDisabled" @click="submitForm()">Okay</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var OntSelector = Vue.extend(require('./OntSelector.vue'));
    var SpeedSelector = Vue.extend(require('./SpeedSelector.vue'));
    var AggregatorSelector = Vue.extend(require('./AggregatorSelector.vue'));
    var DhcpManagementNetworkSelector = Vue.extend(require('./DhcpManagementNetworkSelector.vue'));

    export default {
        props: {
            location: {},
        },

        components: {
            'ont-selector': OntSelector,
            'speed-selector': SpeedSelector,
            'aggregator-selector': AggregatorSelector,
            'dhcp-management-network-selector': DhcpManagementNetworkSelector,
        },

        data: function() {
            return {
                form: {
                    service_location_id: this.location.id,
                    ont_profile_id: '',
                    port_id: '',
                    ip_address_id: '',
                    len: '',
                    circuit_id: '',
                    notes: '',
                    package_id: '',
                },
                formErrors: {
                    'service_location_id': [],
                    'ont_profile_id': [],
                    'port_id': [],
                    'ip_address_id': [],
                    'package_id': [],
                },
                submitIsDisabled: false,
            }
        },

        computed: {
            aggregatorSelectorHasErrors: function() {
                return this.formErrors.port_id.length ? true : false;
            },
            dhcpSelectorHasErrors: function() {
                return this.formErrors.port_id.length ? true : false;
            },
            ontSelectorHasErrors: function() {
                return this.formErrors.ont_profile_id.length ? true : false;
            },
            aggregatorSelectorClasses: function() {
                return this.formErrors.port_id.length ? '' : '';
            },
            dhcpSelectorClasses: function() {
                return this.formErrors.ip_address_id.length ? '' : '';
            },
            ontSelectorClasses: function() {
                return this.formErrors.ont_profile_id.length ? '' : '';
            },
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        methods: {
            cancelForm: function() {
                this.resetForm();
                // window.location.href = "/customers/"+this.location.customer_id;
            },
            clearAggregatorErrors: function() {
                this.formErrors.port_id = [];
            },
            clearDhcpErrors: function() {
                this.formErrors.ip_address_id = [];
            },
            clearOntErrors: function() {
                this.formErrors.ont_profile_id = [];
            },
            initializeEventBus: function() {
                EventBus.$on('provisioning-profile-was-selected', function(profileId) {
                    this.form.ont_profile_id = profileId;
                }.bind(this));
                EventBus.$on('provisioning-port-was-selected', function(portId) {
                    this.form.port_id = portId;
                }.bind(this));
                EventBus.$on('provisioning-ip-address-was-selected', function(ipAddressId) {
                    this.form.ip_address_id = ipAddressId;
                }.bind(this));
            },
            resetForm: function() {
                this.form = {
                    service_location_id: this.location.id,
                    ont_profile_id: '',
                    port_id: '',
                    ip_address_id: '',
                    len: '',
                    circuit_id: '',
                    notes: '',
                    package_id: '',
                }
            },
            resetFormErrors: function() {
                this.formErrors = {
                    'service_location_id': [],
                    'ont_profile_id': [],
                    'port_id': [],
                    'ip_address_id': [],
                }
            },
            speedWasSelected: function(id) {
                this.form.package_id = id;
            },
            submitForm: function() {
                this.submitIsDisabled = true;
                axios.post('/api/provisioning', this.form).then( (response) => {
                    this.resetForm();
                    this.resetFormErrors();
                    this.submitIsDisabled = false;
                    window.location.href = "/provisioning/"+response.data.id;
                }).catch( (error) => {
                    this.resetFormErrors();
                    this.submitIsDisabled = false;
                    this.$nextTick().then( () => {
                        this.formErrors = error.response.data.errors;
                    });
                });
            }
        }
    }
</script>
