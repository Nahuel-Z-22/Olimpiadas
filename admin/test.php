<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Usuarios</title>
</head>
<body>

<h1>Lista de Usuarios</h1>

<script>
// Función para cargar la información de los usuarios
function cargarUsuarios() {
    fetch('obtener_usuarios.php')
    .then(response => response.json())
    .then(data => {
        const usuariosContainer = document.createElement('div'); // Crear contenedor de usuarios
        usuariosContainer.id = 'usuariosContainer'; // Asignar ID al contenedor
        document.body.appendChild(usuariosContainer); // Agregar contenedor al body
        
        data.forEach(usuario => {
            const usuarioDiv = document.createElement('div');
            usuarioDiv.innerHTML = `
                <p><strong>ID:</strong> ${usuario.id}</p>
                <p><strong>Nombre:</strong> <input type="text" id="nombre_${usuario.id}" value="${usuario.nombre}" disabled></p>
                <p><strong>Apellido:</strong> <input type="text" id="apellido_${usuario.id}" value="${usuario.apellido}" disabled></p>
                <p><strong>Email:</strong> <input type="text" id="email_${usuario.id}" value="${usuario.email}" disabled></p>
                <p><strong>Tipo de Usuario:</strong> <input type="text" id="tipo_usuario_${usuario.id}" value="${usuario.tipo_usuario}" disabled></p>
                <button onclick="eliminarUsuario(${usuario.id})">Eliminar</button>
                <button onclick="editarUsuario(${usuario.id})">Editar</button>
                <hr>
            `;
            usuariosContainer.appendChild(usuarioDiv);
        });
    })
    .catch(error => console.error('Error al cargar usuarios:', error));
}

// Función para eliminar un usuario
function eliminarUsuario(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        fetch('eliminar_usuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario eliminado correctamente');
                cargarUsuarios(); // Recargar la lista de usuarios
            } else {
                alert('Error al eliminar usuario');
            }
        })
        .catch(error => console.error('Error al eliminar usuario:', error));
    }
}

// Función para editar un usuario
function editarUsuario(id) {
    const nombreInput = document.getElementById(`nombre_${id}`);
    const apellidoInput = document.getElementById(`apellido_${id}`);
    const emailInput = document.getElementById(`email_${id}`);
    const tipoUsuarioInput = document.getElementById(`tipo_usuario_${id}`);
    
    nombreInput.disabled = !nombreInput.disabled;
    apellidoInput.disabled = !apellidoInput.disabled;
    emailInput.disabled = !emailInput.disabled;
    tipoUsuarioInput.disabled = !tipoUsuarioInput.disabled;

    // Cambiar el texto del botón de editar según el estado de los campos de texto
    const botonEditar = event.target;
    if (nombreInput.disabled) {
        botonEditar.innerText = "Editar";
        actualizarUsuario(id, nombreInput.value, apellidoInput.value, emailInput.value, tipoUsuarioInput.value);
    } else {
        botonEditar.innerText = "Guardar";
    }
}

// Función para actualizar un usuario
function actualizarUsuario(id, nombre, apellido, email, tipo_usuario) {
    const data = {
        id: id,
        nombre: nombre,
        apellido: apellido,
        email: email,
        tipo_usuario: tipo_usuario
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
            cargarUsuarios(); // Recargar la lista de usuarios
        } else {
            alert('Error al editar usuario');
        }
    })
    .catch(error => console.error('Error al editar usuario:', error));
}

// Cargar la lista de usuarios al cargar la página
document.addEventListener('DOMContentLoaded', cargarUsuarios);
</script>

</body>
</html>
