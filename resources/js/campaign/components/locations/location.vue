<template>
    <div v-if="location">
        <h1>{{ location.name }}</h1>
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
                            <img class="preview-image" v-if="location.map" :src="`/storage/${location.map}`" alt="Uploaded map image">
                        </div>
                        <hr>
                        <div class="uk-margin uk-form-controls">
                            <input id="private" name="private" type="checkbox" class="uk-checkbox" v-model="location.private">
                            <label for="private">Private</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <h3>Description</h3>
                        <div v-html="location.description"></div>
                    </div>
                </div>
                <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'locations'}">
                            Cancel
                        </router-link>
                    </p>
            </div>
        </div>
    </div>
    <p v-else class="uk-text-center">
        <i class="fas fa-2x fa-sync fa-spin"></i>
    </p>
</template>

<script>
    export default {
        props: ['id'],
        name: "Location",
        created() {
            this.$store.dispatch('Locations/find', this.id)
                .then((location) => {
                    this.location = JSON.parse(JSON.stringify(location));
                });
        },
        data() {
            return {
                location: null
            }
        },
    }
</script>