<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTablePagination from '@/Components/ui/DataTablePagination.vue'
import { Card } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
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
  Search,
  Plus,
  Eye,
  Pencil,
  Trash2,
  X,
  Users,
  ChevronDown,
  Upload,
  ArrowLeft,
  Save,
  TriangleAlert,
  Building2,
  Lock,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS LOCALES DE EMPLEADOS (Fiel a la imagen)
// -------------------------------------------------------------------
const empleados = ref([
  {
    id: 1,
    nombres: 'LEANDRO',
    apellidos: 'SINTI PINEDO',
    perfil: 'ADMINISTRADOR',
    direccion: 'JR. CIRCUNVALACION 245',
    dni: '12345678',
    telefono: '987654321',
    fechaNacimiento: '1995-04-12',
    observacion: 'Administrador general con acceso a todas las sedes.',
    curriculo: 'cv_leandro_sinti.pdf',
    usuario: 'lsinti',
    clave: '••••••••',
    caja: 'CAJA PRINCIPAL',
    sucursalesAcceso: [
      { id: 'morales', nombre: 'SEDE MORALES', asignado: true, rol: 'ADMINISTRADOR', superUsuario: true },
      { id: 'principal', nombre: 'LOCAL PRINCIPAL', asignado: true, rol: 'ADMINISTRADOR', superUsuario: true },
      { id: 'secundario', nombre: 'LOCAL SECUNDARIO', asignado: false, rol: 'ADMINISTRADOR', superUsuario: false },
    ],
  },
  {
    id: 2,
    nombres: 'ALISON',
    apellidos: 'FASABI CHUJANDAMA',
    perfil: 'MOZOS',
    direccion: 'AV. LIMA 430',
    dni: '87654321',
    telefono: '942123456',
    fechaNacimiento: '1998-08-20',
    observacion: 'Personal de atención en salón.',
    curriculo: 'cv_alison_fasabi.pdf',
    usuario: 'afasabi',
    clave: '••••••••',
    caja: 'CAJA 02 - BARRA',
    sucursalesAcceso: [
      { id: 'morales', nombre: 'SEDE MORALES', asignado: true, rol: 'MOZOS', superUsuario: false },
      { id: 'principal', nombre: 'LOCAL PRINCIPAL', asignado: true, rol: 'MOZOS', superUsuario: false },
      { id: 'secundario', nombre: 'LOCAL SECUNDARIO', asignado: false, rol: 'MOZOS', superUsuario: false },
    ],
  },
  {
    id: 3,
    nombres: 'CARLOS',
    apellidos: 'MENDOZA RUIZ',
    perfil: 'PREPARACION',
    direccion: 'JR. SAN MARTIN 120',
    dni: '45678901',
    telefono: '942987654',
    fechaNacimiento: '1992-11-05',
    observacion: 'Jefe de cocina y barra.',
    curriculo: '',
    usuario: 'cmendoza',
    clave: '••••••••',
    caja: 'CAJA PRINCIPAL',
    sucursalesAcceso: [
      { id: 'morales', nombre: 'SEDE MORALES', asignado: true, rol: 'PREPARACION', superUsuario: false },
      { id: 'principal', nombre: 'LOCAL PRINCIPAL', asignado: true, rol: 'PREPARACION', superUsuario: false },
      { id: 'secundario', nombre: 'LOCAL SECUNDARIO', asignado: true, rol: 'PREPARACION', superUsuario: false },
    ],
  },
  {
    id: 4,
    nombres: 'MARIA',
    apellidos: 'TORRES VEGA',
    perfil: 'VENTAS',
    direccion: 'JR. ALFONSO UGARTE 550',
    dni: '78901234',
    telefono: '987112233',
    fechaNacimiento: '1996-02-18',
    observacion: 'Cajera principal turno mañana.',
    curriculo: '',
    usuario: 'mtorres',
    clave: '••••••••',
    caja: 'CAJA DELIVERY',
    sucursalesAcceso: [
      { id: 'morales', nombre: 'SEDE MORALES', asignado: false, rol: 'VENTAS', superUsuario: false },
      { id: 'principal', nombre: 'LOCAL PRINCIPAL', asignado: true, rol: 'VENTAS', superUsuario: false },
      { id: 'secundario', nombre: 'LOCAL SECUNDARIO', asignado: false, rol: 'VENTAS', superUsuario: false },
    ],
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
  if (!q) return empleados.value
  return empleados.value.filter(
    (e) =>
      e.nombres.toLowerCase().includes(q) ||
      e.apellidos.toLowerCase().includes(q) ||
      e.perfil.toLowerCase().includes(q) ||
      e.dni.includes(q) ||
      e.direccion.toLowerCase().includes(q) ||
      e.telefono.includes(q)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))

const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: NUEVO / EDITAR EMPLEADO
// -------------------------------------------------------------------
const showModal = ref(false)
const isEditMode = ref(false)
const activeModalTab = ref('datos') // 'datos' | 'acceso-sucursal'
const isAccesoExpanded = ref(true) // Acordeón "Datos de Acceso"

const rolesDisponibles = ['ADMINISTRADOR', 'MOZOS', 'PREPARACION', 'VENTAS', 'CAJERO']
const cajasDisponibles = ['CAJA PRINCIPAL', 'CAJA 02 - BARRA', 'CAJA DELIVERY']

const formData = ref({
  id: null,
  nombres: '',
  apellidos: '',
  dni: '',
  fechaNacimiento: '',
  direccion: '',
  telefono: '',
  observacion: '',
  curriculo: '',
  usuario: '',
  clave: '',
  perfil: 'ADMINISTRADOR',
  caja: 'CAJA PRINCIPAL',
  sucursalesAcceso: [
    { id: 'morales', nombre: 'SEDE MORALES', asignado: true, rol: 'ADMINISTRADOR', superUsuario: true },
    { id: 'principal', nombre: 'LOCAL PRINCIPAL', asignado: true, rol: 'ADMINISTRADOR', superUsuario: false },
    { id: 'secundario', nombre: 'LOCAL SECUNDARIO', asignado: false, rol: 'ADMINISTRADOR', superUsuario: false },
  ],
})

function openNuevoEmpleado() {
  isEditMode.value = false
  activeModalTab.value = 'datos'
  isAccesoExpanded.value = true
  formData.value = {
    id: null,
    nombres: '',
    apellidos: '',
    dni: '',
    fechaNacimiento: '',
    direccion: '',
    telefono: '',
    observacion: '',
    curriculo: '',
    usuario: '',
    clave: '',
    perfil: 'ADMINISTRADOR',
    caja: 'CAJA PRINCIPAL',
    sucursalesAcceso: [
      { id: 'morales', nombre: 'SEDE MORALES', asignado: true, rol: 'ADMINISTRADOR', superUsuario: true },
      { id: 'principal', nombre: 'LOCAL PRINCIPAL', asignado: true, rol: 'ADMINISTRADOR', superUsuario: false },
      { id: 'secundario', nombre: 'LOCAL SECUNDARIO', asignado: false, rol: 'ADMINISTRADOR', superUsuario: false },
    ],
  }
  showModal.value = true
}

function openEditarEmpleado(emp) {
  isEditMode.value = true
  activeModalTab.value = 'datos'
  isAccesoExpanded.value = true
  formData.value = JSON.parse(JSON.stringify(emp))
  showModal.value = true
}

function handleCvFileChange(e) {
  const file = e.target.files?.[0]
  if (file) {
    formData.value.curriculo = file.name
  }
}

function guardarEmpleado() {
  if (!formData.value.nombres.trim() || !formData.value.apellidos.trim()) return

  if (isEditMode.value) {
    const idx = empleados.value.findIndex((e) => e.id === formData.value.id)
    if (idx !== -1) {
      empleados.value[idx] = JSON.parse(JSON.stringify(formData.value))
    }
  } else {
    const newId = Math.max(0, ...empleados.value.map((e) => e.id)) + 1
    empleados.value.push({
      ...JSON.parse(JSON.stringify(formData.value)),
      id: newId,
    })
  }
  showModal.value = false
}

// -------------------------------------------------------------------
// MODAL: VISUALIZAR EMPLEADO (VER)
// -------------------------------------------------------------------
const showVerModal = ref(false)
const empleadoSeleccionado = ref(null)
const verModalTab = ref('datos')

function openVerEmpleado(emp) {
  empleadoSeleccionado.value = emp
  verModalTab.value = 'datos'
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL: ELIMINAR EMPLEADO
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const empleadoAEliminar = ref(null)

function openEliminar(emp) {
  empleadoAEliminar.value = emp
  showDeleteModal.value = true
}

function confirmarEliminar() {
  empleados.value = empleados.value.filter((e) => e.id !== empleadoAEliminar.value.id)
  showDeleteModal.value = false
  empleadoAEliminar.value = null
}
</script>

<template>
  <AdminLayout active-nav="admin-empleado" active-submenu="empleados">
    <!-- Título de Página -->
    <div>
      <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">Empleados</h1>
    </div>

    <!-- Contenedor Principal (Tarjeta estilo imagen) -->
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
              @click="openNuevoEmpleado"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </Button>
          </div>
        </div>

        <!-- TABLA DE EMPLEADOS -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <Table>
            <TableHeader class="bg-[#232738] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Nombres</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Apellidos</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Perfil</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Dirección</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Dni</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Teléfono</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-28">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="emp in paginated"
                :key="emp.id"
                class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
              >
                <!-- Nombres -->
                <TableCell class="py-3 px-4 text-xs text-gray-800 font-semibold text-center">
                  {{ emp.nombres }}
                </TableCell>

                <!-- Apellidos -->
                <TableCell class="py-3 px-4 text-xs text-gray-800 font-semibold text-center">
                  {{ emp.apellidos }}
                </TableCell>

                <!-- Perfil -->
                <TableCell class="py-3 px-4 text-xs text-gray-700 text-center font-medium">
                  <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                    {{ emp.perfil }}
                  </span>
                </TableCell>

                <!-- Dirección -->
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center">
                  {{ emp.direccion }}
                </TableCell>

                <!-- Dni -->
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center font-mono">
                  {{ emp.dni }}
                </TableCell>

                <!-- Teléfono -->
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center font-mono">
                  {{ emp.telefono }}
                </TableCell>

                <!-- Acciones -->
                <TableCell class="py-3 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Ver Detalle -->
                    <button
                      class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Ver detalles"
                      @click="openVerEmpleado(emp)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>

                    <!-- Editar -->
                    <button
                      class="w-6 h-6 rounded border border-emerald-300 bg-emerald-50/80 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Editar empleado"
                      @click="openEditarEmpleado(emp)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>

                    <!-- Eliminar -->
                    <button
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Eliminar empleado"
                      @click="openEliminar(emp)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Estado Vacío -->
              <TableRow v-if="paginated.length === 0">
                <TableCell colspan="7" class="py-12 text-center text-xs text-gray-400">
                  No se encontraron empleados registrados
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
         MODAL: ADMINISTRAR EMPLEADOS (CREAR / EDITAR)
         ==================================================================== -->
    <div
      v-if="showModal"
      @click.self="showModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <!-- Header del Modal -->
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Users class="w-5 h-5 text-red-500" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Administrar empleados</h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Pestañas de Navegación del Modal (Fiel a la imagen) -->
        <div class="flex border-b border-gray-200 bg-gray-50/70 px-6 pt-3">
          <button
            type="button"
            class="pb-2.5 px-3 text-xs font-bold transition-all border-b-2"
            :class="[
              activeModalTab === 'datos'
                ? 'border-[#F0302E] text-[#F0302E]'
                : 'border-transparent text-gray-500 hover:text-gray-800'
            ]"
            @click="activeModalTab = 'datos'"
          >
            Datos Empleado
          </button>
          <button
            type="button"
            class="pb-2.5 px-3 text-xs font-bold transition-all border-b-2"
            :class="[
              activeModalTab === 'acceso-sucursal'
                ? 'border-[#F0302E] text-[#F0302E]'
                : 'border-transparent text-gray-500 hover:text-gray-800'
            ]"
            @click="activeModalTab = 'acceso-sucursal'"
          >
            Acceso Sucursal
          </button>
        </div>

        <!-- Cuerpo del Modal -->
        <form @submit.prevent="guardarEmpleado" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
          <!-- PESTAÑA 1: DATOS EMPLEADO -->
          <div v-show="activeModalTab === 'datos'" class="space-y-4">
            <!-- Fila 1: Nombres, Apellidos, DNI -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nombres *</label>
                <Input
                  v-model="formData.nombres"
                  type="text"
                  placeholder="Escribe aquí"
                  required
                  class="h-9 text-xs rounded-lg border-gray-300"
                />
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Apellidos *</label>
                <Input
                  v-model="formData.apellidos"
                  type="text"
                  placeholder="Escribe aquí"
                  required
                  class="h-9 text-xs rounded-lg border-gray-300"
                />
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">DNI</label>
                <Input
                  v-model="formData.dni"
                  type="text"
                  placeholder="Escribe aquí"
                  class="h-9 text-xs rounded-lg border-gray-300 font-mono"
                />
              </div>
            </div>

            <!-- Fila 2: Fecha Nacimiento, Dirección, Teléfono -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Fecha Nacimiento</label>
                <Input
                  v-model="formData.fechaNacimiento"
                  type="date"
                  class="h-9 text-xs rounded-lg border-gray-300"
                />
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Dirección</label>
                <Input
                  v-model="formData.direccion"
                  type="text"
                  placeholder="Escribe aquí"
                  class="h-9 text-xs rounded-lg border-gray-300"
                />
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Teléfono</label>
                <Input
                  v-model="formData.telefono"
                  type="text"
                  placeholder="Escribe aquí"
                  class="h-9 text-xs rounded-lg border-gray-300"
                />
              </div>
            </div>

            <!-- Fila 3: Observación y Currículo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Observación</label>
                <Input
                  v-model="formData.observacion"
                  type="text"
                  placeholder="Escribe aquí"
                  class="h-9 text-xs rounded-lg border-gray-300"
                />
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Currículo (DOC, DOCX, PDF)</label>
                <div class="flex items-center gap-2">
                  <label class="cursor-pointer bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-3 py-1.5 h-9 rounded-lg text-xs font-medium flex items-center gap-1.5 shadow-xs transition-colors shrink-0">
                    <Upload class="w-3.5 h-3.5 text-gray-600" />
                    <span>Seleccionar archivo</span>
                    <input type="file" accept=".pdf,.doc,.docx" class="hidden" @change="handleCvFileChange" />
                  </label>
                  <span class="text-xs text-gray-500 truncate">{{ formData.curriculo || 'Ningún archivo' }}</span>
                </div>
              </div>
            </div>

            <!-- SECCIÓN ACORDEÓN: DATOS DE ACCESO (Fiel a la imagen 2) -->
            <div class="border border-gray-200 rounded-xl overflow-hidden mt-3">
              <button
                type="button"
                class="w-full px-4 py-2.5 bg-gray-50 hover:bg-gray-100/80 text-left flex items-center justify-between text-xs font-bold text-gray-800 transition-colors"
                @click="isAccesoExpanded = !isAccesoExpanded"
              >
                <div class="flex items-center gap-2">
                  <Lock class="w-3.5 h-3.5 text-gray-600" />
                  <span>Datos de Acceso</span>
                </div>
                <span class="text-sm font-bold text-gray-500">{{ isAccesoExpanded ? '—' : '+' }}</span>
              </button>

              <div v-show="isAccesoExpanded" class="p-4 space-y-3 bg-white border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1">Usuario</label>
                    <Input
                      v-model="formData.usuario"
                      type="text"
                      placeholder="Escribe aquí"
                      class="h-9 text-xs rounded-lg border-gray-300"
                    />
                  </div>
                  <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1">Clave</label>
                    <Input
                      v-model="formData.clave"
                      type="password"
                      placeholder="Escribe aquí"
                      class="h-9 text-xs rounded-lg border-gray-300"
                    />
                  </div>
                  <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1">Roles</label>
                    <select
                      v-model="formData.perfil"
                      class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
                    >
                      <option v-for="rol in rolesDisponibles" :key="rol" :value="rol">{{ rol }}</option>
                    </select>
                  </div>
                </div>

                <div class="w-full sm:w-1/3">
                  <label class="block text-[11px] font-semibold text-gray-700 mb-1">Cajas</label>
                  <select
                    v-model="formData.caja"
                    class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
                  >
                    <option v-for="cj in cajasDisponibles" :key="cj" :value="cj">{{ cj }}</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- PESTAÑA 2: ACCESO SUCURSAL (Fiel a la imagen 3) -->
          <div v-show="activeModalTab === 'acceso-sucursal'" class="space-y-4">
            <div class="overflow-x-auto rounded-lg border border-gray-200">
              <Table>
                <TableHeader class="bg-[#232738] text-white">
                  <TableRow class="border-none hover:bg-transparent">
                    <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-left w-2/5">Empresa / Sucursal</TableHead>
                    <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-2/5">Roles</TableHead>
                    <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/5">Super Usuario</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow
                    v-for="suc in formData.sucursalesAcceso"
                    :key="suc.id"
                    class="border-b border-gray-200 hover:bg-gray-50/70"
                  >
                    <!-- Checkbox y Nombre de Sucursal -->
                    <TableCell class="py-3 px-4">
                      <label class="flex items-center gap-2.5 cursor-pointer">
                        <input
                          type="checkbox"
                          v-model="suc.asignado"
                          class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                        />
                        <span class="text-xs font-semibold text-gray-800">{{ suc.nombre }}</span>
                      </label>
                    </TableCell>

                    <!-- Rol Asignado -->
                    <TableCell class="py-3 px-4 text-center">
                      <select
                        v-model="suc.rol"
                        :disabled="!suc.asignado"
                        class="h-8 px-2.5 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400 disabled:opacity-50"
                      >
                        <option v-for="rol in rolesDisponibles" :key="rol" :value="rol">{{ rol }}</option>
                      </select>
                    </TableCell>

                    <!-- Switch Super Usuario -->
                    <TableCell class="py-3 px-4 text-center">
                      <Switch
                        :checked="suc.superUsuario"
                        :disabled="!suc.asignado"
                        @update:checked="suc.superUsuario = $event"
                      />
                    </TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </div>
          </div>

          <!-- BOTONES INFERIORES: ATRÁS + GUARDAR -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <Button
              type="button"
              variant="outline"
              size="sm"
              class="border-gray-300 text-gray-700 text-xs px-4 flex items-center gap-1.5"
              @click="showModal = false"
            >
              <ArrowLeft class="w-3.5 h-3.5" />
              <span>ATRÁS</span>
            </Button>
            <Button
              type="submit"
              size="sm"
              class="bg-[#F0302E] hover:bg-[#D02724] text-white text-xs px-5 font-bold flex items-center gap-1.5 shadow-sm"
            >
              <Save class="w-3.5 h-3.5 stroke-[2.5]" />
              <span>GUARDAR</span>
            </Button>
          </div>
        </form>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: VISUALIZAR EMPLEADOS (VER)
         ==================================================================== -->
    <div
      v-if="showVerModal && empleadoSeleccionado"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Users class="w-5 h-5 text-blue-400" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Visualizar empleados</h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showVerModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <div class="flex border-b border-gray-200 bg-gray-50/70 px-6 pt-3">
          <button
            type="button"
            class="pb-2.5 px-3 text-xs font-bold transition-all border-b-2"
            :class="[
              verModalTab === 'datos'
                ? 'border-[#F0302E] text-[#F0302E]'
                : 'border-transparent text-gray-500 hover:text-gray-800'
            ]"
            @click="verModalTab = 'datos'"
          >
            Datos Empleado
          </button>
          <button
            type="button"
            class="pb-2.5 px-3 text-xs font-bold transition-all border-b-2"
            :class="[
              verModalTab === 'acceso-sucursal'
                ? 'border-[#F0302E] text-[#F0302E]'
                : 'border-transparent text-gray-500 hover:text-gray-800'
            ]"
            @click="verModalTab = 'acceso-sucursal'"
          >
            Acceso Sucursal
          </button>
        </div>

        <div class="p-6 space-y-4 text-xs max-h-[70vh] overflow-y-auto">
          <div v-show="verModalTab === 'datos'" class="bg-gray-50 p-4 rounded-xl space-y-2.5 border border-gray-100">
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Nombres y Apellidos:</span>
              <span class="font-bold text-gray-900">{{ empleadoSeleccionado.nombres }} {{ empleadoSeleccionado.apellidos }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">DNI:</span>
              <span class="font-mono text-gray-800">{{ empleadoSeleccionado.dni || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Teléfono:</span>
              <span class="text-gray-800">{{ empleadoSeleccionado.telefono || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Dirección:</span>
              <span class="text-gray-800">{{ empleadoSeleccionado.direccion || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Perfil / Rol:</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                {{ empleadoSeleccionado.perfil }}
              </span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Caja Asignada:</span>
              <span class="text-gray-800 font-semibold">{{ empleadoSeleccionado.caja || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Usuario:</span>
              <span class="font-mono text-gray-800">{{ empleadoSeleccionado.usuario || '-' }}</span>
            </div>
            <div class="pt-2 border-t border-gray-200">
              <span class="text-gray-500 font-medium block mb-1">Observaciones:</span>
              <p class="text-gray-700 italic bg-white p-2 rounded-lg border border-gray-100">
                {{ empleadoSeleccionado.observacion || 'Sin observaciones registradas.' }}
              </p>
            </div>
          </div>

          <div v-show="verModalTab === 'acceso-sucursal'" class="space-y-3">
            <div class="overflow-x-auto rounded-lg border border-gray-200">
              <Table>
                <TableHeader class="bg-[#232738] text-white">
                  <TableRow class="border-none hover:bg-transparent">
                    <TableHead class="text-xs font-semibold text-white h-8 px-3 text-left">Sucursal</TableHead>
                    <TableHead class="text-xs font-semibold text-white h-8 px-3 text-center">Rol Asignado</TableHead>
                    <TableHead class="text-xs font-semibold text-white h-8 px-3 text-center">Super Usuario</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow
                    v-for="suc in empleadoSeleccionado.sucursalesAcceso"
                    :key="suc.id"
                    class="border-b border-gray-200"
                  >
                    <TableCell class="py-2.5 px-3 font-semibold text-gray-800">
                      {{ suc.nombre }}
                    </TableCell>
                    <TableCell class="py-2.5 px-3 text-center">
                      <span v-if="suc.asignado" class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">
                        {{ suc.rol }}
                      </span>
                      <span v-else class="text-gray-400 text-[11px]">Sin acceso</span>
                    </TableCell>
                    <TableCell class="py-2.5 px-3 text-center">
                      <span
                        v-if="suc.asignado"
                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                        :class="suc.superUsuario ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                      >
                        {{ suc.superUsuario ? 'Sí' : 'No' }}
                      </span>
                      <span v-else class="text-gray-400 text-[11px]">-</span>
                    </TableCell>
                  </TableRow>
                </TableBody>
              </Table>
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
         MODAL: ELIMINAR EMPLEADO
         ==================================================================== -->
    <div
      v-if="showDeleteModal && empleadoAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center animate-in fade-in zoom-in-95 duration-150">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
          <TriangleAlert class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-base text-gray-900 mb-1">¿Eliminar Empleado?</h3>
        <p class="text-xs text-gray-500 mb-6">
          ¿Estás seguro de que deseas eliminar al empleado <strong class="text-gray-800">"{{ empleadoAEliminar.nombres }} {{ empleadoAEliminar.apellidos }}"</strong>?
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
