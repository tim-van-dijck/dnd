<template>
    <div id="spells">
        <h1>Spells</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'spell-create'}">
                    <i class="fas fa-plus"></i> Add spell
                </router-link>
                <paginated-table v-if="spells != null"
                                 :actions="actions"
                                 :columns="columns"
                                 module="Spells"
                                 :records="spells"
                                 @view="$router.push({name: 'spell', params: {id: $event.id}})"
                                 @destroy="destroy" searchable />
                <p v-else class="uk-text-center">
                    <i v-if="spells == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No spells found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import PaginatedTable from "@components/partial/paginated-table";
    import UIKit from 'uikit';
    import {mapState} from "vuex";

    export default {
        name: "spell-overview",
        components: {PaginatedTable},
        created() {
            this.$store.dispatch('Spells/load');
        },
        data() {
            return {
                actions: [
                    {
                        name: 'destroy',
                        icon: 'trash',
                        classes: 'uk-text-danger'
                    },
                    {
                        name: 'edit',
                        icon: 'edit',
                        to: (row) => ({name: 'spell-edit', params: {id: row.id}})
                    }
                ],
                columns: [
                    {
                        name: 'name',
                        title: 'Name'
                    },
                    {
                        name: 'level',
                        title: 'Level',
                        format(level) {
                            switch (level) {
                                case 0:
                                    return 'Cantrip';
                                case 1:
                                    return '1st level';
                                case 2:
                                    return '2nd level';
                                case 3:
                                    return '3rd level';
                                default:
                                    return `${level}th level`;
                            }
                        }
                    },
                    {
                        name: 'school',
                        title: 'School',
                    },
                ]
            }
        },
        methods: {
            destroy(spell) {
                UIKit.modal.prompt(
                    'Are you sure you want to delete this spell? Please write DELETE to confirm', '',
                    {
                        labels: {
                            ok: 'Delete',
                            cancel: 'cancel'
                        }
                    }
                )
                    .then((input) => {
                        if (input === 'DELETE') {
                            this.$store.dispatch('Spells/destroy', spell)
                                .then(() => {
                                    this.$store.dispatch('Spells/load');
                                });
                        } else {
                            this.$store.dispatch('Messages/error', 'Invalid input, delete cancelled.')
                        }
                    });
            }
        },
        computed: {
            ...mapState('Spells', ['spells'])
        }
    }
</script>