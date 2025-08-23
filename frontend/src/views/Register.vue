<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sistema de Viagens
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Crie sua conta
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="space-y-4">
          <div>
            <label for="name" class="form-label">Nome</label>
            <input
              id="name"
              v-model="form.name"
              name="name"
              type="text"
              required
              class="form-input"
              placeholder="Seu nome completo"
              :class="{ 'border-red-500': errors.name }"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>
          
          <div>
            <label for="email" class="form-label">Email</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              required
              class="form-input"
              placeholder="seu@email.com"
              :class="{ 'border-red-500': errors.email }"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
          </div>
          
          <div>
            <label for="password" class="form-label">Senha</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              required
              class="form-input"
              placeholder="Mínimo 6 caracteres"
              :class="{ 'border-red-500': errors.password }"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
          </div>
          
          <div>
            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              name="password_confirmation"
              type="password"
              required
              class="form-input"
              placeholder="Confirme sua senha"
              :class="{ 'border-red-500': errors.password_confirmation }"
            />
            <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.password_confirmation }}</p>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="authStore.loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50"
          >
            <span v-if="authStore.loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ authStore.loading ? 'Cadastrando...' : 'Cadastrar' }}
          </button>
        </div>

        <div class="text-center">
          <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-500">
            Já tem uma conta? Faça login
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

export default {
  name: 'Register',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()

    const form = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    })

    const errors = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    })

    const validateForm = () => {
      errors.name = ''
      errors.email = ''
      errors.password = ''
      errors.password_confirmation = ''

      if (!form.name) {
        errors.name = 'Nome é obrigatório'
      } else if (form.name.length < 2) {
        errors.name = 'Nome deve ter pelo menos 2 caracteres'
      }

      if (!form.email) {
        errors.email = 'Email é obrigatório'
      } else if (!/\S+@\S+\.\S+/.test(form.email)) {
        errors.email = 'Email inválido'
      }

      if (!form.password) {
        errors.password = 'Senha é obrigatória'
      } else if (form.password.length < 6) {
        errors.password = 'Senha deve ter pelo menos 6 caracteres'
      }

      if (!form.password_confirmation) {
        errors.password_confirmation = 'Confirmação de senha é obrigatória'
      } else if (form.password !== form.password_confirmation) {
        errors.password_confirmation = 'Senhas não coincidem'
      }

      return !errors.name && !errors.email && !errors.password && !errors.password_confirmation
    }

    const handleRegister = async () => {
      if (!validateForm()) return

      const result = await authStore.register({
        name: form.name,
        email: form.email,
        password: form.password,
        password_confirmation: form.password_confirmation
      })

      if (result.success) {
        router.push('/dashboard')
      }
    }

    return {
      form,
      errors,
      authStore,
      handleRegister
    }
  }
}
</script>
