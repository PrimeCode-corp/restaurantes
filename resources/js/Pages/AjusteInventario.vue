
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
  RotateCcw,
  Save,
  Trash,
  RefreshCw,
  CheckCircle2,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// OPCIONES DE ALMACÉN
// -------------------------------------------------------------------
const almacenes = [
  'CAJA PRINCIPAL',
  'ALMACEN GENERAL',
  'COCINA',
  'BARRA',
]

const selectedAlmacen = ref('CAJA PRINCIPAL')
const searchQuery = ref('')
const rowsPerPage = ref(10)
const currentPage = ref(1)

const showSuccessToast = ref(false)
const toastMessage = ref('')

// -------------------------------------------------------------------
// DATOS DE AJUSTE DE INVENTARIO (Fiel a la captura proporcionada)
// -------------------------------------------------------------------
const itemsInventario = ref([
  { id: 1, descripcion: 'CHICHARRON DE CHINCHON', unidadMedida: 'UND', stock: 11, cantidad: '', motivo: '' },
  { id: 2, descripcion: '1/8 POLLO A LA BRASA', unidadMedida: 'UND', stock: 10, cantidad: '', motivo: '' },
  { id: 3, descripcion: '1/4 POLLO A LA BRASA', unidadMedida: 'UND', stock: 8, cantidad: '', motivo: '' },
  { id: 4, descripcion: 'MILANESA', unidadMedida: 'UND', stock: 15, cantidad: '', motivo: '' },
  { id: 5, descripcion: 'CEVICHE', unidadMedida: 'UND', stock: 12, cantidad: '', motivo: '' },
  { id: 6, descripcion: 'CECINA FRITA', unidadMedida: 'UND', stock: 5, cantidad: '', motivo: '' },
  { id: 7, descripcion: 'PIZZA REGIONAL PERSONAL', unidadMedida: 'UND', stock: 9, cantidad: '', motivo: '' },
  { id: 8, descripcion: '1/4 POLLO A LA PLANCHA', unidadMedida: 'UND', stock: 10, cantidad: '', motivo: '' },
  { id: 9, descripcion: '1/2 POLLO A LA PLANCHA', unidadMedida: 'UND', stock: 11, cantidad: '', motivo: '' },
  { id: 10, descripcion: 'CHAUFA', unidadMedida: 'UND', stock: 14, cantidad: '', motivo: '' },
])

// -------------------------------------------------------------------
// FILTRADO Y PAGINACIÓN
// -------------------------------------------------------------------
const filtered = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  if (!q) return itemsInventario.value
  return itemsInventario.value.filter(
    (item) =>
      item.descripcion.toLowerCase().includes(q) ||
      item.unidadMedida.toLowerCase().includes(q)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))
const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// ACCIONES: REINICIAR STOCK, GUARDAR, REFLESCAR, VACIAR
// -------------------------------------------------------------------
function reiniciarStock() {
  itemsInventario.value.forEach((i) => {
    i.cantidad = 0
    i.motivo = 'REINICIO DE STOCK A CERO'
  })
  toastMessage.value = 'Cantidades configuradas para reinicio'
  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
}

function guardarAjuste() {
  toastMessage.value = '¡Ajuste de inventario guardado correctamente!'
  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
}

function refrescarLista() {
  searchQuery.value = ''
  currentPage.value = 1
  toastMessage.value = 'Lista de inventario actualizada'
  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
}

