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
  Calendar as CalendarIcon,
  X,
  FileSpreadsheet,
  FileText,
  Printer,
  ChevronDown,
  Check,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// OPCIONES DE CAJAS
// -------------------------------------------------------------------
const cajas = [
  'Todas',
  'CAJA PRINCIPAL',
  'CAJA 02 - BARRA',
  'CAJA DELIVERY',
]

// -------------------------------------------------------------------
// CATÁLOGO DE INSUMOS DISPONIBLES (Para el modal "Lista Insumos")
// -------------------------------------------------------------------
const catalogoInsumos = ref([
  { id: 1, descripcion: 'MASA MADRE', tipo: 'PREPARADO' },
  { id: 2, descripcion: 'SALSA DE TOMATE ESPECIAL', tipo: 'PREPARADO' },
  { id: 3, descripcion: 'QUESO MOZZARELLA 500G', tipo: 'COMPRADO' },
  { id: 4, descripcion: 'OREGANO SECO MOLIDO', tipo: 'PREPARADO' },
  { id: 5, descripcion: 'HARINA DE TRIGO EXTRA', tipo: 'COMPRADO' },
  { id: 6, descripcion: 'ACEITE DE OLIVA EXTRA VIRGEN', tipo: 'COMPRADO' },
  { id: 7, descripcion: 'ALBAHACA FRESCA EN HOJAS', tipo: 'PREPARADO' },
  { id: 8, descripcion: 'PIÑA EN CONSERVA', tipo: 'COMPRADO' },
  { id: 9, descripcion: 'CARNE MOLIDA SELECCIONADA', tipo: 'COMPRADO' },
  { id: 10, descripcion: 'JAMÓN INGLÉS EN LÁMINAS', tipo: 'COMPRADO' },
])

// -------------------------------------------------------------------
// BASE DE DATOS DE MOVIMIENTOS
// -------------------------------------------------------------------
const baseMovimientos = ref([
  {
    id: 1,
    fecha: '01/08/2026 10:15',
    insumo: 'MASA MADRE',
    tipoMovimiento: 'PRODUCCIÓN / ENTRADA',
    cantidad: 25,
    unidad: 'UND',
    caja: 'CAJA PRINCIPAL',
    responsable: 'CARLOS CHEF',
    motivo: 'Lote de masa preparado matutino',
  },
  {
    id: 2,
    fecha: '05/08/2026 14:30',
    insumo: 'MASA MADRE',
    tipoMovimiento: 'VENTA / CONSUMO',
    cantidad: -12,
    unidad: 'UND',
    caja: 'CAJA PRINCIPAL',
    responsable: 'SISTEMA POS',
    motivo: 'Consumo por ordenes comandas',
  },
  {
    id: 3,
    fecha: '12/08/2026 09:20',
    insumo: 'MASA MADRE',
    tipoMovimiento: 'TRANSFERENCIA SALIDA',
    cantidad: -5,
    unidad: 'UND',
    caja: 'CAJA PRINCIPAL',
    responsable: 'ADMIN',
    motivo: 'Transferencia a Sede Morales',
  },
  {
    id: 4,
    fecha: '20/08/2026 18:00',
    insumo: 'MASA MADRE',
    tipoMovimiento: 'PRODUCCIÓN / ENTRADA',
    cantidad: 15,
    unidad: 'UND',
    caja: 'CAJA PRINCIPAL',
    responsable: 'CARLOS CHEF',
    motivo: 'Preparación turno noche',
  },
  {
    id: 5,
    fecha: '31/08/2026 22:45',
    insumo: 'MASA MADRE',
    tipoMovimiento: 'MERMA / DESCARTE',
    cantidad: -2,
    unidad: 'UND',
    caja: 'CAJA PRINCIPAL',
    responsable: 'CONTROL CALIDAD',
    motivo: 'Masa sobrefermentada al cierre',
  },
])

