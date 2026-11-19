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
const productoSeleccionado = ref(null) // objeto { id, descripcion, categoria, tipo }
const fechaInicio = ref('')
const fechaFin = ref('')
const busquedaDetalle = ref('')

const rowsPerPage = ref(10)
const currentPage = ref(1)

function handleCheckTodosChange() {
  if (checkTodos.value) {
    productoSeleccionado.value = null
  }
}

// -------------------------------------------------------------------
// CATÁLOGO DE PRODUCTOS DISPONIBLES (Para el modal de búsqueda)
// -------------------------------------------------------------------
const catalogoProductos = ref([
  { id: 1, descripcion: 'POLLO A LA BRASA 1/8', categoria: 'PRODUCTOS', tipo: 'TERMINADO', precio: 16.00 },
  { id: 2, descripcion: 'POLLO A LA BRASA 1/4', categoria: 'PRODUCTOS', tipo: 'TERMINADO', precio: 24.00 },
  { id: 3, descripcion: 'POLLO A LA BRASA ENTERO', categoria: 'PRODUCTOS', tipo: 'TERMINADO', precio: 68.00 },
  { id: 4, descripcion: 'PIZZA FAMILIAR CLÁSICA', categoria: 'PRODUCTOS', tipo: 'TERMINADO', precio: 45.00 },
  { id: 5, descripcion: 'PIZZA MEDIANA AMERICANA', categoria: 'PRODUCTOS', tipo: 'TERMINADO', precio: 32.00 },
  { id: 6, descripcion: 'GASEOSA COCA COLA 1.5L', categoria: 'PRODUCTOS', tipo: 'BEBIDA', precio: 10.00 },
  { id: 7, descripcion: 'GASEOSA INCA KOLA 500ML', categoria: 'PRODUCTOS', tipo: 'BEBIDA', precio: 4.50 },
  { id: 8, descripcion: 'CHICHA MORADA JARRA', categoria: 'PRODUCTOS', tipo: 'BEBIDA', precio: 15.00 },
])

// -------------------------------------------------------------------
// REGISTROS KARDEX PRODUCTOS (Fiel a la captura 2: POLLO A LA BRASA 1/8)
// -------------------------------------------------------------------
const registrosKardex = ref([
  {
    id: 1,
    fecha: '00/00/0000',
    producto: 'POLLO A LA BRASA 1/8',
    descripcion: 'VENTA DE PRODUCTOS',
    tipoES: 'SALIDA',
    precio: 16.00,
    cantidad: 1.00,
  },
  {
    id: 2,
    fecha: '18/08/2026',
    producto: 'POLLO A LA BRASA 1/8',
    descripcion: 'VENTA BOLETA B001-0024',
    tipoES: 'SALIDA',
    precio: 16.00,
    cantidad: 2.00,
  },
  {
    id: 3,
    fecha: '18/08/2026',
    producto: 'POLLO A LA BRASA 1/8',
    descripcion: 'PRODUCCIÓN / INGRESO COCINA',
    tipoES: 'ENTRADA',
    precio: 16.00,
    cantidad: 10.00,
  },
])

function formatMoney(num) {
  return Number(num || 0).toFixed(2)
}

function cargarResultados() {
  // Simulación de carga
}

// -------------------------------------------------------------------
// FILTRADO DE TABLA KARDEX PRODUCTOS
// -------------------------------------------------------------------
const filteredKardex = computed(() => {
  const q = busquedaDetalle.value.toLowerCase().trim()
  return registrosKardex.value.filter((k) => {
    const matchQuery = !q ||
      k.producto.toLowerCase().includes(q) ||
      k.descripcion.toLowerCase().includes(q) ||
      k.tipoES.toLowerCase().includes(q)

    const matchProducto = checkTodos.value || !productoSeleccionado.value ||
      k.producto.toLowerCase().includes(productoSeleccionado.value.descripcion.toLowerCase())

    return matchQuery && matchProducto
  })
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredKardex.value.length / rowsPerPage.value)))
const paginatedKardex = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filteredKardex.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: BUSCAR PRODUCTO
// -------------------------------------------------------------------
const showModalProducto = ref(false)
const busquedaModal = ref('')
const selectedModalId = ref(1)
const modalCurrentPage = ref(1)
const modalRowsPerPage = ref(10)

const modalFiltrados = computed(() => {
  const q = busquedaModal.value.toLowerCase().trim()
  if (!q) return catalogoProductos.value
  return catalogoProductos.value.filter(
    (p) =>
      p.descripcion.toLowerCase().includes(q) ||
      p.categoria.toLowerCase().includes(q) ||
      p.tipo.toLowerCase().includes(q)
  )
})

const modalTotalPages = computed(() =>
  Math.max(1, Math.ceil(modalFiltrados.value.length / modalRowsPerPage.value))
)

const modalPaginados = computed(() => {
  const start = (modalCurrentPage.value - 1) * modalRowsPerPage.value
  return modalFiltrados.value.slice(start, start + modalRowsPerPage.value)
})

