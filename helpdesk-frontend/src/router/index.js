import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import TicketListView from '../views/TicketListView.vue'
import TicketDetailView from '../views/TicketDetailView.vue'
import TicketCreateView from '../views/TicketCreateView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      meta: { guest: true }
    },
    {
      path: '/tickets',
      name: 'tickets',
      component: TicketListView,
      meta: { requiresAuth: true }
    },
    {
      path: '/tickets/create',
      name: 'ticket-create',
      component: TicketCreateView,
      meta: { requiresAuth: true }
    },
    {
      path: '/tickets/:id',
      name: 'ticket-detail',
      component: TicketDetailView,
      meta: { requiresAuth: true }
    },
  ]
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token')
  if (to.meta.requiresAuth && !token) {
    next({ name: 'login' })
  } else if (to.meta.guest && token) {
    next({ name: 'tickets' })
  } else {
    next()
  }
})

export default router