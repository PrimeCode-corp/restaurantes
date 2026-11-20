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
  Printer,
  X,
  ArrowLeft,
  ChevronDown,
  Calendar as CalendarIcon,
  Save,
  CheckCircle2,
  RotateCw,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// FILTROS SUPERIORES (Captura: 190402.png)
// -------------------------------------------------------------------
const fechasFiltro = ['TODOS', 'HOY', 'ESTA SEMANA', 'ESTE MES']
const selectedFechaFiltro = ref('TODOS')

const estadosFiltro = ['TODOS', 'PENDIENTE', 'PAGADO', 'VENCIDO']
const selectedEstadoFiltro = ref('TODOS')

const clienteFiltro = ref('')
const todosClientesCheck = ref(true)

// -------------------------------------------------------------------
// TABLA PRINCIPAL DE CRÉDITOS
// -------------------------------------------------------------------
const creditos = ref([
  {
    id: 1,
    fecha: '00/00/0000',
    fechaFinal: '00/00/0000',
    cliente: 'NOMBRE DEL CLIENTE',
    numCuotas: 2,
    monto: 220.00,
    montoPagado: 110.00,
    montoPendiente: 110.00,
    descripcion: 'VENTA Nº T001 - 00020',
    estado: 'PENDIENTE', // 'PENDIENTE' | 'PAGADO' | 'VENCIDO'
    documento: 'FACTURA DE VENTA ELECTRONICA',
    numDocumento: '00-00000000',
    interes: 10.00,
    montoInteres: 0.00,
    items: [
      { cant: 1.00, um: 'UND', descripcion: 'LOMO SALTADO', precioU: 35.00, total: 35.00 },
      { cant: 2.00, um: 'UND', descripcion: 'GASEOSA 500 ML', precioU: 5.00, total: 10.00 },
    ],
    cuotas: [
      { num: 1, cuota: 110.00, pagado: 110.00, capital: 110.00, interes: 0.00, fechaPago: '00/00/0000', estado: 'PAGADO' },
      { num: 2, cuota: 110.00, pagado: 0.00, capital: 110.00, interes: 0.00, fechaPago: '15/11/2026', estado: 'PENDIENTE' },
    ],
  },
  {
    id: 2,
    fecha: '10/10/2026',
    fechaFinal: '20/10/2026',
    cliente: 'EMPRESA TURÍSTICA SELVA SAC',
    numCuotas: 1,
    monto: 340.00,
    montoPagado: 340.00,
    montoPendiente: 0.00,
    descripcion: 'VENTA EVENTO CORPORATIVO',
    estado: 'PAGADO',
    documento: 'FACTURA ELECTRONICA',
    numDocumento: 'F001-00034',
    interes: 0.00,
    montoInteres: 0.00,
    items: [],
    cuotas: [],
  },
  {
    id: 3,
    fecha: '01/09/2026',
    fechaFinal: '15/09/2026',
    cliente: 'JUAN VALENCIA',
    numCuotas: 3,
    monto: 180.00,
    montoPagado: 60.00,
    montoPendiente: 120.00,
    descripcion: 'CONSUMO RESTAURANTE',
    estado: 'VENCIDO',
    documento: 'BOLETA ELECTRONICA',
    numDocumento: 'B001-00567',
    interes: 5.00,
    montoInteres: 9.00,
    items: [],
    cuotas: [],
  },
])

// Buscador y Paginación
const searchQuery = ref('')
const currentPage = ref(1)
const rowsPerPage = ref(10)

const filteredCreditos = computed(() => {
  let list = creditos.value
  if (selectedEstadoFiltro.value !== 'TODOS') {
    list = list.filter((c) => c.estado === selectedEstadoFiltro.value)
  }
  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase().trim()
    list = list.filter(
      (c) =>
        c.cliente.toLowerCase().includes(q) ||
        c.descripcion.toLowerCase().includes(q) ||
        c.estado.toLowerCase().includes(q)
    )
  }
  return list
})

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredCreditos.value.length / rowsPerPage.value))
)

