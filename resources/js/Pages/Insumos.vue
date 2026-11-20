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
  Package,
  ImagePlus,
  TriangleAlert,
  CheckCircle2,
  Tag,
  DollarSign,
  AlignLeft,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// CATEGORÍAS DISPONIBLES
// -------------------------------------------------------------------
const categorias = [
  { id: 'lacteos', label: 'Lácteos' },
  { id: 'carnes', label: 'Carnes y Embutidos' },
  { id: 'verduras', label: 'Verduras y Hortalizas' },
  { id: 'granos', label: 'Granos y Cereales' },
  { id: 'condimentos', label: 'Condimentos y Especias' },
  { id: 'bebidas', label: 'Bebidas' },
  { id: 'panaderia', label: 'Panadería y Repostería' },
  { id: 'aceites', label: 'Aceites y Grasas' },
  { id: 'otros', label: 'Otros' },
]

// -------------------------------------------------------------------
// DATOS LOCALES DE INSUMOS
// -------------------------------------------------------------------
const insumos = ref([
  {
    id: 1,
    nombre: 'QUESO MOZZARELLA',
    categoria: 'lacteos',
    precioVenta: 28.50,
    descripcion: 'Queso mozzarella fresco para pizza y pastas, presentación de 500g.',
    estado: 'Activo',
    imagen: null,
  },
  {
    id: 2,
    nombre: 'CARNE MOLIDA DE RES',
    categoria: 'carnes',
    precioVenta: 22.00,
    descripcion: 'Carne molida de res primera calidad, ideal para hamburguesas y salsas.',
    estado: 'Activo',
    imagen: null,
  },
  {
    id: 3,
    nombre: 'HARINA DE TRIGO',
    categoria: 'granos',
    precioVenta: 8.50,
    descripcion: 'Harina de trigo todo uso para masas y rebozados, bolsa 1kg.',
    estado: 'Activo',
    imagen: null,
  },
  {
    id: 4,
    nombre: 'TOMATE FRESCO',
    categoria: 'verduras',
    precioVenta: 5.00,
    descripcion: 'Tomate fresco de temporada, seleccionado para salsas y ensaladas.',
    estado: 'Activo',
    imagen: null,
  },
  {
    id: 5,
    nombre: 'ACEITE DE OLIVA',
    categoria: 'aceites',
    precioVenta: 35.00,
    descripcion: 'Aceite de oliva extra virgen, botella 750ml, importado.',
    estado: 'Inactivo',
    imagen: null,
  },
  {
    id: 6,
    nombre: 'OREGANO SECO',
    categoria: 'condimentos',
    precioVenta: 4.50,
    descripcion: 'Orégano seco molido, bolsita 50g para condimentar pizzas y carnes.',
    estado: 'Activo',
    imagen: null,
  },
])

// -------------------------------------------------------------------
// BÚSQUEDA Y PAGINACIÓN
// -------------------------------------------------------------------
const searchQuery = ref('')
const categoriaFilter = ref('')
const rowsPerPage = ref(10)
const currentPage = ref(1)

const filtered = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  return insumos.value.filter((ins) => {
    const matchQ = !q || ins.nombre.toLowerCase().includes(q) || ins.descripcion.toLowerCase().includes(q)
    const matchCat = !categoriaFilter.value || ins.categoria === categoriaFilter.value
    return matchQ && matchCat
  })
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))

const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

function getCategoriaLabel(id) {
  return categorias.find((c) => c.id === id)?.label || id
}

// -------------------------------------------------------------------
// MODAL: CREAR / EDITAR INSUMO
// -------------------------------------------------------------------
const showModal = ref(false)
const isEditMode = ref(false)
const imagePreview = ref(null)
const formData = ref({
  id: null,
  nombre: '',
  categoria: '',
  precioVenta: '',
  descripcion: '',
  estado: 'Activo',
  imagen: null,
})

function openNuevoInsumo() {
  isEditMode.value = false
  imagePreview.value = null
  formData.value = {
    id: null,
    nombre: '',
    categoria: '',
    precioVenta: '',
    descripcion: '',
    estado: 'Activo',
    imagen: null,
  }
  showModal.value = true
}

function openEditarInsumo(ins) {
  isEditMode.value = true
  imagePreview.value = ins.imagen || null
  formData.value = { ...ins }
  showModal.value = true
}

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = (e) => {
    imagePreview.value = e.target.result
    formData.value.imagen = e.target.result
  }
  reader.readAsDataURL(file)
}

