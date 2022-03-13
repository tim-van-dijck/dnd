<div class="uk-container uk-container-expand uk-background-primary">
    <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
        <div class="uk-navbar-left">
            <div class="uk-navbar-item uk-visible@s">
            </div>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><a href="/campaigns" class="uk-navbar-item">My Campaigns</a></li>
                <li><a href="/profile" class="uk-navbar-item">My Profile</a></li>
                <li><a href="/admin"><i class="fas fa-cogs"></i></a></li>
                <li>
                    <form action="/logout" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="uk-navbar-item uk-button-link" title="Sign out">
                            <i class="fas fa-sign-out-alt fa-fw"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</div>