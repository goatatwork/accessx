<template>
    <div class="container-fluid">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/provisioning">Provisioning</a></li>
                <li class="breadcrumb-item active" aria-current="page">Provisioning By Service Location</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-4">
                <service-location-card :location="location"></service-location-card>
            </div>
            <div class="col-8">
                <provision-by-service-location-form :location="location"></provision-by-service-location-form>
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
