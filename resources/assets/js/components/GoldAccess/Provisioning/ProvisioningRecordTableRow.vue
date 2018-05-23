<template>
    <tr>
        <td>
            <i class="material-icons">explore</i>
            <a :href="record.record_url">
                 View
            </a>
        </td>
        <td>
            <i class="material-icons">{{ customerTypeIcon }}</i>
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
        <td>{{ record.ont }}</td>
        <td>{{record.management_ip}}</td>
        <td>
            <ul class="list-unstyled small">
                <a :href="record.aggregator_url">
                <li>{{ record.aggregator }}</li>
                <li class="pl-3"><span class="fas fa-long-arrow-alt-right"></span> Slot {{ record.slot }}</li>
                <li class="pl-5"><span class="fas fa-long-arrow-alt-right"></span> Port {{ record.port }}</li>
                </a>
            </ul>

        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            record:  {},
        },

        computed: {
            customerTypeIcon: function() {
                return (this.record.customer_type == 'Business') ? 'business' : 'person';
            }
        }
    }
</script>
