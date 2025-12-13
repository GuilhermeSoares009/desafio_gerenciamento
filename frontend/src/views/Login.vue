<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div
      class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
    >

      <div
        class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
      >
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1
            class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
          >
            Entre na sua conta
          </h1>

          <form
            class="space-y-4 md:space-y-6"
            @submit.prevent="handleLogin"
          >
            <div>
              <label
                for="email"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                E-mail
              </label>
              <input
                id="email"
                type="email"
                v-model="form.email"
                placeholder="name@company.com"
                required
                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                       focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                       dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              />
            </div>

            <div>
              <label
                for="password"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Senha
              </label>
              <input
                id="password"
                type="password"
                v-model="form.password"
                placeholder="••••••••"
                required
                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                       focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                       dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              />
            </div>

            <button
              type="submit"
              class="w-full text-white bg-primary-600 hover:bg-primary-700
                     focus:ring-4 focus:outline-none focus:ring-primary-300
                     font-medium rounded-lg text-sm px-5 py-2.5 text-center
                     dark:bg-primary-600 dark:hover:bg-primary-700
                     dark:focus:ring-primary-800"
            >
              Entrar
            </button>

          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const CREDENCIAIS_MOCK = {
  email: 'admin@sistema.com',
  password: '123456'
}

function handleLogin() {

  if (form.value.email === CREDENCIAIS_MOCK.email && 
      form.value.password === CREDENCIAIS_MOCK.password) {
    
    localStorage.setItem('token', 'mock-token-123')
    localStorage.setItem('user', JSON.stringify({
      email: form.value.email,
      nome: 'Administrador'
    }))

    router.push('/pessoas')
    
  } else {
    alert('❌ Email ou senha incorretos!\n\nUse:\nEmail: admin@sistema.com\nSenha: 123456')
  }
}
</script>
