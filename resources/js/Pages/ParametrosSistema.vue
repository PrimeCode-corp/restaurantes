<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Card } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Switch } from '@/Components/ui/switch'
import {
  Briefcase,
  Sliders,
  Printer,
  TriangleAlert,
  Upload,
  Lock,
  Save,
  CheckCircle2,
  X,
} from 'lucide-vue-next'

// -------------------------------------------------------------------
// DATOS DE LA EMPRESA (Fiel a la imagen 4)
// -------------------------------------------------------------------
const empresaForm = ref({
  razonSocial: 'RIQUISIMOS PIZZITA S.A.C.',
  nombreComercial: 'RIQUISIMOS PIZZITA',
  tipoSociedad: 'E.I.R.L.',
  ruc: '248859238002',
  telefono: '123 456 789',
  direccion: 'JR. MANUELA MOREY NRO 219 TARAPOTO - SAN MARTIN - SAN MARTIN',
  logoNombre: 'Ningún archivo seleccionado',
  departamento: 'SAN MARTIN',
  provincia: 'SAN MARTIN',
  distrito: 'TARAPOTO',
})

const departamentos = ['SAN MARTIN', 'LIMA', 'AREQUIPA', 'LA LIBERTAD', 'CUSCO', 'PIURA', 'LORETO']
const provincias = ['SAN MARTIN', 'MOYOBAMBA', 'LAMAS', 'RIOJA', 'BELLAVISTA', 'MARISCAL CACERES']
const distritos = ['TARAPOTO', 'MORALES', 'LA BANDA DE SHILCAYO', 'CACATACHI', 'JUAN GUERRA']

// -------------------------------------------------------------------
// AJUSTES: COMPORTAMIENTO
// -------------------------------------------------------------------
const comportamientoAjustes = ref([
  { id: 'ventana_venta', label: 'MOSTRAR VENTANA DE VENTA', active: true },
  { id: 'cocina_rapida', label: 'COCINA PARA PRODUCTOS RÁPIDOS', active: false },
  { id: 'validar_mozo', label: 'VALIDAR MOZO POR MESA', active: true },
  { id: 'sonido_pedido', label: 'SONIDO EN NUEVO PEDIDO', active: false },
  { id: 'bloquear_stock', label: 'BLOQUEAR PEDIDO SIN STOCK', active: false },
  { id: 'propina_voluntaria', label: 'PERMITIR PROPINA VOLUNTARIA', active: false },
])

const comportamientoActivos = computed(() => {
  return comportamientoAjustes.value.filter((a) => a.active).length
})

// -------------------------------------------------------------------
// AJUSTES: IMPRESIÓN Y DOCUMENTOS
// -------------------------------------------------------------------
const impresionAjustes = ref([
  { id: 'imprimir_doc', label: 'IMPRIMIR DOCUMENTO', active: true },
  { id: 'imprimir_ws', label: 'IMPRIMIR DOCUMENTO AL ENVIAR WHATSAPP', active: false },
  { id: 'mostrar_logo', label: 'MOSTRAR LOGO IMPRESIÓN', active: true },
  { id: 'cortar_ticket', label: 'CORTAR TICKET AUTOMÁTICAMENTE', active: false },
  { id: 'enviar_ws_auto', label: 'ENVIAR TICKET AUTOMÁTICO POR WHATSAPP', active: false },
  { id: 'codigo_qr', label: 'INCLUIR CÓDIGO QR EN TICKET', active: false },
  { id: 'precuenta_auto', label: 'IMPRIMIR PRE-CUENTA AUTOMÁTICA', active: false },
  { id: 'copia_seguridad', label: 'COPIA DE SEGURIDAD EN IMPRESORA 2', active: false },
])

const impresionActivos = computed(() => {
  return impresionAjustes.value.filter((a) => a.active).length
})

// -------------------------------------------------------------------
// FEEDBACK DE GUARDADO Y MODAL CLAVE
// -------------------------------------------------------------------
const showSaveSuccess = ref(false)
const showClaveModal = ref(false)
const claveForm = ref({ claveActual: '', nuevaClave: '', confirmarClave: '' })
const claveSuccess = ref(false)

function guardarParametros() {
  showSaveSuccess.value = true
  setTimeout(() => {
    showSaveSuccess.value = false
  }, 3500)
}

function handleFileChange(event) {
  const file = event.target.files?.[0]
  if (file) {
    empresaForm.value.logoNombre = file.name
  }
}

function cambiarClave() {
  if (!claveForm.value.nuevaClave || claveForm.value.nuevaClave !== claveForm.value.confirmarClave) return
  claveSuccess.value = true
  setTimeout(() => {
    claveSuccess.value = false
    showClaveModal.value = false
    claveForm.value = { claveActual: '', nuevaClave: '', confirmarClave: '' }
  }, 1800)
}
</script>

