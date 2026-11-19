<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTablePagination from '@/Components/ui/DataTablePagination.vue'
import { Card } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
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
  Plus,
  Eye,
  Trash2,
  ArrowLeft,
  Save,
  ChevronDown,
  FileText,
  Building2,
  Calendar,
  ArrowRightLeft,
  X,
} from 'lucide-vue-next'

// -------------------------------------------------------------------------
// MODO VISTA: 'lista' | 'nuevo'
// -------------------------------------------------------------------------
const viewMode = ref('lista')

// -------------------------------------------------------------------------
// OPCIONES DE ALMACENES / SUCURSALES
// -------------------------------------------------------------------------
const almacenes = [
  { id: 'todos', label: 'ALMACEN - TODOS' },
  { id: 'caja-principal', label: 'ALMACEN - CAJA PRINCIPAL' },
  { id: 'general', label: 'ALMACEN - GENERAL' },
  { id: 'cocina', label: 'ALMACEN - COCINA' },
  { id: 'barra', label: 'ALMACEN - BARRA' },
]

const periodos = [
  { id: 'todo', label: 'TODO EL PERIODO' },
  { id: 'hoy', label: 'HOY' },
  { id: 'semana', label: 'ESTA SEMANA' },
  { id: 'mes', label: 'ESTE MES' },
]

// -------------------------------------------------------------------------
// CATÁLOGO DE INSUMOS DISPONIBLES (para modal de lista)
// -------------------------------------------------------------------------
const catalogoInsumos = ref([
  { id: 1,  nombre: 'MASA MADRE',        tipo: 'PREPARADO', um: 'UND', stock: 18, precioU: 6.80 },
  { id: 2,  nombre: 'SALSA DE TOMATE',   tipo: 'PREPARADO', um: 'UND', stock: 24, precioU: 4.50 },
  { id: 3,  nombre: 'QUESO MOZZARELLA',  tipo: 'COMPRADO',  um: 'UND', stock: 12, precioU: 28.50 },
  { id: 4,  nombre: 'PIÑA',              tipo: 'COMPRADO',  um: 'UND', stock: 30, precioU: 5.00 },
  { id: 5,  nombre: 'CARNE MOLIDA',      tipo: 'COMPRADO',  um: 'KG',  stock: 8,  precioU: 22.00 },
  { id: 6,  nombre: 'HARINA DE TRIGO',   tipo: 'COMPRADO',  um: 'KG',  stock: 50, precioU: 8.50 },
  { id: 7,  nombre: 'ACEITE DE OLIVA',   tipo: 'COMPRADO',  um: 'LT',  stock: 6,  precioU: 35.00 },
  { id: 8,  nombre: 'OREGANO SECO',      tipo: 'PREPARADO', um: 'UND', stock: 40, precioU: 4.50 },
  { id: 9,  nombre: 'SAL',               tipo: 'COMPRADO',  um: 'KG',  stock: 20, precioU: 2.00 },
  { id: 10, nombre: 'AZUCAR',            tipo: 'COMPRADO',  um: 'KG',  stock: 15, precioU: 3.50 },
  { id: 11, nombre: 'LEVADURA',          tipo: 'PREPARADO', um: 'UND', stock: 25, precioU: 3.00 },
  { id: 12, nombre: 'ALBAHACA FRESCA',   tipo: 'PREPARADO', um: 'UND', stock: 15, precioU: 2.50 },
])

