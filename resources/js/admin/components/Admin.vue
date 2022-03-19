<template>
    <div id="admin-app">
        <header-navbar />
        <navigation>
            <aside id="left-col" class="uk-light uk-visible@m">
                <div class="left-logo uk-flex uk-flex-middle">
                    DUNGEONS & DIARIES
                </div>

                <div class="left-nav-wrap">
                    <ul class="uk-nav uk-nav-default" data-uk-nav>
                        <li :key="i" v-for="i in 5">
                            <span class="navigation-placeholder">&nbsp;</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </navigation>
        <messages></messages>
        <div id="content" uk-height-viewport="expand: true">
            <div class="uk-container uk-container-expand">
                <router-view>
                    <div class="uk-section uk-section-default">
                        <div class="uk-container padded">
                            &nbsp;
                        </div>
                    </div>
                </router-view>
                <footer class="uk-section uk-section-small uk-text-center">
                    <hr>
                    <p class="uk-text-small uk-text-center">
                        Made with love by <a href="https://bit.ly/2Rz0xNG" target="_blank">Tim van Dijck</a>
                    </p>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
import Messages from "@components/layout/messages";
import Navigation from "@admin/components/layout/navigation";
import HeaderNavbar from "@components/layout/header-navbar";

export default {
    name: "Admin",
    components: {HeaderNavbar, Navigation, Messages}
}
</script>