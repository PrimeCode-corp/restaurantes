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
  Printer,
  TriangleAlert,
  CheckCircle2,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS LOCALES DE IMPRESORAS (Fiel a la imagen 2)
// -------------------------------------------------------------------
const impresoras = ref([
  {
    id: 1,
    nombre: 'IMPTICKET',
    ip: 'localhost',
    tipo: 'USB',
    puerto: '9100',
    anchoPapel: '80mm',
    marca: 'EPSON TM-T20III',
    estado: 'Conectada',
  },
  {
    id: 2,
    nombre: 'IMPCOCINA',
    ip: '192.168.1.200',
    tipo: 'RED / ETHERNET',
    puerto: '9100',
    anchoPapel: '80mm',
    marca: 'BIXOLON SRP-350',
    estado: 'Conectada',
  },
  {
    id: 3,
    nombre: 'IMPBAR',
    ip: '192.168.1.201',
    tipo: 'RED / ETHERNET',
    puerto: '9100',
    anchoPapel: '80mm',
    marca: 'XPRINTER XP-Q800',
    estado: 'Conectada',
  },
  {
    id: 4,
    nombre: 'IMPDELIVERY',
    ip: 'localhost',
    tipo: 'BLUETOOTH',
    puerto: 'COM3',
    anchoPapel: '58mm',
    marca: 'PORTABLE MINI 58',
    estado: 'Conectada',
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
  if (!q) return impresoras.value
  return impresoras.value.filter(
    (imp) =>
      imp.nombre.toLowerCase().includes(q) ||
      imp.ip.toLowerCase().includes(q) ||
      imp.tipo.toLowerCase().includes(q)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))

const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// MODAL: NUEVA / EDITAR IMPRESORA
// -------------------------------------------------------------------
const showModal = ref(false)
const isEditMode = ref(false)
const formData = ref({
  id: null,
  nombre: '',
  ip: 'localhost',
  tipo: 'USB',
  puerto: '9100',
  anchoPapel: '80mm',
  marca: 'EPSON TM-T20III',
})

const tiposConexion = ['USB', 'RED / ETHERNET', 'BLUETOOTH', 'SERIAL']
const anchosPapel = ['80mm', '58mm']

function openNuevaImpresora() {
  isEditMode.value = false
  formData.value = {
    id: null,
    nombre: '',
    ip: 'localhost',
    tipo: 'USB',
    puerto: '9100',
    anchoPapel: '80mm',
    marca: 'EPSON TM-T20III',
  }
  showModal.value = true
}

function openEditarImpresora(imp) {
  isEditMode.value = true
  formData.value = { ...imp }
  showModal.value = true
}

function guardarImpresora() {
  if (!formData.value.nombre.trim() || !formData.value.ip.trim()) return

  if (isEditMode.value) {
    const idx = impresoras.value.findIndex((i) => i.id === formData.value.id)
    if (idx !== -1) {
      impresoras.value[idx] = { ...impresoras.value[idx], ...formData.value }
    }
  } else {
    const newId = Math.max(0, ...impresoras.value.map((i) => i.id)) + 1
    impresoras.value.push({
      ...formData.value,
      id: newId,
      estado: 'Conectada',
    })
  }
  showModal.value = false
}

// -------------------------------------------------------------------
// MODAL: VER DETALLE & TEST
// -------------------------------------------------------------------
const showVerModal = ref(false)
const impresoraSeleccionada = ref(null)
const testMessage = ref(null)

function openVerImpresora(imp) {
  impresoraSeleccionada.value = imp
  testMessage.value = null
  showVerModal.value = true
}

function probarImpresion() {
  testMessage.value = `¡Ticket de prueba enviado a ${impresoraSeleccionada.value.nombre} (${impresoraSeleccionada.value.ip})!`
  setTimeout(() => {
    testMessage.value = null
  }, 4000)
}

// -------------------------------------------------------------------
// MODAL: ELIMINAR IMPRESORA
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const impresoraAEliminar = ref(null)

function openEliminar(imp) {
  impresoraAEliminar.value = imp
  showDeleteModal.value = true
}

function confirmarEliminar() {
  impresoras.value = impresoras.value.filter((i) => i.id !== impresoraAEliminar.value.id)
  showDeleteModal.value = false
  impresoraAEliminar.value = null
}
</script>

<template>
  <AdminLayout active-nav="admin-sistema" active-submenu="impresoras">
    <!-- Título de Página -->
    <div>
      <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">Impresoras</h1>
    </div>

    <!-- Contenedor Principal (Tarjeta estilo image 2) -->
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
              @click="openNuevaImpresora"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </Button>
          </div>
        </div>

        <!-- TABLA DE IMPRESORAS -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <Table>
            <TableHeader class="bg-[#232738] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/3">Nombre</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/3">Ip</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">Tipo</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-28">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="imp in paginated"
                :key="imp.id"
                class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
              >
                <!-- Nombre -->
                <TableCell class="py-3 px-4 text-xs text-gray-800 font-medium text-center">
                  {{ imp.nombre }}
                </TableCell>

                <!-- IP -->
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center font-mono">
                  {{ imp.ip }}
                </TableCell>

                <!-- Tipo -->
                <TableCell class="py-3 px-4 text-xs text-gray-700 text-center font-medium">
                  <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    {{ imp.tipo }}
                  </span>
                </TableCell>

                <!-- Acciones -->
                <TableCell class="py-3 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Ver Detalle -->
                    <button
                      class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Ver detalles y prueba"
                      @click="openVerImpresora(imp)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>

                    <!-- Editar -->
                    <button
                      class="w-6 h-6 rounded border border-emerald-300 bg-emerald-50/80 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Editar impresora"
                      @click="openEditarImpresora(imp)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>

                    <!-- Eliminar -->
                    <button
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Eliminar impresora"
                      @click="openEliminar(imp)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Estado Vacío -->
              <TableRow v-if="paginated.length === 0">
                <TableCell colspan="4" class="py-12 text-center text-xs text-gray-400">
                  No se encontraron impresoras registradas
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
         MODAL: CREAR / EDITAR IMPRESORA
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
            <Printer class="w-5 h-5 text-red-500" />
            <h3 class="font-bold text-sm uppercase tracking-wide">
              {{ isEditMode ? 'Editar Impresora' : 'Nueva Impresora' }}
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
        <form @submit.prevent="guardarImpresora" class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre de la Impresora *</label>
            <Input
              v-model="formData.nombre"
              type="text"
              placeholder="Ej. IMPTICKET"
              required
              class="h-9 text-xs rounded-lg border-gray-300 uppercase"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">IP / Host *</label>
              <Input
                v-model="formData.ip"
                type="text"
                placeholder="localhost o 192.168.1.200"
                required
                class="h-9 text-xs rounded-lg border-gray-300 font-mono"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Puerto</label>
              <Input
                v-model="formData.puerto"
                type="text"
                placeholder="9100"
                class="h-9 text-xs rounded-lg border-gray-300 font-mono"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Tipo de Conexión</label>
              <select
                v-model="formData.tipo"
                class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
              >
                <option v-for="t in tiposConexion" :key="t" :value="t">{{ t }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Ancho Papel</label>
              <select
                v-model="formData.anchoPapel"
                class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
              >
                <option v-for="ap in anchosPapel" :key="ap" :value="ap">{{ ap }}</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Modelo / Marca</label>
            <Input
              v-model="formData.marca"
              type="text"
              placeholder="Ej. EPSON TM-T20III"
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
              {{ isEditMode ? 'Guardar Cambios' : 'Registrar Impresora' }}
            </Button>
          </div>
        </form>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: VER DETALLES & PROBAR IMPRESIÓN
         ==================================================================== -->
    <div
      v-if="showVerModal && impresoraSeleccionada"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Printer class="w-5 h-5 text-blue-400" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Detalles de Impresora</h3>
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
              <span class="font-bold text-gray-900">{{ impresoraSeleccionada.nombre }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">IP / Dirección:</span>
              <span class="font-mono text-gray-800">{{ impresoraSeleccionada.ip }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Tipo:</span>
              <span class="font-semibold text-gray-800">{{ impresoraSeleccionada.tipo }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Puerto:</span>
              <span class="font-mono text-gray-800">{{ impresoraSeleccionada.puerto }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Ancho Papel:</span>
              <span class="text-gray-800">{{ impresoraSeleccionada.anchoPapel }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Modelo:</span>
              <span class="text-gray-800">{{ impresoraSeleccionada.marca }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Estado:</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                {{ impresoraSeleccionada.estado }}
              </span>
            </div>
          </div>

          <div v-if="testMessage" class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 flex items-center gap-2">
            <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0" />
            <span>{{ testMessage }}</span>
          </div>

          <div class="flex items-center justify-between pt-2">
            <Button
              type="button"
              variant="outline"
              size="sm"
              class="border-blue-400 text-blue-700 hover:bg-blue-50 text-xs px-3.5"
              @click="probarImpresion"
            >
              🖨️ Probar Impresión
            </Button>
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
         MODAL: ELIMINAR IMPRESORA
         ==================================================================== -->
    <div
      v-if="showDeleteModal && impresoraAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center animate-in fade-in zoom-in-95 duration-150">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
          <TriangleAlert class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-base text-gray-900 mb-1">¿Eliminar Impresora?</h3>
        <p class="text-xs text-gray-500 mb-6">
          ¿Estás seguro de que deseas eliminar la impresora <strong class="text-gray-800">"{{ impresoraAEliminar.nombre }}"</strong>?
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
