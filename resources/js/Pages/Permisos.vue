<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTablePagination from '@/Components/ui/DataTablePagination.vue'
import { Card } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Switch } from '@/Components/ui/switch'
import {
  Table,
  TableHeader,
  TableBody,
  TableRow,
  TableHead,
  TableCell,
} from '@/Components/ui/table'
import {
  ChevronDown,
  ChevronRight,
  Folder,
  Save,
  CheckCircle2,
  ShieldCheck,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS DE ROLES (Panel Izquierdo)
// -------------------------------------------------------------------
const roles = ref([
  {
    id: 'admin',
    descripcion: 'ADMINISTRADOR',
    moduloDefault: 'VENTAS',
    permisos: {
      'admin-sistema': { active: true, ver: true, crear: true, editar: true, eliminar: true },
      'admin-empleado': { active: true, ver: true, crear: true, editar: true, eliminar: true },
      'almacenes': { active: true, ver: true, crear: true, editar: true, eliminar: true },
      'compras': { active: true, ver: true, crear: true, editar: true, eliminar: true },
      'finanzas': { active: true, ver: true, crear: true, editar: true, eliminar: true },
      'mantenimientos': { active: true, ver: true, crear: true, editar: true, eliminar: true },
      'ventas': { active: true, ver: true, crear: true, editar: true, eliminar: true },
    },
  },
  {
    id: 'mozos',
    descripcion: 'MOZOS',
    moduloDefault: 'VENTAS',
    permisos: {
      'admin-sistema': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'admin-empleado': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'almacenes': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'compras': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'finanzas': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'mantenimientos': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'ventas': { active: true, ver: true, crear: true, editar: false, eliminar: false },
    },
  },
  {
    id: 'preparacion',
    descripcion: 'PREPARACION',
    moduloDefault: 'VENTAS',
    permisos: {
      'admin-sistema': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'admin-empleado': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'almacenes': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'compras': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'finanzas': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'mantenimientos': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'ventas': { active: true, ver: true, crear: false, editar: false, eliminar: false },
    },
  },
  {
    id: 'ventas',
    descripcion: 'VENTAS',
    moduloDefault: 'PREPARACION',
    permisos: {
      'admin-sistema': { active: true, ver: true, crear: false, editar: false, eliminar: false },
      'admin-empleado': { active: true, ver: true, crear: false, editar: false, eliminar: false },
      'almacenes': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'compras': { active: true, ver: true, crear: false, editar: false, eliminar: false },
      'finanzas': { active: true, ver: true, crear: false, editar: false, eliminar: false },
      'mantenimientos': { active: false, ver: false, crear: false, editar: false, eliminar: false },
      'ventas': { active: true, ver: true, crear: true, editar: true, eliminar: false },
    },
  },
])

// -------------------------------------------------------------------
// DEFINICIÓN DE MÓDULOS DE PERMISOS (Panel Derecho)
// -------------------------------------------------------------------
const modulosDisponibles = [
  { id: 'admin-sistema', label: 'ADMINISTRACION DEL SISTEMA' },
  { id: 'admin-empleado', label: 'ADMINISTRAR EMPLEADO' },
  { id: 'almacenes', label: 'ALMACENES' },
  { id: 'compras', label: 'COMPRAS' },
  { id: 'finanzas', label: 'FINANZAS' },
  { id: 'mantenimientos', label: 'MANTENIMIENTOS' },
  { id: 'ventas', label: 'VENTAS' },
]

// Rol activo seleccionado
const selectedRoleId = ref('ventas')
const expandedModules = ref({
  'admin-sistema': false,
  'ventas': true,
})

const selectedRole = computed(() => {
  return roles.value.find((r) => r.id === selectedRoleId.value) || roles.value[0]
})

// Paginación de la tabla izquierda
const rowsPerPage = ref(10)
const currentPage = ref(1)
const totalPages = computed(() => Math.max(1, Math.ceil(roles.value.length / rowsPerPage.value)))
const paginatedRoles = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return roles.value.slice(start, start + rowsPerPage.value)
})

// Toggle para expandir/colapsar un módulo
function toggleExpand(modId) {
  expandedModules.value[modId] = !expandedModules.value[modId]
}

// Toggle maestro de módulo
function toggleModule(modId, active) {
  if (selectedRole.value.permisos[modId]) {
    selectedRole.value.permisos[modId].active = active
    if (!active) {
      selectedRole.value.permisos[modId].ver = false
      selectedRole.value.permisos[modId].crear = false
      selectedRole.value.permisos[modId].editar = false
      selectedRole.value.permisos[modId].eliminar = false
    } else {
      selectedRole.value.permisos[modId].ver = true
    }
  }
}

// Feedback al guardar / actualizar
const showSuccessToast = ref(false)

function actualizarPermisos() {
  showSuccessToast.value = true
  setTimeout(() => {
    showSuccessToast.value = false
  }, 3500)
}
</script>

