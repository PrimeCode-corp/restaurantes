<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTablePagination from '@/Components/ui/DataTablePagination.vue'
import { Card } from '@/Components/ui/card'
import { Input } from '@/Components/ui/input'
import {
  Table,
  TableHeader,
  TableBody,
  TableRow,
  TableHead,
  TableCell,
} from '@/Components/ui/table'
import {
  Search,
  Warehouse,
  ChevronDown,
  Calendar as CalendarIcon,
  Check,
  X,
  FileSpreadsheet,
  FileText,
  FileCode,
  Download,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// FILTROS SUPERIORES (Fiel a la captura 1 y 2)
// -------------------------------------------------------------------
const almacenes = [
  'ALMACEN - TODOS',
  'ALMACEN GENERAL',
  'CAJA PRINCIPAL',
  'COCINA',
  'BARRA',
]

const selectedAlmacen = ref('ALMACEN - TODOS')
const checkTodos = ref(true)
const insumoSeleccionado = ref(null) // objeto { id, descripcion, categoria, tipoInsumo }
const fechaInicio = ref('')
const fechaFin = ref('')
const busquedaDetalle = ref('')

function handleCheckTodosChange() {
  if (checkTodos.value) {
    insumoSeleccionado.value = null
  }
}

const rowsPerPage = ref(10)
const currentPage = ref(1)

// Control de resultados visibles
const resultadosCargados = ref(true)

// -------------------------------------------------------------------
// CATÁLOGO DE INSUMOS Y PRODUCTOS (Para modal de búsqueda)
// -------------------------------------------------------------------
const catalogoInsumosProductos = ref([
  { id: 1, descripcion: 'CECINA', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO' },
  { id: 2, descripcion: 'HARINA', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO' },
  { id: 3, descripcion: 'MASA MADRE', categoria: 'INSUMOS', tipoInsumo: 'PREPARADO' },
  { id: 4, descripcion: 'PIZZA FAMILIAR CLÁSICA', categoria: 'PRODUCTOS', tipoInsumo: '-' },
  { id: 5, descripcion: 'SALSA DE TOMATE ESPECIAL', categoria: 'INSUMOS', tipoInsumo: 'PREPARADO' },
  { id: 6, descripcion: 'GASEOSA COCA COLA 1.5L', categoria: 'PRODUCTOS', tipoInsumo: '-' },
  { id: 7, descripcion: 'QUESO MOZZARELLA 500G', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO' },
  { id: 8, descripcion: 'OREGANO SECO MOLIDO', categoria: 'INSUMOS', tipoInsumo: 'PREPARADO' },
  { id: 9, descripcion: 'POLLO ENTERO MARINADO', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO' },
  { id: 10, descripcion: 'LECHE EVAPORADA', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO' },
])

// -------------------------------------------------------------------
// REGISTROS KARDEX (Fiel a la captura 2 con Harina / Ajuste de inventario)
// -------------------------------------------------------------------
const registrosKardex = ref([
  {
    id: 1,
    fecha: '00/00/0000',
    insumo: 'HARINA',
    descripcion: 'AJUSTE DE INVENTARIO',
    tipoES: 'ENTRADA',
    precio: 1.50,
    cantidad: 15.00,
    stock: 35.00,
  },
  {
    id: 2,
    fecha: '05/08/2026',
    insumo: 'HARINA',
    descripcion: 'COMPRA SEGÚN FACTURA F001-492',
    tipoES: 'ENTRADA',
    precio: 1.50,
    cantidad: 50.00,
    stock: 85.00,
  },
  {
    id: 3,
    fecha: '12/08/2026',
    insumo: 'HARINA',
    descripcion: 'CONSUMO PRODUCCIÓN MASA',
    tipoES: 'SALIDA',
    precio: 1.50,
    cantidad: 20.00,
    stock: 65.00,
  },
])

function formatMoney(num) {
  return Number(num || 0).toFixed(2)
}

function cargarResultados() {
  resultadosCargados.value = true
}

// -------------------------------------------------------------------
// FILTRADO DE TABLA KARDEX
// -------------------------------------------------------------------
const filteredKardex = computed(() => {
  const q = busquedaDetalle.value.toLowerCase().trim()
  return registrosKardex.value.filter((k) => {
    const matchQuery = !q ||
      k.insumo.toLowerCase().includes(q) ||
      k.descripcion.toLowerCase().includes(q) ||
      k.tipoES.toLowerCase().includes(q)

    const matchInsumo = checkTodos.value || !insumoSeleccionado.value ||
      k.insumo.toLowerCase() === insumoSeleccionado.value.descripcion.toLowerCase()

    return matchQuery && matchInsumo
  })
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredKardex.value.length / rowsPerPage.value)))
const paginatedKardex = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filteredKardex.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: INSUMOS Y PRODUCTOS (Fiel a la captura 3 y 4)
// -------------------------------------------------------------------
const showModalInsumos = ref(false)
const busquedaModal = ref('')
const selectedModalId = ref(1) // Cecina seleccionado por defecto
const modalCurrentPage = ref(1)
const modalRowsPerPage = ref(10)

const modalFiltrados = computed(() => {
  const q = busquedaModal.value.toLowerCase().trim()
  if (!q) return catalogoInsumosProductos.value
  return catalogoInsumosProductos.value.filter(
    (i) =>
      i.descripcion.toLowerCase().includes(q) ||
      i.categoria.toLowerCase().includes(q) ||
      i.tipoInsumo.toLowerCase().includes(q)
  )
})

const modalTotalPages = computed(() =>
  Math.max(1, Math.ceil(modalFiltrados.value.length / modalRowsPerPage.value))
)

const modalPaginados = computed(() => {
  const start = (modalCurrentPage.value - 1) * modalRowsPerPage.value
  return modalFiltrados.value.slice(start, start + modalRowsPerPage.value)
})

function openModalInsumos() {
  busquedaModal.value = ''
  selectedModalId.value = insumoSeleccionado.value ? insumoSeleccionado.value.id : 1
  modalCurrentPage.value = 1
  showModalInsumos.value = true
}

function aceptarInsumoModal() {
  const ins = catalogoInsumosProductos.value.find((i) => i.id === selectedModalId.value)
  if (ins) {
    insumoSeleccionado.value = ins
    checkTodos.value = false
  }
  showModalInsumos.value = false
}

// Menú desplegable para botón "MOSTRAR REPORTES"
const showReportMenu = ref(false)
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="kardex-insumos">
    <div class="space-y-4">
      <!-- Título de Página -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Kardex Insumos
      </h1>

      <!-- Contenedor Principal (Fiel a la captura 1 y 2) -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-7 space-y-6 min-h-0 sm:min-h-[580px]">
        <!-- Barra de Filtros Superiores -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">
          <!-- 1. Buscar Insumo con Check Todos (md:col-span-4) -->
          <div class="md:col-span-4 space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Buscar Insumo:</label>
            <div class="flex items-center gap-2">
              <div class="relative flex-1">
                <input
                  type="text"
                  readonly
                  :value="checkTodos ? '' : (insumoSeleccionado ? insumoSeleccionado.descripcion : '')"
                  :placeholder="checkTodos ? 'Todos los insumos seleccionados' : 'Busca insumo a seleccionar...'"
                  class="w-full h-9 pl-3 pr-8 text-xs rounded-lg border border-gray-300 bg-white placeholder:text-gray-400 cursor-pointer shadow-2xs focus:outline-none focus:ring-1 focus:ring-gray-400"
                  @click="openModalInsumos"
                />
                <Search class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>

              <!-- Checkbox Todos con icono cuadrado rojo/naranja -->
              <label class="flex items-center gap-1.5 cursor-pointer select-none shrink-0 text-xs font-semibold text-gray-700">
                <input
                  v-model="checkTodos"
                  type="checkbox"
                  class="w-4 h-4 rounded border-gray-300 text-[#F0302E] focus:ring-[#F0302E] accent-[#F0302E]"
                  @change="handleCheckTodosChange"
                />
                <span>Todos</span>
              </label>
            </div>
          </div>

          <!-- 2. Almacén (md:col-span-3) -->
          <div class="md:col-span-3 space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Almacén:</label>
            <div class="relative">
              <Warehouse class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
              <select
                v-model="selectedAlmacen"
                class="w-full h-9 pl-9 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold uppercase appearance-none focus:outline-none focus:ring-1 focus:ring-gray-400 cursor-pointer shadow-2xs"
              >
                <option v-for="alm in almacenes" :key="alm" :value="alm">
                  {{ alm }}
                </option>
              </select>
              <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            </div>
          </div>

          <!-- 3. Calendario (md:col-span-3) -->
          <div class="md:col-span-3 space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Calendario:</label>
            <div class="grid grid-cols-2 gap-2">
              <div class="relative">
                <Input
                  type="text"
                  readonly
                  :value="fechaInicio || 'TODOS'"
                  class="h-9 pl-7 pr-2 text-xs rounded-lg border-gray-300 bg-white text-gray-700 font-semibold"
                />
                <CalendarIcon class="absolute left-2 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" />
                <input
                  v-model="fechaInicio"
                  type="date"
                  class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                />
              </div>

              <div class="relative">
                <Input
                  type="text"
                  readonly
                  :value="fechaFin || 'TODOS'"
                  class="h-9 pl-7 pr-2 text-xs rounded-lg border-gray-300 bg-white text-gray-700 font-semibold"
                />
                <CalendarIcon class="absolute left-2 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" />
                <input
                  v-model="fechaFin"
                  type="date"
                  class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                />
              </div>
            </div>
          </div>

          <!-- 4. Botón VER RESULTADOS (md:col-span-2) -->
          <div class="md:col-span-2">
            <button
              type="button"
              class="w-full h-9 bg-[#24265D] hover:bg-[#1C1E49] text-white rounded-lg text-xs font-bold tracking-wider flex items-center justify-center gap-1.5 shadow-sm transition-colors cursor-pointer select-none"
              @click="cargarResultados"
            >
              <FileText class="w-3.5 h-3.5" />
              <span>VER RESULTADOS</span>
            </button>
          </div>
        </div>

        <!-- Tarjeta Interior: Detalle Kardex -->
        <Card class="bg-white border border-gray-200/90 shadow-2xs rounded-xl p-5 space-y-4">
          <h2 class="text-sm font-bold text-gray-900 tracking-tight">
            Detalle Kardex
          </h2>

          <!-- Buscador de Detalle Kardex -->
          <div class="relative w-full max-w-xs">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="busquedaDetalle"
              type="text"
              placeholder="Buscar..."
              class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs"
            />
          </div>

          <!-- TABLA DETALLE KARDEX -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">Fecha</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[18%]">Insumo</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[30%]">Descripción</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">Tipo E/S</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[10%]">Precio</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[10%]">Cantidad</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[8%]">Stock</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <!-- Filas de datos -->
                <TableRow
                  v-for="reg in paginatedKardex"
                  :key="reg.id"
                  class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors"
                >
                  <TableCell class="py-3 px-4 text-xs text-center font-mono text-gray-700">
                    {{ reg.fecha }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-center font-bold text-gray-800 uppercase">
                    {{ reg.insumo }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-center font-medium text-gray-700 uppercase">
                    {{ reg.descripcion }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-center font-semibold uppercase">
                    <span
                      class="inline-block px-2 py-0.5 rounded text-[11px] font-bold"
                      :class="[
                        reg.tipoES === 'ENTRADA'
                          ? 'text-emerald-700 bg-emerald-50 border border-emerald-200'
                          : 'text-rose-700 bg-rose-50 border border-rose-200'
                      ]"
                    >
                      {{ reg.tipoES }}
                    </span>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-center font-mono text-gray-700">
                    {{ formatMoney(reg.precio) }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-center font-mono font-bold text-gray-800">
                    {{ formatMoney(reg.cantidad) }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-center font-mono font-bold text-gray-900">
                    {{ formatMoney(reg.stock) }}
                  </TableCell>
                </TableRow>

                <!-- Skeleton rows de relleno visual idénticas a la captura 2 -->
                <TableRow
                  v-for="k in Math.max(0, 10 - paginatedKardex.length)"
                  :key="'skel-k-' + k"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-14 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-20 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-32 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-12 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-10 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-12 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-10 mx-auto"></div></TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </Card>

        <!-- Parte Inferior: Paginador a la izquierda y Botón MOSTRAR REPORTES a la derecha -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
          <!-- Paginador -->
          <DataTablePagination
            :current-page="currentPage"
            :total-pages="totalPages"
            :rows-per-page="rowsPerPage"
            :rows-per-page-options="[10, 25, 50]"
            @update:current-page="currentPage = $event"
            @update:rows-per-page="rowsPerPage = $event"
          />

          <!-- Botón Verde MOSTRAR REPORTES con borde y dropdown (Fiel a la captura) -->
          <div class="relative">
            <button
              type="button"
              class="h-9 px-4 border border-[#28A745] text-[#28A745] hover:bg-emerald-50 rounded-lg text-xs font-bold tracking-wide flex items-center gap-2 transition-colors cursor-pointer shadow-2xs"
              @click="showReportMenu = !showReportMenu"
            >
              <Download class="w-3.5 h-3.5 stroke-[2.5]" />
              <span>MOSTRAR REPORTES</span>
              <ChevronDown class="w-3.5 h-3.5" />
            </button>

            <!-- Menú de opciones de exportación del reporte -->
            <div
              v-if="showReportMenu"
              class="absolute right-0 bottom-full mb-1 w-44 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-30"
            >
              <button
                type="button"
                class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                @click="showReportMenu = false"
              >
                <FileSpreadsheet class="w-4 h-4 text-emerald-600" />
                <span>Exportar Excel</span>
              </button>
              <button
                type="button"
                class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                @click="showReportMenu = false"
              >
                <FileText class="w-4 h-4 text-rose-600" />
                <span>Exportar PDF</span>
              </button>
            </div>
          </div>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL: INSUMOS Y PRODUCTOS (Capturas 3 y 4)                     -->
    <!-- =============================================================== -->
    <div
      v-if="showModalInsumos"
      @click.self="showModalInsumos = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden animate-in zoom-in-95 duration-150 flex flex-col max-h-[90vh] flex flex-col">
        <!-- Header con fondo oscuro y texto azul -->
        <div class="px-6 py-4 flex items-center justify-between bg-[#20233B] text-white">
          <h3 class="font-bold text-sm text-[#4E73DF] font-sans tracking-tight">
            Insumos y Productos
          </h3>
          <button
            type="button"
            class="text-red-400 hover:text-red-300 transition-colors p-1 rounded-md"
            @click="showModalInsumos = false"
          >
            <X class="w-5 h-5 stroke-[2.5]" />
          </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-6 space-y-4">
          <!-- Buscador con bordes redondeados y lupa -->
          <div class="relative w-full max-w-sm max-h-[90vh] flex flex-col">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="busquedaModal"
              type="text"
              placeholder="Buscar..."
              class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs"
            />
          </div>

          <!-- Tabla de Insumos y Productos -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[50%]">Descripción</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[25%]">Categoría</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[25%]">Tipo Insumo</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="item in modalPaginados"
                  :key="item.id"
                  class="cursor-pointer border-b border-gray-100 transition-colors select-none"
                  :class="[
                    selectedModalId === item.id
                      ? 'bg-blue-50/80 font-bold text-[#24265D]'
                      : 'hover:bg-gray-50/70'
                  ]"
                  @click="selectedModalId = item.id"
                >
                  <TableCell class="py-2.5 px-4 text-xs text-center">
                    <div class="flex items-center justify-center gap-1.5">
                      <span v-if="selectedModalId === item.id" class="w-1.5 h-1.5 rounded-full bg-[#3E55B3]"></span>
                      <span class="text-gray-800">{{ item.descripcion }}</span>
                    </div>
                  </TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center font-semibold text-gray-600 font-mono">
                    {{ item.categoria }}
                  </TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center font-mono text-gray-600">
                    {{ item.tipoInsumo }}
                  </TableCell>
                </TableRow>

                <!-- Skeleton rows de relleno -->
                <TableRow
                  v-for="m in Math.max(0, 6 - modalPaginados.length)"
                  :key="'skel-m-' + m"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-36 mx-auto"></div></TableCell>
                  <TableCell class="py-3 px-4 text-center text-xs font-mono text-gray-400">INSUMOS</TableCell>
                  <TableCell class="py-3 px-4 text-center text-xs font-mono text-gray-400">COMPRADO</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginador del Modal + Botones iconos PDF / Excel a la derecha -->
          <div class="flex items-center justify-between pt-1">
            <DataTablePagination
              :current-page="modalCurrentPage"
              :total-pages="modalTotalPages"
              :rows-per-page="modalRowsPerPage"
              :rows-per-page-options="[10, 20]"
              @update:current-page="modalCurrentPage = $event"
              @update:rows-per-page="modalRowsPerPage = $event"
            />

            <!-- Iconos de PDF (rojo) y Excel (verde) en el pie del modal como en la captura 3 y 4 -->
            <div class="flex items-center gap-2">
              <button
                type="button"
                class="w-7 h-7 rounded border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors shadow-2xs"
                title="Exportar PDF"
              >
                <FileText class="w-3.5 h-3.5" />
              </button>
              <button
                type="button"
                class="w-7 h-7 rounded border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors shadow-2xs"
                title="Exportar Excel"
              >
                <FileSpreadsheet class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>

        <!-- Footer Modal: [ACEPTAR (azul)] [CERRAR (rojo contorno)] (Fiel a la captura) -->
        <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end gap-3">
          <button
            type="button"
            class="h-9 px-6 bg-[#2B50ED] hover:bg-[#2040D0] text-white rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer"
            @click="aceptarInsumoModal"
          >
            <span>ACEPTAR</span>
          </button>
          <button
            type="button"
            class="h-9 px-6 border border-[#F0302E] text-[#F0302E] hover:bg-red-50 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
            @click="showModalInsumos = false"
          >
            <span>CERRAR</span>
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
