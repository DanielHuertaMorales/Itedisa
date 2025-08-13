<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Validaciones iniciales
if (!isset($_POST['categoria']) || !isset($_POST['marca']) || empty($_FILES['pdfs']['name'][0])) {
    die("Debes seleccionar una marca y una categoría, y subir al menos un PDF.");
}

$categoriaId = intval($_POST['categoria']);
$marcaId = intval($_POST['marca']);

if ($categoriaId <= 0 || $marcaId <= 0) {
    die("Debes seleccionar una marca y una categoría.");
}

$resultados = [];
$errores = [];

foreach ($_FILES['pdfs']['tmp_name'] as $index => $tmpName) {
    $fileName = $_FILES['pdfs']['name'][$index];
    $fileType = $_FILES['pdfs']['type'][$index];

    if ($fileType !== 'application/pdf') {
        $errores[] = "El archivo $fileName no es un PDF válido.";
        continue;
    }

    $rutaDestino = __DIR__ . "/uploads/" . basename($fileName);
    if (!move_uploaded_file($tmpName, $rutaDestino)) {
        $errores[] = "Error al subir el archivo $fileName.";
        continue;
    }

    // Enviar al microservicio
    $curl = curl_init();
    $postData = [
        'marca_id' => $marcaId,
        'categoria_id' => $categoriaId,
        'pdfs[]' => new CURLFile($rutaDestino)
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => 'http://localhost:5000/procesar',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_TIMEOUT => 60
    ]);

    $respuesta = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($respuesta === false || $httpCode !== 200) {
        $errores[] = "Error al procesar $fileName: respuesta no válida del microservicio.";
        continue;
    }

    $data = json_decode($respuesta, true);
    if (isset($data['resultado'])) {
        foreach ($data['resultado'] as &$res) {
            // Limpiar el nombre del archivo para que solo sea el nombre base
            if (isset($res['archivo'])) {
                $res['archivo'] = basename($res['archivo']);
            }
        }
        unset($res); // evitar referencias accidentales
        $resultados = array_merge($resultados, $data['resultado']);
    } else {
        $errores[] = "Respuesta inesperada del microservicio para $fileName.";
    }

    unlink($rutaDestino);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos extraídos</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@tabler/icons/icons/iconfont/tabler-icons.min.js"></script>
</head>
<body class="bg-gray-100 pt-36 p-8">

  <?php if (!empty($errores)): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
      <?php foreach ($errores as $error) echo "<p>$error</p>"; ?>
    </div>
  <?php endif; ?>

  <div class="overflow-x-auto">
    <table id="tabla-resultados" class="min-w-full bg-white rounded shadow-md">
      <thead class="bg-gray-800 text-white">
        <tr>
          <th class="py-3 px-4 text-left">Archivo</th>
          <th class="py-3 px-4 text-left">Marca ID</th>
          <th class="py-3 px-4 text-left">Categoría ID</th>
          <th class="py-3 px-4 text-left">Contenido</th>
        </tr>
      </thead>
      <tbody class="text-gray-700"></tbody>
    </table>
  </div>

  <!-- Modal -->
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Editar Contenido</h2>
        <button onclick="cerrarModal()" class="text-gray-600 hover:text-red-500 text-2xl font-bold">&times;</button>
      </div>
      <textarea id="contenido-modal" rows="10" class="w-full p-2 border rounded resize-y text-sm font-mono"></textarea>
      <div class="flex justify-end mt-4">
        <button onclick="guardarContenidoTemporal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar cambios</button>
      </div>
    </div>
  </div>

  <script>
    let filaActual = null;
    let datosPDF = <?php echo json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;

    function mostrarResultados(resultados) {
        const tbody = document.querySelector("#tabla-resultados tbody");
        tbody.innerHTML = "";

        resultados.forEach((item, index) => {
            // Asegurar que solo mostramos el nombre del archivo
            const nombreArchivo = item.archivo ? item.archivo.split(/[\\/]/).pop() : '';

            const tr = document.createElement("tr");
            tr.setAttribute("data-index", index);

            tr.innerHTML = `
            <td class="border px-4 py-2">${nombreArchivo}</td>
            <td class="border px-4 py-2">${item.marca_id || ''}</td>
            <td class="border px-4 py-2">${item.categoria_id || ''}</td>
            <td class="border px-4 py-2 text-center">
                <button onclick="abrirModal(${index})" class="text-blue-600 hover:text-blue-800" title="Ver contenido">
                <i class="ti ti-eye text-lg"></i>
                </button>
            </td>
            `;

            tbody.appendChild(tr);
        });
    }


    function abrirModal(index) {
      filaActual = index;
      const contenido = datosPDF[index].contenido || '';
      document.getElementById("contenido-modal").value = contenido;
      document.getElementById("modal").classList.remove("hidden");
      document.getElementById("modal").classList.add("flex");
    }

    function cerrarModal() {
      document.getElementById("modal").classList.add("hidden");
    }

    function guardarContenidoTemporal() {
      const nuevoContenido = document.getElementById("contenido-modal").value;
      datosPDF[filaActual].contenido = nuevoContenido;

      const fila = document.querySelector(`tr[data-index="${filaActual}"]`);
      fila.classList.add("bg-yellow-100");

      cerrarModal();
    }

    document.addEventListener("DOMContentLoaded", () => {
      mostrarResultados(datosPDF);
    });
  </script>

</body>
</html>
