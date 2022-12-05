<template>
    <div id="spellbook">
        <slot>
        </slot>
        <div :id="name" uk-modal>
            <div class="uk-width-expand uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title"><i class="fas fa-book"></i> Spellbook</h2>
                <spellbook></spellbook>
                <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import Spellbook from '../spellbook'

export default {
    props: {
        name: {
            type: String,
            default: 'spellbook-modal'
        }
    },
    name: 'spellbook-modal',
    components: { Spellbook }
}
</script>