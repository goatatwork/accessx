<template>
    <tr>
        <td>
            <a :href="record.record_url">
                View
            </a>
        </td>
        <td>
            <a :href="record.customer_url">
                {{record.customer}}
            </a>
        </td>
        <td>
            <a :href="record.service_location_url">
                {{record.address}}
            </a>
        </td>
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
        },
    }
</script>
