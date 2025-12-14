<template>
  <div>
    <!-- Modal -->
    <div
      v-if="isOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
      @click.self="emit('close')"
    >
      <div class="relative p-4 w-full max-w-2xl">
        <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
          <div class="flex justify-between items-center pb-4 mb-4
                      border-b dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ isEdit ? 'Editar Pessoa' : 'Nova Pessoa' }}
            </h3>
            <button
              @click="emit('close')"
              class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5 dark:hover:bg-gray-600"
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
                  @input="handleDocumentoInput"
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
                  @input="handleDocumentoInput"
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
                  @input="handleTelefoneInput"
                  type="text"
                  placeholder="(00) 00000-0000"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
              </div>
            </div>

            <div class="flex justify-end gap-2">
              <p v-if="error" class="text-red-500 text-xs italic mr-2">{{ error }}</p>
              <button
                type="button"
                @click="emit('close')"
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

const props = defineProps(['isOpen', 'pessoa'])
const emit = defineEmits(['save']) 

const isEdit = ref(false)

const form = ref({
  nome: '',
  email: '',
  telefone: '',
  tipo: 'fisica',
  documento: ''
})

const error = ref('')

watch(() => props.pessoa, (newPessoa) => {
  if (newPessoa) {
    form.value = { ...newPessoa }
    handleDocumentoInput() 
    handleTelefoneInput() 
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


const formatCPF = (value) => {
  return value
    .replace(/\D/g, '')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d{1,2})$/, '$1-$2')
    .slice(0, 14)
}

const formatCNPJ = (value) => {
  return value
    .replace(/\D/g, '')
    .replace(/(\d{2})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1/$2')
    .replace(/(\d{4})(\d{1,2})$/, '$1-$2')
    .slice(0, 18)
}

const formatTelefone = (value) => {
  return value
    .replace(/\D/g, '')
    .replace(/(\d{2})(\d)/, '($1) $2')
    .replace(/(\d{4,5})(\d{4})$/, '$1-$2')
    .slice(0, 15)
}

const handleDocumentoInput = () => {
  if (form.value.tipo === 'fisica') {
    form.value.documento = formatCPF(form.value.documento)
  } else {
    form.value.documento = formatCNPJ(form.value.documento)
  }
}

const handleTelefoneInput = () => {
  form.value.telefone = formatTelefone(form.value.telefone)
}

const submitForm = async () => {
  error.value = '' 

  if (!form.value.nome.trim()) {
    error.value = 'Nome é obrigatório'
    return
  }
  if (!form.value.documento.trim()) {
    error.value = 'Documento é obrigatório'
    return
  }
  if (form.value.email && !/\S+@\S+\.\S+/.test(form.value.email)) {
    error.value = 'Email inválido'
    return
  }

  if (form.value.tipo === 'fisica' && form.value.documento.replace(/\D/g, '').length !== 11) {
    error.value = 'CPF deve ter 11 dígitos'
    return
  }
  if (form.value.tipo === 'juridica' && form.value.documento.replace(/\D/g, '').length !== 14) {
    error.value = 'CNPJ deve ter 14 dígitos'
    return
  }
  if (form.value.telefone && form.value.telefone.replace(/\D/g, '').length < 10) {
    error.value = 'Telefone deve ter pelo menos 10 dígitos'
    return
  }

  try {
    const token = localStorage.getItem('token')
    const url = props.pessoa ? `/pessoas/${props.pessoa.id}` : '/pessoas'
    const method = props.pessoa ? 'put' : 'post'

    const payload = {
      nome: form.value.nome,
      email: form.value.email,
      telefone: form.value.telefone.replace(/\D/g, ''), 
      tipo: form.value.tipo,
      documento: form.value.documento.replace(/\D/g, '')
    }

    const response = await axios[method](url, payload, {
      headers: { Authorization: `Bearer ${token}` }
    })
    emit('save', response.data)
    emit('close')
  } catch (err) {
    console.error('Erro ao salvar pessoa:', err)
    if (err.response && err.response.status === 422) {
      error.value = Object.values(err.response.data.errors).flat().join(', ')
    } else {
      error.value = 'Erro inesperado. Tente novamente.'
    }
  }
}

</script>
