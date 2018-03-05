<template>
    <div class="card">
        <div class="card-header" id="add-subnet-card">
            <h5 class="mb-0">

                <button class="btn btn-link" data-toggle="collapse" data-target="#add-subnet-card-body" :aria-expanded="cardIsOpen" aria-controls="#add-subnet-card-body" @click="toggledAddSubnetCard()">
                    Add A Subnet
                </button>

                <button v-if="cardIsOpen" class="btn btn-link float-right text-danger" data-toggle="collapse" data-target="#add-subnet-card-body" :aria-expanded="cardIsOpen" aria-controls="#add-subnet-card-body" @click="toggledAddSubnetCard()">
                    Cancel
                </button>

            </h5>
        </div>

        <div id="add-subnet-card-body" class="collapse" :class="collapseClasses" aria-labelledby="add-subnet-card" data-parent="#accordion">
            <div class="card-body">

                <add-dhcp-subnet-form :shared-network="sharedNetwork"></add-dhcp-subnet-form>

            </div>
        </div>
    </div>
</template>

<script>
    var AddDhcpSubnetForm = Vue.extend(require('./AddDhcpSubnetForm.vue'));

    export default {
        props: {
            sharedNetwork: {},
        },

        components: {
            'add-dhcp-subnet-form': AddDhcpSubnetForm,
        },

        data: function() {
            return {
                cardIsOpen: false,
            }
        },

        computed: {
            collapseClasses: function() {
                return this.cardIsOpen ? 'show' : 'collapse';
            }
        },

        created: function() {
            this.initializeEventBus();
        },

        beforedestroy: function() {
            this.initializeEventBus();
        },

        methods: {
            bounceToPage: function() {
                let self = this;
                setTimeout(function() {
                    console.log('bla');
                    window.location.href = '/dhcp/shared_networks/'+self.sharedNetwork.id;
                }, 300);
            },
            hideCard: function() {
                this.cardIsOpen = false;
            },
            initializeEventBus: function() {
                EventBus.$on('subnet-was-added', function(subnet) {
                    this.hideCard();
                    this.bounceToPage();
                }.bind(this));
            },
            toggledAddSubnetCard: function() {
                this.cardIsOpen = ! this.cardIsOpen;
                EventBus.$emit('refresh-slider');
            }
        }
    }
</script>
