import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'
import PessoasIndex from '../views/pessoas/Index.vue'
import PessoasForm from '../views/pessoas/Form.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/',
    component: Dashboard,
    children: [
      {
        path: '',
        redirect: '/pessoas'
      },
      {
        path: 'pessoas',
        name: 'PessoasIndex',
        component: PessoasIndex
      },
      {
        path: 'pessoas/criar',
        name: 'PessoasCriar',
        component: PessoasForm
      },
      {
        path: 'pessoas/:id/editar',
        name: 'PessoasEditar',
        component: PessoasForm
      }
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