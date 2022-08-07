<template>
    <header id="top-head" class="uk-position-fixed">
        <div class="uk-container uk-container-expand uk-background-primary">
            <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
                <div class="uk-navbar-left">
                    <slot name="left"></slot>
                </div>
                <div class="uk-navbar-right">
                    <slot name="right">
                        <ul class="uk-navbar-nav">
                            <li><a href="/admin" class="uk-navbar-item">Admin</a></li>
                            <li><a href="/campaigns" class="uk-navbar-item">My Campaigns</a></li>
                            <li><a href="/profile" class="uk-navbar-item">My Profile</a></li>
                            <li>
                                <a href="/logout" title="Sign out" class="uk-navbar-item"
                                   @click.prevent="logout">
                                    <i class="fas fa-sign-out-alt fa-fw"></i>
                                </a>
                            </li>
                        </ul>
                    </slot>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
import { useMainStore } from '../../admin/stores/main'

export default {
    name: 'header-navbar',
    setup() {
        const store = useMainStore()

        return {
            logout: () => store.logout()
        }
    }
}
</script>