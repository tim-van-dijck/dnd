<template>
    <editor :id="id" :name="name" :value="value" @input="emit" :init="init"></editor>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'

import 'tinymce/icons/default'
import 'tinymce/plugins/anchor'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/fullscreen'
import 'tinymce/plugins/hr'
import 'tinymce/plugins/link'
import 'tinymce/plugins/paste'
import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/table'
import 'tinymce/themes/silver'

export default {
    name: 'HtmlEditor',
    props: ['id', 'name', 'value', 'height'],
    emits: ['input'],
    setup(props, ctx) {
        return {
            id: props.id,
            name: props.name,
            value: props.value,
            emit: (value) => ctx.emit('input', value),
            init: {
                height: props.height || 400,
                plugins: [
                    'link', 'hr', 'anchor', 'fullscreen',
                    'searchreplace', 'autolink', 'table'
                ],
                theme: 'silver',
                skin: 'oxide',
                skin_url: '/skins/ui/oxide',
                toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
                init_instance_callback() {
                    const freeTiny = document.querySelector('.tox-notifications-container')
                    if (freeTiny) {
                        freeTiny.style.display = 'none'
                    }
                }
            }
        }
    },
    components: { Editor }
}
</script>