<template>
    <div class="modal fade" id="create-user-modal" tabindex="-1" role="dialog" aria-labelledby="create-user-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="create-user-modal-label">Create User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">

                        <div class="row mb-5">
                            <div class="col d-flex flex-column text-center p-3" :class="{ 'border border-primary': formData.role == 'admin' }">
                                <span class="fas fa-2x fa-fw fa-user-shield text-primary align-self-center mb-2"></span>
                                <span class="text-secondary">Admin</span>
                                <div class="form-check">
                                        <input type="radio"
                                            :id="formRadioAdminRoleId"
                                            name="role"
                                            class="form-check-input position-static"
                                            value="admin"
                                            v-model="formData.role"
                                        >
                                        <label class="custom-control-label sr-only" :for="formRadioAdminRoleId"></label>
                                </div>
                            </div>

                            <div class="col d-flex flex-column text-center p-3" :class="{ 'border border-success': formData.role == 'technician' }">
                                <span class="fas fa-2x fa-fw fa-user-ninja text-success align-self-center mb-2"></span>
                                <span class="text-secondary">Technician</span>
                                <div class="form-check">
                                        <input type="radio"
                                            :id="formRadioTechnicianRoleId"
                                            name="role"
                                            class="form-check-input position-static"
                                            value="technician"
                                            v-model="formData.role"
                                        >
                                        <label class="custom-control-label sr-only" :for="formRadioTechnicianRoleId"></label>
                                </div>
                            </div>
                            <div class="col d-flex flex-column text-center p-3" :class="{ 'border border-warning': formData.role == 'guest' }">
                                <span class="fas fa-2x fa-fw fa-user-astronaut text-warning align-self-center mb-2"></span>
                                <span class="text-secondary">Guest</span>
                                <div class="form-check">
                                        <input type="radio"
                                            :id="formRadioGuestRoleId"
                                            name="role"
                                            class="form-check-input position-static"
                                            value="guest"
                                            v-model="formData.role"
                                        >
                                        <label class="custom-control-label sr-only" :for="formRadioGuestRoleId"></label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name" class="sr-only">Name</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="far fa-user"></span>
                                            </div>
                                        </div>
                                        <input type="text"
                                            class="form-control form-control-sm"
                                            :id="formNameId"
                                            name="name"
                                            aria-describedby="nameHelp"
                                            placeholder="Enter the user's name"
                                            v-model="formData.name"
                                        >
                                    </div>

                                    <small id="nameHelp" class="form-text text-muted"></small>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="sr-only">Email</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="far fa-envelope"></span>
                                            </div>
                                        </div>
                                        <input type="email"
                                            class="form-control form-control-sm"
                                            :id="formEmailId"
                                            name="email"
                                            aria-describedby="emailHelp"
                                            placeholder="Enter the user's email address"
                                            v-model="formData.email"
                                        >
                                    </div>

                                    <small id="emailHelp" class="form-text text-muted"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="password" class="sr-only">Password</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="fas fa-key"></span>
                                            </div>
                                        </div>
                                        <input type="password"
                                            class="form-control form-control-sm"
                                            :id="formPasswordId"
                                            name="password"
                                            aria-describedby="passwordHelp"
                                            placeholder="Enter the new password"
                                            v-model="formData.password"
                                        >
                                    </div>

                                    <small id="passwordHelp" class="form-text text-muted"></small>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation" class="sr-only">Confirm Password</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="fas fa-key"></span>
                                            </div>
                                        </div>
                                        <input type="password"
                                            class="form-control form-control-sm"
                                            :id="formPasswordConfirmationId"
                                            name="password_confirmation"
                                            aria-describedby="passwordConfirmationHelp"
                                            placeholder="Confirm the new password"
                                            v-model="formData.password_confirmation"
                                        >
                                    </div>

                                    <small id="passwordConfirmationHelp" class="form-text text-muted"></small>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" @click.prevent="submitForm">Save changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: {},
        },

        computed: {
            formEmailId: function() {
                return 'email';
            },
            formNameId: function() {
                return 'name';
            },
            formPasswordChangeToggleId: function() {
                return 'change-password';
            },
            formPasswordId: function() {
                return 'password';
            },
            formRadioAdminRoleId: function() {
                return 'admin-role';
            },
            formRadioGuestRoleId: function() {
                return 'guest-role';
            },
            formRadioTechnicianRoleId: function() {
                return 'technician-role';
            },
            formPasswordConfirmationId: function() {
                return 'password-confirmation';
            },
        },

        data: function() {
            return {
                formData: {
                    'name': '',
                    'email': '',
                    'role': '',
                    'password': '',
                    'password_confirmation': ''
                }
            }
        },

        methods: {
            submitForm: function() {
                axios.post('/api/authorization/users', this.formData).then( (response) => {
                    $('#create-user-modal').modal('hide');
                    this.announceUserCreate(response.data);
                    console.log(response.data);
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            },
            announceUserCreate: function(user) {
                EventBus.$emit('user-was-updated', user);
            }
        }

    }
</script>
