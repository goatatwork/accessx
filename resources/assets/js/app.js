
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// import * as uiv from 'uiv';

window.Vue = require('vue');

Vue.component('dhcpbot-option43-toggle', require('./components/Dhcpbot/Option43Toggle.vue'));

Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

Vue.component('echo-messages', require('./components/GoldAccess/Echo/EchoMessages.vue'));
Vue.component('presence-information', require('./components/GoldAccess/Echo/EchoPresence.vue'));
Vue.component('settings-manager', require('./components/GoldAccess/GaSettings/GaSettings.vue'));

Vue.component('media-file', require('./components/GoldAccess/Core/MediaFile.vue'));
Vue.component('media-file-list', require('./components/GoldAccess/Core/MediaFileList.vue'));
Vue.component('file-uploader', require('./components/GoldAccess/Core/FileUploader.vue'));
Vue.component('delete-modal', require('./components/GoldAccess/Core/DeleteModal.vue'));
Vue.component('marked-content', require('./components/GoldAccess/Core/MarkedContent.vue'));
Vue.component('toggle-switch', require('./components/GoldAccess/Core/ToggleSwitch.vue'));

Vue.component('instant-edit-select', require('./components/GoldAccess/Forms/InstantSelect.vue'));

Vue.component('activity-logs-card', require('./components/GoldAccess/Cards/ActivityLogsCard.vue'));
Vue.component('activity-logs-table', require('./components/GoldAccess/ActivityLogs/ActivityLogsTable.vue'));

Vue.component('service-location-page', require('./components/GoldAccess/Customers/ServiceLocationPage.vue'));
Vue.component('service-location-card', require('./components/GoldAccess/Customers/ServiceLocationCard.vue'));

// Service status cards
Vue.component('dnsmasq-server-status-card', require('./components/GoldAccess/Dhcp/DnsmasqServerStatusCard.vue'));

// ONTs
Vue.component('ont-card', require('./components/GoldAccess/Onts/OntCard.vue'));
Vue.component('ont-software', require('./components/GoldAccess/Onts/OntSoftware.vue'));
Vue.component('ont-file-uploader', require('./components/GoldAccess/Onts/OntFileUploader.vue'));

Vue.component('onts-index', require('./components/GoldAccess/Onts/OntsIndex.vue'));
Vue.component('show-ont-page', require('./components/GoldAccess/Onts/ShowOntPage.vue'));

// When this one is complete, and show-dhcp-network-page can be removed.
Vue.component('subnet-calculator', require('./components/GoldAccess/Dhcp/SubnetCalculator.vue'));
Vue.component('add-subnet-accordion-card', require('./components/GoldAccess/Dhcp/AddSubnetAccordionCard.vue'));

Vue.component('edit-provisioning-record-page', require('./components/GoldAccess/Provisioning/EditProvisioningRecordPage.vue'));
Vue.component('provisioning-records-table', require('./components/GoldAccess/Provisioning/ProvisioningRecordsTable.vue'));
Vue.component('provision-by-service-location', require('./components/GoldAccess/Provisioning/ProvisionByServiceLocation.vue'));

Vue.component('customers-table', require('./components/GoldAccess/Customers/CustomersTable.vue'));

Vue.component('user-management', require('./components/GoldAccess/Users/UserManagement.vue'));
Vue.component('create-user-modal', require('./components/GoldAccess/Users/CreateUserModal.vue'));

Vue.component('ont-software-file-uploader', require('./components/GoldAccess/Core/OntSoftwareFileUploader.vue'));
Vue.component('ont-config-file-uploader', require('./components/GoldAccess/Core/OntConfigFileUploader.vue'));

// Dhcp Leases Viewer
Vue.component('dhcp-leases', require('./components/GoldAccess/Dhcp/Leases.vue'));

// Module to add ports to an aggregator slot
Vue.component('slot-port-creator', require('./components/GoldAccess/Aggregators/SlotPortCreator.vue'));
window.EventBus = new Vue({});

const app = new Vue({
    el: '#app'
});

window.moment = require('moment');
