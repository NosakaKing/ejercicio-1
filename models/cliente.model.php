<?php
require_once('../config/conexion.php');

class Clase_Cliente {
    public function todos()
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "SELECT id, nombre, apellido , email, telefono FROM clientes";
        $datos = mysqli_query($con, $cadena);

        return $datos;

        $con->close(); 
    }

    public function uno($idCliente)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "SELECT id, nombre, apellido , email, telefono FROM clientes WHERE id = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('i', $idCliente);
        $stmt->execute();
        $datos = $stmt->get_result()->fetch_assoc(); 
        $con->close(); 
        
        return $datos;
    }

    public function Insertar($nombre, $apellido , $email, $telefono)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "INSERT INTO clientes (nombre, apellido, email, telefono) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('ssss', $nombre, $apellido, $email, $telefono);

        if ($stmt->execute()) {
        } else {
            return 'Error al insertar en la base de datos: ' . $stmt->error;
        }

        $con->close();
    }

    //TODO: Procedimiento para actualizar/
    public function Actualizar($idCliente, $nombre, $apellido, $email, $telefono)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "UPDATE clientes SET nombre = ?, apellido = ?,  email = ?, telefono = ? WHERE id = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('ssssi', $nombre, $apellido,  $email, $telefono, $idCliente);

        if ($stmt->execute()) {
            return $idCliente;
        } else {
            return 'Error al actualizar el registro: ' . $stmt->error;
        }

        $con->close();
    }

    public function eliminar($id)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "DELETE FROM clientes WHERE id = ?";
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