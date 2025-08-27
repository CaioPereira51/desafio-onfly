import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'),
    meta: { requiresGuest: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/Dashboard.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/pedidos',
    name: 'Pedidos',
    component: () => import('../views/Pedidos.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/pedidos/novo',
    name: 'NovoPedido',
    component: () => import('../views/NovoPedido.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/pedidos/:id',
    name: 'DetalhesPedido',
    component: () => import('../views/DetalhesPedido.vue'),
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const isAuthenticated = authStore.isAuthenticated

  console.log('Router guard:', {
    to: to.path,
    isAuthenticated,
    token: authStore.token,
    requiresAuth: to.meta.requiresAuth,
    requiresGuest: to.meta.requiresGuest
  })

  if (to.meta.requiresAuth && !isAuthenticated) {
    console.log('Redirecionando para login - não autenticado')
    next('/login')
  } else if (to.meta.requiresGuest && isAuthenticated) {
    console.log('Redirecionando para dashboard - já autenticado')
    next('/dashboard')
  } else {
    console.log('Navegação permitida')
    next()
  }
})

export default router
