document.addEventListener("DOMContentLoaded", function() {
    let productosEnCarrito = localStorage.getItem("productos-en-carrito");
    productosEnCarrito = JSON.parse(productosEnCarrito);
    var base_url = ".";

    const contenedorCarritoVacio = document.querySelector("#carrito-vacio");
    const contenedorCarritoProductos = document.querySelector("#carrito-productos");
    const contenedorCarritoAcciones = document.querySelector("#carrito-acciones");
    const contenedorCarritoComprado = document.querySelector("#carrito-comprado");

    function cargarProductosCarrito() {
        const usuarioId = window.usuarioId;

        fetch(`obtener_transacciones.php?usuario_id=${usuarioId}`)
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

                        const subtotal = producto.precio * producto.cantidad;
                        total += subtotal;

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
                            <div class="carrito-producto-estado">
                                <small>Estado del Pedido</small>
                                <p>${producto.estado_pedido}</p>
                            </div>
                            <div class="carrito-producto-precio">
                                <small>Precio</small>
                                <p>$${number_format(producto.precio, ',', '.')}</p>
                            </div>
                            <div class="carrito-producto-subtotal">
                                <small>Subtotal</small>
                                <p>$${number_format(subtotal, ',', '.')}</p>
                            </div>
                        `;

                        contenedorCarritoProductos.append(div);
                    });

                    const contenedorTotal = document.querySelector("#total");
                    contenedorTotal.innerText = `$${number_format(total, ',', '.')}`;

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

    cargarProductosCarrito();
});

function number_format(number, dec_point, thousands_sep) {
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
        sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep;

    var parts = n.toFixed(0).split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);

    return parts.join(dec);
}
