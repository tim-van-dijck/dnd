<template>
    <div id="proficiency-tab">
        <div uk-accordion="multiple: true;">
            
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="$emit('next')">Next <i class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
    export default {
        name: "pc-form-features-tab.vue"
    }
</script>