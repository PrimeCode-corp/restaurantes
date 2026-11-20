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
  X,
  ArrowLeft,
  ChevronDown,
  Calendar as CalendarIcon,
  Save,
  CheckCircle2,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// FILTROS SUPERIORES (Captura: 190328.png)
// -------------------------------------------------------------------
const fechasFiltro = ['TODOS', 'HOY', 'ESTA SEMANA', 'ESTE MES']
const selectedFechaFiltro = ref('TODOS')

const tiposIngresoEgreso = ['Selecciona', 'INGRESO', 'EGRESO']
const selectedTipoFiltro = ref('Selecciona')

const conceptos = ['Selecciona', 'VENTA DIRECTA', 'APERTURA CAJA', 'PAGO PROVEEDORES', 'SERVICIOS BÁSICOS', 'OTROS']
const selectedConceptoFiltro = ref('Selecciona')

const mediosPago = ['Selecciona', 'EFECTIVO', 'TARJETA', 'TRANSFERENCIA', 'YAPE / PLIN']
const selectedMedioPagoFiltro = ref('Selecciona')

// -------------------------------------------------------------------
// TABLA PRINCIPAL DE MOVIMIENTOS DE CAJA
// -------------------------------------------------------------------
const movimientosCaja = ref([
  {
    id: 1,
    fecha: '00/00/0000',
    hora: '00:00:00',
    descripcion: 'CAJA PRINCIPAL',
    referencia: '681-000022',
    monto: 0.00,
    tipo: 'DEMO',
    concepto: 'DEMO',
    documento: 'DEMO',
    numero: '00000000',
    medioPago: 'DEMO',
    caja: 'DEMO',
    usuario: 'DEMO',
  },
  {
    id: 2,
    fecha: '15/10/2026',
    hora: '09:15:30',
    descripcion: 'VENTA EN EFECTIVO',
    referencia: 'TICKET-0012',
    monto: 85.00,
    tipo: 'INGRESO',
    concepto: 'VENTA DIRECTA',
    documento: 'BOLETA',
    numero: 'B001-00124',
    medioPago: 'EFECTIVO',
    caja: 'CAJA PRINCIPAL',
    usuario: 'JOSUÉ',
  },
  {
    id: 3,
    fecha: '15/10/2026',
    hora: '11:40:10',
    descripcion: 'COMPRA GAS DE COCINA',
    referencia: 'RECIBO-44',
    monto: 55.00,
    tipo: 'EGRESO',
    concepto: 'SERVICIOS BÁSICOS',
    documento: 'FACTURA',
    numero: 'F002-0056',
    medioPago: 'EFECTIVO',
    caja: 'CAJA PRINCIPAL',
    usuario: 'MARÍA',
  },
])

// Buscador y Paginación
const searchQuery = ref('')
const currentPage = ref(1)
const rowsPerPage = ref(10)

const filteredMovimientos = computed(() => {
  let list = movimientosCaja.value
  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase().trim()
    list = list.filter(
      (m) =>
        m.descripcion.toLowerCase().includes(q) ||
        m.referencia.toLowerCase().includes(q) ||
        m.concepto.toLowerCase().includes(q) ||
        m.caja.toLowerCase().includes(q) ||
        m.usuario.toLowerCase().includes(q)
    )
  }
  return list
})

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredMovimientos.value.length / rowsPerPage.value))
)

const paginatedMovimientos = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filteredMovimientos.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL 1: NUEVO INGRESO (Captura: 190344.png izquierda)
// -------------------------------------------------------------------
const showModalIngreso = ref(false)
const formIngreso = ref({
  referencia: '',
  fecha: '00/00/0000',
  medioPago: 'EFECTIVO',
  documento: 'DNI - DOC. NACIONAL DE IDENTIDAD',
  numeroDocumento: '',
  moneda: 'S/',
  monto: 0.00,
  cambio: 0.00,
  descripcion: '',
  concepto: 'OTROS INGRESOS',
})

