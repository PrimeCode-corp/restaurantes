<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  Search,
  Filter,
  Eye,
  ChevronLeft,
  ChevronRight,
  MessageCircle,
  Mail,
  Printer,
  Calendar,
  X,
  Send
} from 'lucide-vue-next'

// Filters
const filterFecha = ref('TODOS')
const filterComprobantes = ref('TODOS')
const filterTipoPago = ref('TODOS')
const filterMedioPago = ref('TODOS')
const filterCliente = ref('')
const filterVendedor = ref('')

// Modals
const showDetalleModal = ref(false)
const showEmailModal = ref(false)
const showWhatsappModal = ref(false)
const showReimprimirModal = ref(false)

// Sample items
const ventas = ref([
  {
    id: 1,
    fecha: '00/00/0000',
    hora: '00:00:00',
    cliente: 'DEMO',
    documento: 'CAJA PRINCIPAL',
    nDocumento: 'S01-000022',
    medioPago: 'DEMO',
    tipoPago: 'DEMO',
    monto: 'DEMO',
    estado: '00000000'
  }
])

// Detalle data for modal
const detalleItems = ref([
  { cant: '2', um: 'UNIDAD', producto: 'TALLARINES ROJOS', pu: '14.00', subtotal: '28.00' },
  { cant: '1', um: 'UNIDAD', producto: 'CEVICHE MIXTO', pu: '24.00', subtotal: '24.00' },
])

const openDetalle = (item) => {
  showDetalleModal.value = true
}
</script>

