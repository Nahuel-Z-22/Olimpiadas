document.addEventListener("DOMContentLoaded", function() {
    let productosEnCarrito = localStorage.getItem("productos-en-carrito");
    productosEnCarrito = JSON.parse(productosEnCarrito);
    var base_url = ".";

    const contenedorCarritoVacio = document.querySelector("#carrito-vacio");
    const contenedorCarritoProductos = document.querySelector("#carrito-productos");
    const contenedorCarritoAcciones = document.querySelector("#carrito-acciones");
    const contenedorCarritoComprado = document.querySelector("#carrito-comprado");
    const contenedorTotal = document.querySelector("#total");

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
    
                    productosEnCarrito.forEach(producto => {
    
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
                                <p>$${number_format(producto.precio, ',', '.')}</p>
                            </div>
                            <div class="carrito-producto-subtotal">
                                <small>Subtotal</small>
                                <p>$${number_format(producto.precio * producto.cantidad, ',', '.')}</p>
                            </div>
                            <button class="carrito-producto-eliminar" data-producto-id="${producto.id}"><i></i></button>
                        `;
    
                        contenedorCarritoProductos.append(div);
                    });
    
                    actualizarTotal();
    
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
    

    function number_format(number, dec_point, thousands_sep) {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep;
    
        var parts = n.toFixed(0).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);
    
        return parts.join(dec);
    }
    

    cargarProductosCarrito();

    function actualizarTotal() {
        fetch('contador2.php?usuario_id=' + window.usuarioId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const totalCalculado = data.total_productos;
                    contenedorTotal.innerText = `$${number_format(totalCalculado, ',', '.')}`;
                } else {
                    console.error("Error al obtener el total de productos en el carrito:", data.error);
                }
            })
            .catch(error => {
                console.error("Error al obtener el total de productos en el carrito:", error);
            });
    }
    

    document.getElementById("procesar-transaccion").addEventListener("click", function() {
        var metodoPagoSeleccionado = document.getElementById("metodo-pago").value;

        if (metodoPagoSeleccionado) {
            fetch('procesar_transaccion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'metodo_pago=' + encodeURIComponent(metodoPagoSeleccionado),
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'index.php';
                } else {
                    throw new Error('Error al procesar la transacción');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            Toastify({
                text: "Por favor selecciona un método de pago",
                backgroundColor: "linear-gradient(to right, #E91E63, #9C27B0)",
                duration: 3000
            }).showToast();
        }
    });

    function cargarMetodosPago() {
        fetch('obtener_metodos_pago.php')
            .then(response => response.json())
            .then(data => {
                const selectMetodoPago = document.getElementById('metodo-pago');
                selectMetodoPago.innerHTML = "";
                data.forEach(metodo => {
                    const option = document.createElement('option');
                    option.value = metodo.id;
                    option.text = metodo.nombre;
                    selectMetodoPago.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error al obtener métodos de pago:", error);
            });
    }

    cargarMetodosPago();
});
