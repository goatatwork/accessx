
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// import * as uiv from 'uiv';

window.Vue = require('vue');

Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

Vue.component('media-file', require('./components/GoldAccess/Core/MediaFile.vue'));
Vue.component('media-file-list', require('./components/GoldAccess/Core/MediaFileList.vue'));
Vue.component('file-uploader', require('./components/GoldAccess/Core/FileUploader.vue'));
Vue.component('delete-modal', require('./components/GoldAccess/Core/DeleteModal.vue'));
Vue.component('marked-content', require('./components/GoldAccess/Core/MarkedContent.vue'));

Vue.component('service-location-page', require('./components/GoldAccess/Customers/ServiceLocationPage.vue'));

// ONTs
Vue.component('ont-card', require('./components/GoldAccess/Onts/OntCard.vue'));
Vue.component('ont-software', require('./components/GoldAccess/Onts/OntSoftware.vue'));
Vue.component('ont-file-uploader', require('./components/GoldAccess/Onts/OntFileUploader.vue'));

Vue.component('onts-index', require('./components/GoldAccess/Onts/OntsIndex.vue'));
Vue.component('show-ont-page', require('./components/GoldAccess/Onts/ShowOntPage.vue'));

// When this one is complete, and show-dhcp-network-page can be removed.
Vue.component('add-subnet-accordion-card', require('./components/GoldAccess/Dhcp/AddSubnetAccordionCard.vue'));

Vue.component('provision-by-service-location', require('./components/GoldAccess/Provisioning/ProvisionByServiceLocation.vue'));

window.EventBus = new Vue({});

const app = new Vue({
    el: '#app'
});

// Vue.use(uiv, {prefix:'uiv'});
