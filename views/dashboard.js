// Inicialización
function init() {
  $("#frm_usuarios").on("submit", function (e) {
    guardaryeditar(e);
  });
}

$().ready(() => {
  cargaTabla();
});

// Cargar la tabla de usuarios
var cargaTabla = () => {
  var html = "";

  $.get("../controllers/usuario.controller.php?op=todos", (listausuarios) => {
    console.log(listausuarios);
    $.each(listausuarios, (indice, unusuario) => {
      html += `
            <tr>
                <td>${indice + 1}</td>
                <td>${unusuario.nombre}</td>
                <td>${unusuario.apellido}</td>
                <td>${unusuario.correo}</td>

            <td>
                <button class="btn btn-primary" onclick="editar(${unusuario.UsuarioId})">Editar</button>
                <button class="btn btn-danger" onclick="eliminar(${unusuario.UsuarioId})">Eliminar</button>
            </td>
            </tr>
        `;
    });
    $("#cuerpousuarios").html(html);
  });
};

// Guardar y editar usuario
var guardaryeditar = (e) => {
  e.preventDefault();
  var frm_usuarios = new FormData($("#frm_usuarios")[0]);
  for (var pair of frm_usuarios.entries()) {
    console.log(pair[0] + ", " + pair[1]);
  }
  var UsuarioId = $("#UsuarioId").val();
  var ruta = "";
  if (UsuarioId == 0) {
    // Insertar
    ruta = "../controllers/usuario.controller.php?op=insertar";
  } else {
    // Actualizar
    ruta = "../controllers/usuario.controller.php?op=actualizar";
  }

  $.ajax({
    url: ruta,
    type: "POST",
    data: frm_usuarios,
    contentType: false,
    processData: false,
    success: function (datos) {
      console.log(datos);
      $("#usuariosModal").modal("hide");
      location.reload(); // Recargar la página
      cargaTabla();
    },
  });
};


// Eliminar usuario
var eliminar = (UsuarioId) => {
  Swal.fire({
    title: "Usuarios",
    text: "¿Está seguro que desea eliminar el usuario?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Eliminar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../controllers/usuario.controller.php?op=eliminar",
        type: "POST",
        data: { idUsuarios: UsuarioId },
        success: (resultado) => {
          console.log("Respuesta del servidor:", resultado);
          if (resultado.message === "Eliminado correctamente") {
            Swal.fire({
              title: "Usuarios",
              text: "Se eliminó con éxito",
              icon: "success",
            });
            cargaTabla(); // Actualiza la tabla después de eliminar
          } else {
            Swal.fire({
              title: "Usuarios",
              text: "No se pudo eliminar",
              icon: "error",
            });
          }
        },
        error: () => {
          Swal.fire({
            title: "Usuarios",
            text: "Ocurrió un error al intentar eliminar",
            icon: "error",
          });
        }
      });
    }
  });
};

// Editar usuario
var editar = (UsuarioId) => {
  $.ajax({
    url: `../controllers/usuario.controller.php?op=uno&id=${UsuarioId}`,
    type: "GET",
    success: function (data) {
      $("#UsuarioId").val(data.UsuarioId);
      $("#Nombre").val(data.nombre);
      $("#Apellido").val(data.apellido);
      $("#correo").val(data.correo);
      $("#password").val(data.password);

      // Mostrar el modal de edición
      $("#modalUsuario").modal("show");
    },
    error: function () {
      Swal.fire({
        title: "Usuarios",
        text: "Ocurrió un error al intentar obtener los datos del usuario",
        icon: "error",
      });
    },
  });
};


init();


