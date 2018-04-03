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
                <service-location-card :location="serviceLocation"></service-location-card>
            </div>
            <div class="col-8">
                <!-- <provision-by-service-location-form :location="location"></provision-by-service-location-form> -->
                <edit-provisioning-record-form :provisioning-record="provisioningRecord"></edit-provisioning-record-form>
            </div>
        </div>

    </div>
</template>

<script>
    var EditProvisioningRecordForm = Vue.extend(require('./EditProvisioningRecordForm.vue'));

    export default {
        props: {
            provisioningRecord: {},
            serviceLocation: {},
        },

        components: {
            'edit-provisioning-record-form': EditProvisioningRecordForm,
        }
    }
</script>
