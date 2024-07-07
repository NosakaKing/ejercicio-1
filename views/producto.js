// Inicialización
function init() {
    $("#frm_productos").on("submit", function (e) {
        guardaryeditar(e);
    });
}

$(document).ready(() => {
    cargaTabla();
});

// Cargar la tabla de productos
var cargaTabla = () => {
    var html = "";

    $.get("../controllers/producto.controller.php?op=todos", (listaProductos) => {
        console.log(listaProductos);
        $.each(listaProductos, (indice, unProducto) => {
            html += `
                <tr>
                    <td>${indice + 1}</td>
                    <td>${unProducto.nombre}</td>
                    <td>${unProducto.precio}</td>
                    <td>${unProducto.stock}</td>
                    <td>
                        <button class="btn btn-primary" onclick="editar(${unProducto.id})">Editar</button>
                        <button class="btn btn-danger" onclick="eliminar(${unProducto.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });
        $("#cuerpoproductos").html(html); 
    });
};

// Guardar y editar producto
var guardaryeditar = (e) => {
    e.preventDefault(); 

    var frm_productos = new FormData($("#frm_productos")[0]);

    var ProductoIdEdit = $("#ProductoId").val(); 

    var ruta = "";
    if (ProductoIdEdit != "") {
        // Actualizar
        ruta = "../controllers/producto.controller.php?op=actualizar";
    } else {
        // Insertar
        ruta = "../controllers/producto.controller.php?op=insertar";
    }

    $.ajax({
        url: ruta,
        type: "POST",
        data: frm_productos, // Cambio aquí
        contentType: false,
        processData: false,
        
        success: function (datos) {
            console.log(datos);
            location.reload(); // Recargar la página
            $("#modalUsuario").modal("hide");
        },
        error: function (xhr, status, error) {
            console.error("Error al guardar o editar:", error);
        }
    });
};

// Eliminar producto
var eliminar = (ProductoId) => { // Cambio aquí
    Swal.fire({
        title: "Productos", // Cambio aquí
        text: "¿Está seguro que desea eliminar el producto?", // Cambio aquí
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Eliminar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../controllers/producto.controller.php?op=eliminar",
                type: "POST",
                data: { idProducto: ProductoId }, // Cambio aquí
                success: (resultado) => {
                    console.log("Respuesta del servidor:", resultado);
                    if (resultado.message === "Eliminado correctamente") {
                        Swal.fire({
                            title: "Producto", // Cambio aquí
                            text: "Se eliminó con éxito",
                            icon: "success",
                        });
                        location.reload(); // Recargar la página
                    } else {
                        Swal.fire({
                            title: "Productos", 
                            text: "No se pudo eliminar",
                            icon: "error",
                        });
                    }
                },
                error: () => {
                    Swal.fire({
                        title: "Productos", 
                        text: "Ocurrió un error al intentar eliminar",
                        icon: "error",
                    });
                }
            });
        }
    });
};

// Editar Producto
var editar = (ProductoId) => { 
    $.ajax({
        url: `../controllers/producto.controller.php?op=uno&id=${ProductoId}`,
        type: "GET",
        success: function (data) {
            $("#ProductoId").val(data.id); 
            $("#Nombre").val(data.nombre); 
            $("#Precio").val(data.precio); 
            $("#Stock").val(data.stock);
            // Mostrar el modal de edición
            $("#modalProducto").modal("show");
        },
        error: function () {
            Swal.fire({
                title: "Productos", // Cambio aquí
                text: "Ocurrió un error al intentar obtener los datos del producto", // Cambio aquí
                icon: "error",
            });
        },
    });
};

init();
