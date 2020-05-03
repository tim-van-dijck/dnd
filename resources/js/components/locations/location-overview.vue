<template>
    <div id="locations">
        <h1>Locations</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'location-create'}">
                    <i class="fas fa-plus"></i> Add location
                </router-link>
                <table class="uk-table uk-table-divider" v-if="locations != null && locations.data.length > 0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th class="uk-table-shrink">Map</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="location in locations.data">
                        <td class="uk-width-small">
                            <ul class="uk-iconnav">
                                <li>
                                    <a href="/" class="uk-text-danger" @click.prevent="destroy(location)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </li>
                                <li>
                                    <router-link :to="{name: 'location-edit', params: {id: location.id}}">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{name: 'location', params: {id: location.id}}">
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </td>
                        <td>{{ location.name }}</td>
                        <td>{{ location.type }}</td>
                        <td>{{ location.location }}</td>
                        <td><a href=""><i class="fas fa-map"></i></a></td>
                    </tr>
                    </tbody>
                </table>
                <p v-else class="uk-text-center">
                    <i v-if="locations == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No locations found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import UIKit from 'uikit';

    export default {
        name: "location-overview",
        created() {
            this.$store.dispatch('Locations/loadLocations');
        },
        methods: {
            destroy(location) {
                UIKit.modal.confirm('Are you sure you want to delete this location?', {
                    labels: {
                        ok: 'Delete',
                        cancel: 'cancel'
                    }
                })
                    .then(() => {
                        this.$store.dispatch('Locations/destroy', location)
                    });
            }
        },
        computed: {
            ...mapState('Locations', ['locations'])
        }
    }
</script>