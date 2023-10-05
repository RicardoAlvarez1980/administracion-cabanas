<?php
require_once 'Conexion.php';

function menu($titulo, $opciones) {
    echo (PHP_EOL);
    echo ('---------------------------'.PHP_EOL);
    echo ($titulo.PHP_EOL);
    echo ('---------------------------'.PHP_EOL);

    foreach ($opciones as $opcion) {
        echo ($opcion[0] .'-'. $opcion[1]. PHP_EOL );
    } 

    $opcion = readline('Elija una opcion: ');
    $funcion = $opciones[$opcion][2];
    $argumentos = $opciones[$opcion][3]; // Obtener los argumentos

    // Llamar a la función con los argumentos apropiados
    call_user_func_array($funcion, $argumentos);
}


// Menú Secundario



// Funciones para gestionar las cabañas
function gestionarCabanas()
{
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
function listarCabanas($conexion)
{
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

function agregarCabana($conexion)
{
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

function actualizarCabana($conexion)
{
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

function eliminarCabana($conexion)
{
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
function gestionarClientes()
{
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
function listarClientes($conexion)
{
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

function agregarCliente($conexion)
{
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
function actualizarCliente($conexion)
{
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
function eliminarCliente($conexion)
{
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
function buscarClientes($conexion, $parametroBusqueda)
{
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

function buscarClientesMenu($conexion)
{
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
function gestionarReservas()
{
    $conexion = Conexion::obtenerInstancia()->obtenerConexion();

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
                agregarReserva($conexion);
                break;
            case 2:
                listarReservas($conexion);
                modificarReserva($conexion, readline("Ingrese el número de la reserva a modificar: "));
                break;
            case 3:
                listarReservas($conexion);
                eliminarReserva($conexion, readline("Ingrese el número de la reserva a eliminar: "));
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
function listarReservas($conexion)
{
    // Consulta SQL para obtener los datos de las reservas
    $consulta = "SELECT r.numero AS numero_reserva, c.nombre AS nombre_cliente, ca.numero AS numero_cabana,
                 r.fechaInicio, r.fechaFin, (r.fechaFin - r.fechaInicio) * ca.costoDiario AS costo_total
                 FROM Reservas r
                 INNER JOIN Clientes c ON r.cliente_dni = c.DNI
                 INNER JOIN Cabanas ca ON r.cabana_numero = ca.numero
                 ORDER BY r.numero";

    $stmt = $conexion->prepare($consulta);

    if ($stmt) {
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($resultados)) {
            echo "Listado de Reservas:\n";
            echo "---------------------------\n";
            foreach ($resultados as $reserva) {
                echo "Número de Reserva: " . $reserva['numero_reserva'] . "\n";
                echo "Cliente: " . $reserva['nombre_cliente'] . "\n";
                echo "Cabaña: " . $reserva['numero_cabana'] . "\n";
                echo "Fecha de inicio: " . $reserva['fechainicio'] . "\n";
                echo "Fecha de fin: " . $reserva['fechafin'] . "\n";
                echo "Costo de la reserva: $" . $reserva['costo_total'] . "\n";
                echo "---------------------------\n";
            }
        } else {
            echo "No hay reservas registradas.\n";
        }
    } else {
        echo "Error al preparar la consulta.\n";
    }
}

// FUNCIÓN AGREGAR RESERVA
function agregarReserva($conexion)
{
    echo "\nAgregar Reserva\n";

    // Mostrar lista de cabañas disponibles
    $consultaCabañas = "SELECT numero, capacidad, descripcion, costoDiario FROM Cabanas";
    $stmtCabañas = $conexion->prepare($consultaCabañas);

    if ($stmtCabañas) {
        $stmtCabañas->execute();
        $cabañasDisponibles = $stmtCabañas->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cabañasDisponibles)) {
            echo "No hay cabañas disponibles para reservar.\n";
            return;
        }

        echo "Lista de Cabañas Disponibles:\n";
        foreach ($cabañasDisponibles as $cabaña) {
            echo "Número: " . $cabaña['numero'] . "\n";
            echo "Capacidad: " . $cabaña['capacidad'] . "\n";
            echo "Descripción: " . $cabaña['descripcion'] . "\n";
            echo "Costo Diario: $" . $cabaña['costodiario'] . "\n";
            echo "---------------------------\n";
        }

        // Selección de cabaña
        echo "Ingrese el número de la cabaña a reservar: ";
        $numeroCabana = intval(trim(fgets(STDIN)));

        // Verificar si la cabaña existe
        $consultaCabañaExiste = "SELECT COUNT(*) FROM Cabanas WHERE numero = ?";
        $stmtCabañaExiste = $conexion->prepare($consultaCabañaExiste);

        if ($stmtCabañaExiste) {
            $stmtCabañaExiste->bindParam(1, $numeroCabana);
            $stmtCabañaExiste->execute();
            $cabañaExiste = $stmtCabañaExiste->fetchColumn();

            if (!$cabañaExiste) {
                echo "No se encontró una cabaña con el número especificado.\n";
                return;
            }
        }
    } else {
        echo "Error al preparar la consulta de cabañas.\n";
        return;
    }

    // Mostrar lista de clientes
    $consultaClientes = "SELECT DNI, nombre, direccion, telefono, email FROM Clientes";
    $stmtClientes = $conexion->prepare($consultaClientes);

    if ($stmtClientes) {
        $stmtClientes->execute();
        $clientesDisponibles = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clientesDisponibles)) {
            echo "No hay clientes registrados para asociar a la reserva.\n";
            return;
        }

        echo "Lista de Clientes:\n";
        foreach ($clientesDisponibles as $cliente) {
            echo "DNI: " . $cliente['dni'] . "\n";
            echo "Nombre: " . $cliente['nombre'] . "\n";
            echo "Dirección: " . $cliente['direccion'] . "\n";
            echo "Teléfono: " . $cliente['telefono'] . "\n";
            echo "Email: " . $cliente['email'] . "\n";
            echo "---------------------------\n";
        }

        // Selección de cliente
        echo "Ingrese el DNI (Solo ingrese números) del cliente que realiza la reserva: ";
        $dniCliente = intval(trim(fgets(STDIN)));

        // Verificar si el cliente existe
        $consultaClienteExiste = "SELECT COUNT(*) FROM Clientes WHERE DNI = ?";
        $stmtClienteExiste = $conexion->prepare($consultaClienteExiste);

        if ($stmtClienteExiste) {
            $stmtClienteExiste->bindParam(1, $dniCliente);
            $stmtClienteExiste->execute();
            $clienteExiste = $stmtClienteExiste->fetchColumn();

            if (!$clienteExiste) {
                echo "No se encontró un cliente con el DNI especificado.\n";
                return;
            }
        }
    } else {
        echo "Error al preparar la consulta de clientes.\n";
        return;
    }

    // Ingreso de fechas de reserva
    echo "Ingrese la fecha de inicio de la reserva (formato YYYY-MM-DD): ";
    $fechaInicio = trim(fgets(STDIN));
    echo "Ingrese la fecha de fin de la reserva (formato YYYY-MM-DD): ";
    $fechaFin = trim(fgets(STDIN));

    // Calcular el costo total de la reserva (puedes usar la lógica que mencionaste antes)
    $consultaCostoDiario = "SELECT costoDiario FROM Cabanas WHERE numero = ?";
    $stmtCostoDiario = $conexion->prepare($consultaCostoDiario);

    if ($stmtCostoDiario) {
        $stmtCostoDiario->bindParam(1, $numeroCabana);
        $stmtCostoDiario->execute();
        $costoDiario = $stmtCostoDiario->fetchColumn();

        $fechaInicioTimestamp = strtotime($fechaInicio);
        $fechaFinTimestamp = strtotime($fechaFin);

        if ($fechaInicioTimestamp === false || $fechaFinTimestamp === false) {
            echo "Error en el formato de fecha. Use el formato YYYY-MM-DD.\n";
            return;
        }


    } else {
        echo "Error al calcular el costo total de la reserva.\n";
        return;
    }

    // Insertar la reserva en la base de datos
    $consultaInsertarReserva = "INSERT INTO Reservas (cliente_dni, cabana_numero, fechaInicio, fechaFin) VALUES (?, ?, ?, ?)";
    $stmtInsertarReserva = $conexion->prepare($consultaInsertarReserva);

    if ($stmtInsertarReserva) {
        $stmtInsertarReserva->bindParam(1, $dniCliente);
        $stmtInsertarReserva->bindParam(2, $numeroCabana);
        $stmtInsertarReserva->bindParam(3, $fechaInicio);
        $stmtInsertarReserva->bindParam(4, $fechaFin);

        if ($stmtInsertarReserva->execute()) {
            echo "Reserva agregada exitosamente.\n";
        } else {
            echo "Error al agregar la reserva.\n";
        }
    } else {
        echo "Error al preparar la consulta de inserción de reserva.\n";
    }
}
function modificarReserva($conexion, $numeroReserva)
{
    echo "\nModificar Reserva\n";

    // Verificar si la reserva existe en la base de datos
    $consultaReservaExiste = "SELECT COUNT(*) FROM Reservas WHERE numero = ?";
    $stmtReservaExiste = $conexion->prepare($consultaReservaExiste);

    if ($stmtReservaExiste) {
        $stmtReservaExiste->bindParam(1, $numeroReserva);
        $stmtReservaExiste->execute();
        $reservaExiste = $stmtReservaExiste->fetchColumn();

        if (!$reservaExiste) {
            echo "No se encontró una reserva con el número especificado.\n";
            return;
        }
    } else {
        echo "Error al preparar la consulta de verificación de reserva.\n";
        return;
    }

    echo "Ingrese la nueva fecha de inicio de la reserva (formato YYYY-MM-DD): ";
    $nuevaFechaInicio = trim(fgets(STDIN));
    echo "Ingrese la nueva fecha de fin de la reserva (formato YYYY-MM-DD): ";
    $nuevaFechaFin = trim(fgets(STDIN));

    // Actualizar la reserva en la base de datos y obtener los detalles actualizados
    $consultaActualizarReserva = "
        UPDATE Reservas
        SET fechaInicio = ?, fechaFin = ?
        WHERE numero = ?
        RETURNING numero, cliente_dni, cabana_numero, fechaInicio, fechaFin;
    ";
    $stmtActualizarReserva = $conexion->prepare($consultaActualizarReserva);

    if ($stmtActualizarReserva) {
        $stmtActualizarReserva->bindParam(1, $nuevaFechaInicio);
        $stmtActualizarReserva->bindParam(2, $nuevaFechaFin);
        $stmtActualizarReserva->bindParam(3, $numeroReserva);

        if ($stmtActualizarReserva->execute()) {
            $reserva = $stmtActualizarReserva->fetch(PDO::FETCH_ASSOC);

            if ($reserva) {
                echo "Reserva modificada exitosamente.\n";
                echo "===========================\n";
                // Mostrar los detalles de la reserva modificada
                echo "Número de Reserva: " . $reserva['numero'] . "\n";
                echo "Cliente DNI: " . $reserva['cliente_dni'] . "\n";
                echo "Cabaña Número: " . $reserva['cabana_numero'] . "\n";
                echo "Fecha de inicio: " . $reserva['fechainicio'] . "\n";
                echo "Fecha de fin: " . $reserva['fechafin'] . "\n";
                echo "===========================\n";
            } else {
                echo "No se encontró una reserva con el número especificado.\n";
            }
        } else {
            echo "Error al modificar la reserva.\n";
        }
    } else {
        echo "Error al preparar la consulta de actualización de reserva.\n";
    }
}

function eliminarReserva($conexion, $numeroReserva)
{
    echo "\nEliminar Reserva\n";

    // Verificar si la reserva existe en la base de datos
    $consultaReservaExiste = "SELECT COUNT(*) FROM Reservas WHERE numero = ?";
    $stmtReservaExiste = $conexion->prepare($consultaReservaExiste);

    if ($stmtReservaExiste) {
        $stmtReservaExiste->bindParam(1, $numeroReserva);
        $stmtReservaExiste->execute();
        $reservaExiste = $stmtReservaExiste->fetchColumn();

        if (!$reservaExiste) {
            echo "No se encontró una reserva con el número especificado.\n";
            return;
        }
    } else {
        echo "Error al preparar la consulta de verificación de reserva.\n";
        return;
    }

    // Eliminar la reserva de la base de datos
    $consultaEliminarReserva = "DELETE FROM Reservas WHERE numero = ?";
    $stmtEliminarReserva = $conexion->prepare($consultaEliminarReserva);

    if ($stmtEliminarReserva) {
        $stmtEliminarReserva->bindParam(1, $numeroReserva);

        if ($stmtEliminarReserva->execute()) {
            echo "Reserva eliminada exitosamente.\n";
        } else {
            echo "Error al eliminar la reserva.\n";
        }
    } else {
        echo "Error al preparar la consulta de eliminación de reserva.\n";
    }
}