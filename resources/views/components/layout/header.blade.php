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
                    <a href="/logout" title="Sign out" class="uk-navbar-item"
                       @click.prevent="$store.dispatch('logout')">
                        <i class="fas fa-sign-out-alt fa-fw"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>