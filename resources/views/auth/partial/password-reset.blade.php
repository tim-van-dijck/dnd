<form class="toggle-class" action="/password/email" method="post" hidden>
    @csrf
    <div class="uk-margin-small">
        <div class="uk-inline uk-width-1-1">
            <input class="uk-input" placeholder="E-mail" name="email" required type="text">
            <i class="uk-form-icon uk-form-icon-flip fas fa-envelope"></i>
        </div>
    </div>
    <div class="uk-margin-bottom">
        <button type="submit" class="uk-button uk-button-primary uk-width-1-1">SEND PASSWORD</button>
    </div>
</form>