const openModalIngreso = () => {
  formIngreso.value = {
    referencia: '',
    fecha: '00/00/0000',
    medioPago: 'EFECTIVO',
    documento: 'DNI - DOC. NACIONAL DE IDENTIDAD',
    numeroDocumento: '',
    moneda: 'S/',
    monto: 0.00,
    cambio: 0.00,
    descripcion: '',
    concepto: 'OTROS INGRESOS',
  }
  showModalIngreso.value = true
}

const guardarIngreso = () => {
  movimientosCaja.value.unshift({
    id: Date.now(),
    fecha: formIngreso.value.fecha || '15/10/2026',
    hora: '12:00:00',
    descripcion: formIngreso.value.descripcion || 'INGRESO REGISTRADO',
    referencia: formIngreso.value.referencia || '-',
    monto: Number(formIngreso.value.monto || 0),
    tipo: 'INGRESO',
    concepto: formIngreso.value.concepto || 'OTROS INGRESOS',
    documento: formIngreso.value.documento || '-',
    numero: formIngreso.value.numeroDocumento || '-',
    medioPago: formIngreso.value.medioPago || 'EFECTIVO',
    caja: 'CAJA PRINCIPAL',
    usuario: 'ADMIN',
  })
  showModalIngreso.value = false
}

// -------------------------------------------------------------------
// MODAL 2: NUEVO EGRESO (Captura: 190344.png centro)
// -------------------------------------------------------------------
const showModalEgreso = ref(false)
const formEgreso = ref({
  referencia: '',
  fecha: '00/00/0000',
  medioPago: 'EFECTIVO',
  documento: 'DNI - DOC. NACIONAL DE IDENTIDAD',
  numeroDocumento: '',
  moneda: 'S/',
  monto: 0.00,
  cambio: 0.00,
  descripcion: '',
  concepto: 'OTROS INGRESOS',
})

const openModalEgreso = () => {
  formEgreso.value = {
    referencia: '',
    fecha: '00/00/0000',
    medioPago: 'EFECTIVO',
    documento: 'DNI - DOC. NACIONAL DE IDENTIDAD',
    numeroDocumento: '',
    moneda: 'S/',
    monto: 0.00,
    cambio: 0.00,
    descripcion: '',
    concepto: 'OTROS INGRESOS',
  }
  showModalEgreso.value = true
}

const guardarEgreso = () => {
  movimientosCaja.value.unshift({
    id: Date.now(),
    fecha: formEgreso.value.fecha || '15/10/2026',
    hora: '12:00:00',
    descripcion: formEgreso.value.descripcion || 'EGRESO REGISTRADO',
    referencia: formEgreso.value.referencia || '-',
    monto: Number(formEgreso.value.monto || 0),
    tipo: 'EGRESO',
    concepto: formEgreso.value.concepto || 'OTROS EGRESOS',
    documento: formEgreso.value.documento || '-',
    numero: formEgreso.value.numeroDocumento || '-',
    medioPago: formEgreso.value.medioPago || 'EFECTIVO',
    caja: 'CAJA PRINCIPAL',
    usuario: 'ADMIN',
  })
  showModalEgreso.value = false
}

// -------------------------------------------------------------------
// MODAL 3: NUEVO CONCEPTO (Captura: 190344.png derecha)
// -------------------------------------------------------------------
const showModalConcepto = ref(false)
const formConcepto = ref({
  descripcion: '',
  tipoEntradaSalida: 'SAN MARTIN',
  codigo: '',
})

const openModalConcepto = () => {
  formConcepto.value = {
    descripcion: '',
    tipoEntradaSalida: 'SAN MARTIN',
    codigo: '',
  }
  showModalConcepto.value = true
}

const formatMoney = (val) => Number(val || 0).toFixed(2)
</script>

