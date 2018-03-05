<template>
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <div class="col-md-4">
                    <service-location-card :location="location"></service-location-card>
                </div>
                <div class="col-md-8">
                    <provision-by-service-location-form :location="location"></provision-by-service-location-form>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    var ServiceLocationCard = Vue.extend(require('../Customers/ServiceLocationCard.vue'));
    var ProvisionByServiceLocationForm = Vue.extend(require('./ProvisionByServiceLocationForm.vue'));

    export default {
        props: {
            location: {},
        },

        components: {
            'service-location-card': ServiceLocationCard,
            'provision-by-service-location-form': ProvisionByServiceLocationForm,
        }
    }
</script>
