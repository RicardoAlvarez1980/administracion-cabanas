<?php
require_once 'Conexion.php';
require_once 'Menu.php';

function salir() {
    echo ("Adios !");
    echo (PHP_EOL);
}

// Menú de ingreso al sistema
function menuPrincipal() {
    //Conectamos a la base de datos.
    $conexion = Conexion::obtenerInstancia()->obtenerConexion();
    
    $opcionesPrincipales = [
        ['0', 'Salir', 'salir',[]],
        ['1', 'Gestión general del Sistema', 'GestorGeneral',[]],
        ['2', 'Búsqueda de Clientes', 'buscarClientesMenu',[$conexion]],
        ['3', 'Listados de Clientes, Cabañas y Reservas', 'menuListados',[]],
    ];

    menu('Menu principal', $opcionesPrincipales);
}

// Menú Secundario
function GestorGeneral() {
  
    $opcionesPrincipales = [
        ['0', 'Volver al menu anterior', 'menuPrincipal', []],
        ['1', 'Gestionar Clientes', 'gestionarClientes', []],
        ['2', 'Gestionar Cabañas', 'gestionarCabanas', []],
        ['3', 'Gestionar Reservas', 'gestionarReservas', []],
    ];

    menu('Gestión General del Sistema', $opcionesPrincipales);
}
function menuListados() {
    $conexion = Conexion::obtenerInstancia()->obtenerConexion();

    $opcionesPrincipales = [
        ['0', 'Volver al menu anterior', 'menuPrincipal', []],
        ['1', 'Listar Clientes ', 'listarClientes', [$conexion]],
        ['2', 'Listar Cabañas', 'listarcabanas', [$conexion]],
        ['3', 'Listar Reservas', 'listarReservas', [$conexion]],
    ];

    menu('Listados', $opcionesPrincipales);

}
