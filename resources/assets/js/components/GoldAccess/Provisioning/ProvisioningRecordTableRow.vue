<template>
    <tr>
        <td>
            <small>
                <i class="material-icons">explore</i>
                <a :href="record.record_url">
                     View
                </a>
            </small>
        </td>
        <td v-show="showing.customer_name">
            <small>
                <i class="material-icons">{{ customerTypeIcon }}</i>
                <a :href="record.customer_url">
                    {{record.customer}}
                </a>
            </small>
        </td>
        <td v-show="showing.service_location">
            <small>
                <a :href="record.service_location_url">
                    {{record.address}}
                </a>
            </small>
        </td>
        <td v-show="showing.ont"><small>{{ record.ont }}</small></td>
        <td v-show="showing.ont_software"><small>{{ record.ont_software }}</small></td>
        <td v-show="showing.package"><small>{{record.package}}</small></td>
        <td v-show="showing.aggregator"><small>{{record.aggregator}}</small></td>
        <td v-show="showing.slot"><small>{{record.slot}}</small></td>
        <td v-show="showing.port"><small>{{record.port}}</small></td>
        <td v-show="showing.management_ip"><small>{{record.management_ip}}</small></td>
        <td v-show="showing.len"><small>{{ record.len }}</small></td>
    </tr>
</template>

<script>
    export default {
        props: {
            record:  {},
            showing: {},
        },

        computed: {
            customerTypeIcon: function() {
                return (this.record.customer_type == 'Business') ? 'business' : 'person';
            }
        }
    }
</script>
