<template>
    <div :id="link_id" class="d-inline pr-2">
        <a :href="destination_url" :class="link_class" @click.prevent="clicked">
            {{ page }}
        </a>
    </div>
</template>

<script>
    export default {
        props: {
            current_page: {
                required: true
            },
            destination_page: {
                required: true
            },
            records_per_page: {
                required: true
            }
        },

        computed: {
            link_id() {
                return 'link-to-'+this.page;
            },
            link_class() {
                return {
                    'text-dark': this.current_page == this.page,
                    'font-weight-bold': this.current_page == this.page
                }
            },
            page() {
                return this.destination_page;
            },
            destination_url() {
                return '?page='+this.page+'&records_per_page='+this.records_per_page;
            }
        },

        methods: {
            clicked() {
                this.$emit('clicked', this.page);
            }
        }
    }
</script>
