<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once './includes/conexion.php';

if (!isset($conexion)) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

$queryCat = "SELECT id, nombre FROM categorias";
$resultCat = mysqli_query($conexion, $queryCat);

$queryMarca = "SELECT id, nombre FROM marcas";
$resultMarca = mysqli_query($conexion, $queryMarca);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir PDFs - Admin ITEDISA</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-100 pt-36 p-8">
    <?php include 'menu_admin.php'; ?>

    <!-- Formulario -->
    <div class="max-w-xl mx-auto bg-white shadow-lg p-6 rounded-xl mb-10">
        <h1 class="text-2xl font-bold mb-6 text-center">Subir PDFs de Productos</h1>

        <form id="formPDF" enctype="multipart/form-data">
            <label class="block font-medium mb-2">Selecciona Categoría:</label>
            <select name="categoria_id" id="categoria" required class="w-full p-2 mb-4 border rounded">
                <option value="">-- Selecciona una categoría --</option>
                <?php while ($cat = mysqli_fetch_assoc($resultCat)): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                <?php endwhile; ?>
            </select>

            <label class="block font-medium mb-2">Selecciona Marca:</label>

            <input type="text" id="buscar-marca" placeholder="Buscar marca..." 
                class="w-full p-2 mb-2 border rounded" autocomplete="off" />

            <select name="marca_id" id="marca" required class="w-full p-2 mb-4 border rounded" size="6" style="height:auto;">
                <option value="">-- Selecciona una marca --</option>
                <?php while ($marca = mysqli_fetch_assoc($resultMarca)): ?>
                    <option value="<?= $marca['id'] ?>"><?= htmlspecialchars($marca['nombre']) ?></option>
                <?php endwhile; ?>
            </select>

            <label class="block font-medium mb-2">Selecciona uno o más archivos PDF:</label>
            <input type="file" name="pdfs[]" id="pdfs" accept="application/pdf" multiple required class="w-full p-2 mb-4 border rounded bg-white">

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                Procesar PDFs
            </button>
        </form>
    </div>

    <!-- Tabla -->
    <div class="bg-white shadow-lg p-6 rounded-xl w-full overflow-x-auto">
        <table id="tablaResultados" class="min-w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2 border text-center">✔</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Descripción</th>
                    <th class="px-4 py-2 border">Imagen</th>
                    <th class="px-4 py-2 border">Categoría</th>
                    <th class="px-4 py-2 border">Marca</th>
                    <th class="px-4 py-2 border">Características</th>
                    <th class="px-4 py-2 border">Ficha Técnica</th>
                    <th class="px-4 py-2 border">Error</th>
                </tr>
            </thead>
            <tbody>
                <!-- Llenado dinámico -->
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-right">
        <button id="btnGuardar" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Guardar seleccionados
        </button>
    </div>

    <!-- Modal -->
    <div id="modalEditor" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-lg">
            <h2 id="modalTitulo" class="text-xl font-bold mb-4">Editar</h2>
            <textarea id="modalTexto" class="w-full border p-2 rounded h-40"></textarea>
            <div class="flex justify-end mt-4">
                <button id="cerrarModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</button>
                <button id="guardarModal" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </div>
    </div>

<script>
let campoEditando = null;
let filaEditando = null;

document.getElementById('btnGuardar').addEventListener('click', async function() {
    let seleccionados = [];
    let filas = [];

    document.querySelectorAll('#tablaResultados tbody tr').forEach(row => {
        let checkbox = row.querySelector('.marcar-fila');
        if (checkbox && checkbox.checked) {
            seleccionados.push({
                nombre: row.cells[1].textContent.trim(),
                descripcion: row.dataset.descripcion || '',
                imagen: row.dataset.imagen || '',
                categoria_id: row.cells[4].textContent.trim(),
                marca_id: row.cells[5].textContent.trim(),
                caracteristicas: row.dataset.caracteristicas || '',
                ficha_tecnica: row.dataset.ficha_tecnica || ''
            });
            filas.push(row);
        }
    });

    if (seleccionados.length === 0) {
        alert("No hay registros seleccionados para guardar.");
        return;
    }

    try {
        let response = await fetch('guardar_productos.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ productos: seleccionados })
        });

        let result = await response.json();

        if (result.success) {
            alert(`Se guardaron ${filas.length} productos correctamente.`);
            filas.forEach(fila => {
                fila.style.backgroundColor = '#d1d5db'; // gris Tailwind
                fila.querySelector('.marcar-fila').disabled = true;
            });
        } else {
            alert("Error al guardar productos: " + (result.error || 'Desconocido'));
        }
    } catch (error) {
        alert("Error en la comunicación con el servidor.");
        console.error(error);
    }
});

