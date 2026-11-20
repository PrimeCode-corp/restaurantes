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
  RotateCcw,
  List,
  FolderPlus,
  AlertCircle
} from 'lucide-vue-next'

const currentView = ref('list') // 'list' | 'create' | 'report_list'
const activeTab = ref('basico') // 'basico' | 'insumos'

// Modals
const showInactivosModal = ref(false)
const showCategoriaModal = ref(false)

// Sample items
const productos = ref([
  { id: 1, nombre: 'CHICHARRON DE CHANCHO', categoria: 'CARNES', precio: '18.00', cocina: 'COCINA 1', tiempo: '5' },
  { id: 2, nombre: '1/8 POLLO A LA BRASA', categoria: 'POLLO A LA BRASA', precio: '8.00', cocina: 'COCINA 1', tiempo: '18' },
  { id: 3, nombre: '1/4 POLLO A LA BRASA', categoria: 'POLLO A LA BRASA', precio: '10.00', cocina: 'COCINA 1', tiempo: '15' },
  { id: 4, nombre: 'MILANESA', categoria: 'CARNES', precio: '12.00', cocina: 'COCINA 1', tiempo: '15' },
  { id: 5, nombre: 'CEVICHE', categoria: 'MARINO', precio: '14.00', cocina: 'COCINA 1', tiempo: '18' },
  { id: 6, nombre: 'CECINA FRITA', categoria: 'REGIONAL', precio: '10.00', cocina: 'COCINA 1', tiempo: '4' },
  { id: 7, nombre: 'PIZZA REGIONAL PERSONAL', categoria: 'PASTAS', precio: '30.00', cocina: 'COCINA 1', tiempo: '40' },
  { id: 8, nombre: '1/4 POLLO A LA PLANCHA', categoria: 'CARNES', precio: '10.00', cocina: 'COCINA 1', tiempo: '25' },
  { id: 9, nombre: '1/8 POLLO A LA PLANCHA', categoria: 'CARNES', precio: '8.00', cocina: 'COCINA 1', tiempo: '30' },
  { id: 10, nombre: 'CHAUFA', categoria: 'CHIFA', precio: '8.00', cocina: 'COCINA 1', tiempo: '6' },
])

// Form state for new product
const form = ref({
  nombre: '',
  codigoSunat: '',
  codigoCorto: '',
  categoria: '',
  subCategoria: '',
  tipoItem: '',
  itemDerivado: '',
  precioVenta: '',
  productoActivo: true,
  tipoDescuento: '',
  descuento: '0',
  stockMinimo: '',
  cocina: '',
  orden: '',
  tipoAtencion: '',
  imprimirOtrasCocinas: false,
  icbper: false,
})

// Tab 2: Insumos y Complementos
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

const addOpcion = (compIndex) => {
  complementos.value[compIndex].opciones.push({ nombre: '', precioAdicional: '0' })
}

const removeOpcion = (compIndex, optIndex) => {
  complementos.value[compIndex].opciones.splice(optIndex, 1)
}

const removeComplemento = (compIndex) => {
  complementos.value.splice(compIndex, 1)
}

// Inactivos
const inactivosList = ref([
  { id: 1, descripcion: 'CHAUFA CON FRESA', precioVenta: '8.00', categoria: 'CHAUFAS', tiempo: '0.25', cocina: 'COCINA 1' }
])
const selectedInactivo = ref(null)
</script>

