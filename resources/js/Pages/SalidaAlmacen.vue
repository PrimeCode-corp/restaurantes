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
  Pencil,
  Trash2,
  X,
  Calendar as CalendarIcon,
  ArrowLeft,
  Save,
  Check,
  CheckCircle2,
  TriangleAlert,
  ChevronDown,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// MODO VISTA: 'lista' | 'nuevo'
// -------------------------------------------------------------------
const viewMode = ref('lista')

// -------------------------------------------------------------------
// OPCIONES
// -------------------------------------------------------------------
const comprobantes = [
  'Selecciona',
  'BOLETA DE VENTA',
  'FACTURA',
  'GUÍA DE REMISIÓN',
  'NOTA DE SALIDA',
]

const almacenes = [
  'Selecciona',
  'ALMACEN GENERAL',
  'CAJA PRINCIPAL',
  'COCINA',
  'BARRA',
]

// -------------------------------------------------------------------
// CATÁLOGO DE PROVEEDORES (Para modal Proveedores)
// -------------------------------------------------------------------
const catalogoProveedores = ref([
  {
    codProveedor: 'P00003',
    proveedor: 'ABARROTES EL MOSHAQUITO S.A.C.',
    direccion: 'JR. JIRON 123',
    numDocumento: '01046282837',
    tipoDoc: 'RUC',
    telefono: '000000000',
  },
  {
    codProveedor: 'P00004',
    proveedor: 'DISTRIBUIDORA GASTRONOMICA S.R.L.',
    direccion: 'AV. LIMA 450',
    numDocumento: '20489563211',
    tipoDoc: 'RUC',
    telefono: '942851234',
  },
  {
    codProveedor: 'P00005',
    proveedor: 'COMERCIALIZADORA DEL ORIENTE',
    direccion: 'JR. SAN MARTIN 890',
    numDocumento: '10458963214',
    tipoDoc: 'RUC',
    telefono: '978546123',
  },
])

