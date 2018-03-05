<template>
    <div id="media-file-list">
        <media-file v-for="(media_file) in theList" :key="media_file.id" :media-file="media_file" v-on:media-file-was-deleted="removeItemFromList"></media-file>
    </div>
</template>

<script>
    export default {
        props: {
            listOf: {},
            uploadComponent: '',
        },

        data: function() {
            return {
                theList: this.listOf,
            }
        },

        created: function() {
            this.initializeEventBus();
        },

        methods: {
            initializeEventBus: function() {
                EventBus.$on('ont-file-was-added', function(payload) {
                    if (payload.tag == this.uploadComponent) {
                        this.listOf.unshift(payload.file);
                    }
                }.bind(this));
            },
            removeItemFromList: function(mediaFile, tag) {
                let index = this.theList.indexOf(mediaFile);
                this.theList.splice(index, 1);
            }
        }
    }
</script>
