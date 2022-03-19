<template>
    <div class="player-characters">
        <router-link class="uk-button uk-button-primary" :to="{name: 'pc-create', params: {type: 'player'}}">
            <i class="fas fa-plus"></i> Add character
        </router-link>
        <paginated-table v-if="characters != null && characters.data.length > 0"
                         :actions="actions"
                         :columns="columns"
                         module="Characters"
                         :records="characters"
                         @edit="$router.push({name: 'pc-edit', params: {id: $event.id}})"
                         @view="$router.push({name: 'pc-detail', params: {id: $event.id}})"
                         @inventory="$router.push({name: 'inventory', params: {id: $event.info.inventory_id}})"
                         @destroy="destroy" />
        <p v-else class="uk-text-center">
            <i v-if="characters == null" class="fas fa-sync fa-spin fa-2x"></i>
            <span v-else>
                No characters found
            </span>
        </p>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import PaginatedTable from "@components/partial/paginated-table";
    import UIKit from "uikit";

    export default {
        name: "PlayerCharacterOverview",
        components: {PaginatedTable},
        created() {
            this.$store.dispatch('Characters/load', 'player');
            this.$store.dispatch('Characters/loadRaces');
        },
        data() {
            return {
                actions: [
                    {name: 'destroy', title: 'Delete character', icon: 'trash', classes: 'uk-text-danger'},
                    {name: 'edit', title: 'Edit character', icon: 'edit'},
                    {name: 'view', title: 'Go to details', icon: 'eye'},
                    {name: 'sheet', title: 'Download character sheet', href: (row) => `/campaign/characters/${row.id}/sheet`, icon: 'file', newTab: true},
                    {name: 'inventory', title: 'Go to inventory', icon: 'shopping-bag'}
                ],
                columns: [
                    {title: 'Name', name: 'info.name'},
                    {
                        title: 'Race',
                        name: 'race',
                        format(race) {
                            let value = race.name;
                            if (race.subrace) {
                                value += ` (${race.subrace.name})`;
                            }
                            return value
                        }
                    },
                    {
                        title: 'Class',
                        name: 'classes',
                        format(classes) {
                            if (classes) {
                                if (classes.length == 1) {
                                    return classes[0].name
                                } else {
                                    let classNames = [];
                                    for (let charClass of classes) {
                                        classNames.push(charClass.name);
                                    }
                                    if (classNames.length > 0) {
                                        return `Multiclass: ${classNames.join(' - ')}`;
                                    }
                                }
                            }
                            return 'N/A'
                        }
                    },
                    {
                        title: 'Level',
                        name: 'classes',
                        format(classes) {
                            let level = 0;
                            for (let charClass of classes) {
                                level += parseInt(charClass.level);
                            }
                            return level;
                        }
                    },
                    {
                        name: 'owner',
                        title: 'Owner',
                        format(owner) {
                            return owner || 'N/A';
                        }
                    }
                ]
            }
        },
        methods: {
            destroy(character) {
                UIKit.modal.confirm(`Are you sure you want to delete ${character.info.name}?`, {
                    labels: {
                        ok: 'Delete',
                        cancel: 'cancel'
                    }
                })
                    .then(() => {
                        this.$store.dispatch('Characters/destroy', character)
                            .then(() => {
                                this.$store.dispatch('Characters/load');
                            })
                    });
            }
        },
        computed: {
            ...mapState('Characters', ['characters', 'races'])
        }
    }
</script>