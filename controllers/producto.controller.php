<?php

require_once('../config/cors.php');
require_once('../models/producto.model.php');

$producto = new Clase_Producto();
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case "GET":
        if (isset($_GET['id'])) {
            $uno = $producto->uno($_GET['id']);
            echo json_encode(mysqli_fetch_assoc($uno));
        } else {
            $datos = $producto->todos();
            $todos = array();
            while($fila = mysqli_fetch_assoc($datos)) {
                array_push($todos, $fila);
            }
            echo json_encode($todos);
        }
        break;

    case "POST":
        $datos = json_decode(file_get_contents('php://input'));
        if (!empty($datos->nombre) && !empty($datos->precio) && !empty($datos->stock)) {
            $insertar = $producto->Insertar($datos->nombre, $datos->precio, $datos->stock);
            echo json_encode(array("Message" => "Insertado Correctamente"));
        } else {
            echo json_encode(array("Message" => "Error, Faltan Datos"));
        }
        break;

    case "PUT":
        $datos = json_decode(file_get_contents('php://input'));
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (!empty($datos->$id) || !empty($datos->nombre) || !empty($datos->precio) || !empty($datos->stock)) {
                try {
                    $actualizar = array();
                    $actualizar = $producto->actualizar($id, $datos->nombre, $datos->precio, $datos->stock);
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
            $datos = $producto->eliminar($_GET['id']);
            echo json_encode(array('message' => 'Eliminado correctamente'));
        }else{
            echo json_encode(array('message' => 'El id es obligatorio'));
        }
    break;

}