// -------------------------------------------------------------------
// CATÁLOGO DE INSUMOS Y PRODUCTOS (Para modal de añadir al detalle)
// -------------------------------------------------------------------
const catalogoInsumosProductos = ref([
  { id: 1, descripcion: 'CECINA', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO', um: 'UNIDAD', precioU: 5.00 },
  { id: 2, descripcion: 'LECHE EVAPORADA', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO', um: 'LT', precioU: 4.20 },
  { id: 3, descripcion: 'POLLO ENTERO', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO', um: 'KG', precioU: 10.00 },
  { id: 4, descripcion: 'AZÚCAR RUBIA', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO', um: 'KG', precioU: 3.50 },
  { id: 5, descripcion: 'QUESO MOZZARELLA 500G', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO', um: 'KG', precioU: 28.50 },
  { id: 6, descripcion: 'MASA MADRE', categoria: 'INSUMOS', tipoInsumo: 'PREPARADO', um: 'UND', precioU: 3.00 },
  { id: 7, descripcion: 'ACEITE DE OLIVA EXTRA', categoria: 'INSUMOS', tipoInsumo: 'COMPRADO', um: 'LT', precioU: 32.00 },
])

// -------------------------------------------------------------------
// LISTADO PRINCIPAL DE SALIDAS DE ALMACÉN (Captura 1)
// -------------------------------------------------------------------
const salidas = ref([
  {
    id: 1,
    fecha: '00/00/0000',
    documento: '01046282837',
    tipoDoc: 'RUC',
    tipoPago: 'FACTURA',
    codProveedor: 'P000001',
    proveedor: 'ABARROTES EL MOSHAQUITO S.A.C.',
    monto: 110.00,
    almacen: 'ALMACEN GENERAL',
    observaciones: 'SALIDA DE INSUMOS',
    detalle: [
      { id: 1, descripcion: 'CECINA', cantidad: 5, um: 'UND', precioU: 10.00, total: 240.00 },
      { id: 2, descripcion: 'LECHE EVAPORADA', cantidad: 10, um: 'LT', precioU: 4.20, total: 42.00 },
      { id: 3, descripcion: 'POLLO ENTERO', cantidad: 6, um: 'KG', precioU: 10.00, total: 60.00 },
      { id: 4, descripcion: 'AZÚCAR RUBIA', cantidad: 8, um: 'KG', precioU: 3.50, total: 28.00 },
    ],
  },
  {
    id: 2,
    fecha: '18/08/2026',
    documento: '01046282837',
    tipoDoc: 'RUC',
    tipoPago: 'BOLETA DE VENTA',
    codProveedor: 'P00004',
    proveedor: 'ABARROTES EL MOSHAQUITO S.A.C.',
    monto: 152.00,
    almacen: 'CAJA PRINCIPAL',
    observaciones: 'DESPACHO DE INSUMOS COCINA',
    detalle: [
      { id: 1, descripcion: 'CECINA', cantidad: 5, um: 'UNIDAD', precioU: 5.00, total: 25.00 },
      { id: 2, descripcion: 'LECHE EVAPORADA', cantidad: 10, um: 'LT', precioU: 4.20, total: 42.00 },
    ],
  },
])

// -------------------------------------------------------------------
// BÚSQUEDA Y PAGINACIÓN LISTA PRINCIPAL
// -------------------------------------------------------------------
const searchQuery = ref('')
const rowsPerPage = ref(10)
const currentPage = ref(1)

const filteredSalidas = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  if (!q) return salidas.value
  return salidas.value.filter(
    (s) =>
      s.documento.toLowerCase().includes(q) ||
      s.codProveedor.toLowerCase().includes(q) ||
      s.proveedor.toLowerCase().includes(q) ||
      s.tipoPago.toLowerCase().includes(q) ||
      s.almacen.toLowerCase().includes(q)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredSalidas.value.length / rowsPerPage.value)))
const paginatedSalidas = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filteredSalidas.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// FORMULARIO NUEVA SALIDA ALMACÉN (Captura 2 y 3)
// -------------------------------------------------------------------
const nuevoForm = ref({
  codProveedor: 'P0004',
  proveedor: 'ABARROTES EL MOSHAQUITO S.A.C.',
  tipoDoc: 'RUC',
  numeroDocumento: '01046282837',
  fecha: '',
  fechaRaw: '2026-08-01',
  observacion: '',
  comprobante: 'BOLETA DE VENTA',
  almacen: 'CAJA PRINCIPAL',
})

const detalleItems = ref([
  { id: 1, descripcion: 'CECINA', cantidad: 5, um: 'UNIDAD', precioU: 5.00, total: 25.00 },
  { id: 2, descripcion: 'LECHE EVAPORADA', cantidad: 10, um: 'LT', precioU: 4.20, total: 42.00 },
])

const subtotalNuevo = computed(() =>
  detalleItems.value.reduce((acc, item) => acc + item.cantidad * item.precioU, 0)
)
const totalNuevo = computed(() => subtotalNuevo.value)

function formatMoney(num) {
  return Number(num || 0).toFixed(2)
}

function irANuevo() {
  viewMode.value = 'nuevo'
}

function volverLista() {
  viewMode.value = 'lista'
}

function quitarDetalle(index) {
  detalleItems.value.splice(index, 1)
}

function guardarSalida() {
  const newId = Date.now()
  salidas.value.unshift({
    id: newId,
    fecha: nuevoForm.value.fechaRaw ? nuevoForm.value.fechaRaw.split('-').reverse().join('/') : '00/00/0000',
    documento: nuevoForm.value.numeroDocumento,
    tipoDoc: nuevoForm.value.tipoDoc,
    tipoPago: nuevoForm.value.comprobante === 'Selecciona' ? 'BOLETA DE VENTA' : nuevoForm.value.comprobante,
    codProveedor: nuevoForm.value.codProveedor,
    proveedor: nuevoForm.value.proveedor,
    monto: totalNuevo.value || 152.00,
    almacen: nuevoForm.value.almacen === 'Selecciona' ? 'CAJA PRINCIPAL' : nuevoForm.value.almacen,
    observaciones: nuevoForm.value.observacion || 'SALIDA DE INSUMOS',
    detalle: JSON.parse(JSON.stringify(detalleItems.value)),
  })
  toastMessage.value = '¡Salida de almacén guardada exitosamente!'
  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
  viewMode.value = 'lista'
}

// -------------------------------------------------------------------
// MODAL: VER SALIDA ALMACÉN (Captura 4: Salida - Ver)
// -------------------------------------------------------------------
const showVerModal = ref(false)
const salidaSeleccionada = ref(null)

function openVerModal(salida) {
  salidaSeleccionada.value = salida
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL: PROVEEDORES (Captura 5)
// -------------------------------------------------------------------
const showModalProveedores = ref(false)
const busquedaProveedor = ref('')
const selectedProveedorCod = ref('P00003')
const provCurrentPage = ref(1)
const provRowsPerPage = ref(8)

const proveedoresFiltrados = computed(() => {
  const q = busquedaProveedor.value.toLowerCase().trim()
  if (!q) return catalogoProveedores.value
  return catalogoProveedores.value.filter(
    (p) =>
      p.proveedor.toLowerCase().includes(q) ||
      p.codProveedor.toLowerCase().includes(q) ||
      p.numDocumento.includes(q)
  )
})

const provTotalPages = computed(() =>
  Math.max(1, Math.ceil(proveedoresFiltrados.value.length / provRowsPerPage.value))
)

const proveedoresPaginados = computed(() => {
  const start = (provCurrentPage.value - 1) * provRowsPerPage.value
  return proveedoresFiltrados.value.slice(start, start + provRowsPerPage.value)
})

function openModalProveedores() {
  busquedaProveedor.value = ''
  selectedProveedorCod.value = nuevoForm.value.codProveedor || 'P00003'
  provCurrentPage.value = 1
  showModalProveedores.value = true
}

function aceptarProveedor() {
  const prov = catalogoProveedores.value.find((p) => p.codProveedor === selectedProveedorCod.value)
  if (prov) {
    nuevoForm.value.codProveedor = prov.codProveedor
    nuevoForm.value.proveedor = prov.proveedor
    nuevoForm.value.tipoDoc = prov.tipoDoc
    nuevoForm.value.numeroDocumento = prov.numDocumento
  }
  showModalProveedores.value = false
}

// -------------------------------------------------------------------
// MODAL: BUSCADOR INSUMOS / PRODUCTOS PARA AGREGAR
// -------------------------------------------------------------------
const showModalInsumos = ref(false)
const busquedaInsumo = ref('')
const selectedInsumoId = ref(1)
const insCurrentPage = ref(1)
const insRowsPerPage = ref(10)

const insumosFiltrados = computed(() => {
  const q = busquedaInsumo.value.toLowerCase().trim()
  if (!q) return catalogoInsumosProductos.value
  return catalogoInsumosProductos.value.filter(
    (i) =>
      i.descripcion.toLowerCase().includes(q) ||
      i.categoria.toLowerCase().includes(q) ||
      i.tipoInsumo.toLowerCase().includes(q)
  )
})

const insTotalPages = computed(() =>
  Math.max(1, Math.ceil(insumosFiltrados.value.length / insRowsPerPage.value))
)

const insumosPaginados = computed(() => {
  const start = (insCurrentPage.value - 1) * insRowsPerPage.value
  return insumosFiltrados.value.slice(start, start + insRowsPerPage.value)
})

function openModalInsumos() {
  busquedaInsumo.value = ''
  selectedInsumoId.value = 1
  insCurrentPage.value = 1
  showModalInsumos.value = true
}

function aceptarInsumo() {
  const item = catalogoInsumosProductos.value.find((i) => i.id === selectedInsumoId.value)
  if (item) {
    const existing = detalleItems.value.find((d) => d.id === item.id)
    if (!existing) {
      detalleItems.value.push({
        id: item.id,
        descripcion: item.descripcion,
        cantidad: 1,
        um: item.um,
        precioU: item.precioU,
        total: item.precioU,
      })
    }
  }
  showModalInsumos.value = false
}

// -------------------------------------------------------------------
// ELIMINAR REGISTRO PRINCIPAL
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const salidaAEliminar = ref(null)
const showSuccessToast = ref(false)
const toastMessage = ref('')

function openDeleteModal(s) {
  salidaAEliminar.value = s
  showDeleteModal.value = true
}

function confirmDelete() {
  if (!salidaAEliminar.value) return
  salidas.value = salidas.value.filter((s) => s.id !== salidaAEliminar.value.id)
  showDeleteModal.value = false
  salidaAEliminar.value = null
  toastMessage.value = 'Registro de salida eliminado exitosamente'
  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
}
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="salida-almacen">
    <!-- =============================================================== -->
    <!-- 1. VISTA LISTA PRINCIPAL (Captura 1)                             -->
    <!-- =============================================================== -->
    <div v-if="viewMode === 'lista'" class="space-y-4">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Salida Almacén
        </h1>
        <transition name="fade">
          <div
            v-if="showSuccessToast"
            class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg shadow-2xs"
          >
            <CheckCircle2 class="w-4 h-4 text-emerald-600" />
            <span>{{ toastMessage }}</span>
          </div>
        </transition>
      </div>

      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-7 flex flex-col justify-between min-h-[560px]">
        <div>
          <!-- Barra Superior: Buscador + Botón + NUEVO -->
          <div class="flex items-center justify-between gap-4 mb-6">
            <div class="relative w-full max-w-sm">
              <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar..."
                class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-400"
              />
            </div>

            <!-- Botón + NUEVO en tono índigo oscuro #24265D -->
            <button
              type="button"
              class="bg-[#24265D] hover:bg-[#1E204E] text-white px-5 py-2 h-9 rounded-md text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-all cursor-pointer select-none shrink-0"
              @click="irANuevo"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </button>
          </div>

          <!-- TABLA DE SALIDAS DE ALMACÉN -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center">Fecha</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center">Documento</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center">Tipo Doc.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center">Tipo Pago</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center">Cod. Proveedor</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center">Monto</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center">Almacén</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-3 py-2 text-center w-28">Acciones</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="s in paginatedSalidas"
                  :key="s.id"
                  class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors"
                >
                  <TableCell class="py-3 px-3 text-xs text-center font-mono text-gray-700">
                    {{ s.fecha }}
                  </TableCell>
                  <TableCell class="py-3 px-3 text-xs text-center font-mono text-gray-700">
                    {{ s.documento }}
                  </TableCell>
                  <TableCell class="py-3 px-3 text-xs text-center font-semibold text-gray-700">
                    {{ s.tipoDoc }}
                  </TableCell>
                  <TableCell class="py-3 px-3 text-xs text-center text-gray-700">
                    {{ s.tipoPago }}
                  </TableCell>
                  <TableCell class="py-3 px-3 text-xs text-center font-mono text-gray-700">
                    {{ s.codProveedor }}
                  </TableCell>
                  <TableCell class="py-3 px-3 text-xs text-center font-bold text-gray-900 font-mono">
                    {{ formatMoney(s.monto) }}
                  </TableCell>
                  <TableCell class="py-3 px-3 text-xs text-center text-gray-700 font-medium">
                    {{ s.almacen }}
                  </TableCell>
                  <!-- Acciones: Ver (Azul), Editar (Verde), Eliminar (Rojo) -->
                  <TableCell class="py-3 px-3 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        type="button"
                        class="w-7 h-7 rounded-md bg-[#EBF3FC] hover:bg-[#D7E9FB] text-[#2B77D6] flex items-center justify-center transition-colors cursor-pointer"
                        title="Ver detalle"
                        @click="openVerModal(s)"
                      >
                        <Eye class="w-3.5 h-3.5" />
                      </button>
                      <button
                        type="button"
                        class="w-7 h-7 rounded-md bg-[#EAF7EE] hover:bg-[#D4EEDC] text-[#34A853] flex items-center justify-center transition-colors cursor-pointer"
                        title="Editar"
                        @click="irANuevo"
                      >
                        <Pencil class="w-3.5 h-3.5" />
                      </button>
                      <button
                        type="button"
                        class="w-7 h-7 rounded-md bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors cursor-pointer"
                        title="Eliminar"
                        @click="openDeleteModal(s)"
                      >
                        <Trash2 class="w-3.5 h-3.5" />
                      </button>
                    </div>
                  </TableCell>
                </TableRow>

                <!-- Filas Skeleton de relleno idénticas a la captura -->
                <TableRow
                  v-for="i in Math.max(0, 10 - paginatedSalidas.length)"
                  :key="'skel-s-' + i"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3.5 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-14 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-20 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-10 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-16 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-14 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-12 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-24 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-3 text-center">
                    <div class="flex items-center justify-center gap-1.5 opacity-60">
                      <div class="w-7 h-7 rounded-md bg-[#EBF3FC] flex items-center justify-center text-[#2B77D6]"><Eye class="w-3.5 h-3.5" /></div>
                      <div class="w-7 h-7 rounded-md bg-[#EAF7EE] flex items-center justify-center text-[#34A853]"><Pencil class="w-3.5 h-3.5" /></div>
                      <div class="w-7 h-7 rounded-md bg-[#FDEEED] flex items-center justify-center text-[#E53935]"><Trash2 class="w-3.5 h-3.5" /></div>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <!-- Paginador inferior -->
        <div class="pt-6 flex items-center justify-between">
          <DataTablePagination
            :current-page="currentPage"
            :total-pages="totalPages"
            :rows-per-page="rowsPerPage"
            :rows-per-page-options="[5, 10, 20]"
            @update:current-page="currentPage = $event"
            @update:rows-per-page="rowsPerPage = $event"
          />
          <span class="text-xs text-gray-400 font-medium">
            Total {{ filteredSalidas.length }} salidas
          </span>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- 2. VISTA FORMULARIO NUEVA SALIDA ALMACÉN (Captura 2 y 3)        -->
    <!-- =============================================================== -->
    <div v-else-if="viewMode === 'nuevo'" class="space-y-4">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Salida Almacén
        </h1>
        <Button
          variant="outline"
          size="sm"
          class="border-gray-300 text-gray-700 text-xs px-4 h-8 flex items-center gap-1.5 hover:bg-gray-50 rounded-lg"
          @click="volverLista"
        >
          <ArrowLeft class="w-3.5 h-3.5" />
          <span>Volver al listado</span>
        </Button>
      </div>

      <!-- Cabecera de Datos del Documento y Proveedor -->
      <div class="space-y-4">
        <!-- Fila 1: Cod. Proveedor, Fecha, Proveedor, Tipo Documento, Número Documento -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5">
          <!-- Cod. Proveedor con lupa que abre Modal Proveedores -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Cod. Proveedor:</label>
            <div class="relative flex items-center">
              <Input
                v-model="nuevoForm.codProveedor"
                type="text"
                placeholder="Buscar..."
                readonly
                class="h-9 pr-8 text-xs rounded-lg border-gray-300 bg-white font-mono cursor-pointer"
                @click="openModalProveedores"
              />
              <button
                type="button"
                class="absolute right-2 text-[#3E55B3] hover:text-[#283875] p-1 cursor-pointer"
                @click="openModalProveedores"
              >
                <Search class="w-4 h-4 stroke-[2.5]" />
              </button>
            </div>
          </div>

          <!-- Fecha con icono calendario -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Fecha:</label>
            <div class="relative">
              <Input
                type="text"
                readonly
                :value="nuevoForm.fechaRaw ? nuevoForm.fechaRaw.split('-').reverse().join('/') : '00/00/0000'"
                placeholder="-"
                class="h-9 pr-8 text-xs rounded-lg border-gray-300 bg-white font-mono"
              />
              <CalendarIcon class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              <input
                v-model="nuevoForm.fechaRaw"
                type="date"
                class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
              />
            </div>
          </div>

          <!-- Proveedor (Razón Social en gris suave) -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Proveedor:</label>
            <Input
              :value="nuevoForm.proveedor || '-'"
              readonly
              placeholder="-"
              class="h-9 text-xs rounded-lg border-gray-200 bg-[#E9ECEF]/90 text-gray-800 truncate"
            />
          </div>

          <!-- Tipo Documento (en gris suave) -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Tipo Documento:</label>
            <Input
              :value="nuevoForm.tipoDoc || '-'"
              readonly
              placeholder="-"
              class="h-9 text-xs rounded-lg border-gray-200 bg-[#E9ECEF]/90 text-gray-800 font-semibold"
            />
          </div>

          <!-- Número Documento (en gris suave) -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Número Documento:</label>
            <Input
              :value="nuevoForm.numeroDocumento || '-'"
              readonly
              placeholder="-"
              class="h-9 text-xs rounded-lg border-gray-200 bg-[#E9ECEF]/90 text-gray-800 font-mono"
            />
          </div>
        </div>

        <!-- Fila 2: Observación, Comprobante, Almacén -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">
          <!-- Observación (Span 6) -->
          <div class="md:col-span-6 space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Observación:</label>
            <Input
              v-model="nuevoForm.observacion"
              type="text"
              placeholder="Escribe aquí..."
              class="h-9 text-xs rounded-lg border-gray-300 bg-white"
            />
          </div>

          <!-- Comprobante (Span 3) -->
          <div class="md:col-span-3 space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Comprobante:</label>
            <div class="relative">
              <select
                v-model="nuevoForm.comprobante"
                class="w-full h-9 pl-3 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 appearance-none focus:outline-none focus:ring-1 focus:ring-gray-400 cursor-pointer shadow-2xs"
              >
                <option v-for="c in comprobantes" :key="c" :value="c">{{ c }}</option>
              </select>
              <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
            </div>
          </div>

          <!-- Almacén (Span 3) -->
          <div class="md:col-span-3 space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Almacén:</label>
            <div class="relative">
              <select
                v-model="nuevoForm.almacen"
                class="w-full h-9 pl-3 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 appearance-none focus:outline-none focus:ring-1 focus:ring-gray-400 cursor-pointer shadow-2xs"
              >
                <option v-for="a in almacenes" :key="a" :value="a">{{ a }}</option>
              </select>
              <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
            </div>
          </div>
        </div>
      </div>

      <!-- Tarjeta: Detalle de Transferencia / Salida -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 space-y-4">
        <h2 class="text-sm font-bold text-gray-900 tracking-tight">
          Detalle de Transferencia
        </h2>

        <!-- Buscador de Insumos + Botón verde + AÑADIR -->
        <div class="flex items-center gap-3">
          <div class="relative flex-1">
            <input
              type="text"
              readonly
              placeholder="Buscar insumos..."
              class="w-full h-9 pl-3.5 pr-8 text-xs rounded-lg border border-gray-300 bg-white placeholder:text-gray-400 cursor-pointer shadow-2xs focus:outline-none focus:ring-1 focus:ring-gray-400"
              @click="openModalInsumos"
            />
            <Search class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#3E55B3] stroke-[2.5] pointer-events-none" />
          </div>

          <button
            type="button"
            class="bg-[#12A347] hover:bg-[#0E8A3B] text-white px-5 py-2 h-9 rounded-md text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-all cursor-pointer shrink-0"
            @click="openModalInsumos"
          >
            <Plus class="w-4 h-4 stroke-[3]" />
            <span>AÑADIR</span>
          </button>
        </div>

        <!-- Tabla de Detalle de Items -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
          <Table class="w-full border-collapse">
            <TableHeader class="bg-[#20233B] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-10 px-4 text-left w-[35%]">Producto</TableHead>
                <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[18%]">Cantidad</TableHead>
                <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">U. M.</TableHead>
                <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[15%]">Precio U.</TableHead>
                <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[15%]">Total Precio</TableHead>
                <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[5%]">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="(item, idx) in detalleItems"
                :key="item.id"
                class="hover:bg-gray-50/70 border-b border-gray-100 transition-colors"
              >
                <!-- Producto -->
                <TableCell class="py-2.5 px-4 text-xs font-semibold text-gray-800">
                  {{ item.descripcion }}
                </TableCell>
                <!-- Selector numérico con flechitas arriba/abajo -->
                <TableCell class="py-2.5 px-4 text-center">
                  <div class="inline-flex items-center border border-gray-300 rounded-md bg-white overflow-hidden px-2 py-0.5">
                    <input
                      v-model.number="item.cantidad"
                      type="number"
                      min="1"
                      class="w-12 h-6 text-center text-xs font-semibold text-gray-800 focus:outline-none"
                    />
                  </div>
                </TableCell>
                <!-- U.M. -->
                <TableCell class="py-2.5 px-4 text-xs text-center font-mono text-gray-700">
                  {{ item.um }}
                </TableCell>
                <!-- Precio Unitario -->
                <TableCell class="py-2.5 px-4 text-xs text-center font-mono text-gray-700">
                  {{ formatMoney(item.precioU) }}
                </TableCell>
                <!-- Total Precio -->
                <TableCell class="py-2.5 px-4 text-xs text-center font-mono font-bold text-gray-800">
                  {{ formatMoney(item.cantidad * item.precioU) }}
                </TableCell>
                <!-- Botón Eliminar Fila rojo suave con icono papelera -->
                <TableCell class="py-2.5 px-4 text-center">
                  <button
                    type="button"
                    class="w-7 h-7 rounded-md bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors mx-auto cursor-pointer"
                    title="Eliminar fila"
                    @click="quitarDetalle(idx)"
                  >
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </TableCell>
              </TableRow>

              <!-- Skeleton rows de relleno visual para igualar la captura 3 -->
              <TableRow
                v-for="k in Math.max(0, 4 - detalleItems.length)"
                :key="'skel-det-s-' + k"
                class="border-b border-gray-100"
              >
                <TableCell class="py-3 px-4"><div class="h-2.5 bg-gray-200 rounded-full w-44"></div></TableCell>
                <TableCell class="py-3 px-4 text-center">
                  <div class="inline-flex items-center border border-gray-200 rounded-md bg-white px-2 py-0.5">
                    <span class="w-8 text-center text-xs text-gray-400 font-mono">{{ k === 1 ? '6' : '8' }}</span>
                  </div>
                </TableCell>
                <TableCell class="py-3 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-10 mx-auto"></div></TableCell>
                <TableCell class="py-3 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-12 mx-auto"></div></TableCell>
                <TableCell class="py-3 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-14 mx-auto"></div></TableCell>
                <TableCell class="py-3 px-4 text-center">
                  <div class="w-7 h-7 rounded-md bg-[#FDEEED] flex items-center justify-center text-[#E53935] mx-auto opacity-70">
                    <Trash2 class="w-3.5 h-3.5" />
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Paginación Detalle + Cuadro de Subtotal / Total -->
        <div class="pt-2 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
          <!-- Paginación estilo 10 v, flechas -->
          <div class="flex items-center gap-2 select-none pt-2">
            <div class="h-7 px-2.5 border border-gray-300 rounded text-xs flex items-center gap-1 bg-white text-gray-700">
              <span>10</span>
              <ChevronDown class="w-3 h-3 text-gray-500" />
            </div>
            <button class="w-6 h-6 rounded border border-gray-300 text-gray-500 hover:bg-gray-100 flex items-center justify-center text-xs">
              ‹
            </button>
            <span class="text-xs text-gray-600 font-medium">Página 1 de 1</span>
            <button class="w-6 h-6 rounded border border-gray-300 text-gray-500 hover:bg-gray-100 flex items-center justify-center text-xs">
              ›
            </button>
          </div>

          <!-- Cuadro de Totales (Subtotal y Total enmarcado idéntico a las capturas) -->
          <div class="w-full sm:w-64 border border-gray-200 rounded-xl p-3.5 bg-white shadow-2xs space-y-2">
            <div class="flex items-center justify-between text-xs text-gray-700 pb-1.5 border-b border-gray-100">
              <span class="font-medium">Subtotal:</span>
              <span class="font-mono font-bold text-gray-900">{{ formatMoney(subtotalNuevo) }}</span>
            </div>
            <div class="flex items-center justify-between text-xs text-gray-900 pt-0.5">
              <span class="font-bold">Total:</span>
              <span class="font-mono font-bold text-sm text-gray-900">{{ formatMoney(totalNuevo) }}</span>
            </div>
          </div>
        </div>

        <!-- Botones de Acción Inferiores -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
          <Button
            type="button"
            variant="outline"
            size="sm"
            class="border-gray-300 text-gray-700 text-xs px-5 h-9 flex items-center gap-1.5 hover:bg-gray-50 rounded-lg font-bold"
            @click="volverLista"
          >
            <ArrowLeft class="w-3.5 h-3.5 stroke-[2.5]" />
            <span>ATRÁS</span>
          </Button>

          <Button
            type="button"
            size="sm"
            class="bg-[#F0302E] hover:bg-[#DC2626] text-white text-xs px-6 h-9 flex items-center gap-1.5 rounded-lg font-bold shadow-sm"
            @click="guardarSalida"
          >
            <Save class="w-3.5 h-3.5 stroke-[2.5]" />
            <span>GUARDAR</span>
          </Button>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- 3. MODAL: VER SALIDA ALMACÉN (Captura 4: Salida - Ver)          -->
    <!-- =============================================================== -->
    <div
      v-if="showVerModal && salidaSeleccionada"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden animate-in zoom-in-95 duration-150 flex flex-col">
        <!-- Header del Modal -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Ver Salida Almacén
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md"
            @click="showVerModal = false"
          >
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <!-- Info superior: Observaciones y Fecha -->
        <div class="p-6 space-y-4">
          <div class="flex items-center justify-between text-xs">
            <div>
              <span class="font-bold text-gray-900">Observaciones: </span>
              <span class="text-gray-600 uppercase">{{ salidaSeleccionada.observaciones || 'SALIDA DE INSUMOS' }}</span>
            </div>
            <div>
              <span class="font-bold text-gray-900">Observaciones: </span>
              <span class="font-mono text-gray-600">{{ salidaSeleccionada.fecha }}</span>
            </div>
          </div>

          <!-- Tabla de Insumos Detalle -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-left w-[40%]">Insumos</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 text-center w-[15%]">Cantidad</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 text-center w-[15%]">U. M.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 text-center w-[15%]">Precio U.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 text-center w-[15%]">Total</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="det in salidaSeleccionada.detalle"
                  :key="det.id"
                  class="border-b border-gray-100 hover:bg-gray-50/70"
                >
                  <TableCell class="py-2.5 px-4 text-xs font-semibold text-gray-800">
                    {{ det.descripcion }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ det.cantidad }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ det.um }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ formatMoney(det.precioU) }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono font-bold text-gray-900">
                    {{ formatMoney(det.total) }}
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <!-- Footer Modal: Botón ATRÁS -->
        <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end">
          <button
            type="button"
            class="h-9 px-5 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
            @click="showVerModal = false"
          >
            <ArrowLeft class="w-3.5 h-3.5 stroke-[2.5]" />
            <span>ATRÁS</span>
          </button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- 4. MODAL: PROVEEDORES (Captura 5)                               -->
    <!-- =============================================================== -->
    <div
      v-if="showModalProveedores"
      @click.self="showModalProveedores = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-4xl overflow-hidden animate-in zoom-in-95 duration-150 flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Proveedores
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md"
            @click="showModalProveedores = false"
          >
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-6 space-y-4">
          <!-- Buscador -->
          <div class="relative w-full max-w-sm">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="busquedaProveedor"
              type="text"
              placeholder="Buscar..."
              class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs"
            />
          </div>

          <!-- Tabla de Proveedores Modal -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 text-center">Cod. Proveedor</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center">Proveedor</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center">Dirección</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 text-center">N° Documento</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-2 text-center">Tipo Doc.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-3 text-center">Teléfono</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="p in proveedoresPaginados"
                  :key="p.codProveedor"
                  class="cursor-pointer border-b border-gray-100 transition-colors select-none"
                  :class="[
                    selectedProveedorCod === p.codProveedor
                      ? 'bg-blue-50/80 font-bold text-[#24265D]'
                      : 'hover:bg-gray-50/70'
                  ]"
                  @click="selectedProveedorCod = p.codProveedor"
                >
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono">
                    <div class="flex items-center justify-center gap-1.5">
                      <span v-if="selectedProveedorCod === p.codProveedor" class="w-1.5 h-1.5 rounded-full bg-[#3E55B3]"></span>
                      <span>{{ p.codProveedor }}</span>
                    </div>
                  </TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center text-gray-800">{{ p.proveedor }}</TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center text-gray-600">{{ p.direccion }}</TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">{{ p.numDocumento }}</TableCell>
                  <TableCell class="py-2.5 px-2 text-xs text-center font-semibold text-gray-700">{{ p.tipoDoc }}</TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">{{ p.telefono }}</TableCell>
                </TableRow>

                <!-- Skeleton rows de relleno -->
                <TableRow
                  v-for="n in Math.max(0, 4 - proveedoresPaginados.length)"
                  :key="'skel-ps-' + n"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-12 mx-auto"></div></TableCell>
                  <TableCell class="py-3 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-36 mx-auto"></div></TableCell>
                  <TableCell class="py-3 px-4 text-center"><div class="h-2 bg-gray-200 rounded-full w-24 mx-auto"></div></TableCell>
                  <TableCell class="py-3 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-20 mx-auto"></div></TableCell>
                  <TableCell class="py-3 px-2 text-center"><div class="h-2 bg-gray-200 rounded-full w-8 mx-auto"></div></TableCell>
                  <TableCell class="py-3 px-3 text-center"><div class="h-2 bg-gray-200 rounded-full w-16 mx-auto"></div></TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginador del Modal -->
          <div class="pt-1">
            <DataTablePagination
              :current-page="provCurrentPage"
              :total-pages="provTotalPages"
              :rows-per-page="provRowsPerPage"
              :rows-per-page-options="[8, 16, 24]"
              @update:current-page="provCurrentPage = $event"
              @update:rows-per-page="provRowsPerPage = $event"
            />
          </div>
        </div>

        <!-- Footer Modal Proveedores: [+ NUEVO PRODUCTO] a la izquierda y [ATRÁS] [ACEPTAR] a la derecha -->
        <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-between">
          <button
            type="button"
            class="h-9 px-4 border border-[#3E55B3] text-[#3E55B3] hover:bg-[#EEF2FF] rounded-lg text-xs font-bold tracking-wide flex items-center gap-1.5 transition-colors cursor-pointer"
          >
            <Plus class="w-3.5 h-3.5 stroke-[2.5]" />
            <span>NUEVO PRODUCTO</span>
          </button>

          <div class="flex items-center gap-3">
            <button
              type="button"
              class="h-9 px-4 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
              @click="showModalProveedores = false"
            >
              <ArrowLeft class="w-3.5 h-3.5 stroke-[2.5]" />
              <span>ATRÁS</span>
            </button>
            <button
              type="button"
              class="h-9 px-5 bg-[#F0302E] hover:bg-[#DC2626] text-white rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer"
              @click="aceptarProveedor"
            >
              <Check class="w-4 h-4 stroke-[3]" />
              <span>ACEPTAR</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- 5. MODAL: INSUMOS Y PRODUCTOS PARA AÑADIR                       -->
    <!-- =============================================================== -->
    <div
      v-if="showModalInsumos"
      @click.self="showModalInsumos = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-xl overflow-hidden animate-in zoom-in-95 duration-150 flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Insumos y Productos
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md"
            @click="showModalInsumos = false"
          >
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-6 space-y-4">
          <!-- Buscador -->
          <div class="relative w-full max-w-sm">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="busquedaInsumo"
              type="text"
              placeholder="Buscar..."
              class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs"
            />
          </div>

          <!-- Tabla Insumos y Productos -->
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
                  v-for="ins in insumosPaginados"
                  :key="ins.id"
                  class="cursor-pointer border-b border-gray-100 transition-colors select-none"
                  :class="[
                    selectedInsumoId === ins.id
                      ? 'bg-blue-50/80 font-bold text-[#24265D]'
                      : 'hover:bg-gray-50/70'
                  ]"
                  @click="selectedInsumoId = ins.id"
                >
                  <TableCell class="py-2.5 px-4 text-xs text-center">
                    <div class="flex items-center justify-center gap-1.5">
                      <span v-if="selectedInsumoId === ins.id" class="w-1.5 h-1.5 rounded-full bg-[#3E55B3]"></span>
                      <span class="text-gray-800">{{ ins.descripcion }}</span>
                    </div>
                  </TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center font-semibold text-gray-600 font-mono">
                    {{ ins.categoria }}
                  </TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center font-mono text-gray-600">
                    {{ ins.tipoInsumo }}
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginador del Modal -->
          <div class="pt-1">
            <DataTablePagination
              :current-page="insCurrentPage"
              :total-pages="insTotalPages"
              :rows-per-page="insRowsPerPage"
              :rows-per-page-options="[10, 20]"
              @update:current-page="insCurrentPage = $event"
              @update:rows-per-page="insRowsPerPage = $event"
            />
          </div>
        </div>

        <!-- Footer Modal Insumos: [ATRÁS] [ACEPTAR] -->
        <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end gap-3">
          <button
            type="button"
            class="h-9 px-4 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
            @click="showModalInsumos = false"
          >
            <ArrowLeft class="w-3.5 h-3.5 stroke-[2.5]" />
            <span>ATRÁS</span>
          </button>
          <button
            type="button"
            class="h-9 px-5 bg-[#F0302E] hover:bg-[#DC2626] text-white rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer"
            @click="aceptarInsumo"
          >
            <Check class="w-4 h-4 stroke-[3]" />
            <span>ACEPTAR</span>
          </button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL: ELIMINAR SALIDA                                          -->
    <!-- =============================================================== -->
    <div
      v-if="showDeleteModal && salidaAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center space-y-4 animate-in zoom-in-95 duration-150">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
          <TriangleAlert class="w-6 h-6 stroke-[2]" />
        </div>
        <div>
          <h3 class="font-bold text-base text-gray-900">¿Eliminar Salida de Almacén?</h3>
          <p class="text-xs text-gray-500 mt-1 leading-relaxed">
            ¿Está seguro de eliminar el registro <strong class="text-gray-800">{{ salidaAEliminar.documento }}</strong>? Esta acción no se puede deshacer.
          </p>
        </div>
        <div class="flex items-center justify-center gap-3 pt-2">
          <Button
            variant="outline"
            size="sm"
            class="rounded-lg text-xs px-4"
            @click="showDeleteModal = false"
          >
            Cancelar
          </Button>
          <Button
            size="sm"
            class="bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs px-4"
            @click="confirmDelete"
          >
            Sí, eliminar
          </Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
