import axios from "axios";
import { useAuthRepository } from "../../../../../../repositories/AuthRepository";

export const useNavigationState = () => {
  const authRepository = useAuthRepository()

  const logout = (e) => {
    e.preventDefault()
    axios.post('/logout')
      .then(() => {
        document.location.href = '/'
      })
  }

  return {
    logout,
    navigation: navigation.filter(section => {
      return section.items.filter((item) => authRepository.can('view', item.name)).length > 0
    })
  }
}

const navigation = [
  {
    title: 'CAMPAIGN',
    items: [
      {
        name: 'character',
        title: 'Characters',
        to: '/characters',
        icon: 'users'
      },
      {
        name: 'location',
        title: 'Locations',
        to: '/locations',
        icon: 'map-marked-alt'
      },
      {
        name: 'quest',
        title: 'Quests',
        to: '/quests',
        icon: 'exclamation'
      },
      {
        name: 'journal',
        title: 'Journal',
        to: '/journal',
        icon: 'book-open'
      },
      {
        name: 'note',
        title: 'Notes',
        to: '/notes',
        icon: 'file-alt'
      },
      {
        name: 'inventory',
        title: 'Inventory',
        to: '/inventories',
        icon: 'shopping-bag'
      }
    ]
  },
  {
    title: 'PLATFORM',
    items: [
      {
        name: 'user',
        title: 'Users',
        to: '/users',
        icon: 'users-cog'
      },
      {
        name: 'role',
        title: 'Campaign Roles',
        to: '/roles',
        icon: 'user-lock'
      }
    ]
  }
]