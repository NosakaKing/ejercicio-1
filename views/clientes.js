// Inicialización
function init() {
    $("#frm_usuarios").on("submit", function (e) {
        guardaryeditar(e);
    });
}

$(document).ready(() => {
    cargaTabla();
});

// Cargar la tabla de usuarios
var cargaTabla = () => {
    var html = "";

    $.get("../controllers/cliente.controller.php?op=todos", (listaClientes) => {
        console.log(listaClientes);
        $.each(listaClientes, (indice, uncliente) => {
            html += `
                <tr>
                    <td>${indice + 1}</td>
                    <td>${uncliente.nombre}</td>
                    <td>${uncliente.apellido}</td>
                    <td>${uncliente.email}</td>
                    <td>${uncliente.telefono}</td>
                    <td>
                        <button class="btn btn-primary" onclick="editar(${uncliente.id})">Editar</button>
                        <button class="btn btn-danger" onclick="eliminar(${uncliente.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });
        $("#cuerpousuarios").html(html);
    });
};

// Guardar y editar usuario
var guardaryeditar = (e) => {
    e.preventDefault(); // Detener la acción predeterminada del formulario

    var frm_usuarios = new FormData($("#frm_usuarios")[0]);

    var UsuarioIdEdit = $("#UsuarioId").val(); 

    var ruta = "";
    if (UsuarioIdEdit != "") {
        // Actualizar
        ruta = "../controllers/cliente.controller.php?op=actualizar";
    } else {
        // Insertar
        ruta = "../controllers/cliente.controller.php?op=insertar";
    }

    $.ajax({
        url: ruta,
        type: "POST",
        data: frm_usuarios,
        contentType: false,
        processData: false,
        success: function (datos) {
            console.log(datos);
            $("#modalUsuario").modal("hide");
            location.reload(); // Recargar la página
        },
        error: function (xhr, status, error) {
            console.error("Error al guardar o editar:", error);
        }
    });
};

// Eliminar usuario
var eliminar = (UsuarioId) => {
    Swal.fire({
        title: "Clientes",
        text: "¿Está seguro que desea eliminar el cliente?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Eliminar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../controllers/cliente.controller.php?op=eliminar",
                type: "POST",
                data: { idUsuarios: UsuarioId },
                success: (resultado) => {
                    console.log("Respuesta del servidor:", resultado);
                    if (resultado.message === "Eliminado correctamente") {
                        Swal.fire({
                            title: "Cliente",
                            text: "Se eliminó con éxito",
                            icon: "success",
                        });
                        location.reload(); // Recargar la página
                    } else {
                        Swal.fire({
                            title: "Clientes",
                            text: "No se pudo eliminar",
                            icon: "error",
                        });
                    }
                },
                error: () => {
                    Swal.fire({
                        title: "Clientes",
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
        url: `../controllers/cliente.controller.php?op=uno&id=${UsuarioId}`,
        type: "GET",
        success: function (data) {
            $("#UsuarioId").val(data.id);
            $("#Nombre").val(data.nombre);
            $("#Apellido").val(data.apellido);
            $("#correo").val(data.email);
            $("#telefono").val(data.telefono);

            // Mostrar el modal de edición
            $("#modalUsuario").modal("show");
        },
        error: function () {
            Swal.fire({
                title: "Clientes",
                text: "Ocurrió un error al intentar obtener los datos del cliente",
                icon: "error",
            });
        },
    });
};

init();
