<template>
    <div class="personality uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Personality trait</h2>
                <div v-if="personality.trait" v-html="personality.trait"></div>
                <p class="uk-text-center uk-text-italic" v-else>This character doesn't have a personality trait</p>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Ideal</h2>
                <div v-if="personality.ideal" v-html="personality.ideal"></div>
                <p class="uk-text-center uk-text-italic" v-else>This character doesn't have an ideal</p>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Bond</h2>
                <div v-if="personality.bond" v-html="personality.bond"></div>
                <p class="uk-text-center uk-text-italic" v-else>This character doesn't have a bond</p>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Flaw</h2>
                <div v-if="personality.flaw" v-html="personality.flaw"></div>
                <p class="uk-text-center uk-text-italic" v-else>This character doesn't have a flaw</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "pc-detail-personality-tab",
        props: ['personality']
    }
</script>