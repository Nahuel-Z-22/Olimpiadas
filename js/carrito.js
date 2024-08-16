let productosEnCarrito = localStorage.getItem("productos-en-carrito");
productosEnCarrito = JSON.parse(productosEnCarrito);
var base_url = ".";

const contenedorCarritoVacio = document.querySelector("#carrito-vacio");
const contenedorCarritoProductos = document.querySelector("#carrito-productos");
const contenedorCarritoAcciones = document.querySelector("#carrito-acciones");
const contenedorCarritoComprado = document.querySelector("#carrito-comprado");
let botonesEliminar = document.querySelectorAll(".carrito-producto-eliminar");
const botonVaciar = document.querySelector("#carrito-acciones-vaciar");
const contenedorTotal = document.querySelector("#total");
const botonComprar = document.querySelector("#carrito-acciones-comprar");

function cargarProductosCarrito() {
    const usuarioId = window.usuarioId;

    fetch(`obtener_carrito.php?usuario_id=${usuarioId}`)
        .then(response => response.json())
        .then(productosEnCarrito => {
            if (productosEnCarrito && productosEnCarrito.length > 0) {

                contenedorCarritoVacio.classList.add("disabled");
                contenedorCarritoProductos.classList.remove("disabled");
                contenedorCarritoAcciones.classList.remove("disabled");
                contenedorCarritoComprado.classList.add("disabled");

                contenedorCarritoProductos.innerHTML = "";

                let total = 0;

                productosEnCarrito.forEach(producto => {
                    total += producto.precio * producto.cantidad;

                    const div = document.createElement("div");
                    div.classList.add("carrito-producto");
                    div.innerHTML = `
                        <img class="carrito-producto-imagen" src="${base_url}${producto.imagen}" alt="${producto.titulo}">
                        <div class="carrito-producto-titulo">
                            <small>Título</small>
                            <h3>${producto.titulo}</h3>
                        </div>
                        <div class="carrito-producto-cantidad">
                            <small>Cantidad</small>
                            <p>${producto.cantidad}</p>
                        </div>
                        <div class="carrito-producto-precio">
                            <small>Precio</small>
                            <p>$${number_format(producto.precio, 0, ',', '.')}</p>
                        </div>
                        <div class="carrito-producto-subtotal">
                            <small>Subtotal</small>
                            <p>$${number_format(producto.precio * producto.cantidad, 0, ',', '.')}</p>
                        </div>
                        <button class="carrito-producto-eliminar" data-producto-id="${producto.id}"><i></i>Eliminar</button>
                    `;

                    contenedorCarritoProductos.append(div);
                });

                actualizarBotonesEliminar();
                actualizarTotal(number_format(total, 0, ',', '.'));

            } else {
                contenedorCarritoVacio.classList.remove("disabled");
                contenedorCarritoProductos.classList.add("disabled");
                contenedorCarritoAcciones.classList.add("disabled");
                contenedorCarritoComprado.classList.add("disabled");
            }
        })
        .catch(error => {
            console.error("Error al obtener los productos del carrito:", error);
        });
}

function number_format(number, decimals, dec_point, thousands_sep) {

    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number, 
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function actualizarTotal(total) {
    document.getElementById('total-carrito').textContent = `$${total}`;
}



function eliminarDelCarrito(e) {
    const productoId = e.target.getAttribute('data-producto-id');
    const usuarioId = window.usuarioId;

    fetch(`eliminar_producto_carrito.php?usuario_id=${usuarioId}&producto_id=${productoId}`)
        .then(response => {
            if (response.ok) {
                cargarProductosCarrito();
                mostrarToastEliminacion();
            } else {
                console.error("Error al eliminar el producto del carrito.");
            }
        })
        .catch(error => {
            console.error("Error al eliminar el producto del carrito:", error);
        });
}

function mostrarToastEliminacion() {
    Toastify({
        text: "Producto eliminado",
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        style: {
          background: "linear-gradient(to right, #4b33a8, #785ce9)",
          borderRadius: "2rem",
          textTransform: "uppercase",
          fontSize: ".75rem"
        },
        offset: {
            x: '1.5rem',
            y: '1.5rem'
          },
        onClick: function(){}
      }).showToast();
}

cargarProductosCarrito();

function actualizarBotonesEliminar() {
    botonesEliminar = document.querySelectorAll(".carrito-producto-eliminar");

    botonesEliminar.forEach(boton => {
        boton.addEventListener("click", eliminarDelCarrito);
    });
}


botonVaciar.addEventListener("click", vaciarCarrito);
function vaciarCarrito() {
    fetch('contador.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    icon: 'question',
                    html: `Se van a borrar ${data.total_productos} productos.`,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Solo si el usuario confirma, se vaciará el carrito
                        fetch('vaciar_carrito.php')
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    productosEnCarrito = []; // Vaciar el array local
                                    localStorage.setItem("productos-en-carrito", JSON.stringify(productosEnCarrito));
                                    cargarProductosCarrito();
                                } else {
                                    console.error("Error al vaciar el carrito:", data.error);
                                }
                            })
                            .catch(error => {
                                console.error("Error al vaciar el carrito:", error);
                            });
                    }
                });
            } else {
                console.error("Error al obtener el contador de productos:", data.error);
            }
        })
        .catch(error => {
            console.error("Error al obtener el contador de productos:", error);
        });
}

function actualizarTotal() {
    fetch('contador2.php?usuario_id=' + window.usuarioId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const totalCalculado = data.total_productos;
                const totalFormateado = number_format(totalCalculado, 0, ',', '.');
                contenedorTotal.innerText = `$${totalFormateado}`;
            } else {
                console.error("Error al obtener el total de productos en el carrito:", data.error);
            }
        })
        .catch(error => {
            console.error("Error al obtener el total de productos en el carrito:", error);
        });
}

botonComprar.addEventListener("click", comprarCarrito);
function comprarCarrito() {

    productosEnCarrito.length = 0;
    localStorage.setItem("productos-en-carrito", JSON.stringify(productosEnCarrito));
    
    contenedorCarritoVacio.classList.add("disabled");
    contenedorCarritoProductos.classList.add("disabled");
    contenedorCarritoAcciones.classList.add("disabled");
    contenedorCarritoComprado.classList.remove("disabled");

}

document.addEventListener("DOMContentLoaded", function() {
    var comprarButton = document.getElementById("carrito-acciones-comprar");
    if (comprarButton) {
        comprarButton.addEventListener("click", function() {
            // Redirigir al usuario a metodo.php
            window.location.href = "ticket.php";
        });
    }
});