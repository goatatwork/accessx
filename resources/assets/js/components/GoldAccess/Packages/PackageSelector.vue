<template>
    <div>
        <select name="package"
            class="custom-select"
            :id="selectId"
            @change="changePackage($event.target.value)"
        >
            <option v-for="package in packages"
                :id="package.id"
                :value="package.id"
                :selected="package.id == currentPackage.id"
            >
                {{ package.name }}
            </option>

        </select>

        <change-package-modal
            v-for="package in packages"
            :key="package.id"
            :rate-package="package"
            :record-id="recordId"
        ></change-package-modal>

    </div>
</template>

<script>
    var ChangePackageModal = Vue.extend(require('./ChangePackageModal.vue'));

    export default {
        props: {
            allPackages: {},
            currentPackage: {
                required: false
            },
            recordId: ''
        },

        components: {
            'change-package-modal': ChangePackageModal,
        },

        data() {
            return {
                packages: this.allPackages
            }
        },

        computed: {
            selectId() {
                return 'select_for_';
            }
        },

        mounted() {
            console.log('package selector is here');
        },

        methods: {
            changePackage: function(x) {
                $('#changePackageModal-'+x).modal('show');
                // $('#'+this.confirmModal.id).modal('show');
                // this.changeValue = x;
                // this.$set(this.formData, this.fieldToPatch, x);
            }
        }
    }
</script>
