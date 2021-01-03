<template>
    <button v-if="button" uk-toggle="target: #spellbook-modal" class="uk-button uk-button-secondary" type="button">
        <i v-if="icon" class="fas fa-book fa-fw"></i>
        <slot></slot>
    </button>
    <a v-else href="" title="Spellbook" :uk-toggle="`target: #${name}`">
        <i v-if="icon" class="fas fa-book fa-fw"></i>
        <slot></slot>
    </a>
</template>

<script>
export default {
    props: {
        name: {
            type: String
        },
        button: {
            type: Boolean,
            default: false
        },
        icon: {
            type: Boolean,
            default: false
        },
    },
    name: "spellbook-button"
}
</script>