<?php
require_once('../config/conexion.php');

class Clase_Usuario {
    public function todos()
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "SELECT UsuarioId, nombre, apellido , correo, password FROM usuarios";
        $datos = mysqli_query($con, $cadena);

        return $datos;

        $con->close(); // Mover esta línea no tiene efecto aquí; siempre se debería cerrar la conexión tras usarla
    }

    public function uno($idUsuarios)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "SELECT UsuarioId, nombre, apellido, correo, password FROM usuarios WHERE UsuarioId = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('i', $idUsuarios);
        $stmt->execute();
        $datos = $stmt->get_result();
        return $datos;
        $con->close(); 
    }

    public function Insertar($nombre, $apellido , $correo, $password)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $cadena = "INSERT INTO usuarios (nombre, apellido, correo, password) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('ssss', $nombre, $apellido, $correo, $hashedPassword);

        if ($stmt->execute()) {
        } else {
            return 'Error al insertar en la base de datos: ' . $stmt->error;
        }

        $con->close();
    }

    //TODO: Procedimiento para actualizar/
    public function Actualizar($idUsuarios, $nombre, $apellido,  $correo, $password)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "UPDATE usuarios SET nombre = ?, apellido = ?,  correo = ?, password = ? WHERE UsuarioId = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('ssssi', $nombre, $apellido,  $correo, $password, $idUsuarios);

        if ($stmt->execute()) {
            return $idUsuarios;
        } else {
            return 'Error al actualizar el registro: ' . $stmt->error;
        }

        $con->close();
    }

    public function eliminar($idUsuarios)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();

        $cadena = "DELETE FROM usuarios WHERE UsuarioId = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('i', $idUsuarios);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

        $con->close(); 
    }

    //Login
    public function Login($correo, $password)
    {
        $con = new Clase_Conectar();
        $con = $con->Procedimiento_Conectar();
        
        $cadena = "SELECT UsuarioId, nombre, apellido, correo, password FROM usuarios WHERE correo = ? AND password = ?";
        $stmt = $con->prepare($cadena);
        $stmt->bind_param('ss', $correo, $password);
        $stmt->execute();
        $datos = $stmt->get_result();
        return $datos;
        $con->close(); 
    }
}