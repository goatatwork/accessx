<template>
    <tr>
        <td nowrap>{{ log.created_at_for_humans }}</td>
        <td>{{ log.level }}</td>
        <td style="max-width: 300px;overflow: scroll;word-wrap: break-word;"><small>{{ log.message }}</small></td>
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
