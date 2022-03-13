<template>
    <div id="races">
        <h1>Races</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'race-create'}">
                    <i class="fas fa-plus"></i> Add race
                </router-link>
                <paginated-table :actions="actions"
                                 :columns="columns"
                                 module="Races"
                                 :records="races"
                                 @destroy="destroy"
                                 searchable />
            </div>
        </div>
    </div>
</template>

<script>
import PaginatedTable from "@/components/partial/paginated-table";
import UIKit from "uikit";
import {mapState} from "vuex";

export default {
    name: "race-overview",
    components: {PaginatedTable},
    created() {
        this.$store.dispatch('Races/load');
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
                    to: (race) => ({nane: 'race-edit', params: {id: race.id}})
                },
                {
                    name: 'view',
                    icon: 'eye',
                    to: (race) => ({name: 'race', params: {id: race.id}})
                }
            ],
            columns: [
                {
                    name: 'name',
                    title: 'Name'
                },
                {
                    name: 'size',
                    title: 'Size',
                },
                {
                    name: 'speed',
                    title: 'Speed',
                    format: (speed) => `${speed}ft.`
                }
            ]
        }
    },
    methods: {
        destroy(race) {
            UIKit.modal.prompt(
                'Are you sure you want to delete this race? Please write DELETE to confirm', '',
                {
                    labels: {
                        ok: 'Delete',
                        cancel: 'cancel'
                    }
                }
            )
                .then((input) => {
                    if (input === 'DELETE') {
                        this.$store.dispatch('Races/destroy', race)
                            .then(() => {
                                this.$store.dispatch('Races/load');
                            });
                    } else {
                        this.$store.dispatch('Messages/error', 'Invalid input, delete cancelled.')
                    }
                });
        }
    },
    computed: {
        ...mapState('Races', ['races'])
    }
}
</script>