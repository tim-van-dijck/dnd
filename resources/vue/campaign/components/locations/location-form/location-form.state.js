import { reactive } from 'vue'
import { useRouter } from 'vue-router'

export const useLocationFormState = (store, can, id) => {
    const router = useRouter()
    return reactive({
        errors: {},
        input: null,
        src: null,
        init() {
            if (id) {
                store.find(id)
                    .then((location) => {
                        this.input = reactive({ ...location })
                        if (typeof this.input.map == 'string' && this.input.map.length > 0) {
                            this.src = `/storage/${this.input.map}`
                        }
                    })
            } else {
                this.input = {}
            }
        },
        setInput(input) {
            this.input = input
        },
        setErrors(errors) {
            this.errors = errors
        },
        handleFileChange(e) {
            const files = e.target.files || e.dataTransfer.files
            if (files.length) {
                this.createImage(files[0])
            }
        },
        createImage(file) {
            this.input.map = file
            const reader = new FileReader()
            reader.onload = (e) => {
                this.src = e.target.result
            }
            reader.readAsDataURL(file)

        },
        handleSubmit() {
            const location = new FormData()
            for (const prop of ['name', 'type', 'description', 'location_id', 'map']) {
                switch (prop) {
                    case 'map':
                        if (this.input[prop] instanceof File) {
                            location.append(prop, this.input[prop])
                        }
                        break
                    case 'location_id':
                        if (typeof this.input[prop] === 'number' && this.input[prop] > 0) {
                            location.append(prop, this.input[prop])
                        }
                        break
                    default:
                        if (this.input[prop] !== '' && this.input[prop] != null) {
                            location.append(prop, this.input[prop])
                        }
                }
            }
            location.append('private', this.input.private ? 1 : 0)
            if (can('edit', 'role')) {
                for (const userId in this.input.permissions) {
                    const permission = this.input.permissions[userId]
                    location.append(`permissions[${userId}][view]`, permission.view ? 1 : 0)
                    location.append(`permissions[${userId}][create]`, permission.create ? 1 : 0)
                    location.append(`permissions[${userId}][edit]`, permission.edit ? 1 : 0)
                    location.append(`permissions[${userId}][delete]`, permission.delete ? 1 : 0)
                }
            }

            const promise = id > 0 ? store.update({ id, location }) : store.store(location)

            promise
                .then(() => router.push({ name: 'locations' }))
                .catch((error) => this.setErrors(error.response.data.errors))
        }
    })
}