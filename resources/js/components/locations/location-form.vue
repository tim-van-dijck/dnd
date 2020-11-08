<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="location" id="location-form" class="uk-form-stacked">
                    <ul v-if="$store.getters.can('edit', 'role')" uk-tab>
                        <li :class="{'uk-active': tab === 'details'}">
                            <a href="" @click.prevent="tab = 'details'">Details</a>
                        </li>
                        <li :class="{'uk-active': tab === 'permissions'}">
                            <a href="" @click.prevent="tab = 'permissions'">Permissions</a>
                        </li>
                    </ul>
                    <div v-show="tab === 'details'" uk-grid>
                        <div class="uk-width-1-2">
                            <div class="uk-margin">
                                <label for="name" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('name')}">Name*</label>
                                <input id="name" title="name" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('name')}"
                                       v-model="location.name">
                            </div>
                            <div class="uk-margin">
                                <label for="type" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('type')}">Type*</label>
                                <input id="type" name="type" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('type')}"
                                       v-model="location.type">
                            </div>
                            <div class="uk-margin">
                                <label for="location" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('location_id')}">Location</label>
                                <v-select id="location" name="location_id" class="uk-select"
                                          :class="{'uk-form-danger': errors.hasOwnProperty('location_id')}"
                                          @search="onSearch" :options="locations"
                                          :reduce="item => item.value"
                                          v-model="location.location_id">
                                </v-select>
                            </div>
                            <div class="uk-margin">
                                <label for="map" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('map')}">Map</label>
                                <img class="preview-image" v-if="map" :src="map" alt="Uploaded map image" width="300" height="300">
                                <input id="map" name="map" type="file" :class="{'uk-form-danger': errors.hasOwnProperty('map')}" @change="onFileChange">
                            </div>
                            <hr>
                            <div class="uk-margin uk-form-controls">
                                <input id="private" name="private" type="checkbox" class="uk-checkbox"
                                       :class="{'uk-form-danger': errors.hasOwnProperty('private')}" v-model="location.private">
                                <label for="private" :class="{'uk-text-danger': errors.hasOwnProperty('private')}">Private</label>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <label for="description" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('description')}">Description</label>
                            <html-editor id="description" name="description" v-model="location.description" height="600">
                            </html-editor>
                        </div>
                    </div>
                    <permissions-form v-show="tab === 'permissions' && $store.getters.can('edit', 'role')"
                                      entity="location" :id="id"
                                      v-model="location.permissions" />
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
    import HtmlEditor from "../partial/html-editor";
    import PermissionsForm from "../partial/permissions-form";

    export default {
        name: "LocationForm",
        props: ['id'],
        created() {
            if (this.id) {
                this.$store.dispatch('Locations/find', this.id)
                    .then((location) => {
                        this.location = JSON.parse(JSON.stringify(location));
                        if (typeof this.location.map == 'string' && this.location.map.length > 0) {
                            this.map = `/storage/${this.location.map}`;
                        }
                    });
            } else {
                this.location = {};
            }
        },
        data() {
            return {
                location: null,
                locations: [],
                map: null,
                tab: 'details'
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
                let location = new FormData();
                for (let prop of ['name', 'type', 'description', 'location_id', 'map']) {
                    if ((['map', 'location_id'].includes(prop) || this.location[prop] !== '') && this.location[prop] != null) {
                        location.append(prop, this.location[prop]);
                    }
                }
                location.append('private', this.location.private ? 1 : 0);
                if (this.$store.getters.can('edit', 'role')) {
                    for (let userId in this.location.permissions) {
                        let permission = this.location.permissions[userId];
                        location.append(`permissions[${userId}][view]`, permission.view ? 1 : 0);
                        location.append(`permissions[${userId}][create]`, permission.create ? 1 : 0);
                        location.append(`permissions[${userId}][edit]`, permission.edit ? 1 : 0);
                        location.append(`permissions[${userId}][delete]`, permission.delete ? 1 : 0);
                    }
                }

                let promise;
                if (this.id > 0) {
                    promise = this.$store.dispatch('Locations/update', {id: this.id, location});
                } else {
                    promise = this.$store.dispatch('Locations/store', {location})
                }

                promise
                    .then(() => {
                        this.$router.push({name: 'locations'});
                    })
                    .catch((error) => {
                        this.$store.commit('Locations/SET_ERRORS', error.response.data.errors);
                        this.$store.dispatch('Messages/error', error.response.data.message, {root: true});
                    });
            },
            onSearch(query, loading) {
                if (query.length > 2) {
                    loading(true);
                    this.search(query, loading, this);
                }
            },
            search: _.debounce((query, loading, vm) => {
                axios.get(`/campaign/locations?filter[query]=${escape(query)}&page[number]=1&page[size]=10`)
                    .then((response) => {
                        let locations = response.data.data.map((item) => {
                            return {value: item.id, label: item.name};
                        });
                        vm.$set(vm, 'locations', locations);
                        loading(false);
                    });
            }, 1000)
        },
        computed: {
            ...mapState('Locations', ['errors']),
            title() {
                if (this.id) {
                    return 'Edit ' + (this.location ? this.location.name : 'location');
                } else {
                    return 'Add location';
                }
            }
        },
        components: {
            PermissionsForm,
            HtmlEditor,
            Editor, VSelect
        }
    }
</script>