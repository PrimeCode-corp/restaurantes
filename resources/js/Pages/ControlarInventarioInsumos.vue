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
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// OPCIONES DE FILTROS SUPERIORES (Fiel a la captura)
// -------------------------------------------------------------------
const almacenes = [
  'ALMACEN - TODOS',
  'ALMACEN GENERAL',
  'CAJA PRINCIPAL',
  'COCINA',
  'BARRA',
]

const stockFiltros = [
  'STOCK - TODOS',
  'SIN STOCK',
  'STOCK MÍNIMO',
  'STOCK NORMAL',
]

const selectedAlmacen = ref('ALMACEN - TODOS')
const selectedStockFiltro = ref('STOCK - TODOS')
const searchQuery = ref('')
const rowsPerPage = ref(10)
const currentPage = ref(1)

// -------------------------------------------------------------------
// LISTA DE PRODUCTOS / INSUMOS CON SEMÁFORO DE STOCK
// -------------------------------------------------------------------
// Rojo: Sin stock (0 o crítico <= 3)
// Naranja: Stock mínimo (4 - 15)
// Verde: Stock normal (> 15)
const productosInventario = ref([
  {
    id: 1,
    producto: 'MASA MADRE',
    categoria: 'INSUMOS',
    tipoInsumo: 'PREPARADO',
    stock: 3,
    um: 'UND',
    precioCosto: 6.80,
    almacen: 'ALMACEN GENERAL',
  },
  {
    id: 2,
    producto: 'SALSA TOMATE',
    categoria: 'INSUMOS',
    tipoInsumo: 'PREPARADO',
    stock: 12,
    um: 'LT',
    precioCosto: 0.00,
    almacen: 'ALMACEN GENERAL',
  },
  {
    id: 3,
    producto: 'CEBOLLA MORADA',
    categoria: 'INSUMOS',
    tipoInsumo: 'COMPRADO',
    stock: 25,
    um: 'KG',
    precioCosto: 0.00,
    almacen: 'ALMACEN GENERAL',
  },
])

// Función que define el estado de stock
function getStockStatus(stock) {
  if (stock <= 3) return 'sin_stock' // Rojo
  if (stock <= 15) return 'stock_minimo' // Naranja
  return 'stock_normal' // Verde
}

function formatMoney(num) {
  return Number(num || 0).toFixed(2)
}

