<script setup>
import { ref, watch, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
  LayoutDashboard,
  Monitor,
  Users,
  Warehouse,
  ShoppingBag,
  Banknote,
  Wrench,
  ReceiptText,
  ChevronLeft,
  ChevronDown,
} from 'lucide-vue-next'

const props = defineProps({
  activeNav: {
    type: String,
    default: 'almacenes',
  },
  activeSubmenu: {
    type: String,
    default: 'insumos',
  },
})

// Módulos padre reconocidos
const validModules = ['dashboard', 'admin-sistema', 'admin-empleado', 'almacenes', 'compras', 'finanzas', 'ventas']

// Claves de almacenamiento
const STORAGE_MODULE_KEY = 'mishky_active_module'
const STORAGE_ROUTE_KEY = 'mishky_active_route'

// Estado del módulo que tiene su acordeón/submenú expandido (estilo acordeón exclusivo: solo uno abierto a la vez)
const openNav = ref(null)

// Definiciones de submenús
const adminSubmenu = [
  { id: 'empresas', label: 'Empresa', route: 'empresas' },
  { id: 'sucursales', label: 'Sucursales', route: 'sucursales' },
  { id: 'configuracion_caja', label: 'Configuración Caja', route: 'configuracion_caja' },
  { id: 'impresoras', label: 'Impresoras', route: 'impresoras' },
  { id: 'modulos', label: 'Módulos', route: 'modulos' },
  { id: 'parametros_sistema', label: 'Parámetros del Sistema', route: 'parametros_sistema' },
  { id: 'roles', label: 'Roles', route: 'roles' },
  { id: 'permisos', label: 'Permisos', route: 'permisos' },
]

const adminEmpleadoSubmenu = [
  { id: 'empleados', label: 'Empleados', route: 'empleados' },
]

const almacenesSubmenu = [
  { id: 'insumos', label: 'Insumos', route: 'insumos' },
  { id: 'ajuste_inventario', label: 'Ajuste de Inventario', route: 'ajuste_inventario' },
  { id: 'transferencia_insumos', label: 'Transferencia de Insumos', route: 'transferencia_insumos' },
  { id: 'proveedores', label: 'Proveedores', route: 'proveedores' },
  { id: 'movimiento_insumos', label: 'Movimiento de Insumos', route: 'movimiento_insumos' },
  { id: 'entrada_almacen', label: 'Entrada de Almacén', route: 'entrada_almacen' },
  { id: 'salida_almacen', label: 'Salida de Almacén', route: 'salida_almacen' },
  { id: 'controlar_inventario_insumos', label: 'Controlar Inventario Insumos', route: 'controlar_inventario_insumos' },
  { id: 'kardex_insumos', label: 'Kardex Insumos', route: 'kardex_insumos' },
  { id: 'kardex_productos', label: 'Kardex Productos Venta', route: 'kardex_productos' },
]

const comprasSubmenu = [
  { id: 'realizar_compra', label: 'Realizar Compra', route: 'compras.realizar' },
  { id: 'reporte_compras', label: 'Reporte Compras', route: 'compras.reporte' },
]

const finanzasSubmenu = [
  { id: 'apertura_cierre_caja', label: 'Apertura Cierre de Caja', route: 'finanzas.apertura-cierre' },
  { id: 'controlar_caja', label: 'Controlar Caja', route: 'finanzas.control-caja' },
  { id: 'creditos', label: 'Créditos', route: 'finanzas.creditos' },
  { id: 'deudas', label: 'Deudas', route: 'finanzas.deudas' },
  { id: 'creditos_empleados', label: 'Créditos Empleados', route: 'finanzas.creditos-empleados' },
]

const ventasSubmenu = [
  { id: 'productos_venta', label: 'Productos Venta', route: 'ventas.productos-venta' },
  { id: 'clientes', label: 'Clientes', route: 'ventas.clientes' },
  { id: 'productos_mas_vendidos', label: 'Productos más Vendidos', route: 'ventas.productos-mas-vendidos' },
  { id: 'reporte_ventas_detalladas', label: 'Reporte Ventas Detalladas', route: 'ventas.reporte-ventas-detalladas' },
  { id: 'reporte_formulas', label: 'Reporte de Formulas', route: 'ventas.reporte-formulas' },
  { id: 'grupos_complementos', label: 'Grupos Complementos', route: 'ventas.grupos-complementos' },
]

