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
  Eye,
  Trash2,
  Printer,
  AlertCircle,
  X,
  ArrowLeft,
  ChevronDown,
  CheckCircle2,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// TARJETAS SUPERIORES DE RESUMEN (Captura: 190210.png)
// -------------------------------------------------------------------
const summaryStats = ref({
  cajasAbiertas: 1,
  aperturaCaja: 100.00,
  ventasTotales: 1320.00,
  diferenciaAcumulada: -4.50,
})

// -------------------------------------------------------------------
// LISTADO PRINCIPAL DE REGISTROS DE CAJA
// -------------------------------------------------------------------
const registrosCaja = ref([
  {
    id: 1,
    caja: 'CAJA PRINCIPAL',
    fechaHora: '00/00/0000 - 00:00',
    encargado: 'JOSUÉ CAJERO',
    montoApertura: 100.00,
    observacion: 'DEMO',
    estado: 'CERRADO', // 'ABIERTO' | 'CERRADO'
  },
  {
    id: 2,
    caja: 'CAJA PRINCIPAL',
    fechaHora: '15/10/2026 - 08:30',
    encargado: 'MARÍA GONZÁLEZ',
    montoApertura: 150.00,
    observacion: 'TURNO MAÑANA',
    estado: 'ABIERTO',
  },
  {
    id: 3,
    caja: 'CAJA BARRA',
    fechaHora: '14/10/2026 - 16:00',
    encargado: 'CARLOS PÉREZ',
    montoApertura: 100.00,
    observacion: '-',
    estado: 'CERRADO',
  },
  {
    id: 4,
    caja: 'CAJA DELIVERY',
    fechaHora: '14/10/2026 - 12:00',
    encargado: 'JOSUÉ CAJERO',
    montoApertura: 80.00,
    observacion: 'VENTAS DELIVERY',
    estado: 'ABIERTO',
  },
  {
    id: 5,
    caja: 'CAJA PRINCIPAL',
    fechaHora: '13/10/2026 - 08:00',
    encargado: 'MARÍA GONZÁLEZ',
    montoApertura: 120.00,
    observacion: '-',
    estado: 'CERRADO',
  },
])

// Buscador y Paginación
const searchQuery = ref('')
const currentPage = ref(1)
const rowsPerPage = ref(10)

const filteredRegistros = computed(() => {
  let list = registrosCaja.value
  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase().trim()
    list = list.filter(
      (r) =>
        r.caja.toLowerCase().includes(q) ||
        r.encargado.toLowerCase().includes(q) ||
        r.observacion.toLowerCase().includes(q) ||
        r.estado.toLowerCase().includes(q)
    )
  }
  return list
})

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredRegistros.value.length / rowsPerPage.value))
)

const paginatedRegistros = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filteredRegistros.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: VER CAJA (Captura: 190227.png) - Tabs: Detalle Venta | Lista Ventas
// -------------------------------------------------------------------
const showVerCajaModal = ref(false)
const activeTabVerCaja = ref('detalle') // 'detalle' | 'lista'
const cajaSeleccionadaVer = ref(null)

const searchDetalleVenta = ref('')
const searchListaVentas = ref('')

// Datos simulados para tab: Detalle Venta
const detalleVentasData = ref([
  {
    fecha: '00/00/0000',
    hora: '00:00:00',
    descripcion: 'APERTURA CAJA',
    referencia: 'JR. JIRON 123',
    monto: 0.00,
    tipo: 'SALIDA',
    concepto: 'SIN CONCEPTO',
    documento: 'TICKET DE VENTA',
    numero: '00000000',
    medioDe: 'EFECTIVO',
    caja: 'EFECTIVO',
  },
  {
    fecha: '15/10/2026',
    hora: '13:45:10',
    descripcion: 'VENTA MESA 4',
    referencia: 'SALÓN PRINCIPAL',
    monto: 145.50,
    tipo: 'INGRESO',
    concepto: 'VENTA CONSUMO',
    documento: 'BOLETA ELECTRÓNICA',
    numero: 'B001-00042',
    medioDe: 'TARJETA',
    caja: 'CAJA PRINCIPAL',
  },
  {
    fecha: '15/10/2026',
    hora: '14:20:00',
    descripcion: 'PAGO PROVEEDOR VERDURAS',
    referencia: 'COMPRA INSUMOS',
    monto: 60.00,
    tipo: 'SALIDA',
    concepto: 'GASTO MENOR',
    documento: 'RECIBO CAJA',
    numero: '000012',
    medioDe: 'EFECTIVO',
    caja: 'CAJA PRINCIPAL',
  },
])

