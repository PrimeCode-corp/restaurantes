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
  Calendar as CalendarIcon,
  ArrowLeft,
  Save,
  Check,
  CheckCircle2,
  TriangleAlert,
  ChevronDown,
  RotateCw,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// MODO VISTA: 'lista' | 'administrar'
// -------------------------------------------------------------------
const viewMode = ref('lista')

// -------------------------------------------------------------------
// OPCIONES SELECTORES
// -------------------------------------------------------------------
const tiposPago = ['CONTADO', 'CRÉDITO']
const comprobantes = ['BOLETA DE VENTA', 'FACTURA', 'TICKET', 'NOTA DE VENTA']
const mediosPago = ['CONTADO', 'TARJETA DE CRÉDITO', 'TRANSFERENCIA', 'YAPE / PLIN', 'EFECTIVO']
const almacenes = ['CAJA PRINCIPAL', 'ALMACEN GENERAL', 'COCINA', 'BARRA']

// -------------------------------------------------------------------
// CATÁLOGO DE PROVEEDORES (Modal Proveedor)
// -------------------------------------------------------------------
const catalogoProveedores = ref([
  {
    codProveedor: 'P00003',
    proveedor: 'ABARROTES EL MOSHAQUITO S.A.C.',
    direccion: 'JR. JIRON 123',
    numDocumento: '01046282837',
    tipoDoc: 'RUC',
    telefono: '000000000',
  },
  {
    codProveedor: 'P00004',
    proveedor: 'DISTRIBUIDORA GASTRONOMICA S.R.L.',
    direccion: 'AV. CENTRAL 450',
    numDocumento: '20489563211',
    tipoDoc: 'RUC',
    telefono: '942851234',
  },
  {
    codProveedor: 'P00005',
    proveedor: 'AVASTESEDORES PRINCIPALES',
    direccion: 'CALLE COMERCIO 789',
    numDocumento: '77777777',
    tipoDoc: 'DNI',
    telefono: '987654321',
  },
  {
    codProveedor: 'P00006',
    proveedor: 'COMERCIALIZADORA DEL ORIENTE S.A.',
    direccion: 'JR. SAN MARTIN 890',
    numDocumento: '10458963214',
    tipoDoc: 'RUC',
    telefono: '978546123',
  },
])

// -------------------------------------------------------------------
// CATÁLOGO DE INSUMOS (Modal Insumos)
// -------------------------------------------------------------------
const catalogoInsumos = ref([
  { id: 1, descripcion: 'MASA MADRE', tipoInsumo: 'PREPARADO', um: 'KG', precioUnitario: 12.00 },
  { id: 2, descripcion: 'CECINA', tipoInsumo: 'PREPARADO', um: 'UNIDAD', precioUnitario: 3.00 },
  { id: 3, descripcion: 'ARROZ COSTEÑO', tipoInsumo: 'COMPRADO', um: 'KG', precioUnitario: 190.00 },
  { id: 4, descripcion: 'VINAGRE BLANCO 250 ML', tipoInsumo: 'COMPRADO', um: 'BOTELLA', precioUnitario: 4.00 },
  { id: 5, descripcion: 'QUESO MOZZARELLA', tipoInsumo: 'COMPRADO', um: 'KG', precioUnitario: 28.50 },
  { id: 6, descripcion: 'SALSA DE TOMATE PIZZERA', tipoInsumo: 'PREPARADO', um: 'LITRO', precioUnitario: 15.00 },
  { id: 7, descripcion: 'HARINA DE TRIGO ESPECIAL', tipoInsumo: 'COMPRADO', um: 'SACO', precioUnitario: 145.00 },
  { id: 8, descripcion: 'ACEITUNAS NEGRAS', tipoInsumo: 'COMPRADO', um: 'KG', precioUnitario: 14.00 },
])

// -------------------------------------------------------------------
// LISTADO PRINCIPAL DE COMPRAS (Captura: REALIZAR COMPRA.png)
// -------------------------------------------------------------------
const compras = ref([
  {
    id: 1,
    fecha: '00/00/0000',
    numDocumento: '77777777',
    tipoDocumento: 'DNI',
    tipoPago: 'CONTADO',
    proveedor: 'AVASTESEDORES PRINCIPALES',
    codProveedor: 'P00004',
    total: 0.00,
    observacion: 'COMPRA PRUEBA',
    comprobante: 'BOLETA DE VENTA',
    medioPago: 'CONTADO',
    almacen: 'CAJA PRINCIPAL',
    subtotal: 0.00,
    igv: 0.00,
    items: [],
  },
  {
    id: 2,
    fecha: '15/10/2026',
    numDocumento: '01046282837',
    tipoDocumento: 'RUC',
    tipoPago: 'CONTADO',
    proveedor: 'ABARROTES EL MOSHAQUITO S.A.C.',
    codProveedor: 'P00004',
    total: 289.80,
    observacion: 'COMPRA PRUEBA',
    comprobante: 'BOLETA DE VENTA',
    medioPago: 'CONTADO',
    almacen: 'CAJA PRINCIPAL',
    subtotal: 289.80,
    igv: 0.00,
    items: [
      { id: 1, descripcion: 'CECINA', cantidad: 25, um: 'UNIDAD', precioU: 3.00, totalPrecio: 75.00 },
      { id: 2, descripcion: 'ARROZ COSTEÑO', cantidad: 1, um: 'UND', precioU: 190.80, totalPrecio: 190.80 },
      { id: 3, descripcion: 'VINAGRE BLANCO 250 ML', cantidad: 6, um: 'UND', precioU: 4.00, totalPrecio: 24.00 },
    ],
  },
])

// Buscador y filtros de tabla principal
const searchQuery = ref('')
const selectedPeriodo = ref('TODO EL PERIODO')
const currentPage = ref(1)
const rowsPerPage = ref(10)

