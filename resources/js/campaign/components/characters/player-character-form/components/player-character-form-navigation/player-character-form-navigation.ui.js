import { computed } from 'vue'

export const useTabs = (props, ctx) => {
    const enabledTabs = computed(() => {
        const enabled = ['details']
        if (props.character.info.hasOwnProperty('race_id') && props.character.info.race_id != null) {
            enabled.push('class')
            for (const chosenClass of props.character.classes) {
                if (chosenClass.class_id != null) {
                    enabled.push('ability', 'proficiency', 'personality', 'background')
                    if (props.spellcaster.value) {
                        enabled.push('spells')
                    }
                }
            }
        }
        return enabled.filter((value, index, self) => self.indexOf(value) === index)
    })
    const activeTab = computed(() => {
        switch (props.tab) {
            case 'ability':
                return 'Abilities'
            case 'proficiency':
                return 'Languages, Skills & Proficiencies'
            default:
                return props.tab.toLowerCase()
        }
    })
    const navigate = (tab) => {
        if (enabledTabs.value.includes(tab)) {
            ctx.emit('navigate', tab)
        }
    }

    return { activeTab, enabledTabs, list: getTabList(props.spellcaster.value), navigate }
}

export const useStyling = (enabledTabs, activeTab) => {
    return (tab) =>
        (
            {
                'uk-button-primary': tab === activeTab.value,
                'uk-button-default': tab !== activeTab.value && enabledTabs.value.includes(tab),
                'disabled': !enabledTabs.value.includes(tab)
            }
        )
}

const getTabList = (spellcaster) => [
    {
        key: 'details',
        label: 'Details',
        errorKey: 'info'
    },
    {
        key: 'class',
        label: 'Class',
        errorKey: 'classes'
    },
    {
        key: 'background',
        label: 'Background'
    },
    {
        key: 'proficiency',
        label: 'Languages, Skills & Proficiencies',
        errorKey: 'proficiencies'
    },
    {
        key: 'ability',
        label: 'Abilities',
        errorKey: 'ability_scores'
    },
    {
        key: 'personality',
        label: 'Personality'
    },
    {
        condition: spellcaster,
        key: 'spells',
        label: 'Spells'
    }
]