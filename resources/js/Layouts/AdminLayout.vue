<script setup>
import { ref } from 'vue'
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

// Control del sidebar en pantallas móviles
const mobileSidebarOpen = ref(false)

function toggleMobileSidebar() {
  mobileSidebarOpen.value = !mobileSidebarOpen.value
}

function closeMobileSidebar() {
  mobileSidebarOpen.value = false
}
</script>

<template>
  <div class="w-full h-screen flex flex-col bg-[#F1F3F5] text-gray-800 overflow-hidden font-sans select-none">
    <!-- Topbar común con soporte móvil -->
    <AppHeader
      :mobile-sidebar-open="mobileSidebarOpen"
      @toggle-mobile-sidebar="toggleMobileSidebar"
    />

    <!-- Contenedor Principal: Sidebar + Contenido de Página -->
    <div class="flex-1 flex overflow-hidden relative">
      <!-- Overlay oscuro para backdrop móvil -->
      <transition
        enter-active-class="transition-opacity ease-linear duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity ease-linear duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="mobileSidebarOpen"
          class="fixed inset-0 bg-black/60 z-30 lg:hidden backdrop-blur-xs"
          @click="closeMobileSidebar"
        />
      </transition>

      <!-- Sidebar común: fijo/desplegable en móvil, estático en lg+ -->
      <div
        class="fixed inset-y-0 left-0 z-40 transform transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex"
        :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
      >
        <AppSidebar
          :active-nav="activeNav"
          :active-submenu="activeSubmenu"
          @close-mobile="closeMobileSidebar"
        />
      </div>

      <!-- Contenido Principal Dinámico -->
      <main class="flex-1 overflow-y-auto p-3 sm:p-5 lg:p-7 space-y-4 w-full min-w-0">
        <slot />
      </main>
    </div>
  </div>
</template>
