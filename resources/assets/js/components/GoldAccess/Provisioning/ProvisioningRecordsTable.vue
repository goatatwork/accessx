<template>
    <div class="row">
        <div class="col">
            <pre>{{ provisioningRecords }}</pre>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            provisioningRecords: {}
        }
    }
</script>