const paginatedCreditos = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filteredCreditos.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL 1: NUEVO CRÉDITO (Captura: 190409.png - Nuevo Credito / Nuevo Credito 2)
// -------------------------------------------------------------------
const showModalNuevo = ref(false)
const formNuevoCredito = ref({
  cliente: '',
  fecha: '00/00/0000',
  tipoCalculo: 'SIN CRONOGRAMA', // 'SIN CRONOGRAMA' | 'MODO FRANCES'
  documento: 'DNI - DOC. NACIONAL DE IDENTIDAD',
  numeroDocumento: '',
  moneda: 'S/',
  monto: 0.00,
  montoInicial: 0.00,
  descripcion: '',
  fecha1erPago: '00/00/0000',
  tasaTea: 0,
  numCuotas: 0,
  repetirAvisoCada: 0,
  unidadRepeticion: 'Meses',
  cuotaFija: '',
  cronogramaGenerado: [],
})

const openModalNuevoCredito = () => {
  formNuevoCredito.value = {
    cliente: '',
    fecha: '00/00/0000',
    tipoCalculo: 'SIN CRONOGRAMA',
    documento: 'DNI - DOC. NACIONAL DE IDENTIDAD',
    numeroDocumento: '',
    moneda: 'S/',
    monto: 0.00,
    montoInicial: 0.00,
    descripcion: '',
    fecha1erPago: '00/00/0000',
    tasaTea: 0,
    numCuotas: 0,
    repetirAvisoCada: 0,
    unidadRepeticion: 'Meses',
    cuotaFija: '',
    cronogramaGenerado: [],
  }
  showModalNuevo.value = true
}

const generarCronograma = () => {
  const cuotas = []
  const n = Number(formNuevoCredito.value.numCuotas) || 1
  const m = Number(formNuevoCredito.value.monto) || 0
  const valorCuota = (m / n).toFixed(2)
  for (let i = 1; i <= n; i++) {
    cuotas.push({
      num: i,
      fechaPago: formNuevoCredito.value.fecha1erPago || '00/00/0000',
      montoCuota: valorCuota,
      capital: valorCuota,
      interes: '0.00',
    })
  }
  formNuevoCredito.value.cronogramaGenerado = cuotas
}

const limpiarNuevoCredito = () => {
  formNuevoCredito.value.cronogramaGenerado = []
  formNuevoCredito.value.monto = 0.00
  formNuevoCredito.value.numCuotas = 0
}

const guardarNuevoCredito = () => {
  creditos.value.unshift({
    id: Date.now(),
    fecha: formNuevoCredito.value.fecha || '00/00/0000',
    fechaFinal: formNuevoCredito.value.fecha1erPago || '00/00/0000',
    cliente: formNuevoCredito.value.cliente || 'CLIENTE REGISTRADO',
    numCuotas: Number(formNuevoCredito.value.numCuotas) || 1,
    monto: Number(formNuevoCredito.value.monto) || 0.00,
    montoPagado: Number(formNuevoCredito.value.montoInicial) || 0.00,
    montoPendiente: (Number(formNuevoCredito.value.monto) - Number(formNuevoCredito.value.montoInicial || 0)).toFixed(2),
    descripcion: formNuevoCredito.value.descripcion || 'CRÉDITO NUEVO',
    estado: 'PENDIENTE',
    documento: formNuevoCredito.value.documento,
    numDocumento: formNuevoCredito.value.numeroDocumento,
    interes: Number(formNuevoCredito.value.tasaTea) || 0,
    montoInteres: 0.00,
    items: [],
    cuotas: [],
  })
  showModalNuevo.value = false
}

// -------------------------------------------------------------------
// MODAL 2: VER CRÉDITO (Captura: 190409.png inferior izquierda)
// -------------------------------------------------------------------
const showModalVer = ref(false)
const creditoSeleccionadoVer = ref(null)

const openVerCredito = (c) => {
  creditoSeleccionadoVer.value = c
  showModalVer.value = true
}

// -------------------------------------------------------------------
// MODAL 3: PAGOS EN CAJA (Captura: 190409.png inferior derecha)
// -------------------------------------------------------------------
const showModalPagosCaja = ref(false)
const pagosCajaList = ref([
  {
    fechaPago: '00/00/0000',
    hora: '00:00:00',
    numCuota: 1,
    documento: 'RECIBOS DE INGRESO',
    numDocumento: '00-00000000',
    monto: 0.00,
  },
  {
    fechaPago: '15/10/2026',
    hora: '10:30:00',
    numCuota: 1,
    documento: 'RECIBO DE CAJA',
    numDocumento: 'RC-00124',
    monto: 110.00,
  },
])

const openModalPagosCaja = () => {
  showModalPagosCaja.value = true
}

