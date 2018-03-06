<template>
    <tr>
        <td>{{record.customer}}</td>
        <td>{{record.address}}</td>
        <td>{{record.package}}</td>
        <td>{{record.management_ip}}</td>
        <td>
            {{ record.aggregator }}
            <span class="fas fa-long-arrow-alt-right"></span>
            Slot {{ record.slot }}
            <span class="fas fa-long-arrow-alt-right"></span>
            Port {{ record.port }}
        </td>
        <td>{{ record.ont }}</td>
    </tr>
</template>

<script>
    export default {
        props: {
            record:  {},
        }
    }
</script>
