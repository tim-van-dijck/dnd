<template>
    <div id="app">
        <header-navbar>
            <template slot="left">
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
                                    <div
                                        class="search-result uk-link-text uk-padding-small uk-text-secondary uk-display-block"
                                        @click="navigate(result.to)">
                                        <h5 v-html="result.label"></h5>
                                        <p>{{ result.type }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </template>
        </header-navbar>
        <navigation>
            <aside id="left-col" class="uk-light uk-visible@m">
                <div class="left-logo uk-flex uk-flex-middle">
                    DUNGEONS & DIARIES
                </div>

                <div class="left-nav-wrap">
                    <ul class="uk-nav uk-nav-default" data-uk-nav>
                        <li class="uk-nav-header">CAMPAIGN</li>
                        <li v-for="i in 5">
                            <span class="navigation-placeholder">&nbsp;</span>
                        </li>
                    </ul>
                </div>
                <div class="bar-bottom">
                    <ul class="uk-subnav uk-flex uk-flex-center uk-child-width-1-5" data-uk-grid>
                        <li v-for="i in 4">
                            <span class="navigation-placeholder">&nbsp;</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </navigation>
        <messages/>
        <div id="content" uk-height-viewport="expand: true">
            <div class="uk-container uk-container-expand">
                <router-view></router-view>
                <footer class="uk-section uk-section-small uk-text-center">
                    <hr>
                    <p class="uk-text-small uk-text-center">
                        Made with love by <a href="https://bit.ly/2Rz0xNG" target="_blank">Tim van Dijck</a>
                    </p>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
import HeaderNavbar from '@components/layout/header-navbar'
import Messages from '@components/layout/messages'
import { debounce } from 'lodash'
import Navigation from './layout/navigation'

export default {
    name: 'Campaign',
    data() {
        return {
            searchResults: [],
            searchVisible: false,
            query: ''
        }
    },
    methods: {
        search: debounce(function () {
            if (this.query.length === 0) {
                this.searchResults = 0
            }
            if (this.query.length >= 3) {
                axios.get(`/campaign/search?query=${this.query}`)
                    .then((response) => {
                        this.searchResults = response.data.map((item) => {
                            let to
                            switch (item.type) {
                                case 'location':
                                    to = { name: 'location', params: { id: item.id } }
                                    break
                                case 'note':
                                    to = { name: 'note', params: { id: item.id } }
                                    break
                                case 'npc':
                                    to = { name: 'npc-detail', params: { id: item.id } }
                                    break
                                case 'player':
                                    to = { name: 'pc-detail', params: { id: item.id } }
                                    break
                                case 'quest':
                                    to = { name: 'quest', params: { id: item.id } }
                                    break
                            }
                            return {
                                id: item.id,
                                label: item.label.replace(this.query, `<b>${this.query}</b>`),
                                to,
                                type: item.type === 'player' ? 'Player Character' : item.type
                            }
                        })
                        this.searchVisible = this.searchResults.length > 0
                    })
            }
        }, 500),
        navigate(to) {
            this.query = ''
            this.searchVisible = false
            if (this.$route.name === to.name) {
                for (const index in this.$route.params) {
                    if (this.$route.params[index] != to.params[index]) {
                        return this.$router.push(to)
                    }
                }
            } else {
                return this.$router.push(to)
            }
        }
    },
    watch: {
        query() {
            this.search()
        }
    },
    components: { Navigation, HeaderNavbar, Messages }
}
</script>