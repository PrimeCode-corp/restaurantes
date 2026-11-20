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
  Phone,
  FileText,
  CheckCircle2,
  TriangleAlert,
  ArrowLeft,
  Save,
  MapPin,
  Calendar,
  Mail,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// TIPOS DE DOCUMENTO
// -------------------------------------------------------------------
const tiposDocumento = [
  'DNI - DOC. NACIONAL DE IDENTIDAD',
  'RUC - REGISTRO ÚNICO DE CONTRIBUYENTE',
  'CARNET DE EXTRANJERÍA',
  'PASAPORTE',
]

// -------------------------------------------------------------------
// DATOS LOCALES DE PROVEEDORES (Exacto a la imagen de referencia)
// -------------------------------------------------------------------
const proveedores = ref([
  {
    id: 1,
    proveedor: 'ABARROTES EL MOSHAQUITO S.A.C.',
    codProveedor: 'P000003',
    direccion: 'JR. JIRON 123',
    ruc: '01046282837',
    telefono: '000000000',
    tipoDoc: 'RUC - REGISTRO ÚNICO DE CONTRIBUYENTE',
    numDoc: '01046282837',
    nombreComercial: 'ABARROTES EL MOSHAQUITO',
    tipo: 'PERSONA JURÍDICA',
    fechaNacimiento: '1998-05-14',
    email: 'contacto@elmoshaquito.com',
    otrasDirecciones: [
      { id: 1, direccion: 'AV. LOS HEROES 450 - SUCURSAL 2', telefono: '042521199' },
    ],
  },
  {
    id: 2,
    proveedor: 'DISTRIBUIDORA LACTEOS DEL VALLE S.R.L.',
    codProveedor: 'P000004',
    direccion: 'AV. CIRCUNVALACION 742',
    ruc: '20548963211',
    telefono: '942851234',
    tipoDoc: 'RUC - REGISTRO ÚNICO DE CONTRIBUYENTE',
    numDoc: '20548963211',
    nombreComercial: 'LÁCTEOS DEL VALLE',
    tipo: 'PERSONA JURÍDICA',
    fechaNacimiento: '2005-11-20',
    email: 'ventas@lacteosvalle.com',
    otrasDirecciones: [],
  },
  {
    id: 3,
    proveedor: 'CARNES Y EMBUTIDOS SAN MARTÍN E.I.R.L.',
    codProveedor: 'P000005',
    direccion: 'JR. ALFONSO UGARTE 310',
    ruc: '20601248963',
    telefono: '978456123',
    tipoDoc: 'RUC - REGISTRO ÚNICO DE CONTRIBUYENTE',
    numDoc: '20601248963',
    nombreComercial: 'EMBUTIDOS SAN MARTÍN',
    tipo: 'PERSONA JURÍDICA',
    fechaNacimiento: '2012-03-08',
    email: 'pedidos@sanmartincarnes.pe',
    otrasDirecciones: [
      { id: 1, direccion: 'PARQUE INDUSTRIAL MZ B LTE 4', telefono: '978456125' },
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
  if (!q) return proveedores.value
  return proveedores.value.filter(
    (p) =>
      p.proveedor.toLowerCase().includes(q) ||
      p.codProveedor.toLowerCase().includes(q) ||
      p.ruc.toLowerCase().includes(q) ||
      p.direccion.toLowerCase().includes(q) ||
      p.telefono.toLowerCase().includes(q)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / rowsPerPage.value)))

const paginated = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filtered.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// ESTADOS Y MODAL CREAR / EDITAR PROVEEDOR
// -------------------------------------------------------------------
const showModal = ref(false)
const isEditMode = ref(false)
const activeModalTab = ref('principales') // 'principales' | 'direcciones'
const isSearchingReniec = ref(false)
const showSuccessToast = ref(false)
const toastMessage = ref('')

const defaultForm = () => ({
  id: null,
  numDoc: '',
  tipoDoc: 'DNI - DOC. NACIONAL DE IDENTIDAD',
  razonSocial: '',
  fechaNacimiento: '',
  nombreComercial: '',
  telefono: '',
  direccion: '',
  email: '',
  tipo: '',
  otrasDirecciones: [],
})

const formData = ref(defaultForm())

// Apertura modal nuevo
function openNuevoProveedor() {
  isEditMode.value = false
  activeModalTab.value = 'principales'
  formData.value = defaultForm()
  showModal.value = true
}

// Apertura modal editar
function openEditarProveedor(p) {
  isEditMode.value = true
  activeModalTab.value = 'principales'
  formData.value = {
    id: p.id,
    numDoc: p.numDoc || p.ruc || '',
    tipoDoc: p.tipoDoc || 'RUC - REGISTRO ÚNICO DE CONTRIBUYENTE',
    razonSocial: p.proveedor || '',
    fechaNacimiento: p.fechaNacimiento || '',
    nombreComercial: p.nombreComercial || '',
    telefono: p.telefono || '',
    direccion: p.direccion || '',
    email: p.email || '',
    tipo: p.tipo || '',
    otrasDirecciones: p.otrasDirecciones ? JSON.parse(JSON.stringify(p.otrasDirecciones)) : [],
  }
  showModal.value = true
}

// Búsqueda simulada RENIEC / SUNAT
function buscarReniec() {
  if (!formData.value.numDoc) return
  isSearchingReniec.value = true
  setTimeout(() => {
    isSearchingReniec.value = false
    const doc = formData.value.numDoc.trim()
    if (doc.length === 8) {
      formData.value.razonSocial = 'RODRIGUEZ MENDOZA CARLOS ALBERTO'
      formData.value.nombreComercial = 'COMERCIAL DON CARLOS'
      formData.value.direccion = 'JR. SAN MARTIN 450 - TARAPOTO'
      formData.value.tipo = 'PERSONA NATURAL CON NEGOCIO'
      formData.value.telefono = '942852190'
      formData.value.email = 'carlos.mendoza@gmail.com'
    } else if (doc.length === 11) {
      formData.value.razonSocial = 'INVERSIONES GASTRONOMICAS DEL ORIENTE S.A.C.'
      formData.value.nombreComercial = 'DISTRIBUIDORA GASTRONOMICA'
      formData.value.direccion = 'AV. LIMA 1020 - SAN MARTIN'
      formData.value.tipo = 'SOCIEDAD ANÓNIMA CERRADA'
      formData.value.telefono = '042523410'
      formData.value.email = 'contacto@gastronomicaoriente.pe'
    } else {
      formData.value.razonSocial = 'PROVEEDOR GENERAL ' + doc
      formData.value.direccion = 'DIRECCIÓN FISCAL REGISTRADA'
      formData.value.tipo = 'ACTIVO HABIDO'
    }
  }, 450)
}

// Agregar fila a otras direcciones
function addOtrasDireccionRow() {
  formData.value.otrasDirecciones.push({
    id: Date.now(),
    direccion: '',
    telefono: '',
  })
}

// Eliminar fila de otras direcciones
function removeOtrasDireccionRow(idx) {
  formData.value.otrasDirecciones.splice(idx, 1)
}

// Guardar Proveedor
function guardarProveedor() {
  if (!formData.value.numDoc && !formData.value.razonSocial) return

  const nombreFinal = formData.value.razonSocial.trim() || formData.value.nombreComercial.trim() || 'PROVEEDOR REGISTRADO'

  if (isEditMode.value) {
    const idx = proveedores.value.findIndex((p) => p.id === formData.value.id)
    if (idx !== -1) {
      proveedores.value[idx] = {
        ...proveedores.value[idx],
        proveedor: nombreFinal,
        ruc: formData.value.numDoc,
        numDoc: formData.value.numDoc,
        tipoDoc: formData.value.tipoDoc,
        direccion: formData.value.direccion || 'SIN DIRECCIÓN',
        telefono: formData.value.telefono || '000000000',
        nombreComercial: formData.value.nombreComercial,
        tipo: formData.value.tipo,
        fechaNacimiento: formData.value.fechaNacimiento,
        email: formData.value.email,
        otrasDirecciones: JSON.parse(JSON.stringify(formData.value.otrasDirecciones)),
      }
      toastMessage.value = '¡Proveedor actualizado exitosamente!'
    }
  } else {
    const nextNum = proveedores.value.length + 1
    const cod = 'P' + String(nextNum).padStart(6, '0')
    proveedores.value.unshift({
      id: Date.now(),
      proveedor: nombreFinal,
      codProveedor: cod,
      ruc: formData.value.numDoc || '00000000000',
      numDoc: formData.value.numDoc,
      tipoDoc: formData.value.tipoDoc,
      direccion: formData.value.direccion || 'SIN DIRECCIÓN',
      telefono: formData.value.telefono || '000000000',
      nombreComercial: formData.value.nombreComercial,
      tipo: formData.value.tipo,
      fechaNacimiento: formData.value.fechaNacimiento,
      email: formData.value.email,
      otrasDirecciones: JSON.parse(JSON.stringify(formData.value.otrasDirecciones)),
    })
    toastMessage.value = '¡Proveedor registrado exitosamente!'
  }

  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
  showModal.value = false
}

// -------------------------------------------------------------------
// MODAL VER DETALLES
// -------------------------------------------------------------------
const showVerModal = ref(false)
const proveedorSeleccionado = ref(null)

function openVerProveedor(p) {
  proveedorSeleccionado.value = p
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL ELIMINAR
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const proveedorAEliminar = ref(null)

function openDeleteModal(p) {
  proveedorAEliminar.value = p
  showDeleteModal.value = true
}

function confirmDelete() {
  if (!proveedorAEliminar.value) return
  proveedores.value = proveedores.value.filter((p) => p.id !== proveedorAEliminar.value.id)
  showDeleteModal.value = false
  proveedorAEliminar.value = null
  toastMessage.value = 'Proveedor eliminado correctamente'
  showSuccessToast.value = true
  setTimeout(() => { showSuccessToast.value = false }, 3000)
}
</script>

<template>
  <AdminLayout active-nav="almacenes" active-submenu="proveedores">
    <!-- Contenedor Principal de la Vista Proveedores -->
    <div class="space-y-4">
      <!-- Título de Página -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">Proveedores</h1>
        <transition name="fade">
          <div
            v-if="showSuccessToast"
            class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg shadow-xs"
          >
            <CheckCircle2 class="w-4 h-4 text-emerald-600" />
            <span>{{ toastMessage }}</span>
          </div>
        </transition>
      </div>

      <!-- Tarjeta Blanca Contenedora (Fiel a la pantalla 1) -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-7 flex flex-col justify-between min-h-0 sm:min-h-[560px]">
        <div>
          <!-- Barra Superior: Buscador + Botón + NUEVO -->
          <div class="flex items-center justify-between gap-4 mb-6">
            <!-- Barra de búsqueda redondeada -->
            <div class="relative w-full max-w-sm max-h-[90vh] flex flex-col">
              <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar..."
                class="pl-10 pr-4 py-2 h-9 text-xs rounded-lg border-gray-300 bg-white placeholder:text-gray-400 shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-400"
              />
            </div>

            <!-- Botón + NUEVO (Fiel al botón azul índigo de la imagen) -->
            <button
              type="button"
              class="bg-[#24265D] hover:bg-[#1E204E] text-white px-5 py-2 h-9 rounded-md text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-all cursor-pointer select-none shrink-0"
              @click="openNuevoProveedor"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" />
              <span>NUEVO</span>
            </button>
          </div>

          <!-- TABLA DE PROVEEDORES (Fiel al diseño exacto de la imagen) -->
          <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-2xs">
            <Table class="w-full border-collapse">
              <TableHeader class="bg-[#20233B] text-white">
                <TableRow class="border-none hover:bg-transparent">
                  <TableHead class="text-xs font-semibold text-white h-10 px-5 py-2.5 text-center">Proveedor</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 py-2.5 text-center">Cod. Proveedor</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 py-2.5 text-center">Dirección</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 py-2.5 text-center">RUC</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 py-2.5 text-center">Teléfono</TableHead>
                  <TableHead class="text-xs font-semibold text-white h-10 px-4 py-2.5 text-center w-32">Acciones</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <!-- Filas con datos reales -->
                <TableRow
                  v-for="prov in paginated"
                  :key="prov.id"
                  class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors"
                >
                  <TableCell class="py-3 px-5 text-xs font-medium text-gray-800 text-center">
                    {{ prov.proveedor }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs font-mono text-gray-600 text-center">
                    {{ prov.codProveedor }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs text-gray-600 text-center">
                    {{ prov.direccion }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs font-mono text-gray-600 text-center">
                    {{ prov.ruc }}
                  </TableCell>
                  <TableCell class="py-3 px-4 text-xs font-mono text-gray-600 text-center">
                    {{ prov.telefono }}
                  </TableCell>
                  <!-- Acciones con iconos estilizados: Azul (Ver), Verde (Editar), Rojo (Eliminar) -->
                  <TableCell class="py-3 px-4 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                      <!-- Ver (Azul claro suave) -->
                      <button
                        type="button"
                        class="w-7 h-7 rounded-md bg-[#EBF3FC] hover:bg-[#D7E9FB] text-[#2B77D6] flex items-center justify-center transition-colors cursor-pointer"
                        title="Ver detalles"
                        @click="openVerProveedor(prov)"
                      >
                        <Eye class="w-3.5 h-3.5" />
                      </button>
                      <!-- Editar (Verde claro suave) -->
                      <button
                        type="button"
                        class="w-7 h-7 rounded-md bg-[#EAF7EE] hover:bg-[#D4EEDC] text-[#34A853] flex items-center justify-center transition-colors cursor-pointer"
                        title="Editar"
                        @click="openEditarProveedor(prov)"
                      >
                        <Pencil class="w-3.5 h-3.5" />
                      </button>
                      <!-- Eliminar (Rojo claro suave) -->
                      <button
                        type="button"
                        class="w-7 h-7 rounded-md bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors cursor-pointer"
                        title="Eliminar"
                        @click="openDeleteModal(prov)"
                      >
                        <Trash2 class="w-3.5 h-3.5" />
                      </button>
                    </div>
                  </TableCell>
                </TableRow>

                <!-- Filas Skeleton de relleno simuladas si hay pocos registros (como en la imagen mockup) -->
                <TableRow
                  v-for="i in Math.max(0, 3 - paginated.length)"
                  :key="'skeleton-' + i"
                  class="border-b border-gray-100"
                >
                  <TableCell class="py-3.5 px-5 text-center">
                    <div class="h-2.5 bg-gray-200 rounded-full w-44 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3.5 px-4 text-center">
                    <div class="h-2.5 bg-gray-200 rounded-full w-14 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3.5 px-4 text-center">
                    <div class="h-2.5 bg-gray-200 rounded-full w-28 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3.5 px-4 text-center">
                    <div class="h-2.5 bg-gray-200 rounded-full w-20 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3.5 px-4 text-center">
                    <div class="h-2.5 bg-gray-200 rounded-full w-16 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3.5 px-4 text-center">
                    <div class="flex items-center justify-center gap-1.5 opacity-60">
                      <div class="w-7 h-7 rounded-md bg-[#EBF3FC] flex items-center justify-center text-[#2B77D6]">
                        <Eye class="w-3.5 h-3.5" />
                      </div>
                      <div class="w-7 h-7 rounded-md bg-[#EAF7EE] flex items-center justify-center text-[#34A853]">
                        <Pencil class="w-3.5 h-3.5" />
                      </div>
                      <div class="w-7 h-7 rounded-md bg-[#FDEEED] flex items-center justify-center text-[#E53935]">
                        <Trash2 class="w-3.5 h-3.5" />
                      </div>
                    </div>
                  </TableCell>
                </TableRow>

                <!-- Estado vacío si la búsqueda no arroja nada -->
                <TableRow v-if="filtered.length === 0">
                  <TableCell colspan="6" class="text-center py-10 text-gray-400 text-xs">
                    No se encontraron proveedores que coincidan con "{{ searchQuery }}"
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>

        <!-- Paginación inferior (Fiel a la esquina inferior izquierda: [10 v] [<-] Página 1 de 1 [->]) -->
        <div class="pt-6 flex items-center justify-between">
          <DataTablePagination
            :current-page="currentPage"
            :total-pages="totalPages"
            :rows-per-page="rowsPerPage"
            :rows-per-page-options="[5, 10, 20, 50]"
            @update:current-page="currentPage = $event"
            @update:rows-per-page="rowsPerPage = $event"
          />
          <span class="text-xs text-gray-400 font-medium">
            Total {{ filtered.length }} proveedores
          </span>
        </div>
      </Card>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL: NUEVO / EDITAR PROVEEDOR (Fiel a la Imagen 2)              -->
    <!-- ================================================================= -->
    <div
      v-if="showModal"
      @click.self="showModal = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden animate-in zoom-in-95 duration-150 flex flex-col max-h-[90vh] flex flex-col">
        <!-- Header del Modal -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-base text-gray-900 font-sans tracking-tight">
            {{ isEditMode ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md"
            @click="showModal = false"
          >
            <X class="w-5 h-5 stroke-[2]" />
          </button>
        </div>

        <!-- Pestañas de Navegación del Modal: Datos Principales | Otras Direcciones -->
        <div class="flex items-center gap-6 px-6 pt-3 border-b border-gray-200 bg-white select-none">
          <button
            type="button"
            class="pb-2.5 text-xs font-semibold transition-all relative"
            :class="[
              activeModalTab === 'principales'
                ? 'text-[#F0302E] font-bold after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#F0302E]'
                : 'text-gray-500 hover:text-gray-800'
            ]"
            @click="activeModalTab = 'principales'"
          >
            Datos Principales
          </button>
          <button
            type="button"
            class="pb-2.5 text-xs font-semibold transition-all relative"
            :class="[
              activeModalTab === 'direcciones'
                ? 'text-[#F0302E] font-bold after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#F0302E]'
                : 'text-gray-500 hover:text-gray-800'
            ]"
            @click="activeModalTab = 'direcciones'"
          >
            Otras Direcciones
          </button>
        </div>

        <!-- Contenido del Modal -->
        <div class="p-6 overflow-y-auto max-h-[70vh]">
          <!-- =================== TAB 1: DATOS PRINCIPALES =================== -->
          <div v-show="activeModalTab === 'principales'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Columna Izquierda -->
              <div class="space-y-4">
                <!-- N° de Documento con botón RENIEC -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    N° de Documento
                  </label>
                  <div class="flex items-center gap-2">
                    <Input
                      v-model="formData.numDoc"
                      type="text"
                      placeholder="Escribe aquí"
                      class="h-9 text-xs rounded-md border-gray-300 placeholder:text-gray-400 font-mono flex-1"
                    />
                    <button
                      type="button"
                      class="h-9 px-3.5 border border-[#3E55B3] text-[#3E55B3] hover:bg-[#EEF2FF] rounded-md text-xs font-bold tracking-wide flex items-center gap-1.5 transition-colors cursor-pointer shrink-0 disabled:opacity-50"
                      :disabled="isSearchingReniec"
                      @click="buscarReniec"
                    >
                      <Search class="w-3.5 h-3.5 stroke-[2.5]" />
                      <span>{{ isSearchingReniec ? 'BUSCANDO...' : 'RENIEC' }}</span>
                    </button>
                  </div>
                </div>

                <!-- Razón Social o Nombre Completo (Gris suave como en el diseño) -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    Razón Social o Nombre Completo
                  </label>
                  <Input
                    v-model="formData.razonSocial"
                    type="text"
                    placeholder="-"
                    class="h-9 text-xs rounded-md border-gray-200 bg-[#E9ECEF]/80 text-gray-800 placeholder:text-gray-400"
                  />
                </div>

                <!-- Nombre Comercial (Marca) -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    Nombre Comercial (Marca)
                  </label>
                  <Input
                    v-model="formData.nombreComercial"
                    type="text"
                    placeholder="Escribe aquí"
                    class="h-9 text-xs rounded-md border-gray-300 placeholder:text-gray-400 text-gray-800"
                  />
                </div>

                <!-- Dirección (Gris suave) -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    Dirección
                  </label>
                  <Input
                    v-model="formData.direccion"
                    type="text"
                    placeholder="-"
                    class="h-9 text-xs rounded-md border-gray-200 bg-[#E9ECEF]/80 text-gray-800 placeholder:text-gray-400"
                  />
                </div>

                <!-- Tipo con Icono de Pin morado / azul -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    Tipo
                  </label>
                  <div class="relative">
                    <Input
                      v-model="formData.tipo"
                      type="text"
                      placeholder="-"
                      class="h-9 pr-10 text-xs rounded-md border-gray-200 bg-[#E9ECEF]/80 text-gray-800 placeholder:text-gray-400"
                    />
                    <div class="absolute right-1 top-1 bottom-1 w-7 bg-[#2E3166] rounded flex items-center justify-center text-white pointer-events-none">
                      <MapPin class="w-3.5 h-3.5 fill-current" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Columna Derecha -->
              <div class="space-y-4">
                <!-- Tipo de Documento (Select) -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    Tipo
                  </label>
                  <select
                    v-model="formData.tipoDoc"
                    class="w-full h-9 px-3 text-xs rounded-md border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-400"
                  >
                    <option v-for="t in tiposDocumento" :key="t" :value="t">
                      {{ t }}
                    </option>
                  </select>
                </div>

                <!-- Fecha Nacimiento -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    Fecha Nacimiento
                  </label>
                  <div class="relative">
                    <Input
                      v-model="formData.fechaNacimiento"
                      type="date"
                      class="h-9 text-xs rounded-md border-gray-300 text-gray-700 w-full"
                    />
                  </div>
                </div>

                <!-- Teléfono -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    Teléfono
                  </label>
                  <Input
                    v-model="formData.telefono"
                    type="text"
                    placeholder="Escribe aquí"
                    class="h-9 text-xs rounded-md border-gray-300 placeholder:text-gray-400 text-gray-800 font-mono"
                  />
                </div>

                <!-- E-mail -->
                <div>
                  <label class="block text-xs font-semibold text-gray-800 mb-1">
                    E-mail
                  </label>
                  <Input
                    v-model="formData.email"
                    type="email"
                    placeholder="Escribe aquí"
                    class="h-9 text-xs rounded-md border-gray-300 placeholder:text-gray-400 text-gray-800"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- =================== TAB 2: OTRAS DIRECCIONES =================== -->
          <div v-show="activeModalTab === 'direcciones'" class="space-y-4">
            <!-- Botón + AÑADIR (Verde en la esquina derecha superior) -->
            <div class="flex justify-end">
              <button
                type="button"
                class="bg-[#12A347] hover:bg-[#0E8A3B] text-white px-4 py-2 rounded-md text-xs font-bold tracking-wide flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer"
                @click="addOtrasDireccionRow"
              >
                <Plus class="w-4 h-4 stroke-[2.5]" />
                <span>AÑADIR</span>
              </button>
            </div>

            <!-- Tabla de Otras Direcciones (Fiel al diseño exacto de la imagen 2 derecha) -->
            <div class="rounded-xl overflow-hidden border border-gray-200">
              <Table class="w-full border-collapse">
                <TableHeader class="bg-[#20233B] text-white">
                  <TableRow class="border-none hover:bg-transparent">
                    <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[55%]">Dirección</TableHead>
                    <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[35%]">Teléfono</TableHead>
                    <TableHead class="text-xs font-semibold text-white h-10 px-4 text-center w-[10%]"></TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow
                    v-for="(item, idx) in formData.otrasDirecciones"
                    :key="item.id || idx"
                    class="border-b border-gray-100 hover:bg-transparent"
                  >
                    <!-- Input Dirección redondeado -->
                    <TableCell class="p-3">
                      <Input
                        v-model="item.direccion"
                        type="text"
                        placeholder="Escribe aquí..."
                        class="h-9 text-xs rounded-full border-gray-300 placeholder:text-gray-400 px-4"
                      />
                    </TableCell>
                    <!-- Input Teléfono redondeado -->
                    <TableCell class="p-3">
                      <Input
                        v-model="item.telefono"
                        type="text"
                        placeholder="Escribe aquí..."
                        class="h-9 text-xs rounded-full border-gray-300 placeholder:text-gray-400 px-4 font-mono"
                      />
                    </TableCell>
                    <!-- Botón Eliminar Fila rojo suave con icono papelera -->
                    <TableCell class="p-3 text-center">
                      <button
                        type="button"
                        class="w-8 h-8 rounded-lg bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors mx-auto cursor-pointer"
                        title="Eliminar dirección"
                        @click="removeOtrasDireccionRow(idx)"
                      >
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </TableCell>
                  </TableRow>

                  <!-- Fila vacía inicial si no hay ninguna agregada -->
                  <TableRow v-if="formData.otrasDirecciones.length === 0">
                    <TableCell colspan="3" class="text-center py-8 text-gray-400 text-xs">
                      No hay otras direcciones registradas. Haz clic en <span class="font-semibold text-[#12A347]">+ AÑADIR</span> para agregar sucursales o depósitos alternos.
                    </TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </div>
          </div>
        </div>

        <!-- Footer del Modal: Botones ATRÁS y GUARDAR (Fiel a la imagen) -->
        <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end gap-3">
          <!-- Botón ATRÁS -->
          <button
            type="button"
            class="h-9 px-4 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
            @click="showModal = false"
          >
            <ArrowLeft class="w-3.5 h-3.5 stroke-[2.5]" />
            <span>ATRÁS</span>
          </button>
          <!-- Botón GUARDAR (Rojo vibrante #F0302E) -->
          <button
            type="button"
            class="h-9 px-6 bg-[#F0302E] hover:bg-[#DC2626] text-white rounded-lg text-xs font-bold tracking-wider flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer"
            @click="guardarProveedor"
          >
            <Save class="w-3.5 h-3.5 stroke-[2.5]" />
            <span>GUARDAR</span>
          </button>
        </div>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL: VER DETALLES DEL PROVEEDOR                                 -->
    <!-- ================================================================= -->
    <div
      v-if="showVerModal && proveedorSeleccionado"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="bg-[#20233B] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Building2 class="w-4 h-4 text-[#3E55B3]" />
            <h3 class="font-bold text-sm tracking-wide">Ficha del Proveedor</h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1"
            @click="showVerModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <div class="p-6 space-y-4 text-xs">
          <div class="bg-gray-50 p-4 rounded-xl border border-gray-200/80 space-y-2.5">
            <div class="flex justify-between items-start border-b border-gray-200 pb-2">
              <span class="text-gray-500 font-medium">Proveedor / Razón Social:</span>
              <span class="font-bold text-gray-900 text-right max-w-[280px]">{{ proveedorSeleccionado.proveedor }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-500 font-medium">Código Proveedor:</span>
              <span class="font-mono font-bold text-gray-800">{{ proveedorSeleccionado.codProveedor }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-500 font-medium">RUC / N° Doc:</span>
              <span class="font-mono font-bold text-gray-800">{{ proveedorSeleccionado.ruc }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-500 font-medium">Teléfono:</span>
              <span class="font-mono text-gray-800">{{ proveedorSeleccionado.telefono || 'Sin teléfono' }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-500 font-medium">Dirección Principal:</span>
              <span class="text-gray-800 text-right">{{ proveedorSeleccionado.direccion }}</span>
            </div>
            <div v-if="proveedorSeleccionado.email" class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-500 font-medium">E-mail:</span>
              <span class="text-gray-800">{{ proveedorSeleccionado.email }}</span>
            </div>
            <div v-if="proveedorSeleccionado.tipo" class="flex justify-between">
              <span class="text-gray-500 font-medium">Tipo:</span>
              <span class="text-gray-800">{{ proveedorSeleccionado.tipo }}</span>
            </div>
          </div>

          <!-- Otras direcciones registradas -->
          <div v-if="proveedorSeleccionado.otrasDirecciones?.length" class="space-y-2">
            <h4 class="font-bold text-gray-800 uppercase tracking-wide text-[11px]">Otras Direcciones</h4>
            <div class="space-y-1.5">
              <div
                v-for="(od, i) in proveedorSeleccionado.otrasDirecciones"
                :key="i"
                class="p-2.5 rounded-lg border border-gray-200 bg-gray-50/50 flex items-center justify-between"
              >
                <div>
                  <p class="font-semibold text-gray-800">{{ od.direccion }}</p>
                  <p v-if="od.telefono" class="text-gray-500 font-mono text-[11px]">Tel: {{ od.telefono }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
          <Button
            size="sm"
            class="bg-[#20233B] hover:bg-[#2B2F4C] text-white text-xs px-4 rounded-lg"
            @click="showVerModal = false"
          >
            Cerrar
          </Button>
        </div>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL: CONFIRMAR ELIMINAR PROVEEDOR                               -->
    <!-- ================================================================= -->
    <div
      v-if="showDeleteModal && proveedorAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden p-6 text-center space-y-4 animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
          <TriangleAlert class="w-6 h-6 stroke-[2]" />
        </div>
        <div>
          <h3 class="font-bold text-base text-gray-900">¿Eliminar Proveedor?</h3>
          <p class="text-xs text-gray-500 mt-1 leading-relaxed">
            ¿Está seguro de eliminar a <strong class="text-gray-800">{{ proveedorAEliminar.proveedor }}</strong>? Esta acción no se puede deshacer.
          </p>
        </div>
        <div class="flex items-center justify-center gap-3 pt-2">
          <Button
            variant="outline"
            size="sm"
            class="rounded-lg text-xs px-4"
            @click="showDeleteModal = false"
          >
            Cancelar
          </Button>
          <Button
            size="sm"
            class="bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs px-4"
            @click="confirmDelete"
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
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
