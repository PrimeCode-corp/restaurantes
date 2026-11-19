<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Card } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
  Calendar as CalendarIcon,
  ChevronDown,
  FileText,
  Minus,
  Plus,
  RotateCcw,
  Download,
  Printer,
  MoreVertical,
  Maximize2,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// ESTADOS DE FILTROS (Captura: reporte.png y reporte2.png)
// -------------------------------------------------------------------
const rangos = ['ESTE MES', 'HOY', 'ESTA SEMANA', 'MES ANTERIOR', 'ESTE AÑO', 'PERSONALIZADO']
const selectedRango = ref('ESTE MES')

const fechaInicio = ref('01/09/2026')
const fechaFin = ref('01/10/2026')

const comprobantes = ['TODOS...', 'BOLETA DE VENTA', 'FACTURA', 'TICKET', 'NOTA DE VENTA']
const selectedComprobante = ref('TODOS...')

const tiposCompra = ['TODOS...', 'CONTADO', 'CRÉDITO']
const selectedTipoCompra = ref('TODOS...')

const mediosPago = ['TODOS...', 'CONTADO', 'EFECTIVO', 'TARJETA DE CRÉDITO', 'TRANSFERENCIA', 'YAPE / PLIN']
const selectedMedioPago = ref('TODOS...')

// Estado del visor del reporte: si se ha pulsado MOSTRAR REPORTE
const reportLoaded = ref(true)
const zoomLevel = ref(64)
const currentPage = ref(1)
const totalPages = ref(1)

const mostrarReporte = () => {
  reportLoaded.value = true
}

const zoomIn = () => {
  if (zoomLevel.value < 200) zoomLevel.value += 10
}

const zoomOut = () => {
  if (zoomLevel.value > 30) zoomLevel.value -= 10
}
</script>

