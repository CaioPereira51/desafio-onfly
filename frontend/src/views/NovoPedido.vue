<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-semibold text-gray-900">Sistema de Viagens</h1>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <router-link
                to="/dashboard"
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                Dashboard
              </router-link>
              <router-link
                to="/pedidos"
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                Pedidos
              </router-link>
              <router-link
                to="/pedidos/novo"
                class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                Novo Pedido
              </router-link>
            </div>
          </div>
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <span class="text-sm text-gray-500 mr-4">{{ authStore.user?.name }}</span>
              <button
                @click="handleLogout"
                class="btn btn-secondary"
              >
                Sair
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main content -->
    <main class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Novo Pedido de Viagem</h2>
          <p class="mt-2 text-gray-600">
            Preencha os dados abaixo para solicitar uma nova viagem corporativa.
          </p>
        </div>

        <!-- Form -->
        <div class="card">
          <form @submit.prevent="handleSubmit">
            <div class="space-y-6">
              <div>
                <label for="destino" class="form-label">Destino *</label>
                <input
                  id="destino"
                  v-model="form.destino"
                  type="text"
                  required
                  class="form-input"
                  placeholder="Ex: São Paulo, SP"
                  :class="{ 'border-red-500': errors.destino }"
                />
                <p v-if="errors.destino" class="mt-1 text-sm text-red-600">{{ errors.destino }}</p>
              </div>

              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="data_ida" class="form-label">Data de Ida *</label>
                  <input
                    id="data_ida"
                    v-model="form.data_ida"
                    type="date"
                    required
                    class="form-input"
                    :min="minDate"
                    :class="{ 'border-red-500': errors.data_ida }"
                  />
                  <p v-if="errors.data_ida" class="mt-1 text-sm text-red-600">{{ errors.data_ida }}</p>
                </div>

                <div>
                  <label for="data_volta" class="form-label">Data de Volta *</label>
                  <input
                    id="data_volta"
                    v-model="form.data_volta"
                    type="date"
                    required
                    class="form-input"
                    :min="form.data_ida || minDate"
                    :class="{ 'border-red-500': errors.data_volta }"
                  />
                  <p v-if="errors.data_volta" class="mt-1 text-sm text-red-600">{{ errors.data_volta }}</p>
                </div>
              </div>

              <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                      Informações Importantes
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                      <ul class="list-disc pl-5 space-y-1">
                        <li>As datas devem ser futuras</li>
                        <li>A data de volta deve ser posterior à data de ida</li>
                        <li>Seu pedido será revisado por um administrador</li>
                        <li>Você receberá notificação quando o status for atualizado</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex justify-end space-x-3">
                <router-link
                  to="/pedidos"
                  class="btn btn-secondary"
                >
                  Cancelar
                </router-link>
                <button
                  type="submit"
                  :disabled="loading"
                  class="btn btn-primary"
                >
                  <span v-if="loading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Criando...
                  </span>
                  <span v-else>Criar Pedido</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useToast } from 'vue-toastification'
import api from '../services/api'

export default {
  name: 'NovoPedido',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    const toast = useToast()
    
    const loading = ref(false)
    
    const form = reactive({
      destino: '',
      data_ida: '',
      data_volta: ''
    })

    const errors = reactive({
      destino: '',
      data_ida: '',
      data_volta: ''
    })

    const minDate = computed(() => {
      const today = new Date()
      return today.toISOString().split('T')[0]
    })

    const validateForm = () => {
      errors.destino = ''
      errors.data_ida = ''
      errors.data_volta = ''

      if (!form.destino.trim()) {
        errors.destino = 'Destino é obrigatório'
      }

      if (!form.data_ida) {
        errors.data_ida = 'Data de ida é obrigatória'
      } else {
        const dataIda = new Date(form.data_ida)
        const hoje = new Date()
        hoje.setHours(0, 0, 0, 0)
        
        if (dataIda <= hoje) {
          errors.data_ida = 'Data de ida deve ser futura'
        }
      }

      if (!form.data_volta) {
        errors.data_volta = 'Data de volta é obrigatória'
      } else if (form.data_ida && form.data_volta) {
        const dataIda = new Date(form.data_ida)
        const dataVolta = new Date(form.data_volta)
        
        if (dataVolta <= dataIda) {
          errors.data_volta = 'Data de volta deve ser posterior à data de ida'
        }
      }

      return !errors.destino && !errors.data_ida && !errors.data_volta
    }

    const handleSubmit = async () => {
      if (!validateForm()) return

      loading.value = true
      try {
        await api.post('/pedidos', form)
        toast.success('Pedido criado com sucesso!')
        router.push('/pedidos')
      } catch (error) {
        const message = error.response?.data?.message || 'Erro ao criar pedido'
        toast.error(message)
        
        if (error.response?.data?.errors) {
          Object.keys(error.response.data.errors).forEach(key => {
            if (errors[key] !== undefined) {
              errors[key] = error.response.data.errors[key][0]
            }
          })
        }
      } finally {
        loading.value = false
      }
    }

    const handleLogout = async () => {
      await authStore.logout()
      router.push('/login')
    }

    return {
      form,
      errors,
      loading,
      minDate,
      authStore,
      handleSubmit,
      handleLogout
    }
  }
}
</script>