function openModalProducto() {
  busquedaModal.value = ''
  selectedModalId.value = productoSeleccionado.value ? productoSeleccionado.value.id : 1
  modalCurrentPage.value = 1
  showModalProducto.value = true
}

function aceptarProductoModal() {
  const prod = catalogoProductos.value.find((p) => p.id === selectedModalId.value)
  if (prod) {
    productoSeleccionado.value = prod
    checkTodos.value = false
  }
  showModalProducto.value = false
}
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="kardex-productos">
    <div class="space-y-4">
      <!-- Título de Página -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Kardex Productos
      </h1>

      <!-- Contenedor Principal (Fiel a la captura 1 y 2) -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-7 space-y-6 min-h-[580px]">
        <!-- Barra de Filtros Superiores -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">
          <!-- 1. Buscar Insumo/Producto con Check Todos (md:col-span-4) -->
          <div class="md:col-span-4 space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Buscar Insumo:</label>
            <div class="flex items-center gap-2">
              <div class="relative flex-1">
                <input
                  type="text"
                  readonly
                  :value="checkTodos ? '' : (productoSeleccionado ? productoSeleccionado.descripcion : '')"
                  :placeholder="checkTodos ? 'Busca insumo a seleccionar....' : 'Busca insumo a seleccionar....'"
                  class="w-full h-9 pl-3 pr-8 text-xs rounded-lg border border-gray-300 bg-white placeholder:text-gray-400 cursor-pointer shadow-2xs focus:outline-none focus:ring-1 focus:ring-gray-400"
                  @click="openModalProducto"
                />
                <Search class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>

              <!-- Checkbox Todos con borde rojo y fondo naranja-rojizo como en la captura -->
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

          <!-- TABLA DETALLE KARDEX PRODUCTOS (Fiel a la captura: Fecha | Productos | Descripción | Tipo E/S | Precio | Cantidad) -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[15%]">Fecha</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[25%]">Productos</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[30%]">Descripción</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">Tipo E/S</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[10%]">Precio</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[8%]">Cantidad</TableHead>
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
                    {{ reg.producto }}
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
                </TableRow>

                <!-- Skeleton rows de relleno visual para calcar la captura 2 -->
                <TableRow
                  v-for="k in Math.max(0, 10 - paginatedKardex.length)"
                  :key="'skel-kp-' + k"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-14 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-28 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-32 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-14 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-10 mx-auto"></div></TableCell>
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

          <!-- Botón Verde MOSTRAR REPORTES con contorno verde -->
          <button
            type="button"
            class="h-9 px-4 border border-[#28A745] text-[#28A745] hover:bg-emerald-50 rounded-lg text-xs font-bold tracking-wide flex items-center gap-2 transition-colors cursor-pointer shadow-2xs"
          >
            <FileSpreadsheet class="w-3.5 h-3.5" />
            <span>MOSTRAR REPORTES</span>
            <ChevronDown class="w-3.5 h-3.5" />
          </button>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL: BUSCAR PRODUCTO                                          -->
    <!-- =============================================================== -->
    <div
      v-if="showModalProducto"
      @click.self="showModalProducto = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden animate-in zoom-in-95 duration-150 flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 flex items-center justify-between bg-[#20233B] text-white">
          <h3 class="font-bold text-sm text-[#4E73DF] font-sans tracking-tight">
            Insumos y Productos
          </h3>
          <button
            type="button"
            class="text-red-400 hover:text-red-300 transition-colors p-1 rounded-md"
            @click="showModalProducto = false"
          >
            <X class="w-5 h-5 stroke-[2.5]" />
          </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-6 space-y-4">
          <!-- Buscador -->
          <div class="relative w-full max-w-sm">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="busquedaModal"
              type="text"
              placeholder="Buscar..."
              class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs"
            />
          </div>

          <!-- Tabla de Productos Modal -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[50%]">Descripción</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[25%]">Categoría</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[25%]">Tipo</TableHead>
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
                    {{ item.tipo }}
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginador del Modal -->
          <div class="flex items-center justify-between pt-1">
            <DataTablePagination
              :current-page="modalCurrentPage"
              :total-pages="modalTotalPages"
              :rows-per-page="modalRowsPerPage"
              :rows-per-page-options="[10, 20]"
              @update:current-page="modalCurrentPage = $event"
              @update:rows-per-page="modalRowsPerPage = $event"
            />
          </div>
        </div>

        <!-- Footer Modal -->
        <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end gap-3">
          <button
            type="button"
            class="h-9 px-6 bg-[#2B50ED] hover:bg-[#2040D0] text-white rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer"
            @click="aceptarProductoModal"
          >
            <span>ACEPTAR</span>
          </button>
          <button
            type="button"
            class="h-9 px-6 border border-[#F0302E] text-[#F0302E] hover:bg-red-50 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
            @click="showModalProducto = false"
          >
            <span>CERRAR</span>
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