document.getElementById('formPDF').addEventListener('submit', async function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let response = await fetch('http://localhost:5000/procesar', {
        method: 'POST',
        body: formData
    });

    let data = await response.json();
    let tbody = document.querySelector('#tablaResultados tbody');
    tbody.innerHTML = '';

    if (data.resultado) {
        data.resultado.forEach((item, index) => {
            let row = document.createElement('tr');

            row.innerHTML = `
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="marcar-fila">
                </td>
                <td class="border px-4 py-2">${item.nombre || ''}</td>
                <td class="border px-4 py-2 text-center">
                    <button onclick="abrirModal('descripcion', ${index})"><i data-feather="eye"></i></button>
                </td>
                <td class="border px-4 py-2 text-center">
                    <button onclick="abrirModal('imagen', ${index})"><i data-feather="image"></i></button>
                </td>
                <td class="border px-4 py-2">${item.categoria_id || ''}</td>
                <td class="border px-4 py-2">${item.marca_id || ''}</td>
                <td class="border px-4 py-2 text-center">
                    <button onclick="abrirModal('caracteristicas', ${index})"><i data-feather="eye"></i></button>
                </td>
                <td class="border px-4 py-2 text-center">
                    <button onclick="abrirModal('ficha_tecnica', ${index})"><i data-feather="link"></i></button>
                </td>
                <td class="border px-4 py-2 text-red-600">${item.error || ''}</td>
            `;

            row.dataset.descripcion = item.descripcion || '';
            row.dataset.caracteristicas = (item.caracteristicas || []).join("\n");
            row.dataset.imagen = item.imagen || '';
            row.dataset.ficha_tecnica = item.ficha_tecnica || '';

            tbody.appendChild(row);
        });

        feather.replace();

        // Escuchar checkboxes
        document.querySelectorAll('.marcar-fila').forEach(chk => {
            chk.addEventListener('change', function() {
                if (this.checked) {
                    this.closest('tr').classList.add('bg-green-100');
                } else {
                    this.closest('tr').classList.remove('bg-green-100');
                }
            });
        });
    }
});

function abrirModal(campo, index) {
    let fila = document.querySelectorAll('#tablaResultados tbody tr')[index];
    campoEditando = campo;
    filaEditando = fila;
    document.getElementById('modalTitulo').innerText = "Editar " + campo.replace('_', ' ');
    document.getElementById('modalTexto').value = fila.dataset[campo];
    document.getElementById('modalEditor').classList.remove('hidden');
    document.getElementById('modalEditor').classList.add('flex');
}

document.getElementById('cerrarModal').addEventListener('click', () => {
    document.getElementById('modalEditor').classList.add('hidden');
    document.getElementById('modalEditor').classList.remove('flex');
});

document.getElementById('guardarModal').addEventListener('click', () => {
    if (filaEditando && campoEditando) {
        filaEditando.dataset[campoEditando] = document.getElementById('modalTexto').value;

        // Si es ficha técnica, actualizar icono con enlace
        if (campoEditando === 'ficha_tecnica') {
            let btn = filaEditando.querySelector(`button[onclick*="ficha_tecnica"]`);
            if (filaEditando.dataset.ficha_tecnica.trim()) {
                btn.outerHTML = `<a href="${filaEditando.dataset.ficha_tecnica}" target="_blank"><i data-feather="link"></i></a>`;
            }
        }
    }
    document.getElementById('modalEditor').classList.add('hidden');
    document.getElementById('modalEditor').classList.remove('flex');
    feather.replace();
});

document.getElementById('buscar-marca').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const select = document.getElementById('marca');
    const opciones = select.options;

    for (let i = 0; i < opciones.length; i++) {
      const texto = opciones[i].text.toLowerCase();
      // Mostrar opción si coincide o es la opción vacía
      opciones[i].style.display = (texto.includes(filtro) || opciones[i].value === "") ? '' : 'none';
    }
});

</script>

</body>
</html>
