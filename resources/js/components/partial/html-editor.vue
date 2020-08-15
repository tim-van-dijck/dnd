<template>
    <editor :id="id" :name="name" :value="value" @input="emit" :init="init"></editor>
</template>

<script>
    import tinymce from 'tinymce/tinymce';
    import Editor from '@tinymce/tinymce-vue';

    import 'tinymce/icons/default';
    import 'tinymce/themes/silver';

    import 'tinymce/plugins/paste';
    import 'tinymce/plugins/link';
    import 'tinymce/plugins/hr';
    import 'tinymce/plugins/anchor';
    import 'tinymce/plugins/searchreplace';
    import 'tinymce/plugins/autolink';
    import 'tinymce/plugins/table';
    import 'tinymce/plugins/fullscreen';

    export default {
        name: "HtmlEditor",
        props: ['id', 'name', 'value', 'height'],
        data() {
            return {
                init: {
                    height: this.height || 400,
                    plugins: [
                        'link', 'hr', 'anchor', 'fullscreen',
                        'searchreplace', 'autolink', 'table'
                    ],
                    theme: 'silver',
                    skin: 'oxide',
                    skin_url: '/skins/ui/oxide',
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