const periodos = [
  'TODO EL PERIODO',
  'HOY',
  'ESTA SEMANA',
  'ESTE MES',
  'ESTE AÑO',
]

const filteredCompras = computed(() => {
  let list = compras.value
  if (searchQuery.value.trim() !== '') {
    const q = searchQuery.value.toLowerCase().trim()
    list = list.filter(
      (c) =>
        c.numDocumento?.toLowerCase().includes(q) ||
        c.proveedor?.toLowerCase().includes(q) ||
        c.tipoDocumento?.toLowerCase().includes(q) ||
        c.tipoPago?.toLowerCase().includes(q)
    )
  }
  return list
})

const totalPages = computed(() =>
  Math.max(1, Math.ceil(filteredCompras.value.length / rowsPerPage.value))
)

const paginatedCompras = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value
  return filteredCompras.value.slice(start, start + rowsPerPage.value)
})

// -------------------------------------------------------------------
// FORMULARIO: ADMINISTRAR COMPRA (Capturas: adm1, adm2, adm3, adm4)
// -------------------------------------------------------------------
const compraForm = ref({
  id: null,
  codProveedor: '',
  proveedor: '',
  tipoDocumento: '',
  numDocumento: '',
  fecha: '00/00/0000',
  observacion: '',
  tipoPago: 'CONTADO',
  comprobante: 'BOLETA DE VENTA',
  medioPago: 'CONTADO',
  almacen: 'CAJA PRINCIPAL',
  busquedaInsumoText: '',
  items: [],
  subtotal: 0.00,
  igv: 0.00,
  total: 0.00,
})

const compraPage = ref(1)
const compraRowsPerPage = ref(8)

const compraTotalPages = computed(() =>
  Math.max(1, Math.ceil((compraForm.value.items.length || 1) / compraRowsPerPage.value))
)

const paginatedCompraItems = computed(() => {
  const start = (compraPage.value - 1) * compraRowsPerPage.value
  return compraForm.value.items.slice(start, start + compraRowsPerPage.value)
})

const recalcularTotales = () => {
  let sub = 0
  compraForm.value.items.forEach((item) => {
    item.totalPrecio = Number((Number(item.cantidad || 0) * Number(item.precioU || 0)).toFixed(2))
    sub += item.totalPrecio
  })
  compraForm.value.subtotal = Number(sub.toFixed(2))
  compraForm.value.total = Number((compraForm.value.subtotal + Number(compraForm.value.igv || 0)).toFixed(2))
}

const limpiarFormulario = () => {
  compraForm.value = {
    id: null,
    codProveedor: '',
    proveedor: '',
    tipoDocumento: '',
    numDocumento: '',
    fecha: '00/00/0000',
    observacion: '',
    tipoPago: 'CONTADO',
    comprobante: 'BOLETA DE VENTA',
    medioPago: 'CONTADO',
    almacen: 'CAJA PRINCIPAL',
    busquedaInsumoText: '',
    items: [],
    subtotal: 0.00,
    igv: 0.00,
    total: 0.00,
  }
  compraPage.value = 1
}

const irANuevo = () => {
  limpiarFormulario()
  viewMode.value = 'administrar'
}

const volverALista = () => {
  viewMode.value = 'lista'
}

const editarCompra = (compra) => {
  compraForm.value = {
    id: compra.id,
    codProveedor: compra.codProveedor || '',
    proveedor: compra.proveedor || '',
    tipoDocumento: compra.tipoDocumento || '',
    numDocumento: compra.numDocumento || '',
    fecha: compra.fecha || '00/00/0000',
    observacion: compra.observacion || '',
    tipoPago: compra.tipoPago || 'CONTADO',
    comprobante: compra.comprobante || 'BOLETA DE VENTA',
    medioPago: compra.medioPago || 'CONTADO',
    almacen: compra.almacen || 'CAJA PRINCIPAL',
    busquedaInsumoText: '',
    items: JSON.parse(JSON.stringify(compra.items || [])),
    subtotal: compra.subtotal || 0.00,
    igv: compra.igv || 0.00,
    total: compra.total || 0.00,
  }
  compraPage.value = 1
  viewMode.value = 'administrar'
}

const eliminarItemCompra = (index) => {
  compraForm.value.items.splice(index, 1)
  recalcularTotales()
}

const vaciarItemsCompra = () => {
  compraForm.value.items = []
  recalcularTotales()
}

const guardarCompra = () => {
  recalcularTotales()
  if (compraForm.value.id) {
    const idx = compras.value.findIndex((c) => c.id === compraForm.value.id)
    if (idx !== -1) {
      compras.value[idx] = {
        ...compras.value[idx],
        ...compraForm.value,
        total: compraForm.value.total,
      }
    }
  } else {
    const nuevoId = compras.value.length ? Math.max(...compras.value.map((c) => c.id)) + 1 : 1
    compras.value.unshift({
      ...compraForm.value,
      id: nuevoId,
      total: compraForm.value.total,
    })
  }
  viewMode.value = 'lista'
}

// -------------------------------------------------------------------
// MODAL 1: PROVEEDORES (Captura: MODALES.png - arriba)
// -------------------------------------------------------------------
const showModalProveedores = ref(false)
const searchProveedorModal = ref('')
const proveedorSeleccionadoTemp = ref(null)
const proveedorPage = ref(1)
const proveedorRowsPerPage = ref(8)

const filteredProveedores = computed(() => {
  let list = catalogoProveedores.value
  if (searchProveedorModal.value.trim() !== '') {
    const q = searchProveedorModal.value.toLowerCase().trim()
    list = list.filter(
      (p) =>
        p.proveedor.toLowerCase().includes(q) ||
        p.codProveedor.toLowerCase().includes(q) ||
        p.numDocumento.includes(q)
    )
  }
  return list
})

