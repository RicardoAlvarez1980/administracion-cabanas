<?php
require_once 'Cabanas.php';
require_once 'Reservas.php';
require_once 'Clientes.php';
require_once 'Conexion.php';

// Arreglo para almacenar las cabañas, reservas y clientes
$cabanas = [];
$reservas = [];
$clientes = [];

// Menú de ingreso al sistema

while (true) {
    $conexion = Conexion::obtenerInstancia()->obtenerConexion();

    echo "\nBienvenido a CabinManager, su gestor de reservas!\n";
    echo "1. Gestión general del Sistema\n";
    echo "2. Búsqueda de Clientes\n";
    echo "3. Listados de Clientes, Cabañas y Reservas\n";
    echo "0. Salir\n";
    $opcion = readline("Ingrese el número correspondiente a la opción deseada: ");

    switch ($opcion) {
        case 1:
            GestorGeneral();
            break;
        case 2:
            buscarClientesMenu($conexion);
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
function GestorGeneral()
{
    while (true) {

        echo "\nGestión General del Sistema\n";
        echo "1. Gestionar Clientes\n";
        echo "2. Gestionar Cabañas\n";
        echo "3. Gestionar Reservas\n";
        echo "0. Volver al menu anterior\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ");

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
    }
}
function menuListados()
{
    $conexion = Conexion::obtenerInstancia()->obtenerConexion();

    while (true) {
        echo "\nListados:\n";
        echo "1. Listar Clientes\n";
        echo "2. Listar Cabañas\n";
        echo "3. Listar Reservas\n";
        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ");

        switch ($opcion) {
            case 1:
                listarClientes($conexion);
                break;
            case 2:
                listarCabanas($conexion);
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
        }
    }
}

// Funciones para gestionar las cabañas
function gestionarCabanas() {
    $conexion = Conexion::obtenerInstancia()->obtenerConexion();
    while (true) {
        echo "\nBienvenido al menú de gestión de Cabañas:\n";
        echo "1. Agregar Cabaña\n";
        echo "2. Actualizar Cabaña\n";
        echo "3. Eliminar Cabaña\n";
        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ");
        switch ($opcion) {
            case 1:
                echo "---------------------------\n";
                agregarCabana($conexion);
                break;
            case 2:
                actualizarCabana($conexion);
                break;
            case 3:
                eliminarCabana($conexion);
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
function listarCabanas($conexion) {
    $query = "SELECT * FROM Cabanas";
    $resultado = $conexion->query($query);

    if ($resultado) {
        $cabanas = $resultado->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cabanas)) {
            echo "No hay cabañas registradas.\n";
        } else {
            echo "Listado de Cabañas:\n";
            echo "---------------------------\n";
            foreach ($cabanas as $cabana) {
                echo "Número: " . $cabana['numero'] . "\n";
                echo "Capacidad: " . $cabana['capacidad'] . "\n";
                echo "Descripción: " . $cabana['descripcion'] . "\n";
                echo "Costo Diario: $" . $cabana['costodiario'] . "\n";
                echo "---------------------------\n";
            }
        }
    } else {
        echo "Error en la consulta: " . $conexion->errorInfo()[2];
    }
}

function agregarCabana($conexion) {
    echo "\nAgregar Cabaña\n";
    echo "Ingrese el número de la cabaña: ";
    $numero = intval(trim(fgets(STDIN)));

    // Verificar si el número de cabaña ya existe
    $consulta = "SELECT * FROM Cabanas WHERE numero = ?";
    $stmt = $conexion->prepare($consulta);

    echo "Ingrese la capacidad de la cabaña: ";
    $capacidad = intval(trim(fgets(STDIN)));
    echo "Ingrese la descripción de la cabaña: ";
    $descripcion = trim(fgets(STDIN));
    echo "Ingrese el costo diario de la cabaña: ";
    $costoDiario = floatval(trim(fgets(STDIN)));

    // Agrega la cabaña a la base de datos.
    $query = "INSERT INTO Cabanas (numero, capacidad, descripcion, costodiario) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);

    if ($stmt) {
        $stmt->bindParam(1, $numero);
        $stmt->bindParam(2, $capacidad);
        $stmt->bindParam(3, $descripcion);
        $stmt->bindParam(4, $costoDiario);

        if ($stmt->execute()) {
            echo "Cabaña agregada exitosamente.\n";
        } else {
            echo "Error al agregar la cabaña: " . $stmt->errorInfo()[2] . "\n";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->errorInfo()[2] . "\n";
    }
}

function actualizarCabana($conexion) {
    echo "\nActualizar Cabaña\n";
    echo "Ingrese el número de la cabaña a actualizar: ";
    $numero = intval(trim(fgets(STDIN)));

    // Comprobamos si la cabaña  existe en la base de datos
    $consulta = "SELECT * FROM Cabanas WHERE numero = ?";
    $stmt = $conexion->prepare($consulta);

    if ($stmt) {
        $stmt->bindParam(1, $numero);
        $stmt->execute();
        $cabana = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cabana) {
            echo "Ingrese la nueva capacidad de la cabaña: ";
            $capacidad = intval(trim(fgets(STDIN)));
            echo "Ingrese la nueva descripción de la cabaña: ";
            $descripcion = trim(fgets(STDIN));
            echo "Ingrese el nuevo costo diario de la cabaña: ";
            $costoDiario = floatval(trim(fgets(STDIN)));
        }
        // Actualizamos los datos de la cabaña en la base de datos
        $actualizarConsulta = "UPDATE Cabanas SET capacidad = ?, descripcion = ?, costodiario = ? WHERE numero = ?";
        $stmtActualizar = $conexion->prepare($actualizarConsulta);
        if ($stmtActualizar) {
            $stmtActualizar->bindParam(1, $capacidad);
            $stmtActualizar->bindParam(2, $descripcion);
            $stmtActualizar->bindParam(3, $costoDiario);
            $stmtActualizar->bindParam(4, $numero);

            if ($stmtActualizar->execute()) {
                echo "Canbaña actualizado exitosamente.\n";
            } else {
                echo "Error al actualizar la cabaña: " . $stmtActualizar->errorInfo()[2] . "\n";
            }
        } else {
            echo "Error en la preparación de la consulta de actualización: " . $conexion->errorInfo()[2] . "\n";
        }
    } else {
        echo "No se encontró una cabaña con el número especificado.\n";
    }
}

function eliminarCabana($conexion) {
    echo "\nEliminar Cabaña\n";
    echo "Ingrese el número de la cabaña a eliminar: ";
    $numero = intval(trim(fgets(STDIN)));

    // Verificamos si la cabaña existe en la base de datos
    $consulta = "SELECT * FROM Cabanas WHERE numero = ?";
    $stmt = $conexion->prepare($consulta);

    if ($stmt) {
        $stmt->bindParam(1, $numero);
        $stmt->execute();
        $cabana = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cabana) {
            //Eliminamos la cabaña de la base de datos.
            $eliminarConsulta = "DELETE FROM Cabanas WHERE numero = ?";
            $stmtEliminar = $conexion->prepare($eliminarConsulta);
            if ($stmtEliminar) {
                $stmtEliminar->bindParam(1, $numero);
                if ($stmtEliminar->execute()) {
                    echo "Cabaña eliminada exitosamente.\n";
                } else {
                    echo "Error al eliminar la cabaña: " . $stmtEliminar->errorInfo()[2] . "\n";
                }
            } else {
                echo "Error en la preparación de la consulta de eliminación: " . $conexion->errorInfo()[2] . "\n";
            }
        } else {
            echo "No se encontró una cabaña con el número especificado.\n";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->errorInfo()[2] . "\n";
    }
}
// MENU DE GESTIÓN DE CLIENTES
function gestionarClientes() {
    $conexion = Conexion::obtenerInstancia()->obtenerConexion();

    while (true) {
        echo "\nMenú de Clientes\n";
        echo "1. Agregar Cliente\n";
        echo "2. Actualizar Cliente\n";
        echo "3. Eliminar Cliente\n";
        echo "4. Buscar Clientes\n";
        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ");

        switch ($opcion) {
            case 1:
                echo "---------------------------\n";
                agregarCliente($conexion);
                break;
            case 2:
                actualizarCliente($conexion);
                break;
            case 3:
                eliminarCliente($conexion);
                break;
            case 4:
                buscarClientesMenu($conexion);
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

//  FUNCIÓN DE LISTAR CLIENTES
function listarClientes($conexion) {
    $query = "SELECT * FROM clientes";
    $resultado = $conexion->query($query);

    if ($resultado) {
        $clientes = $resultado->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clientes)) {
            echo "No hay clientes registrados.\n";
        } else {
            echo "Listado de Clientes:\n";
            echo "---------------------------\n";
            foreach ($clientes as $cliente) {
                echo "DNI: " . $cliente['dni'] . "\n";
                echo "Nombre: " . $cliente['nombre'] . "\n";
                echo "Dirección: " . $cliente['direccion'] . "\n";
                echo "Teléfono: " . $cliente['telefono'] . "\n";
                echo "Email: " . $cliente['email'] . "\n";
                echo "---------------------------\n";
            }
        }
    } else {
        echo "Error en la consulta: " . $conexion->errorInfo()[2];
    }
}

function agregarCliente($conexion) {
    echo "\nAgregar Cliente\n";
    echo "Ingrese el número de DNI del cliente: ";
    $dni = trim(fgets(STDIN));
    echo "Ingrese el nombre del cliente: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese la dirección del cliente: ";
    $direccion = trim(fgets(STDIN));
    echo "Ingrese el teléfono del cliente: ";
    $telefono = trim(fgets(STDIN));
    echo "Ingrese el email del cliente: ";
    $email = trim(fgets(STDIN));

    $query = "INSERT INTO clientes (dni, nombre, direccion, telefono, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);

    if ($stmt) {
        $stmt->bindParam(1, $dni);
        $stmt->bindParam(2, $nombre);
        $stmt->bindParam(3, $direccion);
        $stmt->bindParam(4, $telefono);
        $stmt->bindParam(5, $email);

        if ($stmt->execute()) {
            echo "Cliente agregado exitosamente.\n";
        } else {
            echo "Error al agregar el cliente: " . $stmt->errorInfo()[2] . "\n";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->errorInfo()[2] . "\n";
    }
}

// FUNCIÓN ACTUALIZAR CLIENTE MEDIANTE DNI
function actualizarCliente($conexion) {
    echo "\nActualizar Cliente\n";
    echo "Ingrese el DNI del cliente a actualizar: ";
    $dni = intval(trim(fgets(STDIN)));

    // Comprobamos si el cliente existe en la base de datos
    $consulta = "SELECT * FROM clientes WHERE dni = ?";
    $stmt = $conexion->prepare($consulta);

    if ($stmt) {
        $stmt->bindParam(1, $dni);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            echo "Ingrese el nuevo nombre del cliente: ";
            $nombre = trim(fgets(STDIN));
            echo "Ingrese la nueva dirección del cliente: ";
            $direccion = trim(fgets(STDIN));
            echo "Ingrese el nuevo teléfono del cliente: ";
            $telefono = trim(fgets(STDIN));
            echo "Ingrese el nuevo email del cliente: ";
            $email = trim(fgets(STDIN));

            // Actualizamos los datos del cliente en la base de datos
            $actualizarConsulta = "UPDATE clientes SET nombre = ?, direccion = ?, telefono = ?, email = ? WHERE dni = ?";
            $stmtActualizar = $conexion->prepare($actualizarConsulta);

            if ($stmtActualizar) {
                $stmtActualizar->bindParam(1, $nombre);
                $stmtActualizar->bindParam(2, $direccion);
                $stmtActualizar->bindParam(3, $telefono);
                $stmtActualizar->bindParam(4, $email);
                $stmtActualizar->bindParam(5, $dni);

                if ($stmtActualizar->execute()) {
                    echo "Cliente actualizado exitosamente.\n";
                } else {
                    echo "Error al actualizar el cliente: " . $stmtActualizar->errorInfo()[2] . "\n";
                }
            } else {
                echo "Error en la preparación de la consulta de actualización: " . $conexion->errorInfo()[2] . "\n";
            }
        } else {
            echo "No se encontró un cliente con el DNI especificado.\n";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->errorInfo()[2] . "\n";
    }
}

// FUNCIÓN ELIMINAR CLIENTE MEDIANTE DNI
function eliminarCliente($conexion) {
    echo "\nEliminar Cliente\n";
    echo "Ingrese el DNI del cliente a eliminar: ";
    $dni = intval(trim(fgets(STDIN)));

    // Verificamos si el cliente existe en la base de datos
    $consulta = "SELECT * FROM clientes WHERE dni = ?";
    $stmt = $conexion->prepare($consulta);

    if ($stmt) {
        $stmt->bindParam(1, $dni);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            // Eliminamos el cliente de la base de datos
            $eliminarConsulta = "DELETE FROM clientes WHERE dni = ?";
            $stmtEliminar = $conexion->prepare($eliminarConsulta);

            if ($stmtEliminar) {
                $stmtEliminar->bindParam(1, $dni);

                if ($stmtEliminar->execute()) {
                    echo "Cliente eliminado exitosamente.\n";
                } else {
                    echo "Error al eliminar el cliente: " . $stmtEliminar->errorInfo()[2] . "\n";
                }
            } else {
                echo "Error en la preparación de la consulta de eliminación: " . $conexion->errorInfo()[2] . "\n";
            }
        } else {
            echo "No se encontró un cliente con el DNI especificado.\n";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->errorInfo()[2] . "\n";
    }
}

// FUNCIONES PARA BUSCAR CLIENTES (SE REQUIEREN AMBAS)
function buscarClientes($conexion, $parametroBusqueda) {
    $parametroBusqueda = '%' . $parametroBusqueda . '%'; // Agregamos comodines % para buscar en cualquier parte del nombre

    $consulta = "SELECT * FROM clientes WHERE nombre ILIKE ?";
    $stmt = $conexion->prepare($consulta);

    if ($stmt) {
        $stmt->bindParam(1, $parametroBusqueda);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->errorInfo()[2] . "\n";
        return [];
    }
}

function buscarClientesMenu($conexion) {
    echo "\nBuscar Clientes\n";
    $parametroBusqueda = readline("Ingrese el término de búsqueda: ");
    $resultados = buscarClientes($conexion, $parametroBusqueda);

    if (empty($resultados)) {
        echo "No se encontraron clientes que coincidan con la búsqueda.\n";
    } else {
        echo "Resultados de la búsqueda:\n";
        foreach ($resultados as $cliente) {
            echo "DNI: " . $cliente['dni'] . "\n";
            echo "Nombre: " . $cliente['nombre'] . "\n";
            echo "Dirección: " . $cliente['direccion'] . "\n";
            echo "Teléfono: " . $cliente['telefono'] . "\n";
            echo "Email: " . $cliente['email'] . "\n";
            echo "---------------------------\n";
        }
    }
}
// Funciones para gestionar las reservas
function gestionarReservas() {
    global $reservas, $cabanas, $clientes;

    while (true) {
        echo "\nMenú de Reservas\n";
        echo "1. Agregar Reserva\n";
        echo "2. Modificar Reserva\n";
        echo "3. Eliminar Reserva\n";

        echo "0. Volver al Menú Principal\n";
        $opcion = readline("Ingrese el número correspondiente a la opción deseada: ");

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
//Falta modificar esta funcion 

function listarReservas() {
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


function agregarReserva() {
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
        echo "DNI: " . $cliente->getDni() . "\n";
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
        if ($cliente->getDni() === $idCliente) {
            $clienteSeleccionado = $cliente;
            break;
        }
    }
    if (!$clienteSeleccionado) {
        echo "No se encontró un cliente con el DNI especificado.\n";
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