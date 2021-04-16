<template>
    <div class="row">
        <div class="col">

            <div class="form-group">
                <label for="ont_id">Speed</label>
                <select class="form-control" name="ont_id" @change="speedWasSelected($event.target.value)">
                    <option value="">Select</option>
                    <option v-for="package in packages" :value="package.id">{{ package.name }}</option>
                </select>
                <span v-show="fetching" class="text-danger">Fetching Speed Packages...</span>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fetching: false,
                packages: {}
            }
        },

        mounted() {
            this.fetchPackages();
        },

        methods: {
            fetchPackages() {
                this.fetching = true;
                axios.get('/api/packages').then(response => {
                    this.packages = response.data
                    this.fetching = false;
                }).catch(error => {
                    console.log(error);
                    this.fetching = false;
                });
            },
            speedWasSelected(id) {
                this.$emit('speed-was-selected', id);
            }
        }
    }
</script>
