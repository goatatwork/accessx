<template>
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <div class="col-md-12">

                    <slot name="trigger">

                        <button
                            class="btn btn-default pull-right"
                            :id="collapseTriggerId"
                            role="button"
                            data-toggle="collapse"
                            :href="collapseAreaHref"
                            aria-expanded="false"
                            :aria-controls="collapseAreaId"
                            @click="$emit('collapse-toggled')"
                        >

                            {{ buttonText }}

                        </button>

                    </slot>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div :id="collapseAreaId" class="panel-collapse collapse" role="tabpanel" :aria-labelledby="collapseTriggerId">
                        <div class="panel-body">

                            <slot name="body">
                                Please provide the content for the panel body here. It will need to be surrounded by
                            </slot>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            buttonText: {
                default: 'Button',
            },
            collapseAreaId: {
                default: 'collapseArea-0'
            },
            collapseTriggerId: {
                default: 'collapseTrigger-0'
            },
        },

        computed: {
            collapseAreaHref: function() {
                return '#'+this.collapseAreaId;
            },
        },
    }
</script>
