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
        <td>
            <small>
                <i class="material-icons">{{ customerTypeIcon }}</i>
                <a :href="record.customer_url">
                    {{record.customer}}
                </a>
            </small>
        </td>
        <td>
            <small>
                <a :href="record.service_location_url">
                    {{record.address}}
                </a>
            </small>
        </td>
        <td><small>{{record.package}}</small></td>
        <td><small>{{ record.ont }}</small></td>
        <td><small>{{record.management_ip}}</small></td>
        <td><small>{{ record.len }}</small></td>
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
