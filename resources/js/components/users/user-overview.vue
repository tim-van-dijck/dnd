<template>
    <div id="users">
        <h1>Users</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <button class="uk-button uk-button-primary" uk-toggle="target: #invite-user">
                    <i class="fas fa-plus"></i> Invite user
                </button>
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
                                <li v-if="$store.getters.can('delete', 'user')">
                                    <a href="/" class="uk-text-danger" @click.prevent="destroy(user)">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                </li>
                                <li>
                                    <router-link :to="{name: 'user-edit', params: {id: user.id}}">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </td>
                        <td>{{ user.name != '' ? user.name : user.email }}</td>
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
        <div id="invite-user" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <form>
                    <div class="uk-margin">
                        <label for="email" class="uk-form-label">Email address</label>
                        <input id="email" name="email" type="text"
                               :class="{'uk-input': true, 'uk-form-danger': errors && errors.hasOwnProperty('email')}"
                               v-model="user.email">
                        <span v-if="errors && errors.hasOwnProperty('email')" class="uk-text-danger uk-text-small uk-align-right">
                            {{ errors.email[0] }}
                        </span>
                    </div>
                    <div class="uk-margin" v-if="roles != null">
                        <label for="role" class="uk-form-label">Role</label>
                        <select id="role" name="role" type="text"
                                :class="{'uk-select': true, 'uk-form-danger': errors && errors.hasOwnProperty('role')}"
                                v-model="user.role">
                            <option value="">- Make a choice - </option>
                            <option v-if="roles != null" v-for="role in roles.data" :value="role.id">{{ role.name }}</option>
                        </select>
                        <span v-if="errors && errors.hasOwnProperty('email')" class="uk-text-danger uk-text-small uk-align-right">
                            {{ errors.role[0] }}
                        </span>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Invite</button>
                        <button class="uk-button uk-button-danger uk-modal-close">
                            Cancel
                        </button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import UIkit from 'uikit';

    export default {
        name: "UserOverview",
        created() {
            this.$store.dispatch('Users/load');
            this.$store.dispatch('Roles/load');
        },
        data() {
            return {
                errors: {},
                user: {
                    email: '',
                    role: ''
                }
            }
        },
        methods: {
            save() {
                axios.post('/campaign/users/invite', this.user)
                    .then(() => {
                        this.$store.dispatch('Messages/success', 'Invite sent!')
                        UIkit.modal('#invite-user').hide();
                        this.$store.dispatch('Users/load');
                        this.errors = {};
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
            },
            destroy(user) {
                this.$store.dispatch('Users/')
            }
        },
        computed: {
            ...mapState('Users', ['users']),
            ...mapState('Roles', ['roles'])
        }
    }
</script>