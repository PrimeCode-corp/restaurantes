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
  UserCheck,
  Eye,
  Pencil,
  Trash2,
  X,
  Building2,
  TriangleAlert,
  CreditCard,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS LOCALES DE EMPRESAS
// -------------------------------------------------------------------
const empresas = ref([
  {
    id: 1,
    razonSocial: 'RIQUISIMOS PIZZITA S.A.C.',
    nDocumento: '20601234567',
    tipo: 'RUC',
    nombreComercial: 'Riquisimos Pizzita',
    telefono: '987 654 321',
    direccion: 'Av. La Marina 1234, Lima',
    tipoSociedad: 'S.A.C.',
    logo: null,
    cuentasBancarias: [
      { id: 1, banco: 'BCP', numero: '191-12345678-0-01', cci: '00219100123456780111', mostrarImpresion: true },
      { id: 2, banco: 'Interbank', numero: '200-0987654-3', cci: '00320000987654300', mostrarImpresion: false },
    ],
  },
  {
    id: 2,
    razonSocial: 'MISHKY INNOVATIONS S.R.L.',
    nDocumento: '20709876543',
    tipo: 'RUC',
    nombreComercial: 'Mishky POS',
    telefono: '944 111 222',
    direccion: 'Jr. Los Pinos 567, Miraflores',
    tipoSociedad: 'S.R.L.',
    logo: null,
    cuentasBancarias: [],
  },
])

// -------------------------------------------------------------------
// BÚSQUEDA / PAGINACIÓN
// -------------------------------------------------------------------
const searchQuery = ref('')
const rowsPerPage = ref(10)
const currentPage = ref(1)

