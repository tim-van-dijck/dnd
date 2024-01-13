import { PlayerCharacter } from '@dnd/types'
import { useCampaignRepositories } from '../../../../providers/CampaignRepositoryProvider'

export const usePlayerCharacterOverviewUi = () => {
  const { AuthRepository } = useCampaignRepositories()

  return (
    {
      can: AuthRepository.can,
      actions: [
        {
          name: 'destroy',
          title: 'Delete character',
          icon: 'trash',
          classes: 'uk-text-danger',
          condition: ({ id }) => AuthRepository.can('delete', 'character', id)
        },
        {
          name: 'edit',
          title: 'Edit character',
          icon: 'edit',
          to: ({ id }) => `/characters/players/${id}/edit`,
          condition: ({ id }) => AuthRepository.can('edit', 'character', id)
        },
        {
          name: 'view',
          title: 'Go to player-character',
          icon: 'eye',
          to: ({ id }) => `/characters/players/${id}`,
          condition: ({ id }) => AuthRepository.can('view', 'character', id)
        },
        {
          name: 'sheet',
          title: 'Download character sheet',
          href: ({ id }) => `/campaign/characters/${id}/sheet`,
          icon: 'file',
          newTab: true,
          condition: ({ id }) => AuthRepository.can('view', 'character', id)
        },
        {
          name: 'inventory',
          title: 'Go to inventories',
          icon: 'shopping-bag',
          to: (character: PlayerCharacter) => `/inventories/${character.info.inventory_id}`,
          condition: ({ id, info }: PlayerCharacter) => AuthRepository.can('view', 'character', id)
          // && AuthRepository.can('view', 'inventory', info.inventory_id)
        }
      ],
      columns: [
        {
          title: 'Name',
          name: 'info.name'
        },
        {
          title: 'Race',
          name: 'race',
          format(race) {
            let value = race.name
            if (race.subrace) {
              value += ` (${race.subrace.name})`
            }
            return value
          }
        },
        {
          title: 'Class',
          name: 'classes',
          format(classes) {
            if (Array.isArray(classes)) {
              if (classes.length === 1) {
                return classes[0].name
              } else {
                const classNames = classes.map(({ name }) => name)
                if (classNames.length > 0) {
                  return `Multiclass: ${classNames.join(' - ')}`
                }
              }
            }
            return 'N/A'
          }
        },
        {
          title: 'Level',
          name: 'level',
          format(_, row) {
            let level = 0
            for (const charClass of row.classes) {
              level += parseInt(charClass.level)
            }
            return level
          }
        },
        {
          name: 'owner',
          title: 'Owner',
          format(owner) {
            return owner || 'N/A'
          }
        }
      ]
    }
  )
}