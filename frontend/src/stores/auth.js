import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useToast } from 'vue-toastification'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const loading = ref(false)

  const toast = useToast()

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.roles?.some(role => role.name === 'admin'))

  const setToken = (newToken) => {
    token.value = newToken
    if (newToken) {
      localStorage.setItem('token', newToken)
      api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
    } else {
      localStorage.removeItem('token')
      delete api.defaults.headers.common['Authorization']
    }
  }

  const setUser = (userData) => {
    user.value = userData
  }

  const login = async (credentials) => {
    loading.value = true
    try {
      const response = await api.post('/auth/login', credentials)
      const { token: newToken, user: userData } = response.data.authorization
      
      setToken(newToken)
      setUser(userData)
      
      toast.success('Login realizado com sucesso!')
      return { success: true }
    } catch (error) {
      const message = error.response?.data?.message || 'Erro ao fazer login'
      toast.error(message)
      return { success: false, error: message }
    } finally {
      loading.value = false
    }
  }

  const register = async (userData) => {
    loading.value = true
    try {
      const response = await api.post('/auth/register', userData)
      const { token: newToken, user: userInfo } = response.data.authorization
      
      setToken(newToken)
      setUser(userInfo)
      
      toast.success('Cadastro realizado com sucesso!')
      return { success: true }
    } catch (error) {
      const message = error.response?.data?.message || 'Erro ao fazer cadastro'
      toast.error(message)
      return { success: false, error: message }
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    try {
      await api.post('/auth/logout')
    } catch (error) {
      console.error('Erro ao fazer logout:', error)
    } finally {
      setToken(null)
      setUser(null)
      toast.success('Logout realizado com sucesso!')
    }
  }

  const fetchUser = async () => {
    if (!token.value) return

    try {
      const response = await api.get('/auth/me')
      setUser(response.data.user)
    } catch (error) {
      console.error('Erro ao buscar usuário:', error)
      setToken(null)
      setUser(null)
    }
  }

  const initializeAuth = async () => {
    if (token.value) {
      await fetchUser()
    }
  }

  return {
    user,
    token,
    loading,
    isAuthenticated,
    isAdmin,
    login,
    register,
    logout,
    fetchUser,
    initializeAuth
  }
})
