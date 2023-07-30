const dateTimeFormat = Intl.DateTimeFormat('nl', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
})

export const formatDate = (date) => dateTimeFormat.format(new Date(date));