function vaciarFormulario() {
  itemsInventario.value.forEach((i) => {
    i.cantidad = ''
    i.motivo = ''
  })
  toastMessage.value = 'Campos de ajuste limpiados'
  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
}
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="ajuste-inventario">
    <div class="space-y-4">
      <!-- Título de Página -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Ajuste de Inventario
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

      <!-- Tarjeta Contenedora Principal -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-7 flex flex-col justify-between min-h-[580px]">
        <div>
          <!-- Barra Superior: Buscador + Selector Almacén + Botones REINICIAR STOCK & GUARDAR -->
          <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-6">
            <!-- Lado Izquierdo: Buscador y Selector de Almacén -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
              <!-- Buscador redondeado con icono lupa -->
              <div class="relative w-full sm:w-72">
                <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <Input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Buscar..."
                  class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-400"
                />
              </div>

              <!-- Selector de Almacén (ej: CAJA PRINCIPAL) -->
              <div class="relative w-full sm:w-60">
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

            <!-- Lado Derecho: Botones REINICIAR STOCK y GUARDAR (Fiel a la captura superior derecha) -->
            <div class="flex items-center gap-2.5 shrink-0">
              <!-- Botón REINICIAR STOCK (Borde gris, fondo blanco, texto oscuro) -->
              <button
                type="button"
                class="h-9 px-4 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs"
                @click="reiniciarStock"
              >
                <RotateCcw class="w-3.5 h-3.5 text-gray-600 stroke-[2.5]" />
                <span>REINICIAR STOCK</span>
              </button>

              <!-- Botón GUARDAR (Rojo vibrante #F0302E) -->
              <button
                type="button"
                class="h-9 px-5 bg-[#F0302E] hover:bg-[#DC2626] text-white rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer"
                @click="guardarAjuste"
              >
                <Save class="w-3.5 h-3.5 stroke-[2.5]" />
                <span>GUARDAR</span>
              </button>
            </div>
          </div>

          <!-- TABLA DE AJUSTE DE INVENTARIO (Fiel a la captura) -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[30%]">Descripción</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[15%]">Unidad Medida</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">Stock</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[15%]">Cantidad</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[28%]">Motivo</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="item in paginated"
                  :key="item.id"
                  class="hover:bg-gray-50/70 border-b border-gray-100 transition-colors"
                >
                  <!-- Descripción -->
                  <TableCell class="py-2.5 px-4 text-xs font-bold text-gray-800 text-center uppercase">
                    {{ item.descripcion }}
                  </TableCell>
                  <!-- Unidad Medida -->
                  <TableCell class="py-2.5 px-4 text-xs text-center font-mono text-gray-600">
                    {{ item.unidadMedida }}
                  </TableCell>
                  <!-- Stock actual -->
                  <TableCell class="py-2.5 px-4 text-xs text-center font-bold font-mono text-gray-800">
                    {{ item.stock }}
                  </TableCell>
                  <!-- Cantidad (Input numérico interactivo con flechas) -->
                  <TableCell class="py-2.5 px-4 text-center">
                    <input
                      v-model.number="item.cantidad"
                      type="number"
                      placeholder="0"
                      class="w-full max-w-[120px] h-8 px-2 text-center text-xs font-semibold rounded-lg border border-gray-300 bg-white placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 mx-auto"
                    />
                  </TableCell>
                  <!-- Motivo (Input de texto "Escribe aquí...") -->
                  <TableCell class="py-2.5 px-4 text-center">
                    <input
                      v-model="item.motivo"
                      type="text"
                      placeholder="Escribe aquí..."
                      class="w-full h-8 px-3 text-xs rounded-lg border border-gray-300 bg-white placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400"
                    />
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <!-- Parte Inferior: Paginación a la izquierda y Botones [REFRESCAR] [VACIAR] a la derecha -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
          <!-- Paginador -->
          <DataTablePagination
            :current-page="currentPage"
            :total-pages="totalPages"
            :rows-per-page="rowsPerPage"
            :rows-per-page-options="[10, 25, 50]"
            @update:current-page="currentPage = $event"
            @update:rows-per-page="rowsPerPage = $event"
          />

          <!-- Botones Inferiores Derechos: [REFRESCAR] (verde) y [VACIAR] (rojo) -->
          <div class="flex items-center gap-2.5">
            <!-- REFRESCAR -->
            <button
              type="button"
              class="h-9 px-4 border border-[#28A745] text-[#28A745] hover:bg-emerald-50 rounded-lg text-xs font-bold tracking-wide flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs"
              @click="refrescarLista"
            >
              <RefreshCw class="w-3.5 h-3.5 stroke-[2.5]" />
              <span>REFRESCAR</span>
            </button>

            <!-- VACIAR -->
            <button
              type="button"
              class="h-9 px-4 border border-[#F0302E] text-[#F0302E] hover:bg-red-50 rounded-lg text-xs font-bold tracking-wide flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs"
              @click="vaciarFormulario"
            >
              <Trash class="w-3.5 h-3.5 stroke-[2.5]" />
              <span>VACIAR</span>
            </button>
          </div>
        </div>
      </Card>
    </div>
  </AdminLayout>
</template>
