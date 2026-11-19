<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { Card } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { LogOut, ChevronRight } from 'lucide-vue-next'

const hoveredModule = ref(null)
const selectedModule = ref(null)

const modules = [
  {
    id: 'administracion',
    title: 'ADMINISTRACION',
    subtitle: 'Administrar Mishky Pos',
    iconType: 'admin',
    route: 'empleados',
  },
  {
    id: 'caja',
    title: 'CAJA',
    subtitle: 'Cobros y control de ventas',
    iconType: 'caja',
    route: 'configuracion_caja',
  },
  {
    id: 'caja-touch',
    title: 'CAJA TOUCH',
    subtitle: 'Cobros con pantalla táctil',
    iconType: 'touch',
    route: 'dashboard',
  },
  {
    id: 'delivery',
    title: 'DELIVERY',
    subtitle: 'Organizar entregas',
    iconType: 'delivery',
    route: 'dashboard',
  },
  {
    id: 'caja-rapida',
    title: 'CAJA RÁPIDA',
    subtitle: 'Atención ágil y simplificada',
    iconType: 'fast',
    route: 'configuracion_caja',
  },
  {
    id: 'atencion-mozos',
    title: 'ATENCIÓN MOZOS',
    subtitle: 'Registro y control de mesas',
    iconType: 'mozos',
    route: 'dashboard',
  },
  {
    id: 'cocina',
    title: 'COCINA',
    subtitle: 'Gestión de pedidos en cocina',
    iconType: 'cocina',
    route: 'dashboard',
  },
]

const handleModuleClick = (mod) => {
  selectedModule.value = mod.id
  if (mod.route) {
    router.visit(route(mod.route))
  }
}

const handleExit = () => {
  router.post(route('logout'))
}
</script>

