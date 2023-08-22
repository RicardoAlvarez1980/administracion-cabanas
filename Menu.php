<?php
require_once 'cabanas.php';
require_once 'reservas.php';
require_once 'clientes.php';
require_once 'busquedas.php';
require_once 'data.php';


// Arreglo para almacenar las cabañas, reservas y clientes
$cabanas = [];
$reservas = [];
$clientes = [];

// Menú de ingreso al sistema
while(true){
    echo "\nBienvenido a CabinManager, su gestor de reservas!\n";
    echo "1. Gestión general del Sistema\n";
    echo "2. Búsqueda de Clientes\n";
    echo "3. Listados de Clientes, Cabañas y Reservas\n";
    echo "0. Salir\n";
    $opcion = readline("Ingrese el número correspondiente a la opción deseada: ") ;

    switch ($opcion){
        case 1:
            GestorGeneral();
            break;
        
        case 2:
            buscarClientesMenu();
            break;
        case 3:
            menuListados();
            break;
        case 0:
        echo "¡Hasta luego!\n";
        exit;

        default:
            echo "Opción inválida. Intente nuevamente.\n";
            break;    
    }
}


// Menú Secundario
function GestorGeneral(){
while (true) {

    echo "\nGestión General del Sistema\n";
    echo "1. Gestionar Clientes\n";
    echo "2. Gestionar Cabañas\n";
    echo "3. Gestionar Reservas\n";
    echo "0. Volver al menu anterior\n";
    $opcion = readline("Ingrese el número correspondiente a la opción deseada: ") ;

    switch ($opcion) {
        case 1:
            gestionarClientes();
            break;

        case 2:
            gestionarCabanas();
            break;

        case 3:
            gestionarReservas();
            break;

        case 0:
            echo "Volviendo al Menú Principal\n";
            return;

        default:
            echo "Opción inválida. Intente nuevamente.\n";
            break;
    }
}}

//Revisar la ubicación de esta función
function menuListados(){
    while(true) {
        echo "\nListados:\n";
        echo "1. Listar Clientes\n";
        echo "2. Listar Cabañas\n";
        echo "3. Listar Reservas\n";
        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ") ;

    switch ($opcion) {
        case 1:
            listarClientes();
            break;

        case 2:
            listarCabanas();
            break;

        case 3:
            listarReservas();
            break;

        case 0:
            echo "Volviendo al Menú Principal\n";
            return;

        default:
            echo "Opción inválida. Intente nuevamente.\n";
            break;
    }}

}



// Funciones para gestionar las cabañas
function gestionarCabanas()
{
    global $cabanas;

    while (true) {
        echo "\nBienvenido al menú de gestión de Cabañas:\n";
        echo "1. Agregar Cabaña\n";
        echo "2. Actualizar Cabaña\n";
        echo "3. Eliminar Cabaña\n";
        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ") ;

        switch ($opcion) {
            case 1:
                echo "---------------------------\n";
                agregarCabana();
        
                break;
            case 2:
                actualizarCabana();
                break;

            case 3:
                eliminarCabana();

                break;


            case 0:

                echo "Volviendo al Menú Principal...\n";
                return;

            default:

                echo "Opción inválida. Intente nuevamente.\n";
                break;
        }
    }
}

function listarCabanas()
{
    global $cabanas;

    if (empty($cabanas)) {
        echo "No hay cabañas registradas.\n";
    } else {
        echo "Listado de Cabañas:\n";
        echo "---------------------------\n";
        foreach ($cabanas as $cabana) {
            echo "Número: " . $cabana->getNumero() . "\n";
            echo "Capacidad: " . $cabana->getCapacidad() . "\n";
            echo "Descripción: " . $cabana->getDescripcion() . "\n";
            echo "Costo Diario: " . $cabana->getCostoDiario() . "\n";
            echo "---------------------------\n";
        }
    }
}

function agregarCabana()
{
    global $cabanas;

    echo "\nAgregar Cabaña\n";
    echo "Ingrese el número de la cabaña: ";
    $numero = intval(trim(fgets(STDIN)));

    // Verificar si el número de cabaña ya existe
    foreach ($cabanas as $cabana) {
        if ($cabana->getNumero() === $numero) {
            echo "Ya existe una cabaña con ese número. Intente nuevamente.\n";
            return;
        }
    }

    echo "Ingrese la capacidad de la cabaña: ";
    $capacidad = intval(trim(fgets(STDIN)));
    echo "Ingrese la descripción de la cabaña: ";
    $descripcion = trim(fgets(STDIN));
    echo "Ingrese el costo diario de la cabaña: ";
    $costoDiario = floatval(trim(fgets(STDIN)));

    $cabana = new Cabanas($numero, $capacidad, $descripcion, $costoDiario);
    $cabanas[] = $cabana;

    echo "La cabaña nº " . $cabana->getNumero() . " fue agregada exitosamente.\n";
}

