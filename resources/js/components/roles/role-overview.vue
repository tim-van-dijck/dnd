<template>
    <div id="roles">
        <h1>Roles</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'role-create'}">
                    <i class="fas fa-plus"></i> Add role
                </router-link>
                <table class="uk-table uk-table-divider" v-if="roles != null && roles.data.length > 0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="role in roles.data">
                        <td class="uk-width-small">
                            <ul class="uk-iconnav">
                                <li>
                                    <a href="/" class="uk-text-danger" @click.prevent="destroy(role)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </li>
                                <li>
                                    <router-link :to="{name: 'role-edit', params: {id: role.id}}">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </td>
                        <td>{{ role.name }}</td>
                    </tr>
                    </tbody>
                </table>
                <p v-else class="uk-text-center">
                    <i v-if="roles == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No roles found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import UIKit from 'uikit';

    export default {
        name: "RoleOverview",
        created() {
            this.$store.dispatch('Roles/load');
        },
        methods: {
            destroy(role) {
                UIKit.modal.confirm('Are you sure you want to delete this note?', {
                    labels: {
                        ok: 'Delete',
                        cancel: 'cancel'
                    }
                }).then(() => {
                    this.$store.dispatch('Roles/destroy', role)
                        .then(() => {
                            this.$store.dispatch('Roles/load');
                        });
                });
            }
        },
        computed: {
            ...mapState('Roles', ['roles'])
        }
    }
</script>