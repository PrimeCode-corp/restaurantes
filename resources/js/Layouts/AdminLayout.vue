<script setup>
import AppHeader from '@/Components/layout/AppHeader.vue'
import AppSidebar from '@/Components/layout/AppSidebar.vue'

defineProps({
  activeNav: {
    type: String,
    default: 'admin-sistema',
  },
  activeSubmenu: {
    type: String,
    default: 'sucursales',
  },
})
</script>

<template>
  <div class="w-full h-screen flex flex-col bg-[#F1F3F5] text-gray-800 overflow-hidden font-sans select-none">
    <!-- Topbar común -->
    <AppHeader />

    <!-- Contenedor Principal: Sidebar + Contenido de Página -->
    <div class="flex-1 flex overflow-hidden">
      <!-- Sidebar común -->
      <AppSidebar
        :active-nav="activeNav"
        :active-submenu="activeSubmenu"
      />

      <!-- Contenido Principal Dinámico -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-7 space-y-4">
        <slot />
      </main>
    </div>
  </div>
</template>
