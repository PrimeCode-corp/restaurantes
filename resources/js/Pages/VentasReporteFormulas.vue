<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  Printer,
  Download,
  Share2,
  ChevronLeft,
  ChevronRight
} from 'lucide-vue-next'

const formulas = ref([
  {
    titulo: 'AGUA CON GAS',
    filas: [
      { insumo: 'AGUA CON GAS', cantidad: '1.000', descuento: '1.0000' }
    ]
  },
  {
    titulo: 'AGUA MINERAL',
    filas: [
      { insumo: 'AGUA MINERAL', cantidad: '1.000', descuento: '1.0000' }
    ]
  },
  {
    titulo: 'ARROZ CHAUFA + CHICHARRON',
    filas: [
      { insumo: 'INSUMO GENERAL', cantidad: '1.000', descuento: '1.0000' }
    ]
  },
  {
    titulo: 'ARROZ CON LANGOSTINOS',
    filas: [
      { insumo: 'INSUMO GENERAL', cantidad: '1.000', descuento: '1.0000' }
    ]
  },
  {
    titulo: 'ARROZ CON MARISCOS',
    filas: [
      { insumo: 'INSUMO GENERAL', cantidad: '1.000', descuento: '1.0000' }
    ]
  },
])
</script>

<template>
  <AdminLayout active-nav="ventas" active-submenu="reporte_formulas">
    <div class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <!-- Title -->
      <h1 class="text-xl font-bold text-gray-800 mb-6">Reporte de Formulas</h1>

      <!-- PDF Viewer Wrapper Card -->
      <div class="bg-[#2E3346] rounded-xl shadow-lg p-4 flex flex-col min-h-0 sm:min-h-[680px]">
        <!-- PDF Top Bar -->
        <div class="bg-[#1E2540] text-white px-5 py-2.5 rounded-t-lg flex items-center justify-between text-xs mb-3">
          <div class="flex items-center gap-3">
            <span class="font-medium text-gray-300">Untitle</span>
          </div>

          <div class="flex items-center gap-4 text-xs">
            <span class="text-gray-300">1 / 8</span>
            <span class="text-gray-500">|</span>
            <span class="text-gray-300">100%</span>
            <span class="text-gray-500">|</span>
            <button class="hover:text-blue-400 transition-colors"><Download class="w-4 h-4" /></button>
            <button class="hover:text-blue-400 transition-colors"><Printer class="w-4 h-4" /></button>
          </div>
        </div>

        <!-- PDF Layout: Thumbnails Sidebar + Main Document Paper -->
        <div class="flex-1 bg-gray-600/30 p-4 rounded-b-lg flex gap-5 overflow-hidden">
          <!-- Left Mini Thumbnails -->
          <div class="w-44 bg-[#1E2540] rounded-lg p-3 hidden sm:flex flex-col gap-4 overflow-y-auto max-h-[600px]">
            <!-- Thumb 1 Active -->
            <div class="border-2 border-blue-500 bg-white p-2 rounded shadow text-center cursor-pointer">
              <div class="space-y-1 py-1">
                <div class="h-1 bg-gray-300 rounded w-3/4 mx-auto"></div>
                <div class="h-1 bg-gray-200 rounded"></div>
                <div class="h-1 bg-gray-200 rounded"></div>
                <div class="h-1 bg-gray-200 rounded"></div>
              </div>
              <span class="text-[9px] text-gray-500 font-bold">1</span>
            </div>

            <!-- Thumb 2 -->
            <div class="border border-gray-600 bg-white p-2 rounded shadow text-center opacity-70 hover:opacity-100 cursor-pointer">
              <div class="space-y-1 py-1">
                <div class="h-1 bg-gray-300 rounded w-3/4 mx-auto"></div>
                <div class="h-1 bg-gray-200 rounded"></div>
                <div class="h-1 bg-gray-200 rounded"></div>
              </div>
              <span class="text-[9px] text-gray-500 font-bold">2</span>
            </div>

            <!-- Thumb 3 -->
            <div class="border border-gray-600 bg-white p-2 rounded shadow text-center opacity-70 hover:opacity-100 cursor-pointer">
              <div class="space-y-1 py-1">
                <div class="h-1 bg-gray-300 rounded w-3/4 mx-auto"></div>
                <div class="h-1 bg-gray-200 rounded"></div>
              </div>
              <span class="text-[9px] text-gray-500 font-bold">3</span>
            </div>
          </div>

          <!-- Document Sheet (White Paper) -->
          <div class="flex-1 bg-white rounded-lg shadow-2xl p-8 overflow-y-auto max-h-[600px]">
            <!-- Header -->
            <div class="text-left mb-6">
              <h2 class="text-xs font-bold text-gray-800 uppercase">LA CONCHITA RICA PEÑA CRIOLLA S.A.C</h2>
              <p class="text-[10px] text-gray-600 uppercase">AV. VIRGEN DOLOROSA CDRA 14 - LA BANDA DE SHILCAYO - SAN MARTIN - SAN MARTIN</p>
              <p class="text-[10px] text-gray-600">RUC: 20614072677</p>

              <h1 class="text-center font-bold text-sm text-gray-900 mt-6 tracking-wider">PRODUCTOS - INSUMOS</h1>
            </div>

            <!-- Formulas Group Table -->
            <div class="space-y-4">
              <div v-for="(group, idx) in formulas" :key="idx" class="space-y-1">
                <h3 class="text-xs font-bold text-gray-800 tracking-wide uppercase">{{ group.titulo }}</h3>
                <table class="w-full border border-gray-400 text-left text-[11px]">
                  <thead class="bg-gray-200 text-gray-800 border-b border-gray-400">
                    <tr>
                      <th class="py-1 px-2.5 font-bold border-r border-gray-300">Insumos</th>
                      <th class="py-1 px-2.5 font-bold text-center border-r border-gray-300 w-28">Cantidad</th>
                      <th class="py-1 px-2.5 font-bold text-center w-28">Descuento</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-300 bg-white">
                    <tr v-for="(row, rIdx) in group.filas" :key="rIdx">
                      <td class="py-1 px-2.5 border-r border-gray-300 font-medium uppercase text-gray-800">{{ row.insumo }}</td>
                      <td class="py-1 px-2.5 border-r border-gray-300 text-center text-gray-700">{{ row.cantidad }}</td>
                      <td class="py-1 px-2.5 text-center text-gray-700">{{ row.descuento }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
