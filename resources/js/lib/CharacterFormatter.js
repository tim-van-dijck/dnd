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
            let features = {};
            for (let feature of charClass.features) {
                if (feature.choose > 0 && (feature.choices || []).length > 0) {
                    features[feature.id] = {};
                    for (let index in feature.choices) {
                        features[feature.id][parseInt(index) + 1] = feature.choices[index].id;
                    }
                }
            }
            let classObject = {
                class_id: charClass.id,
                level: charClass.level,
                subclass_id: charClass.subclass ? charClass.subclass.id || null : null
            };

            if (Object.keys(features).length > 0) {
                classObject.features = features;
            }

            formatted.push(classObject);
        }
        return formatted;
    }
}