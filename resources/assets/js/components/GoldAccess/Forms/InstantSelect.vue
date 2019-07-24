<template>
    <div :id="divId">

        <select :id="selectId" class="custom-select" name="ont_profile_id" @change="updateChangeValue($event.target.value)">
            <option v-for="(option, index) in theSelectOptions" :value="index" :selected="isSelected(index)" :disabled="isDisabled(index)">{{ option }}</option>
        </select>

        <div class="modal fade"
            tabindex="-1"
            role="dialog"
            :id="confirmModal.id"
            :aria-labelledby="confirmModal.label"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5  :id="confirmModal.label" class="modal-title">Please Confirm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to change the {{ modelName }}?
                    </div>
                    <div class="modal-footer">
                        <form method="POST" :action="formAction">

                            <input type="hidden" name="_token" :value="csrf">
                            <input type="hidden" name="_method" value="PATCH">

                            <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button>
                            <button type="submit" class="btn btn-link text-dark float-right" @click="submitChange">
                                <i v-show="working" class="fas fa-spinner fa-spin"></i>
                                Yes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            currentValue: {
                type: [ Number, String ],
                default: 0,
                coerce: function (val) {
                    return val * 1;
                }
            },
            divId: {
                type: String,
                default: 'instant-select-component'
            },
            fieldToPatch: {
                type: String,
                default: 'somefield',
            },
            formAction: {
                type: String,
                default: '#'
            },
            modelName: {
                type: String,
                default: 'Thing',
            },
            selectId: {
                type: String,
                default: 'select-0'
            },
            successAction: {
                type: String,
                default: '#'
            },
            selectOptions: {
                type: String,
                default: function() {
                    return '{ "0": "Generic Select Option 0", "1": "Generic Select Option 1" }';
                }
            },
        },

        computed: {
            theSelectOptions: function() {
                return JSON.parse(this.selectOptions, (key, value) => {
                    return value;
                });
            }
        },

        data: function() {
            return {
                changeValue: this.currentValue,
                confirmModal: {
                    id: 'instant-select-confirm-modal',
                    label: 'instant-select-confirm-modal-label',
                },
                csrf: window.axios.defaults.headers.common['X-CSRF-TOKEN'],
                formData: {},
                working: false,
            }
        },

        methods: {
            isDisabled: function(id) {
                return id == this.changeValue;
            },
            isSelected: function(id) {
                return id == this.changeValue;
            },
            onFail: function(data) {
                console.log(data);
            },
            onSuccess: function(data) {
                window.location.href = this.successAction;
            },
            submitChange: function() {
                this.working = true;
                this.$set(this.formData, 'reboot', true);
                axios.patch(this.formAction, this.formData).then( (response) => {
                    this.onSuccess(response.data);
                    this.working = false;
                }).catch( (error) => {
                    this.onFail(error.response.data);
                    this.working = false;
                });
            },
            updateChangeValue: function(x) {
                $('#'+this.confirmModal.id).modal('show');
                this.changeValue = x;
                this.$set(this.formData, this.fieldToPatch, x);
            }
        }
    }
</script>
