<div class="uk-container uk-container-expand uk-background-primary">
    <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
        <div class="uk-navbar-left">
            <slot name="left">
                <div class="uk-navbar-item uk-visible@s">
                </div>
            </slot>
        </div>
        <div class="uk-navbar-right">
            <slot name="right">
                <ul class="uk-navbar-nav">
                    <li><a href="/admin" class="uk-navbar-item">Admin</a></li>
                    <li><a href="/campaigns" class="uk-navbar-item">My Campaigns</a></li>
                    <li><a href="/profile" class="uk-navbar-item">My Profile</a></li>
                    <li>
                        <form action="/logout" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" title="Sign out" class="uk-navbar-item uk-button uk-button-link">
                                <i class="fas fa-sign-out-alt fa-fw"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </slot>
        </div>
    </nav>
</div>