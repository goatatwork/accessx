<template>
    <div class="row">
        <div class="col">

            <form method="POST" action="">
                <input type="hidden" name="_token" :value="csrfToken">

                <div class="form-group">
                    <label for="name">Offering Name</label>
                    <input type="text" class="form-control form-control-sm" placeholder="100M down / 50M up" v-model="formData.name">
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="downrate-input">Down <span class="font-italic">(1-1,000,000 kbps)</span></label>
                            <form-slider name="down_rate" :initial-value="Number(formData.down_rate)" @change="sliderChange"></form-slider>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="uprate-input">Up <span class="font-italic">(1-1,000,000 kbps)</span></label>
                            <form-slider name="up_rate" :initial-value="Number(formData.up_rate)" @change="sliderChange"></form-slider>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right mt-3">
                    <button class="btn btn-sm btn-outline-success" @click.prevent="submit">Save</button>
                    <button class="btn btn-sm btn-outline-danger" @click.prevent="cancel">Cancel</button>
                </div>

            </form>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            offering: {
                type: Object,
                required: false,
            }
        },

        computed: {
            csrfToken() {
                return (window.Laravel.csrf_token) ? window.Laravel.csrf_token : '';
            },
            formAction() {
                return (this.offering.id) ? 'patch' : 'post';
            },
        },

        data() {
            return {
                formData: {
                    name: '',
                    down_rate: 50000,
                    up_rate: 50000
                },
            }
        },

        watch: {
            offering(newValue,oldValue) {
                this.formData.name = newValue.name;
                this.formData.down_rate = (newValue.down_rate) ? newValue.down_rate : 50000;
                this.formData.up_rate = (newValue.up_rate) ? newValue.up_rate : 50000;
            }
        },

        methods: {
            cancel() {
                this.resetForm();
                this.$emit('form-cancelled');
            },
            formPost() {
                axios.post('/api/packages', this.formData).then( (response) => {
                    this.$emit('package-added', response.data);
                    this.resetForm();
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            },
            formPatch() {
                axios.patch('/api/packages/'+this.offering.id, this.formData).then( (response) => {
                    this.$emit('package-updated', response.data);
                    this.resetForm();
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            },
            resetForm() {
                this.formData.name = '';
                this.formData.down_rate = 50000;
                this.formData.up_rate = 50000;
            },
            sliderChange(data) {
                this.$set(this.formData, data.name, data.value);
            },
            submit() {
                if (this.formAction == 'post') {
                    this.formPost();
                }
                if (this.formAction == 'patch') {
                    this.formPatch();
                }
            },
        }
    }
</script>

