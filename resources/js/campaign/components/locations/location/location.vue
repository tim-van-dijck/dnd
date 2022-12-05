<template>
    <div v-if="location">
        <h1>
            <router-link class="uk-link-text" :to="{name: 'locations'}"><i class="fas fa-chevron-left"></i>
            </router-link>
            {{ location.name }} <span v-if="location.private" title="This location is private">(<i
            class="fas fa-user-secret"></i>)</span></h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <div uk-grid>
                    <div class="uk-width-1-2">
                        <div class="uk-margin">
                            <h3>Type</h3>
                            <p>{{ location.type }}</p>
                        </div>
                        <div class="uk-margin">
                            <h3>Location</h3>
                            {{ location.location || 'N/A' }}
                        </div>
                        <div v-if="location.map" class="uk-margin">
                            <h3>Map</h3>
                            <img class="preview-image" v-if="location.map" :src="`/storage/${location.map}`"
                                 alt="Uploaded map image">
                        </div>
                        <hr>
                    </div>
                    <div class="uk-width-1-2">
                        <h3>Description</h3>
                        <div v-html="location.description"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p v-else class="uk-text-center">
        <i class="fas fa-2x fa-sync fa-spin"></i>
    </p>
</template>

<script>
import { useLocationStore } from '@campaign/stores/locations'
import { onMounted, ref } from 'vue'

export default {
    props: ['id'],
    name: 'Location',
    setup(props) {
        const location = ref(null)
        const store = useLocationStore()

        onMounted(() => store.find(props.id).then((result) => location.value = result))


        return { location }
    }
}
</script>