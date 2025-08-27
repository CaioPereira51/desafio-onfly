import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const loading = ref(false)

  // const toast = useToast()

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.roles?.some(role => role.name === 'admin'))

  const setToken = (newToken) => {
    console.log('Definindo token:', newToken)
    token.value = newToken
    if (newToken) {
      localStorage.setItem('token', newToken)
      api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
      console.log('Token salvo no localStorage e headers')
    } else {
      localStorage.removeItem('token')
      delete api.defaults.headers.common['Authorization']
      console.log('Token removido do localStorage e headers')
    }
  }

  const setUser = (userData) => {
    user.value = userData
  }

  const login = async (credentials) => {
    loading.value = true
    try {
      console.log('Tentando fazer login com:', credentials)
      const response = await api.post('/auth/login', credentials)
      console.log('Resposta da API:', response.data)
      
      if (!response.data.authorization || !response.data.authorization.token) {
        throw new Error('Token não encontrado na resposta')
      }
      
      const { token: newToken } = response.data.authorization
      const userData = response.data.user
      
      console.log('Token recebido:', newToken)
      console.log('Dados do usuário:', userData)
      
      setToken(newToken)
      setUser(userData)
      
      // toast.success('Login realizado com sucesso!')
      return { success: true }
    } catch (error) {
      console.error('Erro no login:', error)
      const message = error.response?.data?.message || error.message || 'Erro ao fazer login'
      // toast.error(message)
      return { success: false, error: message }
    } finally {
      loading.value = false
    }
  }

  const register = async (userData) => {
    loading.value = true
    try {
      const response = await api.post('/auth/register', userData)
      const { token: newToken } = response.data.authorization
      const userInfo = response.data.user
      
      setToken(newToken)
      setUser(userInfo)
      
      // toast.success('Cadastro realizado com sucesso!')
      return { success: true }
    } catch (error) {
      const message = error.response?.data?.message || 'Erro ao fazer cadastro'
      // toast.error(message)
      console.error('Erro no cadastro:', message)
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
      // toast.success('Logout realizado com sucesso!')
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