// -------------------------------------------------------------------
// BÚSQUEDA Y FILTRADO
// -------------------------------------------------------------------
const filtered = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  return productosInventario.value.filter((item) => {
    const matchQuery = !q ||
      item.producto.toLowerCase().includes(q) ||
      item.categoria.toLowerCase().includes(q) ||
      item.tipoInsumo.toLowerCase().includes(q)

    const matchAlmacen =
      selectedAlmacen.value === 'ALMACEN - TODOS' ||
      item.almacen === selectedAlmacen.value

    let matchStock = true
    const status = getStockStatus(item.stock)
    if (selectedStockFiltro.value === 'SIN STOCK') matchStock = status === 'sin_stock'
    else if (selectedStockFiltro.value === 'STOCK MÍNIMO') matchStock = status === 'stock_minimo'
    else if (selectedStockFiltro.value === 'STOCK NORMAL') matchStock = status === 'stock_normal'

    return matchQuery && matchAlmacen && matchStock
  })
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))
const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="controlar-inventario-insumos">
    <div class="space-y-4">
      <!-- Título de Página -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Controlar Inventario Insumos
      </h1>

      <!-- Tarjeta Contenedora Principal -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-7 flex flex-col justify-between min-h-0 sm:min-h-[580px]">
        <div>
          <!-- Barra Superior: Buscador + Filtro ALMACEN - TODOS + Filtro STOCK - TODOS -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-6">
            <!-- Buscador -->
            <div class="relative w-full sm:w-72">
              <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar..."
                class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-400"
              />
            </div>

            <!-- Selector ALMACEN - TODOS con icono almacén -->
            <div class="relative w-full sm:w-56">
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

            <!-- Selector STOCK - TODOS con icono de paquete / filtro -->
            <div class="relative w-full sm:w-56">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none font-bold">▦</span>
              <select
                v-model="selectedStockFiltro"
                class="w-full h-9 pl-8 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold uppercase appearance-none focus:outline-none focus:ring-1 focus:ring-gray-400 cursor-pointer shadow-2xs"
              >
                <option v-for="st in stockFiltros" :key="st" :value="st">
                  {{ st }}
                </option>
              </select>
              <ChevronDown class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            </div>
          </div>

          <!-- TABLA DE CONTROL DE INVENTARIO INSUMOS (Fiel a la captura) -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[28%]">Producto</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[18%]">Categoría</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[18%]">Tipo Insumo</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">Stock</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">U. M.</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[12%]">Precio Costo</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="item in paginated"
                  :key="item.id"
                  class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors"
                >
                  <!-- Producto -->
                  <TableCell class="py-3 px-4 text-xs font-bold text-gray-800 text-center uppercase">
                    {{ item.producto }}
                  </TableCell>
                  <!-- Categoría -->
                  <TableCell class="py-3 px-4 text-xs text-center font-semibold text-gray-600 font-mono">
                    {{ item.categoria }}
                  </TableCell>
                  <!-- Tipo Insumo -->
                  <TableCell class="py-3 px-4 text-xs text-center font-mono text-gray-600">
                    {{ item.tipoInsumo }}
                  </TableCell>
                  <!-- Stock con Color Semáforo (Rojo, Naranja, Verde) -->
                  <TableCell class="py-3 px-4 text-xs text-center font-bold font-mono">
                    <span
                      :class="[
                        getStockStatus(item.stock) === 'sin_stock'
                          ? 'text-[#F0302E]'
                          : getStockStatus(item.stock) === 'stock_minimo'
                          ? 'text-[#F59E0B]'
                          : 'text-[#10B981]'
                      ]"
                    >
                      {{ item.stock }}
                    </span>
                  </TableCell>
                  <!-- U. M. -->
                  <TableCell class="py-3 px-4 text-xs text-center font-mono text-gray-600">
                    {{ item.um }}
                  </TableCell>
                  <!-- Precio Costo -->
                  <TableCell class="py-3 px-4 text-xs text-center font-mono text-gray-700">
                    {{ formatMoney(item.precioCosto) }}
                  </TableCell>
                </TableRow>

                <!-- Filas Skeleton de relleno simuladas para calcar el mockup visual -->
                <TableRow
                  v-for="k in Math.max(0, 10 - paginated.length)"
                  :key="'skel-inv-' + k"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-28 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-16 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-16 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-8 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-10 mx-auto"></div></TableCell>
                  <TableCell class="py-3.5 px-4 text-center"><div class="h-2.5 bg-gray-200 rounded-full w-14 mx-auto"></div></TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <!-- Parte Inferior: Paginador + Barra de Referencia de Colores -->
        <div class="space-y-4 pt-4">
          <!-- Paginador -->
          <div class="flex items-center justify-between">
            <DataTablePagination
              :current-page="currentPage"
              :total-pages="totalPages"
              :rows-per-page="rowsPerPage"
              :rows-per-page-options="[10, 25, 50]"
              @update:current-page="currentPage = $event"
              @update:rows-per-page="rowsPerPage = $event"
            />
            <span class="text-xs text-gray-400 font-medium">
              Total {{ filtered.length }} registros
            </span>
          </div>

          <!-- Barra de Referencias Semáforo (Fiel al banner inferior de la captura) -->
          <div class="bg-[#F1F3F5] rounded-xl px-4 py-2.5 flex items-center gap-6 border border-gray-200/60 text-xs">
            <span class="font-bold text-gray-800 tracking-tight">Referencia:</span>
            
            <!-- Sin Stock -->
            <div class="flex items-center gap-2">
              <span class="w-4 h-3 rounded-xs bg-[#F0302E]"></span>
              <span class="text-gray-700 font-semibold text-[11px] uppercase">SIN STOCK</span>
            </div>

            <!-- Stock Mínimo -->
            <div class="flex items-center gap-2">
              <span class="w-4 h-3 rounded-xs bg-[#F59E0B]"></span>
              <span class="text-gray-700 font-semibold text-[11px] uppercase">STOCK MÍNIMO</span>
            </div>

            <!-- Stock Normal -->
            <div class="flex items-center gap-2">
              <span class="w-4 h-3 rounded-xs bg-[#10B981]"></span>
              <span class="text-gray-700 font-semibold text-[11px] uppercase">STOCK NORMAL</span>
            </div>
          </div>
        </div>
      </Card>
    </div>
  </AdminLayout>
</template>
