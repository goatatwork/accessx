<template>
    <div class="container-fluid">

        <div class="row">

            <ont-card :ont="ont"></ont-card>

            <div class="col-9">
                <!-- <add-ont-file-form :ont="ont"></add-ont-file-form> -->
                <ul class="list-unstyled">
                    <media-file v-for="file in mediaFiles" :media-file="file" :key="file.id"></media-file>
                </ul>

                <div v-if="!mediaFiles.length" class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="well flex-center">
                            THERE ARE NO FILES HERE
                        </div>
                    </div>
                </div>
            </div>

            <ont-software :ont="ont"></ont-software>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            ont: {}
        },

        data: function() {
            return {
                mediaFiles: this.ont.media,
            }
        },

        created: function() {
            EventBus.$on('media-file-was-deleted', function(mediaFile) {
                let index = this.mediaFiles.indexOf(mediaFile);
                this.mediaFiles.splice(index,1);
            }.bind(this));
            EventBus.$on('ont-file-was-added', function(mediaFile) {
                this.mediaFiles.push(mediaFile);
            }.bind(this));
        },

        beforeDestroy: function() {
            EventBus.$off();
        }
    }
</script>
