let productos = [];

function obtenerProductos() {
    return fetch('obtener_productos.php')
        .then(response => response.json())
        .then(data => {
            productos = data;
            console.log(productos);
            cargarProductos(productos);
        })
        .catch(error => console.error('Error al obtener productos:', error));
}

const contenedorProductos = document.querySelector("#contenedor-productos");
const botonesCategorias = document.querySelectorAll(".boton-categoria");
const tituloPrincipal = document.querySelector("#titulo-principal");
let botonesAgregar = document.querySelectorAll(".producto-agregar");
const numerito = document.querySelector("#numerito");


botonesCategorias.forEach(boton => boton.addEventListener("click", () => {
    aside.classList.remove("aside-visible");
}))

function number_format(number, decimals, dec_point, thousands_sep) {

    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number, 
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
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

function cargarProductos(productosElegidos) {
    contenedorProductos.innerHTML = "";

    productosElegidos.forEach(producto => {
        const div = document.createElement("div");
        div.classList.add("producto");

        let botonAgregar, claseBoton;
        if (producto.stock > 0) {
            botonAgregar = `<button class="producto-agregar" id="${producto.id}">Agregar</button>`;
            claseBoton = "producto-agregar";
        } else {
            botonAgregar = `<button class="producto-sin-stock" disabled>Sin stock</button>`;
            claseBoton = "producto-sin-stock";
        }

        const enlaceDetalle = `<a href="detalle_producto.php?id=${producto.id}" class="enlace-detalle">`;

        div.innerHTML = `
            ${enlaceDetalle}
                <img class="producto-imagen" src=".${producto.imagen}" alt="${producto.nombre}">
                <div class="producto-detalles">
                    <h3 class="producto-titulo">${producto.nombre}</h3>
                    <p class="producto-precio">$${number_format(producto.precio, 0, ',', '.')}</p>
                </a>
                ${botonAgregar}
            </div>
        `;

        contenedorProductos.append(div);
    });

    actualizarBotonesAgregar();
}




botonesCategorias.forEach(boton => {
    boton.addEventListener("click", (e) => {
        botonesCategorias.forEach(boton => boton.classList.remove("active"));
        e.currentTarget.classList.add("active");

        if (e.currentTarget.id != "todos") {
            const categoriaId = e.currentTarget.id;
            cargarProductosPorCategoria(categoriaId);
        } else {
            tituloPrincipal.innerText = "Todos los productos";
            obtenerProductos();
        }
    });
});

function cargarProductosPorCategoria(categoriaId) {
    fetch(`obtener_productos.php?categoria=${categoriaId}`)
        .then(response => response.json())
        .then(data => {
            productos = data;
            cargarProductos(productos);

            const categoriaNombre = data.length > 0 ? data[0].nombre_categoria : "Categoría Desconocida";

            tituloPrincipal.innerText = `Productos de la categoría ${categoriaNombre}`;
        })
        .catch(error => console.error('Error al obtener productos por categoría:', error));
}

function agregarAlCarrito(e) {
    const boton = e.currentTarget;

    // Deshabilitar el botón temporalmente
    boton.disabled = true;

    const idBoton = boton.id;

    fetch("verificar_sesion.php")
        .then(response => response.json())
        .then(data => {
            if (data.iniciado) {
                console.log("Agregando al carrito...");
                
                if (!document.querySelector(".alerta-agregar")) {
                    Toastify({
                        text: "Producto agregado",
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
                        onClick: function () { }
                    }).showToast();
                }

                const productoAgregado = productos.find(producto => producto.id === idBoton);

                if (productosEnCarrito.some(producto => producto.id === idBoton)) {
                    const index = productosEnCarrito.findIndex(producto => producto.id === idBoton);
                    productosEnCarrito[index].cantidad++;
                } else {
                    productoAgregado.cantidad = 1;
                    productosEnCarrito.push(productoAgregado);
                }

                actualizarNumerito();

                const productoid = productoAgregado.id;
                const cantidad = 1;

                const formData = new FormData();
                formData.append("productoid", productoid);
                formData.append("cantidad", cantidad);

                fetch("insertar_producto.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        console.error('Error en la respuesta del servidor');
                        throw new Error('Error en la respuesta del servidor');
                    }
                    console.log('Producto insertado correctamente');
                    actualizarNumerito();
                })
                .catch(error => {
                    console.error('Error al insertar producto:', error);
                })
                .finally(() => {
                    // Habilitar el botón después de que se complete la solicitud
                    boton.disabled = false;
                });
            } else {
                Toastify({
                    text: "Iniciar sesión para agregar un producto",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff6b6b, #ff9494)", // Cambio de color a rojo
                        borderRadius: "2rem",
                        textTransform: "uppercase",
                        fontSize: ".75rem"
                    },
                    offset: {
                        x: '1.5rem',
                        y: '1.5rem'
                    },
                    onClick: function () {
                        window.location.href = "login.php";
                    }
                }).showToast();
                
                // Habilitar el botón si el usuario no está iniciado
                boton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error al verificar la sesión:', error);
            // Habilitar el botón en caso de error
            boton.disabled = false;
        });
}




function actualizarBotonesAgregar() {
    botonesAgregar = document.querySelectorAll(".producto-agregar");

    botonesAgregar.forEach(boton => {
        boton.addEventListener("click", agregarAlCarrito);
    });
}

let productosEnCarrito;

let productosEnCarritoLS = localStorage.getItem("productos-en-carrito");

if (productosEnCarritoLS) {
    productosEnCarrito = JSON.parse(productosEnCarritoLS);
} else {
    productosEnCarrito = [];
}

function actualizarNumerito() {
    fetch('contador3.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                numerito.innerText = data.total_productos;
            } else {
                console.error('Error al obtener el número de productos del carrito:', data.error);
            }
        })
        .catch(error => console.error('Error al obtener el número de productos del carrito:', error));
}

document.addEventListener("DOMContentLoaded", function() {
    actualizarNumerito();
});

obtenerProductos();
