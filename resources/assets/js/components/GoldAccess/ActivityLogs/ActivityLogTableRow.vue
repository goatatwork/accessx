<template>
    <tr>
        <td>{{ log.created_at }}</td>
        <td>{{ log.level }}</td>
        <td>{{ log.calling_class }}@{{ log.calling_function }}</td>
        <td>{{ log.message }}</td>
        <td><button type="button" class="btn btn-dark btn-sm">F</button></td>
    </tr>
</template>

<script>
    export default {
        props: {
            log: {},
        }
    }
</script>
