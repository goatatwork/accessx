<template>
    <div class="media mb-3">

        <a :href="mediaFile.url" download class="mr-2"><i class="material-icons">file_download</i></a>

        <div class="media-body">
            <div class="row">
                <div class="col-auto">
                    <a :href="mediaFile.url">{{ mediaFile.file_name }}</a>
                    <small><span class="font-italic">{{ mediaFile.human_readable_size }}</span></small>
                </div>
                <div class="col">
                    <small>{{ mediaFile.custom_properties.description }}</small>
                </div>
            </div>
        </div>
        <div class="media-right">
            <modal :title="mediaFile.name" :to-be-deleted="mediaFile" v-on:delete-the-object="deleteTheObject()">
                <div slot="body">
                    <p>Are you sure you wish to delete <strong>{{ mediaFile.file_name }}</strong>?</p>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
    var DeleteModal = Vue.extend(require('./DeleteModal.vue'));

    export default {
        props: {
            mediaFile: {},
        },

        components: {
            'modal': DeleteModal,
        },

        methods: {
            deleteTheObject: function() {
                axios.delete('/api/onts/files/'+this.mediaFile.id).then( (response) => {
                    this.$emit('media-file-was-deleted', this.mediaFile);
                }).catch((error) => {
                    console.log(error);
                });
            },
        }
    }
</script>
