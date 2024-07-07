<?php

require_once('../config/cors.php');
require_once('../models/cliente.model.php');

$cliente = new Clase_Cliente();
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($_GET["op"]) {
    /*TODO: Procedimiento para listar todos los registros */
    case 'todos':
        $datos = array();
        $datos = $cliente->todos();
        $todos = array();
        while ($row = mysqli_fetch_assoc($datos)) {
            $todos[] = $row;
        }
        echo json_encode($todos);
        break;
        
    /*TODO: Procedimiento para sacar un registro */
    case 'uno':
    if (isset($_GET["id"])) {
        $idCliente = intval($_GET["id"]);
        $datos = $cliente->uno($idCliente);
        echo json_encode($datos); // Devuelve los datos del usuario en formato JSON
    } else {
        echo json_encode(array("message" => "ID no proporcionado"));
    }
    break;
        
    /*TODO: Procedimiento para insertar */
    case 'insertar':
        $Nombre = $_POST["Nombre"] ?? null;
        $Apellidos = $_POST["Apellido"] ?? null;
        $email = $_POST["correo"] ?? null;
        $telefono = $_POST["telefono"] ?? null;
        
        if ($Nombre && $Apellidos && $email && $telefono) {
            $insertar = $cliente->insertar($Nombre, $Apellidos, $email, $telefono);
            if ($insertar == 0) {
                echo json_encode(array("message" => "Insertado correctamente"));
            } else {
                echo json_encode(array("message" => "Error al insertar"));
            }
        } else {
            echo json_encode(array("message" => "Error, faltan datos"));
        }
        break;
        
    /*TODO: Procedimiento para actualizar */
    case 'actualizar':
        $UsuarioId = $_POST["UsuarioId"] ?? null;
        $Nombre = $_POST["Nombre"] ?? null;
        $Apellidos = $_POST["Apellido"] ?? null;
        $email = $_POST["correo"] ?? null;
        $telefono = $_POST["telefono"] ?? null;
        
        if ($UsuarioId && $Nombre && $Apellidos && $email && $telefono) {
            $actualizar = $cliente->actualizar($UsuarioId, $Nombre, $Apellidos, $email, $telefono);
            if ($actualizar) {
                echo json_encode(array("message" => "Actualizado correctamente"));
            } else {
                echo json_encode(array("message" => "Error al actualizar"));
            }
        } else {
            echo json_encode(array("message" => "Error, faltan datos"));
        }
        break;
    
        
    /*TODO: Procedimiento para eliminar */
    case 'eliminar':
        if (isset($_POST["idUsuarios"])) {
            $idUsuarios = intval($_POST["idUsuarios"]);
            $eliminar = $cliente->eliminar($idUsuarios);
            if ($eliminar) {
                echo json_encode(array("message" => "Eliminado correctamente"));
            } else {
                echo json_encode(array("message" => "Error al eliminar"));
            }
        } else {
            echo json_encode(array("message" => "ID no proporcionado"));
        }
        break;

    default:
        echo json_encode(array("message" => "Operación no válida"));
        break;
}