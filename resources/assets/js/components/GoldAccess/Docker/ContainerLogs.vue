<template>
    <div class="row">
        <div class="col-3">
            <label for="container_name">Container Name</label>
            <input id="container-input" type="text" class="form-control" name="container_name" v-model="formData.container_name" @keyup.enter="enter">
            <button class="btn btn-dark" @click.prevent="submit">Start</button>
        </div>

        <div class="col">
            <pre>{{ stuff }}</pre>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                stuff: {},
                formData: {
                    container_name: '',
                }
            }
        },

        methods: {
            enter: function(event) {
                $(event.target).blur();
                this.formData.container_name = '';
            },
            listenForLogs: function() {
                axios.get('http://10.200.200.1:4243/events', {
                    transformRequest: [function (data, headers) {
                        delete headers['X-Socket-Id'];
                        delete headers.common['X-CSRF-TOKEN'];
                        return data;
                    }]
                }).then(response => {
                    this.stuff = response.data;
                }).catch(error => {
                    console.log(error);
                });
                // axios('http://10.200.200.1:4243/events', {
                //     method: 'GET',
                //     mode: 'no-cors',
                //     headers: {
                //         'Access-Control-Allow-Origin': '*',
                //         'Content-Type': 'application/json',
                //     },
                //     withCredentials: true,
                //     credentials: 'same-origin',
                // }).then(response => {
                //     this.stuff = response.data;
                // }).catch(error => {
                //     console.log(error);
                // });
            },
            submit: function() {
                this.listenForLogs();
            }
        }
    }
</script>
