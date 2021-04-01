<template>
    <div>
        <input type="number" :name="name" class="form-control form-control-sm mb-3" :id="inputId" v-model.number="value" @change="change" @submit="submit">

        <vue-slider v-model="value" :min="min" :max="max" :tooltip="'none'" @change="change" @submit="submit"></vue-slider>

        {{ kilobits }}kbps <span class="font-weight-bold">({{ megabits }}mbps)</span>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/material.css'

    export default {
        props: {
            max: {
                // type: Number,
                default: 1000000
            },
            min: {
                // type: Number,
                default: 1
            },
            name: {
                type: String,
                default() {
                    return 'slider-name'
                }
            },
            initialValue: {
                type: Number,
                default: 1
            }
        },

        components: {
            VueSlider
        },

        computed: {
            announcement() {
                return {
                    name: this.name,
                    value: this.value
                }
            },
            inputId() {
                return this.name+'-input';
            },

            kilobits() {
                return this.value;
            },

            megabits() {
                return this.value / 1000;
            },
        },

        data() {
            return {
                value: this.initialValue,
            }
        },

        methods: {
            change() {
                this.$emit('change', this.announcement);
            },
            submit() {
                console.log('submit');
            }
        },

        watch: {
            initialValue(x) {
                if ((x >= this.min) && (x <= this.max)) {
                    this.value = x;
                }
            }
        }
    }
</script>
