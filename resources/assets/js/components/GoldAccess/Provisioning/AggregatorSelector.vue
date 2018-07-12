<template>
    <div class="row">
        <div class="col">

            <div class="form-group">
                <label for="aggregator_id">Aggregator</label>
                <select class="form-control" name="aggregator_id" @change="aggregatorWasSelected($event.target.value)">
                    <option value="">Select</option>
                    <option v-for="aggregator in aggregators" :value="aggregator.id">{{ aggregator.name }}</option>
                </select>
                <span v-show="fetchingSlots" class="text-danger">Fetching Slots...</span>
            </div>

            <div v-show="slots.length" class="form-group">
                <label for="slot_id">Slot</label>
                <select class="form-control" name="slot_id" @change="slotWasSelected($event.target.value)">
                    <option value="">Select</option>
                    <option v-for="aSlot in slots" :value="aSlot.id" :disabled="! aSlot.populated">
                        Slot {{ aSlot.slot_number }} - {{ aSlot.module_type_name }}
                    </option>
                </select>
                <span v-show="fetchingPorts" class="text-danger">Fetching Ports...</span>
            </div>

            <div v-show="ports.length" class="form-group">
                <label for="port_id">Ports</label>
                <select class="form-control" name="port_id" @change="portWasSelected($event.target.value)">
                    <option value="">Select</option>
                    <option v-for="port in ports" :value="port.id" :disabled="port.has_provisioning_records">Port {{ port.port_number }}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                fetchingSlots: false,
                fetchingPorts: false,
                aggregators: {},
                slots: {},
                ports: {},
            }
        },

        created: function() {
            this.fetchAggregators();
        },

        methods: {
            addModuleTypeNames: function(slots) {
                _.forEach(slots, (slot) => {
                    if (slot.populated) {
                        axios.get('/api/infrastructure/module_types/'+slot.module_type_id).then(response => {
                            Vue.set(slot, 'module_type_name', response.data.name);
                        }).catch(error => {
                            console.log(error);
                        });
                    } else {
                        Vue.set(slot, 'module_type_name', 'unpopulated');
                    }
                });
            },
            aggregatorWasSelected: function(aggregatorId) {
                this.slots = {};
                this.ports = {};
                if (aggregatorId == 0) {
                    return;
                }
                this.fetchSlots(aggregatorId);
                this.$emit('aggregator-was-selected');
            },
            fetchAggregators: function() {
                axios.get('/api/infrastructure/aggregators').then(response => {
                    this.aggregators = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchPorts: function(slotId) {
                this.fetchingPorts = true;
                axios.get('/api/infrastructure/slots/'+slotId+'/ports').then(response => {
                    this.ports = response.data;
                    this.fetchingPorts = false;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchSlots: function(aggregatorId) {
                this.fetchingSlots = true;
                axios.get('/api/infrastructure/aggregators/'+aggregatorId+'/slots').then(response => {
                    this.slots = response.data;
                    this.addModuleTypeNames(response.data);
                    this.fetchingSlots = false;
                }).catch(error => {
                    console.log(error);
                });
            },
            portWasSelected: function(portId) {
                console.log('Port '+portId+' was selected.');
                EventBus.$emit('provisioning-port-was-selected', portId);
                this.$emit('port-was-selected');
            },
            slotWasSelected: function(slotId) {
                this.ports = {};
                if (slotId == 0) {
                    return;
                }
                this.fetchPorts(slotId);
                this.$emit('slot-was-selected');
            }
        }
    }
</script>
