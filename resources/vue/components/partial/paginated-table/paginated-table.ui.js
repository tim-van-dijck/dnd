export const getHref = (href, row) => {
    if (typeof href === 'string') {
        return href
    } else if (typeof href === 'function') {
        return href(row) || '/'
    }
}

export const getTo = (to, row) => {
    if (typeof to === 'object') {
        return to
    } else if (typeof to === 'function') {
        return to(row)
    }
}

export const getValue = (row, name) => {
    let value = row
    for (let key of name.split('.')) {
        value = value[key]
    }
    return value
}