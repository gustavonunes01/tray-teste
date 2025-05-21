import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { User } from '@/services/user.service'
import { UserService } from '@/services/user.service'

export const useUserStore = defineStore('user', () => {
  const currentUser = ref<User | null>(null)
  const loading = ref(false)

  const fetchCurrentUser = async () => {
    loading.value = true
    try {
      currentUser.value = await UserService.getCurrentUser()
    } catch (error) {
      console.error('Erro ao carregar usuário:', error)
      currentUser.value = null
    } finally {
      loading.value = false
    }
  }

  const clearUser = () => {
    currentUser.value = null
  }

  return {
    currentUser,
    loading,
    fetchCurrentUser,
    clearUser
  }
}) 