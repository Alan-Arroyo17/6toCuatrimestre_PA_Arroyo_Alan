const URL_BASE = 'http://192.168.1.140/videojuegos_app/';

// GET ../api/api-videojuegos.php — listar todas
//Videojuegos

async function crearVideojuego() {
    const img = document.getElementById("imagen").files[0];

    try {
        let res;

        if (img) {
            const formData = new FormData();

            formData.append("titulo", document.getElementById("titulo").value);
            formData.append("descripcion", document.getElementById("descripcion").value);
            formData.append("precio", document.getElementById("precio").value);
            formData.append("lanzamiento", document.getElementById("lanzamiento").value);
            formData.append("calificacion", document.getElementById("calificacion").value);
            formData.append("id_genero", document.getElementById("genero-select").value);
            formData.append("id_plataforma", document.getElementById("plataforma-select").value);
            formData.append("imagen", img);

            res = await fetch(URL_BASE + "api-videojuego.php", {
                method: "POST",
                body: formData
            });

        } else {
            const datos = {
                titulo: document.getElementById("titulo").value,
                descripcion: document.getElementById("descripcion").value,
                precio: parseFloat(document.getElementById("precio").value),
                lanzamiento: document.getElementById("lanzamiento").value,
                calificacion: parseFloat(document.getElementById("calificacion").value),
                imagen: "",
                id_genero: parseInt(document.getElementById("genero-select").value),
                id_plataforma: parseInt(document.getElementById("plataforma-select").value)
            };

            res = await fetch(URL_BASE + "api-videojuego.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(datos)
            });
        }

        const data = await res.json();

        if (res.ok) {
            alert("Videojuego creado con éxito");
            window.location.href = "index.html";
        } else {
            alert(data.message || "Error al crear el videojuego");
        }

    } catch (error) {
        console.error(error);
        alert("Error de conexión");
    }
}

