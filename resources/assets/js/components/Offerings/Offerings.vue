<template>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="text-center">Package Name</th>
                                <th class="text-center">Down(k) / Up(k)</th>
                                <th class="text-right">
                                    <span role="button" class="material-icons-two-tone align-middle">add</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr is="offering" v-for="offering in offerings" :key="offering.id" :offering="offering" @offering-deleted="eventOfferingDeleted" @select-for-editing="selectForEditing"></tr>
                        </tbody>
                    </table>
                </div>
                <div class="col">

                    <offering-form :offering="offeringToEdit" @package-added="eventPackageAdded" @package-updated="eventPackageUpdated" @form-cancelled="formCancelled"></offering-form>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var Offering = Vue.extend(require('./Offering.vue'));
    var OfferingForm = Vue.extend(require('./OfferingForm.vue'));

    export default {
        props: {
            allOfferings: {}
        },

        components: {
            'offering': Offering,
            'offering-form': OfferingForm,
        },

        data() {
            return {
                offerings: this.allOfferings,
                offeringToEdit: {}
            }
        },

        methods: {
            eventPackageAdded(data) {
                this.offerings.push(data);
            },
            eventPackageUpdated(offering) {
                this.fetchOfferings();
            },
            eventOfferingDeleted(offering) {
                var index = this.offerings.indexOf(offering);
                this.offerings.splice(index, 1);
            },
            fetchOfferings() {
                axios.get('/api/packages').then(response => {
                    this.offerings = response.data
                }).catch(error => {
                    console.log(error);
                });
            },
            formCancelled() {
                this.offeringToEdit = {};
            },
            selectForEditing(offering) {
                this.offeringToEdit = offering;
            }
        }
    }
</script>
