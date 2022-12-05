<template>
    <div id="messages" v-if="messages.length > 0">
        <transition-group name="slide" enter-active-class="slide-enter-active" leave-class-active="slide-leave-active">
            <div :key="message.id" v-for="message in messages" class="message" :class="`message-${message.type}`"
                 uk-alert>
                <p>{{ message.message }}</p>
            </div>
        </transition-group>
    </div>
</template>

<script>
import { useMessageStore } from '@stores/messages'

export default {
    name: 'Messages',
    setup() {
        const store = useMessageStore()

        return {
            messages: store.messages
        }
    }
}
</script>