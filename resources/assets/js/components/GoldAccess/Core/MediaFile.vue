<template>
    <div class="media">
        <span class="fas fa-2x fa-cloud-upload-alt mr-3"></span>
        <div class="media-body">
            <div class="row">
                <div class="col">
                    <a :href="mediaFile.url">{{ mediaFile.file_name }}</a>
                    <small><span class="font-italic">{{ mediaFile.human_readable_size }}</span></small>
                </div>
                <div class="col">
                    {{ mediaFile.custom_properties.description }}
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
