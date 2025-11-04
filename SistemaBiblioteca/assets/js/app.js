// assets/js/app.js

// Función para hacer fetch a la API
async function apiFetch(url, method = 'GET', data = null) {
    const options = { method };
    if (data) {
        options.headers = { 'Content-Type': 'application/json' };
        options.body = JSON.stringify(data);
    }
    const res = await fetch(url, options);
    return await res.json();
}

// ====================
// Libros
// ====================
async function buscarLibros(query = '') {
    const resp = await apiFetch(`/api/libros.php?q=${encodeURIComponent(query)}`);
    if (resp.success) {
        const tbody = document.querySelector('#tabla-libros tbody');
        tbody.innerHTML = '';
        resp.body.forEach(libro => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${libro.titulo}</td>
                <td>${libro.autores ? libro.autores.nombre : ''}</td>
                <td>${libro.genero}</td>
                <td>${libro.anio_publicacion}</td>
                <td>${libro.disponible ? 'Sí' : 'No'}</td>
                <td>
                    <button onclick="editarLibro(${libro.id})">✏️</button>
                    <button onclick="eliminarLibro(${libro.id})">🗑️</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
}

async function eliminarLibro(id) {
    if (!confirm('¿Eliminar este libro?')) return;
    const resp = await apiFetch(`/api/libros.php?id=${id}`, 'DELETE');
    if (resp.success) buscarLibros();
}

async function editarLibro(id) {
    const nuevoTitulo = prompt('Nuevo título del libro:');
    if (!nuevoTitulo) return;
    const resp = await apiFetch(`/api/libros.php?id=${id}`, 'PATCH', { titulo: nuevoTitulo });
    if (resp.success) buscarLibros();
}

// ====================
// Autores
// ====================
async function buscarAutores() {
    const resp = await apiFetch(`/api/autores.php`);
    if (resp.success) {
        const tbody = document.querySelector('#tabla-autores tbody');
        tbody.innerHTML = '';
        resp.body.forEach(a => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${a.nombre}</td>
                <td>${a.nacionalidad}</td>
                <td>
                    <button onclick="editarAutor(${a.id})">✏️</button>
                    <button onclick="eliminarAutor(${a.id})">🗑️</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
}

async function eliminarAutor(id) {
    if (!confirm('¿Eliminar este autor?')) return;
    const resp = await apiFetch(`/api/autores.php?id=${id}`, 'DELETE');
    if (resp.success) buscarAutores();
}

async function editarAutor(id) {
    const nuevoNombre = prompt('Nuevo nombre del autor:');
    if (!nuevoNombre) return;
    const resp = await apiFetch(`/api/autores.php?id=${id}`, 'PATCH', { nombre: nuevoNombre });
    if (resp.success) buscarAutores();
}

// ====================
// Usuarios (solo admin)
// ====================
async function buscarUsuarios() {
    const resp = await apiFetch(`/api/usuarios.php`);
    if (resp.success) {
        const tbody = document.querySelector('#tabla-usuarios tbody');
        tbody.innerHTML = '';
        resp.body.forEach(u => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${u.nombre}</td>
                <td>${u.email}</td>
                <td>${u.rol}</td>
                <td>
                    <button onclick="editarUsuario(${u.id})">✏️</button>
                    <button onclick="eliminarUsuario(${u.id})">🗑️</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
}

async function eliminarUsuario(id) {
    if (!confirm('¿Eliminar este usuario?')) return;
    const resp = await apiFetch(`/api/usuarios.php?id=${id}`, 'DELETE');
    if (resp.success) buscarUsuarios();
}

async function editarUsuario(id) {
    const nuevoNombre = prompt('Nuevo nombre del usuario:');
    if (!nuevoNombre) return;
    const resp = await apiFetch(`/api/usuarios.php?id=${id}`, 'PATCH', { nombre: nuevoNombre });
    if (resp.success) buscarUsuarios();
}

// ====================
// Préstamos
// ====================
async function buscarPrestamos() {
    const resp = await apiFetch(`/api/prestamos.php`);
    if (resp.success) {
        const tbody = document.querySelector('#tabla-prestamos tbody');
        tbody.innerHTML = '';
        resp.body.forEach(p => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${p.usuarios ? p.usuarios.nombre : ''}</td>
                <td>${p.libros ? p.libros.titulo : ''}</td>
                <td>${p.fecha_prestamo}</td>
                <td>${p.fecha_devolucion || ''}</td>
                <td>${p.devuelto ? 'Sí' : 'No'}</td>
                <td>
                    ${!p.devuelto ? `<button onclick="marcarDevuelto(${p.id}, ${p.id_libro})">✔️ Devolver</button>` : ''}
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
}

async function marcarDevuelto(idPrestamo, idLibro) {
    const resp = await apiFetch(`/api/prestamos.php?id=${idPrestamo}`, 'PATCH', { devuelto: true, id_libro: idLibro });
    if (resp.success) buscarPrestamos();
}

// ====================
// Inicializar al cargar
// ====================
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#tabla-libros')) buscarLibros();
    if (document.querySelector('#tabla-autores')) buscarAutores();
    if (document.querySelector('#tabla-usuarios')) buscarUsuarios();
    if (document.querySelector('#tabla-prestamos')) buscarPrestamos();
});
