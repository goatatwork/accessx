<template>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col text-right">
                    <button class="btn btn-dark" data-toggle="collapse" data-target="#add-a-port">
                        <span class="badge badge-pill badge-warning rotate-15">NEW</span>
                        Add A Port
                    </button>
                </div>
            </div>

            <div id="add-a-port" class="row collapse">
                <div class="col">

                    <div class="row mt-2">
                        <div class="col">
                            <p>Some switches have additional ports that can be used as customer access ports. This is where you can add those ports</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <label for="Port Number">Port Number</label>
                            <input type="number" class="form-control form-control-sm" name="port_number" min="0" v-model="formData.port_number">
                        </div>

                        <div class="col">
                            <label for="Port Number">Module Number</label>
                            <input type="number" class="form-control form-control-sm" name="module" min="0" v-model="formData.module">
                        </div>

                        <div class="col">
                            <label for="Port Number">Port Name</label>
                            <input type="text" class="form-control form-control-sm" name="port_name" placeholder="optional" v-model="formData.port_name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <button type="submit" class="btn btn-sm btn-success form-control" @click="submit">
                                <span v-show="fetching" class="fas fa-spinner fa-spin"></span>
                                {{submitButtonText}}
                            </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-sm btn-danger form-control" @click="cancel">Cancel</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <hr>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="success-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 calss="modal-title" id="success-modal-label">Port Added!</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    The port was added.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="reloadPage">Got It</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            aggSlot: {},
        },

        data() {
            return {
                fetching: false,
                formData: {
                    module: 2
                },
            }
        },

        computed: {
            submitButtonText() {
                return (this.fetching) ? 'Creating Port' : 'Create Port';
            }
        },

        methods: {
            cancel() {
                this.clearForm();
                this.closeCollapse();
            },

            clearForm() {
                this.formData = {
                    module: 2
                }
            },
            closeCollapse() {
                $('#add-a-port').collapse('hide');
            },
            openSuccessModal() {
                $('#success-modal').modal('show');
                console.log('open');
            },
            reloadPage() {
                setTimeout( () => {
                    location.reload(true);
                }, 500);
            },
            submit() {
                this.fetching = true;
                axios.post('/api/infrastructure/slots/'+this.aggSlot.id+'/ports', this.formData).then( (response) => {
                    this.fetching = false;
                    this.clearForm();
                    this.openSuccessModal();
                }).catch( (error) => {
                    this.fetching = false;
                    console.log(error.response.data);
                });
            }
        }
    }
</script>

<style>
.rotate-15 {
    -webkit-transform: rotate(-15deg);
    -moz-transform: rotate(-15deg);
    -ms-transform: rotate(-15deg);
    -o-transform: rotate(-15deg);
    transform: rotate(-15deg);
}
</style>
