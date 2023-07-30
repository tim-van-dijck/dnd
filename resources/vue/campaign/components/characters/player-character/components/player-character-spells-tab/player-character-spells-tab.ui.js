export const getTitle = (level) => {
    switch (level) {
        case 1:
            return '1st level'
        case 2:
            return '2nd level'
        case 3:
            return '3rd level'
        default:
            return `${level}th level`
    }
}