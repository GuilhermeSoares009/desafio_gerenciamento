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
          <form @submit.prevent="submit">
            <div class="grid gap-4 mb-4 sm:grid-cols-2">

              <div class="sm:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Nome Completo *
                </label>
                <input
                  v-model="form.nome"
                  type="text"
                  required
                  class="input"
                  placeholder="João Silva ou Empresa LTDA"
                />
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  CPF/CNPJ *
                </label>
                <input
                  v-model="form.cpf"
                  type="text"
                  required
                  class="input"
                  placeholder="000.000.000-00"
                />
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Tipo *
                </label>
                <select 
                  v-model="form.tipo" 
                  required
                  class="input"
                >
                  <option value="fisica">Pessoa Física</option>
                  <option value="juridica">Pessoa Jurídica</option>
                </select>
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Telefone *
                </label>
                <input
                  v-model="form.telefone"
                  type="text"
                  required
                  class="input"
                  placeholder="(11) 98765-4321"
                />
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Email *
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="input"
                  placeholder="email@exemplo.com"
                />
              </div>

            </div>

            <div class="flex items-center justify-between">
              <div class="flex gap-3">
                <button
                  type="submit"
                  class="text-white bg-blue-700 hover:bg-blue-800
                         px-5 py-2.5 rounded-lg text-sm font-medium"
                >
                  {{ isEdit ? 'Atualizar' : 'Cadastrar' }}
                </button>

                <button
                  type="button"
                  @click="closeModal"
                  class="text-gray-900 bg-white border border-gray-300
                         hover:bg-gray-100 px-5 py-2.5 rounded-lg text-sm
                         dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                         dark:hover:bg-gray-700"
                >
                  Cancelar
                </button>
              </div>

              <button
                v-if="isEdit"
                type="button"
                @click="deletar"
                class="text-red-600 border border-red-600
                       hover:bg-red-600 hover:text-white
                       px-5 py-2.5 rounded-lg text-sm font-medium"
              >
                Excluir
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue'

const props = defineProps({
  showButton: {
    type: Boolean,
    default: false
  },
  pessoa: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['save', 'delete', 'close'])

const isOpen = ref(false)
const isEdit = ref(false)

const form = ref({
  id: null,
  nome: '',
  cpf: '',
  tipo: 'fisica',
  telefone: '',
  email: ''
})

function openModal(pessoa = null) {
  if (pessoa) {

    isEdit.value = true
    form.value = { ...pessoa }
  } else {

    isEdit.value = false
    form.value = {
      id: null,
      nome: '',
      cpf: '',
      tipo: 'fisica',
      telefone: '',
      email: ''
    }
  }
  isOpen.value = true
}

function closeModal() {
  isOpen.value = false
  emit('close')
}

function submit() {
  if (isEdit.value) {
    emit('save', { ...form.value }) 
  } else {
    emit('save', { ...form.value, id: Date.now() }) 
  }
  closeModal()
}

function deletar() {
  if (confirm('Tem certeza que deseja excluir esta pessoa?')) {
    emit('delete', form.value.id)
    closeModal()
  }
}

defineExpose({ openModal })
</script>

<style scoped>
.input {
  @apply bg-gray-50 border border-gray-300 text-sm rounded-lg
         block w-full p-2.5 text-gray-900
         dark:bg-gray-700 dark:border-gray-600 dark:text-white
         focus:ring-blue-500 focus:border-blue-500;
}
</style>