// -------------------------------------------------------------------
// FILTROS
// -------------------------------------------------------------------
const selectedCaja = ref('Todas')
const selectedInsumo = ref(null) // objeto { id, descripcion, tipo }
const fechaInicio = ref('2026-08-01')
const fechaFin = ref('2026-08-31')

// Formateo display fecha tipo "01 Ago 2026"
function formatDateDisplay(dateStr) {
  if (!dateStr) return '-'
  const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
  const parts = dateStr.split('-')
  if (parts.length === 3) {
    const day = parts[2]
    const monthIndex = parseInt(parts[1], 10) - 1
    const year = parts[0]
    return `${day} ${months[monthIndex]} ${year}`
  }
  return dateStr
}

// Control reporte generado
const reporteGenerado = ref(false)
const movimientosReporte = ref([])

function generarReporte() {
  reporteGenerado.value = true
  if (selectedInsumo.value) {
    movimientosReporte.value = baseMovimientos.value.filter(
      (m) => m.insumo.toLowerCase().includes(selectedInsumo.value.descripcion.toLowerCase())
    )
  } else {
    movimientosReporte.value = [...baseMovimientos.value]
  }
}

// -------------------------------------------------------------------
// MODAL: LISTA INSUMOS (Fiel a la captura 3 y 4)
// -------------------------------------------------------------------
const showModalInsumos = ref(false)
const busquedaModal = ref('')
const selectedModalInsumoId = ref(1) // Por defecto Masa Madre como en la captura
const modalCurrentPage = ref(1)
const modalRowsPerPage = ref(10)

const catalogoFiltrado = computed(() => {
  const q = busquedaModal.value.toLowerCase().trim()
  if (!q) return catalogoInsumos.value
  return catalogoInsumos.value.filter(
    (i) =>
      i.descripcion.toLowerCase().includes(q) ||
      i.tipo.toLowerCase().includes(q)
  )
})

const modalTotalPages = computed(() =>
  Math.max(1, Math.ceil(catalogoFiltrado.value.length / modalRowsPerPage.value))
)

const catalogoPaginado = computed(() => {
  const start = (modalCurrentPage.value - 1) * modalRowsPerPage.value
  return catalogoFiltrado.value.slice(start, start + modalRowsPerPage.value)
})

function openModalInsumos() {
  busquedaModal.value = ''
  if (selectedInsumo.value) {
    selectedModalInsumoId.value = selectedInsumo.value.id
  } else {
    selectedModalInsumoId.value = 1
  }
  modalCurrentPage.value = 1
  showModalInsumos.value = true
}

function aceptarInsumo() {
  if (!selectedModalInsumoId.value) return
  const ins = catalogoInsumos.value.find((i) => i.id === selectedModalInsumoId.value)
  if (ins) {
    selectedInsumo.value = ins
  }
  showModalInsumos.value = false
}

