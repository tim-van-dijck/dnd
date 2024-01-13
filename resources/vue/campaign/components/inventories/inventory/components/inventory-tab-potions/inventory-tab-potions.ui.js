import UIKit from 'uikit'

export const ui = {
    actions: [
        {
            name: 'info',
            icon: 'info-circle'
        }
    ],
    columns: [
        {
            name: 'name',
            label: 'Name',
            format: (name, item) => `${name} ${item.equipped ? ' (equipped)' : ''}`
        },
        {
            name: 'quantity',
            label: '#'
        },
        {
            name: 'weight',
            label: 'Weight',
            format: (weight, item) => `${weight * item.quantity}lbs`
        }
    ],
    info(item) {
        UIKit.modal.dialog(`<div class="uk-modal-body"><h2>${item.name}</h2><div>${item.description}</div></div>`)
    }
}