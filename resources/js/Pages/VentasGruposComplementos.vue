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
  X
} from 'lucide-vue-next'

const currentView = ref('list') // 'list' | 'create'

// List data
const grupos = ref([
  {
    id: 1,
    nombre: 'ABARROTES EL MOSHAQUITO S.A.C.',
    multiple: 'P002003',
    obligatorio: 'RUC',
    maximo: '01046282837'
  }
])

// Form for new grupo complementos
const listaInsumos = ref([
  { id: 1, nombre: 'CHICHARRON DE CHANCHO', cantidad: '1.00', unidad: 'GR' },
  { id: 2, nombre: '1/8 POLLO A LA BRASA', cantidad: '2.00', unidad: 'KG' },
  { id: 3, nombre: '1/4 POLLO A LA BRASA', cantidad: '12.00', unidad: 'GR' },
  { id: 4, nombre: 'MILANESA', cantidad: '43.00', unidad: 'LT' },
  { id: 5, nombre: 'CEVICHE', cantidad: '24.00', unidad: 'ML' },
])

const complementos = ref([
  {
    nombre: '',
    tipoSeleccion: 'unico', // 'unico' | 'multiple'
    minimo: '0',
    maximo: '0',
    opciones: [
      { nombre: '', precioAdicional: '0' }
    ]
  }
])

const addComplemento = () => {
  complementos.value.push({
    nombre: '',
    tipoSeleccion: 'unico',
    minimo: '0',
    maximo: '0',
    opciones: [{ nombre: '', precioAdicional: '0' }]
  })
}

const addOpcion = (compIdx) => {
  complementos.value[compIdx].opciones.push({ nombre: '', precioAdicional: '0' })
}

const removeOpcion = (compIdx, optIdx) => {
  complementos.value[compIdx].opciones.splice(optIdx, 1)
}

const removeComplemento = (compIdx) => {
  complementos.value.splice(compIdx, 1)
}
</script>

