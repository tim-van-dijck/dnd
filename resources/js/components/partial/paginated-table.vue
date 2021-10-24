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
            <tr v-for="row in records.data">
                <td class="uk-width-small">
                    <ul class="uk-iconnav">
                        <li v-for="action in actions">
                            <a v-if="action.to" :href="getTo(action.to, row)" :target="action.newTab ? '_blank' : 'self'" :title="action.title">
                                <i :class="`fas fa-${action.icon}`"></i>
                            </a>
                            <a href="/" :class="action.classes || ''"  @click.prevent="$emit(action.name, row)" :title="action.title" v-else>
                                <i :class="`fas fa-${action.icon}`"></i>
                            </a>
                        </li>
                    </ul>
                </td>
                <td v-for="column in columns">{{ column.format ? column.format(getValue(row, column.name), row) : getValue(row, column.name) }}</td>
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
    export default {
        name: "paginated-table",
        props: ['actions', 'columns', 'module', 'records'],
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
            getTo(to, row) {
                if (typeof to === 'string') {
                    return to;
                } else if (typeof to === 'function') {
                    return to(row) || '/';
                }
            }
        },
        computed: {
            pages() {
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
        }
    }
</script>