<template>
  <AdminLayout active-nav="finanzas" active-submenu="controlar_caja">
    <div class="space-y-4">
      <!-- Título de Página (Captura: Transferencia de Insumos / Controlar Caja) -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Controlar Caja
      </h1>

      <!-- =============================================================== -->
      <!-- TARJETA DE FILTROS SUPERIORES (Captura: 190328.png)             -->
      <!-- =============================================================== -->
      <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex items-center gap-2 text-sm font-bold text-gray-800">
          <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
          </svg>
          <span>Filtros</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
          <!-- 1. Fecha -->
          <div class="space-y-1.5">
            <label class="block text-[11px] font-semibold text-gray-700">Fecha:</label>
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

          <!-- 2. Tipo Ingreso / Egreso -->
          <div class="space-y-1.5">
            <label class="block text-[11px] font-semibold text-gray-700">Tipo Ingreso / Egreso</label>
            <div class="relative">
              <select
                v-model="selectedTipoFiltro"
                class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
              >
                <option v-for="t in tiposIngresoEgreso" :key="t" :value="t">{{ t }}</option>
              </select>
              <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
            </div>
          </div>

          <!-- 3. Concepto -->
          <div class="space-y-1.5">
            <label class="block text-[11px] font-semibold text-gray-700">Concepto</label>
            <div class="relative">
              <select
                v-model="selectedConceptoFiltro"
                class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
              >
                <option v-for="c in conceptos" :key="c" :value="c">{{ c }}</option>
              </select>
              <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
            </div>
          </div>

          <!-- 4. Medio de Pago -->
          <div class="space-y-1.5">
            <label class="block text-[11px] font-semibold text-gray-700">Medio de Pago</label>
            <div class="relative">
              <select
                v-model="selectedMedioPagoFiltro"
                class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
              >
                <option v-for="m in mediosPago" :key="m" :value="m">{{ m }}</option>
              </select>
              <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
            </div>
          </div>
        </div>
      </Card>

      <!-- =============================================================== -->
      <!-- TABLA PRINCIPAL CON BOTONES + INGRESO Y + EGRESO                -->
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

          <!-- Botones Azules Oscuros: + INGRESO y + EGRESO -->
          <div class="flex items-center gap-2.5">
            <Button
              type="button"
              class="bg-[#2E365C] hover:bg-[#232946] text-white text-xs font-semibold px-4 h-9 rounded-xl flex items-center gap-1.5 shadow-xs cursor-pointer transition-colors uppercase"
              @click="openModalIngreso"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>INGRESO</span>
            </Button>
            <Button
              type="button"
              class="bg-[#2E365C] hover:bg-[#232946] text-white text-xs font-semibold px-4 h-9 rounded-xl flex items-center gap-1.5 shadow-xs cursor-pointer transition-colors uppercase"
              @click="openModalEgreso"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>EGRESO</span>
            </Button>
          </div>
        </div>

        <!-- Tabla -->
        <div class="border border-gray-200 rounded-xl overflow-x-auto bg-white">
          <Table class="min-w-[950px]">
            <TableHeader>
              <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Fecha</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Hora</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Descripción</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Referencia</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Monto</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Tipo</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Concepto</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Documento</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Número</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Medio de Pago</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Caja</TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">Usuario</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <!-- Fila de ejemplo / datos -->
              <TableRow
                v-for="m in paginatedMovimientos"
                :key="'mov-caja-' + m.id"
                class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors text-xs"
              >
                <TableCell class="py-3 px-3 text-center font-mono">{{ m.fecha }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-mono">{{ m.hora }}</TableCell>
                <TableCell class="py-3 px-3 text-left font-semibold text-gray-800 uppercase">{{ m.descripcion }}</TableCell>
                <TableCell class="py-3 px-3 text-left font-mono text-gray-600">{{ m.referencia }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-bold text-gray-900 font-mono">{{ formatMoney(m.monto) }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-semibold uppercase" :class="m.tipo === 'INGRESO' ? 'text-[#2EB836]' : 'text-[#E53935]'">
                  {{ m.tipo }}
                </TableCell>
                <TableCell class="py-3 px-3 text-left text-gray-600 uppercase">{{ m.concepto }}</TableCell>
                <TableCell class="py-3 px-3 text-center text-gray-600 uppercase">{{ m.documento }}</TableCell>
                <TableCell class="py-3 px-3 text-center font-mono text-gray-700">{{ m.numero }}</TableCell>
                <TableCell class="py-3 px-3 text-center uppercase text-gray-700">{{ m.medioPago }}</TableCell>
                <TableCell class="py-3 px-3 text-center uppercase text-gray-700">{{ m.caja }}</TableCell>
                <TableCell class="py-3 px-3 text-center uppercase text-gray-700 font-medium">{{ m.usuario }}</TableCell>
              </TableRow>

              <!-- Skeleton rows para replicar imagen -->
              <TableRow
                v-for="i in Math.max(0, 10 - paginatedMovimientos.length)"
                :key="'skel-mov-' + i"
                class="border-b border-gray-100 hover:bg-transparent"
              >
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-14 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-20 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-16 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-10 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-14 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-14 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-14 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-14 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div></TableCell>
                <TableCell class="py-3.5 px-3 text-center"><div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div></TableCell>
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
            Total {{ filteredMovimientos.length }} registros
          </span>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 1: NUEVO INGRESO (Captura: 190344.png izquierda)           -->
    <!-- =============================================================== -->
    <div
      v-if="showModalIngreso"
      @click.self="showModalIngreso = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Nuevo Ingreso
          </h3>
          <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="showModalIngreso = false">
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <div class="p-6 space-y-3.5 max-h-[75vh] overflow-y-auto">
          <!-- Referencia -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Referencia</label>
            <Input v-model="formIngreso.referencia" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200" />
          </div>

          <!-- Fecha + Medio de Pago -->
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Fecha</label>
              <div class="relative flex items-center">
                <Input v-model="formIngreso.fecha" class="h-9 pr-8 text-xs font-mono rounded-xl border-gray-200" />
                <CalendarIcon class="absolute right-2.5 w-4 h-4 text-[#F0302E]" />
              </div>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Medio de Pago</label>
              <select v-model="formIngreso.medioPago" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="EFECTIVO">EFECTIVO</option>
                <option value="TARJETA">TARJETA</option>
                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
              </select>
            </div>
          </div>

          <!-- Documento + Número Documento -->
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Documento</label>
              <select v-model="formIngreso.documento" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="DNI - DOC. NACIONAL DE IDENTIDAD">DNI - DOC. NACIONAL DE IDENTIDAD</option>
                <option value="RUC">RUC</option>
              </select>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Número Documento</label>
              <Input v-model="formIngreso.numeroDocumento" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
          </div>

          <!-- Moneda + Monto -->
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Moneda</label>
              <select v-model="formIngreso.moneda" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="S/">S/</option>
                <option value="$">$</option>
              </select>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Monto</label>
              <Input v-model.number="formIngreso.monto" type="number" step="0.01" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
          </div>

          <!-- Cambio + Descripción -->
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Cambio</label>
              <Input v-model.number="formIngreso.cambio" type="number" step="0.01" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Descripción</label>
              <Input v-model="formIngreso.descripcion" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200" />
            </div>
          </div>

          <!-- Concepto con botón para crear nuevo concepto -->
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Concepto</label>
            <div class="flex items-center gap-2">
              <select v-model="formIngreso.concepto" class="flex-1 h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="OTROS INGRESOS">OTROS INGRESOS</option>
                <option value="VENTA DIRECTA">VENTA DIRECTA</option>
                <option value="SERVICIOS">SERVICIOS</option>
              </select>
              <button
                type="button"
                class="w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-gray-50 text-gray-600 cursor-pointer shadow-2xs"
                title="Nuevo Concepto"
                @click="openModalConcepto"
              >
                <Plus class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2.5 bg-white">
          <Button variant="outline" class="h-9 px-5 text-xs font-semibold rounded-xl" @click="showModalIngreso = false">
            <ArrowLeft class="w-3.5 h-3.5" /><span>ATRÁS</span>
          </Button>
          <Button class="h-9 px-5 text-xs font-bold text-white bg-[#F0302E] hover:bg-[#d42725] rounded-xl" @click="guardarIngreso">
            <Save class="w-4 h-4" /><span>GUARDAR</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 2: NUEVO EGRESO (Captura: 190344.png centro)              -->
    <!-- =============================================================== -->
    <div
      v-if="showModalEgreso"
      @click.self="showModalEgreso = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Nuevo Egreso
          </h3>
          <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="showModalEgreso = false">
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <div class="p-6 space-y-3.5 max-h-[75vh] overflow-y-auto">
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Referencia</label>
            <Input v-model="formEgreso.referencia" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Fecha</label>
              <div class="relative flex items-center">
                <Input v-model="formEgreso.fecha" class="h-9 pr-8 text-xs font-mono rounded-xl border-gray-200" />
                <CalendarIcon class="absolute right-2.5 w-4 h-4 text-[#F0302E]" />
              </div>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Medio de Pago</label>
              <select v-model="formEgreso.medioPago" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="EFECTIVO">EFECTIVO</option>
                <option value="TARJETA">TARJETA</option>
                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Documento</label>
              <select v-model="formEgreso.documento" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="DNI - DOC. NACIONAL DE IDENTIDAD">DNI - DOC. NACIONAL DE IDENTIDAD</option>
                <option value="RUC">RUC</option>
              </select>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Número Documento</label>
              <Input v-model="formEgreso.numeroDocumento" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Moneda</label>
              <select v-model="formEgreso.moneda" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="S/">S/</option>
                <option value="$">$</option>
              </select>
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Monto</label>
              <Input v-model.number="formEgreso.monto" type="number" step="0.01" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Cambio</label>
              <Input v-model.number="formEgreso.cambio" type="number" step="0.01" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
            </div>
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Descripción</label>
              <Input v-model="formEgreso.descripcion" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200" />
            </div>
          </div>

          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Concepto</label>
            <div class="flex items-center gap-2">
              <select v-model="formEgreso.concepto" class="flex-1 h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
                <option value="OTROS INGRESOS">OTROS INGRESOS</option>
                <option value="GASTO OPERATIVO">GASTO OPERATIVO</option>
                <option value="PAGO PROVEEDOR">PAGO PROVEEDOR</option>
              </select>
              <button
                type="button"
                class="w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-gray-50 text-gray-600 cursor-pointer shadow-2xs"
                title="Nuevo Concepto"
                @click="openModalConcepto"
              >
                <Plus class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2.5 bg-white">
          <Button variant="outline" class="h-9 px-5 text-xs font-semibold rounded-xl" @click="showModalEgreso = false">
            <ArrowLeft class="w-3.5 h-3.5" /><span>ATRÁS</span>
          </Button>
          <Button class="h-9 px-5 text-xs font-bold text-white bg-[#F0302E] hover:bg-[#d42725] rounded-xl" @click="guardarEgreso">
            <Save class="w-4 h-4" /><span>GUARDAR</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 3: NUEVO CONCEPTO (Captura: 190344.png derecha)           -->
    <!-- =============================================================== -->
    <div
      v-if="showModalConcepto"
      @click.self="showModalConcepto = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Nuevo Concepto
          </h3>
          <button type="button" class="text-gray-400 hover:text-gray-600 p-1" @click="showModalConcepto = false">
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <div class="p-6 space-y-3.5">
          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Descripción</label>
            <Input v-model="formConcepto.descripcion" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200" />
          </div>

          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Tipo Entrada / Salida</label>
            <select v-model="formConcepto.tipoEntradaSalida" class="w-full h-9 px-3 text-xs bg-white border border-gray-200 rounded-xl">
              <option value="SAN MARTIN">SAN MARTIN</option>
              <option value="LIMA">LIMA</option>
            </select>
          </div>

          <div class="space-y-1">
            <label class="block text-[11px] font-semibold text-gray-700">Código</label>
            <Input v-model="formConcepto.codigo" placeholder="Escribe aquí" class="h-9 text-xs rounded-xl border-gray-200 font-mono" />
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2.5 bg-white">
          <Button variant="outline" class="h-9 px-4 text-xs font-semibold rounded-xl" @click="showModalConcepto = false">
            <ArrowLeft class="w-3.5 h-3.5" /><span>ATRÁS</span>
          </Button>
          <Button class="h-9 px-4 text-xs font-bold text-white bg-[#F0302E] hover:bg-[#d42725] rounded-xl" @click="showModalConcepto = false">
            <Save class="w-4 h-4" /><span>GUARDAR</span>
          </Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
