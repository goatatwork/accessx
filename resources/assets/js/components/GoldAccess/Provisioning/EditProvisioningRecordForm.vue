<template>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <table class="table table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="4" class="text-center">ONT & Profile Information</th>
                            </tr>
                        </thead>
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">ONT</th>
                                <th class="text-center">Software Version</th>
                                <th class="text-center">Profile</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">{{ provisioningRecord.ont.manufacturer }} {{ provisioningRecord.ont.model_number }}</td>
                                <td class="text-center">{{ provisioningRecord.ont_software.version }}</td>
                                <td class="text-center">
                                    {{ provisioningRecord.ont_profile.name }}
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-dark" data-toggle="collapse" data-target="#ont-selector" :disabled="editingNetworkOrIp" @click="editOnt">EDIT</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div id="ont-selector" class="col collapse">

                            <ont-selector></ont-selector>

                            <div v-show="readyToUpdateOnt" class="row">
                                <div class="col">
                                    <button class="btn btn-sm btn-success float-right" @click="submitChanges">
                                        <i v-show="working" class="fa fa-spinner fa-spin"></i>
                                        Save
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col">
                    <table class="table table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="3" class="text-center">Network Information</th>
                            </tr>
                        </thead>
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">Network Location</th>
                                <th class="text-center">IP Address</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    {{ provisioningRecord.aggregator.name }}
                                    <span class="fas fa-long-arrow-alt-right"></span>
                                    Slot {{ provisioningRecord.slot.slot_number }}
                                    <span class="fas fa-long-arrow-alt-right"></span>
                                    Port {{ provisioningRecord.port.port_number }}
                                </td>
                                <td class="text-center">
                                    {{ provisioningRecord.ip.address }}
                                </td>
                                <td class="text-right">

                                    <button
                                        class="btn btn-sm"
                                        :class="editNetworkOrIpButtonClasses"
                                        :disabled="editingOnt"
                                        @click="editLocationOrIp"
                                    >
                                        {{ editNetworkOrIpButtonText }}
                                    </button>

                                    <button v-show="editingNetworkOrIp"
                                        class="btn btn-sm btn-dark animated"
                                        :class="{  'fadeIn': editingNetworkOrIp }"
                                        data-toggle="collapse"
                                        data-target="#network-location-selector"
                                        @click="editLocation"
                                    >
                                        EDIT LOCATION
                                    </button>

                                    <button
                                        v-show="editingNetworkOrIp"
                                        class="btn btn-sm btn-dark animated"
                                        :class="{  'fadeIn': editingNetworkOrIp }"
                                        data-toggle="collapse"
                                        data-target="#dhcp-management-network-selector"
                                        @click="editIp"
                                    >
                                        EDIT MANAGEMENT IP
                                    </button>

                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div id="network-location-selector" class="row collapse">
                        <div class="col">

                            <aggregator-selector></aggregator-selector>

                            <div v-show="readyToUpdateNetworkLocation" class="row">
                                <div class="col">
                                    <button class="btn btn-sm btn-success float-right" @click="submitChanges">
                                        <i v-show="working" class="fa fa-spinner fa-spin"></i>
                                        Save
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="dhcp-management-network-selector" class="row collapse">
                        <div class="col">

                            <dhcp-management-network-selector></dhcp-management-network-selector>

                            <div v-show="readyToUpdateManagementIp" class="row">
                                <div class="col">
                                    <button class="btn btn-sm btn-success float-right" @click="submitChanges">
                                        <i v-show="working" class="fa fa-spinner fa-spin"></i>
                                        Save
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" colspan="3">Speed Package</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">Package: </td>
                                        <td class="text-left">
                                            <span v-show="! editingSpeed">{{ package_name }}</span>
                                            <span v-show="editingSpeed">
                                                <label for="Package" class="sr-only">Package</label>

        <select name="package_id"
            class="custom-select"
            id="package_id"
            v-model="formData.package_id"
            @change="selectPackage($event.target)"
        >
            <option v-for="speedPacakge in speed_packages"
                :id="speedPacakge.id"
                :key="speedPacakge.id"
                :value="speedPacakge.id"
                :selected="speedPacakge.id == provisioningRecord.package_id"
            >
                {{ speedPacakge.name }}
            </option>

        </select>

                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <button v-show="! editingSpeed" class="btn btn-sm btn-dark" @click="editSpeed">EDIT</button>
                                            <div v-show="editingSpeed" class="btn-group" role="group" aria-label="Speed Package Selection">
                                                <button class="btn btn-sm btn-success" @click="submitSpeedChange">
                                                    <i v-show="working" class="fa fa-spinner fa-spin"></i>
                                                    Save
                                                </button>
                                                <button class="btn btn-sm btn-secondary" @click="cancelEditSpeed">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" colspan="3">Plant Identifiers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">LEN</td>
                                        <td class="text-left">
                                            <span v-show="! editingLen">{{ formData.len }}</span>
                                            <span v-show="editingLen">
                                                <label for="LEN" class="sr-only">LEN</label>
                                                <input type="text" class="form-control form-control-sm" name="len" v-model="formData.len"></span>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <button v-show="! editingLen" class="btn btn-sm btn-dark" @click="editLen">EDIT</button>
                                            <div v-show="editingLen" class="btn-group" role="group" aria-label="LEN Editing Form Controls">
                                                <button class="btn btn-sm btn-success" @click="submitLenChange">
                                                    <i v-show="working" class="fa fa-spinner fa-spin"></i>
                                                    Save
                                                </button>
                                                <button class="btn btn-sm btn-secondary" @click="cancelEditLen">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Circuit ID</td>
                                        <td class="text-left">
                                            <span v-show="! editingCircuitid">{{ formData.circuit_id }}</span>
                                            <span v-show="editingCircuitid">
                                                <label for="Circuit ID" class="sr-only">Circuit ID</label>
                                                <input type="text" class="form-control form-control-sm" name="circuit_id" v-model="formData.circuit_id"></span>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <button v-show="! editingCircuitid" class="btn btn-sm btn-dark" @click="editCircuitid">EDIT</button>
                                            <div v-show="editingCircuitid" class="btn-group" role="group" aria-label="Circuit ID Editing Form Controls">
                                                <button class="btn btn-sm btn-success" @click="submitCircuitidChange">
                                                    <i v-show="working" class="fa fa-spinner fa-spin"></i>
                                                    Save
                                                </button>
                                                <button class="btn btn-sm btn-secondary" @click="cancelEditCircuitid">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var OntSelector = Vue.extend(require('./OntSelector.vue'));
    var AggregatorSelector = Vue.extend(require('./AggregatorSelector.vue'));
    var DhcpManagementNetworkSelector = Vue.extend(require('./DhcpManagementNetworkSelector.vue'));

    export default {
        props: {
            recordToEdit: {},
            speedPackages: {}
        },

        components: {
            'ont-selector': OntSelector,
            'aggregator-selector': AggregatorSelector,
            'dhcp-management-network-selector': DhcpManagementNetworkSelector,
        },

        created: function() {
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        data: function() {
            return {
                editingOnt: false,
                editingLen: false,
                editingSpeed: false,
                editingCircuitid: false,
                editingNetworkOrIp: false,
                formData: {
                    ont_profile_id: this.recordToEdit.ont_profile.id,
                    ip_address_id: this.recordToEdit.ip.id,
                    port_id: this.recordToEdit.port.id,
                    len: this.recordToEdit.len,
                    circuit_id: this.recordToEdit.circuit_id,
                    package_id: this.recordToEdit.package_id,
                    reboot: true,
                },
                package_id: this.recordToEdit.package_id,
                package_name: this.recordToEdit.package_name,
                provisioningRecord: this.recordToEdit,
                speed_packages: this.speedPackages,
                working: false
            }
        },

        computed: {
            editNetworkOrIpButtonClasses: function() {
                return this.editingNetworkOrIp ? 'btn-danger' : 'btn-dark';
            },
            editNetworkOrIpButtonText: function() {
                return this.editingNetworkOrIp ? 'CANCEL' : 'EDIT';
            },
            readyToUpdateOnt: function() {
                return this.formData.ont_profile_id != this.provisioningRecord.ont_profile.id;
            },
            readyToUpdateNetworkLocation: function() {
                return this.formData.port_id != this.provisioningRecord.port.id;
            },
            readyToUpdateManagementIp: function() {
                return this.formData.ip_address_id != this.provisioningRecord.ip.id;
            },
        },

        methods: {
            cancelEditCircuitid: function() {
                this.editingCircuitid = false;
                this.formData.circuit_id = this.provisioningRecord.circuit_id;
            },
            cancelEditLen: function() {
                this.editingLen = false;
                this.formData.len = this.provisioningRecord.len;
            },
            cancelEditSpeed: function() {
                this.editingSpeed = false;
                this.package_id = this.provisioningRecord.package_id;
                this.package_name = this.provisioningRecord.package_name;
                this.formData.package_id = this.provisioningRecord.package_id;
            },
            editCircuitid: function() {
                this.editingOnt = false;
                this.editingLen = false;
                this.editingSpeed = false;
                this.editingCircuitid = true;
                this.editingNetworkOrIp = false;
            },
            editIp: function() {
                this.editingOnt = false;
                this.editingLen = false;
                this.editingSpeed = false;
                this.editingCircuitid = false;
                this.editingNetworkOrIp = true;
                $('#network-location-selector').collapse('hide');
                $('#dhcp-management-network-selector').collapse('show');
            },
            editLen: function() {
                this.editingOnt = false;
                this.editingLen = true;
                this.editingSpeed = false;
                this.editingCircuitid = false;
                this.editingNetworkOrIp = false;
            },
            editLocation: function() {
                this.editingOnt = false;
                this.editingLen = false;
                this.editingSpeed = false;
                this.editingCircuitid = false;
                this.editingNetworkOrIp = true;
                $('#network-location-selector').collapse('show');
                $('#dhcp-management-network-selector').collapse('hide');
            },
            editLocationOrIp: function() {
                this.editingOnt = false;
                this.editingLen = false;
                this.editingCircuitid = false;
                this.editingSpeed = false;
                this.editingNetworkOrIp = ! this.editingNetworkOrIp;
                $('#network-location-selector').collapse('hide');
                $('#dhcp-management-network-selector').collapse('hide');
            },
            editOnt: function() {
                this.editingLen = false;
                this.editingCircuitid = false;
                this.editingNetworkOrIp = false;
                this.editingSpeed = false;
                this.editingOnt = ! this.editingOnt;
                $('#network-location-selector').collapse('hide');
                $('#dhcp-management-network-selector').collapse('hide');
            },
            editSpeed: function() {
                this.editingOnt = false;
                this.editingLen = false;
                this.editingCircuitid = false;
                this.editingNetworkOrIp = false;
                this.editingSpeed = ! this.editingSpeed;
            },

            initializeEventBus: function() {
                EventBus.$on('provisioning-ip-address-was-selected', function(id) {
                    this.updateIpId(id);
                }.bind(this));
                EventBus.$on('provisioning-profile-was-selected', function(id) {
                    this.updateProfileId(id);
                }.bind(this));
                EventBus.$on('provisioning-port-was-selected', function(id) {
                    this.updatePortId(id);
                }.bind(this));
            },

            resetForm: function() {
                this.formData = {
                    ont_profile_id: this.provisioningRecord.ont_profile.id,
                    ip_address_id: this.provisioningRecord.ip.id,
                    port_id: this.provisioningRecord.port.id,
                    len: this.provisioningRecord.len,
                    circuit_id: this.provisioningRecord.circuit_id,
                    package_id: this.provisioningRecord.package_id,
                    reboot: true,
                },
                this.editingOnt = false,
                this.editingLen = false,
                this.editingSpeed = false,
                this.editingCircuitid = false,
                this.editingNetworkOrIp = false;
                $('#ont-selector').collapse('hide');
                $('#network-location-selector').collapse('hide');
                $('#dhcp-management-network-selector').collapse('hide');
            },

            selectPackage: function(target) {
                var new_package = _.filter(this.speed_packages, (speed_package) => {
                    return target.value == speed_package.id;
                });

                this.package_id = new_package[0].id;
                this.package_name = new_package[0].name;
            },

            submitChanges: function() {
                this.working = true;
                axios.patch('/api/provisioning/'+this.provisioningRecord.id, this.formData).then( (response) => {
                    this.resetForm();
                    window.location.href = "/provisioning/"+this.provisioningRecord.id+"/edit";
                    this.working = false;
                }).catch( (error) => {
                    console.log(error.response.data);
                    this.working = false;
                });
            },
            submitLenChange: function() {
                this.formData.reboot = false;
                this.working = true;
                axios.patch('/api/provisioning/'+this.provisioningRecord.id, this.formData).then( (response) => {
                    this.formData.len = response.data.len;
                    this.editingLen = false;
                    this.working = false;
                }).catch( (error) => {
                    console.log(error.response.data);
                    this.working = false;
                });
            },
            submitCircuitidChange: function() {
                this.formData.reboot = false;
                this.working = true;
                axios.patch('/api/provisioning/'+this.provisioningRecord.id, this.formData).then( (response) => {
                    this.formData.circuit_id = response.data.circuit_id;
                    this.editingCircuitid = false;
                    this.working = false;
                }).catch( (error) => {
                    console.log(error.response.data);
                    this.working = false;
                });
            },
            submitSpeedChange: function() {
                this.formData.reboot = false;
                this.working = true;
                axios.patch('/api/provisioning/'+this.provisioningRecord.id, this.formData).then( (response) => {
                    this.editingSpeed = false;
                    this.working = false;
                }).catch( (error) => {
                    console.log(error.response.data);
                    this.working = false;
                });
            },
            updateIpId: function(id) {
                this.formData.ip_address_id = id;
            },
            updatePortId: function(id) {
                this.formData.port_id = id;
            },
            updateProfileId: function(id) {
                this.formData.ont_profile_id = id;
            }
        }
    }
</script>
