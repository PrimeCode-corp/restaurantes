<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { ChevronLeft, ChevronDown } from 'lucide-vue-next'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalPages: {
    type: Number,
    required: true,
  },
  rowsPerPage: {
    type: Number,
    default: 10,
  },
  rowsPerPageOptions: {
    type: Array,
    default: () => [5, 10, 20, 50],
  },
})

const emit = defineEmits(['update:currentPage', 'update:rowsPerPage', 'page-change'])

const isDropdownOpen = ref(false)
const dropdownRef = ref(null)

const selectRowsPerPage = (opt) => {
  emit('update:rowsPerPage', opt)
  emit('update:currentPage', 1)
  isDropdownOpen.value = false
}

const prevPage = () => {
  if (props.currentPage > 1) {
    emit('update:currentPage', props.currentPage - 1)
    emit('page-change', props.currentPage - 1)
  }
}

const nextPage = () => {
  if (props.currentPage < props.totalPages) {
    emit('update:currentPage', props.currentPage + 1)
    emit('page-change', props.currentPage + 1)
  }
}

const handleClickOutside = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isDropdownOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  window.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <div class="flex flex-wrap items-center justify-between sm:justify-start gap-2 select-none w-full sm:w-auto">
    <!-- Selector de registros por página -->
    <div ref="dropdownRef" class="relative">
      <button
        type="button"
        class="flex items-center gap-1.5 border border-gray-300 rounded-md px-2.5 py-1 bg-white text-xs font-medium text-gray-700 shadow-xs hover:border-gray-400 hover:bg-gray-50 transition-colors"
        @click.stop="isDropdownOpen = !isDropdownOpen"
      >
        <span>{{ rowsPerPage }}</span>
        <ChevronDown class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': isDropdownOpen }" />
      </button>

      <!-- Menú desplegable -->
      <div
        v-if="isDropdownOpen"
        class="absolute bottom-full left-0 mb-1 w-16 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-30"
      >
        <button
          v-for="opt in rowsPerPageOptions"
          :key="opt"
          type="button"
          class="w-full text-center px-2 py-1 text-xs text-gray-700 hover:bg-gray-100 transition-colors font-medium"
          :class="{ 'bg-gray-100 font-bold text-gray-900': opt === rowsPerPage }"
          @click="selectRowsPerPage(opt)"
        >
          {{ opt }}
        </button>
      </div>
    </div>

    <div class="flex items-center gap-1.5 sm:gap-2">
      <!-- Botón Página Anterior -->
      <button
        type="button"
        class="w-7 h-7 rounded border border-gray-300 bg-white hover:bg-gray-100 text-gray-600 flex items-center justify-center text-xs transition-colors shadow-xs disabled:opacity-40 disabled:cursor-not-allowed"
        :disabled="currentPage <= 1"
        title="Página anterior"
        @click="prevPage"
      >
        <ChevronLeft class="w-4 h-4" />
      </button>

      <!-- Indicador de página -->
      <span class="text-xs text-gray-600 font-medium px-1 sm:px-1.5 whitespace-nowrap">
        Pág. {{ currentPage }} de {{ totalPages }}
      </span>

      <!-- Botón Página Siguiente -->
      <button
        type="button"
        class="w-7 h-7 rounded border border-gray-300 bg-white hover:bg-gray-100 text-gray-600 flex items-center justify-center text-xs transition-colors shadow-xs disabled:opacity-40 disabled:cursor-not-allowed"
        :disabled="currentPage >= totalPages"
        title="Página siguiente"
        @click="nextPage"
      >
        <ChevronLeft class="w-4 h-4 rotate-180" />
      </button>
    </div>
  </div>
</template>
