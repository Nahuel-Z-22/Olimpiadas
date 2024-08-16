const botonesMenu = document.querySelectorAll(".boton-menu");
const contenidoAdministracion = document.getElementById("contenido-administracion");

function mostrarFormularioAgregar() {
  document.getElementById("formularioAgregar").style.display = "block";
  document.getElementById("filtros").style.display = "none";
  document.getElementById("productos").style.display = "none";
}

function mostrarProductos() {
  contenidoAdministracion.innerHTML = ''; 
  const titulo = document.createElement('h1');
  const botonAgregar = document.createElement('button');
  botonAgregar.textContent = 'Agregar Producto';
  botonAgregar.className = 'boton-mostrar-productos';
  botonAgregar.onclick = mostrarFormularioAgregar;
  const botonMostrar = document.createElement('button');
  botonMostrar.textContent = 'Mostrar Productos';
  botonMostrar.className = 'boton-mostrar-productos';
  botonMostrar.onclick = mostrarProductos;
  const divFormulario = document.createElement('div');
  divFormulario.id = 'formularioAgregar';
  divFormulario.style.display = 'none'; 
  divFormulario.innerHTML = `
  <form id="formulario" enctype="multipart/form-data">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" required><br>
    <label for="descripcion">Descripción:</label><br>
    <textarea id="descripcion" name="descripcion" required></textarea><br>
    <label for="precio">Precio:</label><br>
    <input type="number" id="precio" name="precio" required><br>
    <label for="stock">Stock:</label><br>
    <input type="number" id="stock" name="stock" required><br>
    <label for="categoria">Categoría:</label><br>
    <select id="categoria" name="categoria_id" required>
      <option value="">Seleccione una categoría</option>
      <!-- Aquí se cargarán las opciones de categorías mediante JavaScript -->
    </select><br>
    <label for="marca">Marca:</label><br>
    <input type="text" id="marca" name="marca" required><br>
    <label for="modelo">Modelo:</label><br>
    <input type="text" id="modelo" name="modelo" required><br>
    <label for="imagen">Imagen:</label><br>
    <input type="file" id="imagen" name="imagen" accept="image/*" required onchange="mostrarMiniatura()"><br>
    <img id="miniatura" src="#" alt="Vista previa de la imagen" style="display:none; max-width: 200px; max-height: 200px;"><br><br>
    <button type="button" class="boton-mostrar-productos" onclick="agregarProducto()">Agregar Producto</button>
  </form>
  `;

  const divFiltros = document.createElement('div');
  divFiltros.id = 'filtros';
  divFiltros.style.display = 'none'; 
  divFiltros.innerHTML = `
    <label for="filtroCategoria">Filtrar por categorías:</label>
    <select id="filtroCategoria" onchange="filtrarProductos()">
      <option value="todos">Todos</option>
      <!-- Aquí se cargarán las opciones de categorías mediante JavaScript -->
    </select>
    <br>
    <label for="filtroNombre">Filtrar por nombre:</label>
    <input type="text" id="filtroNombre" oninput="filtrarProductos()">
  `;

  const divProductos = document.createElement('div');
  divProductos.id = 'productos';
  divProductos.style.display = 'none'; 
  divProductos.innerHTML = `
    <table id="tablaProductos" border="1">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Categoría</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Imagen</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="cuerpoTablaProductos">
        <!-- Aquí se cargarán los productos mediante JavaScript -->
      </tbody>
    </table>
  `;

  const divFormularioEditar = document.createElement('div');
  divFormularioEditar.id = 'formularioEditar';
  divFormularioEditar.style.display = 'none'; 
  divFormularioEditar.innerHTML = `
    <h2>Editar Producto</h2>
    <form id="formularioEditarProducto" enctype="multipart/form-data">
      <input type="hidden" id="producto_id" name="producto_id">
      <label for="editar_nombre">Nombre:</label><br>
      <input type="text" id="editar_nombre" name="editar_nombre" required><br>
      <label for="editar_descripcion">Descripción:</label><br>
      <textarea id="editar_descripcion" name="editar_descripcion" required></textarea><br>
      <label for="editar_precio">Precio:</label><br>
      <input type="number" id="editar_precio" name="editar_precio" required><br>
      <label for="editar_stock">Stock:</label><br>
      <input type="number" id="editar_stock" name="editar_stock" required><br>
      <label for="editar_categoria">Categoría:</label><br>
      <select id="editar_categoria" name="editar_categoria_id" required>
        <!-- Opciones de categorías se cargarán mediante JavaScript -->
      </select><br>
      <label for="editar_marca">Marca:</label><br>
      <input type="text" id="editar_marca" name="editar_marca" required><br>
      <label for="editar_modelo">Modelo:</label><br>
      <input type="text" id="editar_modelo" name="editar_modelo" required><br>
      <label for="editar_imagen">Imagen:</label><br>
      <input type="file" id="editar_imagen" name="editar_imagen" accept="image/*"><br><br>
      <button type="button" onclick="actualizarProducto()">Actualizar Producto</button>
      <input type="hidden" id="imagen_existente" name="imagen_existente" value="">
      <!-- Botón de cancelar -->
      <button type="button" onclick="cancelarEdicionProducto()">Cancelar</button>
    </form>
  `;

  contenidoAdministracion.appendChild(titulo);
  contenidoAdministracion.appendChild(botonAgregar);
  contenidoAdministracion.appendChild(botonMostrar);
  contenidoAdministracion.appendChild(divFormulario);
  contenidoAdministracion.appendChild(divFiltros);
  contenidoAdministracion.appendChild(divProductos);
  contenidoAdministracion.appendChild(divFormularioEditar);
  
  document.getElementById("formularioAgregar").style.display = "none";
  document.getElementById("filtros").style.display = "block";
  document.getElementById("productos").style.display = "block";
  cargarCategorias();
  cargarProductos();
}

