<template>
        <div v-html="theContent"></div>
</template>

<script>
    import marked from 'marked';

    export default {
        props: {
            markedContent: '',
        },

        computed: {
            theContent: function() {
                return marked(this.markedContent);
            }
        }
    }
</script>
