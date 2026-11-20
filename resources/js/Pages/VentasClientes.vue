<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  Search,
  Plus,
  Eye,
  Pencil,
  Trash2,
  ChevronLeft,
  ChevronRight,
  X,
  MapPin
} from 'lucide-vue-next'

const showModal = ref(false)
const modalTab = ref('principales') // 'principales' | 'direcciones'

// Sample data
const clientes = ref([
  {
    id: 1,
    nombre: 'ABARROTES EL MOSHAQUITO S.A.C.',
    codigo: 'P000003',
    tipoDoc: 'RUC',
    documento: '01046282837',
    telefono: '000000000',
    direccion: 'JR. JIRON 123'
  }
])

// Form data
const form = ref({
  tipoDoc: 'DNI - DOC. NACIONAL DE IDENTIDAD',
  numeroDoc: '',
  razonSocial: '',
  fechaNacimiento: '',
  nombreComercial: '',
  telefono: '',
  direccion: '',
  email: '',
  ubigeo: '',
  direccionesSecundarias: [
    { direccion: '', telefono: '' }
  ]
})

const addDireccion = () => {
  form.value.direccionesSecundarias.push({ direccion: '', telefono: '' })
}

const removeDireccion = (index) => {
  form.value.direccionesSecundarias.splice(index, 1)
}

const resetForm = () => {
  form.value = {
    tipoDoc: 'DNI - DOC. NACIONAL DE IDENTIDAD',
    numeroDoc: '',
    razonSocial: '',
    fechaNacimiento: '',
    nombreComercial: '',
    telefono: '',
    direccion: '',
    email: '',
    ubigeo: '',
    direccionesSecundarias: [{ direccion: '', telefono: '' }]
  }
  modalTab.value = 'principales'
}

const openNewModal = () => {
  resetForm()
  showModal.value = true
}
</script>

