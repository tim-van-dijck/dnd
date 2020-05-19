<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="role != null" action="">
                    <div class="uk-margin">
                        <label for="name" class="uk-form-label">Name</label>
                        <input id="name" name="name" type="text" class="uk-input" v-model="role.name">
                    </div>
                    <h2>Permissions</h2>
                    <table class="uk-table" v-if="permissions != null">
                            <thead>
                            <tr>
                                <th>Entity</th>
                                <th>View</th>
                                <th>Create</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('view')" :checked="selected.view">
                                </td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('create')" :checked="selected.create">
                                </td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('edit')" :checked="selected.edit">
                                </td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('delete')" :checked="selected.delete">
                                </td>
                            </tr>
                            <tr v-for="permission in permissions">
                                <td style="text-transform: capitalize;">{{ permission.name }}</td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" v-model="role.permissions[permission.id].view">
                                </td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" v-model="role.permissions[permission.id].create">
                                </td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" v-model="role.permissions[permission.id].edit">
                                </td>
                                <td>
                                    <input type="checkbox" class="uk-checkbox" v-model="role.permissions[permission.id].delete">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    <p v-else class="uk-text-center">
                        <i class="fas fa-2x fa-sync fa-spin"></i>
                    </p>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'roles'}">
                            Cancel
                        </router-link>
                    </p>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "RoleForm",
        props: ['id'],
        created() {
            this.$store.dispatch('Roles/loadPermissions')
                .then(() => {
                    if (this.id) {
                        this.$store.dispatch('Roles/find', this.id)
                            .then((role) => {
                                let localRole = {
                                    name: role.name,
                                    permissions: {}
                                };

                                for (let permission of role.permissions) {
                                    localRole.permissions[permission.id] = {
                                        view: permission.view,
                                        create: permission.create,
                                        edit: permission.edit,
                                        delete: permission.delete,
                                    };
                                }

                                this.$set(this, 'role', JSON.parse(JSON.stringify(localRole)));
                            });
                    } else {
                        let role = {
                            permissions: {},
                        };
                        for (let permission of this.permissions) {
                            role.permissions[permission.id] = {
                                view: false,
                                create: false,
                                edit: false,
                                delete: false
                            };
                        }
                        this.role = role;
                    }
                });
        },
        data() {
            return {
                role: null,
                selected: {
                    view: false,
                    create: false,
                    edit: false,
                    delete: false
                }
            }
        },
        methods: {
            save() {
                let promise;
                let role = {
                    name: this.role.name,
                    permissions: []
                };

                for (let index in this.role.permissions) {
                    role.permissions.push({
                        id: index,
                        view: this.role.permissions[index].view,
                        create: this.role.permissions[index].create,
                        edit: this.role.permissions[index].edit,
                        delete: this.role.permissions[index].delete,
                    });
                }

                if (this.id) {
                    promise = this.$store.dispatch('Roles/update', {id: this.id, role});
                } else {
                    promise = this.$store.dispatch('Roles/store', role);
                }
                promise
                    .then(() => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$router.push({name: 'roles'});
                        }
                    });
            },
            selectAll(type) {
                this.selected[type] = !this.selected[type];
                for (let index in this.role.permissions) {
                    let permission = this.role.permissions[index];
                    permission[type] = this.selected[type];
                }
            }
        },
        computed: {
            ...mapState('Roles', ['errors', 'permissions']),
            title() {
                if (this.id) {
                    return `Edit ${this.role && this.role.name.length > 0 ? this.role.name: 'role'}`;
                } else {
                    return 'Create new role';
                }
            }
        },
        watch: {
            role: {
                deep: true,
                handler(role) {
                    if (role != null) {
                        for (let type in this.selected) {
                            let allSelected = true;
                            for (let permission in role.permissions) {
                                if (!role.permissions[permission][type]) {
                                    allSelected = false;
                                    break;
                                }
                            }
                            this.$set(this.selected, type, allSelected);
                        }
                    }
                }
            }
        }
    }
</script>