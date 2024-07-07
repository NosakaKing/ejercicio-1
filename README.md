# Mi Primera Aplicacion en PHP utilizando [Visual Studio Code](https://code.visualstudio.com/)
> Material de clase del repositorio de [Ileroc](https://github.com/lleroc/Ej1.git)
<p align="center">
  <img src="https://i.imgur.com/RN9xamt.gif">
</p>

## Pruebas de Login
<p>Se Utilizo el material del Ej2 del repositorio y se incremento un codigo para encriptar la clave del usuario al ingresarla y actualizarla.</p>
<b>Se utilizo la primera base de datos para este ejercicio asi que no usamos roles</b>

- Agregamos el siguiente codigo para encriptar la clave
``` PHP
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$cadena = "INSERT INTO usuarios (nombre, apellido, correo, password) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($cadena);
$stmt->bind_param('ssss', $nombre, $apellido, $correo, $hashedPassword);
```

## CRUD Funcionales
<p>En este espacio se muestra el funcionamiento del sistema, de las 3 tablas, Producto, Cliente y Usuarios.</p>
- INSERT
<p align="center">
  <img src="https://i.imgur.com/m6bRf4R.gif">
</p>

- UPDATE
<p align="center">
  <img src="https://i.imgur.com/o1zCD2B.gif">
</p>

- DELETE
  <p align="center">
  <img src="https://i.imgur.com/iP5VfGW.gif">
</p>
