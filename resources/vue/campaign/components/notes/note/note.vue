<template>
    <div v-if="note">
        <h1>
            <router-link class="uk-link-text" :to="{name: 'notes'}"><i class="fas fa-chevron-left"></i></router-link>
            {{ note ? note.name : '' }} <span v-if="note.private" title="This character is private">(<i
            class="fas fa-user-secret"></i>)</span></h1>
        <div class="uk-section uk-section-default">
            <div id="note" class="uk-container padded">
                <div v-html="note.content"></div>
                <p class="uk-margin">
                    <router-link class="uk-button uk-button-text" :to="{name: 'notes'}">
                        <i class="fa fa-chevron-left fa-fw"></i> Back to notes
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
import { useNoteStore } from '@campaign/stores/notes'
import { onMounted, ref } from 'vue'

export default {
    name: 'Note',
    props: ['id'],
    setup(props) {
        const store = useNoteStore()
        const note = ref(null)

        onMounted(() => store.find(props.id).then((result) => note.value = result))

        return { note }
    }
}
</script>