function cargarCategorias() {
  fetch('obtener_categorias.php')
    .then(response => response.json())
    .then(data => {
      const selectCategoria = document.getElementById("categoria");
      const filtroCategoria = document.getElementById("filtroCategoria");
      const editarCategoria = document.getElementById("editar_categoria");
      selectCategoria.innerHTML = '<option value="">Seleccione una categoría</option>';
      filtroCategoria.innerHTML = '<option value="todos">Todos</option>';
      editarCategoria.innerHTML = '<option value="">Seleccione una categoría</option>';
      data.forEach(categoria => {
        selectCategoria.innerHTML += `<option value="${categoria.id}">${categoria.nombre}</option>`;
        filtroCategoria.innerHTML += `<option value="${categoria.id}">${categoria.nombre}</option>`;
        editarCategoria.innerHTML += `<option value="${categoria.id}">${categoria.nombre}</option>`;
      });
    });
}

function cargarProductos() {
var link = document.createElement('link');
link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';
link.rel = 'stylesheet';
document.head.appendChild(link);

  fetch('obtener_productos2.php')
    .then(response => response.json())
    .then(data => {
      const filtroCategoria = document.getElementById("filtroCategoria").value;
      const filtroNombre = document.getElementById("filtroNombre").value.toLowerCase();
      const tbody = document.getElementById("cuerpoTablaProductos");
      tbody.innerHTML = '';
      data.forEach(producto => {
        if ((filtroCategoria === "todos" || producto.categoria_id == filtroCategoria) && producto.nombre.toLowerCase().includes(filtroNombre)) {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${producto.nombre}</td>
            <td>${producto.descripcion}</td>
            <td>${producto.precio}</td>
            <td>${producto.stock}</td>
            <td>${producto.nombre_categoria}</td>
            <td>${producto.marca}</td>
            <td>${producto.modelo}</td>
            <td><img src="../${producto.imagen}" alt="Imagen de Producto" style="max-width: 100px; max-height: 100px;"></td>
            <td>
            <button onclick="eliminarProducto(${producto.id})" style="background: none; border: none; cursor: pointer;">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </button>
              <button onclick="editarProducto(${producto.id})" style="background: none; border: none; cursor: pointer;">
              <i class="fa fa-pencil" aria-hidden="true"></i>
            </button>
            </td>
          `;
          tbody.appendChild(tr);
        }
      });
    });
}

function filtrarProductos() {
  cargarProductos();
}

function agregarProducto() {
  const nombre = document.getElementById("nombre").value.trim();
  const descripcion = document.getElementById("descripcion").value.trim();
  const precio = parseFloat(document.getElementById("precio").value);
  const stock = parseInt(document.getElementById("stock").value);
  const categoria = document.getElementById("categoria").value;
  const marca = document.getElementById("marca").value.trim();
  const modelo = document.getElementById("modelo").value.trim();
  const imagen = document.getElementById("imagen").files[0];

  if (nombre === "" || descripcion === "" || isNaN(precio) || isNaN(stock) || categoria === "" || marca === "" || modelo === "" || !imagen) {
      alert("Por favor complete todos los campos.");
      return;
  }

  if (precio <= 0 || stock <= 0) {
      alert("El precio y el stock deben ser mayores que 0.");
      return;
  }

  if (/[eE]/.test(precio) || /[eE]/.test(stock)) {
      alert("El precio y el stock no pueden contener la letra 'e'.");
      return;
  }

  const formData = new FormData();
  formData.append("nombre", nombre);
  formData.append("descripcion", descripcion);
  formData.append("precio", precio);
  formData.append("stock", stock);
  formData.append("categoria_id", categoria);
  formData.append("marca", marca);
  formData.append("modelo", modelo);
  formData.append("imagen", imagen);

  fetch('agregar_producto.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert("Producto agregado correctamente");
          document.getElementById("formulario").reset();
          document.getElementById("miniatura").style.display = "none";
      } else {
          alert("Error al agregar el producto: " + data.error);
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
}

function mostrarMiniatura() {
  const fileInput = document.getElementById('imagen');
  const file = fileInput.files[0];

  if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
          const miniatura = document.getElementById('miniatura');
          miniatura.src = e.target.result;
          miniatura.style.display = 'block';
      };
      reader.readAsDataURL(file);
  } else {
      const miniatura = document.getElementById('miniatura');
      miniatura.src = '';
      miniatura.style.display = 'none';
  }
}

function eliminarProducto(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
    fetch('eliminar_producto.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ producto_id: id })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Producto eliminado correctamente");
        cargarProductos();
      } else {
        alert("Error al eliminar el producto: " + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
}

function editarProducto(id) {
  document.getElementById("formularioEditar").style.display = "block";
  
  fetch(`obtener_producto.php?id=${id}`)
    .then(response => response.json())
    .then(producto => {
      document.getElementById("producto_id").value = producto.id;
      document.getElementById("editar_nombre").value = producto.nombre;
      document.getElementById("editar_descripcion").value = producto.descripcion;
      document.getElementById("editar_precio").value = producto.precio;
      document.getElementById("editar_stock").value = producto.stock;
      document.getElementById("editar_categoria").value = producto.categoria_id;
      document.getElementById("editar_marca").value = producto.marca;
      document.getElementById("editar_modelo").value = producto.modelo;
      document.getElementById("imagen_existente").value = producto.imagen;
    })
    .catch(error => {
      console.error('Error:', error);
    });
    document.getElementById('formularioEditar').scrollIntoView({ behavior: 'smooth' });
}

function cancelarEdicionProducto() {
  document.getElementById("formularioEditar").style.display = "none";
}

function actualizarProducto() {
  const formulario = document.getElementById("formularioEditarProducto");
  const formData = new FormData(formulario);
  fetch('actualizar_producto.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Producto actualizado correctamente");
      document.getElementById("formularioEditar").style.display = "none";
      cargarProductos();
    } else {
      alert("Error al actualizar el producto: " + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

function mostrarUsuarios() {
  contenidoAdministracion.innerHTML = '';

  fetch('obtener_usuarios.php')
      .then(response => response.json())
      .then(usuarios => {
          let tabla = '<table>';
          tabla += '<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Dirección</th><th>Tipo de Usuario</th><th>Acciones</th></tr>';

          usuarios.forEach(usuario => {
              tabla += `<tr>
                          <td>${usuario.id}</td>
                          <td><input type="text" id="nombre_${usuario.id}" value="${usuario.nombre}" disabled></td>
                          <td><input type="text" id="apellido_${usuario.id}" value="${usuario.apellido}" disabled></td>
                          <td><input type="text" id="email_${usuario.id}" value="${usuario.email}" disabled></td>
                          <td><input type="text" id="direccion_${usuario.id}" value="${usuario.direccion || ''}" disabled></td>
                          <td>
                            <select id="tipo_usuario_${usuario.id}" disabled>
                              <option value="cliente" ${usuario.tipo_usuario === 'cliente' ? 'selected' : ''}>Cliente</option>
                              <option value="admin" ${usuario.tipo_usuario === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                          </td>
                          <td>
                          <button onclick="eliminarUsuario(${usuario.id})" style="background: none; border: none; cursor: pointer;">
                          <i class="fa fa-trash" aria-hidden="true"></i>
                          </button>
                          <button id="botonEditar_${usuario.id}" onclick="editarUsuario(${usuario.id})" style="background: none; border: none; cursor: pointer;">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                          </button>
                          </td>
                        </tr>`;
          });

          tabla += '</table>';
          contenidoAdministracion.innerHTML = tabla;
      });
}

function eliminarUsuario(id) {
  fetch('eliminar_usuario.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id }),
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          mostrarUsuarios();  // Refresh the user list
      } else {
          console.error('Error:', data);
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
}

function editarUsuario(id) {
  const nombreInput = document.getElementById(`nombre_${id}`);
  const apellidoInput = document.getElementById(`apellido_${id}`);
  const emailInput = document.getElementById(`email_${id}`);
  const direccionInput = document.getElementById(`direccion_${id}`);
  const tipoUsuarioInput = document.getElementById(`tipo_usuario_${id}`);

  // Habilitar o deshabilitar la edición de los inputs
  nombreInput.disabled = !nombreInput.disabled;
  apellidoInput.disabled = !apellidoInput.disabled;
  emailInput.disabled = !emailInput.disabled;
  direccionInput.disabled = !direccionInput.disabled;
  tipoUsuarioInput.disabled = !tipoUsuarioInput.disabled;

  const botonEditar = document.getElementById(`botonEditar_${id}`);
  if (nombreInput.disabled) {
      botonEditar.innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i>';
      actualizarUsuario(id, nombreInput.value, apellidoInput.value, emailInput.value, direccionInput.value, tipoUsuarioInput.value);
  } else {
      botonEditar.innerHTML = '<i class="fa fa-save" aria-hidden="true"></i>';
  }
}

function actualizarUsuario(id, nombre, apellido, email, direccion, tipo_usuario) {
  const data = {
      id,
      nombre,
      apellido,
      email,
      direccion,
      tipo_usuario
  };

  fetch('editar_usuario.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert('Usuario editado correctamente');
          mostrarUsuarios(); // Recargar la lista de usuarios
      } else {
          alert('Error al editar usuario');
      }
  })
  .catch(error => console.error('Error al editar usuario:', error));
}

function mostrarCategorias() {
  contenidoAdministracion.innerHTML = '';

  const titulo = document.createElement('h2');
  contenidoAdministracion.appendChild(titulo);

  const botonAgregar = document.createElement('button');
  botonAgregar.textContent = 'Agregar Categoría';
  botonAgregar.className = 'boton-mostrar-productos';
  botonAgregar.onclick = agregarCategoria;
  contenidoAdministracion.appendChild(botonAgregar);
  
  const tablaCategorias = document.createElement('table');
  tablaCategorias.innerHTML = `
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody></tbody>
  `;
  contenidoAdministracion.appendChild(tablaCategorias);

  const tbody = tablaCategorias.querySelector('tbody');

  fetch("obtener_categorias.php")
    .then(response => response.json())
    .then(categorias => {
      categorias.forEach(categoria => {
        const row = document.createElement('tr');
        row.id = `categoria_${categoria.id}`;
        row.innerHTML = `
          <td>${categoria.id}</td>
          <td><span id="nombre_${categoria.id}">${categoria.nombre}</span></td>
          <td>
          <button onclick="activarEdicionCategoria(${categoria.id})" style="background: none; border: none; cursor: pointer;">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </button>
          <button onclick="eliminarCategoria(${categoria.id})" style="background: none; border: none; cursor: pointer;">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </button>
        </td>        
        `;
        tbody.appendChild(row);
      });
    })
    .catch(error => console.error("Error al obtener categorías:", error));
}


function agregarCategoria() {
  const nuevaCategoria = prompt("Ingrese el nombre de la nueva categoría:");

  if (nuevaCategoria) {
    fetch("agregar_categoria.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `nombre=${nuevaCategoria}`
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          mostrarCategorias();
        } else {
          console.error("Error al agregar categoría:", data.message);
        }
      })
      .catch(error => console.error("Error al agregar categoría:", error));
  }
}

function activarEdicionCategoria(id) {
  const nombreSpan = document.getElementById(`nombre_${id}`);
  const nombreActual = nombreSpan.innerText;
  nombreSpan.innerHTML = `<input type="text" id="editNombre_${id}" value="${nombreActual}">`;

  const botonEditar = document.querySelector(`#categoria_${id} button:nth-of-type(1)`);
  botonEditar.innerHTML = "Guardar";
  botonEditar.onclick = () => guardarEdicionCategoria(id);
}
function guardarEdicionCategoria(id) {
  const nuevoNombre = document.getElementById(`editNombre_${id}`).value;
  fetch("editar_categoria.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `id=${id}&nombre=${nuevoNombre}`
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        mostrarCategorias();
      } else {
        console.error("Error al editar categoría:", data.message);
      }
    })
    .catch(error => console.error("Error al editar categoría:", error));
}

function eliminarCategoria(id) {
  if (confirm("¿Estás seguro de que deseas eliminar esta categoría?")) {
    fetch("eliminar_categoria.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `id=${id}`
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          mostrarCategorias();
        } else {
          console.error("Error al eliminar categoría:", data.message);
        }
      })
      .catch(error => console.error("Error al eliminar categoría:", error));
  }
}

function mostrarMetodosPago() {
  contenidoAdministracion.innerHTML = '';

  const titulo = document.createElement('h2');
  contenidoAdministracion.appendChild(titulo);

  const botonAgregar = document.createElement('button');
  botonAgregar.textContent = 'Agregar Método de Pago';
  botonAgregar.className = 'boton-mostrar-productos';
  botonAgregar.onclick = agregarMetodoPago;
  contenidoAdministracion.appendChild(botonAgregar);
  
  const tablaMetodosPago = document.createElement('table');
  tablaMetodosPago.innerHTML = `
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody></tbody>
  `;
  contenidoAdministracion.appendChild(tablaMetodosPago);

  const tbody = tablaMetodosPago.querySelector('tbody');

  fetch("obtener_metodo_pago.php")
    .then(response => response.json())
    .then(metodosPago => {
      metodosPago.forEach(metodoPago => {
        const row = document.createElement('tr');
        row.id = `metodoPago_${metodoPago.id}`;
        row.innerHTML = `
          <td>${metodoPago.id}</td>
          <td><span id="nombre_${metodoPago.id}">${metodoPago.nombre}</span></td>
          <td><span id="descripcion_${metodoPago.id}">${metodoPago.descripcion}</span></td>
          <td>
          <button onclick="activarEdicion(${metodoPago.id})" style="background: none; border: none; cursor: pointer;">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </button>
          <button onclick="eliminarMetodoPago(${metodoPago.id})" style="background: none; border: none; cursor: pointer;">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </button>
        </td>        
        `;
        tbody.appendChild(row);
      });
    })
    .catch(error => console.error("Error al obtener métodos de pago:", error));
}

function agregarMetodoPago() {
  const nuevoNombre = prompt("Ingrese el nombre del nuevo método de pago:");
  const nuevaDescripcion = prompt("Ingrese la descripción del nuevo método de pago:");

  if (nuevoNombre && nuevaDescripcion) {
    fetch("agregar_metodo_pago.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `nombre=${nuevoNombre}&descripcion=${nuevaDescripcion}`
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          mostrarMetodosPago();
        } else {
          console.error("Error al agregar método de pago:", data.message);
        }
      })
      .catch(error => console.error("Error al agregar método de pago:", error));
  }
}

