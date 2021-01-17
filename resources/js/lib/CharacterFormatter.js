export default {
    format(character) {
        let char = {
            info: character.info,
            personality: character.personality,
            ability_scores: character.ability_scores,
            background_id: character.background_id,
            proficiencies: character.proficiencies,
            spells: character.spells
        }
        char.info.race_id = character.race.id;
        char.info.subrace_id = character.subrace ? character.subrace.id : null;
        char.classes = this.formatClasses(character.classes);
        return char;
    },

    formatClasses(classes) {
        let formatted = [];

        for (let charClass of classes) {
            formatted.push({
                class_id: charClass.id,
                level: charClass.level,
                subclass_id: charClass.subclass ? charClass.subclass.id || null : null
            });
        }
        return formatted;
    }
}