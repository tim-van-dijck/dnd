<template>
    <div>
        <h1>Invite a user</h1>
    </div>
</template>

<script>
    export default {
        name: "UserForm",
        created() {
            this.$store.dispatch('Roles/load');
        },
        data() {
            return {
                user: {
                    email: '',
                    role: ''
                }
            }
        },
        computed: {
        }
    }
</script>