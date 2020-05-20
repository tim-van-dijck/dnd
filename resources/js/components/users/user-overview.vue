<template>
    <div id="users">
        <h1>Users</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'user-invite'}">
                    <i class="fas fa-plus"></i> Invite user
                </router-link>
                <table class="uk-table uk-table-divider" v-if="users != null && users.data.length > 0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users.data">
                        <td class="uk-width-small">
                            <ul class="uk-iconnav">
                                <li>
                                    <a href="/" class="uk-text-danger" @click.prevent="destroy(user)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </li>
                                <li>
                                    <router-link :to="{name: 'user-edit', params: {id: user.id}}">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.role }}</td>
                    </tr>
                    </tbody>
                </table>
                <p v-else class="uk-text-center">
                    <i v-if="users == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No users found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "user-overview",
        created() {
            this.$store.dispatch('Users/load');
        },
        computed: {
            ...mapState('Users', ['users'])
        }
    }
</script>