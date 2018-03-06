<template>
    <div class="container-fluid">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Provisioning Records</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-4">
                <service-location-card :location="location"></service-location-card>
            </div>
            <div class="col-md-8">
                some stuff here
            </div>
        </div>

    </div>
</template>

<script>
    var ServiceLocationCard = Vue.extend(require('../Customers/ServiceLocationCard.vue'));

    export default {
        props: {
            location: {},
        },

        components: {
            'service-location-card': ServiceLocationCard,
        }
    }
</script>
