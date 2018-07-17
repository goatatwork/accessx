<template>
    <li class="list-group-item">

        <div class="row">
            <div class="col d-flex align-items-center">
                <span class="float-left">{{ settingData.name }}</span>
            </div>
            <div class="col">

                <div class="row">
                    <div class="col d-flex align-items-center">
                        <span class="float-left">{{ setting.value }}</span>
                    </div>
                    <div class="col d-flex align-items-center justify-content-end">
                        <button class="btn btn-link text-dark" data-toggle="collapse" :data-target="collapseHref" @click="toggleOpenDetails">
                            <i class="material-icons">
                                {{ collapseToggleIcon }}
                            </i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div :id="collapseId" class="row collapse">
            <div class="col">

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="value" class="sr-only">{{ settingData.name }}</label>
                            <input type="text"
                                class="form-control form-control-sm"
                                :class="{ 'is-invalid': errors.value, 'border-success': is_success }"
                                :aria-described-by="descriptionId"
                                name="value"
                                v-model="settingData.value"
                                required
                                @keydown="delete errors.value"
                                @keydown.enter="submit"
                            >
                            <small v-show="errors.value">
                                <ul class="list-unstyled text-danger mt-3">
                                    <li v-for="error in errors.value">{{ error }}</li>
                                </ul>
                            </small>
                            <small :id="descriptionId" class="form-text text-muted">{{ settingData.description }}</small>
                        </div>
                        <div v-show="!is_success" class="form-group">
                            <button class="btn btn-sm btn-success" @click="submit">Save</button>
                            <button class="btn btn-sm btn-dark" @click="cancel">Cancel</button>
                        </div>
                        <div v-show="is_success" class="form-group">
                            <button class="btn btn-sm btn-success" disabled>SUCCESS!</button>
                            <button class="btn btn-sm btn-dark" @click="close">Close</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </li>
</template>

<script>
    export default {
        props: {
            setting: {
                type: Object,
            }
        },

        computed: {
            collapseId: function() {
                return 'setting-details-'+this.settingData.id;
            },
            collapseHref: function() {
                return '#setting-details-'+this.settingData.id;
            },
            descriptionId: function() {
                return 'setting-description-'+this.settingData.id;
            },
        },

        data: function() {
            return {
                errors: {},
                is_success: false,
                collapseToggleIcon: 'arrow_right',
                settingData: Object.assign({}, this.setting)
            }
        },

        methods: {
            cancel: function() {
                this.reset();
            },
            close: function() {
                this.is_success = false;
                $(this.collapseHref).collapse('hide');
                this.collapseToggleIcon = 'arrow_right';
            },
            reset: function() {
                this.errors = {},
                $(this.collapseHref).collapse('hide');
                this.collapseToggleIcon = 'arrow_right';
                this.settingData = Object.assign({}, this.setting);
            },
            submit: function() {
                axios.patch('/api/settings/'+this.setting.name, this.settingData).then( (response) => {
                    this.is_success = true;
                    this.settingData = response.data;
                }).catch( (error) => {
                    this.errors = error.response.data.errors;
                });
            },
            toggleOpenDetails: function() {
                this.collapseToggleIcon == 'arrow_right' ? this.collapseToggleIcon = 'arrow_drop_down' : this.collapseToggleIcon = 'arrow_right';
            },
        }
    }
</script>