// Datos simulados para tab: Lista Ventas
const listaVentasData = ref([
  {
    fecha: '00/00/0000',
    hora: '00:00:00',
    documento: '0000000000',
    serie: '0000',
    numero: '0000000',
    cliente: 'CLIENTES VARIOS',
    ruc: '00000000',
    tipoPago: 'CONTADO',
    medioPago: 'EFECTIVO',
    caja: 'CAJA PRINCIPAL',
    moneda: 'S/',
  },
  {
    fecha: '15/10/2026',
    hora: '12:30:22',
    documento: 'FACTURA',
    serie: 'F001',
    numero: '0000450',
    cliente: 'CORPORACION GASTRONOMICA SAC',
    ruc: '20548963211',
    tipoPago: 'CONTADO',
    medioPago: 'TRANSFERENCIA',
    caja: 'CAJA PRINCIPAL',
    moneda: 'S/',
  },
  {
    fecha: '15/10/2026',
    hora: '14:05:40',
    documento: 'BOLETA',
    serie: 'B001',
    numero: '0001289',
    cliente: 'JUAN ALVAREZ',
    ruc: '45896321',
    tipoPago: 'CONTADO',
    medioPago: 'YAPE',
    caja: 'CAJA PRINCIPAL',
    moneda: 'S/',
  },
])

const openVerCaja = (reg) => {
  cajaSeleccionadaVer.value = reg
  activeTabVerCaja.value = 'detalle'
  showVerCajaModal.value = true
}

// -------------------------------------------------------------------
// VISOR DE REPORTES PDF (Capturas: 190241, 190300, 190309)
// -------------------------------------------------------------------
const showReportModal = ref(false)
const currentReportTitle = ref('buildReporteinsumos')
const currentReportCategory = ref('anulados') // 'anulados' | 'categoria' | 'sin-categoria'
const showReportDropdown = ref(false)

const openReportViewer = (tipo, title = 'buildReporteinsumos') => {
  currentReportCategory.value = tipo
  currentReportTitle.value = title
  showReportModal.value = true
  showReportDropdown.value = false
}

// -------------------------------------------------------------------
// ELIMINAR REGISTRO DE CAJA
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const registroAEliminar = ref(null)

const openDeleteModal = (reg) => {
  registroAEliminar.value = reg
  showDeleteModal.value = true
}

const confirmarEliminar = () => {
  if (registroAEliminar.value) {
    registrosCaja.value = registrosCaja.value.filter((r) => r.id !== registroAEliminar.value.id)
  }
  showDeleteModal.value = false
  registroAEliminar.value = null
}

const formatMoney = (val) => Number(val || 0).toFixed(2)
</script>