<template>
  <AdminLayout active-nav="ventas" active-submenu="reporte_ventas_detalladas">
    <div class="p-6 bg-[#F4F6F9] min-h-[calc(100vh-64px)]">
      <!-- Title -->
      <h1 class="text-xl font-bold text-gray-800 mb-6">Reporte de Ventas Detalladas</h1>

      <!-- Top Filter Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4 text-xs font-bold text-gray-800">
          <Filter class="w-4 h-4 text-gray-600" />
          <span>Filtros</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
          <div>
            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Fecha</label>
            <div class="relative">
              <select v-model="filterFecha" class="w-full pl-8 pr-8 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-700 appearance-none">
                <option>TODOS</option>
                <option>HOY</option>
                <option>ESTA SEMANA</option>
                <option>ESTE MES</option>
              </select>
              <Calendar class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-red-500" />
              <ChevronRight class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 rotate-90" />
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Comprobantes</label>
            <div class="relative">
              <select v-model="filterComprobantes" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-700">
                <option>TODOS</option>
                <option>BOLETA</option>
                <option>FACTURA</option>
                <option>TICKET</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Tipo Pago</label>
            <div class="relative">
              <select v-model="filterTipoPago" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-700">
                <option>TODOS</option>
                <option>CONTADO</option>
                <option>CRÉDITO</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Medio de Pago</label>
            <div class="relative">
              <select v-model="filterMedioPago" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white text-gray-700">
                <option>TODOS</option>
                <option>EFECTIVO</option>
                <option>YAPE / PLIN</option>
                <option>TARJETA</option>
              </select>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Cliente:</label>
            <div class="relative">
              <input
                v-model="filterCliente"
                type="text"
                placeholder="Busca Cliente"
                class="w-full pl-3 pr-9 py-2 border border-gray-200 rounded-lg text-xs"
              />
              <Search class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-500" />
            </div>
          </div>
          <div>
            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Vendedor:</label>
            <div class="relative">
              <input
                v-model="filterVendedor"
                type="text"
                placeholder="Busca Vendedor"
                class="w-full pl-3 pr-9 py-2 border border-gray-200 rounded-lg text-xs"
              />
              <Search class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-500" />
            </div>
          </div>
        </div>
      </div>

      <!-- Main Table Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Action Buttons Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
          <div class="relative w-80">
            <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input
              type="text"
              placeholder="Buscar..."
              class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-xs text-gray-700 placeholder-gray-400 focus:ring-1 focus:ring-red-500"
            />
          </div>

          <div class="flex items-center gap-2.5">
            <button
              @click="showWhatsappModal = true"
              class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 transition-colors"
            >
              <MessageCircle class="w-4 h-4 text-gray-600" />
              <span>ENVIAR WHATSAPP</span>
            </button>

            <button
              @click="showEmailModal = true"
              class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5 transition-colors"
            >
              <Mail class="w-4 h-4 text-gray-600" />
              <span>ENVIAR CORREO</span>
            </button>

            <button
              @click="showReimprimirModal = true"
              class="px-4 py-2 bg-[#F57C00] hover:bg-[#e65100] text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-colors shadow-sm"
            >
              <Printer class="w-4 h-4" />
              <span>RE IMPRIMIR</span>
            </button>
          </div>
        </div>

        <!-- Sales Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="w-full text-xs text-left">
            <thead class="bg-[#2E3346] text-white">
              <tr>
                <th class="py-3 px-3 font-semibold text-center">Fecha</th>
                <th class="py-3 px-3 font-semibold text-center">Hora</th>
                <th class="py-3 px-4 font-semibold text-center">Cliente</th>
                <th class="py-3 px-3 font-semibold text-center">Documento</th>
                <th class="py-3 px-3 font-semibold text-center">N° Documento</th>
                <th class="py-3 px-3 font-semibold text-center">Medio Pago</th>
                <th class="py-3 px-3 font-semibold text-center">Tipo Pago</th>
                <th class="py-3 px-3 font-semibold text-center">Monto</th>
                <th class="py-3 px-3 font-semibold text-center">Estado</th>
                <th class="py-3 px-3 font-semibold text-center">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
              <tr v-for="item in ventas" :key="item.id" class="hover:bg-gray-50/80 transition-colors">
                <td class="py-3 px-3 text-center text-gray-600">{{ item.fecha }}</td>
                <td class="py-3 px-3 text-center text-gray-600">{{ item.hora }}</td>
                <td class="py-3 px-4 text-center font-medium text-gray-800">{{ item.cliente }}</td>
                <td class="py-3 px-3 text-center text-gray-600">{{ item.documento }}</td>
                <td class="py-3 px-3 text-center text-gray-600 font-mono">{{ item.nDocumento }}</td>
                <td class="py-3 px-3 text-center text-gray-600">{{ item.medioPago }}</td>
                <td class="py-3 px-3 text-center text-gray-600">{{ item.tipoPago }}</td>
                <td class="py-3 px-3 text-center font-semibold text-gray-800">{{ item.monto }}</td>
                <td class="py-3 px-3 text-center text-gray-500">{{ item.estado }}</td>
                <td class="py-3 px-3 text-center">
                  <button
                    @click="openDetalle(item)"
                    class="w-7 h-7 rounded bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors mx-auto"
                  >
                    <Eye class="w-3.5 h-3.5" />
                  </button>
                </td>
              </tr>

              <!-- Skeletons (10 rows as in mockup) -->
              <tr v-for="n in 8" :key="'skel-' + n" class="hover:bg-gray-50/80">
                <td class="py-3 px-3 text-center text-gray-400">00/00/0000</td>
                <td class="py-3 px-3 text-center text-gray-400"><div class="h-3 bg-gray-200 rounded-full w-12 mx-auto"></div></td>
                <td class="py-3 px-4 text-center"><div class="h-3 bg-gray-200 rounded-full w-24 mx-auto"></div></td>
                <td class="py-3 px-3 text-center"><div class="h-3 bg-gray-200 rounded-full w-20 mx-auto"></div></td>
                <td class="py-3 px-3 text-center"><div class="h-3 bg-gray-200 rounded-full w-16 mx-auto"></div></td>
                <td class="py-3 px-3 text-center"><div class="h-3 bg-gray-200 rounded-full w-16 mx-auto"></div></td>
                <td class="py-3 px-3 text-center"><div class="h-3 bg-gray-200 rounded-full w-14 mx-auto"></div></td>
                <td class="py-3 px-3 text-center"><div class="h-3 bg-gray-200 rounded-full w-14 mx-auto"></div></td>
                <td class="py-3 px-3 text-center"><div class="h-3 bg-gray-200 rounded-full w-14 mx-auto"></div></td>
                <td class="py-3 px-3 text-center">
                  <div class="w-7 h-7 rounded bg-blue-50 text-blue-400 flex items-center justify-center mx-auto opacity-70">
                    <Eye class="w-3.5 h-3.5" />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-5 text-xs text-gray-500">
          <div class="flex items-center gap-2">
            <select class="border border-gray-200 rounded px-2 py-1 text-xs text-gray-600 bg-white">
              <option>10</option>
              <option>25</option>
            </select>
            <button class="p-1 border border-gray-200 rounded hover:bg-gray-50 disabled:opacity-50">
              <ChevronLeft class="w-3.5 h-3.5" />
            </button>
            <span class="px-2">Página 1 de 1</span>
            <button class="p-1 border border-gray-200 rounded hover:bg-gray-50">
              <ChevronRight class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>

      <!-- MODAL 1: VER VENTA DETALLADA -->
      <div
        v-if="showDetalleModal"
        @click.self="showDetalleModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
      >
        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
          <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <h2 class="text-xs font-bold text-gray-800">Ver Venta Detallada</h2>
            <button @click="showDetalleModal = false" class="text-gray-400 hover:text-gray-700">
              <X class="w-4 h-4" />
            </button>
          </div>

          <div class="p-6 space-y-4">
            <!-- Header Info Details -->
            <div class="grid grid-cols-2 gap-y-2 text-xs">
              <div><span class="text-gray-500 font-semibold">Cliente:</span> <span class="font-bold text-gray-700">CLIENTES VARIOS</span></div>
              <div><span class="text-gray-500 font-semibold">Documento:</span> <span class="text-gray-700">FACTURA ELECTRONICA</span></div>
              <div><span class="text-gray-500 font-semibold">Empleado:</span> <span class="text-gray-700">NOMBRE DEL EMPLEADO</span></div>
              <div><span class="text-gray-500 font-semibold">N° Documento:</span> <span class="font-mono text-gray-700">00-0000000</span></div>
              <div><span class="text-gray-500 font-semibold">Tipo Pago:</span> <span class="text-gray-700">CONTADO</span></div>
              <div><span class="text-gray-500 font-semibold">Medio Pago:</span> <span class="text-gray-700">EFECTIVO</span></div>
              <div class="col-span-2"><span class="text-gray-500 font-semibold">Fecha:</span> <span class="text-gray-700">00/00/0000</span></div>
            </div>

            <!-- Detallado Table Box -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
              <div class="bg-gray-50 px-4 py-2 text-xs font-bold text-gray-700 flex items-center justify-between border-b border-gray-200">
                <span>Detallado</span>
                <ChevronLeft class="w-4 h-4 rotate-90 text-gray-400" />
              </div>

              <table class="w-full text-xs text-left">
                <thead class="bg-[#2E3346] text-white">
                  <tr>
                    <th class="py-2.5 px-3 font-semibold text-center">Cant.</th>
                    <th class="py-2.5 px-3 font-semibold text-center">U.M.</th>
                    <th class="py-2.5 px-4 font-semibold">Producto</th>
                    <th class="py-2.5 px-3 font-semibold text-right">P.U.</th>
                    <th class="py-2.5 px-3 font-semibold text-right">Sub Total</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                  <tr v-for="(d, idx) in detalleItems" :key="idx">
                    <td class="py-2 px-3 text-center text-gray-700">{{ d.cant }}</td>
                    <td class="py-2 px-3 text-center text-gray-600">{{ d.um }}</td>
                    <td class="py-2 px-4 font-medium text-gray-800">{{ d.producto }}</td>
                    <td class="py-2 px-3 text-right text-gray-700">{{ d.pu }}</td>
                    <td class="py-2 px-3 text-right font-medium text-gray-800">{{ d.subtotal }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr class="bg-gray-50 border-t border-gray-200 font-bold text-gray-800">
                    <td colspan="4" class="py-2.5 px-4 text-left">TOTAL</td>
                    <td class="py-2.5 px-3 text-right">52.00</td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end pt-2">
              <button
                @click="showDetalleModal = false"
                class="px-5 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1.5"
              >
                <ChevronLeft class="w-4 h-4" />
                <span>ATRÁS</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL 2: ENVIAR EMAIL -->
      <div
        v-if="showEmailModal"
        @click.self="showEmailModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
      >
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
          <div class="px-5 py-3.5 flex items-center justify-between border-b border-gray-100">
            <h2 class="text-xs font-bold text-gray-800">Enviar Email</h2>
            <button @click="showEmailModal = false" class="text-gray-400 hover:text-gray-700">
              <X class="w-4 h-4" />
            </button>
          </div>
          <div class="p-5 space-y-3">
            <div>
              <div class="text-[11px] font-bold text-gray-800">FACTURA ELECTRONICA - 00 0000000</div>
              <div class="text-[10px] text-gray-500">CLIENTE: CLIENTES VARIOS</div>
            </div>
            <div>
              <label class="block text-[11px] text-gray-600 mb-1">Estado:</label>
              <input type="text" value="PAGADO" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs" />
            </div>
            <div class="flex items-center justify-end gap-2 pt-3">
              <button
                @click="showEmailModal = false"
                class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1"
              >
                <ChevronLeft class="w-3.5 h-3.5" />
                <span>ATRÁS</span>
              </button>
              <button
                @click="showEmailModal = false"
                class="px-5 py-2 bg-[#F0302E] hover:bg-red-600 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm"
              >
                <Send class="w-3.5 h-3.5" />
                <span>ENVIAR</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL 3: ENVIAR WHATSAPP -->
      <div
        v-if="showWhatsappModal"
        @click.self="showWhatsappModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
      >
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
          <div class="px-5 py-3.5 flex items-center justify-between border-b border-gray-100">
            <h2 class="text-xs font-bold text-gray-800">Enviar Whatsapp</h2>
            <button @click="showWhatsappModal = false" class="text-gray-400 hover:text-gray-700">
              <X class="w-4 h-4" />
            </button>
          </div>
          <div class="p-5 space-y-3">
            <div>
              <div class="text-[11px] font-bold text-gray-800">FACTURA ELECTRONICA - 00 0000000</div>
              <div class="text-[10px] text-gray-500">CLIENTE: CLIENTES VARIOS</div>
            </div>
            <div>
              <label class="block text-[11px] text-gray-600 mb-1">Estado:</label>
              <input type="text" value="PAGADO" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs" />
            </div>
            <div class="flex items-center justify-end gap-2 pt-3">
              <button
                @click="showWhatsappModal = false"
                class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1"
              >
                <ChevronLeft class="w-3.5 h-3.5" />
                <span>ATRÁS</span>
              </button>
              <button
                @click="showWhatsappModal = false"
                class="px-5 py-2 bg-[#F0302E] hover:bg-red-600 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm"
              >
                <Send class="w-3.5 h-3.5" />
                <span>ENVIAR</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL 4: RE IMPRIMIR TICKET PREVIEW -->
      <div
        v-if="showReimprimirModal"
        @click.self="showReimprimirModal = false"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
      >
        <div class="bg-[#2E3346] text-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 flex flex-col md:flex-row min-h-[550px]">
          <!-- Left: Thermal Ticket Mockup -->
          <div class="flex-1 bg-gray-500/30 p-6 flex items-center justify-center overflow-y-auto max-h-[600px]">
            <div class="bg-white text-gray-900 w-72 p-5 rounded shadow font-mono text-[9px] leading-tight space-y-2">
              <div class="text-center">
                <div class="font-bold text-[10px]">LA CONCHITA RICA PEÑA CRIOLLA</div>
                <div>LA CONCHITA RICA PEÑA CRIOLLA S.A.C</div>
                <div>AV. VIRGEN DOLOROSA CDRA 14 - LA BANDA DE SHILCAYO - SAN MARTIN - SAN MARTIN</div>
                <div>TELF: DELIVERY 928944538</div>
                <div>RUC: 20614072677</div>
                <div class="font-bold my-1">BOLETA DE VENTA ELECTRÓNICA<br>B001-00000060</div>
              </div>

              <div class="border-t border-b border-dashed border-gray-400 py-1 space-y-0.5">
                <div>CLIENTE: CLIENTES VARIOS</div>
                <div>DNI: 00000000</div>
                <div>DIRECCIÓN: SIN DIRECCIÓN</div>
                <div>FECHA EMISIÓN: 31/01/2026 17:03:13</div>
                <div>FORMA DE PAGO: CONTADO</div>
              </div>

              <!-- Ticket Table -->
              <table class="w-full text-left">
                <thead>
                  <tr class="border-b border-gray-300">
                    <th>DESCRIPCIÓN<br>CANT &nbsp; U.M.</th>
                    <th class="text-right">PRECIO</th>
                    <th class="text-right">TOTAL</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr>
                    <td colspan="3" class="pt-1 font-bold">CEVICHE DE CABALLA</td>
                  </tr>
                  <tr>
                    <td>2.00 &nbsp; UND</td>
                    <td class="text-right">25.00</td>
                    <td class="text-right">50.00</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="pt-1 font-bold">CEVICHE MIXTO</td>
                  </tr>
                  <tr>
                    <td>1.00 &nbsp; UND</td>
                    <td class="text-right">25.00</td>
                    <td class="text-right">25.00</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="pt-1">COMPLEMENTO FAMILIAR</td>
                  </tr>
                  <tr>
                    <td>1.00 &nbsp; UND</td>
                    <td class="text-right">4.00</td>
                    <td class="text-right">4.00</td>
                  </tr>
                </tbody>
              </table>

              <div class="border-t border-dashed border-gray-400 pt-1 space-y-0.5 text-right font-bold">
                <div>SUB TOTAL S/ 79.50</div>
                <div>EXONERADA S/ 79.50</div>
                <div class="text-[10px]">TOTAL S/ 79.50</div>
              </div>

              <div class="text-[8px] text-center pt-2">
                <div>SON: SETENTA Y NUEVE Y 50/100 SOLES</div>
                <div class="my-1 font-bold">**** EFECTIVO S/ 9.00</div>
                <div class="font-bold">**** YAPE S/ 70.50</div>
                <div>VENDEDOR: GABRIELA | MESA: MESA 06</div>
              </div>

              <!-- Fake QR code box -->
              <div class="w-20 h-20 bg-gray-200 border border-gray-400 mx-auto flex items-center justify-center text-[7px] text-gray-500 text-center p-1">
                [ QR CODE SUNAT ]
              </div>
            </div>
          </div>

          <!-- Right: Print Settings Panel -->
          <div class="w-full md:w-80 bg-[#1E2540] p-6 flex flex-col justify-between">
            <div class="space-y-4">
              <div class="flex items-center justify-between pb-2 border-b border-gray-700">
                <span class="text-sm font-bold text-white">Imprimir</span>
                <span class="text-xs text-gray-400">1 hoja de papel</span>
              </div>

              <div>
                <label class="block text-xs text-gray-400 mb-1">Destino</label>
                <select class="w-full px-3 py-2 bg-[#2E3346] border border-gray-600 rounded text-xs text-white">
                  <option>Nitro PDF Creator</option>
                  <option>Ticketera Térmica 80mm</option>
                </select>
              </div>

              <div>
                <label class="block text-xs text-gray-400 mb-1">Páginas</label>
                <select class="w-full px-3 py-2 bg-[#2E3346] border border-gray-600 rounded text-xs text-white">
                  <option>Todas</option>
                </select>
              </div>

              <div>
                <label class="block text-xs text-gray-400 mb-1">Copias</label>
                <input type="number" value="1" class="w-24 px-3 py-1.5 bg-[#2E3346] border border-gray-600 rounded text-xs text-white" />
              </div>

              <div>
                <label class="block text-xs text-gray-400 mb-1">Diseño</label>
                <select class="w-full px-3 py-2 bg-[#2E3346] border border-gray-600 rounded text-xs text-white">
                  <option>Vertical</option>
                </select>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-700">
              <button
                @click="showReimprimirModal = false"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs font-semibold"
              >
                Cancelar
              </button>
              <button
                @click="showReimprimirModal = false"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold"
              >
                Imprimir
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