<template>
  <AdminLayout active-nav="ventas" active-submenu="grupos_complementos">
    <!-- VIEW 1: LIST -->
    <div v-if="currentView === 'list'" class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <!-- Title -->
      <h1 class="text-xl font-bold text-gray-800 mb-6">Grupos Complementos</h1>

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
            @click="currentView = 'create'"
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
                <th class="py-3 px-4 font-semibold text-center w-1/3">Nombre</th>
                <th class="py-3 px-4 font-semibold text-center">Múltiple</th>
                <th class="py-3 px-4 font-semibold text-center">Obligatorio</th>
                <th class="py-3 px-4 font-semibold text-center">Máximo</th>
                <th class="py-3 px-4 font-semibold text-center">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
              <tr v-for="g in grupos" :key="g.id" class="hover:bg-gray-50/80 transition-colors">
                <td class="py-3.5 px-4 text-gray-800 font-medium uppercase">{{ g.nombre }}</td>
                <td class="py-3.5 px-4 text-gray-600 text-center uppercase">{{ g.multiple }}</td>
                <td class="py-3.5 px-4 text-gray-600 text-center uppercase">{{ g.obligatorio }}</td>
                <td class="py-3.5 px-4 text-gray-600 text-center">{{ g.maximo }}</td>
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

              <!-- Skeletons -->
              <tr class="hover:bg-gray-50/80">
                <td class="py-3.5 px-4"><div class="h-3.5 bg-gray-200 rounded-full w-48"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-12 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-24 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-24 mx-auto"></div></td>
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
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-24 mx-auto"></div></td>
                <td class="py-3.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-24 mx-auto"></div></td>
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
            <select class="border border-gray-200 rounded px-2 py-1 text-xs text-gray-600 bg-white">
              <option>10</option>
              <option>25</option>
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
    </div>

    <!-- VIEW 2: FORM CREATE (BASE) -->
    <div v-else-if="currentView === 'create'" class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">Grupos Complementos</h1>
        <button
          @click="currentView = 'list'"
          class="px-4 py-1.5 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 flex items-center gap-1.5"
        >
          <ChevronLeft class="w-4 h-4" />
          <span>Volver al Listado</span>
        </button>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
        <!-- Sub Header Tabs (Información Básica / Lista de Insumos) -->
        <div class="flex items-center border-b border-gray-200">
          <button class="px-6 py-2.5 text-xs font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent">
            Información Básica
          </button>
          <button class="px-6 py-2.5 text-xs font-semibold text-[#F0302E] border-b-2 border-[#F0302E]">
            Lista de Insumos
          </button>
        </div>

        <!-- Section 1: Insumos / Productos -->
        <div class="border border-gray-200 rounded-xl p-5 space-y-4">
          <h3 class="text-xs font-bold text-gray-700">Lista</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
              <label class="block text-[11px] font-medium text-gray-600 mb-1">Insumos / Productos</label>
              <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                <option value="">Selecciona...</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-gray-600 mb-1">Cantidad</label>
              <input type="text" placeholder="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs" />
            </div>
            <div class="flex items-center gap-2">
              <div class="flex-1">
                <label class="block text-[11px] font-medium text-gray-600 mb-1">Unidad</label>
                <input type="text" placeholder="-" disabled class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-xs text-gray-500" />
              </div>
              <button class="px-4 py-2 bg-[#28A745] hover:bg-[#218838] text-white rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors mt-5">
                <Plus class="w-3.5 h-3.5" />
                <span>AGREGAR</span>
              </button>
            </div>
          </div>

          <!-- Insumos Table -->
          <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4">
            <table class="w-full text-xs text-left">
              <thead class="bg-gray-100 text-gray-700 font-bold border-b">
                <tr>
                  <th class="py-2.5 px-4 w-1/2">Nombre</th>
                  <th class="py-2.5 px-4 text-center">Cantidad</th>
                  <th class="py-2.5 px-4 text-center">Unidad</th>
                  <th class="py-2.5 px-4 text-center">Acciones</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 bg-white">
                <tr v-for="insumo in listaInsumos" :key="insumo.id" class="hover:bg-gray-50/80">
                  <td class="py-2.5 px-4 font-medium text-gray-800">{{ insumo.nombre }}</td>
                  <td class="py-2.5 px-4 text-center text-gray-700">{{ insumo.cantidad }}</td>
                  <td class="py-2.5 px-4 text-center text-gray-600">{{ insumo.unidad }}</td>
                  <td class="py-2.5 px-4 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                      <button class="w-6 h-6 rounded bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100">
                        <Pencil class="w-3 h-3" />
                      </button>
                      <button class="w-6 h-6 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100">
                        <Trash2 class="w-3 h-3" />
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Section 2: Complementos -->
        <div class="border border-gray-200 rounded-xl p-5 space-y-4">
          <h3 class="text-xs font-bold text-gray-700">Complementos</h3>

          <div v-for="(comp, compIndex) in complementos" :key="compIndex" class="space-y-4 pb-4 border-b border-gray-100 last:border-b-0">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
              <div class="md:col-span-2">
                <label class="block text-[11px] font-medium text-gray-600 mb-1">Nombre</label>
                <input v-model="comp.nombre" type="text" placeholder="Escribe aquí..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs" />
              </div>
              <div>
                <label class="block text-[11px] font-medium text-gray-600 mb-1">Tipo Selección Opciones</label>
                <div class="flex items-center gap-4 mt-2">
                  <label class="inline-flex items-center text-xs text-gray-700 cursor-pointer">
                    <input type="radio" :name="'tipoSel-' + compIndex" value="unico" v-model="comp.tipoSeleccion" class="text-red-600 focus:ring-red-500" />
                    <span class="ml-1.5">Único</span>
                  </label>
                  <label class="inline-flex items-center text-xs text-gray-700 cursor-pointer">
                    <input type="radio" :name="'tipoSel-' + compIndex" value="multiple" v-model="comp.tipoSeleccion" class="text-red-600 focus:ring-red-500" />
                    <span class="ml-1.5">Múltiple</span>
                  </label>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Mínimo</label>
                  <input v-model="comp.minimo" type="text" placeholder="0" class="w-16 px-2 py-1.5 border border-gray-200 rounded-lg text-xs" />
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Máximo</label>
                  <input v-model="comp.maximo" type="text" placeholder="0" class="w-16 px-2 py-1.5 border border-gray-200 rounded-lg text-xs" />
                </div>
                <button @click="removeComplemento(compIndex)" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 mt-5">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Sub Options -->
            <div class="pl-4 border-l-2 border-gray-200 space-y-2">
              <div v-for="(opt, optIndex) in comp.opciones" :key="optIndex" class="flex items-center gap-3">
                <div class="flex-1">
                  <label v-if="optIndex === 0" class="block text-[11px] font-medium text-gray-600 mb-1">Opción de Nombre</label>
                  <input v-model="opt.nombre" type="text" placeholder="Escribe aquí..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs" />
                </div>
                <div class="w-32">
                  <label v-if="optIndex === 0" class="block text-[11px] font-medium text-gray-600 mb-1">Precio Adicional</label>
                  <input v-model="opt.precioAdicional" type="text" placeholder="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs" />
                </div>
                <button @click="removeOpcion(compIndex, optIndex)" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 mt-5">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>

              <button
                @click="addOpcion(compIndex)"
                class="px-3 py-1.5 border border-green-600 text-green-700 hover:bg-green-50 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors mt-2"
              >
                <Plus class="w-3.5 h-3.5" />
                <span>AGREGAR OPCIÓN</span>
              </button>
            </div>
          </div>

          <button
            @click="addComplemento"
            class="px-4 py-2 bg-[#28A745] hover:bg-[#218838] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors mt-2"
          >
            <Plus class="w-3.5 h-3.5" />
            <span>AGREGAR COMPLEMENTO</span>
          </button>
        </div>

        <!-- Footer Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
          <button
            @click="currentView = 'list'"
            class="px-5 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 transition-colors"
          >
            <ChevronLeft class="w-4 h-4" />
            <span>ATRÁS</span>
          </button>
          <button
            class="px-6 py-2 bg-[#F0302E] hover:bg-[#d92220] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-sm"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            <span>GUARDAR</span>
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