function actualizarCabana()
{
    global $cabanas;

    echo "\nActualizar Cabaña\n";
    echo "Ingrese el número de la cabaña a actualizar: ";
    $numero = intval(trim(fgets(STDIN)));

    foreach ($cabanas as $cabana) {
        if ($cabana->getNumero() === $numero) {
            echo "Ingrese la nueva capacidad de la cabaña: ";
            $capacidad = intval(trim(fgets(STDIN)));
            echo "Ingrese la nueva descripción de la cabaña: ";
            $descripcion = trim(fgets(STDIN));
            echo "Ingrese el nuevo costo diario de la cabaña: ";
            $costoDiario = floatval(trim(fgets(STDIN)));

            $cabana->setCapacidad($capacidad);
            $cabana->setDescripcion($descripcion);
            $cabana->setCostoDiario($costoDiario);

            echo "Cabaña actualizada exitosamente.\n";
            return;
        }
    }

    echo "No se encontró una cabaña con el número especificado.\n";
}

function eliminarCabana()
{
    global $cabanas;

    echo "\nEliminar Cabaña\n";
    echo "Ingrese el número de la cabaña a eliminar: ";
    $numero = intval(trim(fgets(STDIN)));

    foreach ($cabanas as $key => $cabana) {
        if ($cabana->getNumero() === $numero) {
            unset($cabanas[$key]);
            echo "Cabaña eliminada exitosamente.\n";
            return;
        }
    }

    echo "No se encontró una cabaña con el número especificado.\n";
}

// Funciones para gestionar los clientes
function gestionarClientes()
{
    global $clientes;

    while (true) {
        echo "\nMenú de Clientes\n";
        echo "1. Agregar Cliente\n";
        echo "2. Actualizar Cliente\n";
        echo "3. Eliminar Cliente\n";
        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ") ;

        switch ($opcion) {
            case 1:
                echo "---------------------------\n";
                agregarCliente();
                break;

            case 2:
                actualizarCliente();
                break;

            case 3:
                eliminarCliente();
                break;


            case 0:
                echo "Volviendo al Menú Principal...\n";
                return;

            default:
                echo "Opción inválida. Intente nuevamente.\n";
                break;
        }
    }
}

function listarClientes()
{
    global $clientes;

    if (empty($clientes)) {
        echo "No hay clientes registrados.\n";
    } else {
        echo "Listado de Clientes:\n";
        echo "---------------------------\n";
        foreach ($clientes as $cliente) {
            echo "ID: " . $cliente->getId() . "\n";
            echo "Nombre: " . $cliente->getNombre() . "\n";
            echo "Dirección: " . $cliente->getDireccion() . "\n";
            echo "Teléfono: " . $cliente->getTelefono() . "\n";
            echo "Email: " . $cliente->getEmail() . "\n";
            echo "---------------------------\n";
        }
    }
}

function agregarCliente()
{
    global $clientes;

    echo "\nAgregar Cliente\n";
    echo "Ingrese el ID del cliente: ";
    $id = intval(trim(fgets(STDIN)));

    foreach ($clientes as $cliente) {
        if ($cliente->getId() === $id) {
            echo "Ya existe un cliente con ese ID. Intente nuevamente.\n";
            return;
        }
    }

    echo "Ingrese el nombre del cliente: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese la dirección del cliente: ";
    $direccion = trim(fgets(STDIN));
    echo "Ingrese el teléfono del cliente: ";
    $telefono = trim(fgets(STDIN));
    echo "Ingrese el email del cliente: ";
    $email = trim(fgets(STDIN));

    $cliente = new Clientes($id, $nombre, $direccion, $telefono, $email);
    $clientes[] = $cliente;

    echo "Cliente agregado exitosamente.\n";
}