async function getVideojuegos() {
    const tbody = document.querySelector('#tabla-videojuegos tbody');
    if (!tbody) {
        return;
    }
    try {
        const response = await fetch(`${URL_BASE}api-videojuego.php`);
        const data = await response.json();
        tbody.innerHTML = '';
        data.forEach(videojuego => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${videojuego.id}</td>
                <td>${videojuego.titulo}</td>
                <td>${videojuego.descripcion}</td>
                <td>${videojuego.precio}</td>
                <td>${videojuego.lanzamiento}</td>
                <td>${videojuego.calificacion}</td>
                <td><img src="${URL_BASE}api-imagen.php?nombre=${encodeURIComponent(videojuego.imagen)}" alt="${videojuego.titulo}" width="100"></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="redirigirEditarVideojuego(${videojuego.id})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarVideojuego(${videojuego.id})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        })
    } catch (error) {
        console.error('Error al obtener las videojuegos: ', error);
    }
}


async function getVideojuegoPorID(id) {
    try {
        const response = await fetch(`${URL_BASE}api-videojuego.php?id=${id}`);
        if (response.ok) {
            const videojuego = await response.json();
            return videojuego;
        } else {
            console.log('Error al obtener el videojuego: ', response.statusText);
        }
    } catch (error) {
        console.error('Error al obtener el videojuego: ', error);
    }
}

async function poblarFormularioEdicion(id) {
    const videojuego = await getVideojuegoPorID(id);

    if (videojuego) {
        document.getElementById('titulo').value = videojuego.titulo;
        document.getElementById('descripcion').value = videojuego.descripcion;
        document.getElementById('precio').value = videojuego.precio;
        document.getElementById('lanzamiento').value = videojuego.lanzamiento;
        document.getElementById('calificacion').value = videojuego.calificacion;
        document.getElementById('genero-select').value = videojuego.id_genero;
        document.getElementById('plataforma-select').value = videojuego.id_plataforma;

        const imagenActual = document.getElementById("preview-imagen");
        if (imagenActual && videojuego.imagen) {
            imagenActual.src = `${URL_BASE}api-imagen.php?nombre=${encodeURIComponent(videojuego.imagen)}`;
        }

    } else {
        console.log('No se pudo poblar el formulario de edición: videojuego no encontrado');
    }
}

async function buscarVideojuegos(nombre) {
    const tbody = document.querySelector('#tabla-videojuegos tbody');
    if (!tbody) {
        return;
    }
    try {
        const response = await fetch(`${URL_BASE}api-videojuego.php?titulo=${encodeURIComponent(nombre)}`);
        const data = await response.json();
        tbody.innerHTML = '';
        data.forEach(videojuego => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${videojuego.id}</td>
                <td>${videojuego.titulo}</td>
                <td>${videojuego.descripcion}</td>
                <td>${videojuego.precio}</td>
                <td>${videojuego.lanzamiento}</td>
                <td>${videojuego.calificacion}</td>
                <td><img src="${URL_BASE}api-imagen.php?nombre=${encodeURIComponent(videojuego.imagen)}" alt="${videojuego.titulo}" width="100"></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="redirigirEditarVideojuego(${videojuego.id})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarVideojuego(${videojuego.id})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        })
    } catch (error) {
        console.error('Error al buscar los videojuegos: ', error);
    }
}

async function eliminarVideojuego(id) {
    try {
        const response = await fetch(`${URL_BASE}api-videojuego.php?id=${id}`, {
            method: 'DELETE'
        });
        if (response.ok) {
            window.location.replace('index.html'); 
            return;
        } else {
            const errorData = await response.json().catch(() => null); 
            const mensaje = errorData?.mensaje || response.statusText || 'Error al eliminar el videojuego';
            console.log(mensaje);
        }
    } catch (error) {
        console.error('Error al eliminar el videojuego: ', error);
    }
}

async function cargarGeneros() {
    const select = document.getElementById("genero-select");
    try {
        const response = await fetch(`${URL_BASE}api-genero.php`);
        const generos = await response.json();

        generos.forEach(genero => {
            const option = document.createElement("option");
            option.value = genero.id;
            option.textContent = genero.nombre;
            select.appendChild(option);
        });

    } catch (error) {
        console.error(error);
    }
}

async function getVideojuegosPorGenero(idGenero) {
    const tbody = document.querySelector('#tabla-videojuegos tbody');
    const response = await fetch(`${URL_BASE}api-videojuego.php`);
    const data = await response.json();
    tbody.innerHTML = "";
    data.forEach(videojuego => {
        if (videojuego.id_genero == idGenero) {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${videojuego.id}</td>
                <td>${videojuego.titulo}</td>
                <td>${videojuego.descripcion}</td>
                <td>${videojuego.precio}</td>
                <td>${videojuego.lanzamiento}</td>
                <td>${videojuego.calificacion}</td>
                <td>
                    <img src="${URL_BASE}api-imagen.php?nombre=${encodeURIComponent(videojuego.imagen)}" width="100">
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="redirigirEditarVideojuego(${videojuego.id})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarVideojuego(${videojuego.id})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        }
    });
}

async function cargarPlataformas() {
    const select = document.getElementById("plataforma-select");
    try {
        const response = await fetch(`${URL_BASE}api-plataforma.php`);
        const plataformas = await response.json();

        plataformas.forEach(plataforma => {
            const option = document.createElement("option");
            option.value = plataforma.id;
            option.textContent = plataforma.nombre;
            select.appendChild(option);
        });

    } catch (error) {
        console.error(error);
    }
}

async function getVideojuegosPorPlataforma(idPlataforma) {
    const tbody = document.querySelector('#tabla-videojuegos tbody');
    const response = await fetch(`${URL_BASE}api-videojuego.php`);
    const data = await response.json();
    tbody.innerHTML = "";
    data.forEach(videojuego => {
        if (videojuego.id_plataforma == idPlataforma) {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${videojuego.id}</td>
                <td>${videojuego.titulo}</td>
                <td>${videojuego.descripcion}</td>
                <td>${videojuego.precio}</td>
                <td>${videojuego.lanzamiento}</td>
                <td>${videojuego.calificacion}</td>
                <td>
                    <img src="${URL_BASE}api-imagen.php?nombre=${encodeURIComponent(videojuego.imagen)}" width="100">
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="redirigirEditarVideojuego(${videojuego.id})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarVideojuego(${videojuego.id})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        }
    });
}

async function redirigirEditarVideojuego(id) {
    window.location.href = `editar.html?id=${id}`; 
}

async function eliminarVideojuego(id) {
    try {
        const response = await fetch(`${URL_BASE}api-videojuego.php?id=${id}`, {
            method: 'DELETE'
        });
        if (response.ok) {
            window.location.replace('index.html'); 
            return;
        } else {
            const errorData = await response.json().catch(() => null); 
            const mensaje = errorData?.mensaje || response.statusText || 'Error al eliminar el videojuego';
            console.log(mensaje);
        }
    } catch (error) {
        console.error('Error al eliminar el videojuego: ', error);
    }
}

async function getGeneros(){
    const tbody = document.querySelector('#tabla-generos tbody');
    if (!tbody) {
        return;
    }
    try {
        const response = await fetch(`${URL_BASE}api-genero.php`);
        const data = await response.json();
        tbody.innerHTML = '';
        data.forEach(genero => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${genero.id}</td>
                <td>${genero.nombre}</td>
            `;
            tbody.appendChild(tr);
        })
    } catch (error) {
        console.error('Error al obtener los generos: ', error);
    }
}


async function getPlataformas(){
    const tbody = document.querySelector("#tabla-plataformas tbody");
    if(!tbody){
        return;
    }
    try {
        const response = await fetch(`${URL_BASE}api-plataforma.php`);
        const data = await response.json();
        tbody.innerHTML = '';
        data.forEach(plataforma =>{
            const tr = document.createElement('tr');
            tr.innerHTML=`
                <td>${plataforma.id}</td>
                <td>${plataforma.nombre}</td>
            `;
            tbody.appendChild(tr);
        })
    } catch (error) {
        console.error('Error al obtener las plataformas: ', error);
    }
}

async function actualizarVideojuego(id) {
    const img = document.getElementById("imagen").files[0];
    try {

        let response;

        if (img) {

            const formData = new FormData();

            formData.append("titulo", document.getElementById("titulo").value);
            formData.append("descripcion", document.getElementById("descripcion").value);
            formData.append("precio", document.getElementById("precio").value);
            formData.append("lanzamiento", document.getElementById("lanzamiento").value);
            formData.append("calificacion", document.getElementById("calificacion").value);
            formData.append("id_genero", document.getElementById("genero-select").value);
            formData.append("id_plataforma", document.getElementById("plataforma-select").value);
            formData.append("imagen", img);

            response = await fetch(`${URL_BASE}api-videojuego.php?id=${id}`, {
                method: 'PUT',
                body: formData
            });

        } else {

            const videojuegoActualizado = {
                titulo: document.getElementById("titulo").value,
                descripcion: document.getElementById("descripcion").value,
                precio: document.getElementById("precio").value,
                lanzamiento: document.getElementById("lanzamiento").value,
                calificacion: document.getElementById("calificacion").value,
                id_genero: document.getElementById("genero-select").value,
                id_plataforma: document.getElementById("plataforma-select").value
            };

            response = await fetch(`${URL_BASE}api-videojuego.php?id=${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(videojuegoActualizado)
            });

        }

        if (response.ok) {
            window.location.replace('index.html');
            return;
        } else {
            const errorData = await response.json().catch(() => null);
            const mensaje = errorData?.mensaje || response.statusText || 'Error al actualizar el videojuego';
            console.log(mensaje);
        }

    } catch (error) {
        console.error('Error al actualizar el videojuego: ', error);
    }
}

async function eventoCrearVideojuego() {
    const nombre = document.getElementById('titulo').value.trim(); 
    const descripcion = document.getElementById('descripcion').value.trim(); 
    const precio = document.getElementById('precio').value.trim();
    const lanzamiento = document.getElementById('lanzamiento').value.trim();
    const calificacion = document.getElementById('calificacion').value.trim();
    const id_genero = document.getElementById('genero-select').value;
    const id_plataforma = document.getElementById('plataforma-select').value;
    if (!nombre) {
        console.log('El titulo es obligatorio');
        return;
    }
    await crearVideojuego(nombre, descripcion, precio, lanzamiento, calificacion, id_genero, id_plataforma);
}

async function obtenerId() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    if (id) {
        return id;
    } else {
        console.log('No se proporcionó un ID de videojuego para editar');
        return null;
    }
}
document.addEventListener('DOMContentLoaded', () => {
    getVideojuegos();
    cargarGeneros();
    cargarPlataformas();
    getGeneros();
    getPlataformas();
  
    const videojuegoForm = document.getElementById('crear-videojuego');
    const videojuegoEditForm = document.getElementById('formEditar');
    if (videojuegoForm) {
        videojuegoForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Evita que el formulario se envíe de la manera tradicional
            eventoCrearVideojuego();
        });
    }

    const generoSelect = document.getElementById("genero-select");
    generoSelect.addEventListener("change", () => {
        if (generoSelect.value === "") {
            getVideojuegos();
        } else {
            getVideojuegosPorGenero(generoSelect.value);
        }
    });

    const plataformaSelect = document.getElementById("plataforma-select");
    plataformaSelect.addEventListener("change", () => {
        if (plataformaSelect.value === "") {
            getVideojuegos();
        } else {
            getVideojuegosPorPlataforma(plataformaSelect.value);
        }
    });

    if (videojuegoEditForm) {
        obtenerId().then((id) => { 
            if (id) {
                poblarFormularioEdicion(id);
            } else {
                console.log('No se proporcionó un ID de videojuego para editar');
            }
        });
        videojuegoEditForm.addEventListener('submit', (event) => {
            event.preventDefault();
            obtenerId().then((id) => {
                if (id) {
                    actualizarVideojuego(id);
                } else {
                    console.log('No se proporcionó un ID de videojuego para actualizar');
                }
            });
        });
    }

    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const nombre = searchInput.value.trim();
            if (nombre === '') {
                getVideojuegos();
            } else {
                buscarVideojuegos(nombre);
            }
        });
    }
});