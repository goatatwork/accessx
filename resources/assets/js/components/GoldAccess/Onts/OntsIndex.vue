<template>
    <div class="container-fluid">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">ONTs</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col">

                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <dl>
                                    <dt>Total ONTs</dt>
                                    <dd>There are {{ onts.length }} ONTs</dd>
                                </dl>
                            </div>
                            <div class="col">
                                <a href="/onts/create" class="btn btn-secondary float-right">Create An ONT</a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <ont-card v-for="ont in onts" :ont="ont" :key="ont.id"></ont-card>
        </div>

        <div class="row" v-if="!onts.length" >
            <div class="col">
                THERE ARE NO ONTS HERE
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
