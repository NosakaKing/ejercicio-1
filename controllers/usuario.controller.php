<?php

require_once('../config/cors.php');
require_once('../models/usuario.model.php');

$usuario = new Clase_Usuario();
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case "GET":
        if (isset($_GET['id'])) {
            $uno = $usuario->uno($_GET['id']);
            echo json_encode(mysqli_fetch_assoc($uno));
        } else {
            $datos = $usuario->todos();
            $todos = array();
            while($fila = mysqli_fetch_assoc($datos)) {
                array_push($todos, $fila);
            }
            echo json_encode($todos);
        }
        break;

    case "PUT":
        $datos = json_decode(file_get_contents('php://input'));
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (!empty($datos->$id) || !empty($datos->nombre) || !empty($datos->apellido) || !empty($datos->correo) || !empty($datos->password)) {
                try {
                    $actualizar = array();
                    $actualizar = $usuario->actualizar($id, $datos->nombre, $datos->apellido, $datos->correo, $datos->password);
                } catch (Exception $th) {
                    echo json_encode(array("Message" => "Error, No se pudo actualizar"));
                }
            } else {
                echo json_encode(array("Message" => "Error, Faltan Datos"));
            }
        }
        break;

    case "DELETE":
        if (isset($_GET['id'])) {
            $datos = $usuario->eliminar($_GET['id']);
        } else {
            echo json_encode(array('message' => 'El id es obligatorio'));
        }
        break;

    case "POST":
        if (isset($_GET["op"]) && $_GET["op"] == "login") {
            if (empty(trim($_POST["correo"])) || empty(trim($_POST["contrasenia"]))) {
                header('Location: ../index.php?op=2');
                exit();
            }
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
            $datos = json_decode(file_get_contents('php://input'));
            if (!empty($datos->nombre) && !empty($datos->apellido) && !empty($datos->correo) && !empty($datos->password)) {
                $insertar = $usuario->Insertar($datos->nombre, $datos->apellido, $datos->correo, $datos->password);
                echo json_encode(array("Message" => "Insertado Correctamente"));
            } else {
                echo json_encode(array("Message" => "Error, Faltan Datos"));
            }
        }
        break;

    default:
        echo json_encode(array("Message" => "Método no soportado"));
        break;
}
