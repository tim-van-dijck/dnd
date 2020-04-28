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
                            <th class="uk-table-shrink"></th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Map</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="location in locations.data">
                        <td>
                            <router-link :to="{name: 'location-edit', params: {id: location.id}}">
                                <i class="fas fa-edit"></i>
                            </router-link>
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

    export default {
        name: "location-overview",
        created() {
            this.$store.dispatch('Locations/loadLocations', {campaign_id: 1});
        },
        computed: {
            ...mapState('Locations', ['locations'])
        }
    }
</script>