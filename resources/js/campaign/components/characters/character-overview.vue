<template>
    <div id="characters">
        <h1>Characters</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <ul class="uk-tab">
                    <li :class="{'uk-active': $route.name == 'player-characters'}">
                        <router-link :to="{name: 'player-characters'}">
                            Player Characters
                        </router-link>
                    </li>
                    <li :class="{'uk-active': $route.name == 'npcs'}">
                        <router-link :to="{name: 'npcs'}">
                            NPC's
                        </router-link>
                    </li>
                </ul>
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CharacterOverview"
    }
</script>