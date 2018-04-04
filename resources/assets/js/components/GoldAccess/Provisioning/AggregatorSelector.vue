<template>
    <div class="row">
        <div class="col">

            <div class="form-group">
                <label for="aggregator_id">Aggregator</label>
                <select class="form-control" name="aggregator_id" @change="aggregatorWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="aggregator in aggregators" :value="aggregator.id">{{ aggregator.name }}</option>
                </select>
            </div>

            <div v-show="slots.length" class="form-group">
                <label for="slot_id">Slot</label>
                <select class="form-control" name="slot_id" @change="slotWasSelected($event.target.value)">
                    <option value="0">Select</option>
                    <option v-for="aSlot in slots" :value="aSlot.id">Slot {{ aSlot.slot_number }}</option>
                </select>
            </div>

            <div v-show="ports.length" class="form-group">
                <label for="port_id">Ports</label>
                <select class="form-control" name="port_id" @change="portWasSelected($event.target.value)">
                    <option value="0">Select</option>
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
                aggregators: {},
                slots: {},
                ports: {},
            }
        },

        created: function() {
            this.fetchAggregators();
        },

        methods: {
            fetchAggregators: function() {
                axios.get('/api/infrastructure/aggregators').then(response => {
                    this.aggregators = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchPorts: function(slotId) {
                axios.get('/api/infrastructure/slots/'+slotId+'/ports').then(response => {
                    this.ports = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            fetchSlots: function(aggregatorId) {
                axios.get('/api/infrastructure/aggregators/'+aggregatorId+'/slots').then(response => {
                    this.slots = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            aggregatorWasSelected: function(aggregatorId) {
                this.slots = {};
                this.ports = {};
                if (aggregatorId == 0) {
                    return;
                }
                this.fetchSlots(aggregatorId);
            },
            portWasSelected: function(portId) {
                console.log('Port '+portId+' was selected.');
                EventBus.$emit('provisioning-port-was-selected', portId);
                // this is the id value we need so do something usefull with it
            },
            slotWasSelected: function(slotId) {
                this.ports = {};
                if (slotId == 0) {
                    return;
                }
                this.fetchPorts(slotId);
            }
        }
    }
</script>