const filtered = computed(() => {
  const q = searchQuery.value.toLowerCase()
  return empresas.value.filter(
    (e) =>
      e.razonSocial.toLowerCase().includes(q) ||
      e.nDocumento.includes(q) ||
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
// MODAL: NUEVA / EDITAR EMPRESA
// -------------------------------------------------------------------
const showEmpresaModal = ref(false)
const isEditMode = ref(false)
const empresaForm = ref({
  id: null,
  nDocumento: '',
  tipo: 'RUC',
  razonSocial: '',
  telefono: '',
  nombreComercial: '',
  tipoSociedad: 'S.A.C.',
  direccion: '',
  logo: null,
})

const tipoDocOpciones = ['RUC', 'DNI', 'CE']
const tipoSociedadOpciones = ['S.A.C.', 'S.R.L.', 'S.A.', 'E.I.R.L.', 'Natural']

function openNuevaEmpresa() {
  isEditMode.value = false
  empresaForm.value = { id: null, nDocumento: '', tipo: 'RUC', razonSocial: '', telefono: '', nombreComercial: '', tipoSociedad: 'S.A.C.', direccion: '', logo: null }
  showEmpresaModal.value = true
}

function openEditarEmpresa(empresa) {
  isEditMode.value = true
  empresaForm.value = { ...empresa }
  showEmpresaModal.value = true
}

function guardarEmpresa() {
  if (!empresaForm.value.razonSocial.trim() || !empresaForm.value.nDocumento.trim()) return
  if (isEditMode.value) {
    const idx = empresas.value.findIndex((e) => e.id === empresaForm.value.id)
    if (idx !== -1) empresas.value[idx] = { ...empresas.value[idx], ...empresaForm.value }
  } else {
    const newId = Math.max(0, ...empresas.value.map((e) => e.id)) + 1
    empresas.value.push({ ...empresaForm.value, id: newId, cuentasBancarias: [] })
  }
  showEmpresaModal.value = false
}

// -------------------------------------------------------------------
// MODAL: VER / CUENTAS BANCARIAS
// -------------------------------------------------------------------
const showCuentasModal = ref(false)
const empresaSeleccionada = ref(null)
const showNuevaCuentaModal = ref(false)
const isEditCuentaMode = ref(false)
const cuentaForm = ref({ id: null, banco: '', numero: '', cci: '', mostrarImpresion: false })

const bancosOpciones = ['BCP', 'Interbank', 'BBVA', 'Scotiabank', 'BanBif', 'Pichincha', 'Mibanco', 'GNB']

function openVerEmpresa(empresa) {
  empresaSeleccionada.value = empresa
  showCuentasModal.value = true
}

function openNuevaCuenta() {
  isEditCuentaMode.value = false
  cuentaForm.value = { id: null, banco: '', numero: '', cci: '', mostrarImpresion: false }
  showNuevaCuentaModal.value = true
}

function openEditarCuenta(cuenta) {
  isEditCuentaMode.value = true
  cuentaForm.value = { ...cuenta }
  showNuevaCuentaModal.value = true
}

function guardarCuenta() {
  if (!cuentaForm.value.banco || !cuentaForm.value.numero.trim()) return
  const emp = empresas.value.find((e) => e.id === empresaSeleccionada.value.id)
  if (!emp) return
  if (isEditCuentaMode.value) {
    const idx = emp.cuentasBancarias.findIndex((c) => c.id === cuentaForm.value.id)
    if (idx !== -1) emp.cuentasBancarias[idx] = { ...cuentaForm.value }
  } else {
    const newId = Math.max(0, ...emp.cuentasBancarias.map((c) => c.id), 0) + 1
    emp.cuentasBancarias.push({ ...cuentaForm.value, id: newId })
  }
  empresaSeleccionada.value = emp
  showNuevaCuentaModal.value = false
}

function eliminarCuenta(cuentaId) {
  const emp = empresas.value.find((e) => e.id === empresaSeleccionada.value.id)
  if (!emp) return
  emp.cuentasBancarias = emp.cuentasBancarias.filter((c) => c.id !== cuentaId)
  empresaSeleccionada.value = emp
}

// -------------------------------------------------------------------
// MODAL: ELIMINAR EMPRESA
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const empresaAEliminar = ref(null)

function openEliminar(empresa) {
  empresaAEliminar.value = empresa
  showDeleteModal.value = true
}

function confirmarEliminar() {
  empresas.value = empresas.value.filter((e) => e.id !== empresaAEliminar.value.id)
  showDeleteModal.value = false
  empresaAEliminar.value = null
}
</script>

<template>
  <AdminLayout active-nav="admin-sistema" active-submenu="empresa">
    <!-- TÍTULO -->
    <div>
      <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">Empresas</h1>
    </div>

    <!-- TARJETA PRINCIPAL -->
    <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-4 sm:p-6 flex flex-col justify-between min-h-0 sm:min-h-0 sm:min-h-[480px]">
      <div>
        <!-- BARRA SUPERIOR -->
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
          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <Button
              variant="outline"
              size="sm"
              class="flex-1 sm:flex-initial bg-white hover:bg-gray-50 text-gray-700 border-gray-300 rounded-lg px-3 sm:px-3.5 py-1.5 h-9 text-xs font-semibold tracking-wider flex items-center justify-center gap-2 shadow-xs transition-all"
            >
              <UserCheck class="w-4 h-4 text-gray-600" /><span>MIS CUENTAS</span>
            </Button>
            <Button
              size="sm"
              class="flex-1 sm:flex-initial bg-[#20233B] hover:bg-[#2B2F4C] text-white rounded-lg px-3.5 sm:px-4 py-1.5 h-9 text-xs font-semibold tracking-wider flex items-center justify-center gap-1.5 shadow-sm transition-all"
              @click="openNuevaEmpresa"
            >
              <Plus class="w-4 h-4 stroke-[2.5]" /><span>NUEVO</span>
            </Button>
          </div>
        </div>

        <!-- TABLA -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <Table>
            <TableHeader class="bg-[#232738] text-white">
              <TableRow class="border-none hover:bg-transparent">
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">Razón Social</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/5">N° Documento</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/4">Dirección</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-1/6">Teléfono</TableHead>
                <TableHead class="text-xs font-semibold text-white h-9 px-4 py-2 text-center w-28">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="empresa in paginated"
                :key="empresa.id"
                class="hover:bg-gray-50/70 border-b border-gray-200"
              >
                <TableCell class="py-3 px-4 text-xs text-gray-800 font-medium">{{ empresa.razonSocial }}</TableCell>
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center">{{ empresa.nDocumento }}</TableCell>
                <TableCell class="py-3 px-4 text-xs text-gray-600">{{ empresa.direccion }}</TableCell>
                <TableCell class="py-3 px-4 text-xs text-gray-600 text-center">{{ empresa.telefono }}</TableCell>
                <TableCell class="py-3 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Ver / Cuentas Bancarias -->
                    <button
                      class="w-6 h-6 rounded border border-blue-300 bg-blue-50/80 hover:bg-blue-100 text-blue-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Ver / Cuentas Bancarias"
                      @click="openVerEmpresa(empresa)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>
                    <!-- Editar -->
                    <button
                      class="w-6 h-6 rounded border border-emerald-300 bg-emerald-50/80 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Editar"
                      @click="openEditarEmpresa(empresa)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>
                    <!-- Eliminar -->
                    <button
                      class="w-6 h-6 rounded border border-red-300 bg-red-50/80 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shadow-xs"
                      title="Eliminar"
                      @click="openEliminar(empresa)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>
              <!-- Estado vacío -->
              <TableRow v-if="paginated.length === 0">
                <TableCell colspan="5" class="py-10 text-center text-xs text-gray-400">
                  No se encontraron empresas
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
         MODAL 1: NUEVA / EDITAR EMPRESA
         ==================================================================== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showEmpresaModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/55 backdrop-blur-xs" @click="showEmpresaModal = false" />
          <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#1A1D27] px-5 py-3.5 flex items-center justify-between shrink-0">
              <div class="flex items-center gap-2.5">
                <Building2 class="w-4 h-4 text-[#F0302E]" />
                <span class="text-sm font-bold text-white tracking-wide">
                  Admin Empresa — {{ isEditMode ? 'Editar' : 'Nuevo' }}
                </span>
              </div>
              <button class="text-gray-400 hover:text-white transition-colors rounded-md p-0.5 hover:bg-white/10" @click="showEmpresaModal = false">
                <X class="w-4 h-4" />
              </button>
            </div>

            <div class="p-5 space-y-4 overflow-y-auto max-h-[75vh]">
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2 space-y-1">
                  <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">N° de Documento</label>
                  <Input v-model="empresaForm.nDocumento" placeholder="20601234567" class="h-8 text-xs border-gray-300" />
                </div>
                <div class="space-y-1">
                  <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Tipo</label>
                  <select v-model="empresaForm.tipo" class="w-full h-8 rounded-md border border-gray-300 bg-white px-2 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option v-for="t in tipoDocOpciones" :key="t" :value="t">{{ t }}</option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                  <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Razón Social</label>
                  <Input v-model="empresaForm.razonSocial" placeholder="Mi Empresa S.A.C." class="h-8 text-xs border-gray-300" />
                </div>
                <div class="space-y-1">
                  <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Teléfono</label>
                  <Input v-model="empresaForm.telefono" placeholder="987 654 321" class="h-8 text-xs border-gray-300" />
                </div>
              </div>

              <div class="space-y-1">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Nombre Comercial (Marca)</label>
                <Input v-model="empresaForm.nombreComercial" placeholder="Nombre de marca" class="h-8 text-xs border-gray-300" />
              </div>

              <div class="space-y-1">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Logo</label>
                <div class="border-2 border-dashed border-gray-200 rounded-lg h-20 flex items-center justify-center text-xs text-gray-400 cursor-pointer hover:border-gray-300 transition-colors bg-gray-50/60">
                  <span>Haz clic para subir imagen</span>
                </div>
              </div>

              <div class="space-y-1">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Tipo Sociedad</label>
                <select v-model="empresaForm.tipoSociedad" class="w-full h-8 rounded-md border border-gray-300 bg-white px-2 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400">
                  <option v-for="ts in tipoSociedadOpciones" :key="ts" :value="ts">{{ ts }}</option>
                </select>
              </div>

              <div class="space-y-1">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Dirección</label>
                <Input v-model="empresaForm.direccion" placeholder="Av. Ejemplo 123, Ciudad" class="h-8 text-xs border-gray-300" />
              </div>
            </div>

            <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-200 flex justify-end gap-2.5 shrink-0">
              <Button variant="outline" size="sm" class="h-8 px-4 text-xs border-gray-300 text-gray-600 hover:bg-gray-100 rounded-lg" @click="showEmpresaModal = false">
                Cancelar
              </Button>
              <Button size="sm" class="h-8 px-5 text-xs bg-[#F0302E] hover:bg-[#D02724] text-white rounded-lg font-semibold shadow-sm" @click="guardarEmpresa">
                {{ isEditMode ? 'Guardar Cambios' : 'Crear Empresa' }}
              </Button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ====================================================================
         MODAL 2: CUENTAS BANCARIAS (VER)
         ==================================================================== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showCuentasModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/55 backdrop-blur-xs" @click="showCuentasModal = false" />
          <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#1A1D27] px-5 py-3.5 flex items-center justify-between shrink-0">
              <div class="flex items-center gap-2.5">
                <CreditCard class="w-4 h-4 text-[#F0302E]" />
                <div>
                  <span class="text-sm font-bold text-white tracking-wide">Admin Cuenta Bancaria</span>
                  <p v-if="empresaSeleccionada" class="text-[10px] text-gray-400 leading-none mt-0.5">{{ empresaSeleccionada.razonSocial }}</p>
                </div>
              </div>
              <button class="text-gray-400 hover:text-white transition-colors rounded-md p-0.5 hover:bg-white/10" @click="showCuentasModal = false">
                <X class="w-4 h-4" />
              </button>
            </div>

            <div class="p-5 overflow-y-auto max-h-[70vh]">
              <div class="flex justify-end mb-3">
                <Button size="sm" class="h-8 px-4 text-xs bg-[#20233B] hover:bg-[#2B2F4C] text-white rounded-lg flex items-center gap-1.5" @click="openNuevaCuenta">
                  <Plus class="w-3.5 h-3.5 stroke-[2.5]" /> NUEVO
                </Button>
              </div>

              <div class="overflow-x-auto rounded-lg border border-gray-200">
                <Table>
                  <TableHeader class="bg-[#232738]">
                    <TableRow class="border-none hover:bg-transparent">
                      <TableHead class="text-[11px] font-semibold text-white h-8 px-3 py-1.5">Banco</TableHead>
                      <TableHead class="text-[11px] font-semibold text-white h-8 px-3 py-1.5">Número de Cuenta</TableHead>
                      <TableHead class="text-[11px] font-semibold text-white h-8 px-3 py-1.5">CCI</TableHead>
                      <TableHead class="text-[11px] font-semibold text-white h-8 px-3 py-1.5 text-center w-20">Impresión</TableHead>
                      <TableHead class="text-[11px] font-semibold text-white h-8 px-3 py-1.5 text-center w-20">Acciones</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    <TableRow
                      v-for="cuenta in empresaSeleccionada?.cuentasBancarias"
                      :key="cuenta.id"
                      class="hover:bg-gray-50/70 border-b border-gray-200"
                    >
                      <TableCell class="py-2.5 px-3 text-xs font-medium text-gray-800">{{ cuenta.banco }}</TableCell>
                      <TableCell class="py-2.5 px-3 text-xs text-gray-600 font-mono">{{ cuenta.numero }}</TableCell>
                      <TableCell class="py-2.5 px-3 text-xs text-gray-500 font-mono">{{ cuenta.cci }}</TableCell>
                      <TableCell class="py-2.5 px-3 text-center">
                        <span :class="cuenta.mostrarImpresion ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'" class="text-[10px] px-1.5 py-0.5 rounded-full font-medium">
                          {{ cuenta.mostrarImpresion ? 'Sí' : 'No' }}
                        </span>
                      </TableCell>
                      <TableCell class="py-2.5 px-3">
                        <div class="flex items-center justify-center gap-1.5">
                          <button
                            class="w-5 h-5 rounded border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors"
                            title="Editar"
                            @click="openEditarCuenta(cuenta)"
                          >
                            <Pencil class="w-3 h-3" />
                          </button>
                          <button
                            class="w-5 h-5 rounded border border-red-300 bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors"
                            title="Eliminar"
                            @click="eliminarCuenta(cuenta.id)"
                          >
                            <Trash2 class="w-3 h-3" />
                          </button>
                        </div>
                      </TableCell>
                    </TableRow>
                    <TableRow v-if="!empresaSeleccionada?.cuentasBancarias?.length">
                      <TableCell colspan="5" class="py-8 text-center text-xs text-gray-400">Sin cuentas bancarias registradas</TableCell>
                    </TableRow>
                  </TableBody>
                </Table>
              </div>
            </div>

            <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end shrink-0">
              <Button variant="outline" size="sm" class="h-8 px-4 text-xs border-gray-300 text-gray-600 hover:bg-gray-100 rounded-lg" @click="showCuentasModal = false">
                Cerrar
              </Button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ====================================================================
         MODAL 3: NUEVA / EDITAR CUENTA BANCARIA
         ==================================================================== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showNuevaCuentaModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50 backdrop-blur-xs" @click="showNuevaCuentaModal = false" />
          <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#1A1D27] px-5 py-3.5 flex items-center justify-between shrink-0">
              <div class="flex items-center gap-2.5">
                <CreditCard class="w-4 h-4 text-[#F0302E]" />
                <span class="text-sm font-bold text-white tracking-wide">
                  Nueva Bancaria — {{ isEditCuentaMode ? 'Editar' : 'Nuevo' }}
                </span>
              </div>
              <button class="text-gray-400 hover:text-white transition-colors rounded-md p-0.5 hover:bg-white/10" @click="showNuevaCuentaModal = false">
                <X class="w-4 h-4" />
              </button>
            </div>

            <div class="p-5 space-y-4">
              <div class="space-y-1">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Banco</label>
                <select v-model="cuentaForm.banco" class="w-full h-8 rounded-md border border-gray-300 bg-white px-2 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400">
                  <option value="">Seleccionar banco...</option>
                  <option v-for="b in bancosOpciones" :key="b" :value="b">{{ b }}</option>
                </select>
              </div>

              <div class="space-y-1">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Número</label>
                <Input v-model="cuentaForm.numero" placeholder="191-12345678-0-01" class="h-8 text-xs border-gray-300 font-mono" />
              </div>

              <div class="space-y-1">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">CCI</label>
                <Input v-model="cuentaForm.cci" placeholder="00219100123456780111" class="h-8 text-xs border-gray-300 font-mono" />
              </div>

              <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50/60 px-4 py-3">
                <div>
                  <p class="text-xs font-semibold text-gray-700">Mostrar Impresión Pdf Venta</p>
                  <p class="text-[10px] text-gray-400 mt-0.5">Aparece en comprobante de venta</p>
                </div>
                <Switch v-model:checked="cuentaForm.mostrarImpresion" />
              </div>
            </div>

            <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-200 flex justify-end gap-2.5 shrink-0">
              <Button variant="outline" size="sm" class="h-8 px-4 text-xs border-gray-300 text-gray-600 hover:bg-gray-100 rounded-lg" @click="showNuevaCuentaModal = false">
                Cancelar
              </Button>
              <Button size="sm" class="h-8 px-5 text-xs bg-[#F0302E] hover:bg-[#D02724] text-white rounded-lg font-semibold shadow-sm" @click="guardarCuenta">
                {{ isEditCuentaMode ? 'Guardar' : 'Crear Cuenta' }}
              </Button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- ====================================================================
         MODAL 4: CONFIRMAR ELIMINACIÓN
         ==================================================================== -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/55 backdrop-blur-xs" @click="showDeleteModal = false" />
          <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#1A1D27] px-5 py-3.5 flex items-center justify-between shrink-0">
              <div class="flex items-center gap-2.5">
                <TriangleAlert class="w-4 h-4 text-[#F0302E]" />
                <span class="text-sm font-bold text-white">Eliminar Empresa</span>
              </div>
              <button class="text-gray-400 hover:text-white transition-colors rounded-md p-0.5 hover:bg-white/10" @click="showDeleteModal = false">
                <X class="w-4 h-4" />
              </button>
            </div>

            <div class="p-6 flex flex-col items-center text-center gap-3">
              <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                <Trash2 class="w-6 h-6 text-[#F0302E]" />
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-800">¿Eliminar esta empresa?</p>
                <p class="text-xs text-gray-500 mt-1">
                  <span class="font-semibold text-gray-700">{{ empresaAEliminar?.razonSocial }}</span>
                  <br />Esta acción no se puede deshacer.
                </p>
              </div>
            </div>

            <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-200 flex justify-end gap-2.5 shrink-0">
              <Button variant="outline" size="sm" class="h-8 px-4 text-xs border-gray-300 text-gray-600 hover:bg-gray-100 rounded-lg" @click="showDeleteModal = false">
                Cancelar
              </Button>
              <Button size="sm" class="h-8 px-5 text-xs bg-[#F0302E] hover:bg-[#D02724] text-white rounded-lg font-semibold shadow-sm" @click="confirmarEliminar">
                Sí, eliminar
              </Button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </AdminLayout>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.18s ease;
}
.modal-fade-enter-active .relative,
.modal-fade-leave-active .relative {
  transition: transform 0.18s ease, opacity 0.18s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
.modal-fade-enter-from .relative {
  transform: scale(0.96) translateY(-8px);
  opacity: 0;
}
</style>
