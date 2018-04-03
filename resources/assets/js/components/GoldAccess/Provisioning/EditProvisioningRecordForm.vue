<template>
    <div class="row">
        <div class="col">
            <pre>{{ provisioningRecord }}</pre>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            provisioningRecord: {},
        }
    }
</script>