function activarEdicion(id) {
  const nombreSpan = document.getElementById(`nombre_${id}`);
  const descripcionSpan = document.getElementById(`descripcion_${id}`);
  const nombreActual = nombreSpan.innerText;
  const descripcionActual = descripcionSpan.innerText;
  nombreSpan.innerHTML = `<input type="text" id="editNombre_${id}" value="${nombreActual}">`;
  descripcionSpan.innerHTML = `<input type="text" id="editDescripcion_${id}" value="${descripcionActual}">`;

  const botonEditar = document.querySelector(`#metodoPago_${id} button:nth-of-type(1)`);
  botonEditar.innerHTML = "Guardar";
  botonEditar.onclick = () => guardarEdicion(id);
}

function guardarEdicion(id) {
  const nuevoNombre = document.getElementById(`editNombre_${id}`).value;
  const nuevaDescripcion = document.getElementById(`editDescripcion_${id}`).value;
  fetch("editar_metodo_pago.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `id=${id}&nombre=${nuevoNombre}&descripcion=${nuevaDescripcion}`
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        mostrarMetodosPago();
      } else {
        console.error("Error al editar método de pago:", data.message);
      }
    })
    .catch(error => console.error("Error al editar método de pago:", error));
}

function eliminarMetodoPago(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este método de pago?")) {
    fetch("eliminar_metodo_pago.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `id=${id}`
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          mostrarMetodosPago();
        } else {
          console.error("Error al eliminar método de pago:", data.message);
        }
      })
      .catch(error => console.error("Error al eliminar método de pago:", error));
  }
}

