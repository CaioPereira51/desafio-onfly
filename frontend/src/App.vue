<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <router-view />
  </div>
</template>

<script>
import { onMounted } from 'vue'
import { useAuthStore } from './stores/auth'

export default {
  name: 'App',
  setup() {
    console.log('🔧 App.vue setup executando...')
    const authStore = useAuthStore()

    onMounted(async () => {
      console.log('📱 App.vue montado, inicializando auth...')
      await authStore.initializeAuth()
      console.log('✅ Auth inicializado')
    })
  }
}
</script>
