<template>
    <div class="row mb-5 justify-content-center">
        <div class="col-8">

            <setting v-for="setting in settings" :key="setting.id" :setting="setting"></setting>

        </div>
    </div>
</template>

<script>
    var Setting = Vue.extend(require('./GaSetting.vue'));

    export default {
        components: {
            'setting': Setting,
        },

        data: function() {
            return {
                settings: {}
            }
        },

        created: function() {
            this.fetchSettings();
        },

        methods: {
            fetchSettings: function() {
                axios.get('/api/settings').then(response => {
                    this.settings = response.data;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>
