<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Card } from '@/Components/ui/card'
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectItem,
} from '@/Components/ui/select'
import {
  Table,
  TableHeader,
  TableBody,
  TableRow,
  TableHead,
  TableCell,
} from '@/Components/ui/table'
import {
  Calendar,
  Settings,
} from 'lucide-vue-next'

// Estado de controles del Dashboard
const selectedCaja = ref('caja-principal')
const dateRange = ref('01/01/2026 - 19/01/2026')

// Datos del Gráfico de Línea (Visión General)
const chartData = [
  { label: '1 JUE', val: 0 },
  { label: '', val: 2200 },
  { label: '3 SAB', val: 3200 },
  { label: '', val: 2100 },
  { label: '5 LUN', val: 1150 },
  { label: '', val: 1800 },
  { label: '7 MIE', val: 2100 },
  { label: '', val: 2300 },
  { label: '9 VIE', val: 3500 },
  { label: '', val: 3300 },
  { label: '11 DOM', val: 2800 },
  { label: '', val: 1250 },
  { label: '13 MAR', val: 1850 },
  { label: '', val: 2250 },
  { label: '15 JUE', val: 2350 },
  { label: '', val: 3400 },
  { label: '17 SAB', val: 3250 },
  { label: '', val: 2950 },
  { label: '19 LUN', val: 950 },
]

// Coordenadas calculadas para el gráfico SVG
const maxVal = 3500
const svgWidth = 650
const svgHeight = 230
const leftPadding = 45
const rightPadding = 20
const topPadding = 20
const bottomPadding = 35

const points = computed(() => {
  const chartWidth = svgWidth - leftPadding - rightPadding
  const chartHeight = svgHeight - topPadding - bottomPadding
  return chartData.map((item, index) => {
    const x = leftPadding + (index / (chartData.length - 1)) * chartWidth
    const y = topPadding + (1 - item.val / maxVal) * chartHeight
    return { ...item, x, y, index }
  })
})

const pathString = computed(() => {
  return points.value.reduce((acc, curr, index) => {
    return index === 0 ? `M ${curr.x},${curr.y}` : `${acc} L ${curr.x},${curr.y}`
  }, '')
})

const hoveredPoint = ref(null)

// Productos más vendidos (Donut Chart)
const products = [
  { name: 'Ceviche', count: 480, color: '#4361EE' },
  { name: 'Arroz con mariscos', count: 390, color: '#5E60CE' },
  { name: 'Jalea', count: 310, color: '#7209B7' },
  { name: 'Chicharrón de pescado', count: 280, color: '#9B5DE5' },
  { name: 'Tiradito', count: 240, color: '#B5179E' },
  { name: 'Sudado de pescado', count: 210, color: '#D81159' },
  { name: 'Chaufa de mariscos', count: 195, color: '#E01E37' },
  { name: 'Leche de tigre', count: 180, color: '#E85D04' },
  { name: 'Conchitas a la parmesana', count: 165, color: '#F48C06' },
  { name: 'Chicha morada', count: 150, color: '#FAA307' },
  { name: 'Parihuela', count: 130, color: '#FFBA08' },
  { name: 'Causa acevichada', count: 110, color: '#F9DC5C' },
  { name: 'Pescado a la plancha', count: 84, color: '#A7C957' },
  { name: 'Cerveza', count: 70, color: '#6A994E' },
]

const totalProducts = computed(() => products.reduce((sum, p) => sum + p.count, 0))

// Generar sectores SVG para el Donut Chart
const donutSlices = computed(() => {
  let cumulativeAngle = 0
  const radius = 72
  const innerRadius = 45
  const cx = 95
  const cy = 95

  return products.map((prod) => {
    const fraction = prod.count / totalProducts.value
    const angle = fraction * 2 * Math.PI

    const startAngle = cumulativeAngle
    const endAngle = cumulativeAngle + angle
    cumulativeAngle = endAngle

    const x1 = cx + radius * Math.sin(startAngle)
    const y1 = cy - radius * Math.cos(startAngle)
    const x2 = cx + radius * Math.sin(endAngle)
    const y2 = cy - radius * Math.cos(endAngle)

    const x3 = cx + innerRadius * Math.sin(endAngle)
    const y3 = cy - innerRadius * Math.cos(endAngle)
    const x4 = cx + innerRadius * Math.sin(startAngle)
    const y4 = cy - innerRadius * Math.cos(startAngle)

    const largeArcFlag = angle > Math.PI ? 1 : 0

    const pathData = `
      M ${x1} ${y1}
      A ${radius} ${radius} 0 ${largeArcFlag} 1 ${x2} ${y2}
      L ${x3} ${y3}
      A ${innerRadius} ${innerRadius} 0 ${largeArcFlag} 0 ${x4} ${y4}
      Z
    `

    return {
      ...prod,
      pathData,
      startAngle,
      endAngle,
    }
  })
})

