<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  Search,
  Calendar,
  Eye,
  ChevronLeft,
  ChevronRight,
  X,
  Check,
  Printer,
  Download,
  Share2
} from 'lucide-vue-next'

const showReport = ref(false)
const showProductModal = ref(false)

// Filter values
const selectedProducts = ref('1/8 POLLO A LA BRASA, CHAUFA, TACACH...')
const selectedCalendar = ref('TODOS')

// Products selection modal
const selectAll = ref(false)
const productsList = ref([
  { id: 1, descripcion: '1/8 POLLO A LA BRASA', precioVenta: '12.00', tipoInsumo: '12.00', selected: true },
  { id: 2, descripcion: 'CHAUFA DE POLLO', precioVenta: '10.00', tipoInsumo: '10.00', selected: true },
  { id: 3, descripcion: 'TACACHO CON CECINA', precioVenta: '18.00', tipoInsumo: '18.00', selected: true },
  { id: 4, descripcion: '1/4 POLLO A LA BRASA', precioVenta: '16.00', tipoInsumo: '16.00', selected: false },
  { id: 5, descripcion: '1 POLLO ENTERO', precioVenta: '58.00', tipoInsumo: '58.00', selected: false },
])

const toggleSelectAll = () => {
  productsList.value.forEach(p => p.selected = selectAll.value)
}

const generateReport = () => {
  showReport.value = true
}

// Data for PDF preview table
const reportData = ref([
  { codigo: 'PV00008', descripcion: '1 POLLO ENTERO', cantidad: '5.00', precioVenta: '58.00', utilidad: '290.00', pct: '100.00' },
  { codigo: 'PV00074', descripcion: 'PORC PAPAS FRITAS', cantidad: '3.00', precioVenta: '5.00', utilidad: '15.00', pct: '100.00' },
  { codigo: 'PV00071', descripcion: 'ENVASE', cantidad: '3.00', precioVenta: '1.00', utilidad: '3.00', pct: '100.00' },
  { codigo: 'PV00083', descripcion: 'COMBITO 2 CONCORDIA PIÑA 355ML', cantidad: '2.00', precioVenta: '13.00', utilidad: '26.00', pct: '100.00' },
  { codigo: 'PV00081', descripcion: 'PORC JUANE', cantidad: '2.00', precioVenta: '5.00', utilidad: '10.00', pct: '100.00' },
  { codigo: 'PV00042', descripcion: 'PAICHE PANGO', cantidad: '2.00', precioVenta: '20.00', utilidad: '40.00', pct: '100.00' },
  { codigo: 'PV00009', descripcion: '1 POLLO SOLO', cantidad: '2.00', precioVenta: '45.00', utilidad: '90.00', pct: '100.00' },
  { codigo: 'PV00031', descripcion: 'PECHUGA A LA PLANCHA', cantidad: '2.00', precioVenta: '15.00', utilidad: '30.00', pct: '100.00' },
  { codigo: 'PV00002', descripcion: 'CHICHARRON DE CHANCHO', cantidad: '2.00', precioVenta: '16.00', utilidad: '32.00', pct: '100.00' },
  { codigo: 'PV00084', descripcion: 'COMBITO 3 PEPSI 355ML', cantidad: '1.00', precioVenta: '18.00', utilidad: '18.00', pct: '100.00' },
  { codigo: 'PV00091', descripcion: 'PISCO SOUR', cantidad: '1.00', precioVenta: '15.00', utilidad: '14.04', pct: '93.60' },
  { codigo: 'PV00095', descripcion: 'COMBO CHOCOLATE 1', cantidad: '1.00', precioVenta: '20.00', utilidad: '18.00', pct: '90.00' },
  { codigo: 'PV00096', descripcion: 'FIDEOS VERDES CHORIZO PARRILLERO UND', cantidad: '1.00', precioVenta: '15.00', utilidad: '15.00', pct: '100.00' },
  { codigo: 'PV00003', descripcion: 'MILANESA', cantidad: '1.00', precioVenta: '13.00', utilidad: '13.00', pct: '100.00' },
  { codigo: 'PV00005', descripcion: '1/4 POLLO 1/4 PECHO', cantidad: '1.00', precioVenta: '17.00', utilidad: '15.00', pct: '88.24' },
  { codigo: 'PV00005', descripcion: '1/4 POLLO 1/4 PIERNA', cantidad: '1.00', precioVenta: '17.00', utilidad: '15.00', pct: '88.24' },
  { codigo: 'PV00006', descripcion: '1/8 POLLO 2 GUARNICIONES', cantidad: '1.00', precioVenta: '13.00', utilidad: '13.00', pct: '100.00' },
  { codigo: 'PV00041', descripcion: 'PAICHE FRITO', cantidad: '1.00', precioVenta: '20.00', utilidad: '20.00', pct: '100.00' },
  { codigo: 'PV00045', descripcion: 'CHAUFA BRASA', cantidad: '1.00', precioVenta: '18.00', utilidad: '18.00', pct: '100.00' },
  { codigo: 'PV00049', descripcion: 'INCA KOLA 296ML VID', cantidad: '1.00', precioVenta: '3.00', utilidad: '3.00', pct: '100.00' },
  { codigo: 'PV00084', descripcion: 'COMBITO 3 CONCORDIA PIÑA 355ML', cantidad: '1.00', precioVenta: '18.00', utilidad: '18.00', pct: '100.00' },
])
</script>