const totalPagesProveedores = computed(() =>
  Math.max(1, Math.ceil(filteredProveedores.value.length / proveedorRowsPerPage.value))
)

const paginatedProveedores = computed(() => {
  const start = (proveedorPage.value - 1) * proveedorRowsPerPage.value
  return filteredProveedores.value.slice(start, start + proveedorRowsPerPage.value)
})

const openModalProveedores = () => {
  searchProveedorModal.value = ''
  proveedorSeleccionadoTemp.value = null
  showModalProveedores.value = true
}

const seleccionarProveedorFila = (p) => {
  proveedorSeleccionadoTemp.value = p
}

const aceptarProveedorModal = () => {
  if (proveedorSeleccionadoTemp.value) {
    compraForm.value.codProveedor = proveedorSeleccionadoTemp.value.codProveedor
    compraForm.value.proveedor = proveedorSeleccionadoTemp.value.proveedor
    compraForm.value.tipoDocumento = proveedorSeleccionadoTemp.value.tipoDoc
    compraForm.value.numDocumento = proveedorSeleccionadoTemp.value.numDocumento
  }
  showModalProveedores.value = false
}

// -------------------------------------------------------------------
// MODAL 2: LISTA DE INSUMOS (Captura: MODALES.png - abajo)
// -------------------------------------------------------------------
const showModalInsumos = ref(false)
const searchInsumoModal = ref('')
const insumosSeleccionadosIds = ref([])
const insumoPage = ref(1)
const insumoRowsPerPage = ref(10)

const filteredInsumosCatalogo = computed(() => {
  let list = catalogoInsumos.value
  if (searchInsumoModal.value.trim() !== '') {
    const q = searchInsumoModal.value.toLowerCase().trim()
    list = list.filter(
      (i) =>
        i.descripcion.toLowerCase().includes(q) ||
        i.tipoInsumo.toLowerCase().includes(q)
    )
  }
  return list
})

const totalPagesInsumos = computed(() =>
  Math.max(1, Math.ceil(filteredInsumosCatalogo.value.length / insumoRowsPerPage.value))
)

const paginatedInsumos = computed(() => {
  const start = (insumoPage.value - 1) * insumoRowsPerPage.value
  return filteredInsumosCatalogo.value.slice(start, start + insumoRowsPerPage.value)
})

const openModalInsumos = () => {
  searchInsumoModal.value = ''
  insumosSeleccionadosIds.value = []
  showModalInsumos.value = true
}

const toggleSeleccionInsumo = (insumo) => {
  const idx = insumosSeleccionadosIds.value.findIndex((id) => id === insumo.id)
  if (idx !== -1) {
    insumosSeleccionadosIds.value.splice(idx, 1)
  } else {
    insumosSeleccionadosIds.value.push(insumo.id)
  }
}

const isInsumoSeleccionado = (id) => insumosSeleccionadosIds.value.includes(id)

const aceptarInsumosModal = () => {
  const seleccionados = catalogoInsumos.value.filter((i) =>
    insumosSeleccionadosIds.value.includes(i.id)
  )

  // Actualiza el texto de búsqueda con los insumos seleccionados
  if (seleccionados.length > 0) {
    compraForm.value.busquedaInsumoText = seleccionados.map((s) => s.descripcion).join(', ')

    // Añade a la tabla de compra los que no estén repetidos
    seleccionados.forEach((ins) => {
      const existe = compraForm.value.items.some((it) => it.id === ins.id)
      if (!existe) {
        compraForm.value.items.push({
          id: ins.id,
          descripcion: ins.descripcion,
          cantidad: 1,
          um: ins.um || 'UNIDAD',
          precioU: ins.precioUnitario || 0.00,
          totalPrecio: ins.precioUnitario || 0.00,
        })
      }
    })
  }
  recalcularTotales()
  showModalInsumos.value = false
}

// -------------------------------------------------------------------
// MODAL 3: VER COMPRA REALIZADA (Captura: MDOAL1.png)
// -------------------------------------------------------------------
const showVerModal = ref(false)
const compraSeleccionadaVer = ref(null)

const openVerModal = (compra) => {
  compraSeleccionadaVer.value = compra
  showVerModal.value = true
}

// -------------------------------------------------------------------
// MODAL 4: ELIMINAR COMPRA
// -------------------------------------------------------------------
const showDeleteModal = ref(false)
const compraAEliminar = ref(null)

const openDeleteModal = (compra) => {
  compraAEliminar.value = compra
  showDeleteModal.value = true
}

const confirmarEliminar = () => {
  if (compraAEliminar.value) {
    compras.value = compras.value.filter((c) => c.id !== compraAEliminar.value.id)
  }
  showDeleteModal.value = false
  compraAEliminar.value = null
}

const formatMoney = (val) => Number(val || 0).toFixed(2)
</script>

