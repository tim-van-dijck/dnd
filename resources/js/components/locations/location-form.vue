<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="location" id="location-form" class="uk-form-stacked">
                    <div uk-grid>
                        <div class="uk-width-1-2">
                            <div class="uk-margin">
                                <label for="name" class="uk-form-label">Name</label>
                                <input id="name" title="name" type="text" class="uk-input" v-model="location.name">
                            </div>
                            <div class="uk-margin">
                                <label for="type" class="uk-form-label">Type</label>
                                <input id="type" name="type" type="text" class="uk-input" v-model="location.type">
                            </div>
                            <div class="uk-margin">
                                <label for="location" class="uk-form-label">Location</label>
                                <v-select id="location" name="location" class="uk-select"
                                          @search="onSearch"
                                          v-model="location.location_id">
                                </v-select>
                            </div>
                            <div class="uk-margin">
                                <label for="map" class="uk-form-label">Map</label>
                                <img class="preview-image" v-if="map" :src="map" alt="Uploaded map image" width="300" height="300">
                                <input id="map" name="map" type="file" @change="onFileChange">
                            </div>
                            <hr>
                            <div class="uk-margin uk-form-controls">
                                <input id="private" name="private" type="checkbox" class="uk-checkbox" v-model="location.private">
                                <label for="private">Private</label>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <label for="description" class="uk-form-label">Description</label>
                            <editor id="description" name="description" :init="init" v-model="location.description"></editor>
                        </div>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'locations'}">
                            Cancel
                        </router-link>
                    </p>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import Editor from '@tinymce/tinymce-vue';
    import VSelect from 'vue-select';
    import _ from 'lodash';
    import {mapState} from 'vuex';

    export default {
        name: "location-form",
        props: ['id'],
        created() {
            this.location = {};
            if (this.id) {
                this.$store.dispatch('Locations/findLocation', {campaign_id: 1, id: this.id})
                    .then((location) => {
                        this.location = JSON.parse(JSON.stringify(location));
                        if (typeof this.location.map == 'string' && this.location.map.length > 0) {
                            this.map = this.location.map;
                        }
                    });
            }
        },
        data() {
            return {
                location: null,
                locations: [],
                map: null
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (files.length) {
                    this.createImage(files[0]);
                }
            },
            createImage(file) {
                this.location.map = file;
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.map = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            save() {
                let data = {campaign_id: 1, location: this.location};
                if (this.id) {
                    data.id = this.id;
                    this.$store.dispatch('Locations/updateLocation', data);
                } else {
                    this.$store.dispatch('Locations/storeLocation', data);
                }
            },
            onSearch(query, loading) {
                loading(true);
                this.search(query, loading, this);
            },
            search: _.debounce((query, loading, vm) => {
                axios.get(`/campaign/${vm.campaignId || 1}/locations?filter[query]=${escape(query)}&page[number]=1&page[size]=10`)
                    .then((response) => {
                        vm.locations = response.data.data;
                        loading(false);
                    });
            }, 1000)
        },
        computed: {
            ...mapState('Locations', ['errors']),
            init() {
                return {
                    height: 400,
                    plugins: [
                        'link', 'template', 'hr', 'anchor', 'fullscreen',
                        'searchreplace', 'autolink', 'table'
                    ],
                    toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
                    init_instance_callback: function(editor) {
                        var freeTiny = document.querySelector('.tox-notifications-container');
                        freeTiny.style.display = 'none';
                    }
                }
            },
            title() {
                if (this.id) {
                    return 'Edit ' + (this.location ? this.location.name : 'location');
                } else {
                    return 'Add location';
                }
            }
        },
        components: {
            Editor, VSelect
        }
    }
</script>