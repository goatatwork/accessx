<template>
    <div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/provisioning">Provisioning Records</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="font-italic">
                        {{ location.address1 }} {{ location.city }}, {{ location.state }}  {{ location.zip }} - {{ customerDetails.customer_name }}
                    </span>
                </li>
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
    export default {
        props: {
            location: {},
        },

        data: function() {
            return {
                customerDetails: {},
            }
        },

        created: function() {
            this.fetchCustomer();
        },

        methods: {
            fetchCustomer: function() {
                axios.get('/api/customers/'+this.location.customer_id).then(response => {
                    this.customerDetails = response.data;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>
