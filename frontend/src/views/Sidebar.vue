<template>
  <div>
    <!-- Botão mobile (hamburguer) -->
    <button
      @click="toggleSidebar"
      type="button"
      class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden 
             hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 
             dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
    >
      <span class="sr-only">Abrir menu</span>
      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path clip-rule="evenodd" fill-rule="evenodd" 
              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
        </path>
      </svg>
    </button>

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 z-40 w-64 h-screen transition-transform',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full sm:translate-x-0'
      ]"
      aria-label="Sidebar"
    >
      <div class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 
                  dark:bg-gray-800 dark:border-gray-700">
        
        <!-- Logo/Título -->
        <div class="mb-5 pb-5 border-b border-gray-200 dark:border-gray-700">
          <h1 class="text-xl font-bold text-gray-900 dark:text-white">
            Sistema de Cadastro
          </h1>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ usuario?.nome || 'Administrador' }}
          </p>
        </div>

        <!-- Menu Principal -->
        <ul class="space-y-2">
          
          <li>
            <router-link
              to="/pessoas"
              class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg 
                     dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
              active-class="bg-gray-100 dark:bg-gray-700"
            >
              <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900 
                          dark:text-gray-400 dark:group-hover:text-white" 
                   fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
              </svg>
              <span class="ml-3">Dashboard</span>
            </router-link>
          </li>

        </ul>


        <!-- Botão Logout (fixo no final) -->
        <div class="absolute bottom-0 left-0 w-full p-4 bg-white dark:bg-gray-800 
                    border-t border-gray-200 dark:border-gray-700">
          <button
            @click="logout"
            class="flex items-center w-full p-2 text-base font-normal text-red-600 rounded-lg 
                   hover:bg-red-50 dark:hover:bg-gray-700 dark:text-red-400 group"
          >
            <svg class="flex-shrink-0 w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" 
                    d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" 
                    clip-rule="evenodd">
              </path>
            </svg>
            <span class="ml-3">Sair</span>
          </button>
        </div>

      </div>
    </aside>

    <!-- Overlay mobile -->
    <div
      v-if="isSidebarOpen"
      @click="toggleSidebar"
      class="fixed inset-0 z-30 bg-black bg-opacity-50 sm:hidden"
    ></div>

    <main class="flex-1 ml-0 sm:ml-64 p-4">
      <router-view />
    </main>

  </div>
</template>

<script setup>
import { ref, onMounted, defineEmits } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const emit = defineEmits(['open-modal-pessoa'])

const isSidebarOpen = ref(false)
const usuario = ref(null)

const openMenus = ref({
  pessoas: false,
  configuracoes: false
})

onMounted(() => {
  // Carrega dados do usuário do localStorage
  const userStr = localStorage.getItem('user')
  if (userStr) {
    usuario.value = JSON.parse(userStr)
  }
})

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value
}

function toggleMenu(menuName) {
  openMenus.value[menuName] = !openMenus.value[menuName]
}

function abrirModalNovaPessoa() {
  emit('open-modal-pessoa')
  if (window.innerWidth < 640) {
    isSidebarOpen.value = false
  }
}

function logout() {
  if (confirm('Tem certeza que deseja sair?')) {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push('/login')
  }
}
</script>