<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
})

// --- CARRUSEL STATE & LOGIC ---
const slides = [
  {
    tag: 'CONTROL OPERATIVO 360°',
    title: 'Cada mes, cada comanda, cada caja: todo en un mismo lugar.',
    description: 'Optimiza los tiempos de atención y centraliza salones, cocina y finanzas con máxima precisión.'
  },
  {
    tag: 'ATENCIÓN EN TIEMPO REAL',
    title: 'Gestión ágil de mesas, comandas y pedidos al instante.',
    description: 'Comandera móvil para mozos, comandas directas a barra/cocina y división de cuentas en segundos.'
  },
  {
    tag: 'INTELIGENCIA DE NEGOCIO',
    title: 'Reportes inteligentes y facturación electrónica sin complicaciones.',
    description: 'Monitorea tus platos más rentables, control de stock por recetas y cuadre de caja diario en vivo.'
  }
]

const currentSlide = ref(0)
let carouselInterval = null
const isHovered = ref(false)

const startCarousel = () => {
  stopCarousel()
  carouselInterval = setInterval(() => {
    if (!isHovered.value) {
      currentSlide.value = (currentSlide.value + 1) % slides.length
    }
  }, 5000)
}

const stopCarousel = () => {
  if (carouselInterval) {
    clearInterval(carouselInterval)
    carouselInterval = null
  }
}

const goToSlide = (index) => {
  currentSlide.value = index
  startCarousel()
}

// --- FORM STATE & LOGIC ---
const showPassword = ref(false)

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

onMounted(() => {
  startCarousel()
  const savedUser = localStorage.getItem('mishky_remembered_user')
  if (savedUser) {
    form.email = savedUser
    form.remember = true
  }
})

onUnmounted(() => {
  stopCarousel()
})