<template>
  <AdminLayout active-nav="ventas" active-submenu="clientes">
    <div class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <!-- Title -->
      <h1 class="text-xl font-bold text-gray-800 mb-6">Clientes</h1>

      <!-- Main Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Top Toolbar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
          <div class="relative w-80">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input
              type="text"
              placeholder="Buscar..."
              class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-xs text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500"
            />
          </div>

          <button
            @click="openNewModal"
            class="px-5 py-2 bg-[#1E2540] hover:bg-[#151a2e] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-sm"
          >
            <Plus class="w-4 h-4" />
            <span>NUEVO</span>
          </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="w-full text-xs text-left">
            <thead class="bg-[#2E3346] text-white">
              <tr>
                <th class="py-3 px-4 font-semibold text-center">Clientes</th>
                <th class="py-3 px-4 font-semibold text-center">Cod. Cliente</th>
                <th class="py-3 px-4 font-semibold text-center">Tipo Doc.</th>
                <th class="py-3 px-4 font-semibold text-center">Documento</th>
                <th class="py-3 px-4 font-semibold text-center">Teléfono</th>
                <th class="py-3 px-4 font-semibold text-center">Dirección</th>
                <th class="py-3 px-4 font-semibold text-center">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
              <tr v-for="item in clientes" :key="item.id" class="hover:bg-gray-50/80 transition-colors">
                <td class="py-3.5 px-4 text-gray-800 font-medium uppercase">{{ item.nombre }}</td>
                <td class="py-3.5 px-4 text-gray-600 text-center uppercase">{{ item.codigo }}</td>
                <td class="py-3.5 px-4 text-gray-600 text-center uppercase">{{ item.tipoDoc }}</td>
                <td class="py-3.5 px-4 text-gray-600 text-center">{{ item.documento }}</td>
                <td class="py-3.5 px-4 text-gray-600 text-center">{{ item.telefono }}</td>
                <td class="py-3.5 px-4 text-gray-600 uppercase text-center">{{ item.direccion }}</td>
                <td class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <button class="w-7 h-7 rounded bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors">
                      <Eye class="w-3.5 h-3.5" />
                    </button>
                    <button class="w-7 h-7 rounded bg-green-50 hover:bg-green-100 text-green-600 flex items-center justify-center transition-colors">
                      <Pencil class="w-3.5 h-3.5" />
                    </button>
                    <button class="w-7 h-7 rounded bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors">
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Skeleton Rows (as shown in design) -->
              <tr class="hover:bg-gray-50/80">
                <td class="py-3.5 px-4"><div class="h-3.5 bg-gray-200 rounded-full w-48"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-12 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-20 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-24 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-16 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-32 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center gap-1.5 opacity-60">
                    <div class="w-7 h-7 rounded bg-blue-50 text-blue-400 flex items-center justify-center"><Eye class="w-3.5 h-3.5" /></div>
                    <div class="w-7 h-7 rounded bg-green-50 text-green-400 flex items-center justify-center"><Pencil class="w-3.5 h-3.5" /></div>
                    <div class="w-7 h-7 rounded bg-red-50 text-red-400 flex items-center justify-center"><Trash2 class="w-3.5 h-3.5" /></div>
                  </div>
                </td>
              </tr>
              <tr class="hover:bg-gray-50/80">
                <td class="py-3.5 px-4"><div class="h-3.5 bg-gray-200 rounded-full w-44"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-10 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-20 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-24 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-14 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-28 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center gap-1.5 opacity-60">
                    <div class="w-7 h-7 rounded bg-blue-50 text-blue-400 flex items-center justify-center"><Eye class="w-3.5 h-3.5" /></div>
                    <div class="w-7 h-7 rounded bg-green-50 text-green-400 flex items-center justify-center"><Pencil class="w-3.5 h-3.5" /></div>
                    <div class="w-7 h-7 rounded bg-red-50 text-red-400 flex items-center justify-center"><Trash2 class="w-3.5 h-3.5" /></div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-5 text-xs text-gray-500">
          <div class="flex items-center gap-2">
            <select class="border border-gray-200 rounded px-2 py-1 text-xs text-gray-600 bg-white focus:outline-none">
              <option>10</option>
              <option>25</option>
              <option>50</option>
            </select>
            <button class="p-1 border border-gray-200 rounded hover:bg-gray-50 disabled:opacity-50">
              <ChevronLeft class="w-3.5 h-3.5" />
            </button>
            <span class="px-2">Página 1 de 1</span>
            <button class="p-1 border border-gray-200 rounded hover:bg-gray-50">
              <ChevronRight class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>

      <!-- MODAL NUEVO CLIENTE (Tabs: Datos Principales / Otras Direcciones) -->
      <div
        v-if="showModal"
        @click.self="showModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
      >
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
          <!-- Header -->
          <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-800">Nuevo Cliente</h2>
            <button @click="showModal = false" class="text-gray-400 hover:text-gray-700 transition-colors">
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Tabs Header -->
          <div class="px-6 pt-2 flex items-center gap-6 border-b border-gray-200">
            <button
              @click="modalTab = 'principales'"
              class="pb-2.5 text-xs font-semibold border-b-2 transition-colors"
              :class="modalTab === 'principales' ? 'border-[#F0302E] text-[#F0302E]' : 'border-transparent text-gray-500 hover:text-gray-700'"
            >
              Datos Principales
            </button>
            <button
              @click="modalTab = 'direcciones'"
              class="pb-2.5 text-xs font-semibold border-b-2 transition-colors"
              :class="modalTab === 'direcciones' ? 'border-[#F0302E] text-[#F0302E]' : 'border-transparent text-gray-500 hover:text-gray-700'"
            >
              Otras Direcciones
            </button>
          </div>

          <!-- TAB 1: DATOS PRINCIPALES -->
          <div v-if="modalTab === 'principales'" class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Left Col -->
              <div class="space-y-3">
                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">N° de Documento</label>
                  <div class="flex gap-2">
                    <input
                      v-model="form.numeroDoc"
                      type="text"
                      placeholder="Escribe aquí"
                      class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                    />
                    <button class="px-3 py-2 border border-blue-600 rounded-lg text-xs font-bold text-blue-700 hover:bg-blue-50 flex items-center gap-1 shrink-0">
                      <Search class="w-3.5 h-3.5 text-blue-700" />
                      <span>RENIEC</span>
                    </button>
                  </div>
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">Razón Social o Nombre Completo</label>
                  <input
                    v-model="form.razonSocial"
                    type="text"
                    placeholder="-"
                    disabled
                    class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-xs text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">Nombre Comercial (Marca)</label>
                  <input
                    v-model="form.nombreComercial"
                    type="text"
                    placeholder="Escribe aquí"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">Dirección</label>
                  <input
                    v-model="form.direccion"
                    type="text"
                    placeholder="-"
                    disabled
                    class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-xs text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">Ubigeo</label>
                  <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
                    <input
                      v-model="form.ubigeo"
                      type="text"
                      placeholder="-"
                      disabled
                      class="flex-1 px-3 py-2 bg-transparent text-xs text-gray-500 border-none focus:outline-none"
                    />
                    <div class="px-2.5 py-2 bg-[#2E3346] text-white flex items-center justify-center">
                      <MapPin class="w-4 h-4" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Col -->
              <div class="space-y-3">
                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">Tipo</label>
                  <select v-model="form.tipoDoc" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-700">
                    <option>DNI - DOC. NACIONAL DE IDENTIDAD</option>
                    <option>RUC - REGISTRO ÚNICO DE CONTRIBUYENTES</option>
                    <option>PASAPORTE</option>
                    <option>CARNET DE EXTRANJERÍA</option>
                  </select>
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">Fecha Nacimiento</label>
                  <input
                    v-model="form.fechaNacimiento"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 bg-white"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">Teléfono</label>
                  <input
                    v-model="form.telefono"
                    type="text"
                    placeholder="Escribe aquí"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-gray-700 mb-1">E-mail</label>
                  <input
                    v-model="form.email"
                    type="email"
                    placeholder="Escribe aquí"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                  />
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
              <button
                @click="showModal = false"
                class="px-5 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5"
              >
                <ChevronLeft class="w-4 h-4" />
                <span>ATRÁS</span>
              </button>
              <button
                @click="showModal = false"
                class="px-6 py-2 bg-[#F0302E] hover:bg-[#d92220] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span>GUARDAR</span>
              </button>
            </div>
          </div>

          <!-- TAB 2: OTRAS DIRECCIONES -->
          <div v-else-if="modalTab === 'direcciones'" class="p-6 space-y-4">
            <div class="flex justify-end">
              <button
                @click="addDireccion"
                class="px-4 py-1.5 bg-[#28A745] hover:bg-[#218838] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-sm"
              >
                <Plus class="w-3.5 h-3.5" />
                <span>AÑADIR</span>
              </button>
            </div>

            <!-- Addresses Table -->
            <div class="border border-gray-200 rounded-xl overflow-hidden min-h-[180px]">
              <table class="w-full text-xs text-left">
                <thead class="bg-[#2E3346] text-white">
                  <tr>
                    <th class="py-2.5 px-4 font-semibold text-center w-2/3">Dirección</th>
                    <th class="py-2.5 px-4 font-semibold text-center w-1/3">Teléfono</th>
                    <th class="py-2.5 px-2 text-center w-12"></th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                  <tr v-for="(dir, idx) in form.direccionesSecundarias" :key="idx" class="p-2">
                    <td class="p-2.5">
                      <input
                        v-model="dir.direccion"
                        type="text"
                        placeholder="Escribe aquí..."
                        class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                      />
                    </td>
                    <td class="p-2.5">
                      <input
                        v-model="dir.telefono"
                        type="text"
                        placeholder="Escribe aquí..."
                        class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                      />
                    </td>
                    <td class="p-2.5 text-center">
                      <button
                        @click="removeDireccion(idx)"
                        class="w-7 h-7 rounded bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors mx-auto"
                      >
                        <Trash2 class="w-3.5 h-3.5" />
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
              <button
                @click="showModal = false"
                class="px-5 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5"
              >
                <ChevronLeft class="w-4 h-4" />
                <span>ATRÁS</span>
              </button>
              <button
                @click="showModal = false"
                class="px-6 py-2 bg-[#F0302E] hover:bg-[#d92220] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span>GUARDAR</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