const hoveredSlice = ref(null)

// Empleados
const employees = [
  { name: 'LEANDRO SINTI PINEDO', vPromedio: '687.00', vActual: '126.00' },
  { name: 'ALISON FASABI CHUJANDAMA', vPromedio: '652.00', vActual: '134.00' },
]
</script>

<template>
  <AdminLayout active-nav="dashboard" active-submenu="">
    <!-- HEADER INTERNO CON SALUDO Y FILTROS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">
        ¡Bienvenid@ al Dasboard!
      </h1>

      <div class="flex items-center gap-3">
        <!-- Rango de fecha -->
        <div class="flex items-center gap-2.5 bg-white border border-gray-300 rounded-md px-3 py-1.5 text-xs text-gray-700 shadow-xs cursor-pointer hover:border-gray-400 transition-colors">
          <span>{{ dateRange }}</span>
          <Calendar class="w-4 h-4 text-gray-500" />
        </div>

        <!-- Selector de Caja (shadcn-vue Select) -->
        <div class="w-44">
          <Select v-model="selectedCaja">
            <SelectTrigger class="bg-white text-xs h-8 border-gray-300 font-medium shadow-xs">
              <SelectValue placeholder="Seleccionar Caja" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="caja-principal">CAJA PRINCIPAL</SelectItem>
              <SelectItem value="caja-segunda">CAJA 02 - BARRA</SelectItem>
              <SelectItem value="caja-delivery">CAJA 03 - DELIVERY</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>
    </div>

    <!-- ================================================================
         FILA SUPERIOR: VISIÓN GENERAL (GRÁFICO) + 3 KPIS EN COLUMNA
         ================================================================ -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <!-- TARJETA PRINCIPAL: VISIÓN GENERAL (LINE CHART) -->
      <Card class="md:col-span-2 lg:col-span-3 bg-white border border-gray-200/90 shadow-sm rounded-xl p-5 flex flex-col justify-between">
        <div>
          <!-- Header de la tarjeta -->
          <div class="flex items-center justify-between mb-2">
            <h2 class="text-sm font-bold text-gray-900">
              Visión General
            </h2>
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <span class="flex items-center gap-1.5 font-medium text-gray-700">
                <span class="w-2 h-2 rounded-full bg-[#F0302E]"></span>
                Venta
              </span>
              <button class="hover:text-gray-700 transition-colors">
                <Settings class="w-3.5 h-3.5 text-gray-400" />
              </button>
            </div>
          </div>

          <!-- Cifra de ventas -->
          <div class="mb-2">
            <div class="text-2xl sm:text-[26px] font-bold text-[#2563EB] tracking-tight">
              S/ 19,680.00
            </div>
            <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
              <span class="text-emerald-600 font-semibold">+ 4.13%</span>
              <span>que la semana pasada</span>
            </div>
          </div>
        </div>

        <!-- Gráfico de Línea Interactivo (SVG) -->
        <div class="relative w-full h-[230px] select-none mt-1">
          <svg
            class="w-full h-full overflow-visible"
            viewBox="0 0 650 230"
            preserveAspectRatio="none"
          >
            <!-- Líneas horizontales de guía (Y-Axis) -->
            <g class="text-[10px] fill-gray-400 font-sans" text-anchor="end">
              <text x="35" y="24">3500</text>
              <line x1="45" y1="20" x2="630" y2="20" stroke="#E5E7EB" stroke-width="1" />

              <text x="35" y="52">3000</text>
              <line x1="45" y1="48" x2="630" y2="48" stroke="#E5E7EB" stroke-width="1" />

              <text x="35" y="80">2500</text>
              <line x1="45" y1="76" x2="630" y2="76" stroke="#E5E7EB" stroke-width="1" />

              <text x="35" y="108">2000</text>
              <line x1="45" y1="104" x2="630" y2="104" stroke="#E5E7EB" stroke-width="1" />

              <text x="35" y="136">1500</text>
              <line x1="45" y1="132" x2="630" y2="132" stroke="#E5E7EB" stroke-width="1" />

              <text x="35" y="164">1000</text>
              <line x1="45" y1="160" x2="630" y2="160" stroke="#E5E7EB" stroke-width="1" />

              <text x="35" y="192">500</text>
              <line x1="45" y1="188" x2="630" y2="188" stroke="#E5E7EB" stroke-width="1" />

              <text x="35" y="217">0</text>
              <line x1="45" y1="215" x2="630" y2="215" stroke="#9CA3AF" stroke-width="1.2" />
            </g>

            <!-- Línea roja punteada de ventas -->
            <path
              :d="pathString"
              fill="none"
              stroke="#F0302E"
              stroke-width="1.8"
              stroke-dasharray="2.5 2.5"
            />

            <!-- Puntos circulares rojos con efecto hover -->
            <g v-for="(p, i) in points" :key="i">
              <circle
                :cx="p.x"
                :cy="p.y"
                r="3.5"
                class="fill-[#F0302E] stroke-white stroke-2 transition-all duration-150 cursor-pointer hover:r-5 hover:fill-[#D02020]"
                @mouseenter="hoveredPoint = p"
                @mouseleave="hoveredPoint = null"
              />

              <!-- Etiqueta en eje X si corresponde -->
              <text
                v-if="p.label"
                :x="p.x"
                y="228"
                text-anchor="middle"
                class="text-[9.5px] fill-gray-500 font-medium font-sans"
              >
                {{ p.label }}
              </text>
            </g>
          </svg>

          <!-- Tooltip flotante al pasar el mouse sobre un punto -->
          <div
            v-if="hoveredPoint"
            class="absolute z-30 bg-[#1E2230] text-white text-[11px] px-2.5 py-1 rounded shadow-lg pointer-events-none transform -translate-x-1/2 -translate-y-full mb-2"
            :style="{
              left: `${(hoveredPoint.x / 650) * 100}%`,
              top: `${(hoveredPoint.y / 230) * 100}%`,
            }"
          >
            <div class="font-bold">S/ {{ hoveredPoint.val.toLocaleString() }}</div>
            <div class="text-[9px] text-gray-400">{{ hoveredPoint.label || 'Día registrado' }}</div>
          </div>
        </div>
      </Card>

      <!-- 3 TARJETAS LATERALES DE KPIS (COLUMNA DERECHA) -->
      <div class="md:col-span-1 lg:col-span-1 flex flex-col justify-between gap-3">
        <!-- 1. Total Ingresos -->
        <Card class="flex-1 bg-white border border-gray-200/90 shadow-sm rounded-xl p-4 flex flex-col justify-between">
          <div class="text-xs text-gray-600 font-medium">
            Total Ingresos
          </div>
          <div class="my-1.5">
            <div class="text-2xl sm:text-[26px] font-bold text-gray-900 tracking-tight leading-none">
              S/ 3550
            </div>
            <div class="text-xs text-emerald-600 font-semibold mt-1">
              +20% que ayer
            </div>
          </div>
        </Card>

        <!-- 2. Total Pedidos -->
        <Card class="flex-1 bg-white border border-gray-200/90 shadow-sm rounded-xl p-4 flex flex-col justify-between">
          <div class="text-xs text-gray-600 font-medium">
            Total Pedidos
          </div>
          <div class="my-1.5">
            <div class="text-2xl sm:text-[26px] font-bold text-gray-900 tracking-tight leading-none">
              230
            </div>
            <div class="text-xs text-emerald-600 font-semibold mt-1">
              +30% que ayer
            </div>
          </div>
        </Card>

        <!-- 3. Pedidos Cancelados -->
        <Card class="flex-1 bg-white border border-gray-200/90 shadow-sm rounded-xl p-4 flex flex-col justify-between">
          <div class="text-xs text-gray-600 font-medium">
            Pedidos Cancelados
          </div>
          <div class="my-1.5">
            <div class="text-2xl sm:text-[26px] font-bold text-gray-900 tracking-tight leading-none">
              8
            </div>
            <div class="text-xs text-[#F0302E] font-semibold mt-1">
              +5% que ayer
            </div>
          </div>
        </Card>
      </div>
    </div>

    <!-- ================================================================
         FILA INFERIOR: PRODUCTOS VENDIDOS (DONUT) + VENTAS POR EMPLEADO (TABLA)
         ================================================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-6">
      <!-- 1. PRODUCTOS VENDIDOS (DONUT CHART) -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-xl p-5 flex flex-col justify-between">
        <div>
          <h2 class="text-sm font-bold text-gray-900 mb-0.5">
            Productos Vendidos
          </h2>
          <div class="text-2xl font-bold text-[#2563EB] tracking-tight">
            2,934
          </div>
        </div>

        <div class="flex items-center justify-between gap-4 mt-2">
          <!-- Leyenda compacta de productos -->
          <div class="flex-1 grid grid-cols-1 gap-0.5 text-[10.5px] text-gray-600 max-h-[190px] overflow-hidden pr-2">
            <template v-for="item in products" :key="item.name">
              <div
                class="flex items-center gap-2 cursor-pointer hover:text-gray-900 transition-colors py-0.2"
                @mouseenter="hoveredSlice = item"
                @mouseleave="hoveredSlice = null"
              >
                <span
                  class="w-2.5 h-1.5 rounded-sm shrink-0"
                  :style="{ backgroundColor: item.color }"
                ></span>
                <span class="truncate">{{ item.name }}</span>
              </div>
            </template>
            <div class="text-gray-400 font-bold px-1 text-[10px]">...</div>
          </div>

          <!-- Gráfico Donut SVG -->
          <div class="relative w-44 h-44 shrink-0 flex items-center justify-center">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 190 190">
              <path
                v-for="(slice, idx) in donutSlices"
                :key="idx"
                :d="slice.pathData"
                :fill="slice.color"
                class="transition-all duration-200 cursor-pointer"
                :class="{ 'opacity-80 scale-105 origin-center': hoveredSlice?.name === slice.name }"
                @mouseenter="hoveredSlice = slice"
                @mouseleave="hoveredSlice = null"
              />
            </svg>

            <!-- Tooltip centrado en el hueco del Donut -->
            <div
              v-if="hoveredSlice"
              class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none p-2"
            >
              <span class="text-[9.5px] text-gray-500 font-medium truncate max-w-[70px]">
                {{ hoveredSlice.name }}
              </span>
              <span class="text-xs font-bold text-gray-900">
                {{ hoveredSlice.count }}
              </span>
            </div>
          </div>
        </div>
      </Card>

      <!-- 2. VENTAS POR EMPLEADO (TABLA SHADCN-VUE) -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-xl p-5 flex flex-col justify-between">
        <div>
          <h2 class="text-sm font-bold text-gray-900 mb-3">
            Ventas por Empleado
          </h2>

          <Table>
            <TableHeader>
              <TableRow class="border-b border-gray-300">
                <TableHead class="text-xs font-bold text-gray-800 h-7 px-2">
                  Empleado
                </TableHead>
                <TableHead class="text-xs font-bold text-gray-800 text-right h-7 px-2">
                  V. Promedio
                </TableHead>
                <TableHead class="text-xs font-bold text-gray-800 text-right h-7 px-2">
                  V. Actual
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <!-- Fila 1: LEANDRO SINTI PINEDO -->
              <TableRow class="hover:bg-gray-50/80 border-b border-gray-100">
                <TableCell class="py-2.5 px-2 text-xs font-semibold text-gray-800 uppercase">
                  {{ employees[0].name }}
                </TableCell>
                <TableCell class="py-2.5 px-2 text-xs text-right text-gray-600 font-medium">
                  {{ employees[0].vPromedio }}
                </TableCell>
                <TableCell class="py-2.5 px-2 text-xs text-right text-gray-600 font-medium">
                  {{ employees[0].vActual }}
                </TableCell>
              </TableRow>

              <!-- Fila 2: ALISON FASABI CHUJANDAMA -->
              <TableRow class="hover:bg-gray-50/80 border-b border-gray-100">
                <TableCell class="py-2.5 px-2 text-xs font-semibold text-gray-800 uppercase">
                  {{ employees[1].name }}
                </TableCell>
                <TableCell class="py-2.5 px-2 text-xs text-right text-gray-600 font-medium">
                  {{ employees[1].vPromedio }}
                </TableCell>
                <TableCell class="py-2.5 px-2 text-xs text-right text-gray-600 font-medium">
                  {{ employees[1].vActual }}
                </TableCell>
              </TableRow>

              <!-- Filas 3 a 7: Barras Esqueleto (Placeholder idénticas al diseño) -->
              <TableRow v-for="n in 5" :key="n" class="border-none">
                <TableCell class="py-1.5 px-2">
                  <div class="h-2 bg-[#B8BFCB] rounded-full" :style="{ width: `${55 + (n * 9) % 30}%` }"></div>
                </TableCell>
                <TableCell class="py-1.5 px-2 flex justify-end">
                  <div class="h-2 w-10 bg-[#B8BFCB] rounded-full"></div>
                </TableCell>
                <TableCell class="py-1.5 px-2 text-right">
                  <div class="h-2 w-10 bg-[#B8BFCB] rounded-full inline-block"></div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </Card>
    </div>
  </AdminLayout>
</template>