<template>
  <Head title="Área de Ingreso - Mishky POS" />
  <div class="relative w-full h-screen min-h-[640px] overflow-hidden bg-[#ECEEF2] flex flex-col justify-between select-none">
    <!-- PATRÓN DE FONDO (Comandas/Recibos estilo watermark) -->
    <div class="receipt-bg-pattern absolute inset-0 pointer-events-none z-0"></div>

    <!-- HEADER SUPERIOR -->
    <header class="relative z-10 w-full flex items-start justify-between bg-[#1A1D27]">
      <!-- LOGO MISHKY POS (Apegado al borde superior izquierdo) -->
      <div class="bg-[#F0302E] hover:bg-[#E02624] transition-colors text-white px-7 py-3 shadow-md flex items-center gap-3">
        <svg class="w-6 h-6 shrink-0 fill-current" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79.09.32.22.63.38.92.69 1.24 1.94 2.16 3.41 2.45.31.06.63.09.96.09 1.18 0 2.26-.45 3.09-1.19.86-.77 1.41-1.87 1.45-3.1.02-.75-.15-1.46-.46-2.09-.16-.32-.36-.62-.6-.88-.47-.52-1.07-.92-1.75-1.16-.34-.12-.7-.2-1.08-.23-.1-.01-.2-.01-.3-.01-.64 0-1.24.15-1.78.41C8.36 5.92 9.09 4.3 11 3.5c-.5.5-.88 1.14-1.07 1.87-.22.84-.11 1.7.27 2.44.25.48.62.88 1.07 1.17.65.42 1.44.57 2.21.41.97-.2 1.78-.81 2.22-1.66.19-.36.3-.76.32-1.18 1.83 1.55 3 3.86 3 6.45 0 3.88-2.77 7.12-6.52 7.82l-.5.08z"/>
        </svg>
        <span class="font-serif tracking-wider font-extrabold text-sm sm:text-[15px] text-white select-none uppercase">
          MISHKY POS
        </span>
      </div>

      <!-- BOTÓN SALIR -->
      <div class="p-2">
        <Button
          variant="outline"
          size="sm"
          class="bg-[#1A1D27] hover:bg-[#252937] text-gray-300 hover:text-white border border-[#2D3244] hover:border-gray-500 rounded-lg px-3.5 py-1.5 text-xs font-semibold tracking-wider flex items-center gap-2 shadow-sm transition-all duration-200 cursor-pointer"
          @click="handleExit"
        >
          <span class="tracking-wide">SALIR</span>
          <LogOut class="w-3.5 h-3.5 text-red-500 rotate-180" />
        </Button>
      </div>
    </header>

    <!-- CONTENEDOR CENTRAL ("ÁREA DE INGRESO") -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-8 py-4">
      <div class="w-full max-w-[1240px] bg-[#1F222F] text-white rounded-[28px] px-8 sm:px-12 py-8 sm:py-10 shadow-[0_24px_55px_rgba(0,0,0,0.4)] border border-white/5 transition-all">
        <!-- TÍTULO PRINCIPAL -->
        <div class="text-center mb-7 sm:mb-9">
          <h1 class="font-serif text-2xl sm:text-[28px] font-bold tracking-[0.16em] text-[#F3F4F6] uppercase">
            Área de Ingreso
          </h1>
        </div>

        <!-- GRILLA DE MÓDULOS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <template v-for="mod in modules" :key="mod.id">
            <!-- TARJETA CON shadcn-vue Card -->
            <Card
              class="group relative overflow-hidden bg-[#F6F4F2] hover:bg-white text-gray-900 rounded-[18px] border border-[#E5E7EB] hover:border-[#F0302E]/60 shadow-[0_4px_12px_rgba(0,0,0,0.06)] hover:shadow-[0_12px_28px_rgba(0,0,0,0.18)] transition-all duration-200 cursor-pointer p-5 flex flex-col justify-between min-h-[145px] active:scale-[0.98]"
              :class="{
                'ring-2 ring-[#F0302E] border-[#F0302E] bg-white': selectedModule === mod.id
              }"
              @click="handleModuleClick(mod)"
            >
              <!-- ÍCONO ROJO EN CONTENEDOR REDONDEADO -->
              <div class="mb-4">
                <div class="w-11 h-11 rounded-[12px] bg-[#F0302E] text-white flex items-center justify-center shadow-md shadow-[#F0302E]/25 transition-transform duration-200 group-hover:scale-105">
                  <svg v-if="mod.iconType === 'admin'" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm2.8 13.8l-.5.3.1.6c.03.14.05.28.05.42 0 .14-.02.28-.05.42l-.1.6.5.3c.12.07.16.22.1.34l-.46.8c-.06.11-.19.16-.31.12l-.61-.23c-.21.18-.45.32-.71.42l-.15.61c-.03.13-.14.22-.27.22h-.92c-.13 0-.24-.09-.27-.22l-.15-.61c-.26-.1-.5-.24-.71-.42l-.61.23c-.12.04-.25-.01-.31-.12l-.46-.8c-.06-.12-.02-.27.1-.34l.5-.3-.1-.6c-.03-.14-.05-.28-.05-.42 0-.14.02-.28.05-.42l.1-.6-.5-.3c-.12-.07-.16-.22-.1-.34l.46-.8c.06-.11.19-.16.31-.12l.61.23c.21-.18.45-.32.71-.42l.15-.61c.03-.13.14-.22.27-.22h.92c.13 0 .24.09.27.22l.15.61c.26.1.5.24.71.42l.61-.23c.12-.04.25.01.31.12l.46.8c.06.12.02.27-.1.34zm-2.8-.2c-.88 0-1.6.72-1.6 1.6s.72 1.6 1.6 1.6 1.6-.72 1.6-1.6-.72-1.6-1.6-1.6z"/>
                  </svg>
                  <svg v-else-if="mod.iconType === 'caja'" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                    <path d="M19 7h-3V5c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM9 5h6v2H9V5zm10 14H5v-2h14v2zm0-4H5V9h14v6zm-10-4h2v2H9v-2zm4 0h2v2h-2v-2zm-4 3h2v1H9v-1zm4 0h2v1h-2v-1z"/>
                  </svg>
                  <svg v-else-if="mod.iconType === 'touch'" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 6H4V6h16v4zm0 8H4v-6h16v6z"/>
                    <rect x="6" y="12" width="5" height="4" rx="0.5" fill="#F0302E"/>
                    <rect x="13" y="12" width="5" height="4" rx="0.5" fill="#F0302E"/>
                  </svg>
                  <svg v-else-if="mod.iconType === 'delivery'" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                    <path d="M19.5 13c-1.93 0-3.5 1.57-3.5 3.5s1.57 3.5 3.5 3.5 3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5zm0 5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM6 13c-1.93 0-3.5 1.57-3.5 3.5S4.07 20 6 20s3.5-1.57 3.5-3.5S7.93 13 6 13zm0 5c-.83 0-1.5-.67-1.5-1.5S5.17 15 6 15s1.5.67 1.5 1.5S6.83 18 6 18zm8.6-8.2L12.9 6.2c-.38-.49-.96-.78-1.58-.78H7.5c-.83 0-1.5.67-1.5 1.5v1h2v-1h2.8l1.4 2.3H8.1l-1 2H13l1.8 3.5c.34.66 1.03 1.08 1.77 1.08h1.43v-2H16.6l-1.3-2.5h2.5c.78 0 1.44-.55 1.58-1.32l.62-3.36h1v-2h-3.4l-4 3.7z"/>
                  </svg>
                  <svg v-else-if="mod.iconType === 'fast'" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                    <path d="M11 21h-1l1-7H7.5a.5.5 0 0 1-.4-.8l6-10a.5.5 0 0 1 .9.5L13 9h3.5a.5.5 0 0 1 .4.8l-6 11a.5.5 0 0 1-.9.2z"/>
                  </svg>
                  <svg v-else-if="mod.iconType === 'mozos'" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                    <path d="M12 4a1 1 0 0 1 1 1c0 .28-.11.53-.29.71A8.99 8.99 0 0 1 21 14h1a1 1 0 1 1 0 2H2a1 1 0 1 1 0-2h1a8.99 8.99 0 0 1 8.29-8.29A1 1 0 0 1 12 4zm-7 13h14a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2z"/>
                  </svg>
                  <svg v-else-if="mod.iconType === 'cocina'" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                    <path d="M12 3a4.5 4.5 0 0 0-4.47 4.02A3.5 3.5 0 0 0 5 10.5 3.5 3.5 0 0 0 7.5 14h9a3.5 3.5 0 0 0 2.5-3.5 3.5 3.5 0 0 0-2.53-3.48A4.5 4.5 0 0 0 12 3zm-5 13h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1z"/>
                  </svg>
                </div>
              </div>

              <!-- TÍTULO, SUBTÍTULO Y CHEVRON -->
              <div class="flex items-end justify-between gap-2 mt-auto">
                <div class="flex-1 pr-1">
                  <h2 class="font-serif font-bold text-gray-900 text-sm sm:text-[15px] tracking-wider leading-tight uppercase mb-1 group-hover:text-black">
                    {{ mod.title }}
                  </h2>
                  <p class="text-xs text-gray-500 font-normal leading-tight">
                    {{ mod.subtitle }}
                  </p>
                </div>

                <!-- FLECHA DERECHA -->
                <div class="text-gray-400 group-hover:text-gray-900 transition-all duration-200 transform group-hover:translate-x-1 shrink-0 pb-0.5">
                  <ChevronRight class="w-4 h-4 stroke-[2.5]" />
                </div>
              </div>
            </Card>
          </template>
        </div>
      </div>
    </main>

    <div class="h-3 pointer-events-none"></div>
  </div>
</template>

<style scoped>
.font-serif {
  font-family: 'Cinzel', Georgia, serif;
}

.receipt-bg-pattern {
  background-image: url('/images/patron-tickets.jpg');
  background-size: cover;
  background-position: center;
  opacity: 0.15;
}
</style>
