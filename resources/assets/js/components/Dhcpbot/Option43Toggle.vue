<template>
    <div class="row">
        <div class="col text-right">

            <div class="row" v-show="!hasOption43">
                <div class="col">

                    Not currently sending DHCP option 43<br>
                    <button class="btn btn-sm btn-dark" @click="toggleOption43">
                        <span v-show="busy" class="fas fa-spinner" :class="{ 'fa-spin': busy }"></span>
                        Start sending Option 43 ACS URL
                    </button>
                </div>
            </div>

            <div class="row" v-show="hasOption43">
                <div class="col">

                    Currently sending DHCP option 43<br>
                    <button class="btn btn-sm btn-dark" @click="toggleOption43">
                        <span v-show="busy" class="fas fa-spinner fa-spin"></span>
                        Stop sending Option 43 ACS URL
                    </button>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            subnet: {}
        },

        data() {
            return {
                busy: false,
                hasOption43: this.subnet.has_option_43
            }
        },

        methods: {
            toggleOption43() {
                this.busy = true;
                axios.patch('/api/dhcpbot/subnet/'+this.subnet.id+'/option43').then( (response) => {
                    this.busy = false;
                    this.hasOption43 = response.data;
                    console.log(response.data);
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            }
        }
    }
</script>