<template>
  <AdminLayout active-nav="compras" active-submenu="realizar_compra">
    <!-- =============================================================== -->
    <!-- VISTA 1: LISTA PRINCIPAL (Captura: REALIZAR COMPRA.png)         -->
    <!-- =============================================================== -->
    <div v-if="viewMode === 'lista'" class="space-y-4">
      <!-- Título Superior -->
      <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
        Realizar Compra
      </h1>

      <!-- Contenedor Principal Estilo Tarjeta con Sombra Suave -->
      <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <!-- Barra de Filtros Superior: Buscador + Todo el Periodo + Botón + NUEVO -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <div class="flex flex-1 items-center gap-3">
            <!-- Input Buscador -->
            <div class="relative flex-1 max-w-sm max-h-[90vh] flex flex-col">
              <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <Input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar..."
                class="pl-9 h-9 text-xs rounded-xl border-gray-200 bg-white shadow-2xs focus-visible:ring-1 focus-visible:ring-gray-300"
              />
            </div>

            <!-- Selector de Periodo con Icono de Calendario -->
            <div class="relative min-w-[200px]">
              <CalendarIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#3E55B3]" />
              <select
                v-model="selectedPeriodo"
                class="w-full h-9 pl-9 pr-8 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase tracking-tight cursor-pointer"
              >
                <option v-for="p in periodos" :key="p" :value="p">{{ p }}</option>
              </select>
              <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
            </div>
          </div>

          <!-- Botón + NUEVO (Azul marino idéntico al diseño) -->
          <Button
            type="button"
            class="bg-[#2E365C] hover:bg-[#232946] text-white text-xs font-semibold px-4 h-9 rounded-xl flex items-center gap-1.5 shadow-xs cursor-pointer transition-colors"
            @click="irANuevo"
          >
            <Plus class="w-4 h-4 stroke-[2.5]" />
            <span>NUEVO</span>
          </Button>
        </div>

        <!-- Tabla Principal de Compras -->
        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
          <Table>
            <TableHeader>
              <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Fecha
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  N° Documento
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Tipo Documento
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Tipo Pago
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Proveedor
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Total
                </TableHead>
                <TableHead class="text-white text-xs font-semibold h-11 text-center py-2 px-3">
                  Acciones
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <!-- Filas con Datos Reales -->
              <TableRow
                v-for="c in paginatedCompras"
                :key="'compra-' + c.id"
                class="hover:bg-gray-50/80 border-b border-gray-100 transition-colors"
              >
                <TableCell class="py-3 px-3 text-xs text-center text-gray-700">
                  {{ c.fecha }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center font-mono text-gray-700">
                  {{ c.numDocumento }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center font-semibold text-gray-700">
                  {{ c.tipoDocumento }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center text-gray-700 font-medium">
                  {{ c.tipoPago }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center text-gray-700 font-medium">
                  {{ c.proveedor }}
                </TableCell>
                <TableCell class="py-3 px-3 text-xs text-center font-bold text-gray-900 font-mono">
                  {{ formatMoney(c.total) }}
                </TableCell>
                <!-- Acciones: Ver (Azul), Editar (Verde), Eliminar (Rojo) -->
                <TableCell class="py-3 px-3 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#EBF3FC] hover:bg-[#D7E9FB] text-[#2B77D6] flex items-center justify-center transition-colors cursor-pointer"
                      title="Ver compra"
                      @click="openVerModal(c)"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </button>
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#EAF7EE] hover:bg-[#D4EEDC] text-[#34A853] flex items-center justify-center transition-colors cursor-pointer"
                      title="Editar compra"
                      @click="editarCompra(c)"
                    >
                      <Pencil class="w-3.5 h-3.5" />
                    </button>
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors cursor-pointer"
                      title="Eliminar compra"
                      @click="openDeleteModal(c)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </TableCell>
              </TableRow>

              <!-- Filas Skeleton de Relleno para replicar exactamente la imagen -->
              <TableRow
                v-for="i in Math.max(0, 10 - paginatedCompras.length)"
                :key="'skel-c-' + i"
                class="border-b border-gray-100 hover:bg-transparent"
              >
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="h-2.5 bg-gray-200/80 rounded-full w-14 mx-auto"></div>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="h-2.5 bg-gray-200/80 rounded-full w-16 mx-auto"></div>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="h-2.5 bg-gray-200/80 rounded-full w-12 mx-auto"></div>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="h-2.5 bg-gray-200/80 rounded-full w-16 mx-auto"></div>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="h-2.5 bg-gray-200/80 rounded-full w-32 mx-auto"></div>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="h-2.5 bg-gray-200/80 rounded-full w-10 mx-auto"></div>
                </TableCell>
                <TableCell class="py-3.5 px-3 text-center">
                  <div class="flex items-center justify-center gap-1.5 opacity-80">
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
            </TableBody>
          </Table>
        </div>

        <!-- Paginador Inferior idéntico -->
        <div class="pt-2 flex items-center justify-between">
          <DataTablePagination
            :current-page="currentPage"
            :total-pages="totalPages"
            :rows-per-page="rowsPerPage"
            :rows-per-page-options="[8, 10, 20]"
            @update:current-page="currentPage = $event"
            @update:rows-per-page="rowsPerPage = $event"
          />
          <span class="text-xs text-gray-400 font-medium">
            Total {{ filteredCompras.length }} registros
          </span>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- VISTA 2: ADMINISTRAR COMPRA (Capturas: adm1, adm2, adm3, adm4)   -->
    <!-- =============================================================== -->
    <div v-else-if="viewMode === 'administrar'" class="space-y-4">
      <!-- Título de la Sección -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Administrar Compra
        </h1>
        <Button
          variant="outline"
          size="sm"
          class="border-gray-300 text-gray-700 text-xs px-4 h-8 flex items-center gap-1.5 hover:bg-gray-50 rounded-lg cursor-pointer"
          @click="volverALista"
        >
          <ArrowLeft class="w-3.5 h-3.5" />
          <span>Volver al listado</span>
        </Button>
      </div>

      <!-- Contenedor General Formulario (adm1.png) -->
      <Card class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        <!-- Bloque Superior de Campos -->
        <div class="space-y-4">
          <!-- Fila 1: Cod. Proveedor, Fecha, Proveedor, Tipo Documento, Número Documento -->
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5">
            <!-- Cod. Proveedor con Lupa -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Cod. Proveedor:</label>
              <div class="relative flex items-center">
                <Input
                  v-model="compraForm.codProveedor"
                  type="text"
                  placeholder="Buscar..."
                  readonly
                  class="h-9 pr-8 text-xs rounded-xl border-gray-200 bg-white font-mono cursor-pointer shadow-2xs"
                  @click="openModalProveedores"
                />
                <button
                  type="button"
                  class="absolute right-2 text-[#3E55B3] hover:text-[#283875] p-1 cursor-pointer"
                  title="Buscar Proveedor"
                  @click="openModalProveedores"
                >
                  <Search class="w-4 h-4 stroke-[2.5]" />
                </button>
              </div>
            </div>

            <!-- Fecha -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Fecha:</label>
              <div class="relative flex items-center">
                <Input
                  v-model="compraForm.fecha"
                  type="text"
                  placeholder="Selecciona fecha"
                  class="h-9 pr-8 text-xs rounded-xl border-gray-200 bg-white font-mono text-gray-700 shadow-2xs"
                />
                <CalendarIcon class="absolute right-2.5 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Proveedor (Deshabilitado / Fondo Grisáceo) -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Proveedor:</label>
              <Input
                v-model="compraForm.proveedor"
                type="text"
                readonly
                placeholder="-"
                class="h-9 text-xs rounded-xl border-gray-200 bg-gray-100 text-gray-600 font-medium cursor-not-allowed uppercase"
              />
            </div>

            <!-- Tipo Documento (Deshabilitado / Fondo Grisáceo) -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Tipo Documento:</label>
              <Input
                v-model="compraForm.tipoDocumento"
                type="text"
                readonly
                placeholder="-"
                class="h-9 text-xs rounded-xl border-gray-200 bg-gray-100 text-gray-600 font-medium cursor-not-allowed uppercase"
              />
            </div>

            <!-- Número Documento (Deshabilitado / Fondo Grisáceo) -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Número Documento:</label>
              <Input
                v-model="compraForm.numDocumento"
                type="text"
                readonly
                placeholder="-"
                class="h-9 text-xs rounded-xl border-gray-200 bg-gray-100 text-gray-600 font-medium cursor-not-allowed font-mono"
              />
            </div>
          </div>

          <!-- Fila 2: Observación, Tipo Pago, Comprobante, Medio Pago, Almacén -->
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5 items-start">
            <!-- Observación (Textarea de 2 filas) -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Observación</label>
              <textarea
                v-model="compraForm.observacion"
                rows="2"
                placeholder="Escribe aquí"
                class="w-full text-xs p-2 rounded-xl border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-300 shadow-2xs resize-y"
              ></textarea>
            </div>

            <!-- Tipo Pago -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Tipo Pago</label>
              <div class="relative">
                <select
                  v-model="compraForm.tipoPago"
                  class="w-full h-9 px-3 pr-8 text-xs text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option value="" disabled>Selecciona</option>
                  <option v-for="t in tiposPago" :key="t" :value="t">{{ t }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Comprobante -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Comprobante:</label>
              <div class="relative">
                <select
                  v-model="compraForm.comprobante"
                  class="w-full h-9 px-3 pr-8 text-xs text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option value="" disabled>Selecciona</option>
                  <option v-for="c in comprobantes" :key="c" :value="c">{{ c }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Medio Pago -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Medio Pago:</label>
              <div class="relative">
                <select
                  v-model="compraForm.medioPago"
                  class="w-full h-9 px-3 pr-8 text-xs text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option value="" disabled>Selecciona</option>
                  <option v-for="m in mediosPago" :key="m" :value="m">{{ m }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <!-- Almacén -->
            <div class="space-y-1">
              <label class="block text-[11px] font-semibold text-gray-700">Almacén</label>
              <div class="relative">
                <select
                  v-model="compraForm.almacen"
                  class="w-full h-9 px-3 pr-8 text-xs text-gray-700 bg-white border border-gray-200 rounded-xl appearance-none focus:outline-none focus:ring-1 focus:ring-gray-300 uppercase shadow-2xs cursor-pointer"
                >
                  <option value="" disabled>Selecciona</option>
                  <option v-for="a in almacenes" :key="a" :value="a">{{ a }}</option>
                </select>
                <ChevronDown class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>
          </div>
        </div>

        <!-- Sección: Detalle de la Compra -->
        <div class="border border-gray-200 rounded-2xl p-5 space-y-4 bg-white">
          <h2 class="text-base font-bold text-gray-800 tracking-tight">
            Detalle de la Compra
          </h2>

          <!-- Barra de Búsqueda Insumo + Botón + AÑADIR -->
          <div class="flex items-center justify-between gap-4">
            <div class="relative flex-1 max-w-xl max-h-[90vh] flex flex-col">
              <Input
                v-model="compraForm.busquedaInsumoText"
                type="text"
                placeholder="Busca insumo a seleccionar...."
                readonly
                class="h-9 pr-9 text-xs rounded-xl border-gray-200 bg-white cursor-pointer shadow-2xs"
                @click="openModalInsumos"
              />
              <button
                type="button"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#3E55B3] hover:text-[#283875] p-1 cursor-pointer"
                title="Buscar insumos"
                @click="openModalInsumos"
              >
                <Search class="w-4 h-4 stroke-[2.5]" />
              </button>
            </div>

            <!-- Botón + AÑADIR (Verde brillante) -->
            <Button
              type="button"
              class="bg-[#2EB836] hover:bg-[#259b2d] text-white text-xs font-semibold px-4 h-9 rounded-xl flex items-center gap-1.5 shadow-xs cursor-pointer transition-colors"
              @click="openModalInsumos"
            >
              <Plus class="w-4 h-4 stroke-[3]" />
              <span>AÑADIR</span>
            </Button>
          </div>

          <!-- Tabla de Insumos Agregados -->
          <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <Table>
              <TableHeader>
                <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Descripción
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Cantidad
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    U. M.
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Precio U.
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Total Precio
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Acciones
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <!-- Filas Reales con Datos (adm4.png) -->
                <TableRow
                  v-for="(item, idx) in paginatedCompraItems"
                  :key="'item-' + item.id + '-' + idx"
                  class="border-b border-gray-100 hover:bg-gray-50/70 transition-colors"
                >
                  <TableCell class="py-2.5 px-3 text-xs text-left font-semibold text-gray-800">
                    {{ item.descripcion }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-center">
                    <Input
                      v-model.number="item.cantidad"
                      type="number"
                      min="1"
                      class="h-7 w-20 mx-auto text-xs text-center border-gray-200 rounded-lg shadow-2xs font-semibold"
                      @input="recalcularTotales"
                    />
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center text-gray-700 uppercase font-medium">
                    {{ item.um }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    <Input
                      v-model.number="item.precioU"
                      type="number"
                      step="0.01"
                      class="h-7 w-24 mx-auto text-xs text-center border-gray-200 rounded-lg shadow-2xs font-mono"
                      @input="recalcularTotales"
                    />
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-bold text-gray-900 font-mono">
                    {{ formatMoney(item.totalPrecio) }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-center">
                    <button
                      type="button"
                      class="w-7 h-7 rounded-md bg-[#FDEEED] hover:bg-[#FCD8D6] text-[#E53935] flex items-center justify-center transition-colors mx-auto cursor-pointer"
                      title="Eliminar ítem"
                      @click="eliminarItemCompra(idx)"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </TableCell>
                </TableRow>

                <!-- Filas Skeleton de Relleno si no hay suficientes ítems -->
                <TableRow
                  v-for="i in Math.max(0, 8 - paginatedCompraItems.length)"
                  :key="'skel-adm-' + i"
                  class="border-b border-gray-100 hover:bg-transparent"
                >
                  <TableCell class="py-3 px-3">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-24"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-6 w-16 mx-auto bg-gray-100 border border-gray-200 rounded-lg"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-12 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-12 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-12 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="w-7 h-7 rounded-md bg-[#FDEEED] flex items-center justify-center text-[#E53935] mx-auto opacity-70">
                      <Trash2 class="w-3.5 h-3.5" />
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Pie de Detalle: Paginación izquierda + Botones de refrescar/papelera derecha -->
          <div class="flex items-center justify-between pt-1">
            <DataTablePagination
              :current-page="compraPage"
              :total-pages="compraTotalPages"
              :rows-per-page="compraRowsPerPage"
              :rows-per-page-options="[6, 8, 12]"
              @update:current-page="compraPage = $event"
              @update:rows-per-page="compraRowsPerPage = $event"
            />
            <div class="flex items-center gap-1.5">
              <button
                type="button"
                class="w-8 h-8 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center text-gray-600 transition-colors cursor-pointer shadow-2xs"
                title="Recalcular totales"
                @click="recalcularTotales"
              >
                <RotateCw class="w-4 h-4" />
              </button>
              <button
                type="button"
                class="w-8 h-8 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center text-gray-600 transition-colors cursor-pointer shadow-2xs"
                title="Limpiar todos los ítems"
                @click="vaciarItemsCompra"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>

          <!-- Resumen de Totales: Subtotal e IGV (adm1 / adm4) -->
          <div class="flex justify-end pt-3">
            <div class="w-72 border border-gray-200 rounded-xl p-3.5 space-y-2 bg-white shadow-2xs">
              <div class="flex items-center justify-between text-xs font-semibold text-gray-700">
                <span>Subtotal:</span>
                <span class="font-mono text-gray-900">{{ formatMoney(compraForm.subtotal) }}</span>
              </div>
              <div class="flex items-center justify-between text-xs font-semibold text-gray-700">
                <span>IGV:</span>
                <div class="w-24">
                  <Input
                    v-model.number="compraForm.igv"
                    type="number"
                    step="0.01"
                    class="h-7 text-xs text-right font-mono border-gray-200 rounded-lg shadow-2xs"
                    @input="recalcularTotales"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones Inferiores de Acción: ATRÁS y GUARDAR (adm1) -->
        <div class="flex items-center justify-end gap-3 pt-2">
          <Button
            type="button"
            variant="outline"
            class="h-9 px-6 text-xs font-semibold text-gray-700 border-gray-300 hover:bg-gray-50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs"
            @click="volverALista"
          >
            <ArrowLeft class="w-4 h-4" />
            <span>ATRÁS</span>
          </Button>

          <Button
            type="button"
            class="h-9 px-6 text-xs font-bold text-white bg-[#F0302E] hover:bg-[#d42725] rounded-xl flex items-center gap-1.5 cursor-pointer shadow-xs transition-colors"
            @click="guardarCompra"
          >
            <Save class="w-4 h-4" />
            <span>GUARDAR</span>
          </Button>
        </div>
      </Card>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 1: PROVEEDORES (Captura: MODALES.png - arriba)             -->
    <!-- =============================================================== -->
    <div
      v-if="showModalProveedores"
      @click.self="showModalProveedores = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header del Modal -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Proveedores
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md cursor-pointer"
            @click="showModalProveedores = false"
          >
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <!-- Cuerpo del Modal -->
        <div class="p-6 space-y-4">
          <!-- Buscador de Proveedor -->
          <div class="relative max-w-sm max-h-[90vh] flex flex-col">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="searchProveedorModal"
              type="text"
              placeholder="Buscar..."
              class="pl-9 h-9 text-xs rounded-xl border-gray-200 bg-white shadow-2xs"
            />
          </div>

          <!-- Tabla de Proveedores con selección -->
          <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <Table>
              <TableHeader>
                <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Cod. Proveedor
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Proveedor
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Dirección
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    N° Documento
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Tipo Doc.
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Teléfono
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="p in paginatedProveedores"
                  :key="'prov-m-' + p.codProveedor"
                  class="cursor-pointer transition-colors border-b border-gray-100"
                  :class="[
                    proveedorSeleccionadoTemp?.codProveedor === p.codProveedor
                      ? 'bg-blue-50/80 font-semibold'
                      : 'hover:bg-gray-50/80'
                  ]"
                  @click="seleccionarProveedorFila(p)"
                >
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ p.codProveedor }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-left text-gray-800">
                    {{ p.proveedor }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-left text-gray-600">
                    {{ p.direccion }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ p.numDocumento }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-semibold text-gray-700">
                    {{ p.tipoDoc }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ p.telefono }}
                  </TableCell>
                </TableRow>

                <!-- Skeleton Rows (Proveedores 2 / compra) -->
                <TableRow
                  v-for="i in Math.max(0, 8 - paginatedProveedores.length)"
                  :key="'skel-prov-' + i"
                  class="border-b border-gray-100 hover:bg-transparent"
                >
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-12 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-28"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-24"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-16 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-10 mx-auto"></div>
                  </TableCell>
                  <TableCell class="py-3 px-3 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-14 mx-auto"></div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginador Modal Proveedores -->
          <div class="pt-1 flex items-center justify-between">
            <DataTablePagination
              :current-page="proveedorPage"
              :total-pages="totalPagesProveedores"
              :rows-per-page="proveedorRowsPerPage"
              :rows-per-page-options="[6, 8, 12]"
              @update:current-page="proveedorPage = $event"
              @update:rows-per-page="proveedorRowsPerPage = $event"
            />
          </div>
        </div>

        <!-- Footer Modal Proveedores: + NUEVO PROVEEDOR a la izquierda, ATRÁS y ACEPTAR a la derecha -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white">
          <Button
            type="button"
            variant="outline"
            class="h-9 px-4 text-xs font-semibold text-[#3E55B3] border-[#3E55B3] hover:bg-blue-50/50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs uppercase"
          >
            <Plus class="w-4 h-4 stroke-[2.5]" />
            <span>NUEVO PROVEEDOR</span>
          </Button>

          <div class="flex items-center gap-2.5">
            <Button
              type="button"
              variant="outline"
              class="h-9 px-5 text-xs font-semibold text-gray-700 border-gray-300 hover:bg-gray-50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs"
              @click="showModalProveedores = false"
            >
              <ArrowLeft class="w-3.5 h-3.5" />
              <span>ATRÁS</span>
            </Button>
            <Button
              type="button"
              class="h-9 px-5 text-xs font-bold text-white bg-[#F0302E] hover:bg-[#d42725] rounded-xl flex items-center gap-1.5 cursor-pointer shadow-xs transition-colors"
              @click="aceptarProveedorModal"
            >
              <CheckCircle2 class="w-4 h-4" />
              <span>ACEPTAR</span>
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 2: LISTA INSUMOS (Captura: MODALES.png - abajo)            -->
    <!-- =============================================================== -->
    <div
      v-if="showModalInsumos"
      @click.self="showModalInsumos = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header del Modal -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Lista Insumos
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md cursor-pointer"
            @click="showModalInsumos = false"
          >
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <!-- Cuerpo Modal -->
        <div class="p-6 space-y-4">
          <!-- Buscador de Insumos -->
          <div class="relative max-w-sm max-h-[90vh] flex flex-col">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <Input
              v-model="searchInsumoModal"
              type="text"
              placeholder="Buscar..."
              class="pl-9 h-9 text-xs rounded-xl border-gray-200 bg-white shadow-2xs"
            />
          </div>

          <!-- Tabla de Selección de Insumos -->
          <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <Table>
              <TableHeader>
                <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                  <TableHead class="text-white text-xs font-semibold h-10 text-left py-2 px-4">
                    Descripción
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-4">
                    Tipo Insumo
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <!-- Filas Reales e Interactivas -->
                <TableRow
                  v-for="insumo in paginatedInsumos"
                  :key="'ins-m-' + insumo.id"
                  class="cursor-pointer transition-colors border-b border-gray-100 select-none"
                  :class="[
                    isInsumoSeleccionado(insumo.id)
                      ? 'bg-[#D7E9FB] font-semibold text-gray-900'
                      : 'hover:bg-gray-50/80 text-gray-700'
                  ]"
                  @click="toggleSeleccionInsumo(insumo)"
                >
                  <TableCell class="py-2.5 px-4 text-xs">
                    {{ insumo.descripcion }}
                  </TableCell>
                  <TableCell class="py-2.5 px-4 text-xs text-center font-medium uppercase">
                    {{ insumo.tipoInsumo }}
                  </TableCell>
                </TableRow>

                <!-- Filas Skeleton -->
                <TableRow
                  v-for="i in Math.max(0, 10 - paginatedInsumos.length)"
                  :key="'skel-ins-' + i"
                  class="border-b border-gray-100 hover:bg-transparent"
                >
                  <TableCell class="py-3 px-4">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-36"></div>
                  </TableCell>
                  <TableCell class="py-3 px-4 text-center">
                    <div class="h-2.5 bg-gray-200/70 rounded-full w-20 mx-auto"></div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Paginador Modal Insumos -->
          <div class="pt-1 flex items-center justify-between">
            <DataTablePagination
              :current-page="insumoPage"
              :total-pages="totalPagesInsumos"
              :rows-per-page="insumoRowsPerPage"
              :rows-per-page-options="[5, 10, 15]"
              @update:current-page="insumoPage = $event"
              @update:rows-per-page="insumoRowsPerPage = $event"
            />
          </div>
        </div>

        <!-- Footer Modal Insumos: ATRÁS y ACEPTAR a la derecha -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2.5 bg-white">
          <Button
            type="button"
            variant="outline"
            class="h-9 px-5 text-xs font-semibold text-gray-700 border-gray-300 hover:bg-gray-50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs"
            @click="showModalInsumos = false"
          >
            <ArrowLeft class="w-3.5 h-3.5" />
            <span>ATRÁS</span>
          </Button>
          <Button
            type="button"
            class="h-9 px-5 text-xs font-bold text-white bg-[#F0302E] hover:bg-[#d42725] rounded-xl flex items-center gap-1.5 cursor-pointer shadow-xs transition-colors"
            @click="aceptarInsumosModal"
          >
            <CheckCircle2 class="w-4 h-4" />
            <span>ACEPTAR</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 3: VER COMPRA REALIZADA (Captura: MDOAL1.png)             -->
    <!-- =============================================================== -->
    <div
      v-if="showVerModal && compraSeleccionadaVer"
      @click.self="showVerModal = false"
      class="fixed inset-0 z-50 bg-black/45 backdrop-blur-2xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header del Modal -->
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 bg-white">
          <h3 class="font-bold text-sm text-gray-900 font-sans tracking-tight">
            Ver Compra Realizada
          </h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md cursor-pointer"
            @click="showVerModal = false"
          >
            <X class="w-4 h-4 stroke-[2.5]" />
          </button>
        </div>

        <!-- Info Superior: Observaciones y Fecha -->
        <div class="px-6 pt-5 pb-3 flex items-center justify-between text-xs text-gray-700">
          <div>
            <span class="font-bold text-gray-900">Observaciones: </span>
            <span class="font-medium uppercase">{{ compraSeleccionadaVer.observacion || 'COMPRA PRUEBA' }}</span>
          </div>
          <div>
            <span class="font-bold text-gray-900">Fecha </span>
            <span class="font-mono text-gray-700">{{ compraSeleccionadaVer.fecha || '00/00/0000' }}</span>
          </div>
        </div>

        <!-- Tabla con Borde Redondeado Interior -->
        <div class="p-6 pt-1 space-y-4">
          <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <Table>
              <TableHeader>
                <TableRow class="bg-[#2E3346] hover:bg-[#2E3346] border-none text-white">
                  <TableHead class="text-white text-xs font-semibold h-10 text-left py-2 px-4">
                    Insumos
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Cantidad
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    U. M.
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Precio U.
                  </TableHead>
                  <TableHead class="text-white text-xs font-semibold h-10 text-center py-2 px-3">
                    Total
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <!-- Si tiene ítems reales -->
                <TableRow
                  v-for="(it, i) in (compraSeleccionadaVer.items.length ? compraSeleccionadaVer.items : [
                    { descripcion: 'CECINA', cantidad: 25, um: 'UND', precioU: 3.00, totalPrecio: 75.00 },
                    { descripcion: 'ARROZ COSTEÑO', cantidad: 1, um: 'UND', precioU: 190.80, totalPrecio: 190.80 },
                    { descripcion: 'VINAGRE BLANCO 250 ML', cantidad: 6, um: 'UND', precioU: 4.00, totalPrecio: 24.00 }
                  ])"
                  :key="'it-ver-' + i"
                  class="border-b border-gray-100 hover:bg-gray-50/60"
                >
                  <TableCell class="py-2.5 px-4 text-xs font-semibold text-gray-800">
                    {{ it.descripcion }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ it.cantidad }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center text-gray-600 uppercase">
                    {{ it.um }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-mono text-gray-700">
                    {{ formatMoney(it.precioU) }}
                  </TableCell>
                  <TableCell class="py-2.5 px-3 text-xs text-center font-bold text-gray-900 font-mono">
                    {{ formatMoney(it.totalPrecio) }}
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Total / Subtotal a la Derecha -->
          <div class="flex justify-end pt-1">
            <div class="w-64 border border-gray-200 rounded-xl px-4 py-2.5 flex items-center justify-between text-xs font-semibold text-gray-700 bg-white shadow-2xs">
              <span>Subtotal:</span>
              <span class="font-mono text-gray-900 font-bold">
                {{ formatMoney(compraSeleccionadaVer.subtotal || 289.80) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Footer Modal Ver Compra -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end bg-white">
          <Button
            type="button"
            variant="outline"
            class="h-9 px-6 text-xs font-semibold text-gray-700 border-gray-300 hover:bg-gray-50 rounded-xl flex items-center gap-1.5 cursor-pointer shadow-2xs"
            @click="showVerModal = false"
          >
            <ArrowLeft class="w-3.5 h-3.5" />
            <span>ATRÁS</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- =============================================================== -->
    <!-- MODAL 4: CONFIRMACIÓN DE ELIMINAR                              -->
    <!-- =============================================================== -->
    <div
      v-if="showDeleteModal && compraAEliminar"
      @click.self="showDeleteModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header Rojo / Negro -->
        <div class="bg-[#1A1D27] text-white px-5 py-3.5 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <TriangleAlert class="w-4 h-4 text-red-500" />
            <h3 class="font-bold text-xs uppercase tracking-wide">
              Eliminar Compra
            </h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md cursor-pointer"
            @click="showDeleteModal = false"
          >
            <X class="w-4 h-4" />
          </button>
        </div>

        <!-- Contenido -->
        <div class="p-5 space-y-3">
          <p class="text-xs text-gray-600">
            ¿Está seguro de que desea eliminar la compra registrada con documento
            <strong class="text-gray-900 font-mono">{{ compraAEliminar.numDocumento }}</strong>
            de <strong class="text-gray-900">{{ compraAEliminar.proveedor }}</strong>?
          </p>
          <p class="text-[11px] text-red-500 font-medium">
            Esta acción no se puede deshacer.
          </p>
        </div>

        <!-- Footer -->
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
          <Button
            type="button"
            variant="outline"
            size="sm"
            class="h-8 text-xs border-gray-300 rounded-lg cursor-pointer"
            @click="showDeleteModal = false"
          >
            Cancelar
          </Button>
          <Button
            type="button"
            size="sm"
            class="h-8 text-xs bg-red-600 hover:bg-red-700 text-white rounded-lg cursor-pointer font-semibold shadow-xs"
            @click="confirmarEliminar"
          >
            Eliminar
          </Button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
