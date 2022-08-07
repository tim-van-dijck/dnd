import { debounce } from 'lodash'
import { computed, reactive, watch } from 'vue'

export const usePagination = (store, records) => {
    const currentPage = computed(() => records?.meta?.current_page || 1)
    const lastPage = computed(() => records?.meta?.last_page || 1)

    const pages = computed(() => {
        if (records === null) {
            return []
        }

        const pages = [
            { number: 1, active: currentPage.value === 1 }
        ]

        if (currentPage.value - 1 > 2) {
            pages.push({ number: '...', active: false })
        }

        for (let i = -2; i <= 2; i++) {
            if (currentPage.value + i > 1 && currentPage.value + i <= lastPage.value) {
                pages.push({ number: currentPage.value + i, active: i === 0 })
            }
        }

        if (lastPage.value - currentPage.value > 2) {
            pages.push({ number: '...', active: false })
            pages.push({ number: lastPage.value, active: false })
        }

        return pages
    })

    return {
        pages,
        previous() {
            store.previous()
        },
        go(number) {
            if (number !== currentPage.value) {
                store.page(number)
            }
        },
        next() {
            store.next()
        }
    }
}

export const useSearch = (store) => {
    const state = reactive({
        query: '',
        search: debounce(function () {
            if (this.query.length === 0) {
            }
            if (this.query.length >= 3) {
                store.load({ query: this.query })
            }
        }, 500)
    })

    watch(() => state.query, () => {
        state.search()
    })

    return state
}