function actualizarCliente()
{
    global $clientes;

    echo "\nActualizar Cliente\n";
    echo "Ingrese el ID del cliente a actualizar: ";
    $id = intval(trim(fgets(STDIN)));

    foreach ($clientes as $cliente) {
        if ($cliente->getId() === $id) {
            echo "Ingrese el nuevo nombre del cliente: ";
            $nombre = trim(fgets(STDIN));
            echo "Ingrese la nueva dirección del cliente: ";
            $direccion = trim(fgets(STDIN));
            echo "Ingrese el nuevo teléfono del cliente: ";
            $telefono = trim(fgets(STDIN));
            echo "Ingrese el nuevo email del cliente: ";
            $email = trim(fgets(STDIN));

            $cliente->setNombre($nombre);
            $cliente->setDireccion($direccion);
            $cliente->setTelefono($telefono);
            $cliente->setEmail($email);

            echo "Cliente actualizado exitosamente.\n";
            return;
        }
    }

    echo "No se encontró un cliente con el ID especificado.\n";
}

function eliminarCliente()
{
    global $clientes;

    echo "\nEliminar Cliente\n";
    echo "Ingrese el ID del cliente a eliminar: ";
    $id = intval(trim(fgets(STDIN)));

    foreach ($clientes as $key => $cliente) {
        if ($cliente->getId() === $id) {
            unset($clientes[$key]);
            echo "Cliente eliminado exitosamente.\n";
            return;
        }
    }

    echo "No se encontró un cliente con el ID especificado.\n";
}

function buscarClientesMenu()
{
    echo "\nBuscar Clientes\n";
    $parametroBusqueda = readline("Ingrese el término de búsqueda: ");
    $resultados = buscarClientes($parametroBusqueda);

    if (empty($resultados)) {
        echo "No se encontraron clientes que coincidan con la búsqueda.\n";
        //Discutir con Mariano a nivel programación si está bien visto que se pueda agregar clientes dentro de la busqueda.
        $opcion = readline( "Desea agregar un nuevo cliente? S (si) , N (no): ");
        if ($opcion == "S" or $opcion == "s"){
            agregarCliente();
        } 
    
    } else {
        echo "Resultados de la búsqueda:\n";
        foreach ($resultados as $cliente) {
            echo "ID: " . $cliente->getId() . "\n";
            echo "Nombre: " . $cliente->getNombre() . "\n";
            echo "Dirección: " . $cliente->getDireccion() . "\n";
            echo "Teléfono: " . $cliente->getTelefono() . "\n";
            echo "Email: " . $cliente->getEmail() . "\n";
            echo "---------------------------\n";
        }
    }
}

// Funciones para gestionar las reservas
function gestionarReservas()
{
    global $reservas, $cabanas, $clientes;

    while (true) {
        echo "\nMenú de Reservas\n";
        echo "1. Agregar Reserva\n";
        echo "2. Modificar Reserva\n";
        echo "3. Eliminar Reserva\n";

        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ") ;

        switch ($opcion) {
            case 1:
                echo "---------------------------\n";
                agregarReserva();
                break;

            case 2:
                modificarReserva();
                break;

            case 3:
                eliminarReserva();
                break;


            case 0:
                echo "Volviendo al Menú Principal...\n";
                return;

            default:
                echo "Opción inválida. Intente nuevamente.\n";
                break;
        }
    }
}

function listarReservas()
{
    global $reservas;

    if (empty($reservas)) {
        echo "No hay reservas registradas.\n";
    } else {
        echo "Listado de Reservas:\n";
        echo "---------------------------\n";
        foreach ($reservas as $reserva) {
            echo "Número de Reserva: " . $reserva->getNumero() . "\n";
            echo "Cliente: " . $reserva->getCliente()->getNombre() . "\n";
            echo "Cabaña: " . $reserva->getCabana()->getNumero() . "\n";
            echo "Fecha de inicio: " . $reserva->getFechaInicio() . "\n";
            echo "Fecha de fin: " . $reserva->getFechaFin() . "\n";
            echo "Costo de la reserva: " . $reserva->getCostoTotal() . "\n";
            echo "---------------------------\n";
        }
    }
}

