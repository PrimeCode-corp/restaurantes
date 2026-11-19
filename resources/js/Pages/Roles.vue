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
  Shield,
  TriangleAlert,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS LOCALES DE ROLES (Fiel a la imagen 1)
// -------------------------------------------------------------------
const roles = ref([
  {
    id: 1,
    descripcion: 'ADMINISTRADOR',
    detalle: 'Acceso total a todos los módulos y configuraciones del sistema.',
    estado: 'Activo',
  },
  {
    id: 2,
    descripcion: 'MOZOS',
    detalle: 'Toma de pedidos en mesas, visualización de comandas y estado de mesas.',
    estado: 'Activo',
  },
  {
    id: 3,
    descripcion: 'PREPARACION',
    detalle: 'Pantalla de cocina y barra para despacho y preparación de pedidos.',
    estado: 'Activo',
  },
  {
    id: 4,
    descripcion: 'VENTAS',
    detalle: 'Punto de venta POS, cobros en caja, emisión de comprobantes y cierres.',
    estado: 'Activo',
  },
  {
    id: 5,
    descripcion: 'CAJERO',
    detalle: 'Control de apertura, cobros de pedidos, arqueo y cierre de caja.',
    estado: 'Activo',
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
  if (!q) return roles.value
  return roles.value.filter((r) => r.descripcion.toLowerCase().includes(q))
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))

const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: CREAR / EDITAR ROL
// -------------------------------------------------------------------
const showModal = ref(false)
const isEditMode = ref(false)
const formData = ref({
  id: null,
  descripcion: '',
  detalle: '',
  estado: 'Activo',
})

function openNuevoRol() {
  isEditMode.value = false
  formData.value = {
    id: null,
    descripcion: '',
    detalle: '',
    estado: 'Activo',
  }
  showModal.value = true
}

function openEditarRol(rol) {
  isEditMode.value = true
  formData.value = { ...rol }
  showModal.value = true
}

function guardarRol() {
  if (!formData.value.descripcion.trim()) return

  if (isEditMode.value) {
    const idx = roles.value.findIndex((r) => r.id === formData.value.id)
    if (idx !== -1) {
      roles.value[idx] = { ...roles.value[idx], ...formData.value }
    }
  } else {
    const newId = Math.max(0, ...roles.value.map((r) => r.id)) + 1
    roles.value.push({
      ...formData.value,
      id: newId,
    })
  }
  showModal.value = false
}

// -------------------------------------------------------------------
// MODAL: VER DETALLES
// -------------------------------------------------------------------
const showVerModal = ref(false)
const rolSeleccionado = ref(null)

function openVerRol(rol) {
  rolSeleccionado.value = rol
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL: ELIMINAR ROL
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const rolAEliminar = ref(null)

function openEliminar(rol) {
  rolAEliminar.value = rol
  showDeleteModal.value = true
}

function confirmarEliminar() {
  roles.value = roles.value.filter((r) => r.id !== rolAEliminar.value.id)
  showDeleteModal.value = false
  rolAEliminar.value = null
}
</script>

<template>
  <AdminLayout active-nav="admin-sistema" active-submenu="roles">
    <!-- Título de Página -->
    <div>
      <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">Roles</h1>
    </div>

    <!-- Contenedor Principal (Tarjeta estilo image 1) -->
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
              @click="openNuevoRol"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </Button>
          </div>
        </div>

        <!-- TABLA DE ROLES -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <Table>
            <TableHeader class="bg-[#232738] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Descripción</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-28">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="rol in paginated"
                :key="rol.id"
                class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
              >
                <!-- Descripción -->
                <TableCell class="py-3 px-6 text-xs text-gray-800 font-medium text-left">
                  {{ rol.descripcion }}
                </TableCell>

                <!-- Acciones -->
                <TableCell class="py-3 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Ver Detalle -->
                    <button
                      class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Ver detalles"
                      @click="openVerRol(rol)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>

                    <!-- Editar -->
                    <button
                      class="w-6 h-6 rounded border border-emerald-300 bg-emerald-50/80 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Editar rol"
                      @click="openEditarRol(rol)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>

                    <!-- Eliminar -->
                    <button
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Eliminar rol"
                      @click="openEliminar(rol)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Estado Vacío -->
              <TableRow v-if="paginated.length === 0">
                <TableCell colspan="2" class="py-12 text-center text-xs text-gray-400">
                  No se encontraron roles registrados
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
         MODAL: CREAR / EDITAR ROL
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
            <Shield class="w-5 h-5 text-red-500" />
            <h3 class="font-bold text-sm uppercase tracking-wide">
              {{ isEditMode ? 'Editar Rol' : 'Nuevo Rol' }}
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
        <form @submit.prevent="guardarRol" class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre / Descripción del Rol *</label>
            <Input
              v-model="formData.descripcion"
              type="text"
              placeholder="Ej. ADMINISTRADOR"
              required
              class="h-9 text-xs rounded-lg border-gray-300 uppercase"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Detalle / Funciones</label>
            <Input
              v-model="formData.detalle"
              type="text"
              placeholder="Descripción breve de permisos del rol"
              class="h-9 text-xs rounded-lg border-gray-300"
            />
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
              {{ isEditMode ? 'Guardar Cambios' : 'Registrar Rol' }}
            </Button>
          </div>
        </form>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: VER DETALLES
         ==================================================================== -->
    <div
      v-if="showVerModal && rolSeleccionado"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Shield class="w-5 h-5 text-blue-400" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Detalles del Rol</h3>
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
              <span class="text-gray-500 font-medium">Nombre:</span>
              <span class="font-bold text-gray-900">{{ rolSeleccionado.descripcion }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Estado:</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                {{ rolSeleccionado.estado }}
              </span>
            </div>
            <div>
              <span class="text-gray-500 font-medium block mb-1">Descripción:</span>
              <p class="text-gray-800 bg-white p-2.5 rounded-lg border border-gray-200">
                {{ rolSeleccionado.detalle || 'Sin descripción adicional.' }}
              </p>
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
         MODAL: ELIMINAR ROL
         ==================================================================== -->
    <div
      v-if="showDeleteModal && rolAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center animate-in fade-in zoom-in-95 duration-150">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
          <TriangleAlert class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-base text-gray-900 mb-1">¿Eliminar Rol?</h3>
        <p class="text-xs text-gray-500 mb-6">
          ¿Estás seguro de que deseas eliminar el rol <strong class="text-gray-800">"{{ rolAEliminar.descripcion }}"</strong>?
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
