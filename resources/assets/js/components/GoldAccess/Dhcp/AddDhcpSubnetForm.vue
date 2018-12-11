<template>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" :class="{ 'has-error': formErrors.ip }" @keydown="delete formErrors.ip">
                        <label for="ip">IP</label>
                        <input type="text" name="ip" class="form-control input-sm" v-model="ip" @keydown.enter="calculate()" @focusout="calculate()">
                        <div v-if="formErrors.ip">
                            <span v-for="formError in formErrors.ip" class="is-error">
                                {{ formError }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            Select whether your default gateway is at the top or the bottom of the subnet...
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="radio" id="top" value="top" v-model="gateway_preference">
                                <label for="top">Top</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="radio" id="bottom" value="bottom" v-model="gateway_preference">
                                <label for="bottom">Bottom</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4>Network Size</h4>
                    <vue-slider
                        id="vue-slider"
                        ref="slider"
                        @callback="theCallback()"
                        @drag-start="dragStart()"
                        @drag-end="dragEnd()"
                        v-bind="slider"
                        v-model="slider.value"
                    ></vue-slider>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed">
                        <tbody>
                            <tr>
                                <td>Number of Usable IPs</td>
                                <td><span class="badge" :class="classOnChange">{{ calculatedSubnet.usable_addresses }}</span></td>
                            </tr>
                            <tr>
                                <td>Network</td>
                                <td>
                                    {{ calculatedSubnet.network_address }} / {{ calculatedSubnet.cidr }}
                                </td>
                            </tr>
                            <tr>
                                <td>Subnet Mask</td>
                                <td>
                                    {{ calculatedSubnet.subnet_mask }}
                                </td>
                            </tr>
                            <tr>
                                <td>Usable IPs</td>
                                <td>
                                    {{ calculatedSubnet.start_ip }} - {{ calculatedSubnet.end_ip }}
                                </td>
                            </tr>
                            <tr>
                                <td>Broadcast Address</td>
                                <td>
                                    {{ calculatedSubnet.broadcast_address }}
                                </td>
                            </tr>
                            <tr>
                                <td>Gateway</td>
                                <td>
                                    {{ calculatedSubnet.routers }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row animated fadeIn">
                <div class="col-md-12">
                    <button class="btn btn-default form-control" :disabled="number_of_subnets_calculated < 2" @click="submitForm()">Add This Subnet</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vueSlider from 'vue-slider-component';

    export default {
        props: {
            sharedNetwork: {}
        },

        components: {
            vueSlider,
        },

        data: function() {
            return {
                calculatedSubnet: [],
                classOnChange: '',
                formErrors: {},
                gateway_preference: 'top',
                ip: '192.168.1.100',
                number_of_subnets_calculated: 0,
                slider: {
                    data: [
                        '30',
                        '29',
                        '28',
                        '27',
                        '26',
                        '25',
                        '24',
                        '23',
                        '22',
                        '21',
                        '20',
                        '19'
                    ],
                    dotSize: 20,
                    labelActiveStyle: {
                        "color": "#222",
                    },
                    piecewise: true,
                    piecewiseActiveStyle: {
                        "backgroundColor": "#222"
                    },
                    piecewiseLabel: true,
                    piecewiseStyle: {
                        "backgroundColor": "#ccc",
                        "visibility": "visible",
                        "width": "12px",
                        "height": "12px",
                    },
                    processStyle: {
                        "backgroundColor": "#222",
                    },
                    sliderStyle: {
                        "backgroundcolor": "#222",
                    },
                    speed: .3,
                    tooltip: false,
                    value: '24',
                    width: '100%',
                },
            }
        },

        watch: {
            gateway_preference: function() {
                this.calculate();
            }
        },

        created: function() {
            this.initializeEventBus();
        },

        mounted: function() {
            this.calculate();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        methods: {
            collapseToggled: function() {
                let self = this;
                setTimeout(function() {
                    self.$refs.slider.refresh();
                }, 100);
            },
            calculate: function() {
                self = this;
                axios.post('/api/subnetcalculator', {ip:this.ip, cidr:this.slider.value, gateway_preference:this.gateway_preference}).then(function(response) {
                    self.calculatedSubnet = response.data;
                    self.number_of_subnets_calculated++;
                }).catch(function(error) {
                    self.formErrors = error.response.data;
                });
            },
            closeCalculator: function() {
                this.$emit('close-calculator');
            },
            dragEnd: function() {
                // console.log('drag end');
            },
            dragStart: function() {
                // console.log('drag start');
            },
            initializeEventBus: function() {
                EventBus.$on('refresh-slider', function() {
                    this.collapseToggled();
                }.bind(this));
            },
            submitForm: function() {
                self = this;
                axios.post('/api/dhcp/dhcp_shared_networks/'+this.sharedNetwork.id+'/subnets', this.calculatedSubnet).then(function(response) {
                    window.location.reload();
                }).catch(function(error) {
                    console.log(error.response.data);
                });
            },
            theCallback: function(value) {
                this.$nextTick(function() {
                    this.calculate();
                }.bind(this));
            },
        }
    }
</script>
