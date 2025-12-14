<template>
  <div>
    <div v-if="showButton" class="flex justify-center m-5">
      <button
        @click="openModal()"
        class="block text-white bg-blue-700 hover:bg-blue-800
               font-medium rounded-lg text-sm px-5 py-2.5"
        type="button"
      >
        Nova Pessoa
      </button>
    </div>

    <!-- Modal -->
    <div
      v-if="isOpen"
      class="fixed inset-0 z-50 flex items-center justify-center
             bg-black bg-opacity-50"
      @click.self="closeModal"
    >
      <div class="relative p-4 w-full max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
          <div class="flex justify-between items-center pb-4 mb-4
                      border-b dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ isEdit ? 'Editar Pessoa' : 'Nova Pessoa' }}
            </h3>
            <button
              @click="closeModal"
              class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5
                     dark:hover:bg-gray-600"
            >
              ✕
            </button>
          </div>

          <!-- Form -->
          <form @submit.prevent="submitForm">
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Nome Completo *
                </label>
                <input
                  v-model="form.nome"
                  type="text"
                  placeholder="João Silva ou Empresa LTDA"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                />
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Tipo *
                </label>
                <select
                  v-model="form.tipo"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                >
                  <option value="fisica">Pessoa Física</option>
                  <option value="juridica">Pessoa Jurídica</option>
                </select>
              </div>

              <div v-if="form.tipo === 'fisica'">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  CPF
                </label>
                <input
                  v-model="form.documento"
                  type="text"
                  placeholder="000.000.000-00"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                />
              </div>

              <div v-if="form.tipo === 'juridica'">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Documento (CNPJ) *
                </label>
                <input
                  v-model="form.documento"
                  type="text"
                  placeholder="00.000.000/0000-00"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                />
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Email
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="email@exemplo.com"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Telefone
                </label>
                <input
                  v-model="form.telefone"
                  type="text"
                  placeholder="(00) 00000-0000"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
              </div>
            </div>

            <div class="flex justify-end gap-2">
              <button
                type="button"
                @click="closeModal"
                class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700"
              >
                Cancelar
              </button>
              <button
                type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800"
              >
                {{ isEdit ? 'Atualizar' : 'Salvar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const emit = defineEmits(['save', 'delete'])

const isOpen = ref(false)
const isEdit = ref(false)
const pessoaEdit = ref(null)

const form = ref({
  nome: '',
  email: '',
  telefone: '',
  tipo: 'fisica',
  documento: ''  
})

watch(() => pessoaEdit.value, (newPessoa) => {
  if (newPessoa) {
    form.value = { ...newPessoa }
    isEdit.value = true
  } else {
    form.value = {
      nome: '',
      email: '',
      telefone: '',
      tipo: 'fisica',
      documento: ''  
    }
    isEdit.value = false
  }
}, { immediate: true })

const openModal = (pessoa = null) => {
  pessoaEdit.value = pessoa
  isOpen.value = true
}

const closeModal = () => {
  isOpen.value = false
  pessoaEdit.value = null
  emit('close')
}

const submitForm = async () => {
  try {
    const token = localStorage.getItem('token')
    const url = pessoaEdit.value ? `/pessoas/${pessoaEdit.value.id}` : '/pessoas'
    const method = pessoaEdit.value ? 'put' : 'post'

    const payload = {
      nome: form.value.nome,
      email: form.value.email,
      telefone: form.value.telefone,
      tipo: form.value.tipo,
      documento: form.value.documento  
    }

    const response = await axios[method](url, payload, {
      headers: { Authorization: `Bearer ${token}` }
    })
    emit('save', response.data)
    closeModal()
  } catch (err) {
    console.error('Erro ao salvar pessoa:', err)
    if (err.response && err.response.status === 422) {
      console.log('Erros de validação:', err.response.data.errors)
    }
  }
}

defineExpose({ openModal })
</script>
