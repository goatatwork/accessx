<template>
    <tr>
        <td nowrap>{{ log.created_at_for_humans }}</td>
        <td>{{ log.level }}</td>
        <td><small>{{ log.calling_class }}@{{ log.calling_function }}</small></td>
        <td><small>{{ log.message }}</small></td>
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
