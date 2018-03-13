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
                <div class="card">
                    <div class="card-body text-center">
                        <iframe
                            width="400"
                            height="200"
                            frameborder="0"
                            style="border:0"
                            :src="location.google_maps_embed_api_string"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
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
