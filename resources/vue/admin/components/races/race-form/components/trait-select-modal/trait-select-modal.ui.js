import UIkit from "uikit";
import tinymce from "tinymce";

export const ui = {
    initEditorOnShow: () => {
        UIkit.util.on('#trait-select-modal', 'shown', () => {
            tinymce.init({
                height: 400,
                plugins: [
                    'link', 'hr', 'anchor', 'fullscreen',
                    'searchreplace', 'autolink', 'table'
                ],
                theme: 'silver',
                skin: 'oxide',
                skin_url: '/skins/ui/oxide',
                toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
                init_instance_callback() {
                    const freeTiny = document.querySelector('.tox-notifications-container');
                    if (freeTiny) {
                        freeTiny.style.display = 'none';
                    }
                }
            });

        });

    },
    removeEditorOnHide: () => {
        UIkit.util.on('#trait-select-modal', 'beforehide', () => {
            tinymce.remove("#trait-description");
        });
    },
    close: () => {
        UIkit.modal('#trait-select-modal').hide()
    }
}