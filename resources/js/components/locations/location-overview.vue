<template>
    <div id="locations">
        <h1>Locations</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'location-create'}">
                    <i class="fas fa-plus"></i> Add location
                </router-link>
                <paginated-table v-if="locations != null && locations.data.length > 0"
                                 :actions="actions"
                                 :columns="columns"
                                 module="Locations"
                                 :records="locations"
                                 @edit="$router.push({name: 'location-edit', params: {id: $event.id}})"
                                 @view="$router.push({name: 'location', params: {id: $event.id}})"
                                 @destroy="destroy" />
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
    import PaginatedTable from "../partial/paginated-table";

    export default {
        name: "location-overview",
        components: {PaginatedTable},
        created() {
            this.$store.dispatch('Locations/loadLocations');
        },
        data() {
            return {
                actions: [
                    {name: 'destroy', icon: 'trash', classes: 'uk-text-danger'},
                    {name: 'edit', icon: 'edit'},
                    {name: 'view', icon: 'eye'}
                ],
                columns: [
                    {
                        name: 'name',
                        title: 'Name'
                    },
                    {
                        name: 'type',
                        title: 'Type'
                    },
                    {
                        name: 'location',
                        title: 'Location',
                        format(location) {
                            return location || 'N/A';
                        }
                    }
                ]
            }
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