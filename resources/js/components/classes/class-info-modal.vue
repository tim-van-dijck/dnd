<template>
    <div :id="`class-info-${selected.name}`" uk-modal>
        <div class="uk-width-expand uk-modal-dialog uk-modal-body">
            <h2 class="uk-modal-title uk-inline">
                <img style="height: 40px; width: 40px;" :src="`/img/classes/${selected.name}.svg`">
                {{ selected.name }}
                <span v-if="selected.spellcaster" class="uk-text-top uk-text-small">
                    <i class="fas fa-hand-sparkles" title="Spellcasting class"></i>
                </span>
            </h2>
            <div uk-grid>
                <div class="uk-width-1-3">
                    <h3>Description</h3>
                    <div class="class-description" v-html="selected.description"></div>
                </div>
                <div class="uk-width-2-3 class-specs">
                    <h3>Class Features</h3>
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-1-4" v-for="feature in selected.features">
                            <div class="uk-inline">
                                <b>{{ `${feature.name} (level ${feature.level})` }}</b>
                                <div uk-drop="mode:hover; boundary: .class-specs">
                                    <div class="uk-card uk-card-default uk-card-body" v-html="feature.description"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>{{ selected.subclass_flavor }}</h3>
                    <ul uk-tab>
                        <li :class="{'uk-active': active == subclass.id}" v-for="subclass in selected.subclasses">
                            <a href="#" @click.prevent="active = subclass.id">
                                {{ subclass.name }}
                                <span v-if="!selected.spellcaster && subclass.spellcaster">
                                    (<i class="fas fa-hand-sparkles" title="Spellcasting subclass"></i>)
                                </span>
                            </a>
                        </li>
                    </ul>
                    <div v-if="active == subclass.id" class="subclass-info" v-for="subclass in selected.subclasses">
                        <div class="class-description" v-html="subclass.description"></div>
                        <h4>Features</h4>
                        <ul class="uk-list">
                            <li v-for="feature in subclass.features">
                                <div class="uk-inline">
                                    <b>{{ `${feature.name} (level ${feature.level})` }}</b>
                                    <div uk-drop="mode:hover; boundary: .class-specs">
                                        <div class="uk-card uk-card-default uk-card-body" v-html="feature.description"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "class-info-modal",
        props: ['selected'],
        data() {
            return {
                active: null
            }
        },
        created() {
            this.active = this.selected.subclasses[0].id
        }
    }
</script>