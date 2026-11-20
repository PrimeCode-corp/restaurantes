<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import {
  ArrowRight,
  Check,
  ChevronDown,
  Menu,
  X,
  Calendar,
  Settings,
  TrendingUp,
  CreditCard,
  Wallet,
  Users,
  LayoutDashboard,
  Warehouse,
  ShoppingBag,
  Banknote,
  ReceiptText,
  DollarSign,
  Bell,
  UtensilsCrossed
} from 'lucide-vue-next'

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
})

const scrolled = ref(false)
const handleScroll = () => { scrolled.value = window.scrollY > 20 }
onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const activeTab = ref('dashboard')
const mobileMenuOpen = ref(false)
</script>

<template>
  <Head title="Mishky — Controla todo el servicio desde una sola pantalla">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  </Head>

  <div style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;" class="text-[#1a1a1a] scroll-smooth antialiased bg-[#F5F0E8] selection:bg-[#E63B2E] selection:text-white">

    <!-- ============================================================ -->
    <!-- NAVBAR -->
    <!-- ============================================================ -->
    <nav
      :class="[
        'fixed top-0 left-0 right-0 z-50 bg-white transition-all duration-300',
        scrolled ? 'border-b border-[#E8E3DA] shadow-sm py-1' : 'py-2'
      ]"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

          <!-- Logo Secundario de Mishky -->
          <div class="flex items-center gap-3">
            <Link href="/" class="flex items-center">
              <img
                src="/images/logo_mishky_pos.png"
                alt="Logo Mishky POS"
                class="h-10 sm:h-12 w-auto object-contain hover:opacity-90 transition-opacity"
              />
            </Link>
          </div>

          <!-- Centro nav — desktop -->
          <div class="hidden lg:flex items-center gap-6">
            <a href="#producto" class="flex items-center gap-1 text-sm font-medium text-gray-700 hover:text-[#E63B2E] transition-colors">
              Producto <ChevronDown :size="14" class="opacity-60" />
            </a>
            <a href="#servicio" class="flex items-center gap-1 text-sm font-medium text-gray-700 hover:text-[#E63B2E] transition-colors">
              Servicio <ChevronDown :size="14" class="opacity-60" />
            </a>
            <a href="#cocina" class="text-sm font-medium text-gray-700 hover:text-[#E63B2E] transition-colors">Cocina</a>
            <a href="#reservas" class="text-sm font-medium text-gray-700 hover:text-[#E63B2E] transition-colors">Reservas</a>
            <a href="#demo" class="text-sm font-medium text-gray-700 hover:text-[#E63B2E] transition-colors">Precios</a>
          </div>

          <!-- Acciones derecha — desktop -->
          <div class="hidden lg:flex items-center gap-4">
            <Link v-if="canLogin" :href="route('login')" class="text-sm font-medium text-[#1a1a1a] hover:text-[#E63B2E] transition-colors">
              Iniciar sesión
            </Link>
            <a href="#demo" class="bg-[#1a1a1a] text-white text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-black/85 transition-all shadow-sm hover:shadow">
              Solicitar demo
            </a>
          </div>

          <!-- Mobile hamburger -->
          <button
            class="lg:hidden p-2 rounded-md text-gray-700 hover:text-[#E63B2E]"
            @click="mobileMenuOpen = !mobileMenuOpen"
          >
            <Menu v-if="!mobileMenuOpen" :size="22" />
            <X v-else :size="22" />
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-if="mobileMenuOpen" class="lg:hidden bg-white border-t border-gray-100 px-4 pb-5 pt-3 space-y-2.5 shadow-lg">
        <a href="#producto" class="block py-2 text-sm font-medium text-gray-700">Producto</a>
        <a href="#servicio" class="block py-2 text-sm font-medium text-gray-700">Servicio</a>
        <a href="#cocina" class="block py-2 text-sm font-medium text-gray-700">Cocina</a>
        <a href="#reservas" class="block py-2 text-sm font-medium text-gray-700">Reservas</a>
        <Link v-if="canLogin" :href="route('login')" class="block py-2 text-sm font-medium text-gray-700 border-t border-gray-100 pt-3">
          Iniciar sesión
        </Link>
        <a href="#demo" class="block mt-2 bg-[#1a1a1a] text-white text-sm font-semibold px-5 py-2.5 rounded-full text-center">
          Solicitar demo
        </a>
      </div>
    </nav>

    <!-- ============================================================ -->
    <!-- NUEVO HERO: SECCIÓN PRODUCTO CON DASHBOARD REAL DEL SISTEMA -->
    <!-- ============================================================ -->
    <section class="pt-28 pb-20 bg-[#F5F0E8] border-b border-[#E8E3DA]" id="producto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado del Hero -->
        <div class="flex flex-col lg:flex-row gap-10 items-start justify-between mb-12">
          <div class="lg:w-7/12">
            <h1 class="text-4xl sm:text-5xl lg:text-[58px] font-extrabold leading-[1.08] tracking-[-0.035em] text-[#1a1a1a] mb-5">
              Todo lo que necesita un restaurante completo en un solo lugar.
            </h1>
            <p class="text-base sm:text-lg text-[#555555] leading-relaxed max-w-xl font-normal">
              Una plataforma integral para anfitriones, salón, cocina y administración. Facturación, ventas en tiempo real, control de almacenes y estadísticas financieras al instante.
            </p>
          </div>
          <div class="lg:w-5/12 flex flex-col sm:flex-row items-stretch sm:items-center gap-4 lg:pt-6">
            <a href="#demo" class="bg-[#1a1a1a] text-white text-sm font-semibold px-7 py-3.5 rounded-full hover:bg-black/85 transition-all text-center shadow-md">
              Solicitar demo en vivo
            </a>
            <a href="#demo" class="border-2 border-[#1a1a1a] text-[#1a1a1a] text-sm font-semibold px-6 py-3.5 rounded-full hover:bg-[#1a1a1a] hover:text-white transition-all text-center">
              Comenzar 30 días gratis
            </a>
          </div>
        </div>

        <!-- DASHBOARD MOCKUP: IDÉNTICO A LA PANTALLA DE INICIO (DASHBOARD.VUE DEL SISTEMA) -->
        <div class="bg-white rounded-2xl shadow-2xl border border-[#E8E3DA] overflow-hidden">
          
          <!-- Mockup Topbar (AppHeader real del sistema) -->
          <div class="h-12 bg-[#1A1D27] text-white flex items-center justify-between px-4 border-b border-white/10 select-none">
            <div class="flex items-center gap-3">
              <img src="/images/logo_mishky_pos.png" alt="Mishky POS" class="h-6 w-auto object-contain brightness-0 invert" />
              <div class="hidden sm:flex items-center gap-1.5 text-xs text-gray-400 border-l border-white/15 pl-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Sucursal Principal · Activa</span>
              </div>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-300">
              <span class="bg-[#2A2F45] px-2.5 py-1 rounded-md text-[11px] font-semibold text-emerald-400">Caja abierta: S/ 3,450.00</span>
              <Bell :size="16" class="text-gray-400 hover:text-white cursor-pointer" />
              <div class="w-7 h-7 rounded-full bg-[#E63B2E] text-white flex items-center justify-center font-bold text-xs">
                A
              </div>
            </div>
          </div>

          <!-- Mockup Workspace (Sidebar + Dashboard) -->
          <div class="flex flex-col lg:flex-row min-h-[520px] bg-[#F1F3F5]">
            
            <!-- Sidebar del Sistema (AppSidebar real) -->
            <div class="w-full lg:w-56 bg-[#1A1D27] text-white p-3 flex flex-col justify-between shrink-0 select-none border-r border-white/5">
              <div class="space-y-1">
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider px-2 py-1">Menú del Sistema</div>
                <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold bg-[#2A2F45] text-white shadow-xs">
                  <LayoutDashboard :size="15" class="text-[#E63B2E]" />
                  <span>Dashboard</span>
                </div>
                <div class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                  <div class="flex items-center gap-2.5">
                    <UtensilsCrossed :size="15" />
                    <span>Salón & Comandas</span>
                  </div>
                  <span class="text-[9px] bg-[#E63B2E] text-white font-bold px-1.5 py-0.2 rounded-full">12</span>
                </div>
                <div class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                  <div class="flex items-center gap-2.5">
                    <ReceiptText :size="15" />
                    <span>Ventas & Caja</span>
                  </div>
                </div>
                <div class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                  <div class="flex items-center gap-2.5">
                    <Warehouse :size="15" />
                    <span>Almacenes</span>
                  </div>
                </div>
                <div class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                  <div class="flex items-center gap-2.5">
                    <ShoppingBag :size="15" />
                    <span>Compras</span>
                  </div>
                </div>
                <div class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                  <div class="flex items-center gap-2.5">
                    <Banknote :size="15" />
                    <span>Finanzas</span>
                  </div>
                </div>
                <div class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                  <div class="flex items-center gap-2.5">
                    <Users :size="15" />
                    <span>Personal</span>
                  </div>
                </div>
              </div>
              <div class="pt-4 border-t border-white/10 px-2 text-[10px] text-gray-500">
                Mishky POS v2.4 Enterprise
              </div>
            </div>

            <!-- Contenido Principal: El Dashboard real del sistema -->
            <div class="flex-1 p-4 sm:p-6 overflow-y-auto space-y-4">
              
              <!-- Dashboard Header interno -->
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                  <h2 class="text-lg sm:text-xl font-bold tracking-tight text-gray-900">
                    ¡Bienvenid@ al Dashboard!
                  </h2>
                  <p class="text-xs text-gray-500">Métricas consolidadas de hoy · Viernes de alta concurrencia</p>
                </div>

                <div class="flex items-center gap-2">
                  <div class="flex items-center gap-2 bg-white border border-gray-300 rounded-md px-3 py-1.5 text-xs text-gray-700 shadow-2xs">
                    <Calendar class="w-3.5 h-3.5 text-gray-500" />
                    <span>01/10/2026 - 31/10/2026</span>
                  </div>
                  <div class="bg-white border border-gray-300 rounded-md px-3 py-1.5 text-xs text-gray-800 font-semibold shadow-2xs flex items-center gap-1.5">
                    <span>CAJA PRINCIPAL</span>
                    <ChevronDown class="w-3.5 h-3.5 text-gray-400" />
                  </div>
                </div>
              </div>

              <!-- Fila 1: Gráfico Visión General + 3 KPIs en columna (Igual a Dashboard.vue) -->
              <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                
                <!-- Gráfico de ventas (Line Chart con SVG) -->
                <div class="lg:col-span-3 bg-white border border-gray-200/90 shadow-xs rounded-xl p-4 sm:p-5 flex flex-col justify-between">
                  <div>
                    <div class="flex items-center justify-between mb-3">
                      <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-gray-900">Visión General</span>
                        <span class="text-[11px] bg-red-50 text-[#E63B2E] font-bold px-2 py-0.5 rounded-full">+18.4% vs mes anterior</span>
                      </div>
                      <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5 font-medium text-gray-700">
                          <span class="w-2.5 h-2.5 rounded-full bg-[#E63B2E]"></span>
                          Venta Diaria
                        </span>
                        <Settings class="w-3.5 h-3.5 text-gray-400" />
                      </div>
                    </div>

                    <!-- Gráfico SVG renderizado con curva -->
                    <div class="w-full h-44 sm:h-48 relative">
                      <svg class="w-full h-full overflow-visible" viewBox="0 0 650 180" preserveAspectRatio="none">
                        <!-- Líneas guía horizontales -->
                        <line x1="45" y1="20" x2="630" y2="20" stroke="#f0f0f0" stroke-dasharray="3 3" />
                        <line x1="45" y1="65" x2="630" y2="65" stroke="#f0f0f0" stroke-dasharray="3 3" />
                        <line x1="45" y1="110" x2="630" y2="110" stroke="#f0f0f0" stroke-dasharray="3 3" />
                        <line x1="45" y1="155" x2="630" y2="155" stroke="#f0f0f0" />
                        
                        <!-- Etiquetas de eje Y -->
                        <text x="5" y="24" font-size="9" fill="#9ca3af" font-weight="600">S/ 3.5k</text>
                        <text x="5" y="69" font-size="9" fill="#9ca3af" font-weight="600">S/ 2.5k</text>
                        <text x="5" y="114" font-size="9" fill="#9ca3af" font-weight="600">S/ 1.5k</text>
                        <text x="15" y="159" font-size="9" fill="#9ca3af" font-weight="600">S/ 0</text>

                        <!-- Área de relleno de la curva -->
                        <defs>
                          <linearGradient id="gradientVenta" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#E63B2E" stop-opacity="0.25" />
                            <stop offset="100%" stop-color="#E63B2E" stop-opacity="0.0" />
                          </linearGradient>
                        </defs>
                        <polygon
                          points="45,155 77,100 110,50 142,95 175,130 207,110 240,90 272,85 305,35 337,45 370,65 402,125 435,105 467,85 500,80 532,40 565,45 597,60 630,130 630,155 45,155"
                          fill="url(#gradientVenta)"
                        />

                        <!-- Línea de tendencia -->
                        <polyline
                          fill="none"
                          stroke="#E63B2E"
                          stroke-width="2.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          points="45,155 77,100 110,50 142,95 175,130 207,110 240,90 272,85 305,35 337,45 370,65 402,125 435,105 467,85 500,80 532,40 565,45 597,60 630,130"
                        />

                        <!-- Puntos destacados -->
                        <circle cx="305" cy="35" r="4" fill="#E63B2E" stroke="#ffffff" stroke-width="2" />
                        <circle cx="532" cy="40" r="4" fill="#E63B2E" stroke="#ffffff" stroke-width="2" />
                      </svg>
                    </div>

                    <!-- Eje X etiquetas -->
                    <div class="flex justify-between pl-8 pr-2 pt-2 text-[9.5px] font-semibold text-gray-400">
                      <span>1 JUE</span>
                      <span>5 LUN</span>
                      <span>9 VIE</span>
                      <span>13 MAR</span>
                      <span>17 SAB</span>
                      <span>21 MIE</span>
                    </div>
                  </div>
                </div>

                <!-- Columna de 3 KPIs (Cards del sistema) -->
                <div class="flex flex-col gap-3">
                  <!-- KPI 1 -->
                  <div class="bg-white border border-gray-200/90 shadow-xs rounded-xl p-4 flex items-center justify-between">
                    <div>
                      <div class="text-[11px] font-bold text-gray-500 uppercase tracking-tight">Total Ventas (Mes)</div>
                      <div class="text-xl font-black text-gray-900 mt-0.5">S/ 48,290.00</div>
                      <div class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                        <TrendingUp :size="11" /> +12% sobre el objetivo
                      </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-50 text-[#E63B2E] flex items-center justify-center">
                      <DollarSign :size="18" />
                    </div>
                  </div>

                  <!-- KPI 2 -->
                  <div class="bg-white border border-gray-200/90 shadow-xs rounded-xl p-4 flex items-center justify-between">
                    <div>
                      <div class="text-[11px] font-bold text-gray-500 uppercase tracking-tight">Cobros Tarjeta / POS</div>
                      <div class="text-xl font-black text-gray-900 mt-0.5">S/ 31,450.00</div>
                      <div class="text-[10px] text-gray-400 font-medium mt-1">65% del volumen total</div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                      <CreditCard :size="18" />
                    </div>
                  </div>

                  <!-- KPI 3 -->
                  <div class="bg-white border border-gray-200/90 shadow-xs rounded-xl p-4 flex items-center justify-between">
                    <div>
                      <div class="text-[11px] font-bold text-gray-500 uppercase tracking-tight">Efectivo en Caja</div>
                      <div class="text-xl font-black text-gray-900 mt-0.5">S/ 16,840.00</div>
                      <div class="text-[10px] text-emerald-600 font-semibold mt-1">Cuadre sin descuadres</div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                      <Wallet :size="18" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Fila 2: Donut Chart de Productos Más Vendidos + Top Mozos -->
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                
                <!-- Donut Chart Platillos más vendidos (exacto al sistema) -->
                <div class="lg:col-span-2 bg-white border border-gray-200/90 shadow-xs rounded-xl p-4 sm:p-5">
                  <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-gray-900">Productos Más Vendidos</span>
                    <span class="text-xs text-gray-400 font-medium">Top 5 platos del restaurante</span>
                  </div>
                  
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                    <!-- Donut SVG ilustrativo -->
                    <div class="relative w-36 h-36 mx-auto flex items-center justify-center">
                      <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#4361EE" stroke-width="16" stroke-dasharray="75 163" stroke-dashoffset="0" />
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#7209B7" stroke-width="16" stroke-dasharray="55 183" stroke-dashoffset="-75" />
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#E63B2E" stroke-width="16" stroke-dasharray="45 193" stroke-dashoffset="-130" />
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#F48C06" stroke-width="16" stroke-dasharray="35 203" stroke-dashoffset="-175" />
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#2D6A4F" stroke-width="16" stroke-dasharray="28 210" stroke-dashoffset="-210" />
                      </svg>
                      <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-[10px] text-gray-400 font-bold uppercase">Total</span>
                        <span class="text-sm font-black text-gray-900">2,840</span>
                      </div>
                    </div>

                    <!-- Leyenda platos -->
                    <div class="space-y-2 text-xs">
                      <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-sm bg-[#4361EE]"></span>Ceviche Clásico</span>
                        <span class="font-bold text-gray-900">480 unds (28%)</span>
                      </div>
                      <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-sm bg-[#7209B7]"></span>Arroz con Mariscos</span>
                        <span class="font-bold text-gray-900">390 unds (22%)</span>
                      </div>
                      <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-sm bg-[#E63B2E]"></span>Lomo Saltado Criollo</span>
                        <span class="font-bold text-gray-900">310 unds (18%)</span>
                      </div>
                      <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-sm bg-[#F48C06]"></span>Chicharrón de Pescado</span>
                        <span class="font-bold text-gray-900">280 unds (16%)</span>
                      </div>
                      <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-sm bg-[#2D6A4F]"></span>Bebidas & Postres</span>
                        <span class="font-bold text-gray-900">240 unds (14%)</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Ventas por personal de salón -->
                <div class="bg-white border border-gray-200/90 shadow-xs rounded-xl p-4 sm:p-5 flex flex-col justify-between">
                  <div>
                    <div class="flex items-center justify-between mb-3">
                      <span class="text-sm font-bold text-gray-900">Ventas por Mozo</span>
                      <span class="text-xs text-emerald-600 font-semibold">En turno</span>
                    </div>
                    <div class="space-y-2.5 text-xs">
                      <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                          <div class="w-6 h-6 rounded-full bg-gray-100 text-[#1a1a1a] font-bold flex items-center justify-center text-[10px]">C</div>
                          <span class="font-semibold text-gray-800">Carlos Vega</span>
                        </div>
                        <span class="font-bold text-gray-900">S/ 4,210.00</span>
                      </div>
                      <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                          <div class="w-6 h-6 rounded-full bg-gray-100 text-[#1a1a1a] font-bold flex items-center justify-center text-[10px]">M</div>
                          <span class="font-semibold text-gray-800">María Mendoza</span>
                        </div>
                        <span class="font-bold text-gray-900">S/ 3,890.00</span>
                      </div>
                      <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                          <div class="w-6 h-6 rounded-full bg-gray-100 text-[#1a1a1a] font-bold flex items-center justify-center text-[10px]">J</div>
                          <span class="font-semibold text-gray-800">Jorge Quispe</span>
                        </div>
                        <span class="font-bold text-gray-900">S/ 3,140.00</span>
                      </div>
                    </div>
                  </div>
                  <div class="pt-2 text-[11px] text-gray-400 text-center font-medium">
                    Actualización continua de pedidos cobrados
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ============================================================ -->
    <!-- ANTIGUO HERO: AHORA UBICADO EN LA SECCIÓN SECUNDARIA -->
    <!-- ============================================================ -->
    <section class="min-h-screen flex flex-col lg:flex-row bg-white border-b border-[#E8E3DA]" id="servicio">

      <!-- Left side — blanco / cálido -->
      <div class="bg-white w-full lg:w-[48%] flex flex-col justify-center px-8 sm:px-14 lg:px-20 py-16 lg:py-24">
        <!-- Badge -->
        <div class="inline-flex mb-8">
          <span class="inline-flex items-center gap-2 border border-[#E63B2E] text-[#E63B2E] text-xs font-semibold px-3.5 py-1.5 rounded-full hover:bg-[#FFF0EF] transition-colors cursor-pointer">
            <span class="w-1.5 h-1.5 rounded-full bg-[#E63B2E] animate-pulse"></span>
            Nuevo · Salón en vivo & Kitchen Screen →
          </span>
        </div>

        <!-- H1 con tipografía exacta y sólida -->
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.1] tracking-[-0.035em] text-[#1a1a1a] mb-6">
          Gestiona todo el servicio desde una sola pantalla.
        </h2>

        <!-- Sub -->
        <p class="text-base sm:text-lg text-[#555555] leading-relaxed max-w-md mb-8 font-normal">
          Reservas, piso en vivo y turnos de personal para restaurantes de alto flujo. Tu anfitrión, tu pase de cocina y tu oficina por fin coordinan en la misma noche.
        </p>

        <!-- Puntos destacados -->
        <div class="space-y-3 mb-10">
          <div class="flex items-center gap-3 text-sm font-semibold text-[#1a1a1a]">
            <span class="w-5 h-5 rounded-full bg-[#E63B2E] text-white flex items-center justify-center text-xs">✓</span>
            Mapeo interactivo de mesas con tiempo de ocupación
          </div>
          <div class="flex items-center gap-3 text-sm font-semibold text-[#1a1a1a]">
            <span class="w-5 h-5 rounded-full bg-[#E63B2E] text-white flex items-center justify-center text-xs">✓</span>
            Comandera táctil con envío automático a comandera de cocina
          </div>
          <div class="flex items-center gap-3 text-sm font-semibold text-[#1a1a1a]">
            <span class="w-5 h-5 rounded-full bg-[#E63B2E] text-white flex items-center justify-center text-xs">✓</span>
            Cálculo de turnos y personal según reservas esperadas
          </div>
        </div>

        <!-- CTA buttons -->
        <div class="flex flex-wrap items-center gap-5">
          <a href="#demo"
            class="inline-flex items-center gap-2.5 bg-[#1a1a1a] text-white text-sm font-semibold px-6 py-3.5 rounded-full hover:bg-black/85 transition-all shadow-md hover:shadow-lg"
          >
            Solicitar demo
            <span class="bg-[#E63B2E] rounded-full w-5 h-5 flex items-center justify-center">
              <ArrowRight :size="11" class="text-white" />
            </span>
          </a>
          <a href="#demo" class="text-sm font-semibold text-[#1a1a1a] underline underline-offset-4 hover:text-[#E63B2E] transition-colors">
            Comenzar 30 días gratis
          </a>
        </div>
      </div>

      <!-- Right side — diseño ambiental original nocturno de restaurante con linternas cálidas -->
      <div
        class="w-full lg:w-[52%] min-h-[480px] lg:min-h-0 relative flex items-center justify-center overflow-hidden"
        style="background: #100c08;"
      >
        <!-- Luces ambientales y linternas doradas -->
        <div class="absolute inset-0 pointer-events-none">
          <div style="position:absolute;top:8%;left:18%;width:320px;height:320px;background:radial-gradient(circle, rgba(255,180,80,0.12) 0%, transparent 70%);border-radius:50%;"></div>
          <div style="position:absolute;top:45%;right:8%;width:280px;height:280px;background:radial-gradient(circle, rgba(230,120,40,0.10) 0%, transparent 70%);border-radius:50%;"></div>
          <div style="position:absolute;bottom:10%;left:25%;width:240px;height:240px;background:radial-gradient(circle, rgba(255,160,50,0.08) 0%, transparent 70%);border-radius:50%;"></div>
          
          <div style="position:absolute;top:12%;left:22%;width:38px;height:38px;border-radius:50%;background:radial-gradient(circle, #FFE2B0 0%, #E8A040 45%, transparent 70%);filter:blur(0.5px);opacity:0.9;"></div>
          <div style="position:absolute;top:18%;left:52%;width:32px;height:32px;border-radius:50%;background:radial-gradient(circle, #FFE2B0 0%, #E8A040 45%, transparent 70%);filter:blur(0.5px);opacity:0.85;"></div>
          <div style="position:absolute;top:10%;left:76%;width:36px;height:36px;border-radius:50%;background:radial-gradient(circle, #FFE2B0 0%, #E8A040 45%, transparent 70%);filter:blur(0.5px);opacity:0.9;"></div>
          
          <div style="position:absolute;bottom:22%;left:12%;width:88px;height:88px;border-radius:50%;background:radial-gradient(circle at 40% 35%, rgba(90,55,25,0.7), rgba(40,25,10,0.9));border:1px solid rgba(120,80,30,0.35);"></div>
          <div style="position:absolute;bottom:18%;left:40%;width:110px;height:110px;border-radius:50%;background:radial-gradient(circle at 40% 35%, rgba(90,55,25,0.7), rgba(40,25,10,0.9));border:1px solid rgba(120,80,30,0.35);"></div>
        </div>

        <!-- Tarjeta UI flotante (Live Floor Tonight) -->
        <div class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-[320px] mx-6 p-5 border border-gray-100/50">
          <!-- Header -->
          <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-gray-100">
            <div>
              <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Esta noche · Salón principal</div>
            </div>
            <span class="flex items-center gap-1 text-[11px] text-[#2D6A4F] font-bold">
              <span class="w-2 h-2 bg-[#2D6A4F] rounded-full animate-pulse"></span>
              En vivo
            </span>
          </div>

          <!-- Stats row -->
          <div class="grid grid-cols-3 gap-2 mb-4 pb-3 border-b border-gray-100">
            <div class="text-center">
              <div class="text-xl font-extrabold text-[#1a1a1a]">142</div>
              <div class="text-[10px] text-gray-500 tracking-tight">cubiertos</div>
            </div>
            <div class="text-center border-x border-gray-100">
              <div class="text-xl font-extrabold text-[#1a1a1a]">18</div>
              <div class="text-[10px] text-gray-500 tracking-tight">en espera</div>
            </div>
            <div class="text-center">
              <div class="text-xl font-extrabold text-[#1a1a1a]">0</div>
              <div class="text-[10px] text-gray-500 tracking-tight">no-shows</div>
            </div>
          </div>

          <!-- Booking rows -->
          <div class="space-y-2 mb-4">
            <div class="flex items-center justify-between py-0.5">
              <span class="text-xs text-[#1a1a1a] font-semibold">20:15 Moreau <span class="font-normal text-gray-500">· 2</span></span>
              <span class="bg-[#FEE9E8] text-[#E63B2E] text-[10px] font-bold px-2 py-0.5 rounded-full">Tardó 6 min</span>
            </div>
            <div class="flex items-center justify-between py-0.5">
              <span class="text-xs text-[#1a1a1a] font-semibold">20:30 Tanaka <span class="font-normal text-gray-500">· 3</span></span>
              <span class="bg-[#E8F0E9] text-[#2D6A4F] text-[10px] font-bold px-2 py-0.5 rounded-full">Reservado</span>
            </div>
            <div class="flex items-center justify-between py-0.5">
              <span class="text-xs text-[#1a1a1a] font-semibold">Ahora Walk-in <span class="font-normal text-gray-500">· 2</span></span>
              <span class="bg-[#FFF6E0] text-[#B87A00] text-[10px] font-bold px-2 py-0.5 rounded-full">Mesa 7 · 6 min</span>
            </div>
          </div>

          <!-- Bottom stat -->
          <div class="bg-[#F5F0E8] rounded-xl px-4 py-2.5 text-center flex items-center justify-center gap-1.5">
            <span class="text-xs font-bold text-[#1a1a1a]">11 s</span>
            <span class="text-[11px] text-gray-600">para sentar a un cliente sin reserva</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats bar -->
    <div class="bg-[#F5F0E8] border-b border-[#E8E3DA]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:divide-x sm:divide-[#dcd4c6]">
          <div class="text-center sm:text-left sm:pl-8 first:pl-0">
            <span class="text-2xl font-black text-[#1a1a1a] tracking-tight">-38%</span>
            <span class="text-sm text-gray-600 ml-2 font-medium">menos mesas perdidas (no-shows)</span>
          </div>
          <div class="text-center sm:pl-8">
            <span class="text-2xl font-black text-[#1a1a1a] tracking-tight">11s</span>
            <span class="text-sm text-gray-600 ml-2 font-medium">en asignar mesa a clientes directos</span>
          </div>
          <div class="text-center sm:pl-8">
            <span class="text-2xl font-black text-[#1a1a1a] tracking-tight">2h</span>
            <span class="text-sm text-gray-600 ml-2 font-medium">ahorradas en planificar turnos cada semana</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- SECTION: PANTALLA DE COCINA Y PASE -->
    <!-- ============================================================ -->
    <section class="bg-white py-24 border-b border-[#E8E3DA]" id="cocina">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12 items-stretch">

          <!-- Left — Dark kitchen art -->
          <div class="lg:w-1/2 relative">
            <div
              class="relative rounded-2xl overflow-hidden h-[400px] lg:h-full min-h-[400px]"
              style="background: #181512;"
            >
              <div class="absolute inset-0">
                <div style="position:absolute;top:0;left:50%;transform:translateX(-50%);width:260px;height:140px;background:radial-gradient(ellipse, rgba(255,210,100,0.18) 0%, transparent 70%);"></div>
                <div style="position:absolute;bottom:75px;left:0;right:0;height:3px;background:rgba(255,255,255,0.08);"></div>
                <div style="position:absolute;bottom:75px;left:50%;transform:translateX(-50%);width:70px;height:160px;background:rgba(255,255,255,0.06);border-radius:35px 35px 0 0;"></div>
                <div style="position:absolute;bottom:230px;left:50%;transform:translateX(-50%);width:40px;height:40px;background:rgba(255,255,255,0.09);border-radius:50%;"></div>
                <div style="position:absolute;bottom:80px;left:50%;transform:translateX(-50%);width:90px;height:16px;background:rgba(255,255,255,0.12);border-radius:50%;"></div>
                <div style="position:absolute;bottom:90px;left:50%;transform:translateX(-50%);width:46px;height:22px;background:radial-gradient(circle, #E63B2E, #8B1A00);border-radius:6px;"></div>
              </div>

              <!-- Floating ticket card -->
              <div class="absolute top-5 right-5 bg-white rounded-xl shadow-lg p-3.5 w-56 border border-gray-100">
                <div class="text-xs font-bold text-[#1a1a1a] mb-0.5">Mesa 12 · 6 comensales</div>
                <div class="text-[10px] text-[#E63B2E] font-semibold mb-1">1 alergia a frutos secos — comensal 4</div>
                <div class="flex items-center justify-between text-[10px] text-gray-500 pt-1 border-t border-gray-100">
                  <span>En comanda: Platos de fondo</span>
                  <span class="text-[#2D6A4F] font-bold">11 min</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right — text -->
          <div class="lg:w-1/2 flex flex-col justify-center">
            <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-4">Pantalla de cocina</div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#1a1a1a] leading-tight mb-8 tracking-[-0.03em]">
              El pase de cocina ve lo que ve la sala
            </h2>

            <div class="space-y-6">
              <div class="flex gap-4">
                <div class="mt-1">
                  <ArrowRight :size="16" class="text-[#E63B2E]" />
                </div>
                <div>
                  <div class="font-bold text-[#1a1a1a] mb-1">Alergias en cada comanda</div>
                  <div class="text-sm text-gray-600">Las notas de la reserva se imprimen y se muestran en la pantalla del chef por asiento.</div>
                </div>
              </div>
              <div class="flex gap-4">
                <div class="mt-1">
                  <ArrowRight :size="16" class="text-[#E63B2E]" />
                </div>
                <div>
                  <div class="font-bold text-[#1a1a1a] mb-1">Tiempos de preparación controlados</div>
                  <div class="text-sm text-gray-600">El jefe de cocina ve qué mesas esperan su plato fuerte y cuántos minutos llevan con el entrante.</div>
                </div>
              </div>
              <div class="flex gap-4">
                <div class="mt-1">
                  <ArrowRight :size="16" class="text-[#E63B2E]" />
                </div>
                <div>
                  <div class="font-bold text-[#1a1a1a] mb-1">Agotar un plato en un solo toque (86)</div>
                  <div class="text-sm text-gray-600">¿Se agotó un producto? Los mozos se enteran de inmediato antes de tomar la siguiente orden.</div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- SECTION: RESERVAS Y CLIENTES -->
    <!-- ============================================================ -->
    <section class="bg-[#F5F0E8] py-24 border-b border-[#E8E3DA]" id="reservas">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-16 items-center">

          <!-- Left text -->
          <div class="lg:w-1/2">
            <div class="text-[10px] font-bold text-[#E63B2E] tracking-widest uppercase mb-4">01 · Reservas</div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#1a1a1a] leading-tight mb-6 tracking-[-0.03em]">
              Reservas que aprovechan cada espacio
            </h2>
            <p class="text-base text-gray-500 leading-relaxed mb-8">
              Tus comensales reservan desde tu página, Google o redes sociales. Los depósitos de confirmación aseguran las mesas grupales y la lista de espera notifica por SMS apenas se libere un lugar.
            </p>
            <ul class="space-y-4">
              <li class="flex gap-3">
                <ArrowRight :size="16" class="text-[#E63B2E] mt-0.5 shrink-0" />
                <div>
                  <div class="text-sm font-semibold text-[#1a1a1a]">Reserva desde cualquier canal.</div>
                  <div class="text-sm text-gray-500">Tu web, Instagram y ficha de Google.</div>
                </div>
              </li>
              <li class="flex gap-3">
                <ArrowRight :size="16" class="text-[#E63B2E] mt-0.5 shrink-0" />
                <div>
                  <div class="text-sm font-semibold text-[#1a1a1a]">Seña y adelantos para grupos.</div>
                  <div class="text-sm text-gray-500">Reduce drásticamente las mesas no asistidas.</div>
                </div>
              </li>
              <li class="flex gap-3">
                <ArrowRight :size="16" class="text-[#E63B2E] mt-0.5 shrink-0" />
                <div>
                  <div class="text-sm font-semibold text-[#1a1a1a]">Lista de espera automatizada.</div>
                  <div class="text-sm text-gray-500">Tus clientes esperan en la barra, no incómodos en la puerta.</div>
                </div>
              </li>
            </ul>
          </div>

          <!-- Right — Phone mockup -->
          <div class="lg:w-1/2 flex flex-col items-center gap-4">
            <div class="relative">
              <div class="bg-gray-900 rounded-[2.5rem] border-8 border-gray-900 shadow-2xl w-72 overflow-hidden">
                <div class="bg-white">
                  <div class="bg-[#1a1a1a] px-4 pt-5 pb-4">
                    <div class="text-white font-bold text-base">Restaurante Mishky</div>
                    <div class="flex items-center gap-1 mt-1">
                      <span class="text-yellow-400 text-xs">★★★★★</span>
                      <span class="text-gray-400 text-[10px] ml-1">4.9</span>
                    </div>
                  </div>

                  <div class="p-4">
                    <div class="flex gap-2 mb-4">
                      <button class="flex-1 bg-[#1a1a1a] text-white text-xs font-semibold py-2 rounded-lg">2 personas</button>
                      <button class="flex-1 border border-gray-200 text-gray-600 text-xs font-semibold py-2 rounded-lg">6+ personas</button>
                    </div>

                    <div class="text-xs font-semibold text-[#1a1a1a] mb-3">Viernes 17 Octubre</div>

                    <div class="grid grid-cols-3 gap-2 mb-4">
                      <button class="border border-gray-200 rounded-lg py-2 text-xs font-medium text-gray-700 hover:border-[#1a1a1a]">20:05</button>
                      <button class="border border-gray-200 rounded-lg py-2 text-xs font-medium text-gray-700 bg-gray-50 font-bold border-[#1a1a1a]">21:00</button>
                      <button class="border border-gray-200 rounded-lg py-2 text-xs font-medium text-gray-700 hover:border-[#1a1a1a]">21:30</button>
                      <button class="border border-gray-200 rounded-lg py-2 text-xs font-medium text-gray-700 hover:border-[#1a1a1a]">22:05</button>
                      <button class="border border-gray-200 rounded-lg py-2 text-xs font-medium text-gray-700 hover:border-[#1a1a1a]">22:19</button>
                      <button class="border-2 border-dashed border-gray-300 rounded-lg py-2 text-xs font-medium text-gray-400">Espera</button>
                    </div>

                    <button class="w-full bg-[#E63B2E] text-white text-xs font-bold py-3 rounded-xl hover:bg-[#d02f24] transition-colors shadow">
                      Reservar 21:00 para 2
                    </button>
                  </div>
                </div>
              </div>

              <!-- Notificaciones flotantes -->
              <div class="absolute -right-4 top-16 w-56 bg-white rounded-xl shadow-xl border border-gray-100 p-3">
                <div class="text-[10px] font-bold text-[#1a1a1a] mb-0.5">Nueva reserva · Familia Vega · 4</div>
                <div class="text-[9px] text-gray-500">20:30 · desde Google Maps</div>
              </div>
              <div class="absolute -left-4 bottom-16 w-60 bg-white rounded-xl shadow-xl border border-gray-100 p-3">
                <div class="text-[10px] font-bold text-[#2D6A4F] mb-0.5">SMS Enviado ✓</div>
                <div class="text-[9px] text-gray-500">Tu mesa en Mishky está lista. ¿Llegas en 8 min?</div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- CTA FOOTER -->
    <!-- ============================================================ -->
    <section class="bg-[#1a1a1a] py-28 text-center" id="demo">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6 tracking-[-0.03em]">
          ¿Listo para un servicio sin fricciones?
        </h2>
        <p class="text-base text-gray-400 mb-10 max-w-lg mx-auto">
          Únete a los restaurantes gastronómicos que ya impulsan su operación con Mishky.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
          <a href="#demo"
            class="bg-[#E63B2E] text-white text-sm font-semibold px-7 py-3.5 rounded-full hover:bg-[#d02f24] transition-all shadow-md"
          >
            Solicitar una demo
          </a>
          <a href="#demo"
            class="border border-white/30 text-white text-sm font-semibold px-7 py-3.5 rounded-full hover:bg-white hover:text-[#1a1a1a] transition-all"
          >
            Comenzar 30 días gratis
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#111111] py-10 border-t border-white/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
          <div class="flex items-center gap-2">
            <img src="/images/logo_mishky_pos.png" alt="Mishky Logo" class="h-8 w-auto object-contain brightness-0 invert" />
          </div>
          <span class="text-xs text-gray-500">© 2026 Mishky POS. Todos los derechos reservados.</span>
          <div class="flex gap-6">
            <a href="#" class="text-xs text-gray-400 hover:text-white transition-colors">Privacidad</a>
            <a href="#" class="text-xs text-gray-400 hover:text-white transition-colors">Términos</a>
            <a href="#" class="text-xs text-gray-400 hover:text-white transition-colors">Contacto</a>
          </div>
        </div>
      </div>
    </footer>

  </div>
</template>
