<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTablePagination from '@/Components/ui/DataTablePagination.vue'
import { Card } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
  Table,
  TableHeader,
  TableBody,
  TableRow,
  TableHead,
  TableCell,
} from '@/Components/ui/table'
import {
  Search,
  Plus,
  Eye,
  Pencil,
  Trash2,
  X,
  Layers,
  TriangleAlert,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS LOCALES DE MÓDULOS (Fiel a la imagen 3)
// -------------------------------------------------------------------
const modulos = ref([
  {
    id: 1,
    dependencia: 'ADMINISTRACION DEL SISTEMA',
    descripcion: 'PERFIL',
    url: 'index.php/perfil',
    visibilidad: 'AVANZADO',
  },
  {
    id: 2,
    dependencia: 'ADMINISTRACION DEL SISTEMA',
    descripcion: 'EMPRESAS',
    url: 'index.php/empresa',
    visibilidad: 'AVANZADO',
  },
  {
    id: 3,
    dependencia: 'ADMINISTRACION DEL SISTEMA',
    descripcion: 'SUCURSALES',
    url: 'index.php/sucursales',
    visibilidad: 'AVANZADO',
  },
  {
    id: 4,
    dependencia: 'ADMINISTRACION DEL SISTEMA',
    descripcion: 'CONFIGURACIÓN CAJA',
    url: 'index.php/config_caja',
    visibilidad: 'AVANZADO',
  },
  {
    id: 5,
    dependencia: 'ADMINISTRACION DEL SISTEMA',
    descripcion: 'IMPRESORAS',
    url: 'index.php/impresoras',
    visibilidad: 'AVANZADO',
  },
  {
    id: 6,
    dependencia: 'ADMINISTRACION DEL SISTEMA',
    descripcion: 'PARÁMETROS DEL SISTEMA',
    url: 'index.php/parametros',
    visibilidad: 'AVANZADO',
  },
  {
    id: 7,
    dependencia: 'VENTAS',
    descripcion: 'PUNTO DE VENTA (POS)',
    url: 'index.php/pos',
    visibilidad: 'BÁSICO',
  },
  {
    id: 8,
    dependencia: 'VENTAS',
    descripcion: 'COMANDAS Y MESAS',
    url: 'index.php/comandas',
    visibilidad: 'BÁSICO',
  },
  {
    id: 9,
    dependencia: 'ALMACENES',
    descripcion: 'CONTROL DE STOCK',
    url: 'index.php/almacen',
    visibilidad: 'AVANZADO',
  },
  {
    id: 10,
    dependencia: 'FINANZAS',
    descripcion: 'ARQUEO Y MOVIMIENTOS',
    url: 'index.php/finanzas',
    visibilidad: 'ADMINISTRADOR',
  },
])

// -------------------------------------------------------------------
// BÚSQUEDA Y PAGINACIÓN
// -------------------------------------------------------------------
const searchQuery = ref('')
const rowsPerPage = ref(10)
const currentPage = ref(1)

const filtered = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  if (!q) return modulos.value
  return modulos.value.filter(
    (m) =>
      m.dependencia.toLowerCase().includes(q) ||
      m.descripcion.toLowerCase().includes(q) ||
      m.url.toLowerCase().includes(q) ||
      m.visibilidad.toLowerCase().includes(q)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))

const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: CREAR / EDITAR MÓDULO
// -------------------------------------------------------------------
const showModal = ref(false)
const isEditMode = ref(false)
const formData = ref({
  id: null,
  dependencia: 'ADMINISTRACION DEL SISTEMA',
  descripcion: '',
  url: '',
  visibilidad: 'AVANZADO',
})

const dependenciasDisponibles = [
  'ADMINISTRACION DEL SISTEMA',
  'ADMINISTRAR EMPLEADO',
  'ALMACENES',
  'COMPRAS',
  'FINANZAS',
  'MANTENIMIENTOS',
  'VENTAS',
]

const nivelesVisibilidad = ['BÁSICO', 'AVANZADO', 'ADMINISTRADOR']

function openNuevoModulo() {
  isEditMode.value = false
  formData.value = {
    id: null,
    dependencia: 'ADMINISTRACION DEL SISTEMA',
    descripcion: '',
    url: '',
    visibilidad: 'AVANZADO',
  }
  showModal.value = true
}