<template>
  <AdminLayout active-nav="compras" active-submenu="reporte_compras">
    <div class="space-y-4">
      <!-- Título Superior -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Reporte Compras
      </h1>

      <!-- Contenedor Layout en 2 Columnas idéntico a reporte.png / reporte2.png -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Columna Izquierda: Tarjeta Filtros -->
        <div class="lg:col-span-4 xl:col-span-3">
          <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <h2 class="text-base font-bold text-gray-800 tracking-tight">
              Filtros
            </h2>

            <!-- Rango -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-semibold text-gray-700">Rango:</label>
              <div class="relative">
                <select
                  v-model="selectedRango"
                  class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option v-for="r in rangos" :key="r" :value="r">{{ r }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Calendario (2 inputs lado a lado con icono de calendario) -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-semibold text-gray-700">Calendario:</label>
              <div class="grid grid-cols-2 gap-2">
                <div class="relative flex items-center">
                  <CalendarIcon class="absolute left-2.5 w-3.5 h-3.5 text-gray-400 pointer-events-none" />
                  <Input
                    v-model="fechaInicio"
                    type="text"
                    placeholder="-"
                    class="h-9 pl-8 pr-2 text-xs font-mono rounded-xl border-gray-200 bg-white shadow-2xs text-gray-700"
                  />
                </div>
                <div class="relative flex items-center">
                  <CalendarIcon class="absolute left-2.5 w-3.5 h-3.5 text-gray-400 pointer-events-none" />
                  <Input
                    v-model="fechaFin"
                    type="text"
                    placeholder="-"
                    class="h-9 pl-8 pr-2 text-xs font-mono rounded-xl border-gray-200 bg-white shadow-2xs text-gray-700"
                  />
                </div>
              </div>
            </div>

            <!-- Comprobante -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-semibold text-gray-700">Comprobante:</label>
              <div class="relative">
                <select
                  v-model="selectedComprobante"
                  class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option v-for="c in comprobantes" :key="c" :value="c">{{ c }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Tipo Compra -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-semibold text-gray-700">Tipo Compra</label>
              <div class="relative">
                <select
                  v-model="selectedTipoCompra"
                  class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option v-for="t in tiposCompra" :key="t" :value="t">{{ t }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Medio Pago -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-semibold text-gray-700">Medio Pago:</label>
              <div class="relative">
                <select
                  v-model="selectedMedioPago"
                  class="w-full h-9 px-3 pr-8 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option v-for="m in mediosPago" :key="m" :value="m">{{ m }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Botón MOSTRAR REPORTE (Verde vivo exactamente como en reporte.png) -->
            <div class="pt-4">
              <Button
                type="button"
                class="w-full bg-[#2EB836] hover:bg-[#259b2d] text-white text-xs font-bold py-2.5 h-10 rounded-xl flex items-center justify-center gap-2 shadow-xs cursor-pointer transition-colors"
                @click="mostrarReporte"
              >
                <FileText class="w-4 h-4" />
                <span>MOSTRAR REPORTE</span>
              </Button>
            </div>
          </Card>
        </div>

        <!-- Columna Derecha: Contenedor Visor de Reporte (PDF Viewer Style) -->
        <div class="lg:col-span-8 xl:col-span-9">
          <!-- Si reportLoaded es falso (reporte.png) -->
          <div
            v-if="!reportLoaded"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm min-h-[560px] flex items-center justify-center text-gray-300"
          >
            <!-- Panel en blanco como en reporte.png -->
          </div>

          <!-- Si reportLoaded es verdadero (reporte2.png) -->
          <div
            v-else
            class="bg-[#2D3139] rounded-2xl border border-gray-300 shadow-md overflow-hidden flex flex-col min-h-[580px]"
          >
            <!-- Barra Superior del Visor PDF (Toolbar oscuro idéntico a reporte2.png) -->
            <div class="bg-[#32363E] text-gray-200 px-4 py-2 flex items-center justify-between border-b border-gray-700 select-none">
              <!-- Izquierda: Menú Hamburguesa + Título Documento -->
              <div class="flex items-center gap-3">
                <button type="button" class="text-gray-300 hover:text-white p-1 rounded cursor-pointer">
                  <div class="space-y-1 w-4">
                    <div class="h-0.5 bg-gray-300 rounded"></div>
                    <div class="h-0.5 bg-gray-300 rounded"></div>
                    <div class="h-0.5 bg-gray-300 rounded"></div>
                  </div>
                </button>
                <span class="text-xs font-medium text-gray-200 tracking-wide">
                  aranceles
                </span>
              </div>

              <!-- Centro: Paginación + Zoom -->
              <div class="flex items-center gap-3 text-xs">
                <div class="flex items-center gap-1.5">
                  <span class="bg-[#22252B] px-2 py-0.5 rounded text-[11px] font-mono">{{ currentPage }}</span>
                  <span class="text-gray-400">/</span>
                  <span class="text-gray-400 font-mono">{{ totalPages }}</span>
                </div>

                <div class="h-4 w-px bg-gray-600 mx-1"></div>

                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    class="text-gray-300 hover:text-white p-0.5 rounded cursor-pointer"
                    title="Reducir zoom"
                    @click="zoomOut"
                  >
                    <Minus class="w-3.5 h-3.5" />
                  </button>
                  <span class="font-mono text-[11px] text-gray-200 min-w-[36px] text-center">
                    {{ zoomLevel }}%
                  </span>
                  <button
                    type="button"
                    class="text-gray-300 hover:text-white p-0.5 rounded cursor-pointer"
                    title="Aumentar zoom"
                    @click="zoomIn"
                  >
                    <Plus class="w-3.5 h-3.5" />
                  </button>
                </div>

                <div class="h-4 w-px bg-gray-600 mx-1"></div>

                <div class="flex items-center gap-2 text-gray-300">
                  <button type="button" class="hover:text-white p-0.5 rounded cursor-pointer" title="Ajustar a página">
                    <Maximize2 class="w-3.5 h-3.5" />
                  </button>
                  <button type="button" class="hover:text-white p-0.5 rounded cursor-pointer" title="Rotar">
                    <RotateCcw class="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>

              <!-- Derecha: Descargar + Imprimir + Más Opciones -->
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  class="text-gray-300 hover:text-white p-1 rounded cursor-pointer transition-colors"
                  title="Descargar PDF"
                >
                  <Download class="w-4 h-4" />
                </button>
                <button
                  type="button"
                  class="text-gray-300 hover:text-white p-1 rounded cursor-pointer transition-colors"
                  title="Imprimir"
                >
                  <Printer class="w-4 h-4" />
                </button>
                <button
                  type="button"
                  class="text-gray-300 hover:text-white p-1 rounded cursor-pointer transition-colors"
                  title="Más opciones"
                >
                  <MoreVertical class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Cuerpo del Visor con Miniatura Izquierda y Página Principal Derecha -->
            <div class="flex-1 flex bg-[#3A3E47] overflow-hidden min-h-[500px]">
              <!-- Miniatura Izquierda (Thumbnail) -->
              <div class="w-44 bg-[#32363E] border-r border-gray-700 p-4 flex flex-col items-center select-none">
                <div class="w-28 h-40 bg-[#C8CBD0] rounded shadow-md border border-gray-400 hover:ring-2 hover:ring-blue-400 transition-all cursor-pointer"></div>
              </div>

              <!-- Área Principal del Documento: Hoja del Reporte -->
              <div class="flex-1 bg-[#4A4E57] p-8 flex items-center justify-center overflow-auto">
                <div
                  class="w-[520px] min-h-[640px] bg-[#C8CBD0] rounded-sm shadow-2xl flex flex-col items-center justify-center border border-gray-400 select-none transition-transform"
                  :style="{ transform: `scale(${zoomLevel / 80})`, transformOrigin: 'top center' }"
                >
                  <div class="text-center space-y-2 p-8">
                    <span class="text-3xl font-extrabold tracking-widest text-[#7D828D] uppercase font-sans">
                      REPORTE AQUÍ
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