// -------------------------------------------------------------------
// VISOR DE CRONOGRAMA PDF (Capturas: 190414.png / 190421.png)
// -------------------------------------------------------------------
const showPdfCronograma = ref(false)
const tipoPdfReporte = ref('cronograma') // 'cronograma' | 'estado-cuenta'

const openPdfViewer = (tipo = 'cronograma') => {
  tipoPdfReporte.value = tipo
  showPdfCronograma.value = true
}

// -------------------------------------------------------------------
// MODAL ELIMINAR
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const creditoAEliminar = ref(null)

const openDeleteModal = (c) => {
  creditoAEliminar.value = c
  showDeleteModal.value = true
}

const confirmarEliminar = () => {
  if (creditoAEliminar.value) {
    creditos.value = creditos.value.filter((c) => c.id !== creditoAEliminar.value.id)
  }
  showDeleteModal.value = false
}

const formatMoney = (val) => Number(val || 0).toFixed(2)
</script>

<template>
  <AdminLayout active-nav="finanzas" active-submenu="creditos">
    <div class="space-y-4">
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Créditos
      </h1>

      <!-- =============================================================== -->
      <!-- FILTROS SUPERIORES (Captura: 190402.png)                        -->
      <!-- =============================================================== -->
      <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex items-center gap-2 text-sm font-bold text-gray-800">
          <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
          </svg>
          <span>Filtros</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
          <!-- Fecha -->
          <div class="sm:col-span-3 space-y-1.5">
            <label class="block text-[11px] font-semibold text-gray-700">Fecha</label>
            <div class="relative">
              <CalendarIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#F0302E]" />
              <select
                v-model="selectedFechaFiltro"
                class="w-full h-9 pl-9 pr-8 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
              >
                <option v-for="f in fechasFiltro" :key="f" :value="f">{{ f }}</option>
              </select>
              <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
            </div>
          </div>

          <!-- Estado -->
          <div class="sm:col-span-3 space-y-1.5">
            <label class="block text-[11px] font-semibold text-gray-700">Estado</label>
            <div class="relative">
              <select
                v-model="selectedEstadoFiltro"
                class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
              >
                <option v-for="e in estadosFiltro" :key="e" :value="e">{{ e }}</option>
              </select>
              <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
            </div>
          </div>

          <!-- Cliente + Checkbox Todos -->
          <div class="sm:col-span-6 space-y-1.5">
            <label class="block text-[11px] font-semibold text-gray-700">Cliente</label>
            <div class="flex items-center gap-3">
              <Input
                v-model="clienteFiltro"
                placeholder="-"
                class="h-9 text-xs rounded-xl border-gray-200 bg-gray-50 flex-1 shadow-2xs"
              />
              <label class="flex items-center gap-1.5 text-xs text-gray-700 font-medium cursor-pointer select-none">
                <input
                  v-model="todosClientesCheck"
                  type="checkbox"
                  class="rounded text-[#F0302E] focus:ring-0 w-4 h-4 border-gray-300"
                />
                <span>Todos</span>
              </label>
            </div>
          </div>
        </div>
      </Card>

      <!-- =============================================================== -->
      <!-- TABLA PRINCIPAL DE CRÉDITOS (Captura: 190402.png)               -->
      <!-- =============================================================== -->
      <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <!-- Buscador -->
          <div class="relative flex-1 max-w-sm max-h-[90vh] flex flex-col">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar..."
              class="pl-9 h-9 text-xs rounded-xl border-gray-200 bg-white shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-300"
            />
          </div>

          <!-- Botones Superiores: PAGOS EN CAJA + IMPRIMIR (Naranja) + NUEVO (Azul marino) -->
          <div class="flex items-center gap-2.5">
            <Button
              type="button"
              variant="outline"
              class="h-9 px-4 text-xs font-semibold text-gray-700 border-gray-300 hover:bg-gray-50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs uppercase"
              @click="openModalPagosCaja"
            >
              <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
              </svg>
              <span>PAGOS EN CAJA</span>
            </Button>

            <!-- Botón IMPRIMIR (Naranja) -->
            <Button
              type="button"
              class="bg-[#F57C00] hover:bg-[#e66f00] text-white text-xs font-bold px-4 h-9 rounded-xl flex items-center gap-1.5 shadow-xs cursor-pointer transition-colors uppercase"
              @click="openPdfViewer('cronograma')"
            >
              <Printer class="w-4 h-4" />
              <span>IMPRIMIR</span>
              <ChevronDown class="w-3.5 h-3.5 ml-0.5" />
            </Button>

            <!-- Botón + NUEVO -->
            <Button
              type="button"
              class="bg-[#2E365C] hover:bg-[#232946] text-white text-xs font-semibold px-4 h-9 rounded-xl flex items-center gap-1.5 shadow-xs cursor-pointer transition-colors uppercase"
              @click="openModalNuevoCredito"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </Button>
          </div>
        </div>

        <!-- Tabla -->
        <div class="border border-gray-200 rounded-xl overflow-x-auto bg-white">
          <Table class="min-w-[950px]">
            <TableHeader>
              <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Fecha</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Fecha Final</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Cliente</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">N° Cuotas</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Monto</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Monto Pagado</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Monto Pendiente</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Descripción</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Estado</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="c in paginatedCreditos"
                :key="'cred-' + c.id"
                class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors text-xs"
              >
                <TableCell class="py-3 px-3 text-center font-mono text-gray-700">{{ c.fecha }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-mono text-gray-700">{{ c.fechaFinal }}</TableCell>
                <TableCell class="py-3 px-3 text-left font-semibold text-gray-800 uppercase">{{ c.cliente }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-mono font-medium">{{ c.numCuotas }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-bold text-gray-900 font-mono">{{ formatMoney(c.monto) }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-mono text-gray-700">{{ formatMoney(c.montoPagado) }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-mono font-bold text-[#E53935]">{{ formatMoney(c.montoPendiente) }}</TableCell>
                <TableCell class="py-3 px-3 text-left text-gray-600 uppercase">{{ c.descripcion }}</TableCell>
                <TableCell class="py-3 px-3 text-center">
                  <span
                    class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-tight"
                    :class="[
                      c.estado === 'PAGADO' ? 'bg-[#EAF7EE] text-[#34A853]' : '',
                      c.estado === 'PENDIENTE' ? 'bg-[#FFF3E0] text-[#F57C00]' : '',
                      c.estado === 'VENCIDO' ? 'bg-[#FDEEED] text-[#E53935]' : ''
                    ]"
                  >
                    {{ c.estado }}
                  </span>
                </TableCell>
                <TableCell class="py-3 px-3 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#EBF3FC] hover:bg-[#D7E9FB] text-[#2B77D6] flex items-center justify-center transition-colors cursor-pointer"
                      title="Ver crédito"
                      @click="openVerCredito(c)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#EAF7EE] hover:bg-[#D4EEDC] text-[#34A853] flex items-center justify-center transition-colors cursor-pointer"
                      title="Editar"
                      @click="openModalNuevoCredito"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors cursor-pointer"
                      title="Eliminar"
                      @click="openDeleteModal(c)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Skeleton rows -->
              <TableRow
                v-for="i in Math.max(0, 10 - paginatedCreditos.length)"
                :key="'skel-cr-' + i"
                class="border-b border-gray-100 hover:bg-transparent"
              >
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-24 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-6 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-10 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-10 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-10 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-16 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <span class="px-2 py-0.5 rounded text-[11px] font-bold" :class="i % 2 === 0 ? 'bg-[#FFF3E0] text-[#F57C00]' : 'bg-[#EAF7EE] text-[#34A853]'">
                    {{ i % 2 === 0 ? 'PENDIENTE' : 'PAGADO' }}
                  </span>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="flex items-center justify-center gap-1.5 opacity-80">
                    <div class="w-7 h-7 rounded-md bg-[#EBF3FC] flex items-center justify-center text-[#2B77D6]"><Eye class="w-3.5 h-3.5" /></div>
                    <div class="w-7 h-7 rounded-md bg-[#EAF7EE] flex items-center justify-center text-[#34A853]"><Pencil class="w-3.5 h-3.5" /></div>
                    <div class="w-7 h-7 rounded-md bg-[#FDEEED] flex items-center justify-center text-[#E53935]"><Trash2 class="w-3.5 h-3.5" /></div>
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
            Total {{ filteredCreditos.length }} registros
          </span>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 1: NUEVO CRÉDITO (Captura: 190409.png arriba)              -->
    <!-- =============================================================== -->
    <div
      v-if="showModalNuevo"
      @click.self="showModalNuevo = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Nuevo Crédito
          </h3>
          <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="showModalNuevo = false">
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
          <!-- Cliente -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Cliente</label>
            <Input v-model="formNuevoCredito.cliente" placeholder="Escribe para buscar" class="h-9 text-xs rounded-xl border-gray-200" />
          </div>

          <!-- Fila: Fecha + Tipo Cálculo + Fecha 1° Pago (si modo francés) -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Fecha</label>
              <div class="relative flex items-center">
                <Input v-model="formNuevoCredito.fecha" class="h-9 pr-8 text-xs font-mono rounded-xl border-gray-200" />
                <CalendarIcon class="absolute right-2.5 w-4 h-4 text-[#F0302E]" />
              </div>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Tipo Cálculo</label>
              <select v-model="formNuevoCredito.tipoCalculo" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="SIN CRONOGRAMA">SIN CRONOGRAMA</option>
                <option value="MODO FRANCES">MODO FRANCES</option>
              </select>
            </div>
            <div v-if="formNuevoCredito.tipoCalculo === 'MODO FRANCES'" class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Fecha 1° Pago</label>
              <div class="relative flex items-center">
                <Input v-model="formNuevoCredito.fecha1erPago" class="h-9 pr-8 text-xs font-mono rounded-xl border-gray-200" />
                <CalendarIcon class="absolute right-2.5 w-4 h-4 text-[#F0302E]" />
              </div>
            </div>
          </div>

          <!-- Campos Documento + Número -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Documento</label>
              <select v-model="formNuevoCredito.documento" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="DNI - DOC. NACIONAL DE IDENTIDAD">DNI - DOC. NACIONAL DE IDENTIDAD</option>
                <option value="RUC">RUC</option>
              </select>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Número Documento</label>
              <Input v-model="formNuevoCredito.numeroDocumento" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
          </div>

          <!-- Moneda + Monto + Tasa TEA (si modo francés) -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Moneda</label>
              <select v-model="formNuevoCredito.moneda" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="S/">S/</option>
                <option value="$">$</option>
              </select>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Monto</label>
              <Input v-model.number="formNuevoCredito.monto" type="number" step="0.01" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
            <div v-if="formNuevoCredito.tipoCalculo === 'MODO FRANCES'" class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Tasa (T.E.A) %</label>
              <Input v-model.number="formNuevoCredito.tasaTea" type="number" step="0.01" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
          </div>

          <!-- Si MODO FRANCES: N° Cuotas + Repetir Aviso + Cuota Fija (Captura: Nuevo Credito 2) -->
          <div v-if="formNuevoCredito.tipoCalculo === 'MODO FRANCES'" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">N° Cuotas</label>
              <Input v-model.number="formNuevoCredito.numCuotas" type="number" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Repetir Aviso Cada</label>
              <div class="flex items-center gap-1.5">
                <Input v-model.number="formNuevoCredito.repetirAvisoCada" type="number" class="h-9 w-20 text-xs rounded-xl border-gray-200 font-mono" />
                <select v-model="formNuevoCredito.unidadRepeticion" class="h-9 px-2 text-xs bg-white border border-gray-200 rounded-xl flex-1">
                  <option value="Meses">Meses</option>
                  <option value="Días">Días</option>
                </select>
              </div>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Cuota Fija</label>
              <Input v-model="formNuevoCredito.cuotaFija" placeholder="El campo no es obligatorio..." class="h-9 text-xs rounded-xl border-gray-200" />
            </div>
          </div>

          <!-- Monto Inicial + Descripción -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Monto Inicial</label>
              <Input v-model.number="formNuevoCredito.montoInicial" type="number" step="0.01" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Descripción</label>
              <Input v-model="formNuevoCredito.descripcion" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200" />
            </div>
          </div>

          <!-- Botones GENERAR y LIMPIAR (Captura Nuevo Credito 2) -->
          <div v-if="formNuevoCredito.tipoCalculo === 'MODO FRANCES'" class="flex items-center gap-2 pt-1">
            <Button
              type="button"
              variant="outline"
              class="h-8 px-4 text-xs font-semibold text-[#2EB836] border-[#2EB836] hover:bg-green-50 rounded-xl flex items-center gap-1.5 uppercase"
              @click="generarCronograma"
            >
              <RotateCw class="w-3.5 h-3.5" />
              <span>GENERAR</span>
            </Button>
            <Button
              type="button"
              variant="outline"
              class="h-8 px-4 text-xs font-semibold text-[#E53935] border-[#E53935] hover:bg-red-50 rounded-xl flex items-center gap-1.5 uppercase"
              @click="limpiarNuevoCredito"
            >
              <Trash2 class="w-3.5 h-3.5" />
              <span>LIMPIAR</span>
            </Button>
          </div>

          <!-- Tabla de Cuotas Generadas (adm/cronograma) -->
          <div v-if="formNuevoCredito.cronogramaGenerado.length > 0" class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <Table>
              <TableHeader>
                <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] text-white">
                  <TableHead class="text-white text-xs h-9 text-center">N° Cuota</TableHead>
                  <TableHead class="text-white text-xs h-9 text-center">Fecha Pago</TableHead>
                  <TableHead class="text-white text-xs h-9 text-center">Monto Cuota</TableHead>
                  <TableHead class="text-white text-xs h-9 text-center">Capital</TableHead>
                  <TableHead class="text-white text-xs h-9 text-center">Interés</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="c in formNuevoCredito.cronogramaGenerado" :key="c.num" class="text-xs border-b border-gray-100">
                  <TableCell class="py-2 text-center font-mono">{{ c.num }}</TableCell>
                  <TableCell class="py-2 text-center font-mono">{{ c.fechaPago }}</TableCell>
                  <TableCell class="py-2 text-center font-mono font-bold">{{ c.montoCuota }}</TableCell>
                  <TableCell class="py-2 text-center font-mono">{{ c.capital }}</TableCell>
                  <TableCell class="py-2 text-center font-mono">{{ c.interes }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2.5 bg-white">
          <Button variant="outline" class="h-9 px-5 text-xs font-semibold rounded-xl" @click="showModalNuevo = false">
            <ArrowLeft class="w-3.5 h-3.5" /><span>ATRÁS</span>
          </Button>
          <Button class="h-9 px-5 text-xs font-bold text-white bg-[#F0302E] hover:bg-[#d42725] rounded-xl" @click="guardarNuevoCredito">
            <Save class="w-4 h-4" /><span>GUARDAR</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 2: VER CRÉDITO (Captura: 190409.png inferior izquierda)    -->
    <!-- =============================================================== -->
    <div
      v-if="showModalVer && creditoSeleccionadoVer"
      @click.self="showModalVer = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-3xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Ver Crédito
          </h3>
          <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="showModalVer = false">
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
          <!-- Datos Encabezado -->
          <div class="grid grid-cols-3 gap-2 text-xs text-gray-700 bg-gray-50/70 p-4 rounded-xl border border-gray-100">
            <div><span class="font-bold">Cliente: </span>{{ creditoSeleccionadoVer.cliente }}</div>
            <div><span class="font-bold">Fecha: </span><span class="font-mono">{{ creditoSeleccionadoVer.fecha }}</span></div>
            <div><span class="font-bold">Fecha Final: </span><span class="font-mono">{{ creditoSeleccionadoVer.fechaFinal }}</span></div>
            <div><span class="font-bold">Documento: </span>{{ creditoSeleccionadoVer.documento }}</div>
            <div><span class="font-bold">N° Documento: </span><span class="font-mono">{{ creditoSeleccionadoVer.numDocumento }}</span></div>
            <div><span class="font-bold">Monto: </span><span class="font-mono font-bold">{{ formatMoney(creditoSeleccionadoVer.monto) }}</span></div>
          </div>

          <!-- Tabla de Productos / Consumo -->
          <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <Table>
              <TableHeader>
                <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] text-white">
                  <TableHead class="text-white text-xs h-9 text-center">Cant.</TableHead>
                  <TableHead class="text-white text-xs h-9 text-center">U. M.</TableHead>
                  <TableHead class="text-white text-xs h-9 text-left">Descripción</TableHead>
                  <TableHead class="text-white text-xs h-9 text-center">Precio U.</TableHead>
                  <TableHead class="text-white text-xs h-9 text-center">Total</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="(it, idx) in (creditoSeleccionadoVer.items.length ? creditoSeleccionadoVer.items : [
                  { cant: 1.00, um: 'UND', descripcion: 'LOMO SALTADO', precioU: 35.00, total: 35.00 },
                  { cant: 2.00, um: 'UND', descripcion: 'GASEOSA 500 ML', precioU: 5.00, total: 10.00 }
                ])" :key="'it-c-' + idx" class="text-xs border-b border-gray-100">
                  <TableCell class="py-2 text-center font-mono">{{ it.cant }}</TableCell>
                  <TableCell class="py-2 text-center">{{ it.um }}</TableCell>
                  <TableCell class="py-2 font-semibold text-gray-800">{{ it.descripcion }}</TableCell>
                  <TableCell class="py-2 text-center font-mono">{{ formatMoney(it.precioU) }}</TableCell>
                  <TableCell class="py-2 text-center font-mono font-bold">{{ formatMoney(it.total) }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Tabla Cuotas del Crédito -->
          <div class="space-y-2 pt-2">
            <h4 class="text-xs font-bold text-gray-800">Cuotas</h4>
            <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
              <Table>
                <TableHeader>
                  <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] text-white">
                    <TableHead class="text-white text-xs h-9 text-center">N°</TableHead>
                    <TableHead class="text-white text-xs h-9 text-center">Cuota</TableHead>
                    <TableHead class="text-white text-xs h-9 text-center">Pagado</TableHead>
                    <TableHead class="text-white text-xs h-9 text-center">Capital</TableHead>
                    <TableHead class="text-white text-xs h-9 text-center">Interés</TableHead>
                    <TableHead class="text-white text-xs h-9 text-center">Fecha Pago</TableHead>
                    <TableHead class="text-white text-xs h-9 text-center">Estado</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow v-for="cu in (creditoSeleccionadoVer.cuotas.length ? creditoSeleccionadoVer.cuotas : [
                    { num: 1, cuota: 220.00, pagado: 110.00, capital: 220.00, interes: 0.00, fechaPago: '00/00/0000', estado: 'PAGADO' }
                  ])" :key="cu.num" class="text-xs border-b border-gray-100">
                    <TableCell class="py-2 text-center font-mono">{{ cu.num }}</TableCell>
                    <TableCell class="py-2 text-center font-mono font-bold">{{ formatMoney(cu.cuota) }}</TableCell>
                    <TableCell class="py-2 text-center font-mono text-gray-700">{{ formatMoney(cu.pagado) }}</TableCell>
                    <TableCell class="py-2 text-center font-mono">{{ formatMoney(cu.capital) }}</TableCell>
                    <TableCell class="py-2 text-center font-mono">{{ formatMoney(cu.interes) }}</TableCell>
                    <TableCell class="py-2 text-center font-mono">{{ cu.fechaPago }}</TableCell>
                    <TableCell class="py-2 text-center">
                      <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EAF7EE] text-[#34A853]">
                        {{ cu.estado }}
                      </span>
                    </TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </div>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end bg-white">
          <Button variant="outline" class="h-9 px-6 text-xs font-semibold rounded-xl" @click="showModalVer = false">
            <ArrowLeft class="w-3.5 h-3.5" /><span>ATRÁS</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 3: PAGOS EN CAJA (Captura: 190409.png inferior derecha)   -->
    <!-- =============================================================== -->
    <div
      v-if="showModalPagosCaja"
      @click.self="showModalPagosCaja = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Pagos en Caja
          </h3>
          <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="showModalPagosCaja = false">
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <div class="p-6 space-y-4">
          <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <Table>
              <TableHeader>
                <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] text-white">
                  <TableHead class="text-white text-xs h-10 text-center">Fecha Pago</TableHead>
                  <TableHead class="text-white text-xs h-10 text-center">Hora</TableHead>
                  <TableHead class="text-white text-xs h-10 text-center">N° Cuota</TableHead>
                  <TableHead class="text-white text-xs h-10 text-center">Documento</TableHead>
                  <TableHead class="text-white text-xs h-10 text-center">N° Documento</TableHead>
                  <TableHead class="text-white text-xs h-10 text-center">Monto</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="(p, idx) in pagosCajaList" :key="'pago-' + idx" class="text-xs border-b border-gray-100">
                  <TableCell class="py-2.5 text-center font-mono">{{ p.fechaPago }}</TableCell>
                  <TableCell class="py-2.5 text-center font-mono">{{ p.hora }}</TableCell>
                  <TableCell class="py-2.5 text-center font-mono font-bold">{{ p.numCuota }}</TableCell>
                  <TableCell class="py-2.5 text-center uppercase">{{ p.documento }}</TableCell>
                  <TableCell class="py-2.5 text-center font-mono">{{ p.numDocumento }}</TableCell>
                  <TableCell class="py-2.5 text-center font-mono font-bold">{{ formatMoney(p.monto) }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end bg-white">
          <Button variant="outline" class="h-9 px-6 text-xs font-semibold rounded-xl" @click="showModalPagosCaja = false">
            <ArrowLeft class="w-3.5 h-3.5" /><span>ATRÁS</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- VISOR PDF: CRONOGRAMA / ESTADO DE CUENTA (190414 y 190421)      -->
    <!-- =============================================================== -->
    <div
      v-if="showPdfCronograma"
      @click.self="showPdfCronograma = false"
      class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-2 sm:p-4 animate-in fade-in duration-150"
    >
      <div class="bg-[#2D3139] rounded-2xl shadow-2xl border border-gray-700 w-full max-w-5xl h-[88vh] flex flex-col overflow-hidden animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="bg-[#32363E] text-gray-200 px-4 py-2.5 flex items-center justify-between border-b border-gray-700 select-none">
          <div class="flex items-center gap-3">
            <span class="text-xs font-mono font-medium text-gray-200">
              {{ tipoPdfReporte === 'cronograma' ? 'Cronograma de Crédito' : 'Estado de cuenta' }}
            </span>
          </div>
          <button type="button" class="text-gray-400 hover:text-white p-1" @click="showPdfCronograma = false">
            <X class="w-4 h-4" />
          </button>
        </div>

        <div class="flex-1 flex bg-[#4A4E57] p-6 justify-center overflow-auto">
          <div class="w-[580px] bg-white text-gray-900 rounded shadow-2xl p-8 flex flex-col text-[10px] font-mono space-y-4">
            <!-- Header con Logo BOTICA J&P / MISHKY POS -->
            <div class="border-b border-gray-300 pb-3 flex justify-between items-start">
              <div>
                <h4 class="font-bold text-sm">MISHKY POS / FINANZAS</h4>
                <p class="text-[9px] text-gray-600">JR. LIMA 340 - TARAPOTO - SAN MARTÍN</p>
                <p class="text-[9px] text-gray-600">TELÉFONO: 942-123456</p>
              </div>
              <div class="text-right text-[9px] text-gray-500">
                <p>FECHA: 15/10/2026</p>
                <p>PÁGINA: 1 de 1</p>
              </div>
            </div>

            <div class="text-center font-bold text-xs uppercase underline">
              {{ tipoPdfReporte === 'cronograma' ? 'CRONOGRAMA DE PAGOS DE CRÉDITO' : 'ESTADO DE CUENTA DE CRÉDITO' }}
            </div>

            <!-- Tabla de Cuotas -->
            <div class="border border-gray-300">
              <table class="w-full text-left text-[9px]">
                <thead class="bg-gray-100 border-b border-gray-300">
                  <tr>
                    <th class="p-1 text-center">N° CUOTA</th>
                    <th class="p-1 text-center">FECHA</th>
                    <th class="p-1 text-right">CUOTA</th>
                    <th class="p-1 text-right">CAPITAL</th>
                    <th class="p-1 text-right">INTERÉS</th>
                    <th class="p-1 text-right">SALDO</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="i in 5" :key="i" class="border-b border-gray-200">
                    <td class="p-1 text-center font-bold">00{{ i }}</td>
                    <td class="p-1 text-center">15/0{{ i + 5 }}/2026</td>
                    <td class="p-1 text-right">300.00</td>
                    <td class="p-1 text-right">300.00</td>
                    <td class="p-1 text-right">0.00</td>
                    <td class="p-1 text-right">{{ 1500 - (i * 300) }}.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL ELIMINAR                                                  -->
    <!-- =============================================================== -->
    <div
      v-if="showDeleteModal && creditoAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="bg-[#1A1D27] text-white px-5 py-3.5 flex items-center justify-between">
          <h3 class="font-bold text-xs uppercase tracking-wide text-red-500">Eliminar Crédito</h3>
          <button class="text-gray-400 hover:text-white p-1" @click="showDeleteModal = false"><X class="w-4 h-4" /></button>
        </div>
        <div class="p-5 space-y-3 text-xs text-gray-600">
          <p>¿Está seguro de eliminar el crédito de <strong>{{ creditoAEliminar.cliente }}</strong> por un monto de <strong>S/ {{ formatMoney(creditoAEliminar.monto) }}</strong>?</p>
        </div>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
          <Button variant="outline" size="sm" class="h-8 text-xs" @click="showDeleteModal = false">Cancelar</Button>
          <Button size="sm" class="h-8 text-xs bg-red-600 hover:bg-red-700 text-white" @click="confirmarEliminar">Eliminar</Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
