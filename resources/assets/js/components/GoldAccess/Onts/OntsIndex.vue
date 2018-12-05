<template>
    <div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">ONTs</li>
            </ol>
        </nav>


        <div class="row mb-5">
            <div class="col">

                <dl v-if="!onts.length" class="float-left">
                    <dt>Total ONTs</dt>
                    <dd>There are {{ onts.length }} ONTs</dd>
                </dl>

                <span class="float-right">
                    <a href="/onts/create" class="btn btn-secondary"><i class="material-icons mr-2">add</i>Create An ONT</a>
                </span>

            </div>
        </div>

        <div class="row">
            <ont-card v-for="ont in onts" :ont="ont" :key="ont.id"></ont-card>
        </div>

        <div class="row" v-if="!onts.length" >
            <div class="col">
                <span class="fas fa-spin fa-spinner"></span> FETCHING ONTS...
            </div>
        </div>

    </div>
</template>

<script>
    var OntCard = Vue.extend(require('./OntCard.vue'));

    export default {
        components: {
            'ont-card': OntCard,
        },

        data: function() {
            return {
                onts: {},
            }
        },

        created: function() {
            this.fetchOnts();
            this.initializeEventBus();
        },

        beforeDestroy: function() {
            EventBus.$off();
        },

        methods: {
            fetchOnts: function() {
                axios.get('/api/onts').then(response => {
                    this.onts = response.data
                }).catch(error => {
                    console.log(error);
                });
            },
            initializeEventBus: function() {
                EventBus.$on('ont-was-added', function(ont) {
                    this.onts.push(ont);
                }.bind(this));
            }
        }

    }
</script>
