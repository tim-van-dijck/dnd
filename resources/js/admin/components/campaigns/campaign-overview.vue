<template>
    <div id="campaigns">
        <h1>Campaigns</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <paginated-table v-if="campaigns != null && campaigns.data.length > 0"
                                 :actions="actions"
                                 :columns="columns"
                                 module="Campaigns"
                                 :records="campaigns"
                                 @destroy="destroy" />
                <p v-else class="uk-text-center">
                    <i v-if="campaigns == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No campaigns found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import PaginatedTable from "@components/partial/paginated-table";
    import {mapState} from "vuex";
    import UIKit from "uikit";

    export default {
        name: "campaign-overview",
        components: {PaginatedTable},
        created() {
            this.$store.dispatch('Campaigns/load');
        },
        computed: {
            ...mapState('Campaigns', ['campaigns'])
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
                        to: (row) => ({name: 'campaign-edit', params: {id: row.id}})
                    },
                    {
                        name: 'view',
                        icon: 'eye',
                        href: (row) => `/campaigns/${row.id}`
                    }
                ],
                columns: [
                    {
                        name: 'name',
                        title: 'Name'
                    },
                    {
                        name: 'admins',
                        title: 'Admins',
                        formatRaw(admins) {
                            let template = [];
                            for (let admin of admins) {
                                template.push(`<a href="/admin#/users/${admin.id}">${admin.name}</a>`);
                            }
                            return template.join('<br>');
                        }
                    }
                ]
            }
        },
        methods: {
            destroy(campaign) {
                UIKit.modal.prompt(
                    'Are you sure you want to delete this campaign? Please write DELETE to confirm', '',
                    {
                        labels: {
                            ok: 'Delete',
                            cancel: 'cancel'
                        }
                    }
                )
                    .then((input) => {
                        if (input === 'DELETE') {
                            this.$store.dispatch('Campaigns/destroy', campaign)
                                .then(() => {
                                    this.$store.dispatch('Campaigns/load');
                                });
                        } else {
                            this.$store.dispatch('Messages/error', 'Invalid input, delete cancelled.')
                        }
                    });
            }
        }
    }
</script>