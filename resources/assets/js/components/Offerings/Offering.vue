<template>
    <tr>
        <td class="text-center">
            {{ offering.name }}
        </td>
        <td class="text-center">
            {{ inMegabits(offering.down_rate) }}M / {{ inMegabits(offering.up_rate) }}M
        </td>
        <td class="text-right">

            <span role="button" class="fa fa-edit" @click="selectForEditing"></span>
            <span role="button" class="fa fa-trash" data-toggle="modal" :data-target="deleteModalTarget"></span>
            <delete-offering-modal :offering="offering" @offering-deleted="eventOfferingDeleted"></delete-offering-modal>
        </td>
    </tr>
</template>

<script>
    var DeleteOfferingModal = Vue.extend(require('./DeleteOfferingModal.vue'));

    export default {
        props: {
            offering: {}
        },

        components: {
            'delete-offering-modal': DeleteOfferingModal,
        },

        computed: {
            deleteModalTarget() {
                return '#delete-modal-for-'+this.offering.id;
            }
        },

        methods: {
            eventOfferingDeleted(offering) {
                this.$emit('offering-deleted', offering);
            },
            inMegabits(x) {
                return x / 1000;
            },
            selectForEditing() {
                this.$emit('select-for-editing', this.offering);
            },
        }
    }
</script>
