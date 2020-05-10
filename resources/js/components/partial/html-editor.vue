<template>
    <editor id="description" name="description" :value="value" @input="emit" :init="init"></editor>
</template>

<script>
    import Editor from '@tinymce/tinymce-vue';

    export default {
        name: "HtmlEditor",
        props: ['id', 'name', 'value', 'height'],
        data() {
            return {
                init: {
                    height: this.height || 400,
                    plugins: [
                        'link', 'template', 'hr', 'anchor', 'fullscreen',
                        'searchreplace', 'autolink', 'table'
                    ],
                    toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
                    init_instance_callback: function() {
                        var freeTiny = document.querySelector('.tox-notifications-container');
                        if (freeTiny) {
                            freeTiny.style.display = 'none';
                        }
                    }
                },
            }
        },
        methods: {
            emit(value) {
                this.$emit('input', value);
            }
        },
        components: {Editor}
    }
</script>