const navItems = [
  { id: 'dashboard', label: 'Dashboard', icon: LayoutDashboard, route: 'dashboard' },
  { id: 'admin-sistema', label: 'Administración del Sistema', icon: Monitor, hasSubmenu: true },
  { id: 'admin-empleado', label: 'Administrar Empleado', icon: Users, hasSubmenu: true },
  { id: 'almacenes', label: 'Almacenes', icon: Warehouse, hasSubmenu: true },
  { id: 'compras', label: 'Compras', icon: ShoppingBag, hasSubmenu: true },
  { id: 'finanzas', label: 'Finanzas', icon: Banknote, hasSubmenu: true },
  { id: 'mantenimientos', label: 'Mantenimientos', icon: Wrench, hasSubmenu: false },
  { id: 'ventas', label: 'Ventas', icon: ReceiptText, hasSubmenu: true },
]

// Inicializar el acordeón según el módulo activo actual o lo guardado
const initOpenNav = () => {
  if (props.activeNav && validModules.includes(props.activeNav)) {
    openNav.value = props.activeNav
    try {
      localStorage.setItem(STORAGE_MODULE_KEY, props.activeNav)
      if (typeof window !== 'undefined' && window.location?.pathname) {
        localStorage.setItem(STORAGE_ROUTE_KEY, window.location.pathname)
      }
    } catch (e) {
      // Ignorar errores de localStorage si estuviera bloqueado
    }
  } else {
    try {
      const saved = localStorage.getItem(STORAGE_MODULE_KEY)
      if (saved && validModules.includes(saved)) {
        openNav.value = saved
      }
    } catch (e) {}
  }
}

// Al montar el componente
onMounted(() => {
  initOpenNav()
})

// Al cambiar props.activeNav (por navegación de Inertia)
watch(
  () => props.activeNav,
  (newNav) => {
    if (newNav && validModules.includes(newNav)) {
      openNav.value = newNav
      try {
        localStorage.setItem(STORAGE_MODULE_KEY, newNav)
        if (typeof window !== 'undefined' && window.location?.pathname) {
          localStorage.setItem(STORAGE_ROUTE_KEY, window.location.pathname)
        }
      } catch (e) {}
    }
  },
  { immediate: true }
)

// Alternar acordeón: cuando se abre uno, los demás se cierran automáticamente (orden estricto)
const toggleNav = (id) => {
  if (openNav.value === id) {
    // Si ya está abierto y se hace clic en su cabecera, se cierra
    openNav.value = null
  } else {
    // Abre solo este módulo y cierra todos los demás
    openNav.value = id
    try {
      localStorage.setItem(STORAGE_MODULE_KEY, id)
    } catch (e) {}
  }
}

// Helper para verificar si un submenú está activo (por id o por alias usado en diferentes páginas)
const isSubmenuActive = (parentNav, subId, subRoute) => {
  if (props.activeNav !== parentNav) return false
  const active = props.activeSubmenu || ''
  
  // Coincidencia directa con id o route
  if (active === subId || active === subRoute) return true
  
  // Normalizaciones para alias usados en el proyecto:
  // e.g. 'ajuste-inventario' <-> 'ajuste_inventario', 'config-caja' <-> 'configuracion_caja', 'empresa' <-> 'empresas'
  const normalizedActive = active.replace(/[-_]/g, '')
  const normalizedId = subId.replace(/[-_]/g, '')
  const normalizedRoute = subRoute.replace(/[-_.]/g, '')

  if (normalizedActive === normalizedId || normalizedActive === normalizedRoute) return true

  // Casos específicos documentados
  if (subId === 'empresas' && (active === 'empresa' || active === 'empresas')) return true
  if (subId === 'configuracion_caja' && (active === 'config-caja' || active === 'configuracion_caja')) return true
  if (subId === 'parametros_sistema' && (active === 'parametros' || active === 'parametros_sistema')) return true
  if (subId === 'entrada_almacen' && (active === 'entrada-insumos' || active === 'entrada_almacen')) return true

  // Finanzas
  if (subId === 'apertura_cierre_caja' && (active === 'apertura-cierre' || active === 'apertura_cierre_caja' || active === 'apertura-cierre-caja')) return true
  if (subId === 'controlar_caja' && (active === 'control-caja' || active === 'controlar_caja' || active === 'controlar-caja')) return true
  if (subId === 'creditos' && (active === 'creditos' || active === 'credito')) return true
  if (subId === 'deudas' && (active === 'deudas' || active === 'deuda')) return true
  if (subId === 'creditos_empleados' && (active === 'credito-empleado' || active === 'creditos-empleados' || active === 'creditos_empleados')) return true

  return false
}
</script>

