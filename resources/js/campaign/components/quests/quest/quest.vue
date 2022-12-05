<template>
    <h1>
        <router-link class="uk-link-text" :to="{name: 'quests'}"><i class="fas fa-chevron-left"></i></router-link>
        {{ state.quest ? state.quest.title : '' }} <span v-if="state.quest.private" title="This quest is private">(<i
        class="fas fa-user-secret"></i>)</span></h1>
    <div class="uk-section uk-section-default">
        <div id="quest" v-if="state.quest" class="uk-container padded">
            <div uk-grid>
                <div class="uk-width-1-2">
                    <h2>Description</h2>
                    <div class="uk-margin">
                        <div v-if="state.quest.description" v-html="state.quest.description"/>
                        <template v-else>{{ 'No description' }}</template>
                    </div>
                </div>
                <div class="uk-width-1-2">
                    <h2>Objectives</h2>
                    <div class="uk-card uk-card-body objective" v-for="objective in ui.objectives.value">
                        <span class="actions uk-float-right">
                            <i class="fas fa-check-circle fa-fw" :class="{'uk-text-success': objective.status == 1}"
                               @click.prevent="state.complete(objective)"></i>
                            <i class="fas fa-times-circle fa-fw" :class="{'uk-text-danger': objective.status == 2}"
                               @click.prevent="state.fail(objective)"></i>
                        </span>
                        <span
                            :class="{'uk-text-success': objective.status == 1, 'uk-text-danger': objective.status == 2}">
                            <i v-if="objective.status == 1" class="fas fa-check-circle fa-fw"></i>
                            <i v-if="objective.status == 2" class="fas fa-times-circle fa-fw"></i>
                            <s v-if="objective.status > 0">{{ objective.name }}</s>
                            <span v-else>{{ objective.name }}</span>
                        </span>
                    </div>
                    <h2 v-if="ui.optional.value.length > 0">Optional</h2>
                    <div v-if="ui.optional.value.length > 0" class="uk-card uk-card-body objective"
                         v-for="objective in ui.optional.value">
                        <span class="actions uk-float-right">
                            <i class="fas fa-check-circle fa-fw" :class="{'uk-text-success': objective.status == 1}"
                               @click.prevent="state.complete(objective)"></i>
                            <i class="fas fa-times-circle fa-fw" :class="{'uk-text-danger': objective.status == 2}"
                               @click.prevent="state.fail(objective)"></i>
                        </span>
                        <span
                            :class="{'uk-text-success': objective.status == 1, 'uk-text-danger': objective.status == 2}">
                            <i v-if="objective.status == 1"
                               title="Toggle completed"
                               class="fas fa-check-circle fa-fw"/>
                            <i v-if="objective.status == 2"
                               title="Toggle failed"
                               class="fas fa-times-circle fa-fw"/>
                            <s v-if="objective.status > 0">{{ objective.name }}</s>
                            <span v-else>{{ objective.name }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <br><br>
            <p class="uk-margin">
                <router-link class="uk-button uk-button-text" :to="{name: 'quests'}">
                    <i class="fa fa-chevron-left fa-fw"></i> Back to quests
                </router-link>
            </p>
        </div>
        <p v-else class="uk-text-center">
            <i class="fas fa-2x fa-sync fa-spin"></i>
        </p>
    </div>
</template>

<script>
import { useQuestStore } from '@campaign/stores/quests'
import { onMounted } from 'vue'
import { useQuestDetailState } from './quest.state'
import { useObjectives } from './quest.ui'

export default {
    name: 'quest',
    props: ['id'],
    setup(props) {
        const store = useQuestStore()
        const state = useQuestDetailState(store)
        const { objectives, optional } = useObjectives(state)
        onMounted(() => store.find(props.id).then((quest) => state.setQuest(quest)))
        return {
            state,
            ui: {
                objectives,
                optional
            }
        }
    }
}
</script>