<template>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col">
                    <a
                        class="btn btn-dark btn-sm"
                        data-toggle="collapse"
                        :href="collapseHref"
                        role="button"
                        aria-expanded="false"
                        :aria-controls="collapseId"
                        @click="toggleUploadArea()"
                    >
                        <i class="material-icons mr-2">file_upload</i>CREATE A NEW PROFILE
                    </a>
                </div>
            </div>

            <div class="row mt-2">
                <div :id="collapseId" class="col collapse" :data-toggle="uploadAreaIsOpen">
                    <div class="row">
                        <div class="col">

                            <div class="row">
                                <div class="col">
                                    <form :id="dropzoneId" :action="uploadUrl" class="dropzone">
                                        <div v-show="showDropHere" class="dz-default dz-message">
                                            <span>Click Here To Upload A Config File!</span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row mt-2" v-if="readyForUpload">

                                <div class="col text-danger" v-if="uploadErrors.message">
                                    {{ uploadErrors.errors.name[0] }}
                                    <a :href="reloadUrl">Click here to continue</a>
                                </div>

                                <div class="col">

                                    <button type="button"
                                        class="btn btn-sm btn-danger float-left"
                                        @click="toggleUploadArea()"
                                    >
                                        <span class="fas fa-cloud-upload-alt"></span> Cancel
                                    </button>

                                    <button type="button"
                                        class="btn btn-sm btn-dark float-right"
                                        :disabled="uploadIsDisabled"
                                        @click="processQueue()"
                                    >
                                        <span class="fas fa-cloud-upload-alt"></span> Upload
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row" :id="previewTemplateId"  style="display: none;">
                <div class="col dz-preview dz-file-preview">

                    <div class="dz-details mb-3">

                        <div class="row">
                            <div class="col">
                                <div class="form-group"> <!-- input for name -->
                                    <label for="name">Profile Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control form-control-sm"
                                        id="name-input"
                                        aria-describedby="name-help"
                                        placeholder="name"
                                        v-model="formData.name"
                                    >
                                    <small id="name-help" class="form-text text-muted">This is the name that will show up while provisioning customers....</small>
                                </div> <!-- input for name -->

                                <div class="form-group"> <!-- input for notes -->
                                    <label for="notes">Notes</label>
                                    <textarea
                                        class="form-control form-control-sm"
                                        id="notes-input"
                                        rows="2"
                                        name="notes"
                                        v-model="formData.notes"
                                    ></textarea>
                                </div> <!-- input for notes -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="progress" style="height:3em;">
                                    <div
                                        data-dz-uploadprogress
                                        id="software-progress-bar"
                                        class="progress-bar bg-dark progress-bar-striped progress-bar-animated"
                                        role="progressbar"
                                        aria-valuenow="0"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        style="width: 0%"
                                    >
                                        <span class="sr-only">0% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="dz-filename d-flex flex-column mr-5">
                                    <span data-dz-name></span>
                                    <span class="fas fa-file align-self-center"></span>
                                    <div class="dz-size align-self-center" data-dz-size></div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="modal fade"
                tabindex="-1"
                role="dialog"
                :id="modalId"
                :aria-labelledby="modalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5  :id="modalLabel" class="modal-title">Success</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            The file was successfully uploaded.
                        </div>
                        <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-dark float-right" data-dismiss="modal">Dismiss</button> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import Dropzone from 'dropzone';
    Dropzone.autoDiscover = false;

    export default {
        props: {
            uploadUrl: '',
            dropzoneId: '',
        },

        data: function() {
            return {
                myDropzone: {},
                showDropHere: true,
                readyForUpload: false,
                uploadAreaIsOpen: false,
                uploadIsDisabled: false,
                uploadErrors: {},
                formData: {
                    name: '',
                    notes: ''
                }
            }
        },

        computed: {
            collapseHref: function() {
                return '#collapse-'+this.dropzoneId;
            },
            collapseId: function() {
                return 'collapse-'+this.dropzoneId;
            },
            dropzoneHref: function() {
                return '#'+this.dropzoneId;
            },
            modalHref: function() {
                return '#modal-'+this.dropzoneId;
            },
            modalId: function() {
                return 'modal-'+this.dropzoneId;
            },
            modalLabel: function() {
                return 'modal-'+this.dropzoneId+'-label';
            },
            previewTemplateId: function() {
                return 'preview-template-'+this.dropzoneId;
            },
            reloadUrl: function() {
                return window.location.href;
            }
        },

        methods: {
            configureDropzone: function() {
                this.showDropHere = true;
                let self = this;
                // this.myDropzone = $('#'+this.dropzoneId).dropzone({
                this.myDropzone = new Dropzone(this.dropzoneHref, {
                    url: this.uploadUrl,
                    paramName: "uploaded_file",
                    maxFiles: 1,
                    clickable: true,
                    maxFilesize: 50,
                    acceptedFiles: '.img, .txt, .cnf, .config, .cfg, .conf',
                    addRemoveLinks: false,
                    previewTemplate: document.getElementById(this.previewTemplateId).innerHTML,
                    autoProcessQueue: false,
                    createImageThumbnails: false,
                    headers: {
                        "X-CSRF-TOKEN": window.axios.defaults.headers.common['X-CSRF-TOKEN'],
                    },
                    init: function() {
                        this.on("addedfile", function(file) {
                            self.onDropzoneAddedFile(file);
                        });
                        this.on("error", function(file, errorMessage, xhr) {
                            self.onDropzoneError(file, errorMessage, xhr);
                        });
                        this.on("maxfilesexceeded", function(file) {
                            self.onDropzoneMaxFilesExceeded(file);
                        });
                        this.on("maxfilesreached", function(file) {
                            self.onDropzoneMaxFilesReached(file);
                        });
                        this.on("removedfile", function(file) {
                            self.onDropzoneRemovedFile(file);
                        });
                        this.on("sending", function(file, xhr, formData) {
                            self.onDropzoneSending(file, xhr, formData);
                        });
                        this.on("success", function(file, response) {
                            self.onDropzoneSuccess(file, response);
                        });
                        this.on("uploadprogress", function(file, progress, bytesSent) {
                            self.onDropzoneUploadProgress(file, progress, bytesSent);
                        });
                    },
                });
            },
            destroyDropzone: function() {
                console.log('destroying dropzone');
                this.myDropzone = {};
            },
            flashSuccessModal: function() {
                let self = this;
                $(this.modalHref).modal('show');
                setTimeout(function() {
                    $(self.modalHref).modal('hide');
                    window.location.reload();
                }, 1500);
            },
            initializeDropzone: function() {
                this.configureDropzone();
            },
            onDropzoneAddedFile: function(file) {
                this.showDropHere = false;
                console.log(file);
                console.log('!!!!!!!!!!!!!!!!!!!! file added !!!!!!!!!!!!!!!!!!!!')
            },
            onDropzoneError: function(file, errorMessage, xhr) {
                // console.log(file);
                this.uploadErrors = errorMessage;
                console.log(errorMessage);
                this.uploadIsDisabled = true;
                // console.log(xhr);
                // console.log('!!!!!!!!!!!!!!!!!!!! error !!!!!!!!!!!!!!!!!!!!');
            },
            onDropzoneMaxFilesExceeded: function(file) {
                // console.log(file);
                // console.log('!!!!!!!!!!!!!!!!!!!! max files exceeded !!!!!!!!!!!!!!!!!!!!');
            },
            onDropzoneMaxFilesReached: function(file) {
                this.readyForUpload = true;
                // console.log(file);
                // console.log('!!!!!!!!!!!!!!!!!!!! max files reached !!!!!!!!!!!!!!!!!!!!');
            },
            onDropzoneRemovedFile: function(file) {
                this.readyForUpload = false;
            },
            onDropzoneSending: function(file, xhr, formData) {
                formData.append("name", this.formData.name);
                formData.append("notes", this.formData.notes);
            },
            onDropzoneSuccess: function(file, response) {
                let self = this;
                this.myDropzone.removeAllFiles(true);
                this.readyForUpload = false;
                this.uploadAreaIsOpen = false;
                $(this.collapseHref).collapse('hide');
                this.myDropzone.destroy();
                this.myDropzone = {};
                this.flashSuccessModal();
                EventBus.$emit('ont-software-profile-was-added', response);
            },
            onDropzoneUploadProgress: function(file, progress, bytesSent) {
                document.querySelector("#software-progress-bar").style.width = progress + "%";
            },
            toggleUploadArea: function() {
                if (this.myDropzone.options) {
                    this.uploadAreaIsOpen = false;
                    $(this.collapseHref).collapse('hide');
                    this.myDropzone.removeAllFiles(true);
                    this.myDropzone.destroy();
                    this.myDropzone = {};
                    this.showDropHere = true;
                } else {
                    this.uploadAreaIsOpen = true;
                    this.initializeDropzone();
                }
            },
            processQueue: function() {
                this.myDropzone.processQueue();
            }
        }
    }
</script>