<template>
  <AdminLayout active-nav="admin-sistema" active-submenu="parametros">
    <div class="space-y-4 pb-20 select-none">
      <!-- HEADER CON TÍTULO Y SUBTÍTULO -->
      <div>
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 font-sans">
          Parámetros del Sistema
        </h1>
        <p class="text-xs text-gray-500 mt-0.5">
          Configura el comportamiento del punto de venta, cocina, impresión y accesos.
        </p>
      </div>

      <!-- ALERTA DE ADVERTENCIA NARANJA -->
      <div class="bg-gradient-to-r from-orange-100 to-amber-50 border border-orange-300 rounded-xl p-3.5 flex items-start gap-3 shadow-xs">
        <div class="p-1 rounded-md text-orange-600 shrink-0">
          <TriangleAlert class="w-5 h-5" />
        </div>
        <div class="text-xs text-orange-950 leading-relaxed">
          <strong class="text-orange-900 font-bold block mb-0.5">
            Estos ajustes afectan todo el sistema en tiempo real.
          </strong>
          Los cambios en la configuración actual del sistema, alteraran seriamente su funcionamiento. Se recomienda tomar las debidas precauciones para mantener la operatividad del mismo.
        </div>
      </div>

      <!-- ====================================================================
           CARD 1: DATOS DE LA EMPRESA
           ==================================================================== -->
      <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 space-y-5">
        <!-- Header de la tarjeta -->
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-[#F0302E] text-white flex items-center justify-center shrink-0 shadow-xs">
            <Briefcase class="w-4 h-4" />
          </div>
          <div>
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wide">
              DATOS DE LA EMPRESA
            </h2>
            <p class="text-[11px] text-gray-500">
              Identidad fiscal, ubicación y logo.
            </p>
          </div>
        </div>

        <!-- Formulario de Datos de la Empresa -->
        <div class="space-y-4">
          <!-- Fila 1: Razón Social + Nombre Comercial -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Razón Social</label>
              <Input
                v-model="empresaForm.razonSocial"
                type="text"
                class="h-9 text-xs rounded-lg border-gray-300 bg-white"
              />
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nombre Comercial</label>
              <Input
                v-model="empresaForm.nombreComercial"
                type="text"
                class="h-9 text-xs rounded-lg border-gray-300 bg-white"
              />
            </div>
          </div>

          <!-- Fila 2: Tipo Sociedad + RUC + Teléfono -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Tipo Sociedad</label>
              <Input
                v-model="empresaForm.tipoSociedad"
                type="text"
                class="h-9 text-xs rounded-lg border-gray-300 bg-white"
              />
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">RUC</label>
              <Input
                v-model="empresaForm.ruc"
                type="text"
                class="h-9 text-xs rounded-lg border-gray-300 bg-white font-mono"
              />
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Teléfono</label>
              <Input
                v-model="empresaForm.telefono"
                type="text"
                class="h-9 text-xs rounded-lg border-gray-300 bg-white"
              />
            </div>
          </div>

          <!-- Fila 3: Dirección + Logo -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Dirección</label>
              <Input
                v-model="empresaForm.direccion"
                type="text"
                class="h-9 text-xs rounded-lg border-gray-300 bg-white"
              />
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Logo de la Empresa</label>
              <div class="flex items-center gap-2">
                <label class="cursor-pointer bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-3 py-1.5 h-9 rounded-lg text-xs font-medium flex items-center gap-1.5 shadow-xs transition-colors shrink-0">
                  <Upload class="w-3.5 h-3.5 text-gray-600" />
                  <span>Seleccionar archivo</span>
                  <input type="file" accept="image/*" class="hidden" @change="handleFileChange" />
                </label>
                <span class="text-xs text-gray-500 truncate">{{ empresaForm.logoNombre }}</span>
              </div>
            </div>
          </div>

          <!-- Fila 4: Departamento + Provincia + Distrito -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Departamento</label>
              <select
                v-model="empresaForm.departamento"
                class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
              >
                <option v-for="d in departamentos" :key="d" :value="d">{{ d }}</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Provincia</label>
              <select
                v-model="empresaForm.provincia"
                class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
              >
                <option v-for="p in provincias" :key="p" :value="p">{{ p }}</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-gray-700 mb-1">Distrito</label>
              <select
                v-model="empresaForm.distrito"
                class="w-full h-9 px-3 text-xs rounded-lg border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray-400"
              >
                <option v-for="dis in distritos" :key="dis" :value="dis">{{ dis }}</option>
              </select>
            </div>
          </div>
        </div>
      </Card>

      <!-- ====================================================================
           CARDS GRID: COMPORTAMIENTO & IMPRESIÓN Y DOCUMENTOS
           ==================================================================== -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- CARD 2: COMPORTAMIENTO -->
        <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 flex flex-col justify-between">
          <div>
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#F0302E] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <Sliders class="w-4 h-4" />
                </div>
                <div>
                  <h2 class="text-xs font-bold text-gray-900 tracking-wide">
                    Comportamiento
                  </h2>
                  <p class="text-[11px] text-gray-500">
                    Venta, cocina, alarmas.
                  </p>
                </div>
              </div>
              <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                {{ comportamientoActivos }} de {{ comportamientoAjustes.length }} activos
              </span>
            </div>

            <!-- Lista de switches -->
            <div class="divide-y divide-gray-100">
              <div
                v-for="ajuste in comportamientoAjustes"
                :key="ajuste.id"
                class="flex items-center justify-between py-3 hover:bg-gray-50/60 px-1 rounded-lg transition-colors"
              >
                <span class="text-xs font-medium text-gray-800 uppercase pr-4">
                  {{ ajuste.label }}
                </span>
                <Switch
                  :checked="ajuste.active"
                  @update:checked="ajuste.active = $event"
                />
              </div>
            </div>
          </div>
        </Card>

        <!-- CARD 3: IMPRESIÓN Y DOCUMENTOS -->
        <Card class="bg-white border border-gray-200/90 shadow-sm rounded-2xl p-5 sm:p-6 flex flex-col justify-between">
          <div>
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#F0302E] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <Printer class="w-4 h-4" />
                </div>
                <div>
                  <h2 class="text-xs font-bold text-gray-900 tracking-wide">
                    Impresión y documentos
                  </h2>
                  <p class="text-[11px] text-gray-500">
                    Ticket y envío por WhatsApp
                  </p>
                </div>
              </div>
              <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                {{ impresionActivos }} de {{ impresionAjustes.length }} activos
              </span>
            </div>

            <!-- Lista de switches -->
            <div class="divide-y divide-gray-100">
              <div
                v-for="ajuste in impresionAjustes"
                :key="ajuste.id"
                class="flex items-center justify-between py-3 hover:bg-gray-50/60 px-1 rounded-lg transition-colors"
              >
                <span class="text-xs font-medium text-gray-800 uppercase pr-4">
                  {{ ajuste.label }}
                </span>
                <Switch
                  :checked="ajuste.active"
                  @update:checked="ajuste.active = $event"
                />
              </div>
            </div>
          </div>
        </Card>
      </div>
    </div>

    <!-- ====================================================================
         STICKY BOTTOM BAR (GUARDAR Y CAMBIAR CLAVE)
         ==================================================================== -->
    <div class="fixed bottom-0 right-0 left-56 bg-white/95 backdrop-blur-sm border-t border-gray-200 px-6 py-3.5 flex items-center justify-between shadow-lg z-20">
      <div class="flex items-center gap-2">
        <div v-if="showSaveSuccess" class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg animate-in fade-in">
          <CheckCircle2 class="w-4 h-4 text-emerald-600" />
          <span>¡Parámetros del sistema guardados exitosamente!</span>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <Button
          type="button"
          variant="outline"
          size="sm"
          class="bg-white hover:bg-gray-50 text-gray-700 border-gray-300 rounded-lg px-4 py-2 h-9 text-xs font-semibold tracking-wider flex items-center gap-2 shadow-xs transition-all"
          @click="showClaveModal = true"
        >
          <Lock class="w-3.5 h-3.5 text-gray-600" />
          <span>CAMBIAR CLAVE ADMIN</span>
        </Button>

        <Button
          type="button"
          size="sm"
          class="bg-[#F0302E] hover:bg-[#D02724] text-white rounded-lg px-6 py-2 h-9 text-xs font-bold tracking-wider flex items-center gap-2 shadow-sm transition-all"
          @click="guardarParametros"
        >
          <Save class="w-4 h-4 stroke-[2.5]" />
          <span>GUARDAR</span>
        </Button>
      </div>
    </div>

    <!-- ====================================================================
         MODAL: CAMBIAR CLAVE ADMIN
         ==================================================================== -->
    <div
      v-if="showClaveModal"
      @click.self="showClaveModal = false"
      class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="bg-[#1A1D27] text-white px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Lock class="w-4 h-4 text-red-500" />
            <h3 class="font-bold text-sm uppercase tracking-wide">Cambiar Clave Admin</h3>
          </div>
          <button
            class="text-gray-400 hover:text-white transition-colors p-1 rounded-md"
            @click="showClaveModal = false"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="cambiarClave" class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Clave Actual</label>
            <Input
              v-model="claveForm.claveActual"
              type="password"
              placeholder="••••••••"
              required
              class="h-9 text-xs rounded-lg border-gray-300"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Nueva Clave</label>
            <Input
              v-model="claveForm.nuevaClave"
              type="password"
              placeholder="••••••••"
              required
              class="h-9 text-xs rounded-lg border-gray-300"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Confirmar Nueva Clave</label>
            <Input
              v-model="claveForm.confirmarClave"
              type="password"
              placeholder="••••••••"
              required
              class="h-9 text-xs rounded-lg border-gray-300"
            />
          </div>

          <div v-if="claveSuccess" class="p-2 bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 text-xs flex items-center gap-1.5">
            <CheckCircle2 class="w-3.5 h-3.5 text-emerald-600" />
            <span>Clave actualizada correctamente</span>
          </div>

          <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
            <Button
              type="button"
              variant="outline"
              size="sm"
              class="border-gray-300 text-gray-700 text-xs px-4"
              @click="showClaveModal = false"
            >
              Cancelar
            </Button>
            <Button
              type="submit"
              size="sm"
              class="bg-[#F0302E] hover:bg-[#D02724] text-white text-xs px-5 font-semibold"
            >
              Actualizar Clave
            </Button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
