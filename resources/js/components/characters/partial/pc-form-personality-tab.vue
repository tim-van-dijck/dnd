<template>
    <div class="personality">
        <div class="uk-margin">
            <h2 class="uk-h4">Personality trait</h2>
            <html-editor id="trait" name="trait" v-model="personality.trait" height="200"></html-editor>
        </div>
        <div class="uk-margin">
            <h2 class="uk-h4">Ideal</h2>
            <html-editor id="ideal" name="ideal" v-model="personality.ideal" height="200"></html-editor>
        </div>
        <div class="uk-margin">
            <h2 class="uk-h4">Bond</h2>
            <html-editor id="bond" name="bond" v-model="personality.bond" height="200"></html-editor>
        </div>
        <div class="uk-margin">
            <h2 class="uk-h4">Flaw</h2>
            <html-editor id="flaw" name="flaw" v-model="personality.flaw" height="200"></html-editor>
        </div>
    </div>
</template>

<script>
    import HtmlEditor from "../../partial/html-editor";
    export default {
        name: "pc-form-personality-tab",
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
        },
        components: {HtmlEditor}
    }
</script>