<template>
  <AdminLayout active-nav="ventas" active-submenu="productos_mas_vendidos">
    <div class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <!-- Title -->
      <h1 class="text-xl font-bold text-gray-800 mb-6">Producto Más Vendido</h1>

      <!-- Layout Grid: 2 Columns (Left Filter Sidebar, Right Content) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left Filter Card (lg:col-span-4) -->
        <div class="lg:col-span-4 bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
          <h2 class="text-xs font-bold text-gray-800">Filtros</h2>

          <div class="space-y-4">
            <!-- Productos Input with Search Icon -->
            <div>
              <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Productos:</label>
              <div class="relative">
                <input
                  v-model="selectedProducts"
                  @click="showProductModal = true"
                  readonly
                  type="text"
                  placeholder="Busca insumo a seleccionar..."
                  class="w-full pl-3 pr-9 py-2 border border-gray-200 rounded-lg text-xs text-gray-700 bg-white cursor-pointer focus:ring-1 focus:ring-red-500"
                />
                <button
                  type="button"
                  @click="showProductModal = true"
                  class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600"
                >
                  <Search class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Calendario Select -->
            <div>
              <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Calendario:</label>
              <div class="relative">
                <select
                  v-model="selectedCalendar"
                  class="w-full pl-8 pr-8 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-700 appearance-none focus:ring-1 focus:ring-red-500"
                >
                  <option>TODOS</option>
                  <option>HOY</option>
                  <option>ESTA SEMANA</option>
                  <option>ESTE MES</option>
                </select>
                <Calendar class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-red-500" />
                <ChevronRight class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 rotate-90" />
              </div>
            </div>
          </div>

          <!-- Action Button -->
          <button
            @click="generateReport"
            class="w-full py-2.5 bg-[#28A745] hover:bg-[#218838] text-white rounded-lg text-xs font-bold flex items-center justify-center gap-2 transition-colors shadow-sm"
          >
            <Eye class="w-4 h-4" />
            <span>MOSTRAR REPORTE</span>
          </button>
        </div>

        <!-- Right Content Card (lg:col-span-8) -->
        <div class="lg:col-span-8 bg-white rounded-xl shadow-sm border border-gray-100 min-h-0 sm:min-h-[580px] overflow-hidden flex flex-col">
          <!-- Empty State when report not yet generated -->
          <div v-if="!showReport" class="flex-1 flex items-center justify-center p-12 text-center">
            <div class="text-gray-400 max-w-sm max-h-[90vh] flex flex-col">
              <div class="w-16 h-16 bg-gray-50 border border-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                <Calendar class="w-8 h-8 text-gray-300" />
              </div>
              <p class="text-xs text-gray-500 font-medium">Seleccione los filtros y haga clic en "MOSTRAR REPORTE" para visualizar el informe detallado.</p>
            </div>
          </div>

          <!-- PDF Viewer Window when generated -->
          <div v-else class="flex-1 bg-[#2E3346] p-4 flex flex-col">
            <!-- PDF Toolbar -->
            <div class="bg-[#1E2540] text-white px-4 py-2 rounded-t-lg flex items-center justify-between text-xs">
              <div class="flex items-center gap-3">
                <span class="font-medium text-gray-300">aranceles</span>
              </div>

              <div class="flex items-center gap-3">
                <span class="text-gray-400">5 / 60</span>
                <span class="text-gray-400">|</span>
                <span class="text-gray-300">64%</span>
                <span class="text-gray-400">|</span>
                <button class="hover:text-blue-400"><Download class="w-4 h-4" /></button>
                <button class="hover:text-blue-400"><Printer class="w-4 h-4" /></button>
              </div>
            </div>

            <!-- PDF Container Layout: Thumbnail + Page Sheet -->
            <div class="flex-1 bg-gray-600/30 p-4 flex gap-4 overflow-hidden rounded-b-lg">
              <!-- Left Mini Thumbnail -->
              <div class="w-32 bg-[#1E2540] rounded p-2 hidden sm:flex flex-col items-center">
                <div class="border-2 border-blue-400 bg-white p-1 rounded shadow text-[6px] text-gray-400 w-full h-32 flex flex-col justify-between">
                  <div class="h-1 bg-gray-300 rounded"></div>
                  <div class="space-y-0.5">
                    <div class="h-1 bg-gray-200 rounded"></div>
                    <div class="h-1 bg-gray-200 rounded"></div>
                    <div class="h-1 bg-gray-200 rounded"></div>
                  </div>
                  <div class="text-[6px] text-center text-gray-500">1</div>
                </div>
              </div>

              <!-- Main Document Page -->
              <div class="flex-1 bg-white rounded shadow-xl p-8 overflow-y-auto max-h-[500px]">
                <div class="text-right text-[10px] text-gray-500 mb-2">
                  <div>FECHA : 29/10/2026</div>
                  <div>Pág: 1 de 1</div>
                </div>

                <div class="text-left mb-6">
                  <div class="text-[11px] font-bold text-gray-800">DSG HOLDING S.A.C</div>
                  <div class="text-[10px] text-gray-600">JR. YURIMAGUAS NRO. 320 (FRENTE A LA PLAZA DE LA BANDA DE SHILCAY) SAN MARTIN - SAN MARTIN - LA BAND</div>
                  <div class="text-[10px] text-gray-600">RUC: 20613845420</div>
                  <div class="text-center font-bold text-xs text-gray-800 mt-4 tracking-wider">VENTAS DE PRODUCTOS</div>
                </div>

                <!-- Sales Table -->
                <table class="w-full border border-gray-400 text-left text-[10px]">
                  <thead class="bg-gray-200 text-gray-800 border-b border-gray-400">
                    <tr>
                      <th class="p-1 border-r border-gray-300">Código</th>
                      <th class="p-1 border-r border-gray-300">Descripción</th>
                      <th class="p-1 border-r border-gray-300 text-right">Cantidad V.</th>
                      <th class="p-1 border-r border-gray-300 text-right">Precio Venta</th>
                      <th class="p-1 border-r border-gray-300 text-right">Utilidad x Unidad</th>
                      <th class="p-1 text-right">%</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-300">
                    <tr v-for="(row, idx) in reportData" :key="idx" class="hover:bg-gray-50">
                      <td class="p-1 border-r border-gray-300 font-mono">{{ row.codigo }}</td>
                      <td class="p-1 border-r border-gray-300 font-medium">{{ row.descripcion }}</td>
                      <td class="p-1 border-r border-gray-300 text-right">{{ row.cantidad }}</td>
                      <td class="p-1 border-r border-gray-300 text-right">{{ row.precioVenta }}</td>
                      <td class="p-1 border-r border-gray-300 text-right">{{ row.utilidad }}</td>
                      <td class="p-1 text-right">{{ row.pct }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL: LISTA PRODUCTOS -->
      <div
        v-if="showProductModal"
        @click.self="showProductModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
      >
        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
          <!-- Header -->
          <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-800">Lista Productos</h2>
            <button @click="showProductModal = false" class="text-gray-400 hover:text-gray-700 transition-colors">
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Body -->
          <div class="p-6 space-y-4">
            <div class="flex items-center justify-between gap-4">
              <div class="relative flex-1">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <input
                  type="text"
                  placeholder="Buscar..."
                  class="w-full pl-9 pr-3 py-1.5 border border-gray-200 rounded-lg text-xs"
                />
              </div>
              <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                <input
                  type="checkbox"
                  v-model="selectAll"
                  @change="toggleSelectAll"
                  class="rounded text-red-600 focus:ring-red-500"
                />
                <span>Todos</span>
              </label>
            </div>

            <!-- Table -->
            <div class="border border-gray-200 rounded-xl overflow-hidden min-h-[220px]">
              <table class="w-full text-xs text-left">
                <thead class="bg-[#2E3346] text-white">
                  <tr>
                    <th class="py-2.5 px-4 font-semibold text-center w-2/3">Descripción</th>
                    <th class="py-2.5 px-4 font-semibold text-center w-1/3">Tipo Insumo</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                  <tr
                    v-for="p in productsList"
                    :key="p.id"
                    @click="p.selected = !p.selected"
                    class="cursor-pointer transition-colors"
                    :class="p.selected ? 'bg-blue-100/70 hover:bg-blue-100' : 'hover:bg-gray-50'"
                  >
                    <td class="py-2.5 px-4 font-medium text-gray-800 uppercase">{{ p.descripcion }}</td>
                    <td class="py-2.5 px-4 text-center text-gray-700">{{ p.tipoInsumo }}</td>
                  </tr>

                  <!-- Skeletons -->
                  <tr class="hover:bg-gray-50">
                    <td class="py-2.5 px-4"><div class="h-3.5 bg-gray-200 rounded-full w-36"></div></td>
                    <td class="py-2.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-12 mx-auto"></div></td>
                  </tr>
                  <tr class="hover:bg-gray-50">
                    <td class="py-2.5 px-4"><div class="h-3.5 bg-gray-200 rounded-full w-40"></div></td>
                    <td class="py-2.5 px-4 text-center"><div class="h-3.5 bg-gray-200 rounded-full w-12 mx-auto"></div></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination inside modal -->
            <div class="flex items-center justify-between text-xs text-gray-500">
              <div class="flex items-center gap-2">
                <select class="border border-gray-200 rounded px-2 py-0.5 text-xs text-gray-600 bg-white">
                  <option>10</option>
                </select>
                <button class="p-1 border border-gray-200 rounded"><ChevronLeft class="w-3 h-3" /></button>
                <span>Página 1 de 1</span>
                <button class="p-1 border border-gray-200 rounded"><ChevronRight class="w-3 h-3" /></button>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
              <button
                @click="showProductModal = false"
                class="px-5 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5"
              >
                <ChevronLeft class="w-4 h-4" />
                <span>ATRÁS</span>
              </button>
              <button
                @click="showProductModal = false"
                class="px-6 py-2 bg-[#F0302E] hover:bg-[#d92220] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm"
              >
                <Check class="w-4 h-4" />
                <span>ACEPTAR</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
