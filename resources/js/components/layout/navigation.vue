<template>
    <aside id="left-col" class="uk-light uk-visible@m">
        <div class="left-logo uk-flex uk-flex-middle">
            KANKA HARD GAAN
        </div>

        <div class="left-nav-wrap">
            <ul class="uk-nav uk-nav-default" data-uk-nav>
                <li class="uk-nav-header">CAMPAIGN</li>
                <li>
                    <router-link :to="{name: 'player-characters'}">
                        <i class="fas fa-users fa-fw"></i> Characters
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'locations'}">
                        <i class="fas fa-map-marked-alt fa-fw"></i> Locations
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'quests'}">
                        <i class="fas fa-exclamation fa-fw"></i> Quests
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'notes'}">
                        <i class="fas fa-book-open fa-fw"></i> Notes
                    </router-link>
                </li>
                <li class="uk-nav-header">PLATFORM</li>
                <li>
                    <router-link :to="{name: 'users'}">
                        <i class="fas fa-users-cog fa-fw"></i> Users
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'roles'}">
                        <i class="fas fa-user-lock fa-fw"></i> Campaign roles
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="bar-bottom">
            <ul class="uk-subnav uk-flex uk-flex-center uk-child-width-1-5" data-uk-grid>
                <li><a href="/campaigns" title="Home"><i class="fas fa-home fa-fw"></i></a></li>
                <li><a href="/settings" title="Settings"><i class="fas fa-sliders-h fa-fw"></i></a></li>
                <li><a href="/dice" title="Roll dice"><i class="fas fa-dice-d20 fa-fw"></i></a></li>
                <li><a href="/logout" title="Sign out"><i class="fas fa-sign-out-alt fa-fw"></i></a></li>
            </ul>
        </div>
    </aside>
</template>

<script>
    export default {
        name: "Navigation"
    }
</script>