import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import App from './App.vue'
import router from './router'

console.log('🚀 Aplicação Vue iniciando...')

const app = createApp(App)

// Global error handler
app.config.errorHandler = (err, vm, info) => {
  console.error('Erro global:', err)
  console.error('Info:', info)
}

app.use(createPinia())
app.use(router)

app.mount('#app')
console.log('✅ Aplicação Vue montada!')

