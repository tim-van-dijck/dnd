import UIKit from 'uikit'
import { useMessageBus } from "../../services/messages";

export const useModals = () => {
  const messageBus = useMessageBus()
  const confirm = (message, errorMessage, callback) => {
    UIKit.modal.confirm(message)
      .then(() => callback)
      .catch(() => {
      })
  }

  const prompt = (message, options, expectedInput, errorMessage, callback) => {
    UIKit.modal.prompt(message, '', options)
      .then((input) => {
        if (input === expectedInput) {
          callback()
        } else {
          messageBus.error(errorMessage)
        }
      })
  }

  const confirmDelete = (entity, callback) => prompt(
    `Are you sure you want to delete this ${entity}? Please write DELETE to confirm`,
    {
      labels: {
        ok: 'Delete',
        cancel: 'cancel'
      }
    }, 'DELETE',
    'Invalid input, delete cancelled.',
    callback
  )


  return {
    confirm,
    confirmDelete,
    prompt
  }
}
