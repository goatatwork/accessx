<template>
    <div class="row mb-5">
        <div class="col">

            <div class="row">
                <div class="col text-right">

                    <a v-if="!showSubnetCalculator" href="" @click.prevent="showSubnetCalculator = true">
                        <i class="material-icons">add</i>
                        Add A Subnet
                    </a>

                    <button v-if="showSubnetCalculator"
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                        @click="showSubnetCalculator = false"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
            </div>

            <div v-if="showSubnetCalculator" class="row">
                <div class="col">

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="ip-input" class="sr-only">IP</label>

                                <div class="input-group">

                                    <input id="ip-input"
                                        name="ip"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter any IP address"
                                        aria-describedby="ipHelpText"
                                        v-model="ip"
                                    >

                                    <div class="input-group-append">
                                        <button class="btn btn-sm" :class="calculateButtonClasses" type="button" @click="calculate">
                                            <span class="fas fa-redo" :class="calculateButtonIconClasses"></span> {{ calculateButtonText }}
                                        </button>
                                    </div>

                                </div>



                                <small id="ipHelpText" class="form-text text-muted">
                                    Enter any IP within the range that you want to add
                                </small>
                            </div>
                        </div>

                        <div class="col">
                            <h5>Default Gateway Preference</h5>
                            <div class="form-check">
                                <input name="gateway_preference"
                                    type="radio"
                                    class="form-check-input"
                                    id="gateway_preference-input"
                                    value="top"
                                    v-model="gateway_preference"
                                    :checked="gateway_preference == 'top'"
                                >
                                <label class="form-check-label" for="gateway_preference-input">
                                    Top of the subnet
                                </label>
                            </div>

                            <div class="form-check">
                                <input name="gateway_preference"
                                    type="radio"
                                    class="form-check-input"
                                    id="gateway_preference-input"
                                    value="bottom"
                                    v-model="gateway_preference"
                                    :checked="gateway_preference == 'top'"
                                >
                                <label class="form-check-label" for="gateway_preference-input">
                                    Bottom of the subnet
                                </label>
                            </div>

                        </div>

                    </div>

                    <div class="row pl-5 pr-5">
                        <div class="col">
                            <small class="form-text text-muted">
                                Please select a network size
                            </small>
                            <vue-slider
                                ref="cidrSlider"
                                v-model="cidrSlider.value"
                                v-bind="cidrSlider"
                                @callback="changeCidrSlider"
                                @drag-end="cidrSliderDragEnd"
                            >
                            </vue-slider>
                        </div>
                    </div>

                    <div class="row" :class="workInProgressClasses">
                        <div class="col">
                            <span class="fas fa-spinner fa-spin"></span> Calculating
                        </div>
                    </div>


                    <div class="row pl-4 pr-4">
                        <div class="col">
                            <small v-if="ipSlider.show" class="form-text text-muted">
                                If you need to adjust the start and end IPs for this subnet, do so here.
                            </small>
                            <vue-slider
                                ref="ipSlider"
                                v-model="ipSlider.value"
                                v-bind="ipSlider"
                                @callback="changeIpSlider"
                                @drag-end="ipSliderDragEnd"
                            >
                            </vue-slider>
                        </div>
                    </div>


                    <div class="row mt-5" v-if="ipSlider.show">
                        <div class="col">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Start IP</th>
                                        <th>End IP</th>
                                        <th>Netmask</th>
                                        <th>Usable IPs</th>
                                        <th>Gateway</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td :class="{'font-weight-bold' : ipSliderIsMoving}">{{ ipSlider.value[0] }}</td>
                                        <td :class="{'font-weight-bold' : ipSliderIsMoving}">{{ ipSlider.value[1] }}</td>
                                        <td>{{ calculatedSubnet.subnet_mask }}</td>
                                        <td :class="{'font-weight-bold' : ipSliderIsMoving}">{{ calculatedSubnet.usable_addresses }}</td>
                                        <td>{{ calculatedSubnet.routers }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">

                                        <button class="btn btn-sm"
                                            :class="submittingButtonClasses"
                                            type="button"
                                            @click="submit"
                                            :disabled="submitButtonIsDisabled"
                                        >
                                            <span class="fas fa-redo" :class="submittingButtonIconClasses"></span> {{ submittingButtonText }}
                                        </button>

                                            <button class="btn btn-sm btn-danger" :class="{'fade' : ipSliderIsMoving}" @click="cancelForm" >Cancel</button>
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
    import vueSlider from 'vue-slider-component'

    export default {
        props: {
            sharedNetwork: {
                type: Object,
                default() {
                    return {}
                }
            }
        },

        components: {
            vueSlider
        },

        watch: {
            calculatedSubnet() {
                if (this.calculatedSubnet.hasOwnProperty('start_ip')) {
                    this.ipSlider.data = this.calculatedSubnet.addresses;
                    this.ipSlider.value = [this.calculatedSubnet.start_ip, this.calculatedSubnet.end_ip];
                    this.ipSlider.show = true;
                    this.$nextTick(() => this.$refs.ipSlider.refresh());
                    this.workInProgressClasses = 'fade';
                } else {
                    this.ipSlider.show = false;
                    this.$nextTick(() => this.$refs.ipSlider.refresh());
                    this.workInProgressClasses = 'fade';
                }
            },
            gateway_preference() {
                this.calculate();
            }
        },

        data() {
            return {
                showSubnetCalculator: true,

                calculateButtonText: 'Calculate',
                calculateButtonClasses: 'btn-dark',
                calculateButtonIconClasses: '',

                submitButtonIsDisabled: false,
                submittingButtonText: 'Add This Subnet',
                submittingButtonClasses: 'btn-dark',
                submittingButtonIconClasses: '',

                ipSliderIsMoving: false,
                workInProgressClasses: 'fade',
                gateway_preference: 'top',
                ip: '192.168.1.1',
                cidr: '24',

                calculatedSubnet: {},

                cidrSlider: {
                    formatter: '/{value}',
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
                    value: '24',
                    width: "auto",
                    height: 6,
                    direction: "horizontal",
                    dotSize: 16,
                    eventType: "auto",
                    min: 19,
                    max: 30,
                    interval: 1,
                    startAnimation: false,
                    tooltipMerge: true,
                    processDragable: false,
                    minRange: null,
                    maxRange: null,
                    fixed: false,
                    debug: false,
                    disabled: false,
                    show: true,
                    enableCross: false,
                    realTime: false,
                    tooltip: false,
                    clickable: false,
                    tooltipDir: "top",
                    piecewise: true,
                    piecewiseLabel: true,
                    lazy: false,
                    useKeyboard: true,
                    reverse: false,
                    speed: 0.3,
                    focusStyle: null,
                    bgStyle: null,
                    sliderStyle: null,
                    tooltipStyle: null,
                    processStyle: null,
                    piecewiseStyle: null,
                    disabledStyle: null,
                },

                ipSlider: {
                    data: [],
                    value: [],
                    width: "auto",
                    height: 6,
                    direction: "horizontal",
                    dotSize: 16,
                    eventType: "auto",
                    min: 1,
                    max: 254,
                    interval: 1,
                    startAnimation: false,
                    tooltipMerge: true,
                    processDragable: false,
                    minRange: 1,
                    maxRange: 254,
                    fixed: false,
                    debug: false,
                    disabled: false,
                    show: false,
                    enableCross: false,
                    realTime: false,
                    tooltip: "always",
                    clickable: true,
                    tooltipDir: "bottom",
                    piecewise: false,
                    piecewiseLabel: false,
                    lazy: false,
                    useKeyboard: true,
                    reverse: false,
                    speed: 0.5,
                    focusStyle: null,
                    bgStyle: null,
                    sliderStyle: null,
                    tooltipStyle: null,
                    processStyle: null,
                    piecewiseStyle: null,
                    disabledStyle: null,
                },
            }
        },

        methods: {
            cancelForm() {
                this.calculatedSubnet = {};
            },
            changeIpSlider(x) {
                this.ipSliderIsMoving = true;
                this.recalculateNumberOfUsableIps(x);
            },
            changeCidrSlider(x) {
                // console.log(x);
            },
            ipSliderDragEnd(x) {
                this.ipSliderIsMoving = false;
            },
            calculate() {
                this.workInProgressClasses = '';
                this.calculateButtonText = 'Calculating...';
                this.calculateButtonClasses = 'btn-warning';
                this.calculateButtonIconClasses = 'fa-spin';
                axios.post('/api/subnetcalculator', {ip:this.ip, cidr:this.cidr, gateway_preference:this.gateway_preference}).then( (response) => {
                    this.calculatedSubnet = response.data;
                    this.calculateButtonText = 'Recalculate';
                    this.calculateButtonClasses = 'btn-dark';
                    this.calculateButtonIconClasses = '';
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            },
            cidrSliderDragEnd(x) {
                this.cidr = x.value
                this.calculate();
            },
            recalculateNumberOfUsableIps(newValues) {
                let top = this.ipSlider.data.indexOf(newValues[1]);
                let bottom = this.ipSlider.data.indexOf(newValues[0]);
                let difference = top - bottom;

                this.calculatedSubnet.start_ip = newValues[0];
                this.calculatedSubnet.end_ip = newValues[1];
                this.calculatedSubnet.usable_addresses = difference + 1;
            },
            submit() {

                this.submittingButtonText = 'Creating Subnet...';
                this.submittingButtonClasses = 'btn-info';
                this.submittingButtonIconClasses = 'fa-spin';

                axios.post('/api/dhcp/dhcp_shared_networks/'+this.sharedNetwork.id+'/subnets', this.calculatedSubnet).then( (response) => {
                    this.submittingButtonText = 'Success';
                    this.submittingButtonClasses = 'btn-success';
                    this.submittingButtonIconClasses = '';
                    this.submitButtonIsDisabled = true;
                    window.location.reload();
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            },
        }
    }
</script>
