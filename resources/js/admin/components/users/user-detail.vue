<template>
    <div id="user" v-if="user">
        <h1>
            <router-link class="uk-link-text" :to="{name: 'users'}"><i class="fas fa-chevron-left"></i></router-link>
            {{ user.name }} <span v-if="user.admin" title="Admin">(<i class="fas fa-user-shield"></i>)</span>
        </h1>
        <div uk-grid>
            <div class="uk-width-1-1@s uk-width-1-3@l">
                <h2>Info</h2>
                <div class="uk-margin uk-flex uk-flex-between">
                    <b>Name</b>
                    <span>{{ user.name }}</span>
                </div>
                <div class="uk-margin uk-flex uk-flex-between">
                    <b>Email</b>
                    <span>{{ user.email }}</span>
                </div>
                <div class="uk-flex uk-flex-between">
                    <b>Status</b>
                    <span class="uk-label" :class="{'uk-label-success': user.active, 'uk-label-danger': !user.active}">
                        {{ user.active ? 'Active' : 'Banned' }}
                    </span>
                </div>
            </div>
            <div class="uk-width-1-1@s uk-width-2-3@l">
                <h2>Campaigns</h2>
                <table v-if="campaigns">
                    <tbody>
                    <tr v-for="campaign in campaigns">
                        <td>{{ campaign.name }}</td>
                        <td>{{ }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div v-else class="uk-section uk-section-default">
        <p class="uk-text-center">
            <i class="fas fa-sync fa-spin fa-2x"></i>
        </p>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "user-detail",
        props: ['id'],
        created() {
            this.$store.dispatch('Users/find', this.id)
                .then((user) => {
                    this.user = user;
                })
            this.$store.dispatch('Campaigns/loadForUser', this.id)
                .then((campaigns) => {
                    this.campaigns = campaigns;
                });
        },
        data() {
            return {
                campaigns: null,
                user: null
            }
        }
    }
</script>