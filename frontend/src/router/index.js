import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Sidebar from '../views/Sidebar.vue'
import PessoasLista from '../views/pessoas/List.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/',
    component: Sidebar, 
    children: [
      {
        path: '',
        redirect: '/pessoas'
      },
      {
        path: 'pessoas',
        name: 'PessoasLista',
        component: PessoasLista
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token') 
  
  if (to.path !== '/login' && !isAuthenticated) {
    next('/login')
  } else if (to.path === '/login' && isAuthenticated) {
    next('/pessoas')
  } else {
    next()
  }
})

export default router