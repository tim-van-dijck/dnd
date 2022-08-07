import { debounce } from 'lodash'

const onFileChange = (e) => {
    let files = e.target.files || e.dataTransfer.files
    if (files.length) {
        this.createImage(files[0])
    }
}

const createImage = (file) => {
    location.map = file
    var reader = new FileReader()
    reader.onload = (e) => {
        map = e.target.result
    }
    reader.readAsDataURL(file)
}

const save = () => {
    const location = new FormData()
    for (let prop of ['name', 'type', 'description', 'location_id', 'map']) {
        if ((
            ['map', 'location_id'].includes(prop) || this.location[prop] !== ''
        ) && this.location[prop] != null) {
            location.append(prop, this.location[prop])
        }
    }
    location.append('private', this.location.private ? 1 : 0)
    if (this.$store.getters.can('edit', 'role')) {
        for (let userId in this.location.permissions) {
            let permission = this.location.permissions[userId]
            location.append(`permissions[${userId}][view]`, permission.view ? 1 : 0)
            location.append(`permissions[${userId}][create]`, permission.create ? 1 : 0)
            location.append(`permissions[${userId}][edit]`, permission.edit ? 1 : 0)
            location.append(`permissions[${userId}][delete]`, permission.delete ? 1 : 0)
        }
    }

    let promise
    if (this.id > 0) {
        promise = this.$store.dispatch('Locations/update', { id: this.id, location })
    } else {
        promise = this.$store.dispatch('Locations/store', { location })
    }

    promise
        .then(() => {
            this.$router.push({ name: 'locations' })
        })
        .catch((error) => {
            this.$store.commit('Locations/SET_ERRORS', error.response.data.errors)
            this.$store.dispatch('Messages/error', error.response.data.message, { root: true })
        })
}

const search = debounce((query, loading, vm) => {
    axios.get(`/api/campaign/locations?filter[query]=${escape(query)}&page[number]=1&page[size]=10`)
        .then((response) => {
            let locations = response.data.data.map((item) => {
                return { value: item.id, label: item.name }
            })
            vm.$set(vm, 'locations', locations)
            loading(false)
        })
}, 1000)

export { search, save, createImage, onFileChange }