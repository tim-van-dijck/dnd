<template>
    <div class="table-paginated">
        <table class="uk-table uk-table-divider">
            <thead>
            <tr>
                <th></th>
                <th v-for="column in columns">{{ column.title }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="records === null">
                <td class="uk-text-center" :colspan="columns.length+1"><i class="fas fa-sync fa-spin"></i></td>
            </tr>
            <tr v-else-if="(records.data || []).length === 0">
                <td class="uk-text-center" :colspan="columns.length+1">We couldn't find anything</td>
            </tr>

            <tr v-else v-for="row in records.data">
                <td class="uk-width-small">
                    <ul class="uk-iconnav">
                        <li v-for="action in actions">
                            <router-link v-if="action.to" :to="getTo(action.to, row)" :title="action.title">
                                <i :class="`fas fa-${action.icon}`"></i>
                            </router-link>
                            <a v-else-if="action.href" :href="getHref(action.to, row)" :target="action.newTab ? '_blank' : ''" :title="action.title">
                                <i :class="`fas fa-${action.icon}`"></i>
                            </a>
                            <a href="/" :class="action.classes || ''"  @click.prevent="$emit(action.name, row)" :title="action.title" v-else>
                                <i :class="`fas fa-${action.icon}`"></i>
                            </a>
                        </li>
                    </ul>
                </td>
                <template v-for="column in columns">
                    <td v-if="column.formatRaw && typeof column.formatRaw === 'function'"
                        v-html="column.formatRaw(getValue(row, column.name), column.name)"></td>
                    <td v-else>
                        {{ column.format ? column.format(getValue(row, column.name), row) : getValue(row, column.name) }}
                    </td>
                </template>
            </tr>
            </tbody>
        </table>
        <ul v-if="pages.length > 1" class="uk-pagination uk-flex-right uk-margin-medium-top" uk-margin>
            <li>
                <a v-if="records.meta.current_page > 1" href="#" @click.prevent="previous">
                    <span uk-pagination-previous></span>
                </a>
            </li>
            <li v-for="page in pages" :class="{'uk-active': page.active}">
                <span v-if="page.number == '...'">...</span>
                <a v-else-if="!page.active" href="#" @click.prevent="go(page.number)">
                    {{ page.number }}
                </a>
                <span v-else>{{ page.number }}</span>
            </li>
            <li>
                <a v-if="records.meta.current_page != records.meta.last_page" href="#" @click.prevent="next">
                    <span uk-pagination-next></span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    import {debounce} from "lodash";

    export default {
        name: "paginated-table",
        props: {
            actions: {},
            columns: {},
            module: {
                type: String,
                required: true
            },
            records: {
                type: Object,
                default: null
            },
            searchable: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                query: ''
            }
        },
        methods: {
            previous() {
                if (this.records.meta.current_page > 1) {
                    this.$store.dispatch(`${this.module}/previous`);
                }
            },
            go(number) {
                if (number != this.records.meta.current_page) {
                    this.$store.dispatch(`${this.module}/page`, number);
                }
            },
            next() {
                if (this.records.meta.current_page < this.records.meta.last_page) {
                    this.$store.dispatch(`${this.module}/next`);
                }
            },
            getValue(row, name) {
                let value = row;
                for (let key of name.split('.')) {
                    value = value[key];
                }
                return value;
            },
            getHref(to, row) {
                if (typeof to === 'string') {
                    return to;
                } else if (typeof to === 'function') {
                    return to(row) || '/';
                }
            },
            getTo(to, row) {
                if (typeof to === 'object') {
                    return to;
                } else if (typeof to === 'function') {
                    return to(row);
                }
            },
            search: debounce(function () {
                if (this.query.length === 0) {
                    this.searchResults = 0;
                }
                if (this.query.length >= 3) {
                    this.$store.dispatch(`${this.module}/load`, {query: this.query});
                }
            }, 500),
        },
        computed: {
            pages() {
                if (this.records === null) {
                    return [];
                }
                let last = this.records.meta.last_page;
                let current = this.records.meta.current_page;
                let pages = [
                    {number: 1, active: current == 1}
                ];

                if (current - 1 > 2) {
                    pages.push({number: '...', active: false})
                }
                for (let i = -2; i <= 2; i++) {
                    if (current + i > 1 && current + i <= last) {
                        pages.push({number: current + i, active: i === 0});
                    }
                }

                if (last - current > 2) {
                    pages.push({number: '...', active: false});
                    pages.push({number: last, active: false});
                }

                return pages;
            }
        },
        watch: {
            query() {
                this.search();
            }
        }
    }
</script>