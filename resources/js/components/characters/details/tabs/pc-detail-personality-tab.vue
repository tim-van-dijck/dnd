<template>
    <div class="personality uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Personality trait</h2>
                <div v-html="personality.trait"></div>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Ideal</h2>
                <div v-html="personality.ideal"></div>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Bond</h2>
                <div v-html="personality.bond"></div>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body">
                <h2 class="uk-card-title">Flaw</h2>
                <div v-html="personality.flaw"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "pc-detail-personality-tab",
        props: ['value'],
        mounted() {
            this.$set(this, 'personality', this.value);
        },
        data() {
            return {
                personality: {
                    trait: '',
                    ideal: '',
                    bond: '',
                    flaw: ''
                }
            }
        },
        watch: {
            personality: {
                deep: true,
                handler() {
                    this.$emit('input', this.personality);
                }
            }
        }
    }
</script>