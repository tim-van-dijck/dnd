export const useWealth = () => {
    return (inventory) => {
        const fallback = 'There is no currency in this inventories.'
        if (!inventory) {
            return fallback
        }

        let currency = []
        for (const coin of ['platinum', 'electrum', 'gold', 'silver', 'copper']) {
            if ((
                inventory[coin] || 0
            ) > 0) {
                currency.push(`${inventory[coin]} ${coin.substr(0, 1)}p`)
            }
        }

        return currency.length > 0 ? currency.join(' ') : fallback
    }
}