<template>
    <div>
        <!-- START OF deleteFile MODAL -->
        <div class="modal fade"
            tabindex="-1"
            role="dialog"
            :id="modalId"
            :aria-labelledby="modalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5  :id="modalLabel" class="modal-title">Change Package</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                            Switch {{recordId}} to the {{ratePackage.name}} package?
                            <br>
                            <pre>{{ formData }}</pre>
                            <pre>{{ response }}</pre>
                        </slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <button
                                type="button"
                                class="btn btn-dark float-right"
                                data-dismiss="modal"
                            >
                                Dismiss
                            </button>

                            <button
                                type="submit"
                                class="btn btn-link text-dark float-right"

                                @click="confirm()"
                            >
                                <span class="fa fa-spinner fa-spin" v-show="working"></span> Confirm Change
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF deleteFile MODAL -->

    </div>
</template>

<script>
    export default {
        props: {
            ratePackage: {},
            recordId: ''
        },

        computed: {
            modalLabel: function() {
                return 'changePackageModalLabel-'+this.ratePackage.id;
            },
            modalId: function() {
                return 'changePackageModal-'+this.ratePackage.id;
            },
            modalRef: function() {
                return '#changePackageModal-'+this.ratePackage.id;
            },
        },

        data() {
            return {
                formAction: '/api/provisioning/'+this.recordId,
                formData: {
                    package_change: true,
                    provisioning_record_id: this.recordId,
                    package_id: this.ratePackage.id
                },
                response: {},
                working: false
            }
        },

        methods: {
            confirm: function() {
                this.submitChange();
            },
            onSuccess(pr) {
                this.response = pr;
            },
            submitChange: function() {
                this.working = true;
                this.$set(this.formData, 'package_change', true);
                axios.patch(this.formAction, this.formData).then( (response) => {
                    this.onSuccess(response.data);
                    this.working = false;
                }).catch( (error) => {
                    this.onFail(error.response.data);
                    this.working = false;
                });
            },
        }
    }
</script>
