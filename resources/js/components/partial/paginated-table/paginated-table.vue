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
                            <a v-else-if="action.href" :href="getHref(action.href, row)"
                               :target="action.newTab ? '_blank' : ''" :title="action.title">
                                <i :class="`fas fa-${action.icon}`"></i>
                            </a>
                            <a href="/" :class="action.classes || ''" @click.prevent="$emit(action.name, row)"
                               :title="action.title" v-else>
                                <i :class="`fas fa-${action.icon}`"></i>
                            </a>
                        </li>
                    </ul>
                </td>
                <template v-for="column in columns">
                    <td v-if="column.formatRaw && typeof column.formatRaw === 'function'"
                        v-html="column.formatRaw(getValue(row, column.name), column.name)"></td>
                    <td v-else>
                        {{
                            column.format ? column.format(getValue(row, column.name), row) : getValue(row, column.name)
                        }}
                    </td>
                </template>
            </tr>
            </tbody>
        </table>
        <div class="uk-flex uk-flex-between uk-margin-medium-top" uk-margin>
            <div>
                <span>{{ records.meta.from }} - {{ records.meta.to }} of {{ records.meta.total }}</span>
            </div>
            <ul v-if="pages.length > 1" class="uk-pagination uk-margin-remove">
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
    </div>
</template>

<script>
import { usePagination, useSearch } from './paginated-table.state'
import { getHref, getTo, getValue } from './paginated-table.ui'

export default {
    name: 'paginated-table',
    props: {
        actions: {},
        columns: {},
        records: {
            type: Object,
            default: null
        },
        store: {
            required: true
        },
        searchable: {
            type: Boolean,
            default: false
        }
    },
    setup(props) {
        const { previous, next, go, pages } = usePagination(props.store, props.records)
        const { query, search } = useSearch(props.store)

        return {
            getValue,
            getHref,
            getTo,
            previous,
            next,
            go,
            pages,
            query,
            search
        }
    }
}
</script>