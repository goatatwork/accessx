<template>
    <ul class="list-group list-group-horizontal">
        <li class="list-group-item">
            Viewing records {{ meta.from }}-{{ meta.to }} of {{ meta.total }} | Page {{ meta.current_page }} of {{ meta.last_page }}
        </li>
        <li class="list-group-item">
            <a :href="links.prev" @click.prevent="link_clicked($event.target)">previous</a>
        </li>
        <li class="list-group-item">
            <a :href="links.first" @click.prevent="link_clicked($event)">first</a>
        </li>
        <li class="list-group-item">
            <a @click.prevent="getUrl('last')">last</a>
        </li>
        <li class="list-group-item">
            <a @click.prevent="getUrl('next')">next</a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            links: {},
            meta: {}
        },

        methods: {
            getUrl: function(link) {
                this.$emit('get-link', link);
            },
            link_clicked: function(x) {
                console.log(x);
            }
        }
    }
</script>