const handleLogin = () => {
  if (form.remember) {
    localStorage.setItem('mishky_remembered_user', form.email.trim())
  } else {
    localStorage.removeItem('mishky_remembered_user')
  }

  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Iniciar Sesión - Mishky POS" />
  <div class="login-wrapper">
    <!-- ==========================================
         LADO IZQUIERDO - CARRUSEL & BRANDING (50%)
         ========================================== -->
    <section 
      class="left-panel"
      :style="{ backgroundImage: `url('/images/fondo_login.jpg')` }"
      @mouseenter="isHovered = true"
      @mouseleave="isHovered = false"
      aria-label="Panel informativo Mishky POS"
    >
      <!-- Overlay Navy con 65% opacidad -->
      <div class="navy-overlay"></div>

      <!-- Patrón geométrico sutil de fondo -->
      <svg class="bg-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600" aria-hidden="true">
        <circle cx="300" cy="300" r="280" fill="none" stroke="rgba(255, 255, 255, 0.08)" stroke-width="1.5" stroke-dasharray="6 6" />
        <circle cx="300" cy="300" r="180" fill="none" stroke="rgba(255, 255, 255, 0.05)" stroke-width="1" />
        <circle cx="300" cy="300" r="80" fill="none" stroke="rgba(255, 255, 255, 0.04)" stroke-width="1" />
        <line x1="20" y1="300" x2="580" y2="300" stroke="rgba(255, 255, 255, 0.03)" stroke-width="1" />
        <line x1="300" y1="20" x2="300" y2="580" stroke="rgba(255, 255, 255, 0.03)" stroke-width="1" />
      </svg>

      <!-- Curva decorativa roja (#FF2D2D) orgánica que conecta hacia la derecha -->
      <!-- <svg class="decorative-curve" viewBox="0 0 160 800" preserveAspectRatio="none" aria-hidden="true">
        <path 
          d="M 160,0 C 80,140 10,280 85,440 C 145,560 130,690 160,800" 
          fill="none" 
          stroke="#FF2D2D" 
          stroke-width="3"
        />
      </svg> -->

      <!-- Rayas estéticas decorativas Mishky (Rojo, Azul, Celeste) en borde derecho -->
      <!-- <div class="brand-stripes-container">
        <div class="brand-stripe bg-red-accent"></div>
        <div class="brand-stripe bg-blue-accent"></div>
        <div class="brand-stripe bg-cyan-accent"></div>
      </div> -->

      <!-- Contenido del Carrusel -->
      <div class="carousel-container">
        <!-- Logo Principal en Blanco/Azul dentro del panel oscuro -->
        <div class="carousel-brand-header">
          <img src="/images/Logotipo_Principal_Azul.png" alt="Mishky POS" class="hero-brand-logo" />
        </div>

        <!-- Diapositivas (Transiciones de texto suaves) -->
        <div class="slides-viewport">
          <transition name="slide-fade" mode="out-in">
            <div :key="currentSlide" class="slide-content">
              <span class="slide-tag">{{ slides[currentSlide].tag }}</span>
              <h2 class="slide-title">{{ slides[currentSlide].title }}</h2>
              <p class="slide-description">{{ slides[currentSlide].description }}</p>
            </div>
          </transition>
        </div>

        <!-- Indicadores / Dots del carrusel -->
        <div class="carousel-dots" role="tablist" aria-label="Navegación del carrusel">
          <button 
            v-for="(slide, index) in slides" 
            :key="index"
            class="dot-btn"
            :class="{ active: currentSlide === index }"
            @click="goToSlide(index)"
            :aria-label="`Ir a lámina ${index + 1}`"
            :aria-selected="currentSlide === index"
            role="tab"
          ></button>
        </div>
      </div>
    </section>

    <!-- ==========================================
         LADO DERECHO - FORMULARIO LOGIN (50%)
         ========================================== -->
    <section class="right-panel" aria-label="Formulario de inicio de sesión">
      <div class="form-container">
        
        <!-- Logo Mishky POS Superior -->
        <div class="form-header-logo">
          <div class="brand-text-row">
            <img src="/images/Logotipo_Secundario.png" alt="Logo Mishky" class="mishky-logo" width="200" height="160" />
          </div>
        </div>

        <!-- Subtítulo / Instrucción -->
        <p class="form-subtitle">Bienvenido al sistema. Ingresa tus credenciales.</p>

        <!-- Mensaje de Estado / Feedback -->
        <div v-if="status" class="alert-status">
          {{ status }}
        </div>

        <!-- Formulario de Autenticación -->
        <form @submit.prevent="handleLogin" class="auth-form" novalidate>
          
          <!-- 1. Campo Correo / Usuario -->
          <div class="form-group" :class="{ 'has-error': form.errors.email }">
            <label for="email-input" class="input-label">Correo Electrónico</label>
            <div class="input-wrapper">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              <input 
                id="email-input"
                v-model="form.email" 
                type="email" 
                placeholder="ejemplo@mishkypos.com"
                autocomplete="username"
                class="form-input"
                :aria-invalid="!!form.errors.email"
                :aria-describedby="form.errors.email ? 'email-error' : null"
                required
              />
            </div>
            <span v-if="form.errors.email" id="email-error" class="error-message">
              {{ form.errors.email }}
            </span>
          </div>

          <!-- 2. Campo Contraseña -->
          <div class="form-group" :class="{ 'has-error': form.errors.password }">
            <label for="password-input" class="input-label">Contraseña</label>
            <div class="input-wrapper">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              <input 
                id="password-input"
                v-model="form.password" 
                :type="showPassword ? 'text' : 'password'" 
                placeholder="••••••••"
                autocomplete="current-password"
                class="form-input"
                :aria-invalid="!!form.errors.password"
                :aria-describedby="form.errors.password ? 'password-error' : null"
                required
              />
              <!-- Botón Toggle Visibilidad Contraseña -->
              <button 
                type="button" 
                class="password-toggle-btn"
                @click="showPassword = !showPassword"
                :title="showPassword ? 'Ocultar contraseña' : 'Ver contraseña'"
                :aria-label="showPassword ? 'Ocultar contraseña' : 'Ver contraseña'"
              >
                <svg v-if="!showPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toggle-eye-icon">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toggle-eye-icon">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                  <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
              </button>
            </div>
            <span v-if="form.errors.password" id="password-error" class="error-message">
              {{ form.errors.password }}
            </span>
          </div>

          <!-- 3. Recordar Mis Datos -->
          <div class="checkbox-container">
            <label class="custom-checkbox-label">
              <input 
                type="checkbox" 
                v-model="form.remember" 
                class="real-checkbox"
              />
              <span class="custom-checkbox-box" :class="{ checked: form.remember }">
                <svg v-if="form.remember" class="check-mark" viewBox="0 0 12 10" fill="none">
                  <path d="M1 5L4.5 8.5L11 1.5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <span class="checkbox-text">Recordar mis datos</span>
            </label>
          </div>

          <!-- 4. Botón Ingresar -->
          <button 
            type="submit" 
            class="submit-btn" 
            :disabled="form.processing"
            :class="{ loading: form.processing }"
          >
            <span v-if="!form.processing">INGRESAR</span>
            <span v-else class="flex items-center gap-2">
              <svg class="spinner-svg" viewBox="0 0 24 24">
                <circle class="spinner-track" cx="12" cy="12" r="10" fill="none" stroke-width="3"></circle>
                <circle class="spinner-circle" cx="12" cy="12" r="10" fill="none" stroke-width="3"></circle>
              </svg>
              <span>ACCEDIENDO...</span>
            </span>
          </button>
        </form>

      </div>

      <!-- Footer inferior institucional -->
      <footer class="panel-footer">
        <p>© 2026 MISHKY POS. TODOS LOS DERECHOS RESERVADOS.</p>
      </footer>
    </section>
  </div>
</template>

<style scoped>
.login-wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background-color: #FFFFFF;
  position: relative;
}

.left-panel {
  position: relative;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 60px 50px 70px 60px;
  overflow: hidden;
  user-select: none;
}

.navy-overlay {
  position: absolute;
  inset: 0;
  /* background: linear-gradient(135deg, rgba(26, 31, 58, 0.72) 0%, rgba(16, 20, 38, 0.88) 100%); */
  z-index: 1;
}

.bg-pattern {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 120%;
  height: 120%;
  pointer-events: none;
  z-index: 2;
  opacity: 0.6;
}

.decorative-curve {
  position: absolute;
  top: 0;
  right: -2px;
  width: 120px;
  height: 100%;
  pointer-events: none;
  z-index: 4;
}

.brand-stripes-container {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  display: flex;
  gap: 3px;
  z-index: 3;
  pointer-events: none;
  opacity: 0.85;
}

.brand-stripe {
  width: 3.5px;
  height: 100%;
}
.bg-red-accent { background-color: #FF2D2D; }
.bg-blue-accent { background-color: #1A3A52; }
.bg-cyan-accent { background-color: #4A90E2; }

.carousel-container {
  position: relative;
  z-index: 5;
  color: #FFFFFF;
  max-width: 520px;
}

.carousel-brand-header {
  margin-bottom: 28px;
}

.hero-brand-logo {
  height: 48px;
  width: auto;
  object-fit: contain;
  filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
}

.slides-viewport {
  min-height: 140px;
  margin-bottom: 24px;
}

.slide-tag {
  display: inline-block;
  font-family: var(--font-display);
  font-size: 11px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: #FF2D2D;
  background: rgba(255, 45, 45, 0.15);
  border: 1px solid rgba(255, 45, 45, 0.35);
  padding: 4px 10px;
  border-radius: 4px;
  margin-bottom: 12px;
}

.slide-title {
  font-family: var(--font-serif);
  font-size: 26px;
  line-height: 1.25;
  font-weight: 700;
  color: #FFFFFF;
  margin-bottom: 10px;
  letter-spacing: 0.5px;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
}

.slide-description {
  font-size: 14px;
  line-height: 1.5;
  color: #E0E0E0;
  font-weight: 300;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: opacity 300ms ease-in-out, transform 300ms ease-in-out;
}

.slide-fade-enter-from {
  opacity: 0;
  transform: translateY(6px);
}

.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}

.carousel-dots {
  display: flex;
  align-items: center;
  gap: 8px;
}

.dot-btn {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.35);
  border: none;
  cursor: pointer;
  transition: all var(--transition-fast);
  padding: 0;
}

.dot-btn:hover {
  background-color: rgba(255, 255, 255, 0.7);
}

.dot-btn.active {
  background-color: #FF2D2D;
  width: 24px;
  border-radius: 6px;
  box-shadow: 0 0 8px rgba(255, 45, 45, 0.6);
}

.right-panel {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 40px 60px;
  background-color: #FAFAF9;
  position: relative;
  overflow-y: auto;
}

.form-container {
  width: 100%;
  max-width: 450px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.form-header-logo {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 8px;
}

.brand-text-row {
  display: flex;
  align-items: center;
  gap: 12px;
}

.mishky-logo {
  max-width: 190px;
  height: auto;
  object-fit: contain;
}

.form-subtitle {
  font-size: 13.5px;
  color: #777777;
  margin-bottom: 24px;
  text-align: center;
}

.alert-status {
  width: 100%;
  background-color: #E8F8EE;
  border: 1px solid #A3E9B7;
  color: #1D6B35;
  padding: 12px 16px;
  border-radius: var(--radius-md);
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 20px;
}

.auth-form {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
  position: relative;
}

.input-label {
  font-size: 12px;
  font-weight: 500;
  color: #666666;
  margin-bottom: 6px;
  text-align: left;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.input-icon {
  position: absolute;
  left: 15px;
  width: 18px;
  height: 18px;
  color: #999999;
  pointer-events: none;
  transition: color var(--transition-fast);
}

.form-input {
  width: 100%;
  height: 45px;
  background-color: #FFFFFF;
  border: 1px solid #E0E0E0;
  border-radius: 8px;
  padding: 12px 15px 12px 45px;
  font-size: 14px;
  color: #1A1A1A;
  transition: all var(--transition-fast);
}

.form-input::placeholder {
  color: #BBBBBB;
}

.form-input:focus {
  border-color: #FF2D2D;
  box-shadow: 0 0 8px rgba(255, 45, 45, 0.2);
}

.input-wrapper:focus-within .input-icon {
  color: #FF2D2D;
}

.form-group.has-error .form-input {
  border-color: #FF3B30;
  background-color: #FFF9F9;
}

.form-group.has-error .input-icon {
  color: #FF3B30;
}

.error-message {
  font-size: 11px;
  color: #FF3B30;
  margin-top: 5px;
  text-align: left;
}

.password-toggle-btn {
  position: absolute;
  right: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4px;
  color: #999999;
  transition: color var(--transition-fast);
}

.password-toggle-btn:hover {
  color: #1A1A1A;
}

.toggle-eye-icon {
  width: 18px;
  height: 18px;
}

.checkbox-container {
  display: flex;
  align-items: center;
  margin-bottom: 28px;
}

.custom-checkbox-label {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  user-select: none;
}

.real-checkbox {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.custom-checkbox-box {
  width: 18px;
  height: 18px;
  border: 1.5px solid #D0D0D0;
  border-radius: 4px;
  background-color: #FFFFFF;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all var(--transition-fast);
}

.custom-checkbox-box.checked {
  background-color: #FF2D2D;
  border-color: #FF2D2D;
  box-shadow: 0 2px 6px rgba(255, 45, 45, 0.25);
}

.check-mark {
  width: 12px;
  height: 12px;
}

.checkbox-text {
  font-size: 13px;
  color: #333333;
  font-weight: 400;
}

.submit-btn {
  width: 100%;
  height: 48px;
  background-color: #FF2D2D;
  color: #FFFFFF;
  font-family: var(--font-display);
  font-size: 16px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color var(--transition-fast), transform var(--transition-fast), box-shadow var(--transition-fast);
  box-shadow: 0 4px 10px rgba(255, 45, 45, 0.2);
}

.submit-btn:hover:not(:disabled) {
  background-color: #E52D2D;
  box-shadow: 0 4px 14px rgba(255, 45, 45, 0.35);
}

.submit-btn:active:not(:disabled) {
  background-color: #CC2525;
  transform: scale(0.98);
}

.submit-btn:disabled {
  opacity: 0.85;
  cursor: not-allowed;
}

.spinner-svg {
  width: 24px;
  height: 24px;
  animation: spin 1s linear infinite;
}

.spinner-track {
  stroke: rgba(255, 255, 255, 0.3);
}

.spinner-circle {
  stroke: #FFFFFF;
  stroke-dasharray: 45;
  stroke-dashoffset: 15;
}

.panel-footer {
  position: absolute;
  bottom: 20px;
  text-align: center;
  width: 100%;
  left: 0;
  pointer-events: none;
}

.panel-footer p {
  font-size: 11px;
  color: #CCCCCC;
  font-weight: 400;
  letter-spacing: 0.3px;
}

@media (max-width: 850px) {
  .login-wrapper {
    grid-template-columns: 1fr;
    height: auto;
    min-height: 100vh;
    overflow-y: auto;
  }

  .left-panel {
    height: 380px;
    padding: 30px 24px;
  }

  .decorative-curve {
    display: none;
  }

  .slide-title {
    font-size: 22px;
  }

  .slide-description {
    font-size: 13px;
    max-width: 100%;
  }

  .brand-stripes-container {
    display: none;
  }

  .right-panel {
    height: auto;
    min-height: calc(100vh - 380px);
    padding: 40px 24px 60px 24px;
  }

  .form-container {
    max-width: 100%;
  }

  .panel-footer {
    position: relative;
    bottom: auto;
    margin-top: 30px;
  }
}
</style>
