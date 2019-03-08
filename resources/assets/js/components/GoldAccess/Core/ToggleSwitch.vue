<template>
    <div class="btn-group btn-group-sm" role="group" aria-label="Button Group">
        <button type="button" class="btn btn-sm btn-outline-success" :class="{ active: isOn }" @click="toggleOn">{{ onButtonText }}</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" :class="{ active: isOff }" @click="toggleOff">{{ offButtonText }}</button>
    </div>
</template>

<script>
    export default {
        props: {
            defaultState: {
                type: String,
                default() {
                    return 'off';
                }
            }
        },

        computed: {
            isOn() {
                return (this.current_state == 'on') ? true : false;
            },
            isOff() {
                return (this.current_state == 'off') ? true : false;
            },
            onButtonText() {
                return (this.isOn) ? 'Clocks Are On' : 'Turn On';
            },
            offButtonText() {
                return (this.isOff) ? 'Clocks Are Off' : 'Turn Off';
            }
        },

        data() {
            return {
                current_state: this.defaultState,
            }
        },

        methods: {
            toggleOn() {
                this.current_state = 'on';
                this.$emit('toggled', {'new_state': 'on'});
            },
            toggleOff() {
                this.current_state = 'off';
                this.$emit('toggled', {'new_state': 'off'});
            }
        }
    }
</script>