function limpiarInsumo() {
  selectedInsumo.value = null
}
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="movimiento-insumos">
    <div class="space-y-4">
      <!-- Título de Página -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Movimiento de Insumos
      </h1>

      <!-- Layout de Dos Columnas (Panel Izquierdo Filtros + Panel Derecho Reporte) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        
        <!-- ============================================================= -->
        <!-- PANEL IZQUIERDO: FILTROS (Fiel a la captura 1 y 2)            -->
        <!-- ============================================================= -->
        <Card class="lg:col-span-4 xl:col-span-3 bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 flex flex-col justify-between min-h-[460px]">
          <div class="space-y-5">
            <!-- Título de la tarjeta -->
            <h2 class="text-base font-bold text-gray-800 tracking-tight">
              Filtros
            </h2>

            <!-- Filtro 1: Cajas -->
            <div class="space-y-1.5">
              <label class="block text-xs font-semibold text-gray-700">
                Cajas:
              </label>
              <div class="relative">
                <select
                  v-model="selectedCaja"
                  class="w-full h-9 pl-3 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 appearance-none focus:outline-none focus:ring-1 focus:ring-gray-400 cursor-pointer shadow-2xs"
                >
                  <option v-for="c in cajas" :key="c" :value="c">
                    {{ c }}
                  </option>
                </select>
                <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
              </div>
            </div>

            <!-- Filtro 2: Insumo (Input con botón modal lupa) -->
            <div class="space-y-1.5">
              <label class="block text-xs font-semibold text-gray-700">
                Insumos:
              </label>
              <div class="relative flex items-center">
                <input
                  type="text"
                  readonly
                  :value="selectedInsumo ? selectedInsumo.descripcion : ''"
                  placeholder="Busca insumo a seleccionar...."
                  class="w-full h-9 pl-3.5 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 shadow-2xs cursor-pointer"
                  @click="openModalInsumos"
                />
                <button
                  v-if="selectedInsumo"
                  type="button"
                  class="absolute right-7 text-gray-400 hover:text-gray-600 p-1"
                  title="Limpiar insumo"
                  @click.stop="limpiarInsumo"
                >
                  <X class="w-3.5 h-3.5" />
                </button>
                <button
                  type="button"
                  class="absolute right-2.5 text-[#3E55B3] hover:text-[#2E3F87] transition-colors p-0.5"
                  title="Buscar insumo"
                  @click="openModalInsumos"
                >
                  <Search class="w-4 h-4 stroke-[2.5]" />
                </button>
              </div>
            </div>

            <!-- Filtro 3: Calendario (Rango de fechas estilo píldora con icono de calendario) -->
            <div class="space-y-1.5">
              <label class="block text-xs font-semibold text-gray-700">
                Calendario:
              </label>
              <div class="grid grid-cols-2 gap-2">
                <!-- Fecha Inicio -->
                <div class="relative">
                  <div class="h-9 px-3 border border-gray-300 rounded-lg bg-white flex items-center gap-2 text-xs text-gray-700 shadow-2xs cursor-pointer">
                    <CalendarIcon class="w-3.5 h-3.5 text-gray-500 shrink-0" />
                    <span class="truncate font-medium">{{ formatDateDisplay(fechaInicio) }}</span>
                    <input
                      v-model="fechaInicio"
                      type="date"
                      class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                    />
                  </div>
                </div>

                <!-- Fecha Fin -->
                <div class="relative">
                  <div class="h-9 px-3 border border-gray-300 rounded-lg bg-white flex items-center gap-2 text-xs text-gray-700 shadow-2xs cursor-pointer">
                    <CalendarIcon class="w-3.5 h-3.5 text-gray-500 shrink-0" />
                    <span class="truncate font-medium">{{ formatDateDisplay(fechaFin) }}</span>
                    <input
                      v-model="fechaFin"
                      type="date"
                      class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Botón Verde Inferior: MOSTRAR REPORTE (Fiel al botón de la captura) -->
          <div class="pt-8">
            <button
              type="button"
              class="w-full h-10 bg-[#28A745] hover:bg-[#218838] text-white rounded-lg text-xs font-bold tracking-wider flex items-center justify-center gap-2 shadow-sm transition-all cursor-pointer select-none"
              @click="generarReporte"
            >
              <FileSpreadsheet class="w-4 h-4" />
              <span>MOSTRAR REPORTE</span>
            </button>
          </div>
        </Card>

        <!-- ============================================================= -->
        <!-- PANEL DERECHO: ÁREA DEL REPORTE                               -->
        <!-- ============================================================= -->
        <Card class="lg:col-span-8 xl:col-span-9 bg-white border border-gray-200/90 shadow-sm rounded-2xl p-6 min-h-[540px] flex flex-col justify-between">
          <!-- Vista Inicial (en blanco como en la captura 1 y 2) -->
          <div v-if="!reporteGenerado" class="flex-1 flex flex-col items-center justify-center text-center p-8">
            <div class="w-16 h-16 rounded-full bg-gray-50 border border-gray-200/80 flex items-center justify-center text-gray-300 mb-3">
              <FileText class="w-8 h-8 stroke-[1.5]" />
            </div>
            <h3 class="text-sm font-semibold text-gray-700">Área de Reporte de Movimientos</h3>
            <p class="text-xs text-gray-400 max-w-sm mt-1">
              Seleccione los filtros deseados a la izquierda y haga clic en 
              <strong class="text-[#28A745]">MOSTRAR REPORTE</strong> para visualizar el kardex y detalle de movimientos.
            </p>
          </div>

          <!-- Vista con Reporte Generado -->
          <div v-else class="flex-1 flex flex-col justify-between">
            <div class="space-y-4">
              <!-- Encabezado del Reporte -->
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                <div>
                  <h3 class="font-bold text-sm text-gray-900">
                    Historial de Movimientos: {{ selectedInsumo ? selectedInsumo.descripcion : 'TODOS LOS INSUMOS' }}
                  </h3>
                  <p class="text-[11px] text-gray-500 mt-0.5">
                    Rango: {{ formatDateDisplay(fechaInicio) }} al {{ formatDateDisplay(fechaFin) }} | Caja: {{ selectedCaja }}
                  </p>
                </div>
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    class="h-8 px-3 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-2xs"
                    title="Exportar reporte"
                  >
                    <FileSpreadsheet class="w-3.5 h-3.5 text-emerald-600" />
                    <span>Excel</span>
                  </button>
                  <button
                    type="button"
                    class="h-8 px-3 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-2xs"
                    title="Imprimir reporte"
                  >
                    <Printer class="w-3.5 h-3.5 text-gray-600" />
                    <span>Imprimir</span>
                  </button>
                </div>
              </div>

              <!-- Tabla de Movimientos Generada -->
              <div class="overflow-x-auto rounded-xl border border-gray-200">
                <Table class="w-full border-collapse">
                  <TableHeader class="bg-[#20233B] text-white">
                    <TableRow class="border-none hover:bg-transparent">
                      <TableHead class="text-xs font-semibold text-white h-9 px-4 text-left">Fecha y Hora</TableHead>
                      <TableHead class="text-xs font-semibold text-white h-9 px-4 text-left">Insumo</TableHead>
                      <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center">Tipo Movimiento</TableHead>
                      <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center">Cantidad</TableHead>
                      <TableHead class="text-xs font-semibold text-white h-9 px-4 text-left">Caja / Ubicación</TableHead>
                      <TableHead class="text-xs font-semibold text-white h-9 px-4 text-left">Motivo / Detalle</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    <TableRow
                      v-for="mov in movimientosReporte"
                      :key="mov.id"
                      class="hover:bg-gray-50/70 border-b border-gray-100 transition-colors"
                    >
                      <TableCell class="py-2.5 px-4 text-xs font-mono text-gray-600">
                        {{ mov.fecha }}
                      </TableCell>
                      <TableCell class="py-2.5 px-4 text-xs font-semibold text-gray-800">
                        {{ mov.insumo }}
                      </TableCell>
                      <TableCell class="py-2.5 px-4 text-center">
                        <span
                          class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold"
                          :class="[
                            mov.cantidad > 0
                              ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                              : 'bg-rose-50 text-rose-700 border border-rose-200'
                          ]"
                        >
                          {{ mov.tipoMovimiento }}
                        </span>
                      </TableCell>
                      <TableCell class="py-2.5 px-4 text-xs font-bold text-center font-mono"
                        :class="[mov.cantidad > 0 ? 'text-emerald-600' : 'text-rose-600']"
                      >
                        {{ mov.cantidad > 0 ? `+${mov.cantidad}` : mov.cantidad }} {{ mov.unidad }}
                      </TableCell>
                      <TableCell class="py-2.5 px-4 text-xs text-gray-600">
                        {{ mov.caja }}
                      </TableCell>
                      <TableCell class="py-2.5 px-4 text-xs text-gray-500 truncate max-w-[200px]">
                        {{ mov.motivo }}
                      </TableCell>
                    </TableRow>

                    <TableRow v-if="movimientosReporte.length === 0">
                      <TableCell colspan="6" class="text-center py-8 text-gray-400 text-xs">
                        No se registraron movimientos con los filtros seleccionados.
                      </TableCell>
                    </TableRow>
                  </TableBody>
                </Table>
              </div>
            </div>

            <!-- Resumen Inferior -->
            <div class="pt-4 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 mt-4">
              <span>Total {{ movimientosReporte.length }} registro(s)</span>
              <button
                type="button"
                class="text-xs text-gray-500 hover:text-gray-800 underline cursor-pointer"
                @click="reporteGenerado = false"
              >
                Limpiar vista de reporte
              </button>
            </div>
          </div>
        </Card>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL: LISTA INSUMOS (Fiel a la captura 3 y 4)                    -->
    <!-- ================================================================= -->
    <div
      v-if="showModalInsumos"
      @click.self="showModalInsumos = false"
      class="fixed inset-0 z-50 bg-black/40 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden animate-in zoom-in-95 duration-150 flex flex-col">
        <!-- Header del Modal -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Lista Insumos
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
          <!-- Buscador con bordes redondeados y lupa -->
          <div class="relative w-full">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="busquedaModal"
              type="text"
              placeholder="Buscar..."
              class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-400"
            />
          </div>

          <!-- TABLA MODAL (Fiel al mockup de la captura) -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[60%]">
                    Descripción
                  </TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 text-center w-[40%]">
                    Tipo Insumo
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <!-- Filas de insumos reales / interactivos -->
                <TableRow
                  v-for="ins in catalogoPaginado"
                  :key="ins.id"
                  class="cursor-pointer border-b border-gray-100 transition-colors select-none"
                  :class="[
                    selectedModalInsumoId === ins.id
                      ? 'bg-blue-50/80 font-bold text-[#24265D]'
                      : 'hover:bg-gray-50/70'
                  ]"
                  @click="selectedModalInsumoId = ins.id"
                >
                  <TableCell class="py-2.5 px-4 text-xs text-center">
                    <div class="flex items-center justify-center gap-2">
                      <span
                        v-if="selectedModalInsumoId === ins.id"
                        class="w-1.5 h-1.5 rounded-full bg-[#3E55B3]"
                      ></span>
                      <span class="text-gray-800">{{ ins.descripcion }}</span>
                    </div>
                  </TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center font-mono text-gray-600">
                    {{ ins.tipo }}
                  </TableCell>
                </TableRow>

                <!-- Filas Skeleton de relleno simuladas como en el mockup -->
                <TableRow
                  v-for="i in Math.max(0, 7 - catalogoPaginado.length)"
                  :key="'skel-' + i"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3 px-4 text-center">
                    <div class="h-2.5 bg-gray-200 rounded-full w-40 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-center text-xs font-mono text-gray-500">
                    {{ i % 2 === 0 ? 'COMPRADO' : 'PREPARADO' }}
                  </TableCell>
                </TableRow>

                <TableRow v-if="catalogoFiltrado.length === 0">
                  <TableCell colspan="2" class="text-center py-6 text-gray-400 text-xs">
                    No se encontraron insumos
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginación inferior del modal (Selector 10 v, Flechas y Página 1 de 1) -->
          <div class="pt-2">
            <DataTablePagination
              :current-page="modalCurrentPage"
              :total-pages="modalTotalPages"
              :rows-per-page="modalRowsPerPage"
              :rows-per-page-options="[5, 10, 20]"
              @update:current-page="modalCurrentPage = $event"
              @update:rows-per-page="modalRowsPerPage = $event"
            />
          </div>
        </div>

        <!-- Footer del Modal: Botones ATRÁS y ACEPTAR (Fiel a la captura 3 y 4) -->
        <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end gap-3">
          <!-- Botón ATRÁS -->
          <button
            type="button"
            class="h-9 px-4 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
            @click="showModalInsumos = false"
          >
            <span>← ATRÁS</span>
          </button>
          <!-- Botón ACEPTAR (Rojo vibrante #F0302E con icono check) -->
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
  </AdminLayout>
</template>