function removeImage() {
  imagePreview.value = null
  formData.value.imagen = null
}

function guardarInsumo() {
  if (!formData.value.nombre.trim() || !formData.value.categoria) return

  if (isEditMode.value) {
    const idx = insumos.value.findIndex((i) => i.id === formData.value.id)
    if (idx !== -1) {
      insumos.value[idx] = { ...insumos.value[idx], ...formData.value }
    }
  } else {
    const newId = Math.max(0, ...insumos.value.map((i) => i.id)) + 1
    insumos.value.push({ ...formData.value, id: newId })
  }

  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
  showModal.value = false
}

// -------------------------------------------------------------------
// MODAL: VER DETALLES
// -------------------------------------------------------------------
const showVerModal = ref(false)
const insumoSeleccionado = ref(null)

function openVerInsumo(ins) {
  insumoSeleccionado.value = ins
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL: ELIMINAR INSUMO
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const insumoAEliminar = ref(null)

function openEliminar(ins) {
  insumoAEliminar.value = ins
  showDeleteModal.value = true
}

function confirmarEliminar() {
  insumos.value = insumos.value.filter((i) => i.id !== insumoAEliminar.value.id)
  showDeleteModal.value = false
  insumoAEliminar.value = null
}

// Toast feedback
const showSuccessToast = ref(false)
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="insumos">
    <!-- Cabecera de Página -->
    <div class="flex flex-wrap sm:flex-nowrap items-center justify-between gap-3 sm:gap-4">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">Insumos</h1>
        <p class="text-xs text-gray-500 mt-0.5">Gestión de materias primas e ingredientes del almacén</p>
      </div>

      <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
        <transition name="fade">
          <div
            v-if="showSuccessToast"
            class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg"
          >
            <CheckCircle2 class="w-4 h-4 text-emerald-600" />
            <span>¡Insumo guardado exitosamente!</span>
          </div>
        </transition>

        <Button
          size="sm"
          class="bg-[#20233B] hover:bg-[#2B2F4C] text-white rounded-lg px-4 py-1.5 h-9 text-xs font-semibold tracking-wider flex items-center gap-1.5 shadow-sm transition-all"
          @click="openNuevoInsumo"
        >
          <Plus class="w-4 h-4 stroke-[2.5]" />
          <span>NUEVO</span>
        </Button>
      </div>
    </div>

    <!-- Tarjeta Principal -->
    <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-4 sm:p-6 flex flex-col justify-between min-h-0 sm:min-h-0 sm:min-h-[520px]">
      <div>
        <!-- Barra de Herramientas: Búsqueda + Filtro Categoría -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
            <!-- Búsqueda -->
            <div class="relative w-full sm:w-72">
              <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar insumo..."
                class="pl-9 pr-4 py-1.5 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-xs focus-visible:ring-1 focus-visible:ring-gray-400"
              />
            </div>

            <!-- Filtro Categoría -->
            <div class="relative">
              <select
                v-model="categoriaFilter"
                class="h-9 pl-3 pr-8 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400 appearance-none cursor-pointer"
              >
                <option value="">Todas las categorías</option>
                <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                  {{ cat.label }}
                </option>
              </select>
              <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400">▾</span>
            </div>
          </div>

          <span class="text-xs text-gray-400 font-medium">
            {{ filtered.length }} insumo{{ filtered.length !== 1 ? 's' : '' }} encontrado{{ filtered.length !== 1 ? 's' : '' }}
          </span>
        </div>

        <!-- TABLA DE INSUMOS -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <Table>
            <TableHeader class="bg-[#232738] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-left w-12">#</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-left">Nombre</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Categoría</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Precio Venta</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center">Estado</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-28">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="(ins, index) in paginated"
                :key="ins.id"
                class="hover:bg-gray-50/70 border-b border-gray-200 transition-colors"
              >
                <!-- Nro -->
                <TableCell class="py-3 px-4 text-xs text-gray-400 font-medium">
                  {{ (currentPage - 1) * rowsPerPage + index + 1 }}
                </TableCell>

                <!-- Nombre con miniatura -->
                <TableCell class="py-3 px-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-9 h-9 rounded-lg shrink-0 overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center"
                    >
                      <img
                        v-if="ins.imagen"
                        :src="ins.imagen"
                        :alt="ins.nombre"
                        class="w-full h-full object-cover"
                      />
                      <Package v-else class="w-4 h-4 text-gray-300" />
                    </div>
                    <div>
                      <p class="text-xs font-semibold text-gray-800">{{ ins.nombre }}</p>
                      <p class="text-[11px] text-gray-400 line-clamp-1 max-w-[200px]">{{ ins.descripcion || '—' }}</p>
                    </div>
                  </div>
                </TableCell>

                <!-- Categoría -->
                <TableCell class="py-3 px-4 text-center">
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    <Tag class="w-2.5 h-2.5" />
                    {{ getCategoriaLabel(ins.categoria) }}
                  </span>
                </TableCell>

                <!-- Precio -->
                <TableCell class="py-3 px-4 text-center text-xs font-bold text-gray-800">
                  S/ {{ Number(ins.precioVenta).toFixed(2) }}
                </TableCell>

                <!-- Estado -->
                <TableCell class="py-3 px-4 text-center">
                  <span
                    class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold"
                    :class="[
                      ins.estado === 'Activo'
                        ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                        : 'bg-gray-100 text-gray-500 border border-gray-200'
                    ]"
                  >
                    {{ ins.estado }}
                  </span>
                </TableCell>

                <!-- Acciones -->
                <TableCell class="py-3 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <button
                      class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Ver detalles"
                      @click="openVerInsumo(ins)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>
                    <button
                      class="w-6 h-6 rounded border border-emerald-300 bg-emerald-50/80 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Editar insumo"
                      @click="openEditarInsumo(ins)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>
                    <button
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Eliminar insumo"
                      @click="openEliminar(ins)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Estado Vacío -->
              <TableRow v-if="paginated.length === 0">
                <TableCell colspan="6" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-2 text-gray-400">
                    <Package class="w-8 h-8 text-gray-300" />
                    <p class="text-xs font-medium">No se encontraron insumos</p>
                    <p class="text-[11px]">Ajusta la búsqueda o agrega un nuevo insumo</p>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </div>

      <!-- PAGINACIÓN -->
      <div class="mt-6 pt-2">
        <DataTablePagination
          v-model:current-page="currentPage"
          v-model:rows-per-page="rowsPerPage"
          :total-pages="totalPages"
        />
      </div>
    </Card>

    <!-- ====================================================================
         MODAL: CREAR / EDITAR INSUMO
         ==================================================================== -->
    <div
      v-if="showModal"
      @click.self="showModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <Package class="w-5 h-5 text-amber-400" />
            <h3 class="font-bold text-sm uppercase tracking-wide">
              {{ isEditMode ? 'Editar Insumo' : 'Nuevo Insumo' }}
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
        <form @submit.prevent="guardarInsumo" class="p-6 space-y-4">

          <!-- Zona de Imagen -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2">Imagen del Insumo</label>
            <div
              class="relative group rounded-xl border-2 border-dashed overflow-hidden transition-colors"
              :class="[imagePreview ? 'border-gray-200' : 'border-gray-300 hover:border-gray-400 cursor-pointer bg-gray-50/60']"
            >
              <!-- Preview de imagen -->
              <template v-if="imagePreview">
                <img :src="imagePreview" alt="Preview" class="w-full h-36 object-cover" />
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                  <button
                    type="button"
                    class="opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow transition-all"
                    @click="removeImage"
                    title="Quitar imagen"
                  >
                    <X class="w-4 h-4" />
                  </button>
                </div>
              </template>

              <!-- Zona de carga -->
              <template v-else>
                <label for="imagen-upload" class="flex flex-col items-center justify-center gap-2 py-8 cursor-pointer w-full">
                  <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                    <ImagePlus class="w-5 h-5 text-gray-400" />
                  </div>
                  <div class="text-center">
                    <p class="text-xs font-medium text-gray-600">Haz clic para subir una imagen</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">PNG, JPG o WEBP — máx. 2MB</p>
                  </div>
                </label>
                <input
                  id="imagen-upload"
                  type="file"
                  accept="image/*"
                  class="hidden"
                  @change="handleImageUpload"
                />
              </template>
            </div>
          </div>

          <!-- Nombre -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">
              Nombre del Insumo *
            </label>
            <div class="relative">
              <Package class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
              <Input
                v-model="formData.nombre"
                type="text"
                placeholder="Ej. QUESO MOZZARELLA"
                required
                class="pl-9 h-9 text-xs rounded-lg border-gray-300 uppercase"
              />
            </div>
          </div>

          <!-- Fila: Categoría + Precio -->
          <div class="grid grid-cols-2 gap-4">
            <!-- Categoría -->
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">
                Categoría *
              </label>
              <div class="relative">
                <Tag class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" />
                <select
                  v-model="formData.categoria"
                  required
                  class="h-9 w-full pl-9 pr-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400 appearance-none"
                >
                  <option value="" disabled>Seleccionar...</option>
                  <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
                    {{ cat.label }}
                  </option>
                </select>
              </div>
            </div>

            <!-- Precio de Venta -->
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">
                Precio de Venta (S/)
              </label>
              <div class="relative">
                <DollarSign class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" />
                <Input
                  v-model="formData.precioVenta"
                  type="number"
                  step="0.01"
                  min="0"
                  placeholder="0.00"
                  class="pl-9 h-9 text-xs rounded-lg border-gray-300"
                />
              </div>
            </div>
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">
              Descripción
            </label>
            <div class="relative">
              <AlignLeft class="absolute left-3 top-3 w-3.5 h-3.5 text-gray-400" />
              <textarea
                v-model="formData.descripcion"
                rows="2"
                placeholder="Descripción breve del insumo..."
                class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border border-gray-300 bg-white text-gray-700 resize-none focus:outline-none focus:ring-1 focus:ring-gray-400 placeholder:text-gray-400"
              ></textarea>
            </div>
          </div>

          <!-- Estado -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Estado</label>
            <div class="flex gap-3">
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="radio"
                  v-model="formData.estado"
                  value="Activo"
                  class="text-emerald-500 focus:ring-emerald-400"
                />
                <span class="text-xs font-medium text-gray-700">Activo</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="radio"
                  v-model="formData.estado"
                  value="Inactivo"
                  class="text-gray-400 focus:ring-gray-300"
                />
                <span class="text-xs font-medium text-gray-700">Inactivo</span>
              </label>
            </div>
          </div>

          <!-- Botones -->
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
              {{ isEditMode ? 'Guardar Cambios' : 'Registrar Insumo' }}
            </Button>
          </div>
        </form>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: VER DETALLES
         ==================================================================== -->
    <div
      v-if="showVerModal && insumoSeleccionado"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <Package class="w-5 h-5 text-blue-400" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Detalle del Insumo</h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showVerModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <div class="p-6 space-y-4 text-xs">
          <!-- Imagen -->
          <div
            class="w-full h-36 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center"
          >
            <img
              v-if="insumoSeleccionado.imagen"
              :src="insumoSeleccionado.imagen"
              :alt="insumoSeleccionado.nombre"
              class="w-full h-full object-cover"
            />
            <div v-else class="flex flex-col items-center gap-2 text-gray-300">
              <Package class="w-8 h-8" />
              <span class="text-[11px]">Sin imagen</span>
            </div>
          </div>

          <!-- Info -->
          <div class="bg-gray-50 p-4 rounded-xl space-y-2.5 border border-gray-100">
            <div class="flex justify-between items-start">
              <span class="text-gray-500 font-medium">Nombre:</span>
              <span class="font-bold text-gray-900 text-right max-w-[60%]">{{ insumoSeleccionado.nombre }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Categoría:</span>
              <span class="font-semibold text-blue-700">{{ getCategoriaLabel(insumoSeleccionado.categoria) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Precio de Venta:</span>
              <span class="font-bold text-gray-900">S/ {{ Number(insumoSeleccionado.precioVenta).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Estado:</span>
              <span
                class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                :class="[
                  insumoSeleccionado.estado === 'Activo'
                    ? 'bg-emerald-100 text-emerald-700'
                    : 'bg-gray-100 text-gray-500'
                ]"
              >
                {{ insumoSeleccionado.estado }}
              </span>
            </div>
            <div v-if="insumoSeleccionado.descripcion">
              <span class="text-gray-500 font-medium block mb-1">Descripción:</span>
              <p class="text-gray-800 bg-white p-2.5 rounded-lg border border-gray-200 leading-relaxed">
                {{ insumoSeleccionado.descripcion }}
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
         MODAL: ELIMINAR INSUMO
         ==================================================================== -->
    <div
      v-if="showDeleteModal && insumoAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
          <TriangleAlert class="w-6 h-6" />
        </div>
        <h3 class="font-bold text-base text-gray-900 mb-1">¿Eliminar Insumo?</h3>
        <p class="text-xs text-gray-500 mb-6">
          ¿Estás seguro de que deseas eliminar
          <strong class="text-gray-800">"{{ insumoAEliminar.nombre }}"</strong>?
          Esta acción no se puede deshacer.
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

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
