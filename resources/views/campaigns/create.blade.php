@extends('layout.app')

@section('content')
    <h1>Add Campaign</h1>
    <form class="uk-section uk-section-default" method="post" action="{{ route('campaigns.store') }}">
        <div class="uk-container padded">
            <form id="campaign-form" class="uk-form-stacked">
                @csrf
                <div class="uk-margin">
                    <label for="name" class="uk-form-label {{ $errors->has('name') ? 'uk-text-danger': '' }}">Name*</label>
                    <input id="name" name="name" type="text" class="uk-input {{ $errors->has('name') ? 'uk-form-danger': '' }}" value="{{ old('name') }}">
                </div>
                <div class="uk-margin">
                    <label for="description" class="uk-form-label {{ $errors->has('name') ? 'uk-form-danger': '' }}">Description</label>
                    <textarea id="description" name="description">{{ old('description') }}</textarea>
                </div>
                <p class="uk-margin">
                    <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                    <a class="uk-button uk-button-danger" href="{{ route('campaigns.index') }}">
                        Cancel
                    </a>
                </p>
            </form>
        </div>
    </form>
@stop

@section('js')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '#description',
            height: 400,
            plugins: [
                'link', 'template', 'hr', 'anchor', 'fullscreen',
                'searchreplace', 'autolink', 'table'
            ],
            toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
            init_instance_callback: function(editor) {
                var freeTiny = document.querySelector('.tox-notifications-container');
                freeTiny.style.display = 'none';
            }
        });
    </script>
@stop