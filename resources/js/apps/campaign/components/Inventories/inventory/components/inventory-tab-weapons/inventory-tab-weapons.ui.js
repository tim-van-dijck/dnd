const ui = {
    columns: [
        {
            name: 'name',
            label: 'Name',
            format(name, item) {
                return `${name} ${item.equipped ? ' (equipped)' : ''}`
            }
        },
        {
            name: 'properties.damage',
            label: 'Damage',
            format(damage, item) {
                return damage + item.properties.damage_dice
            }
        },
        {
            name: 'quantity',
            label: '#'
        },
        {
            name: 'weight',
            label: 'Weight',
            format(weight, item) {
                return `${weight * item.quantity}lbs`
            }
        },
        {
            name: 'properties',
            label: 'Properties',
            format(properties) {
                let result = []
                for (let key in properties) {
                    if (['damage', 'damage_dice', 'damage_type'].includes(key)) {
                        continue
                    }
                    switch (key) {
                        case 'dual_wield':
                            result.push({ label: 'Light' })
                            break
                        case 'two_handed':
                            result.push({ label: 'Two-Handed' })
                            break
                        case 'range':
                            result.push({ label: `Range (${properties.range})` })
                            break
                        case 'versatile':
                            result.push({ label: `Versatile (${properties.versatile})` })
                            break
                        case 'special':
                            result.push({ label: 'Special', title: properties.special })
                            break
                        default:
                            result.push({ label: key.replace(/^\w/, (c) => c.toUpperCase()) })
                            break
                    }
                }
                return result
            }
        }
    ]
}

export default ui