<script setup>
import { Link } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Avatar, AvatarFallback } from '@/Components/ui/avatar'
import {
  Bell,
  Settings,
  User,
  ConciergeBell,
  Receipt,
  ReceiptText,
} from 'lucide-vue-next'
</script>

<template>
  <header class="h-12 bg-[#1A1D27] text-white flex items-center justify-between px-0 sm:pr-4 z-20 shrink-0 shadow-md select-none">
    <!-- Logo Mishky POS -->
    <div class="flex items-center h-full">
      <Link
        :href="route('area_ingreso')"
        class="h-full w-56 bg-[#F0302E] hover:bg-[#E02624] transition-colors text-white px-5 sm:px-6 flex items-center gap-2.5 cursor-pointer shadow-md"
        title="Regresar a Área de Ingreso"
      >
        <svg class="w-5 h-5 shrink-0 fill-current" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79.09.32.22.63.38.92.69 1.24 1.94 2.16 3.41 2.45.31.06.63.09.96.09 1.18 0 2.26-.45 3.09-1.19.86-.77 1.41-1.87 1.45-3.1.02-.75-.15-1.46-.46-2.09-.16-.32-.36-.62-.6-.88-.47-.52-1.07-.92-1.75-1.16-.34-.12-.7-.2-1.08-.23-.1-.01-.2-.01-.3-.01-.64 0-1.24.15-1.78.41C8.36 5.92 9.09 4.3 11 3.5c-.5.5-.88 1.14-1.07 1.87-.22.84-.11 1.7.27 2.44.25.48.62.88 1.07 1.17.65.42 1.44.57 2.21.41.97-.2 1.78-.81 2.22-1.66.19-.36.3-.76.32-1.18 1.83 1.55 3 3.86 3 6.45 0 3.88-2.77 7.12-6.52 7.82l-.5.08z"/>
        </svg>
        <div class="flex flex-col">
          <span class="font-serif tracking-wider font-extrabold text-sm text-white leading-none uppercase">MISHKY POS</span>
          <span class="text-[8.5px] tracking-tight font-serif italic text-white/90 leading-tight mt-0.5">RIQUISIMOS PIZZITA</span>
        </div>
      </Link>
    </div>

    <!-- Acciones Rápidas -->
    <div class="flex items-center gap-2 sm:gap-3">
      <Link :href="route('configuracion_caja')">
        <Button
          variant="outline"
          size="sm"
          class="bg-[#121520] hover:bg-[#202535] text-gray-200 hover:text-white border-[#2A2F45] rounded-md px-3 py-1 h-7 text-xs font-medium flex items-center gap-1.5 shadow-sm transition-colors"
        >
          <Receipt class="w-3.5 h-3.5 text-blue-400" /><span>Caja</span>
        </Button>
      </Link>
      <Link :href="route('area_ingreso')">
        <Button
          variant="outline"
          size="sm"
          class="bg-[#121520] hover:bg-[#202535] text-gray-200 hover:text-white border-[#2A2F45] rounded-md px-3 py-1 h-7 text-xs font-medium flex items-center gap-1.5 shadow-sm transition-colors"
        >
          <ConciergeBell class="w-3.5 h-3.5 text-blue-400" /><span>Mozos</span>
        </Button>
      </Link>
      <Link :href="route('dashboard')">
        <Button
          variant="outline"
          size="sm"
          class="bg-[#121520] hover:bg-[#202535] text-gray-200 hover:text-white border-[#2A2F45] rounded-md px-3 py-1 h-7 text-xs font-medium flex items-center gap-1.5 shadow-sm transition-colors"
        >
          <ReceiptText class="w-3.5 h-3.5 text-blue-400" /><span>Comandas</span>
        </Button>
      </Link>
    </div>

    <!-- Notificaciones / Avatar y Logout -->
    <div class="flex items-center gap-3 pr-3">
      <button class="text-gray-300 hover:text-white transition-colors relative p-1 rounded-md hover:bg-[#2A2F45]" title="Notificaciones">
        <Bell class="w-4 h-4" />
        <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-[#F0302E] rounded-full"></span>
      </button>
      <Link :href="route('parametros_sistema')" class="text-gray-300 hover:text-white transition-colors p-1 rounded-md hover:bg-[#2A2F45]" title="Configuración">
        <Settings class="w-4 h-4 text-blue-400" />
      </Link>
      <Link :href="route('profile.edit')">
        <Avatar class="w-7 h-7 bg-[#2563EB] text-white cursor-pointer hover:ring-2 hover:ring-blue-400 transition-all">
          <AvatarFallback class="bg-[#2563EB] text-white font-semibold text-xs flex items-center justify-center">
            <User class="w-4 h-4" />
          </AvatarFallback>
        </Avatar>
      </Link>
      <Link
        :href="route('logout')"
        method="post"
        as="button"
        class="text-xs text-gray-400 hover:text-red-400 px-2 py-1 rounded transition-colors"
        title="Cerrar Sesión"
      >
        Salir
      </Link>
    </div>
  </header>
</template>
