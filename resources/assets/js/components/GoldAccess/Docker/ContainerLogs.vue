<template>
    <div class="row">
        <div class="col-3">
            <label for="container_name">Container Name</label>
            <input id="container-input" type="text" class="form-control" name="container_name" v-model="formData.container_name" @keyup.enter="enter">
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
                this.listenForLogs();
            },
            listenForLogs: function() {
                axios({
                    method: 'get',
                    url: 'http://10.200.200.1:4243/events',
                    headers: {
                        'Access-Control-Allow-Origin': '*',
                        'Access-Control-Allow-Headers': 'Origin, Content-Type, X-Auth-Token, Authorization',
                        'Access-Control-Allow-Methods': 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
                        'Access-Control-Allow-Credentials': true,
                        'Content-Type': 'text/html; charset=utf-8'
                    }
                }).then(response => {
                    this.stuff = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
        }
    }
</script>
