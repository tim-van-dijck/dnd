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
    import {mapState} from "vuex";

    export default {
        name: "permissions-form",
        props: ['value'],
        mounted() {
            this.$store.dispatch('Users/load')
                .then(() => {
                    let permissions = {};
                    for (let user of this.users.data) {
                        permissions[user.id] = this.value[user.id] || {
                            view: false,
                            edit: false,
                            create: false,
                            delete: false,
                        };
                    }
                    this.$set(this, 'permissions', permissions);
                });
            this.override = Object.keys(this.value || {}).length > 0;
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
                for (let index in this.value) {
                    let permission = this.value[index];
                    permission[type] = this.selected[type];
                }
            }
        },
        computed: {
            ...mapState('Users',['users'])
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