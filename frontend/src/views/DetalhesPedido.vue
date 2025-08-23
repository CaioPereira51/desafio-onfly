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
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
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
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Detalhes do Pedido</h2>
              <p class="mt-2 text-gray-600">
                Informações completas sobre o pedido de viagem.
              </p>
            </div>
            <router-link
              to="/pedidos"
              class="btn btn-secondary"
            >
              Voltar
            </router-link>
          </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="card">
          <div class="flex items-center justify-center py-8">
            <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-3 text-gray-600">Carregando...</span>
          </div>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="card">
          <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Erro ao carregar pedido</h3>
            <p class="mt-1 text-sm text-gray-500">{{ error }}</p>
            <div class="mt-6">
              <router-link
                to="/pedidos"
                class="btn btn-primary"
              >
                Voltar aos Pedidos
              </router-link>
            </div>
          </div>
        </div>

        <!-- Pedido Details -->
        <div v-else-if="pedido" class="space-y-6">
          <!-- Status Card -->
          <div class="card">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-medium text-gray-900">Pedido #{{ pedido.id }}</h3>
                <p class="text-sm text-gray-500">Criado em {{ formatDate(pedido.created_at) }}</p>
              </div>
              <span
                :class="getStatusClass(pedido.status)"
                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full"
              >
                {{ getStatusLabel(pedido.status) }}
              </span>
            </div>
          </div>

          <!-- Pedido Information -->
          <div class="card">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Viagem</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
              <div>
                <dt class="text-sm font-medium text-gray-500">Solicitante</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ pedido.nome_solicitante }}</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Destino</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ pedido.destino }}</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Data de Ida</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(pedido.data_ida) }}</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Data de Volta</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(pedido.data_volta) }}</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Duração</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ calculateDuration(pedido.data_ida, pedido.data_volta) }} dias</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Última Atualização</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(pedido.updated_at) }}</dd>
              </div>
            </dl>
          </div>

          <!-- Admin Actions -->
          <div v-if="authStore.isAdmin && pedido.status === 'solicitado'" class="card">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ações Administrativas</h3>
            <div class="flex space-x-3">
              <button
                @click="updateStatus('aprovado')"
                :disabled="updating"
                class="btn btn-primary"
              >
                <span v-if="updating" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Aprovando...
                </span>
                <span v-else>Aprovar Pedido</span>
              </button>
              
              <button
                @click="updateStatus('cancelado')"
                :disabled="updating"
                class="btn btn-danger"
              >
                <span v-if="updating" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Cancelando...
                </span>
                <span v-else>Cancelar Pedido</span>
              </button>
            </div>
          </div>

          <!-- Status History -->
          <div class="card">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Histórico de Status</h3>
            <div class="flow-root">
              <ul class="-mb-8">
                <li>
                  <div class="relative pb-8">
                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                    <div class="relative flex space-x-3">
                      <div>
                        <span class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center ring-8 ring-white">
                          <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                          </svg>
                        </span>
                      </div>
                      <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                        <div>
                          <p class="text-sm text-gray-500">
                            Pedido criado por <span class="font-medium text-gray-900">{{ pedido.nome_solicitante }}</span>
                          </p>
                        </div>
                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                          <time>{{ formatDate(pedido.created_at) }}</time>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                
                <li v-if="pedido.status !== 'solicitado'">
                  <div class="relative pb-8">
                    <div class="relative flex space-x-3">
                      <div>
                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                          <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                          </svg>
                        </span>
                      </div>
                      <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                        <div>
                          <p class="text-sm text-gray-500">
                            Pedido {{ pedido.status === 'aprovado' ? 'aprovado' : 'cancelado' }} por administrador
                          </p>
                        </div>
                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                          <time>{{ formatDate(pedido.updated_at) }}</time>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useToast } from 'vue-toastification'
import api from '../services/api'
import { format, differenceInDays } from 'date-fns'
import { ptBR } from 'date-fns/locale'

export default {
  name: 'DetalhesPedido',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const authStore = useAuthStore()
    const toast = useToast()
    
    const pedido = ref(null)
    const loading = ref(true)
    const updating = ref(false)
    const error = ref(null)

    const fetchPedido = async () => {
      try {
        const response = await api.get(`/pedidos/${route.params.id}`)
        pedido.value = response.data.data
      } catch (err) {
        error.value = err.response?.data?.message || 'Erro ao carregar pedido'
        console.error('Erro ao buscar pedido:', err)
      } finally {
        loading.value = false
      }
    }

    const updateStatus = async (status) => {
      updating.value = true
      try {
        await api.patch(`/pedidos/${route.params.id}/status`, { status })
        toast.success(`Pedido ${status === 'aprovado' ? 'aprovado' : 'cancelado'} com sucesso!`)
        await fetchPedido() // Refresh data
      } catch (err) {
        const message = err.response?.data?.message || 'Erro ao atualizar status'
        toast.error(message)
      } finally {
        updating.value = false
      }
    }

    const formatDate = (date) => {
      return format(new Date(date), 'dd/MM/yyyy HH:mm', { locale: ptBR })
    }

    const getStatusLabel = (status) => {
      const labels = {
        solicitado: 'Solicitado',
        aprovado: 'Aprovado',
        cancelado: 'Cancelado'
      }
      return labels[status] || status
    }

    const getStatusClass = (status) => {
      const classes = {
        solicitado: 'bg-yellow-100 text-yellow-800',
        aprovado: 'bg-green-100 text-green-800',
        cancelado: 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }

    const calculateDuration = (dataIda, dataVolta) => {
      return differenceInDays(new Date(dataVolta), new Date(dataIda)) + 1
    }

    const handleLogout = async () => {
      await authStore.logout()
      router.push('/login')
    }

    onMounted(() => {
      fetchPedido()
    })

    return {
      pedido,
      loading,
      updating,
      error,
      authStore,
      updateStatus,
      formatDate,
      getStatusLabel,
      getStatusClass,
      calculateDuration,
      handleLogout
    }
  }
}
</script>
