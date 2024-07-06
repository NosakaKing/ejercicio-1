<?php

require_once('../config/cors.php');
require_once('../models/cliente.model.php');

$cliente = new Clase_Cliente();
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case "GET":
        if (isset($_GET['id'])) {
            $uno = $cliente->uno($_GET['id']);
            echo json_encode(mysqli_fetch_assoc($uno));
        } else {
            $datos = $cliente->todos();
            $todos = array();
            while($fila = mysqli_fetch_assoc($datos)) {
                array_push($todos, $fila);
            }
            echo json_encode($todos);
        }
        break;

    case "POST":
        $datos = json_decode(file_get_contents('php://input'));
        if (!empty($datos->nombre) && !empty($datos->apellido) && !empty($datos->email) && !empty($datos->telefono)) {
            $insertar = $cliente->Insertar($datos->nombre, $datos->apellido, $datos->email, $datos->telefono);
            echo json_encode(array("Message" => "Insertado Correctamente"));
        } else {
            echo json_encode(array("Message" => "Error, Faltan Datos"));
        }
        break;

    case "PUT":
        $datos = json_decode(file_get_contents('php://input'));
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (!empty($datos->$id) || !empty($datos->nombre) || !empty($datos->apellido) || !empty($datos->email) || !empty($datos->telefono)) {
                try {
                    $actualizar = array();
                    $actualizar = $cliente->actualizar($id, $datos->nombre, $datos->apellido, $datos->email, $datos->telefono);
                    echo json_encode(array("Message" => "Actualizado Correctamente"));
                } catch (Exception $th) {
                    echo json_encode(array("Message" => "Error, No se pudo actualizar"));
                }
            } else {
                echo json_encode(array("Message" => "Error, Faltan Datos"));
            }
        }
        break;

    case "DELETE":
        if(isset($_GET['id'])){
            $datos = $cliente->eliminar($_GET['id']);
            echo json_encode(array('message' => 'Eliminado correctamente'));
        }else{
            echo json_encode(array('message' => 'El id es obligatorio'));
        }
    break;

}