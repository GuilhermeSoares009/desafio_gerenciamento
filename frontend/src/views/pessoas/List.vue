<template>
  <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
      <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

        <!-- Top bar -->
        <div class="flex flex-col md:flex-row items-center justify-between p-4 gap-4">
          <div class="w-full md:w-1/2">
            <input
              v-model="search"
              type="text"
              placeholder="Buscar por nome, CPF ou email..."
              class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full pl-3 p-2
                     dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-2">
            <button
              @click="abrirModalCriar"
              class="flex items-center text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg text-sm"
            >
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
              </svg>
              Nova Pessoa
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
              <tr>
                <th class="px-4 py-3">Nome</th>
                <th class="px-4 py-3">CPF/CNPJ</th>
                <th class="px-4 py-3">Tipo</th>
                <th class="px-4 py-3">Telefone</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3 text-right">Ações</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="pessoa in paginatedPessoas"
                :key="pessoa.id"
                class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
              >
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ pessoa.nome }}</td>
                <td class="px-4 py-3">{{ pessoa.cpf }}</td>
                <td class="px-4 py-3">
                  <span 
                    class="px-2 py-1 text-xs font-medium rounded"
                    :class="pessoa.tipo === 'fisica' 
                      ? 'bg-blue-100 text-blue-800' 
                      : 'bg-green-100 text-green-800'"
                  >
                    {{ pessoa.tipo === 'fisica' ? 'Física' : 'Jurídica' }}
                  </span>
                </td>
                <td class="px-4 py-3">{{ pessoa.telefone }}</td>
                <td class="px-4 py-3">{{ pessoa.email }}</td>
                <td class="px-4 py-3 text-right">
                  <button @click="abrirModalEditar(pessoa)" class="text-blue-600 hover:underline mr-3">✏️ Editar</button>
                  <button @click="deletar(pessoa.id)" class="text-red-600 hover:underline">🗑️ Excluir</button>
                </td>
              </tr>

              <tr v-if="paginatedPessoas.length === 0">
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                  Nenhuma pessoa encontrada
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
              Mostrando
              <span class="font-semibold text-gray-900 dark:text-white">{{ startItem }}-{{ endItem }}</span>
              de
              <span class="font-semibold text-gray-900 dark:text-white">{{ filteredPessoas.length }}</span>
            </span>

            <ul class="inline-flex items-stretch -space-x-px">
              <li>
                <button @click="prevPage" :disabled="currentPage === 1" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                  <span class="sr-only">Previous</span>
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </li>

              <li v-for="page in totalPages" :key="page">
                <button
                  @click="goToPage(page)"
                  :class="page === currentPage
                    ? 'flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                    : 'flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'"
                >
                  {{ page }}
                </button>
              </li>

              <li>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                  <span class="sr-only">Next</span>
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>

    <PessoasModal ref="modalRef" @save="salvar" @delete="deletar" />
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import PessoasModal from './Form.vue'

const modalRef = ref(null)
const search = ref('')

const pessoas = ref([
  {
    id: 1,
    nome: 'João Silva',
    cpf: '123.456.789-00',
    tipo: 'fisica',
    telefone: '(11) 98765-4321',
    email: 'joao.silva@email.com'
  },
  {
    id: 2,
    nome: 'Maria Santos',
    cpf: '987.654.321-00',
    tipo: 'fisica',
    telefone: '(11) 91234-5678',
    email: 'maria.santos@email.com'
  },
  {
    id: 3,
    nome: 'Tech Solutions LTDA',
    cpf: '12.345.678/0001-90',
    tipo: 'juridica',
    telefone: '(11) 3333-4444',
    email: 'contato@techsolutions.com.br'
  },
  {
    id: 4,
    nome: 'Carlos Oliveira',
    cpf: '456.789.123-00',
    tipo: 'fisica',
    telefone: '(21) 99999-8888',
    email: 'carlos.oliveira@email.com'
  },
  {
    id: 5,
    nome: 'Innovate Corp',
    cpf: '98.765.432/0001-10',
    tipo: 'juridica',
    telefone: '(11) 2222-3333',
    email: 'contato@innovate.com.br'
  },
  {
    id: 6,
    nome: 'Ana Pereira',
    cpf: '321.654.987-00',
    tipo: 'fisica',
    telefone: '(21) 98888-7777',
    email: 'ana.pereira@email.com'
  },
  {
    id: 7,
    nome: 'Global Tech LTDA',
    cpf: '23.456.789/0001-12',
    tipo: 'juridica',
    telefone: '(11) 4444-5555',
    email: 'contato@globaltech.com.br'
  },
  {
    id: 8,
    nome: 'Ricardo Almeida',
    cpf: '654.321.987-00',
    tipo: 'fisica',
    telefone: '(31) 97777-6666',
    email: 'ricardo.almeida@email.com'
  },
  {
    id: 9,
    nome: 'Creative Solutions LTDA',
    cpf: '34.567.890/0001-23',
    tipo: 'juridica',
    telefone: '(41) 3333-2222',
    email: 'contato@creative.com.br'
  },
  {
    id: 10,
    nome: 'Patrícia Lima',
    cpf: '789.123.456-00',
    tipo: 'fisica',
    telefone: '(51) 96666-5555',
    email: 'patricia.lima@email.com'
  }
])

const currentPage = ref(1)
const itemsPerPage = 5 

const filteredPessoas = computed(() => {
  if (!search.value) return pessoas.value
  const searchLower = search.value.toLowerCase()
  return pessoas.value.filter(p =>
    p.nome.toLowerCase().includes(searchLower) ||
    p.cpf.includes(searchLower) ||
    p.email.toLowerCase().includes(searchLower)
  )
})

const totalPages = computed(() => Math.ceil(filteredPessoas.value.length / itemsPerPage))

const paginatedPessoas = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredPessoas.value.slice(start, start + itemsPerPage)
})

const startItem = computed(() => ((currentPage.value - 1) * itemsPerPage) + 1)
const endItem = computed(() => Math.min(currentPage.value * itemsPerPage, filteredPessoas.value.length))

function goToPage(page) {
  currentPage.value = page
}

function prevPage() {
  if (currentPage.value > 1) currentPage.value--
}

function nextPage() {
  if (currentPage.value < totalPages.value) currentPage.value++
}

function abrirModalCriar() { modalRef.value.openModal() }
function abrirModalEditar(pessoa) { modalRef.value.openModal(pessoa) }
function salvar(pessoa) {
  const index = pessoas.value.findIndex(p => p.id === pessoa.id)
  if (index !== -1) pessoas.value[index] = pessoa
  else pessoas.value.push(pessoa)
}
function deletar(id) {
  if (confirm('Tem certeza que deseja excluir?')) {
    pessoas.value = pessoas.value.filter(p => p.id !== id)
  }
}
</script>
