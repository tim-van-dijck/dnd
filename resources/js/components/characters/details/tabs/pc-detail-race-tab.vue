<template>
    <div v-if="race" id="race-tab" class="uk-width">
        <h2>{{ `${race.name} ${race.subrace ? '(' + race.subrace.name + ')' : ''}` }}</h2>
        <div uk-accordion>
            <div class="accordion uk-open">
                <div class="uk-accordion-title uk-card-title">Description</div>
                <div class="uk-accordion-content">
                    <div v-html="race.description"></div>
                </div>
            </div>
        </div>

        <h3>Race Traits</h3>
        <div uk-grid>
            <div class="uk-width-1-3">
                <ul class="uk-nav uk-nav-default">
                    <li v-for="trait in race.traits" :class="{'uk-active': active === trait}">
                        <a href="#" @click.prevent="active = trait">
                            {{ trait.name }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="uk-width-2-3">
                <template v-if="active">
                    <h4>{{ active.name }}</h4>
                    <div v-html="active.description"></div>
                </template>
                <div v-else class="uk-alert-primary" uk-alert>Select a trait to get some details</div>
            </div>
        </div>
    </div>
    <p v-else class="uk-text-center">
        <i class="fas fa-2x fa-sync fa-spin"></i>
    </p>
</template>

<script>
export default {
    name: "pc-detail-race-tab",
    props: ['race'],
    data() {
        return {
            active: null
        }
    }
}
</script>