<template>
  <aside class="w-56 bg-white border-r border-gray-200 flex flex-col shrink-0 z-10 select-none">
    <nav class="flex-1 py-3 px-2 space-y-0.5 overflow-y-auto">
      <!-- Dashboard -->
      <Link
        :href="route('dashboard')"
        class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-medium transition-all group"
        :class="[
          activeNav === 'dashboard'
            ? 'bg-gray-100/90 text-gray-900 font-semibold shadow-xs'
            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
        ]"
      >
        <div class="flex items-center gap-2.5 truncate">
          <LayoutDashboard
            class="w-4 h-4 shrink-0 transition-colors"
            :class="activeNav === 'dashboard' ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700'"
          />
          <span class="truncate">Dashboard</span>
        </div>
      </Link>

      <!-- 1. Administración del Sistema -->
      <div>
        <button
          type="button"
          class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-semibold transition-all group cursor-pointer"
          :class="[
            activeNav === 'admin-sistema'
              ? 'bg-gray-100/90 text-gray-900'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          ]"
          @click="toggleNav('admin-sistema')"
        >
          <div class="flex items-center gap-2.5 truncate">
            <Monitor class="w-4 h-4 text-gray-700 shrink-0" />
            <span class="truncate">Administración del Sistema</span>
          </div>
          <ChevronDown
            class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0"
            :class="{ '-rotate-90': openNav !== 'admin-sistema' }"
          />
        </button>

        <!-- Submenús de Administración del Sistema: Solo visible si openNav === 'admin-sistema' -->
        <div v-show="openNav === 'admin-sistema'" class="pl-7 pr-2 py-1 space-y-1">
          <template v-for="sub in adminSubmenu" :key="sub.id">
            <Link
              :href="route(sub.route)"
              class="w-full text-left px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors block"
              :class="[
                isSubmenuActive('admin-sistema', sub.id, sub.route)
                  ? 'text-[#F0302E] font-semibold bg-red-50/50'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              ]"
            >
              {{ sub.label }}
            </Link>
          </template>
        </div>
      </div>

      <!-- 2. Administrar Empleado -->
      <div>
        <button
          type="button"
          class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-semibold transition-all group cursor-pointer"
          :class="[
            activeNav === 'admin-empleado'
              ? 'bg-gray-100/90 text-gray-900'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          ]"
          @click="toggleNav('admin-empleado')"
        >
          <div class="flex items-center gap-2.5 truncate">
            <Users class="w-4 h-4 text-gray-700 shrink-0" />
            <span class="truncate">Administrar Empleado</span>
          </div>
          <ChevronDown
            class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0"
            :class="{ '-rotate-90': openNav !== 'admin-empleado' }"
          />
        </button>

        <!-- Submenús de Administrar Empleado: Solo visible si openNav === 'admin-empleado' -->
        <div v-show="openNav === 'admin-empleado'" class="pl-7 pr-2 py-1 space-y-1">
          <template v-for="sub in adminEmpleadoSubmenu" :key="sub.id">
            <Link
              :href="route(sub.route)"
              class="w-full text-left px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors block"
              :class="[
                isSubmenuActive('admin-empleado', sub.id, sub.route)
                  ? 'text-[#F0302E] font-semibold bg-red-50/50'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              ]"
            >
              {{ sub.label }}
            </Link>
          </template>
        </div>
      </div>

      <!-- 3. Almacenes -->
      <div>
        <button
          type="button"
          class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-semibold transition-all group cursor-pointer"
          :class="[
            activeNav === 'almacenes'
              ? 'bg-gray-100/90 text-gray-900'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          ]"
          @click="toggleNav('almacenes')"
        >
          <div class="flex items-center gap-2.5 truncate">
            <Warehouse class="w-4 h-4 text-gray-700 shrink-0" />
            <span class="truncate">Almacenes</span>
          </div>
          <ChevronDown
            class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0"
            :class="{ '-rotate-90': openNav !== 'almacenes' }"
          />
        </button>

        <!-- Submenús de Almacenes: Solo visible si openNav === 'almacenes' -->
        <div v-show="openNav === 'almacenes'" class="pl-7 pr-2 py-1 space-y-1">
          <template v-for="sub in almacenesSubmenu" :key="sub.id">
            <Link
              :href="route(sub.route)"
              class="w-full text-left px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors block"
              :class="[
                isSubmenuActive('almacenes', sub.id, sub.route)
                  ? 'text-[#F0302E] font-semibold bg-red-50/50'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              ]"
            >
              {{ sub.label }}
            </Link>
          </template>
        </div>
      </div>

      <!-- 4. Compras -->
      <div>
        <button
          type="button"
          class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-semibold transition-all group cursor-pointer"
          :class="[
            activeNav === 'compras'
              ? 'bg-gray-100/90 text-gray-900'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          ]"
          @click="toggleNav('compras')"
        >
          <div class="flex items-center gap-2.5 truncate">
            <ShoppingBag class="w-4 h-4 text-gray-700 shrink-0" />
            <span class="truncate">Compras</span>
          </div>
          <ChevronDown
            class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0"
            :class="{ '-rotate-90': openNav !== 'compras' }"
          />
        </button>

        <!-- Submenús de Compras: Solo visible si openNav === 'compras' -->
        <div v-show="openNav === 'compras'" class="pl-7 pr-2 py-1 space-y-1">
          <template v-for="sub in comprasSubmenu" :key="sub.id">
            <Link
              :href="route(sub.route)"
              class="w-full text-left px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors block"
              :class="[
                isSubmenuActive('compras', sub.id, sub.route)
                  ? 'text-[#F0302E] font-semibold bg-red-50/50'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              ]"
            >
              {{ sub.label }}
            </Link>
          </template>
        </div>
      </div>

      <!-- 5. Finanzas -->
      <div>
        <button
          type="button"
          class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-semibold transition-all group cursor-pointer"
          :class="[
            activeNav === 'finanzas'
              ? 'bg-gray-100/90 text-gray-900'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          ]"
          @click="toggleNav('finanzas')"
        >
          <div class="flex items-center gap-2.5 truncate">
            <Banknote class="w-4 h-4 text-gray-700 shrink-0" />
            <span class="truncate">Finanzas</span>
          </div>
          <ChevronDown
            class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0"
            :class="{ '-rotate-90': openNav !== 'finanzas' }"
          />
        </button>

        <!-- Submenús de Finanzas: Solo visible si openNav === 'finanzas' -->
        <div v-show="openNav === 'finanzas'" class="pl-7 pr-2 py-1 space-y-1">
          <template v-for="sub in finanzasSubmenu" :key="sub.id">
            <Link
              :href="route(sub.route)"
              class="w-full text-left px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors block"
              :class="[
                isSubmenuActive('finanzas', sub.id, sub.route)
                  ? 'text-[#F0302E] font-semibold bg-red-50/50'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              ]"
            >
              {{ sub.label }}
            </Link>
          </template>
        </div>
      </div>

      <!-- Módulo Ventas con Acordeón -->
      <div>
        <button
          type="button"
          @click="toggleNav('ventas')"
          class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-medium transition-all group"
          :class="[
            openNav === 'ventas' || activeNav === 'ventas'
              ? 'bg-[#F4F6F9] text-gray-900'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          ]"
        >
          <div class="flex items-center gap-2.5 truncate">
            <ReceiptText class="w-4 h-4 text-gray-700 shrink-0" />
            <span class="truncate">Ventas</span>
          </div>
          <ChevronDown
            class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0"
            :class="{ '-rotate-90': openNav !== 'ventas' }"
          />
        </button>

        <!-- Submenús de Ventas: Solo visible si openNav === 'ventas' -->
        <div v-show="openNav === 'ventas'" class="pl-7 pr-2 py-1 space-y-1">
          <template v-for="sub in ventasSubmenu" :key="sub.id">
            <Link
              :href="route(sub.route)"
              class="w-full text-left px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors block"
              :class="[
                isSubmenuActive('ventas', sub.id, sub.route)
                  ? 'text-[#F0302E] font-semibold bg-red-50/50'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              ]"
            >
              {{ sub.label }}
            </Link>
          </template>
        </div>
      </div>

      <!-- Otros Módulos del Sistema (Módulos sin submenú desplegable, ej: Mantenimientos) -->
      <template v-for="item in navItems.filter(item => !item.hasSubmenu && item.id !== 'dashboard')" :key="item.id">
        <button
          type="button"
          class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all group"
        >
          <div class="flex items-center gap-2.5 truncate">
            <component
              :is="item.icon"
              class="w-4 h-4 text-gray-400 group-hover:text-gray-600 shrink-0 transition-colors"
            />
            <span class="truncate">{{ item.label }}</span>
          </div>
          <ChevronLeft class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 shrink-0" />
        </button>
      </template>
    </nav>
  </aside>
</template>
