<template>
    <div class="modal fade" :id="modalId" tabindex="-1" role="dialog" :aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="modalLabel">{{ user.name }}</h5>
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

                        <div class="row mb-3">
                            <div class="col d-flex flex-row-reverse">
                                <div class="custom-control custom-toggle my-2">
                                    <input type="checkbox"
                                        :id="formPasswordChangeToggleId"
                                        name="reset_password"
                                        class="custom-control-input"
                                        v-model="formData.reset_password"
                                    >
                                    <label class="custom-control-label" :for="formPasswordChangeToggleId">Reset Password</label>
                                </div>
                            </div>
                        </div>

                        <div v-show="showPasswordForm" class="row animated"  :class="toggleClass">
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
                return 'email-for-user-'+this.user.id;
            },
            formNameId: function() {
                return 'name-for-user-'+this.user.id;
            },
            formPasswordChangeToggleId: function() {
                return 'change-password-for-user-'+this.user.id;
            },
            formPasswordId: function() {
                return 'password-for-user-'+this.user.id;
            },
            formRadioAdminRoleId: function() {
                return 'admin-role-for-user-'+this.user.id;
            },
            formRadioGuestRoleId: function() {
                return 'guest-role-for-user-'+this.user.id;
            },
            formRadioTechnicianRoleId: function() {
                return 'technician-role-for-user-'+this.user.id;
            },
            formPasswordConfirmationId: function() {
                return 'password-confirmation-for-user-'+this.user.id;
            },
            modalId: function() {
                return 'user-modal-'+this.user.id;
            },
            modalLabel: function() {
                return 'user-modal-'+this.user.id+'-label';
            },
            showPasswordForm: function() {
                return this.formData.reset_password;
            },
            toggleClass: function() {
                return (this.formData.reset_password) ? 'fadeIn' : 'fadeOut';
            }
        },

        data: function() {
            return {
                roleSelected: {},
                formData: {
                    'id': this.user.id,
                    'name': this.user.name,
                    'email': this.user.email,
                    'role': '',
                    'reset_password': false,
                    'password': '',
                    'password_confirmation': ''
                }
            }
        },

        created: function() {
            this.getFormResource();
        },

        methods: {
            fetchRoleWithPermissions: function() {

            },
            getFormResource: function() {
                axios.get('/api/authorization/users/'+this.user.id+'/role').then(response => {
                    this.roleSelected = response.data;
                    this.formData.role = response.data.name;
                }).catch(error => {
                    console.log(error);
                });
            },
            submitForm: function() {
                axios.patch('/api/authorization/users/'+this.user.id, this.formData).then( (response) => {
                    $('#'+this.modalId).modal('hide');
                    this.announceUserChange(response.data);
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            },
            announceUserChange: function(user) {
                EventBus.$emit('user-was-updated', user);
            }
        }

    }
</script>
