<template>
    <header id="top-head" class="uk-position-fixed">
        <div class="uk-container uk-container-expand uk-background-primary">
            <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
                <div class="uk-navbar-left">
                    <div class="uk-navbar-item uk-visible@s">
                        <form id="header-search" class="uk-search uk-search-default">
                            <span data-uk-search-icon></span>
                            <input class="uk-search-input search-field" type="search" placeholder="Search"
                                   v-model="query"
                                   @focus="searchVisible = searchResults.length > 0"
                                   @keydown.enter.prevent="search"
                                   @keyup.esc="searchVisible = false;">
                            <div v-if="searchVisible" class="results uk-background-muted">
                                <ul class="uk-list uk-margin-remove">
                                    <li v-for="result in searchResults">
                                        <div class="search-result uk-link-text uk-padding-small uk-text-secondary uk-display-block"
                                             @click="navigate(result.to)">
                                            <h5 v-html="result.label"></h5>
                                            <p>{{ result.type }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="uk-navbar-right">
                    <ul class="uk-navbar-nav">
                        <li><a href="/campaigns" class="uk-navbar-item">My Campaigns</a></li>
                        <li><a href="/profile" class="uk-navbar-item">My Profile</a></li>
                        <li>
                            <a href="/logout" title="Sign out" class="uk-navbar-item"
                               @click.prevent="$store.dispatch('logout')">
                                <i class="fas fa-sign-out-alt fa-fw"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
import _ from "lodash";

export default {
    name: "header-navbar",
    data() {
        return {
            searchResults: [],
            searchVisible: false,
            query: ''
        }
    },
    methods: {
        search: _.debounce(function () {
            if (this.query.length === 0) {
                this.searchResults = 0;
            }
            if (this.query.length >= 3) {
                axios.get(`/campaign/search?query=${this.query}`)
                    .then((response) => {
                        this.searchResults = response.data.map((item) => {
                            let to;
                            switch (item.type) {
                                case 'location':
                                    to = {name: 'location', params: {id: item.id}}
                                    break;
                                case 'note':
                                    to = {name: 'note', params: {id: item.id}}
                                    break;
                                case 'npc':
                                    to = {name: 'npc-detail', params: {id: item.id}}
                                    break;
                                case 'player':
                                    to = {name: 'pc-detail', params: {id: item.id}}
                                    break;
                                case 'quest':
                                    to = {name: 'quest', params: {id: item.id}}
                                    break;
                            }
                            return {
                                id: item.id,
                                label: item.label.replace(this.query, `<b>${this.query}</b>`),
                                to,
                                type: item.type === 'player' ? 'Player Character' : item.type
                            }
                        });
                        this.searchVisible = this.searchResults.length > 0;
                    });
            }
        }, 500),
        navigate(to) {
            this.query = '';
            this.searchVisible = false;
            if (this.$route.name === to.name) {
                for (let index in this.$route.params) {
                    if (this.$route.params[index] != to.params[index]) {
                        return this.$router.push(to);
                    }
                }
            } else {
                return this.$router.push(to);
            }
        }
    },
    watch: {
        query() {
            this.search();
        }
    }
}
</script>