function openEditarModulo(mod) {
  isEditMode.value = true
  formData.value = { ...mod }
  showModal.value = true
}

function guardarModulo() {
  if (!formData.value.descripcion.trim() || !formData.value.url.trim()) return

  if (isEditMode.value) {
    const idx = modulos.value.findIndex((m) => m.id === formData.value.id)
    if (idx !== -1) {
      modulos.value[idx] = { ...modulos.value[idx], ...formData.value }
    }
  } else {
    const newId = Math.max(0, ...modulos.value.map((m) => m.id)) + 1
    modulos.value.push({
      ...formData.value,
      id: newId,
    })
  }
  showModal.value = false
}

// -------------------------------------------------------------------
// MODAL: VER DETALLE
// -------------------------------------------------------------------
const showVerModal = ref(false)
const moduloSeleccionado = ref(null)

function openVerModulo(mod) {
  moduloSeleccionado.value = mod
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL: ELIMINAR MÓDULO
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const moduloAEliminar = ref(null)

function openEliminar(mod) {
  moduloAEliminar.value = mod
  showDeleteModal.value = true
}

function confirmarEliminar() {
  modulos.value = modulos.value.filter((m) => m.id !== moduloAEliminar.value.id)
  showDeleteModal.value = false
  moduloAEliminar.value = null
}
</script>

<template>
  <AdminLayout active-nav="admin-sistema" active-submenu="modulos">
    <!-- Título de Página -->
    <div>
      <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">Módulos</h1>
    </div>

    <!-- Contenedor Principal (Tarjeta estilo image 3) -->
    <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 flex flex-col justify-between min-h-[520px]">
      <div>
        <!-- Barra de herramientas: Búsqueda y Botón + NUEVO -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
          <div class="relative w-full sm:w-80">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar..."
              class="pl-9 pr-4 py-1.5 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-xs focus-visible:ring-1 focus-visible:ring-gray-400"
            />
          </div>

          <div class="flex items-center gap-3">
            <Button
              size="sm"
              class="bg-[#20233B] hover:bg-[#2B2F4C] text-white rounded-lg px-4 py-1.5 h-9 text-xs font-semibold tracking-wider flex items-center gap-1.5 shadow-sm transition-all"
              @click="openNuevoModulo"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </Button>
          </div>
        </div>

        <!-- TABLA DE MÓDULOS -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <Table>
            <TableHeader class="bg-[#232738] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">Dependencia</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">Descripción</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">URL</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/6">Visibilidad</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-28">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="mod in paginated"
                :key="mod.id"
                class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
              >
                <!-- Dependencia -->
                <TableCell class="py-3 px-4 text-xs text-gray-800 font-medium text-center">
                  {{ mod.dependencia }}
                </TableCell>

                <!-- Descripción -->
                <TableCell class="py-3 px-4 text-xs text-gray-700 text-center font-semibold">
                  {{ mod.descripcion }}
                </TableCell>

                <!-- URL -->
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center font-mono">
                  {{ mod.url }}
                </TableCell>

                <!-- Visibilidad -->
                <TableCell class="py-3 px-4 text-xs text-gray-700 text-center font-medium">
                  <span
                    class="px-2 py-0.5 rounded-md text-[10.5px] font-semibold"
                    :class="[
                      mod.visibilidad === 'ADMINISTRADOR'
                        ? 'bg-purple-100 text-purple-700 border border-purple-200'
                        : mod.visibilidad === 'AVANZADO'
                        ? 'bg-blue-100 text-blue-700 border border-blue-200'
                        : 'bg-gray-100 text-gray-700 border border-gray-200'
                    ]"
                  >
                    {{ mod.visibilidad }}
                  </span>
                </TableCell>

                <!-- Acciones -->
                <TableCell class="py-3 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Ver Detalle -->
                    <button
                      class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Ver detalles"
                      @click="openVerModulo(mod)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>

                    <!-- Editar -->
                    <button
                      class="w-6 h-6 rounded border border-emerald-300 bg-emerald-50/80 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Editar módulo"
                      @click="openEditarModulo(mod)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>

                    <!-- Eliminar -->
                    <button
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Eliminar módulo"
                      @click="openEliminar(mod)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Estado Vacío -->
              <TableRow v-if="paginated.length === 0">
                <TableCell colspan="5" class="py-12 text-center text-xs text-gray-400">
                  No se encontraron módulos registrados
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </div>

      <!-- PAGINACIÓN REUTILIZABLE -->
      <div class="mt-6 pt-2">
        <DataTablePagination
          v-model:current-page="currentPage"
          v-model:rows-per-page="rowsPerPage"
          :total-pages="totalPages"
        />
      </div>
    </Card>

    <!-- ====================================================================
         MODAL: CREAR / EDITAR MÓDULO
         ==================================================================== -->
    <div
      v-if="showModal"
      @click.self="showModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <!-- Header del Modal -->
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Layers class="w-5 h-5 text-red-500" />
            <h3 class="font-bold text-sm uppercase tracking-wide">
              {{ isEditMode ? 'Editar Módulo' : 'Nuevo Módulo' }}
            </h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Formulario -->
        <form @submit.prevent="guardarModulo" class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Dependencia / Módulo Superior *</label>
            <select
              v-model="formData.dependencia"
              class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
            >
              <option v-for="dep in dependenciasDisponibles" :key="dep" :value="dep">{{ dep }}</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Descripción / Nombre del Módulo *</label>
            <Input
              v-model="formData.descripcion"
              type="text"
              placeholder="Ej. PERFIL"
              required
              class="h-9 text-xs rounded-lg border-gray-300 uppercase"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">URL / Ruta de Acceso *</label>
            <Input
              v-model="formData.url"
              type="text"
              placeholder="Ej. index.php/perfil"
              required
              class="h-9 text-xs rounded-lg border-gray-300 font-mono"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Nivel de Visibilidad</label>
            <select
              v-model="formData.visibilidad"
              class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
            >
              <option v-for="nv in nivelesVisibilidad" :key="nv" :value="nv">{{ nv }}</option>
            </select>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <Button
              type="button"
              variant="outline"
              size="sm"
              class="border-gray-300 text-gray-700 text-xs px-4"
              @click="showModal = false"
            >
              Cancelar
            </Button>
            <Button
              type="submit"
              size="sm"
              class="bg-[#20233B] hover:bg-[#2B2F4C] text-white text-xs px-5 font-semibold"
            >
              {{ isEditMode ? 'Guardar Cambios' : 'Registrar Módulo' }}
            </Button>
          </div>
        </form>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: VER DETALLES
         ==================================================================== -->
    <div
      v-if="showVerModal && moduloSeleccionado"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Layers class="w-5 h-5 text-blue-400" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Detalles del Módulo</h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showVerModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <div class="p-6 space-y-4 text-xs">
          <div class="bg-gray-50 p-4 rounded-xl space-y-2.5 border border-gray-100">
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Dependencia:</span>
              <span class="font-bold text-gray-900">{{ moduloSeleccionado.dependencia }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Descripción:</span>
              <span class="font-semibold text-gray-800">{{ moduloSeleccionado.descripcion }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">URL:</span>
              <span class="font-mono text-gray-800">{{ moduloSeleccionado.url }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Visibilidad:</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">
                {{ moduloSeleccionado.visibilidad }}
              </span>
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <Button
              size="sm"
              class="bg-gray-800 hover:bg-gray-900 text-white text-xs px-5"
              @click="showVerModal = false"
            >
              Cerrar
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: ELIMINAR MÓDULO
         ==================================================================== -->
    <div
      v-if="showDeleteModal && moduloAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center animate-in fade-in zoom-in-95 duration-150">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
          <TriangleAlert class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-base text-gray-900 mb-1">¿Eliminar Módulo?</h3>
        <p class="text-xs text-gray-500 mb-6">
          ¿Estás seguro de que deseas eliminar el módulo <strong class="text-gray-800">"{{ moduloAEliminar.descripcion }}"</strong>?
        </p>

        <div class="flex items-center justify-center gap-3">
          <Button
            type="button"
            variant="outline"
            size="sm"
            class="border-gray-300 text-gray-700 text-xs px-4"
            @click="showDeleteModal = false"
          >
            Cancelar
          </Button>
          <Button
            type="button"
            size="sm"
            class="bg-red-600 hover:bg-red-700 text-white text-xs px-4 font-semibold"
            @click="confirmarEliminar"
          >
            Sí, eliminar
          </Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
