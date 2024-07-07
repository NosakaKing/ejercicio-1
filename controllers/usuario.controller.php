<?php

require_once('../config/cors.php');
require_once('../models/usuario.model.php');

$usuario = new Clase_Usuario();

switch ($_GET["op"]) {
    /*TODO: Procedimiento para listar todos los registros */
    case 'todos':
        $datos = array();
        $datos = $usuario->todos();
        $todos = array();
        while ($row = mysqli_fetch_assoc($datos)) {
            $todos[] = $row;
        }
        echo json_encode($todos);
        break;
        
    /*TODO: Procedimiento para sacar un registro */
    case 'uno':
    if (isset($_GET["id"])) {
        $idUsuarios = intval($_GET["id"]);
        $datos = $usuario->uno($idUsuarios);
        echo json_encode($datos); // Devuelve los datos del usuario en formato JSON
    } else {
        echo json_encode(array("message" => "ID no proporcionado"));
    }
    break;
        
    /*TODO: Procedimiento para insertar */
    case 'insertar':
        $Nombre = $_POST["Nombre"] ?? null;
        $Apellidos = $_POST["Apellido"] ?? null;
        $correo = $_POST["correo"] ?? null;
        $password = $_POST["password"] ?? null;
        
        if ($Nombre && $Apellidos && $correo && $password) {
            $insertar = $usuario->insertar($Nombre, $Apellidos, $correo, $password);
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
        $correo = $_POST["correo"] ?? null;
        $password = $_POST["password"] ?? null;
        
        if ($UsuarioId && $Nombre && $Apellidos && $correo && $password) {
            $actualizar = $usuario->actualizar($UsuarioId, $Nombre, $Apellidos, $correo, $password);
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
            $eliminar = $usuario->eliminar($idUsuarios);
            if ($eliminar) {
                echo json_encode(array("message" => "Eliminado correctamente"));
            } else {
                echo json_encode(array("message" => "Error al eliminar"));
            }
        } else {
            echo json_encode(array("message" => "ID no proporcionado"));
        }
        break;
    

    /*TODO: Procedimiento para login */
    case 'login':
        if (!empty(trim($_POST["correo"])) && !empty(trim($_POST["contrasenia"]))) {
            $correo = $_POST["correo"];
            $contrasena = $_POST["contrasenia"];

            $login = $usuario->login($correo, $contrasena);
            $res = mysqli_fetch_assoc($login);
            if ($res) {
                if ($res['password'] == $contrasena) {
                    header('Location: ../views/dashboard.php');
                    exit();
                } else {
                    header('Location: ../index.php?op=3');
                    exit();
                }
            } else {
                header('Location: ../index.php?op=1');
                exit();
            }
        } else {
            header('Location: ../index.php?op=2');
            exit();
        }
        break;

    default:
        echo json_encode(array("message" => "Operación no válida"));
        break;
}