function agregarReserva()
{
    global $reservas, $cabanas, $clientes;

    echo "\nAgregar Reserva\n";

    // Verificar si hay cabañas disponibles
    if (empty($cabanas)) {
        echo "No hay cabañas disponibles para reservar.\n";
        return;
    }

    // Verificar si hay clientes registrados
    if (empty($clientes)) {
        echo "No hay clientes registrados para asociar a la reserva.\n";
        return;
    }

    // Mostrar lista de cabañas
    echo "Lista de Cabañas Disponibles:\n";
    foreach ($cabanas as $cabana) {
        echo "Número: " . $cabana->getNumero() . "\n";
        echo "Capacidad: " . $cabana->getCapacidad() . "\n";
        echo "Descripción: " . $cabana->getDescripcion() . "\n";
        echo "Costo Diario: " . $cabana->getCostoDiario() . "\n";
        echo "---------------------------\n";
    }

    // Selección de cabaña
    echo "Ingrese el número de la cabaña a reservar: ";
    $numeroCabana = intval(trim(fgets(STDIN)));

    // Verificar si la cabaña existe
    $cabanaSeleccionada = null;
    foreach ($cabanas as $cabana) {
        if ($cabana->getNumero() === $numeroCabana) {
            $cabanaSeleccionada = $cabana;
            break;
        }
    }

    if (!$cabanaSeleccionada) {
        echo "No se encontró una cabaña con el número especificado.\n";
        return;
    }

    // Mostrar lista de clientes
    echo "Lista de Clientes:\n";
    foreach ($clientes as $cliente) {
        echo "DNI: " . $cliente->getId() . "\n";
        echo "Nombre: " . $cliente->getNombre() . "\n";
        echo "Dirección: " . $cliente->getDireccion() . "\n";
        echo "Teléfono: " . $cliente->getTelefono() . "\n";
        echo "Email: " . $cliente->getEmail() . "\n";
        echo "---------------------------\n";
    }

    // Selección de cliente
    echo "Ingrese el DNI (Solo ingrese números) del cliente que realiza la reserva: ";
    $idCliente = intval(trim(fgets(STDIN)));

    // Verificar si el cliente existe
    $clienteSeleccionado = null;
    foreach ($clientes as $cliente) {
        if ($cliente->getId() === $idCliente) {
            $clienteSeleccionado = $cliente;
            break;
        }
    }

    if (!$clienteSeleccionado) {
        echo "No se encontró un cliente con el ID especificado.\n";
        return;
    }

    // Ingreso de fechas de reserva
    echo "Ingrese la fecha de inicio de la reserva (formato YYYY-MM-DD): ";
    $fechaInicio = trim(fgets(STDIN));
    echo "Ingrese la fecha de fin de la reserva (formato YYYY-MM-DD): ";
    $fechaFin = trim(fgets(STDIN));

    // Crear un nuevo objeto de reserva
    $reserva = new Reservas(count($reservas) + 1, $clienteSeleccionado, $cabanaSeleccionada, $fechaInicio, $fechaFin);
    $reservas[] = $reserva;

    // Agregar la reserva al cliente
    $clienteSeleccionado->agregarReserva($reserva);

    echo "Reserva agregada exitosamente.\n";
}

function modificarReserva()
{
    global $reservas;

    echo "\nModificar Reserva\n";
    echo "Ingrese el número de reserva a modificar: ";
    $numeroReserva = intval(trim(fgets(STDIN)));

    foreach ($reservas as $reserva) {
        if ($reserva->getNumero() === $numeroReserva) {
            echo "Ingrese la nueva fecha de inicio de la reserva (formato YYYY-MM-DD): ";
            $nuevaFechaInicio = trim(fgets(STDIN));
            echo "Ingrese la nueva fecha de fin de la reserva (formato YYYY-MM-DD): ";
            $nuevaFechaFin = trim(fgets(STDIN));

            $reserva->setFechaInicio($nuevaFechaInicio);
            $reserva->setFechaFin($nuevaFechaFin);

            echo "Reserva modificada exitosamente.\n";
            return;
        }
    }

    echo "No se encontró una reserva con el número especificado.\n";
}

function eliminarReserva()
{
    global $reservas;

    echo "\nEliminar Reserva\n";
    echo "Ingrese el número de reserva a eliminar: ";
    $numeroReserva = intval(trim(fgets(STDIN)));

    foreach ($reservas as $key => $reserva) {
        if ($reserva->getNumero() === $numeroReserva) {
            unset($reservas[$key]);
            echo "Reserva eliminada exitosamente.\n";
            return;
        }
    }

    echo "No se encontró una reserva con el número especificado.\n";
}

//MODIFICAR CODIGO DE RESERVA (ID DE RESERVA)

?>