<template>
  <AdminLayout active-nav="finanzas" active-submenu="apertura_cierre_caja">
    <div class="space-y-4">
      <!-- Título de Página -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Apertura / Cierre de Caja
      </h1>

      <!-- =============================================================== -->
      <!-- 4 TARJETAS SUPERIORES ESTADÍSTICAS (Captura: 190210.png)        -->
      <!-- =============================================================== -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Cajas abiertas -->
        <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-1">
          <span class="text-xs font-semibold text-gray-500">Cajas abiertas</span>
          <p class="text-2xl font-extrabold text-gray-900 font-sans">
            {{ summaryStats.cajasAbiertas }}
          </p>
        </Card>

        <!-- 2. Apertura caja -->
        <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-1">
          <span class="text-xs font-semibold text-gray-500">Apertura caja</span>
          <p class="text-2xl font-extrabold text-gray-900 font-mono">
            S/ {{ formatMoney(summaryStats.aperturaCaja) }}
          </p>
        </Card>

        <!-- 3. Ventas totales -->
        <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-1">
          <span class="text-xs font-semibold text-gray-500">Ventas totales</span>
          <p class="text-2xl font-extrabold text-gray-900 font-mono">
            S/ {{ summaryStats.ventasTotales.toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
          </p>
        </Card>

        <!-- 4. Diferencia acumulada -->
        <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-1">
          <span class="text-xs font-semibold text-gray-500">Diferencia acumulada</span>
          <p class="text-2xl font-extrabold font-mono text-[#E53935]">
            - S/ {{ formatMoney(Math.abs(summaryStats.diferenciaAcumulada)) }}
          </p>
        </Card>
      </div>

      <!-- =============================================================== -->
      <!-- TABLA PRINCIPAL CON BUSCADOR Y ACCIONES                         -->
      <!-- =============================================================== -->
      <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <!-- Barra de Acción Superior: Buscador Izquierda + Botones ANULADOS y REPORTE CAJA Derecha -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <div class="relative flex-1 max-w-sm">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar..."
              class="pl-9 h-9 text-xs rounded-xl border-gray-200 bg-white shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-300"
            />
          </div>

          <div class="flex items-center gap-2.5 relative">
            <!-- Botón ANULADOS (Gris oscuro con icono de alerta) -->
            <Button
              type="button"
              variant="outline"
              class="h-9 px-4 text-xs font-semibold text-gray-700 border-gray-300 hover:bg-gray-50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs"
              @click="openReportViewer('anulados', 'caja / anulados')"
            >
              <AlertCircle class="w-4 h-4 text-gray-600" />
              <span>ANULADOS</span>
            </Button>

            <!-- Botón Dropdown REPORTE CAJA (Verde) -->
            <div class="relative">
              <Button
                type="button"
                class="bg-white hover:bg-gray-50 text-[#2EB836] border border-[#2EB836] text-xs font-bold px-4 h-9 rounded-xl flex items-center gap-2 shadow-2xs cursor-pointer transition-colors"
                @click="showReportDropdown = !showReportDropdown"
              >
                <Printer class="w-4 h-4" />
                <span>REPORTE CAJA</span>
                <ChevronDown class="w-3.5 h-3.5 ml-0.5" />
              </Button>

              <!-- Menú Desplegable con opciones de reporte -->
              <div
                v-if="showReportDropdown"
                class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-xl shadow-xl py-1 z-30"
              >
                <button
                  type="button"
                  class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 font-medium cursor-pointer"
                  @click="openReportViewer('categoria', 'caja / Por Categoria')"
                >
                  Reporte Por Categoría
                </button>
                <button
                  type="button"
                  class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 font-medium cursor-pointer border-t border-gray-100"
                  @click="openReportViewer('sin-categoria', 'caja / Sin Categoria')"
                >
                  Reporte Sin Categoría
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de Registros -->
        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
          <Table>
            <TableHeader>
              <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Caja
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Fecha / Hora
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Encargado
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Monto Apertura
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Observación
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Estado
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Acciones
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <!-- Filas Reales -->
              <TableRow
                v-for="r in paginatedRegistros"
                :key="'caja-row-' + r.id"
                class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors"
              >
                <TableCell class="py-3 px-3 text-xs text-center font-semibold text-gray-800 uppercase">
                  {{ r.caja }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center font-mono text-gray-700">
                  {{ r.fechaHora }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center font-medium text-gray-700 uppercase">
                  {{ r.encargado }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center font-bold text-gray-900 font-mono">
                  {{ formatMoney(r.montoApertura) }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center text-gray-600 uppercase">
                  {{ r.observacion }}
                </TableCell>
                <TableCell class="py-3 px-3 text-center">
                  <span
                    class="text-xs font-bold uppercase tracking-tight"
                    :class="r.estado === 'ABIERTO' ? 'text-[#2EB836]' : 'text-[#E53935]'"
                  >
                    {{ r.estado }}
                  </span>
                </TableCell>
                <!-- Acciones: Ver (Azul), Eliminar (Rojo), Imprimir (Naranja) -->
                <TableCell class="py-3 px-3 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#EBF3FC] hover:bg-[#D7E9FB] text-[#2B77D6] flex items-center justify-center transition-colors cursor-pointer"
                      title="Ver detalle caja"
                      @click="openVerCaja(r)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors cursor-pointer"
                      title="Eliminar"
                      @click="openDeleteModal(r)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                    <button
                      v-if="r.estado === 'CERRADO'"
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#FFF3E0] hover:bg-[#FFE0B2] text-[#F57C00] flex items-center justify-center transition-colors cursor-pointer"
                      title="Imprimir ticket cierre"
                      @click="openReportViewer('categoria')"
                    >
                      <Printer class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Filas Skeleton de relleno réplica idéntica -->
              <TableRow
                v-for="i in Math.max(0, 10 - paginatedRegistros.length)"
                :key="'skel-reg-' + i"
                class="border-b border-gray-100 hover:bg-transparent"
              >
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-20 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-24 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-24 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-8 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <span class="text-xs font-bold" :class="i % 2 === 0 ? 'text-[#2EB836]' : 'text-[#E53935]'">
                    {{ i % 2 === 0 ? 'ABIERTO' : 'CERRADO' }}
                  </span>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="flex items-center justify-center gap-1.5 opacity-80">
                    <div class="w-7 h-7 rounded-md bg-[#EBF3FC] flex items-center justify-center text-[#2B77D6]"><Eye class="w-3.5 h-3.5" /></div>
                    <div class="w-7 h-7 rounded-md bg-[#FDEEED] flex items-center justify-center text-[#E53935]"><Trash2 class="w-3.5 h-3.5" /></div>
                    <div v-if="i % 2 !== 0" class="w-7 h-7 rounded-md bg-[#FFF3E0] flex items-center justify-center text-[#F57C00]"><Printer class="w-3.5 h-3.5" /></div>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Paginador Inferior -->
        <div class="pt-2 flex items-center justify-between">
          <DataTablePagination
            :current-page="currentPage"
            :total-pages="totalPages"
            :rows-per-page="rowsPerPage"
            :rows-per-page-options="[8, 10, 20]"
            @update:current-page="currentPage = $event"
            @update:rows-per-page="rowsPerPage = $event"
          />
          <span class="text-xs text-gray-400 font-medium">
            Total {{ filteredRegistros.length }} registros
          </span>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL: VER CAJA (Captura: 190227.png)                           -->
    <!-- =============================================================== -->
    <div
      v-if="showVerCajaModal"
      @click.self="showVerCajaModal = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-5xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-150">
        <!-- Header del Modal -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Ver Caja
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md cursor-pointer"
            @click="showVerCajaModal = false"
          >
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <!-- Pestañas Superiores: Detalle Venta | Lista Ventas -->
        <div class="flex items-center gap-6 px-6 pt-3 border-b border-gray-100 bg-white select-none">
          <button
            type="button"
            class="pb-2 text-xs font-semibold transition-all relative cursor-pointer"
            :class="[
              activeTabVerCaja === 'detalle'
                ? 'text-[#F0302E] border-b-2 border-[#F0302E]'
                : 'text-gray-500 hover:text-gray-900'
            ]"
            @click="activeTabVerCaja = 'detalle'"
          >
            Detalle Caja
          </button>
          <button
            type="button"
            class="pb-2 text-xs font-semibold transition-all relative cursor-pointer"
            :class="[
              activeTabVerCaja === 'lista'
                ? 'text-[#F0302E] border-b-2 border-[#F0302E]'
                : 'text-gray-500 hover:text-gray-900'
            ]"
            @click="activeTabVerCaja = 'lista'"
          >
            Lista Ventas
          </button>
        </div>

        <!-- Cuerpo Modal -->
        <div class="p-6 space-y-4">
          <!-- 1. TAB: DETALLE CAJA -->
          <div v-if="activeTabVerCaja === 'detalle'" class="space-y-4">
            <!-- Buscador interno -->
            <div class="relative max-w-sm">
              <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                v-model="searchDetalleVenta"
                type="text"
                placeholder="Buscar..."
                class="pl-9 h-9 text-xs rounded-xl border-gray-200 bg-white shadow-2xs"
              />
            </div>

            <div class="border border-gray-200 rounded-xl overflow-x-auto bg-white">
              <Table class="min-w-[850px]">
                <TableHeader>
                  <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Fecha</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Hora</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Descripción</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Referencia</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Monto</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Tipo</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Concepto</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Documento</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Número</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Medio de</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Caja</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow
                    v-for="(d, i) in detalleVentasData"
                    :key="'det-row-' + i"
                    class="border-b border-gray-100 hover:bg-gray-50/70 text-xs"
                  >
                    <TableCell class="py-2.5 px-3 text-center font-mono">{{ d.fecha }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-mono">{{ d.hora }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-left font-semibold text-gray-800">{{ d.descripcion }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-left text-gray-600">{{ d.referencia }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-bold text-gray-900 font-mono">{{ formatMoney(d.monto) }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-semibold" :class="d.tipo === 'INGRESO' ? 'text-[#2EB836]' : 'text-[#E53935]'">{{ d.tipo }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-left text-gray-600 uppercase">{{ d.concepto }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-left text-gray-600">{{ d.documento }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-mono text-gray-700">{{ d.numero }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center text-gray-700 uppercase">{{ d.medioDe }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center text-gray-700 uppercase">{{ d.caja }}</TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </div>
          </div>

          <!-- 2. TAB: LISTA VENTAS -->
          <div v-else class="space-y-4">
            <div class="relative max-w-sm">
              <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                v-model="searchListaVentas"
                type="text"
                placeholder="Buscar..."
                class="pl-9 h-9 text-xs rounded-xl border-gray-200 bg-white shadow-2xs"
              />
            </div>

            <div class="border border-gray-200 rounded-xl overflow-x-auto bg-white">
              <Table class="min-w-[850px]">
                <TableHeader>
                  <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Fecha</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Hora</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Documento</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Serie</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Número</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Cliente</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">RUC</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Tipo Pago</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Medio Pago</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Caja</TableHead>
                    <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">Moneda</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow
                    v-for="(v, i) in listaVentasData"
                    :key="'vta-row-' + i"
                    class="border-b border-gray-100 hover:bg-gray-50/70 text-xs"
                  >
                    <TableCell class="py-2.5 px-3 text-center font-mono">{{ v.fecha }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-mono">{{ v.hora }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-semibold text-gray-800">{{ v.documento }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-mono text-gray-700">{{ v.serie }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-mono text-gray-700">{{ v.numero }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-left font-medium text-gray-800">{{ v.cliente }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-mono text-gray-700">{{ v.ruc }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center text-gray-700">{{ v.tipoPago }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center text-gray-700">{{ v.medioPago }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center text-gray-700">{{ v.caja }}</TableCell>
                    <TableCell class="py-2.5 px-3 text-center font-bold text-gray-900">{{ v.moneda }}</TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </div>
          </div>
        </div>

        <!-- Footer Modal Ver Caja -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end bg-white">
          <Button
            type="button"
            variant="outline"
            class="h-9 px-6 text-xs font-semibold text-gray-700 border-gray-300 hover:bg-gray-50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs"
            @click="showVerCajaModal = false"
          >
            <ArrowLeft class="w-3.5 h-3.5" />
            <span>ATRÁS</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL: VISOR DE REPORTE ESTILO CHROME PDF (190241, 190300, 190309)-->
    <!-- =============================================================== -->
    <div
      v-if="showReportModal"
      @click.self="showReportModal = false"
      class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-2 sm:p-4 animate-in fade-in duration-150"
    >
      <div class="bg-[#2D3139] rounded-2xl shadow-2xl border border-gray-700 w-full max-w-5xl h-[88vh] flex flex-col overflow-hidden animate-in zoom-in-95 duration-150">
        <!-- Toolbar Superior Chrome PDF -->
        <div class="bg-[#32363E] text-gray-200 px-4 py-2.5 flex items-center justify-between border-b border-gray-700 select-none">
          <div class="flex items-center gap-3">
            <span class="text-xs font-mono font-medium text-gray-200">
              {{ currentReportTitle }}
            </span>
          </div>

          <div class="flex items-center gap-4 text-xs">
            <span class="bg-[#22252B] px-2 py-0.5 rounded text-[11px] font-mono">1 / 2</span>
            <span class="font-mono text-[11px] text-gray-300">67%</span>
          </div>

          <button
            type="button"
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md cursor-pointer"
            @click="showReportModal = false"
          >
            <X class="w-4 h-4" />
          </button>
        </div>

        <!-- Vista del Documento PDF Renderizado -->
        <div class="flex-1 flex bg-[#3A3E47] overflow-hidden">
          <!-- Miniaturas a la izquierda -->
          <div class="w-40 bg-[#32363E] border-r border-gray-700 p-3 flex flex-col items-center gap-3 overflow-y-auto select-none">
            <div class="w-24 h-36 bg-white rounded shadow border-2 border-blue-500 p-1 flex flex-col justify-between">
              <div class="space-y-1">
                <div class="h-1 bg-gray-300 rounded w-full"></div>
                <div class="h-1 bg-gray-200 rounded w-3/4"></div>
              </div>
              <span class="text-[9px] text-center font-mono text-gray-400">1</span>
            </div>
            <div class="w-24 h-36 bg-white/90 rounded shadow border border-gray-400 p-1 flex flex-col justify-between opacity-75">
              <div class="space-y-1">
                <div class="h-1 bg-gray-200 rounded w-full"></div>
              </div>
              <span class="text-[9px] text-center font-mono text-gray-400">2</span>
            </div>
          </div>

          <!-- Documento Central en Blanco con Tabla Estilo PDF Impreso -->
          <div class="flex-1 bg-[#4A4E57] p-6 flex justify-center overflow-auto">
            <div class="w-[560px] bg-white text-gray-900 rounded shadow-2xl p-8 flex flex-col justify-between text-[10px] font-mono space-y-4">
              <!-- Header Empresa del PDF -->
              <div class="flex justify-between items-start border-b border-gray-300 pb-3">
                <div>
                  <h4 class="font-bold text-xs">LA CONCHITA RICA PEÑA CRIOLLA S.A.C</h4>
                  <p class="text-[9px] text-gray-600">AV. VIRGEN DOLOROSA CDRA 14 - LA BANDA DE SHILCAYO</p>
                  <p class="text-[9px] text-gray-600">SAN MARTÍN - SAN MARTÍN</p>
                  <p class="text-[9px] text-gray-600">RUC: 20614672677</p>
                </div>
                <div class="text-right text-[9px] text-gray-500">
                  <p>FECHA: 15/10/2026</p>
                  <p>Pag. 1 de 2</p>
                </div>
              </div>

              <!-- Título Central del PDF -->
              <div class="text-center font-bold text-xs uppercase underline">
                {{ currentReportCategory === 'anulados' ? 'VENTAS DE PRODUCTOS ANULADOS' : 'VENTAS DE PRODUCTOS' }}
              </div>

              <!-- Tabla de Contenido del PDF -->
              <div class="border border-gray-300">
                <table class="w-full text-left text-[9px]">
                  <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                      <th class="p-1.5">Descripción</th>
                      <th class="p-1.5 text-center">Cantidad</th>
                      <th class="p-1.5 text-right">Precio U.</th>
                      <th class="p-1.5 text-right">Sub Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border-b border-gray-200">
                      <td class="p-1.5 font-bold">CEVICHE MIXTO</td>
                      <td class="p-1.5 text-center">1.00</td>
                      <td class="p-1.5 text-right">35.00</td>
                      <td class="p-1.5 text-right">35.00</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                      <td class="p-1.5 font-bold">CHAUFA CON MARISCOS</td>
                      <td class="p-1.5 text-center">2.00</td>
                      <td class="p-1.5 text-right">25.00</td>
                      <td class="p-1.5 text-right">50.00</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                      <td class="p-1.5 font-bold">CHICHA MORADA 1 LITRO</td>
                      <td class="p-1.5 text-center">1.00</td>
                      <td class="p-1.5 text-right">11.00</td>
                      <td class="p-1.5 text-right">11.00</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Resumen General al pie -->
              <div class="border border-gray-300 p-2 text-[9px] space-y-1 bg-gray-50/50">
                <div class="flex justify-between"><span>Total Cantidad:</span><strong>4.00</strong></div>
                <div class="flex justify-between"><span>Total Monto:</span><strong>S/ 96.00</strong></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL: ELIMINAR REGISTRO                                        -->
    <!-- =============================================================== -->
    <div
      v-if="showDeleteModal && registroAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden animate-in zoom-in-95 duration-150">
        <div class="bg-[#1A1D27] text-white px-5 py-3.5 flex items-center justify-between">
          <h3 class="font-bold text-xs uppercase tracking-wide text-red-500">
            Eliminar Registro de Caja
          </h3>
          <button class="text-gray-400 hover:text-white p-1" @click="showDeleteModal = false">
            <X class="w-4 h-4" />
          </button>
        </div>
        <div class="p-5 space-y-3 text-xs text-gray-600">
          <p>
            ¿Está seguro de eliminar el registro de <strong>{{ registroAEliminar.caja }}</strong> del encargado <strong>{{ registroAEliminar.encargado }}</strong>?
          </p>
        </div>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
          <Button variant="outline" size="sm" class="h-8 text-xs" @click="showDeleteModal = false">Cancelar</Button>
          <Button size="sm" class="h-8 text-xs bg-red-600 hover:bg-red-700 text-white" @click="confirmarEliminar">Eliminar</Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