<template>
  <AdminLayout active-nav="admin-sistema" active-submenu="permisos">
    <!-- Título de Página y Botón Actualizar -->
    <div class="flex items-center justify-between gap-4">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Permisos
        </h1>
      </div>

      <div class="flex items-center gap-3">
        <div v-if="showSuccessToast" class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg animate-in fade-in">
          <CheckCircle2 class="w-4 h-4 text-emerald-600" />
          <span>¡Permisos de {{ selectedRole?.descripcion }} actualizados!</span>
        </div>

        <Button
          size="sm"
          class="bg-[#F0302E] hover:bg-[#D02724] text-white rounded-lg px-5 py-2 h-9 text-xs font-bold tracking-wider flex items-center gap-2 shadow-sm transition-all"
          @click="actualizarPermisos"
        >
          <Save class="w-4 h-4 stroke-[2.5]" />
          <span>ACTUALIZAR</span>
        </Button>
      </div>
    </div>

    <!-- CONTENEDOR PRINCIPAL DIVIDIDO EN 2 PANELES (Fiel a las imágenes 2 y 3) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 min-h-[540px]">
      <!-- ====================================================================
           PANEL IZQUIERDO: LISTA DE ROLES (45%)
           ==================================================================== -->
      <Card class="lg:col-span-5 bg-white border border-gray-200/90 shadow-sm rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
        <div>
          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <Table>
              <TableHeader class="bg-[#232738] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-3/5">Descripción</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-2/5">Cant. Permisos</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="rol in paginatedRoles"
                  :key="rol.id"
                  class="cursor-pointer border-b border-gray-200 transition-all select-none"
                  :class="[
                    selectedRoleId === rol.id
                      ? 'bg-blue-100/75 text-blue-900 font-bold hover:bg-blue-100'
                      : 'hover:bg-gray-50/80 text-gray-800'
                  ]"
                  @click="selectedRoleId = rol.id"
                >
                  <TableCell class="py-3 px-4 text-xs font-semibold">
                    <div class="flex items-center gap-2">
                      <span
                        class="w-2 h-2 rounded-full shrink-0"
                        :class="[selectedRoleId === rol.id ? 'bg-blue-600' : 'bg-transparent']"
                      ></span>
                      <span>{{ rol.descripcion }}</span>
                    </div>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-center font-medium">
                    {{ rol.moduloDefault }}
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <!-- PAGINACIÓN TABLA IZQUIERDA -->
        <div class="mt-4 pt-2">
          <DataTablePagination
            v-model:current-page="currentPage"
            v-model:rows-per-page="rowsPerPage"
            :total-pages="totalPages"
          />
        </div>
      </Card>

      <!-- ====================================================================
           PANEL DERECHO: MATRIZ DE PERMISOS (55%)
           ==================================================================== -->
      <Card class="lg:col-span-7 bg-white border border-gray-200/90 shadow-sm rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden">
        <div>
          <!-- Cabecera de la tabla de permisos -->
          <div class="rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-[#232738] text-white px-5 py-2.5 flex items-center justify-between text-xs font-semibold">
              <div class="flex items-center gap-2">
                <ShieldCheck class="w-4 h-4 text-blue-400" />
                <span>Permisos para: <strong class="text-white uppercase">{{ selectedRole?.descripcion }}</strong></span>
              </div>
              <span class="text-[11px] text-gray-300 font-normal">Estado de Acceso</span>
            </div>

            <!-- Lista de módulos con Switches -->
            <div class="divide-y divide-gray-200 bg-white">
              <div
                v-for="mod in modulosDisponibles"
                :key="mod.id"
                class="transition-colors"
              >
                <!-- Fila principal de módulo -->
                <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50/70">
                  <div class="flex items-center gap-2.5">
                    <!-- Botón para expandir/colapsar subpermisos -->
                    <button
                      type="button"
                      class="text-gray-400 hover:text-gray-700 p-0.5 rounded transition-colors"
                      @click="toggleExpand(mod.id)"
                      :title="expandedModules[mod.id] ? 'Colapsar' : 'Expandir'"
                    >
                      <ChevronDown v-if="expandedModules[mod.id]" class="w-4 h-4 text-gray-600" />
                      <ChevronRight v-else class="w-4 h-4 text-gray-400" />
                    </button>

                    <!-- Ícono de Carpeta -->
                    <Folder class="w-4 h-4 text-amber-500 fill-amber-100 shrink-0" />

                    <!-- Nombre del Módulo -->
                    <span class="text-xs font-semibold text-gray-800 tracking-tight">
                      {{ mod.label }}
                    </span>
                  </div>

                  <!-- Switch ON/OFF -->
                  <div>
                    <Switch
                      :checked="selectedRole?.permisos[mod.id]?.active || false"
                      @update:checked="toggleModule(mod.id, $event)"
                    />
                  </div>
                </div>

                <!-- Desglose de subpermisos cuando está expandido -->
                <div
                  v-if="expandedModules[mod.id]"
                  class="bg-gray-50/80 px-10 py-2.5 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs"
                >
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      v-model="selectedRole.permisos[mod.id].ver"
                      :disabled="!selectedRole.permisos[mod.id].active"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="text-[11px] text-gray-700 font-medium">Ver</span>
                  </label>

                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      v-model="selectedRole.permisos[mod.id].crear"
                      :disabled="!selectedRole.permisos[mod.id].active"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="text-[11px] text-gray-700 font-medium">Crear</span>
                  </label>

                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      v-model="selectedRole.permisos[mod.id].editar"
                      :disabled="!selectedRole.permisos[mod.id].active"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="text-[11px] text-gray-700 font-medium">Editar</span>
                  </label>

                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      v-model="selectedRole.permisos[mod.id].eliminar"
                      :disabled="!selectedRole.permisos[mod.id].active"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="text-[11px] text-gray-700 font-medium">Eliminar</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pt-4 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 mt-4">
          <span>Selecciona un rol a la izquierda para configurar sus accesos.</span>
          <span class="font-medium text-gray-700">Cambios se guardan con el botón ACTUALIZAR.</span>
        </div>
      </Card>
    </div>
  </AdminLayout>
</template>