function mostrarTransacciones() {
  contenidoAdministracion.innerHTML = `
      <form id="filtroForm">
          <label for="fechaInicio">Fecha de inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio">
          <label for="fechaFin">Fecha de fin:</label>
          <input type="date" id="fechaFin" name="fechaFin">
          <button type="submit">Filtrar</button>
      </form>
      <p>Total Gastado: <span id="totalGastado"></span></p>
      <table id="tablaTransacciones">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Nombre de usuario</th>
                  <th>Apellido de usuario</th>
                  <th>Nombre del producto</th>
                  <th>Cantidad</th>
                  <th>Total Gastado</th>
                  <th>Fecha de compra</th>
                  <th>Método de pago</th>
              </tr>
          </thead>
          <tbody id="tbodyTransacciones">
          </tbody>
      </table>
  `;

  const filtroForm = document.getElementById('filtroForm');
  const tablaTransacciones = document.getElementById('tbodyTransacciones');
  const totalGastadoSpan = document.getElementById('totalGastado');

  function obtenerTransacciones(fechaInicio, fechaFin) {
      const xhr = new XMLHttpRequest();
      let url = 'obtener_transacciones.php';
      if (fechaInicio !== '' && fechaFin !== '') {
          url += `?fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;
      }
      xhr.open('GET', url);
      xhr.onload = function() {
          if (xhr.status === 200) {
              const data = JSON.parse(xhr.responseText);
              mostrarTransacciones(data.transacciones);
              mostrarTotalGastado(data.total_gastado);
          }
      };
      xhr.send();
  }

  function mostrarTransacciones(transacciones) {
      tablaTransacciones.innerHTML = '';
      transacciones.forEach(function(transaccion) {
          const fila = document.createElement('tr');
          fila.innerHTML = `
              <td>${transaccion.id}</td>
              <td>${transaccion.nombre_usuario}</td>
              <td>${transaccion.apellido_usuario}</td>
              <td>${transaccion.nombre_producto}</td>
              <td>${transaccion.cantidad}</td>
              <td>${transaccion.total_gastado}</td>
              <td>${transaccion.fecha_compra}</td>
              <td>${transaccion.metodo_pago_nombre}</td>
          `;
          tablaTransacciones.appendChild(fila);
      });
  }

  function mostrarTotalGastado(totalGastado) {
      totalGastadoSpan.textContent = totalGastado.toFixed(2);
  }

  filtroForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const fechaInicio = document.getElementById('fechaInicio').value;
      const fechaFin = document.getElementById('fechaFin').value;
      obtenerTransacciones(fechaInicio, fechaFin);
  });

  obtenerTransacciones('', '');
}

















function mostrarPedidos() {
  contenidoAdministracion.innerHTML = `
      <form id="filtroPedidosForm">
          <label for="fechaInicioPedido">Fecha de inicio:</label>
          <input type="date" id="fechaInicioPedido" name="fechaInicio">
          <label for="fechaFinPedido">Fecha de fin:</label>
          <input type="date" id="fechaFinPedido" name="fechaFin">
          <label for="estadoPedido">Estado:</label>
          <select id="estadoPedido" name="estado">
              <option value="">Todos</option>
              <option value="pendiente">Pendiente</option>
              <option value="entregado">Entregado</option>
              <option value="anulado">Anulado</option>
          </select>
          <button type="submit">Filtrar</button>
      </form>
      <table id="tablaPedidos">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Usuario</th>
                  <th>Estado</th>
                  <th>Fecha del pedido</th>
                  <th>Acciones</th>
              </tr>
          </thead>
          <tbody id="tbodyPedidos">
          </tbody>
      </table>
  `;

  const filtroPedidosForm = document.getElementById('filtroPedidosForm');
  const tablaPedidos = document.getElementById('tbodyPedidos');

  function obtenerPedidos(fechaInicio, fechaFin, estado) {
    const xhr = new XMLHttpRequest();
    let url = 'obtener_pedidos.php';
    let params = [];
    if (fechaInicio !== '') params.push(`fechaInicio=${fechaInicio}`);
    if (fechaFin !== '') params.push(`fechaFin=${fechaFin}`);
    if (estado !== '') params.push(`estado=${estado}`);
    if (params.length) url += '?' + params.join('&');
    
    xhr.open('GET', url);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            mostrarPedidosEnTabla(data.pedidos);
        }
    };
    xhr.send();
  }

  function mostrarPedidosEnTabla(pedidos) {
      tablaPedidos.innerHTML = '';
      pedidos.forEach(function(pedido) {
          const fila = document.createElement('tr');
          fila.innerHTML = `
              <td>${pedido.id}</td>
              <td>${pedido.nombre_usuario}</td>
              <td>
                  <select id="estado_${pedido.id}" onchange="actualizarEstadoPedido(${pedido.id})">
                      <option value="pendiente" ${pedido.estado === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                      <option value="entregado" ${pedido.estado === 'entregado' ? 'selected' : ''}>Entregado</option>
                      <option value="anulado" ${pedido.estado === 'anulado' ? 'selected' : ''}>Anulado</option>
                  </select>
              </td>
              <td>${pedido.fecha_pedido}</td>
              <td>
                  <button onclick="eliminarPedido(${pedido.id})">Eliminar</button>
              </td>
          `;
          tablaPedidos.appendChild(fila);
      });
  }

  filtroPedidosForm.addEventListener('submit', function(event) {
    event.preventDefault();
    const fechaInicio = document.getElementById('fechaInicioPedido').value;
    const fechaFin = document.getElementById('fechaFinPedido').value;
    const estado = document.getElementById('estadoPedido').value;
    obtenerPedidos(fechaInicio, fechaFin, estado);
  });

  obtenerPedidos('', ''); // Cargar todos los pedidos inicialmente
}

function actualizarEstadoPedido(id) {
  const estado = document.getElementById(`estado_${id}`).value;
  fetch('editar_estado_pedido.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id, estado })
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert('Estado del pedido actualizado correctamente');
      } else {
          alert('Error al actualizar el estado del pedido');
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
}

function eliminarPedido(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este pedido?")) {
      fetch('eliminar_pedido.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id })
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert('Pedido eliminado correctamente');
              mostrarPedidos();
          } else {
              alert('Error al eliminar el pedido');
          }
      })
      .catch(error => {
          console.error('Error:', error);
      });
  }
}








botonesMenu.forEach(boton => {
    boton.addEventListener("click", () => {
        botonesMenu.forEach(b => b.classList.remove("active"));

        boton.classList.add("active");

        switch (boton.id) {
            case "agregar-productos":
                mostrarProductos();
                break;
            case "gestionar-usuarios":
                mostrarUsuarios();
                break;
            case "gestionar-categorias":
                mostrarCategorias();
                break;
            case "gestionar-metodopago":
              mostrarMetodosPago();
              break;
            case "gestionar-transacciones":
                mostrarTransacciones();
                break;
            case "gestionar-pedidos":
                mostrarPedidos();
                break;
        }
    });
});

mostrarProductos()