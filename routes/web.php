<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Área de ingreso y Dashboard
    Route::get('/area_ingreso', function () {
        return Inertia::render('AreaIngreso');
    })->name('area_ingreso');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Administración del Sistema
    Route::get('/empresas', function () {
        return Inertia::render('Empresas');
    })->name('empresas');

    Route::get('/sucursales', function () {
        return Inertia::render('Sucursales');
    })->name('sucursales');

    Route::get('/configuracion_caja', function () {
        return Inertia::render('ConfiguracionCaja');
    })->name('configuracion_caja');

    Route::get('/impresoras', function () {
        return Inertia::render('Impresoras');
    })->name('impresoras');

    Route::get('/modulos', function () {
        return Inertia::render('Modulos');
    })->name('modulos');

    Route::get('/parametros_sistema', function () {
        return Inertia::render('ParametrosSistema');
    })->name('parametros_sistema');

    Route::get('/roles', function () {
        return Inertia::render('Roles');
    })->name('roles');

    Route::get('/permisos', function () {
        return Inertia::render('Permisos');
    })->name('permisos');

    // Administrar Empleado
    Route::get('/empleados', function () {
        return Inertia::render('Empleados');
    })->name('empleados');

    // Almacenes
    Route::get('/insumos', function () {
        return Inertia::render('Insumos');
    })->name('insumos');

    Route::get('/ajuste_inventario', function () {
        return Inertia::render('AjusteInventario');
    })->name('ajuste_inventario');

    Route::get('/transferencia_insumos', function () {
        return Inertia::render('TransferenciaInsumos');
    })->name('transferencia_insumos');

    Route::get('/proveedores', function () {
        return Inertia::render('Proveedores');
    })->name('proveedores');

    Route::get('/movimiento_insumos', function () {
        return Inertia::render('MovimientoInsumos');
    })->name('movimiento_insumos');

    Route::get('/entrada_almacen', function () {
        return Inertia::render('EntradaAlmacen');
    })->name('entrada_almacen');

    Route::get('/salida_almacen', function () {
        return Inertia::render('SalidaAlmacen');
    })->name('salida_almacen');

    Route::get('/controlar_inventario_insumos', function () {
        return Inertia::render('ControlarInventarioInsumos');
    })->name('controlar_inventario_insumos');

    Route::get('/kardex_insumos', function () {
        return Inertia::render('KardexInsumos');
    })->name('kardex_insumos');

    Route::get('/kardex_productos', function () {
        return Inertia::render('KardexProductos');
    })->name('kardex_productos');

    // Compras
    Route::get('/compras/realizar', function () {
        return Inertia::render('RealizarCompra');
    })->name('compras.realizar');

    Route::get('/compras/reporte', function () {
        return Inertia::render('ReporteCompras');
    })->name('compras.reporte');

    // Finanzas
    Route::get('/finanzas/apertura-cierre', function () {
        return Inertia::render('FinanzasAperturaCierre');
    })->name('finanzas.apertura-cierre');

    Route::get('/finanzas/control-caja', function () {
        return Inertia::render('FinanzasControlCaja');
    })->name('finanzas.control-caja');

    Route::get('/finanzas/creditos', function () {
        return Inertia::render('FinanzasCreditos');
    })->name('finanzas.creditos');

    Route::get('/finanzas/deudas', function () {
        return Inertia::render('FinanzasDeudas');
    })->name('finanzas.deudas');

    Route::get('/finanzas/creditos-empleados', function () {
        return Inertia::render('FinanzasCreditosEmpleados');
    })->name('finanzas.creditos-empleados');

    // Ventas
    Route::get('/ventas/productos-venta', function () {
        return Inertia::render('VentasProductosVenta');
    })->name('ventas.productos-venta');

    Route::get('/ventas/clientes', function () {
        return Inertia::render('VentasClientes');
    })->name('ventas.clientes');

    Route::get('/ventas/productos-mas-vendidos', function () {
        return Inertia::render('VentasProductosMasVendidos');
    })->name('ventas.productos-mas-vendidos');

    Route::get('/ventas/reporte-ventas-detalladas', function () {
        return Inertia::render('VentasReporteVentasDetalladas');
    })->name('ventas.reporte-ventas-detalladas');

    Route::get('/ventas/reporte-formulas', function () {
        return Inertia::render('VentasReporteFormulas');
    })->name('ventas.reporte-formulas');

    Route::get('/ventas/grupos-complementos', function () {
        return Inertia::render('VentasGruposComplementos');
    })->name('ventas.grupos-complementos');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