// -------------------------------------------------------------------------
// LISTA DE TRANSFERENCIAS (datos de ejemplo)
// -------------------------------------------------------------------------
const transferencias = ref([
  {
    id: 1,
    fecha: '30/03/2026',
    origen: 'ALMACEN GENERAL',
    destino: 'CAJA PRINCIPAL',
    numero: 'G81-800821',
    usuario: 'SOMA',
    observaciones: 'REINGRESO DE INSUMOS 1',
    detalle: [
      { insumoId: 1, nombre: 'MASA MADRE',       tipo: 'PREPARADO', um: 'UND', stock: 18, precioU: 6.80,  cantidad: 25 },
      { insumoId: 3, nombre: 'QUESO MOZZARELLA', tipo: 'COMPRADO',  um: 'UND', stock: 12, precioU: 28.50, cantidad: 1 },
    ],
  },
  {
    id: 2,
    fecha: '30/03/2026',
    origen: 'CAJA PRINCIPAL',
    destino: 'ALMACEN GENERAL',
    numero: 'G81-800825',
    usuario: 'SOMA',
    observaciones: 'REINGRESO DE INSUMOS 2',
    detalle: [
      { insumoId: 2, nombre: 'SALSA DE TOMATE', tipo: 'PREPARADO', um: 'UND', stock: 24, precioU: 4.50, cantidad: 3 },
    ],
  },
  { id: 3,  fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
  { id: 4,  fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
  { id: 5,  fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
  { id: 6,  fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
  { id: 7,  fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
  { id: 8,  fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
  { id: 9,  fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
  { id: 10, fecha: '30/03/2026', origen: '', destino: '', numero: '', usuario: '', observaciones: '', detalle: [] },
])

// -------------------------------------------------------------------------
// FILTROS LISTA
// -------------------------------------------------------------------------
const filtroAlmacen = ref('todos')
const filtroPeriodo = ref('todo')
const rowsPerPage = ref(10)
const currentPage = ref(1)

const totalPages = computed(() => Math.max(1, Math.ceil(transferencias.value.length / rowsPerPage.value)))
const paginated = computed(() => {
  const s = (currentPage.value - 1) * rowsPerPage.value
  return transferencias.value.slice(s, s + rowsPerPage.value)
})

// -------------------------------------------------------------------------
// MODAL: VER TRANSFERENCIA
// -------------------------------------------------------------------------
const showVerModal = ref(false)
const transferenciaVer = ref(null)

function openVer(t) {
  transferenciaVer.value = t
  showVerModal.value = true
}

const verSubtotal = computed(() => {
  if (!transferenciaVer.value) return 0
  return transferenciaVer.value.detalle.reduce(
    (acc, item) => acc + item.cantidad * item.precioU,
    0,
  )
})

// -------------------------------------------------------------------------
// FORMULARIO NUEVA TRANSFERENCIA
// -------------------------------------------------------------------------
const form = ref({
  sucursalOrigen: '',
  sucursalDestino: '',
  fechaTransferencia: new Date().toISOString().slice(0, 10),
  observacion: '',
})

const detalleItems = ref([])

// -------------------------------------------------------------------------
// MODAL: LISTA DE INSUMOS (Selector)
// -------------------------------------------------------------------------
const showListaModal = ref(false)
const busquedaModal = ref('')
const selectedInsumoId = ref(null)
const listaCurrentPage = ref(1)
const listaRowsPerPage = ref(10)

const catalogoFiltrado = computed(() => {
  const q = busquedaModal.value.toLowerCase().trim()
  if (!q) return catalogoInsumos.value
  return catalogoInsumos.value.filter((i) => i.nombre.toLowerCase().includes(q))
})

const listaTotalPages = computed(() =>
  Math.max(1, Math.ceil(catalogoFiltrado.value.length / listaRowsPerPage.value)),
)

const catalogoPaginado = computed(() => {
  const s = (listaCurrentPage.value - 1) * listaRowsPerPage.value
  return catalogoFiltrado.value.slice(s, s + listaRowsPerPage.value)
})

function openListaModal() {
  busquedaModal.value = ''
  selectedInsumoId.value = null
  listaCurrentPage.value = 1
  showListaModal.value = true
}

function aceptarInsumo() {
  if (!selectedInsumoId.value) return
  const insumo = catalogoInsumos.value.find((i) => i.id === selectedInsumoId.value)
  if (!insumo) return

  // Evitar duplicados
  if (!detalleItems.value.find((d) => d.insumoId === insumo.id)) {
    detalleItems.value.push({
      insumoId: insumo.id,
      nombre: insumo.nombre,
      tipo: insumo.tipo,
      um: insumo.um,
      stock: insumo.stock,
      precioU: insumo.precioU,
      cantidad: 1,
    })
  }

  showListaModal.value = false
  selectedInsumoId.value = null
}

function quitarInsumo(idx) {
  detalleItems.value.splice(idx, 1)
}

const subtotal = computed(() =>
  detalleItems.value.reduce((acc, item) => acc + item.cantidad * item.precioU, 0),
)
const total = computed(() => subtotal.value)

function formatMoney(val) {
  return Number(val).toFixed(2)
}

function formatFechaForm(isoDate) {
  if (!isoDate) return ''
  const [y, m, d] = isoDate.split('-')
  return `${d}/${m}/${y}`
}

function guardarTransferencia() {
  if (!form.value.sucursalOrigen || !form.value.sucursalDestino) return

  const newId = Math.max(0, ...transferencias.value.map((t) => t.id)) + 1
  const origenLabel = almacenes.find((a) => a.id === form.value.sucursalOrigen)?.label.replace('ALMACEN - ', '') || ''
  const destinoLabel = almacenes.find((a) => a.id === form.value.sucursalDestino)?.label.replace('ALMACEN - ', '') || ''

  transferencias.value.unshift({
    id: newId,
    fecha: formatFechaForm(form.value.fechaTransferencia),
    origen: origenLabel,
    destino: destinoLabel,
    numero: `G81-${String(Math.floor(Math.random() * 999999)).padStart(6, '0')}`,
    usuario: 'USUARIO',
    observaciones: form.value.observacion,
    detalle: detalleItems.value.map((d) => ({ ...d })),
  })

  resetForm()
  viewMode.value = 'lista'
}

function resetForm() {
  form.value = {
    sucursalOrigen: '',
    sucursalDestino: '',
    fechaTransferencia: new Date().toISOString().slice(0, 10),
    observacion: '',
  }
  detalleItems.value = []
}

function openNuevo() {
  resetForm()
  viewMode.value = 'nuevo'
}

function volverLista() {
  resetForm()
  viewMode.value = 'lista'
}

const almacenesOrigen = computed(() => almacenes.filter((a) => a.id !== 'todos'))
const almacenesDestino = computed(() =>
  almacenes.filter((a) => a.id !== 'todos' && a.id !== form.value.sucursalOrigen),
)
</script>

<template>
  <div>
  <AdminLayout active-nav="almacenes" active-submenu="transferencia-insumos">

    <!-- ================================================================
         VISTA: LISTA DE TRANSFERENCIAS
         ================================================================ -->
    <template v-if="viewMode === 'lista'">
      <div class="flex items-center justify-between gap-4">
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Transferencia de Insumos
        </h1>
      </div>

      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 flex flex-col justify-between min-h-[520px]">
        <div>
          <!-- Barra de filtros -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-5">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
              <div class="relative w-full sm:w-52">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
                <Input
                  type="text"
                  placeholder="Buscar..."
                  class="pl-9 pr-3 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-xs"
                />
              </div>

              <div class="relative">
                <Building2 class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none z-10" />
                <select
                  v-model="filtroAlmacen"
                  class="h-9 pl-9 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400 appearance-none cursor-pointer min-w-[180px]"
                >
                  <option v-for="a in almacenes" :key="a.id" :value="a.id">{{ a.label }}</option>
                </select>
                <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
              </div>

              <div class="relative">
                <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none z-10" />
                <select
                  v-model="filtroPeriodo"
                  class="h-9 pl-9 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400 appearance-none cursor-pointer min-w-[160px]"
                >
                  <option v-for="p in periodos" :key="p.id" :value="p.id">{{ p.label }}</option>
                </select>
                <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
              </div>
            </div>

            <Button
              size="sm"
              class="bg-[#20233B] hover:bg-[#2B2F4C] text-white rounded-lg px-4 py-1.5 h-9 text-xs font-semibold tracking-wider flex items-center gap-1.5 shadow-sm transition-all whitespace-nowrap"
              @click="openNuevo"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </Button>
          </div>

          <!-- TABLA -->
          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <Table>
              <TableHeader class="bg-[#232738] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Fecha</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Origen</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Destino</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Número</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Usuario</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Observaciones</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-24">Acciones</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="t in paginated"
                  :key="t.id"
                  class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
                >
                  <TableCell class="py-3 px-4 text-xs text-gray-700 text-center">{{ t.fecha }}</TableCell>
                  <TableCell class="py-3 px-4 text-center">
                    <span v-if="t.origen" class="inline-block px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-semibold">{{ t.origen }}</span>
                    <span v-else class="inline-block w-32 h-2.5 bg-gray-100 rounded animate-pulse"></span>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-center">
                    <span v-if="t.destino" class="inline-block px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-semibold">{{ t.destino }}</span>
                    <span v-else class="inline-block w-32 h-2.5 bg-gray-100 rounded animate-pulse"></span>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-gray-500 text-center font-mono">
                    <span v-if="t.numero">{{ t.numero }}</span>
                    <span v-else class="inline-block w-24 h-2.5 bg-gray-100 rounded animate-pulse"></span>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-gray-700 text-center">
                    <span v-if="t.usuario">{{ t.usuario }}</span>
                    <span v-else class="inline-block w-14 h-2.5 bg-gray-100 rounded animate-pulse"></span>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-gray-500 text-center">
                    <span v-if="t.observaciones">{{ t.observaciones }}</span>
                    <span v-else class="inline-block w-28 h-2.5 bg-gray-100 rounded animate-pulse"></span>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors"
                        title="Ver transferencia"
                        @click="openVer(t)"
                      >
                        <Eye class="w-3.5 h-3.5" />
                      </button>
                      <button
                        class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors"
                        title="Eliminar"
                      >
                        <Trash2 class="w-3.5 h-3.5" />
                      </button>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <!-- Footer paginación + REPORTES -->
        <div class="mt-6 pt-2 flex items-center justify-between gap-4">
          <DataTablePagination
            v-model:current-page="currentPage"
            v-model:rows-per-page="rowsPerPage"
            :total-pages="totalPages"
          />
          <Button
            size="sm"
            variant="outline"
            class="border-gray-300 text-gray-700 text-xs px-4 h-8 flex items-center gap-1.5 hover:bg-gray-50 rounded-lg font-semibold"
          >
            <FileText class="w-3.5 h-3.5" />
            REPORTES
            <ChevronDown class="w-3 h-3 ml-0.5" />
          </Button>
        </div>
      </Card>
    </template>

    <!-- ================================================================
         VISTA: NUEVA TRANSFERENCIA
         ================================================================ -->
    <template v-else>
      <div class="flex items-center gap-3">
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Nueva Transferencia
        </h1>
      </div>

      <div class="space-y-4">
        <!-- Cabecera del formulario -->
        <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5">Sucursal Origen:</label>
              <div class="relative">
                <Building2 class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none z-10" />
                <select
                  v-model="form.sucursalOrigen"
                  class="h-9 w-full pl-9 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400 appearance-none"
                >
                  <option value="" disabled>Seleccione...</option>
                  <option v-for="a in almacenesOrigen" :key="a.id" :value="a.id">{{ a.label }}</option>
                </select>
                <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5">Sucursal Destino:</label>
              <div class="relative">
                <Building2 class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none z-10" />
                <select
                  v-model="form.sucursalDestino"
                  class="h-9 w-full pl-9 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400 appearance-none"
                >
                  <option value="" disabled>Seleccione...</option>
                  <option v-for="a in almacenesDestino" :key="a.id" :value="a.id">{{ a.label }}</option>
                </select>
                <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5">Fecha Transferencia:</label>
              <div class="relative">
                <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none z-10" />
                <input
                  v-model="form.fechaTransferencia"
                  type="date"
                  class="h-9 w-full pl-9 pr-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5">Observación:</label>
              <Input
                v-model="form.observacion"
                type="text"
                placeholder="Escribe aquí..."
                class="h-9 text-xs rounded-lg border-gray-300"
              />
            </div>
          </div>
        </Card>

        <!-- Detalle de Transferencia -->
        <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5">
          <h2 class="text-sm font-bold text-gray-800 mb-4">Detalle de Transferencia</h2>

          <!-- Barra de búsqueda + AÑADIR -->
          <div class="flex items-center gap-3 mb-4">
            <div class="flex-1 relative">
              <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
              <Input
                type="text"
                placeholder="Buscar insumo..."
                readonly
                class="pl-9 h-9 text-xs rounded-lg border-gray-300 cursor-pointer"
                @click="openListaModal"
              />
            </div>
            <Button
              size="sm"
              class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 h-9 text-xs font-semibold flex items-center gap-1.5 whitespace-nowrap"
              @click="openListaModal"
            >
              <Plus class="w-3.5 h-3.5 stroke-[2.5]" />
              AÑADIR
            </Button>
          </div>

          <!-- Tabla de Detalle -->
          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <Table>
              <TableHeader class="bg-[#232738] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-left">Producto</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-32">Cantidad</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-20">U. M.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-20">Stock</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-24">Precio U.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-28">Total Precio</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-20">Acciones</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="(item, idx) in detalleItems"
                  :key="item.insumoId"
                  class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
                >
                  <TableCell class="py-2 px-4 text-xs font-semibold text-gray-800">{{ item.nombre }}</TableCell>
                  <TableCell class="py-2 px-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                      <button
                        type="button"
                        class="w-5 h-5 rounded border border-gray-300 text-gray-600 hover:bg-gray-100 flex items-center justify-center text-sm font-bold leading-none transition-colors"
                        @click="item.cantidad = Math.max(1, item.cantidad - 1)"
                      >−</button>
                      <input
                        v-model.number="item.cantidad"
                        type="number"
                        min="1"
                        class="w-12 h-6 text-center text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-gray-300 bg-white"
                      />
                      <button
                        type="button"
                        class="w-5 h-5 rounded border border-gray-300 text-gray-600 hover:bg-gray-100 flex items-center justify-center text-sm font-bold leading-none transition-colors"
                        @click="item.cantidad++"
                      >+</button>
                    </div>
                  </TableCell>
                  <TableCell class="py-2 px-3 text-xs text-gray-600 text-center">{{ item.um }}</TableCell>
                  <TableCell class="py-2 px-3 text-xs text-gray-500 text-center">{{ item.stock }}</TableCell>
                  <TableCell class="py-2 px-3 text-xs font-medium text-gray-700 text-center">{{ formatMoney(item.precioU) }}</TableCell>
                  <TableCell class="py-2 px-3 text-xs font-bold text-gray-900 text-center">{{ formatMoney(item.cantidad * item.precioU) }}</TableCell>
                  <TableCell class="py-2 px-3 text-center">
                    <button
                      type="button"
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center mx-auto transition-colors"
                      @click="quitarInsumo(idx)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </TableCell>
                </TableRow>

                <TableRow v-if="detalleItems.length === 0">
                  <TableCell colspan="7" class="py-14 text-center">
                    <div class="flex flex-col items-center gap-2 text-gray-300">
                      <ArrowRightLeft class="w-7 h-7" />
                      <p class="text-xs font-medium text-gray-400">Sin insumos agregados. Haz clic en AÑADIR.</p>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginación detalle + Subtotal/Total -->
          <div class="mt-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <select class="h-7 px-2 text-xs rounded border border-gray-300 bg-white text-gray-600 focus:outline-none">
                <option>10</option>
                <option>25</option>
                <option>50</option>
              </select>
              <button class="w-6 h-6 rounded border border-gray-300 text-gray-400 hover:bg-gray-50 flex items-center justify-center">‹</button>
              <span class="text-xs text-gray-500">Página 1 de {{ Math.max(1, Math.ceil(detalleItems.length / 10)) }}</span>
              <button class="w-6 h-6 rounded border border-gray-300 text-gray-400 hover:bg-gray-50 flex items-center justify-center">›</button>
            </div>

            <div class="text-right space-y-1 min-w-[200px]">
              <div class="flex justify-between text-xs text-gray-600 bg-gray-50 px-4 py-1.5 rounded-lg border border-gray-100">
                <span class="font-medium">Subtotal</span>
                <span class="font-semibold text-gray-800">{{ formatMoney(subtotal) }}</span>
              </div>
              <div class="flex justify-between text-xs font-bold text-gray-900 bg-gray-100 px-4 py-1.5 rounded-lg border border-gray-200">
                <span>Total</span>
                <span>{{ formatMoney(total) }}</span>
              </div>
            </div>
          </div>
        </Card>

        <!-- Botones ATRÁS / GUARDAR -->
        <div class="flex items-center justify-between pt-1 pb-2">
          <Button
            type="button"
            variant="outline"
            size="sm"
            class="border-gray-300 text-gray-700 text-xs px-5 h-9 flex items-center gap-2 hover:bg-gray-50 rounded-lg font-semibold"
            @click="volverLista"
          >
            <ArrowLeft class="w-3.5 h-3.5" />
            ATRÁS
          </Button>

          <Button
            type="button"
            size="sm"
            class="bg-red-600 hover:bg-red-700 text-white text-xs px-6 h-9 flex items-center gap-2 rounded-lg font-semibold shadow-sm"
            @click="guardarTransferencia"
          >
            <Save class="w-3.5 h-3.5" />
            GUARDAR
          </Button>
        </div>
      </div>
    </template>

  </AdminLayout>

  <!-- ====================================================================
       MODAL: VER TRANSFERENCIA
       ==================================================================== -->
  <Teleport to="body">
    <div
      v-if="showVerModal && transferenciaVer"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <!-- Header modal -->
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <h3 class="font-bold text-sm uppercase tracking-wide">Ver Transferencia</h3>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showVerModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <div class="p-6 space-y-4">
          <!-- Observaciones + Fecha -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Observaciones:</label>
              <p class="text-xs font-medium text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                {{ transferenciaVer.observaciones || '—' }}
              </p>
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Observaciones:</label>
              <p class="text-xs font-medium text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                {{ transferenciaVer.fecha }}
              </p>
            </div>
          </div>

          <!-- Tabla de insumos del detalle -->
          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <Table>
              <TableHeader class="bg-[#232738] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-left">Insumos</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-24">Cantidad</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-20">U. M.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-24">Precio U.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 py-2 text-center w-24">Total</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="item in transferenciaVer.detalle"
                  :key="item.insumoId"
                  class="border-b border-gray-200"
                >
                  <TableCell class="py-3 px-4 text-xs font-semibold text-gray-800">{{ item.nombre }}</TableCell>
                  <TableCell class="py-3 px-3 text-xs text-gray-700 text-center">{{ item.cantidad }}</TableCell>
                  <TableCell class="py-3 px-3 text-xs text-gray-600 text-center">{{ item.um }}</TableCell>
                  <TableCell class="py-3 px-3 text-xs text-gray-700 text-center">{{ formatMoney(item.precioU) }}</TableCell>
                  <TableCell class="py-3 px-3 text-xs font-bold text-gray-900 text-center">{{ formatMoney(item.cantidad * item.precioU) }}</TableCell>
                </TableRow>

                <!-- Filas en blanco para mostrar como en la imagen -->
                <template v-if="transferenciaVer.detalle.length < 8">
                  <TableRow
                    v-for="n in (8 - transferenciaVer.detalle.length)"
                    :key="`empty-${n}`"
                    class="border-b border-gray-100"
                  >
                    <TableCell class="py-3 px-4"><span class="inline-block w-28 h-2 bg-gray-100 rounded animate-pulse"></span></TableCell>
                    <TableCell class="py-3 px-3 text-center"><span class="inline-block w-8 h-2 bg-gray-100 rounded animate-pulse mx-auto"></span></TableCell>
                    <TableCell class="py-3 px-3 text-center"><span class="inline-block w-8 h-2 bg-gray-100 rounded animate-pulse mx-auto"></span></TableCell>
                    <TableCell class="py-3 px-3 text-center"><span class="inline-block w-10 h-2 bg-gray-100 rounded animate-pulse mx-auto"></span></TableCell>
                    <TableCell class="py-3 px-3 text-center"><span class="inline-block w-10 h-2 bg-gray-100 rounded animate-pulse mx-auto"></span></TableCell>
                  </TableRow>
                </template>
              </TableBody>
            </Table>
          </div>

          <!-- Subtotal -->
          <div class="flex justify-end">
            <div class="min-w-[200px] space-y-1">
              <div class="flex justify-between text-xs text-gray-600 bg-gray-50 px-4 py-1.5 rounded-lg border border-gray-100">
                <span class="font-medium">Subtotal</span>
                <span class="font-bold text-gray-900">{{ formatMoney(verSubtotal) }}</span>
              </div>
            </div>
          </div>

          <!-- Botón ATRÁS -->
          <div class="flex justify-end pt-1">
            <Button
              size="sm"
              variant="outline"
              class="border-gray-300 text-gray-700 text-xs px-5 h-9 flex items-center gap-2 hover:bg-gray-50 rounded-lg font-semibold"
              @click="showVerModal = false"
            >
              <ArrowLeft class="w-3.5 h-3.5" />
              ATRÁS
            </Button>
          </div>
        </div>
      </div>
    </div>
  </Teleport>

  <!-- ====================================================================
       MODAL: LISTA DE INSUMOS (Selector)
       ==================================================================== -->
  <Teleport to="body">
    <div
      v-if="showListaModal"
      @click.self="showListaModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <!-- Header -->
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <h3 class="font-bold text-sm uppercase tracking-wide">Lista Insumos</h3>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showListaModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <div class="p-5 space-y-4">
          <!-- Búsqueda en modal -->
          <div class="relative">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
            <Input
              v-model="busquedaModal"
              type="text"
              placeholder="Buscar..."
              class="pl-9 h-9 text-xs rounded-lg border-gray-300"
              autofocus
            />
          </div>

          <!-- Tabla de insumos seleccionable -->
          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <Table>
              <TableHeader class="bg-[#232738] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-left">Descripción</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-36">Tipo Insumo</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="ins in catalogoPaginado"
                  :key="ins.id"
                  class="cursor-pointer border-b border-gray-200 transition-colors select-none"
                  :class="[
                    selectedInsumoId === ins.id
                      ? 'bg-blue-50 text-blue-900'
                      : 'hover:bg-gray-50/70'
                  ]"
                  @click="selectedInsumoId = ins.id"
                >
                  <TableCell class="py-3 px-4 text-xs font-semibold text-gray-800">
                    <div class="flex items-center gap-2">
                      <span
                        class="w-2 h-2 rounded-full shrink-0"
                        :class="[selectedInsumoId === ins.id ? 'bg-blue-600' : 'bg-transparent']"
                      ></span>
                      {{ ins.nombre }}
                    </div>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-center">
                    <span
                      class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold tracking-wide"
                      :class="[
                        ins.tipo === 'PREPARADO'
                          ? 'bg-blue-100 text-blue-700 border border-blue-200'
                          : 'bg-amber-50 text-amber-700 border border-amber-200'
                      ]"
                    >
                      {{ ins.tipo }}
                    </span>
                  </TableCell>
                </TableRow>

                <!-- Filas vacías de relleno (como en la imagen) -->
                <template v-if="catalogoPaginado.length < 8">
                  <TableRow
                    v-for="n in (8 - catalogoPaginado.length)"
                    :key="`pad-${n}`"
                    class="border-b border-gray-100"
                  >
                    <TableCell class="py-3 px-4"><span class="inline-block w-32 h-2 bg-gray-100 rounded animate-pulse"></span></TableCell>
                    <TableCell class="py-3 px-4 text-center"><span class="inline-block w-16 h-2 bg-gray-100 rounded animate-pulse mx-auto"></span></TableCell>
                  </TableRow>
                </template>
              </TableBody>
            </Table>
          </div>

          <!-- Paginación modal -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <select
                v-model="listaRowsPerPage"
                class="h-7 px-2 text-xs rounded border border-gray-300 bg-white text-gray-600 focus:outline-none"
              >
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
              </select>
              <button
                class="w-6 h-6 rounded border border-gray-300 text-gray-400 hover:bg-gray-50 flex items-center justify-center"
                :disabled="listaCurrentPage <= 1"
                @click="listaCurrentPage--"
              >‹</button>
              <span class="text-xs text-gray-500">Página {{ listaCurrentPage }} de {{ listaTotalPages }}</span>
              <button
                class="w-6 h-6 rounded border border-gray-300 text-gray-400 hover:bg-gray-50 flex items-center justify-center"
                :disabled="listaCurrentPage >= listaTotalPages"
                @click="listaCurrentPage++"
              >›</button>
            </div>

            <!-- Botones ATRÁS / ACEPTAR -->
            <div class="flex items-center gap-3">
              <Button
                size="sm"
                variant="outline"
                class="border-gray-300 text-gray-700 text-xs px-4 h-8 flex items-center gap-1.5 hover:bg-gray-50 rounded-lg font-semibold"
                @click="showListaModal = false"
              >
                <ArrowLeft class="w-3 h-3" />
                ATRÁS
              </Button>
              <Button
                size="sm"
                class="bg-red-600 hover:bg-red-700 text-white text-xs px-5 h-8 rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!selectedInsumoId"
                @click="aceptarInsumo"
              >
                ACEPTAR
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
  </div>
</template>
