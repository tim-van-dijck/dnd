<template>
    <div id="pc-form-nav">
        <div class="uk-button-group uk-width-expand uk-flex-between uk-visible@m">
            <button v-for="tab in tabs.list"
                    v-if="!tab.hasOwnProperty('condition') || !!tab.condition"
                    class="uk-button"
                    :class="classes(tab.key)"
                    @click.prevent="tabs.navigate(tab.key)">
                {{ tab.label }}
                <i v-if="Object.keys(errors).find(item => item.includes(tab.errorKey || tab.key))"
                   class="fas fa-exclamation-triangle"
                   :class="{'uk-text-danger': tabs.activeTab === tab.key}"/>
            </button>
        </div>

        <nav class="uk-navbar-container uk-hidden@m" uk-navbar="dropbar: true; dropbar-mode: push">
            <div class="uk-navbar-center">
                <ul class="uk-navbar-nav">
                    <li>
                        <button type="button" class="uk-button uk-button-primary uk-width-expand uk-text-center">
                            {{ tabs.activeTab }}
                            <i v-if="Object.keys(errors).length > 0"
                               class="fas fa-exclamation-triangle uk-text-danger"></i>
                        </button>
                        <div class="uk-navbar-dropdown uk-navbar-dropdown-width-2">
                            <div class="uk-navbar-dropdown-grid uk-child-width" uk-grid>
                                <div class="uk-width-1-1">
                                    <ul class="uk-width uk-nav uk-navbar-dropdown-nav">
                                        <li v-for="tab in tabs.list">
                                            <button v-if="!tab.hasOwnProperty('condition') || !!tab.condition"
                                                    class="uk-button"
                                                    :class="classes(tab.key)"
                                                    @click.prevent="tabs.navigate(tab.key)">
                                                {{ tab.label }} <i
                                                v-if="Object.keys(errors).find(item => item.includes(tab.errorKey || tab.key))"
                                                class="fas fa-exclamation-triangle uk-text-danger"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
import { useStyling, useTabs } from './player-character-form-navigation.ui'

export default {
    props: ['character', 'errors', 'spellcaster', 'tab'],
    setup(props, ctx) {
        const tabs = useTabs(props, ctx)
        const { classes } = useStyling(tabs.enabledTabs, tabs.activeTab)

        return { classes, tabs }
    }
}
</script>
