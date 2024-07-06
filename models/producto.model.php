<?php
require_once('../config/conexion.php');

class Clase_Producto {
    public function todos()
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "SELECT id, nombre, precio , stock FROM productos";
        $datos = mysqli_query($con, $cadena);

        return $datos;

        $con->close(); 
    }

    public function uno($idProducto)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "SELECT id, nombre, precio , stock FROM productos WHERE id = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('i', $idProducto);
        $stmt->execute();
        $datos = $stmt->get_result();
        return $datos;
        $con->close(); 
    }

    public function Insertar($nombre, $precio , $stock)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('sss', $nombre, $precio, $stock);

        if ($stmt->execute()) {
        } else {
            return 'Error al insertar en la base de datos: ' . $stmt->error;
        }

        $con->close();
    }

    //TODO: Procedimiento para actualizar/
    public function Actualizar($idProducto, $nombre, $precio, $stock)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "UPDATE productos SET nombre = ?, precio = ?,  stock = ? WHERE id = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('sssi', $nombre, $precio, $stock, $idProducto);

        if ($stmt->execute()) {
            return $idProducto;
        } else {
            return 'Error al actualizar el registro: ' . $stmt->error;
        }

        $con->close();
    }

    public function eliminar($id)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "DELETE FROM productos WHERE id = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

        $con->close(); 
    }
}