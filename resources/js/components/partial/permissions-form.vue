<template>
    <div class="permissions">
        <div class="uk-margin">
            <p>Do you want to override the standard permissions per user?</p>
            <label><input class="uk-radio" type="radio" :value="true" v-model="override"> Yes</label>
            <label><input class="uk-radio" type="radio" :value="false" v-model="override"> No</label>
        </div>
        <table class="uk-table" v-if="override && permissions != null && users != null">
            <thead>
            <tr>
                <th>User</th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('view')"
                               :checked="selected.view"> View
                    </label>
                </th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('create')"
                               :checked="selected.create"> Create
                    </label>
                </th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('edit')"
                               :checked="selected.edit"> Edit
                    </label>
                </th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('delete')"
                               :checked="selected.delete"> Delete
                    </label>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users.data">
                <td style="text-transform: capitalize;">{{ user.name }}</td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="permissions[user.id].view">
                </td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="permissions[user.id].create">
                </td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="permissions[user.id].edit">
                </td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="permissions[user.id].delete">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import {mapGetters, mapState} from "vuex";

    export default {
        name: "permissions-form",
        props: ['value', 'entity', 'id'],
        mounted() {
            let promises = [
                this.$store.dispatch('Permissions/fetch', {entity: this.entity, id: this.id}),
                this.$store.dispatch('Users/load')
            ];
            Promise.all(promises)
                .then(() => {
                    this.override = Object.keys(this.permission(this.entity, this.id)).length > 0;
                    this.$set(this, 'permissions', this.permission(this.entity, this.id));

                    for (let user of this.users.data) {
                        if (!this.permissions.hasOwnProperty(user.id)) {
                            this.$set(this.permissions, user.id, {
                                view: false,
                                edit: false,
                                create: false,
                                delete: false,
                            });
                        }
                    }
                });
        },
        data() {
            return {
                override: false,
                permissions: null,
                selected: {
                    view: false,
                    create: false,
                    edit: false,
                    delete: false
                }
            }
        },
        methods: {
            selectAll(type) {
                this.selected[type] = !this.selected[type];
                for (let index in this.permissions) {
                    this.$set(this.permissions[index], type, this.selected[type]);
                }
            }
        },
        computed: {
            ...mapState('Users', ['users']),
            ...mapGetters('Permissions', ['permission']),
        },
        watch: {
            permissions: {
                deep: true,
                handler() {
                    if (this.override) {
                        this.$emit('input', this.permissions);
                    }
                }
            },
            override(value) {
                this.$emit('input', value ? this.permissions : {});
            }
        }
    }
</script>