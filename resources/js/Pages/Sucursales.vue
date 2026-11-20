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
  Building2,
  MapPin,
  Phone,
  Store,
  TriangleAlert,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS LOCALES DE SUCURSALES (Fiel a la imagen)
// -------------------------------------------------------------------
const sucursales = ref([
  {
    id: 1,
    nombre: 'LOCAL PRINCIPAL',
    direccion: 'JR. LIMA 816',
    ubigeo: 'TARAPOTO - SAN MARTIN - SAN MARTIN',
    departamento: 'SAN MARTIN',
    provincia: 'SAN MARTIN',
    distrito: 'TARAPOTO',
    empresa: 'TECNOTRENDS SAC',
    telefono: '942 555 123',
    codigo: 'SUC-001',
    estado: 'Activo',
  },
  {
    id: 2,
    nombre: 'LOCAL SECUNDARIO',
    direccion: 'AV. CIRCUNVALACIÓN 450',
    ubigeo: 'MORALES - SAN MARTIN - SAN MARTIN',
    departamento: 'SAN MARTIN',
    provincia: 'SAN MARTIN',
    distrito: 'MORALES',
    empresa: 'TECNOTRENDS SAC',
    telefono: '942 888 777',
    codigo: 'SUC-002',
    estado: 'Activo',
  },
  {
    id: 3,
    nombre: 'SUCURSAL CENTRO',
    direccion: 'JR. SAN MARTÍN 320',
    ubigeo: 'BANDA DE SHILCAYO - SAN MARTIN - SAN MARTIN',
    departamento: 'SAN MARTIN',
    provincia: 'SAN MARTIN',
    distrito: 'LA BANDA DE SHILCAYO',
    empresa: 'RIQUISIMOS PIZZITA S.A.C.',
    telefono: '987 654 321',
    codigo: 'SUC-003',
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
  if (!q) return sucursales.value
  return sucursales.value.filter(
    (s) =>
      s.nombre.toLowerCase().includes(q) ||
      s.direccion.toLowerCase().includes(q) ||
      s.ubigeo.toLowerCase().includes(q) ||
      s.empresa.toLowerCase().includes(q)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))

const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: NUEVA / EDITAR SUCURSAL
// -------------------------------------------------------------------
const showModal = ref(false)
const isEditMode = ref(false)
const formData = ref({
  id: null,
  nombre: '',
  direccion: '',
  ubigeo: 'TARAPOTO - SAN MARTIN - SAN MARTIN',
  empresa: 'TECNOTRENDS SAC',
  telefono: '',
  codigo: '',
})

const empresasDisponibles = [
  'TECNOTRENDS SAC',
  'RIQUISIMOS PIZZITA S.A.C.',
  'MISHKY INNOVATIONS S.R.L.',
]

const ubigeoOpciones = [
  'TARAPOTO - SAN MARTIN - SAN MARTIN',
  'MORALES - SAN MARTIN - SAN MARTIN',
  'BANDA DE SHILCAYO - SAN MARTIN - SAN MARTIN',
  'LIMA - LIMA - LIMA',
  'MIRAFLORES - LIMA - LIMA',
  'SANTIAGO DE SURCO - LIMA - LIMA',
]

function openNuevaSucursal() {
  isEditMode.value = false
  formData.value = {
    id: null,
    nombre: '',
    direccion: '',
    ubigeo: 'TARAPOTO - SAN MARTIN - SAN MARTIN',
    empresa: 'TECNOTRENDS SAC',
    telefono: '',
    codigo: `SUC-00${sucursales.value.length + 1}`,
  }
  showModal.value = true
}

function openEditarSucursal(sucursal) {
  isEditMode.value = true
  formData.value = { ...sucursal }
  showModal.value = true
}

function guardarSucursal() {
  if (!formData.value.nombre.trim() || !formData.value.direccion.trim()) return

  if (isEditMode.value) {
    const idx = sucursales.value.findIndex((s) => s.id === formData.value.id)
    if (idx !== -1) {
      sucursales.value[idx] = { ...sucursales.value[idx], ...formData.value }
    }
  } else {
    const newId = Math.max(0, ...sucursales.value.map((s) => s.id)) + 1
    sucursales.value.push({
      ...formData.value,
      id: newId,
      estado: 'Activo',
    })
  }
  showModal.value = false
}

// -------------------------------------------------------------------
// MODAL: VER DETALLE SUCURSAL
// -------------------------------------------------------------------
const showVerModal = ref(false)
const sucursalSeleccionada = ref(null)

function openVerSucursal(sucursal) {
  sucursalSeleccionada.value = sucursal
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL: ELIMINAR SUCURSAL
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const sucursalAEliminar = ref(null)

function openEliminar(sucursal) {
  sucursalAEliminar.value = sucursal
  showDeleteModal.value = true
}

function confirmarEliminar() {
  sucursales.value = sucursales.value.filter((s) => s.id !== sucursalAEliminar.value.id)
  showDeleteModal.value = false
  sucursalAEliminar.value = null
}
</script>

<template>
  <AdminLayout active-nav="admin-sistema" active-submenu="sucursales">
    <!-- Título de Página -->
    <div>
      <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">Sucursales</h1>
    </div>

    <!-- Contenedor Principal (Tarjeta estilo image1) -->
    <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 flex flex-col justify-between min-h-0 sm:min-h-[520px]">
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
              @click="openNuevaSucursal"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </Button>
          </div>
        </div>

        <!-- TABLA DE SUCURSALES -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <Table>
            <TableHeader class="bg-[#232738] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/5">Sucursal</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">Dirección</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">Ubigeo</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/5">Empresa</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-28">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="sucursal in paginated"
                :key="sucursal.id"
                class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
              >
                <!-- Sucursal -->
                <TableCell class="py-3 px-4 text-xs text-gray-800 font-medium">
                  {{ sucursal.nombre }}
                </TableCell>

                <!-- Dirección -->
                <TableCell class="py-3 px-4 text-xs text-gray-600">
                  {{ sucursal.direccion }}
                </TableCell>

                <!-- Ubigeo -->
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center">
                  {{ sucursal.ubigeo }}
                </TableCell>

                <!-- Empresa -->
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center">
                  {{ sucursal.empresa }}
                </TableCell>

                <!-- Acciones -->
                <TableCell class="py-3 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Ver Detalle -->
                    <button
                      class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Ver detalles"
                      @click="openVerSucursal(sucursal)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>

                    <!-- Editar -->
                    <button
                      class="w-6 h-6 rounded border border-emerald-300 bg-emerald-50/80 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Editar sucursal"
                      @click="openEditarSucursal(sucursal)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>

                    <!-- Eliminar -->
                    <button
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Eliminar sucursal"
                      @click="openEliminar(sucursal)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Estado Vacío -->
              <TableRow v-if="paginated.length === 0">
                <TableCell colspan="5" class="py-12 text-center text-xs text-gray-400">
                  No se encontraron sucursales registradas
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
         MODAL: CREAR / EDITAR SUCURSAL
         ==================================================================== -->
    <div
      v-if="showModal"
      @click.self="showModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header del Modal -->
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Store class="w-5 h-5 text-red-500" />
            <h3 class="font-bold text-sm uppercase tracking-wide">
              {{ isEditMode ? 'Editar Sucursal' : 'Nueva Sucursal' }}
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
        <form @submit.prevent="guardarSucursal" class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre de la Sucursal *</label>
            <Input
              v-model="formData.nombre"
              type="text"
              placeholder="Ej. LOCAL PRINCIPAL"
              required
              class="h-9 text-xs rounded-lg border-gray-300"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Dirección *</label>
            <Input
              v-model="formData.direccion"
              type="text"
              placeholder="Ej. Jr. Lima 816"
              required
              class="h-9 text-xs rounded-lg border-gray-300"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Empresa Perteneciente *</label>
              <select
                v-model="formData.empresa"
                class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
              >
                <option v-for="emp in empresasDisponibles" :key="emp" :value="emp">
                  {{ emp }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Teléfono</label>
              <Input
                v-model="formData.telefono"
                type="text"
                placeholder="Ej. 942 555 123"
                class="h-9 text-xs rounded-lg border-gray-300"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Ubigeo (Distrito - Provincia - Departamento)</label>
            <select
              v-model="formData.ubigeo"
              class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
            >
              <option v-for="ubi in ubigeoOpciones" :key="ubi" :value="ubi">
                {{ ubi }}
              </option>
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
              {{ isEditMode ? 'Guardar Cambios' : 'Registrar Sucursal' }}
            </Button>
          </div>
        </form>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: VER SUCURSAL
         ==================================================================== -->
    <div
      v-if="showVerModal && sucursalSeleccionada"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Store class="w-5 h-5 text-blue-400" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Detalles de Sucursal</h3>
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
              <span class="text-gray-500 font-medium">Sucursal:</span>
              <span class="font-bold text-gray-900">{{ sucursalSeleccionada.nombre }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Empresa:</span>
              <span class="font-semibold text-gray-800">{{ sucursalSeleccionada.empresa }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Dirección:</span>
              <span class="text-gray-800">{{ sucursalSeleccionada.direccion }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Ubigeo:</span>
              <span class="text-gray-800">{{ sucursalSeleccionada.ubigeo }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Teléfono:</span>
              <span class="text-gray-800">{{ sucursalSeleccionada.telefono || 'No registrado' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Estado:</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                {{ sucursalSeleccionada.estado }}
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
         MODAL: ELIMINAR SUCURSAL
         ==================================================================== -->
    <div
      v-if="showDeleteModal && sucursalAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
          <TriangleAlert class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-base text-gray-900 mb-1">¿Eliminar Sucursal?</h3>
        <p class="text-xs text-gray-500 mb-6">
          ¿Estás seguro de que deseas eliminar la sucursal <strong class="text-gray-800">"{{ sucursalAEliminar.nombre }}"</strong>? Esta acción no se puede deshacer.
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
