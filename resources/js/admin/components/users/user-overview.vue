<template>
    <div id="users">
        <h1>Users</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'user-create'}">
                    <i class="fas fa-plus"></i> Add user
                </router-link>
                <paginated-table :actions="actions"
                                 :columns="columns"
                                 module="Users"
                                 :records="users"
                                 @view="$router.push({name: 'user', params: {id: $event.id}})"
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
        name: "user-overview",
        components: {PaginatedTable},
        created() {
            this.$store.dispatch('Users/load');
        },
        data() {
            return {
                actions: [
                    {
                        name: 'reset',
                        icon: 'unlock'
                    },
                    {
                        name: 'view',
                        icon: 'eye',
                        to: (row) => {
                            return `admin#/users/${row.id}`;
                        }
                    }
                ],
                columns: [
                    {
                        name: 'name',
                        title: 'Name'
                    },
                    {
                        name: 'email',
                        title: 'Email',
                    }
                ]
            }
        },
        methods: {
            destroy(user) {
                UIKit.modal.prompt(
                    'Are you sure you want to delete this user? Please write DELETE to confirm', '',
                    {
                        labels: {
                            ok: 'Delete',
                            cancel: 'cancel'
                        }
                    }
                )
                    .then((input) => {
                        if (input === 'DELETE') {
                            this.$store.dispatch('Users/destroy', user)
                                .then(() => {
                                    this.$store.dispatch('Users/load');
                                });
                        } else {
                            this.$store.dispatch('Messages/error', 'Invalid input, delete cancelled.')
                        }
                    });
            }
        },
        computed: {
            ...mapState('Users', ['users'])
        }
    }
</script>