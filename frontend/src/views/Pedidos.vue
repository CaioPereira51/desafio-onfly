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
                class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
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
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Pedidos de Viagem</h2>
          <router-link
            to="/pedidos/novo"
            class="btn btn-primary"
          >
            Novo Pedido
          </router-link>
        </div>

        <!-- Filters -->
        <div class="card mb-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros</h3>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
              <label class="form-label">Status</label>
              <select v-model="filters.status" class="form-input">
                <option value="">Todos</option>
                <option value="solicitado">Solicitado</option>
                <option value="aprovado">Aprovado</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>
            
            <div>
              <label class="form-label">Destino</label>
              <input
                v-model="filters.destino"
                type="text"
                class="form-input"
                placeholder="Digite o destino"
              />
            </div>
            
            <div>
              <label class="form-label">Data Início</label>
              <input
                v-model="filters.data_inicio"
                type="date"
                class="form-input"
              />
            </div>
            
            <div>
              <label class="form-label">Data Fim</label>
              <input
                v-model="filters.data_fim"
                type="date"
                class="form-input"
              />
            </div>
          </div>
          
          <div class="mt-4 flex space-x-3">
            <button
              @click="applyFilters"
              class="btn btn-primary"
              :disabled="loading"
            >
              {{ loading ? 'Carregando...' : 'Filtrar' }}
            </button>
            <button
              @click="clearFilters"
              class="btn btn-secondary"
            >
              Limpar
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="card">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">ID</th>
                  <th class="table-header-cell">Solicitante</th>
                  <th class="table-header-cell">Destino</th>
                  <th class="table-header-cell">Data Ida</th>
                  <th class="table-header-cell">Data Volta</th>
                  <th class="table-header-cell">Status</th>
                  <th class="table-header-cell">Ações</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="pedido in pedidos" :key="pedido.id">
                  <td class="table-cell">#{{ pedido.id }}</td>
                  <td class="table-cell">{{ pedido.nome_solicitante }}</td>
                  <td class="table-cell">{{ pedido.destino }}</td>
                  <td class="table-cell">{{ formatDate(pedido.data_ida) }}</td>
                  <td class="table-cell">{{ formatDate(pedido.data_volta) }}</td>
                  <td class="table-cell">
                    <span
                      :class="getStatusClass(pedido.status)"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ getStatusLabel(pedido.status) }}
                    </span>
                  </td>
                  <td class="table-cell">
                    <div class="flex space-x-2">
                      <router-link
                        :to="`/pedidos/${pedido.id}`"
                        class="text-primary-600 hover:text-primary-900"
                      >
                        Ver
                      </router-link>
                      
                      <div v-if="authStore.isAdmin && pedido.status === 'solicitado'" class="flex space-x-1">
                        <button
                          @click="updateStatus(pedido.id, 'aprovado')"
                          class="text-green-600 hover:text-green-900"
                          :disabled="loading"
                        >
                          Aprovar
                        </button>
                        <button
                          @click="updateStatus(pedido.id, 'cancelado')"
                          class="text-red-600 hover:text-red-900"
                          :disabled="loading"
                        >
                          Cancelar
                        </button>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="pagination" class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Mostrando {{ pagination.from }} a {{ pagination.to }} de {{ pagination.total }} resultados
            </div>
            <div class="flex space-x-2">
              <button
                v-if="pagination.prev_page_url"
                @click="changePage(pagination.current_page - 1)"
                class="btn btn-secondary"
                :disabled="loading"
              >
                Anterior
              </button>
              <button
                v-if="pagination.next_page_url"
                @click="changePage(pagination.current_page + 1)"
                class="btn btn-secondary"
                :disabled="loading"
              >
                Próxima
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useToast } from 'vue-toastification'
import api from '../services/api'
import { format } from 'date-fns'
import { ptBR } from 'date-fns/locale'

export default {
  name: 'Pedidos',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    const toast = useToast()
    
    const pedidos = ref([])
    const pagination = ref(null)
    const loading = ref(false)
    
    const filters = reactive({
      status: '',
      destino: '',
      data_inicio: '',
      data_fim: ''
    })

    const fetchPedidos = async (page = 1) => {
      loading.value = true
      try {
        const params = { page, ...filters }
        const response = await api.get('/pedidos', { params })
        
        pedidos.value = response.data.data.data
        pagination.value = response.data.data
      } catch (error) {
        toast.error('Erro ao carregar pedidos')
        console.error('Erro ao buscar pedidos:', error)
      } finally {
        loading.value = false
      }
    }

    const applyFilters = () => {
      fetchPedidos(1)
    }

    const clearFilters = () => {
      Object.keys(filters).forEach(key => {
        filters[key] = ''
      })
      fetchPedidos(1)
    }

    const changePage = (page) => {
      fetchPedidos(page)
    }

    const updateStatus = async (pedidoId, status) => {
      try {
        await api.patch(`/pedidos/${pedidoId}/status`, { status })
        toast.success(`Pedido ${status === 'aprovado' ? 'aprovado' : 'cancelado'} com sucesso!`)
        fetchPedidos(pagination.value?.current_page || 1)
      } catch (error) {
        const message = error.response?.data?.message || 'Erro ao atualizar status'
        toast.error(message)
      }
    }

    const formatDate = (date) => {
      return format(new Date(date), 'dd/MM/yyyy', { locale: ptBR })
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

    const handleLogout = async () => {
      await authStore.logout()
      router.push('/login')
    }

    onMounted(() => {
      fetchPedidos()
    })

    return {
      pedidos,
      pagination,
      loading,
      filters,
      authStore,
      applyFilters,
      clearFilters,
      changePage,
      updateStatus,
      formatDate,
      getStatusLabel,
      getStatusClass,
      handleLogout
    }
  }
}
</script>