<template>
  <AdminLayout active-nav="ventas" active-submenu="productos_venta">
    <!-- VIEW 1: PRODUCT LIST -->
    <div v-if="currentView === 'list'" class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <!-- Title -->
      <h1 class="text-xl font-bold text-gray-800 mb-6">Producto Venta</h1>

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

          <div class="flex items-center gap-2.5">
            <button
              @click="showInactivosModal = true"
              class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 transition-colors"
            >
              <AlertCircle class="w-4 h-4 text-gray-600" />
              <span>INACTIVOS</span>
            </button>

            <button
              @click="currentView = 'report_list'"
              class="px-4 py-2 border border-blue-600 rounded-lg text-xs font-semibold text-blue-700 hover:bg-blue-50 flex items-center gap-1.5 transition-colors"
            >
              <List class="w-4 h-4 text-blue-700" />
              <span>LISTA</span>
            </button>

            <button
              @click="showCategoriaModal = true"
              class="px-4 py-2 border border-blue-800 rounded-lg text-xs font-semibold text-blue-900 hover:bg-blue-50 flex items-center gap-1.5 transition-colors"
            >
              <Plus class="w-4 h-4 text-blue-900" />
              <span>CATEGORIA</span>
            </button>

            <button
              @click="currentView = 'create'"
              class="px-5 py-2 bg-[#1E2540] hover:bg-[#151a2e] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-sm"
            >
              <Plus class="w-4 h-4" />
              <span>NUEVO</span>
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="w-full text-xs text-left">
            <thead class="bg-[#2E3346] text-white">
              <tr>
                <th class="py-3 px-4 font-semibold">Nombre</th>
                <th class="py-3 px-4 font-semibold text-center">Categoría</th>
                <th class="py-3 px-4 font-semibold text-center">Precio</th>
                <th class="py-3 px-4 font-semibold text-center">Cocina</th>
                <th class="py-3 px-4 font-semibold text-center">Tiempo Preparación</th>
                <th class="py-3 px-4 font-semibold text-center">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
              <tr v-for="item in productos" :key="item.id" class="hover:bg-gray-50/80 transition-colors">
                <td class="py-3 px-4 text-gray-700 font-medium uppercase">{{ item.nombre }}</td>
                <td class="py-3 px-4 text-gray-600 text-center uppercase">{{ item.categoria }}</td>
                <td class="py-3 px-4 text-gray-700 text-center font-medium">{{ item.precio }}</td>
                <td class="py-3 px-4 text-gray-600 text-center uppercase">{{ item.cocina }}</td>
                <td class="py-3 px-4 text-gray-600 text-center">{{ item.tiempo }}</td>
                <td class="py-3 px-4 text-center">
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
    </div>

    <!-- VIEW 2: FORM NUEVO PRODUCTO VENTA -->
    <div v-else-if="currentView === 'create'" class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <h1 class="text-xl font-bold text-gray-800 mb-6">Nuevo Producto Venta</h1>

      <!-- Card Container -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Tabs Header -->
        <div class="flex items-center border-b border-gray-200 mb-6">
          <button
            @click="activeTab = 'basico'"
            class="px-6 py-2.5 text-xs font-semibold border-b-2 transition-colors"
            :class="activeTab === 'basico' ? 'border-[#F0302E] text-[#F0302E]' : 'border-transparent text-gray-500 hover:text-gray-700'"
          >
            Información Básica
          </button>
          <button
            @click="activeTab = 'insumos'"
            class="px-6 py-2.5 text-xs font-semibold border-b-2 transition-colors"
            :class="activeTab === 'insumos' ? 'border-[#F0302E] text-[#F0302E]' : 'border-transparent text-gray-500 hover:text-gray-700'"
          >
            Lista de Insumos
          </button>
        </div>

        <!-- TAB 1: INFORMACIÓN BÁSICA -->
        <div v-if="activeTab === 'basico'" class="space-y-6">
          <!-- Top section: Nombre, Códigos & Foto -->
          <div class="border border-gray-200 rounded-xl p-5 flex flex-col md:flex-row gap-6">
            <div class="flex-1 space-y-4">
              <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nombre Producto</label>
                <input
                  v-model="form.nombre"
                  type="text"
                  placeholder="Escribe aquí..."
                  class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500 focus:border-red-500"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-gray-700 mb-1">Código producto SUNAT</label>
                  <input
                    v-model="form.codigoSunat"
                    type="text"
                    placeholder="Escribe aquí"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500 focus:border-red-500"
                  />
                </div>
                <div>
                  <label class="block text-xs font-bold text-gray-700 mb-1">Código corto</label>
                  <input
                    v-model="form.codigoCorto"
                    type="text"
                    placeholder="Escribe aquí..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500 focus:border-red-500"
                  />
                </div>
              </div>
            </div>

            <!-- Upload Box -->
            <div class="w-full md:w-36 h-36 bg-gray-100 border border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center text-gray-500 cursor-pointer hover:bg-gray-50 transition-colors shrink-0">
              <svg class="w-6 h-6 mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg>
              <span class="text-xs font-medium">Subir Foto</span>
            </div>
          </div>

          <!-- Middle section: Categoría & Precios (2 Columns) -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Col 1: Categoría -->
            <div class="border border-gray-200 rounded-xl p-5 space-y-4">
              <h3 class="text-xs font-bold text-gray-700">Categoría</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Categoría</label>
                  <select v-model="form.categoria" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                    <option value="">Selecciona...</option>
                    <option>CARNES</option>
                    <option>POLLO A LA BRASA</option>
                  </select>
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Sub Categoría</label>
                  <select v-model="form.subCategoria" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                    <option value="">Selecciona...</option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Tipo Item</label>
                  <select v-model="form.tipoItem" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                    <option value="">Selecciona...</option>
                  </select>
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Item derivado</label>
                  <select v-model="form.itemDerivado" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                    <option value="">Selecciona...</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Col 2: Información Precios -->
            <div class="border border-gray-200 rounded-xl p-5 space-y-4">
              <h3 class="text-xs font-bold text-gray-700">Información Precios</h3>
              <div class="grid grid-cols-2 gap-4 items-center">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Precio Venta</label>
                  <input
                    v-model="form.precioVenta"
                    type="text"
                    placeholder="0"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                  />
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Producto Activo</label>
                  <!-- Toggle Switch -->
                  <button
                    type="button"
                    @click="form.productoActivo = !form.productoActivo"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                    :class="form.productoActivo ? 'bg-[#F0302E]' : 'bg-gray-200'"
                  >
                    <span
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                      :class="form.productoActivo ? 'translate-x-5' : 'translate-x-0'"
                    />
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Tipo Descuento</label>
                  <select v-model="form.tipoDescuento" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                    <option value="">Selecciona...</option>
                  </select>
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Descuento</label>
                  <input
                    v-model="form.descuento"
                    type="text"
                    placeholder="0"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-red-500"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom section: Información Extra -->
          <div class="border border-gray-200 rounded-xl p-5 space-y-4">
            <h3 class="text-xs font-bold text-gray-700">Información Extra</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Stock Mínimo</label>
                  <input
                    v-model="form.stockMinimo"
                    type="text"
                    placeholder="Escribe aquí..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs"
                  />
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Orden</label>
                  <input
                    v-model="form.orden"
                    type="text"
                    placeholder="Escribe aquí..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs"
                  />
                </div>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Cocina</label>
                  <select v-model="form.cocina" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                    <option value="">Selecciona...</option>
                    <option>COCINA 1</option>
                  </select>
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Tipo Atención</label>
                  <select v-model="form.tipoAtencion" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-600">
                    <option value="">Selecciona...</option>
                  </select>
                </div>
              </div>

              <div class="space-y-4 flex flex-col justify-around">
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">Imprimir en otras Cocinas</label>
                  <button
                    type="button"
                    @click="form.imprimirOtrasCocinas = !form.imprimirOtrasCocinas"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                    :class="form.imprimirOtrasCocinas ? 'bg-[#F0302E]' : 'bg-gray-200'"
                  >
                    <span
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                      :class="form.imprimirOtrasCocinas ? 'translate-x-5' : 'translate-x-0'"
                    />
                  </button>
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-gray-600 mb-1">ICBPER</label>
                  <button
                    type="button"
                    @click="form.icbper = !form.icbper"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                    :class="form.icbper ? 'bg-[#F0302E]' : 'bg-gray-200'"
                  >
                    <span
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                      :class="form.icbper ? 'translate-x-5' : 'translate-x-0'"
                    />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB 2: LISTA DE INSUMOS -->
        <div v-else-if="activeTab === 'insumos'" class="space-y-6">
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
                    <th class="py-2.5 px-4">Nombre</th>
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
        </div>

        <!-- Footer Buttons -->
        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
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

    <!-- VIEW 3: PDF / LISTA REPORT VIEW -->
    <div v-else-if="currentView === 'report_list'" class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">Detalle de Productos</h1>
        <button
          @click="currentView = 'list'"
          class="px-4 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 flex items-center gap-1.5"
        >
          <ChevronLeft class="w-4 h-4" />
          <span>Volver al Listado</span>
        </button>
      </div>

      <!-- PDF Preview Window -->
      <div class="bg-[#2E3346] rounded-xl shadow-lg p-4 flex gap-4 min-h-0 sm:min-h-[650px]">
        <!-- PDF Sidebar Thumbnails -->
        <div class="w-44 bg-[#1E2540] rounded-lg p-2.5 flex flex-col gap-3 overflow-y-auto max-h-[620px] text-white text-center text-xs">
          <div class="border-2 border-blue-400 bg-white p-2 rounded shadow text-gray-800 text-[9px] h-36 flex flex-col justify-between">
            <div class="border-b pb-1 font-bold">Página 1</div>
            <div class="space-y-1">
              <div class="h-2 bg-gray-200 rounded"></div>
              <div class="h-2 bg-gray-200 rounded"></div>
              <div class="h-2 bg-gray-200 rounded"></div>
            </div>
            <div class="text-[8px] text-gray-400">1</div>
          </div>
          <div class="border border-gray-600 bg-white p-2 rounded shadow text-gray-800 text-[9px] h-36 flex flex-col justify-between opacity-70">
            <div class="border-b pb-1 font-bold">Página 2</div>
            <div class="space-y-1">
              <div class="h-2 bg-gray-200 rounded"></div>
              <div class="h-2 bg-gray-200 rounded"></div>
            </div>
            <div class="text-[8px] text-gray-400">2</div>
          </div>
        </div>

        <!-- PDF Document Sheet -->
        <div class="flex-1 bg-white rounded-lg p-8 shadow-inner overflow-y-auto max-h-[620px]">
          <div class="text-right text-[10px] text-gray-500 mb-2">
            <div>FECHA: 14/03/2026</div>
            <div>Pág: 1 de 10</div>
          </div>

          <div class="text-center mb-6">
            <h2 class="text-sm font-bold text-gray-800">DSG HOLDING S.A.C</h2>
            <p class="text-[11px] text-gray-600">JR. YURIMAGUAS NRO. 320 (FRENTE A LA PLAZA DE LA BANDA DE SHILCAY) SAN MARTIN - SAN MARTIN - LA BAND</p>
            <p class="text-[11px] text-gray-600">RUC: 20613845420</p>
            <h3 class="text-xs font-bold text-gray-900 mt-4 tracking-wider">DETALLE DE PRODUCTOS</h3>
          </div>

          <!-- Product Groups -->
          <div class="space-y-4 text-xs">
            <!-- 1/2 Pollo -->
            <div>
              <div class="font-bold text-gray-800 mb-1">1/2 POLLO</div>
              <table class="w-full border border-gray-300 text-left text-[11px]">
                <thead class="bg-gray-200 text-gray-700">
                  <tr>
                    <th class="p-1.5 border-r border-gray-300">Producto</th>
                    <th class="p-1.5 w-24 text-right">Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="p-1.5 border-r border-gray-300">1/8 POLLO UND</td>
                    <td class="p-1.5 text-right">4.00</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Inca Kola -->
            <div>
              <div class="font-bold text-gray-800 mb-1">INCA KOLA 1LT DESC</div>
              <table class="w-full border border-gray-300 text-left text-[11px]">
                <thead class="bg-gray-200 text-gray-700">
                  <tr>
                    <th class="p-1.5 border-r border-gray-300">Producto</th>
                    <th class="p-1.5 w-24 text-right">Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="p-1.5 border-r border-gray-300">INCA KOLA 1LT DESC</td>
                    <td class="p-1.5 text-right">1.00</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Cecina Frita -->
            <div>
              <div class="font-bold text-gray-800 mb-1">CECINA FRITA</div>
              <table class="w-full border border-gray-300 text-left text-[11px]">
                <thead class="bg-gray-200 text-gray-700">
                  <tr>
                    <th class="p-1.5 border-r border-gray-300">Producto</th>
                    <th class="p-1.5 w-24 text-right">Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="p-1.5 border-r border-gray-300">CECINA UND</td>
                    <td class="p-1.5 text-right">1.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL 1: INACTIVOS -->
    <div
      v-if="showInactivosModal"
      @click.self="showInactivosModal = false"
      class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
    >
      <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="bg-[#1E2540] text-white px-5 py-3.5 flex items-center justify-between">
          <h2 class="text-xs font-bold tracking-wide">Inactivos</h2>
          <button @click="showInactivosModal = false" class="text-red-400 hover:text-white transition-colors">
            <X class="w-4 h-4" />
          </button>
        </div>

        <!-- Body -->
        <div class="p-6">
          <div class="border border-gray-200 rounded-lg overflow-x-auto min-h-[200px]">
            <table class="w-full text-xs text-left">
              <thead class="bg-gray-100 text-gray-700 border-b">
                <tr>
                  <th class="py-2.5 px-3">Descripción</th>
                  <th class="py-2.5 px-3 text-center">Precio Venta</th>
                  <th class="py-2.5 px-3 text-center">Categoría</th>
                  <th class="py-2.5 px-3 text-center">Tiempo Preparación</th>
                  <th class="py-2.5 px-3 text-center">Cocina</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in inactivosList"
                  :key="item.id"
                  @click="selectedInactivo = item.id"
                  class="cursor-pointer transition-colors"
                  :class="selectedInactivo === item.id ? 'bg-blue-50/80' : 'hover:bg-gray-50'"
                >
                  <td class="py-2.5 px-3 font-medium text-gray-800">{{ item.descripcion }}</td>
                  <td class="py-2.5 px-3 text-center text-gray-700">{{ item.precioVenta }}</td>
                  <td class="py-2.5 px-3 text-center text-gray-600">{{ item.categoria }}</td>
                  <td class="py-2.5 px-3 text-center text-gray-600">{{ item.tiempo }}</td>
                  <td class="py-2.5 px-3 text-center text-gray-600">{{ item.cocina }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination inside modal -->
          <div class="flex items-center justify-between mt-4 text-xs text-gray-500">
            <div class="flex items-center gap-2">
              <select class="border border-gray-200 rounded px-2 py-0.5 text-xs text-gray-600 bg-white">
                <option>10</option>
              </select>
              <button class="p-1 border border-gray-200 rounded"><ChevronLeft class="w-3 h-3" /></button>
              <span>Página 1 de 1</span>
              <button class="p-1 border border-gray-200 rounded"><ChevronRight class="w-3 h-3" /></button>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-3 mt-6">
            <button
              :disabled="!selectedInactivo"
              class="px-5 py-2 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors"
              :class="selectedInactivo ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
            >
              <RotateCcw class="w-3.5 h-3.5" />
              <span>ACTIVAR</span>
            </button>
            <button
              @click="showInactivosModal = false"
              class="px-5 py-2 border border-red-500 text-red-500 hover:bg-red-50 rounded-lg text-xs font-semibold transition-colors"
            >
              CERRAR
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL 2: CATEGORIA -->
    <div
      v-if="showCategoriaModal"
      @click.self="showCategoriaModal = false"
      class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
    >
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="bg-[#1E2540] text-white px-5 py-3.5 flex items-center justify-between">
          <h2 class="text-xs font-bold tracking-wide">Nueva Categoría</h2>
          <button @click="showCategoriaModal = false" class="text-red-400 hover:text-white transition-colors">
            <X class="w-4 h-4" />
          </button>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Nombre Categoría</label>
            <input type="text" placeholder="Escribe el nombre..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs" />
          </div>
          <div class="flex items-center justify-end gap-2 pt-4">
            <button @click="showCategoriaModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-xs text-gray-600 hover:bg-gray-50">
              Cancelar
            </button>
            <button @click="showCategoriaModal = false" class="px-4 py-2 bg-[#F0302E] text-white rounded-lg text-xs font-semibold hover:bg-red-600">
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
