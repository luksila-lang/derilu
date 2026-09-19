<?php
session_start();

if (!isset($_SESSION['admin_logeado']) || $_SESSION['admin_logeado'] !== true) {
    header("Location: login.php");
    exit;
}

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
];

try {
    $bd = new PDO("mysql:host=localhost;port=3308;dbname=cotizacion_barras", "root", "", $opciones);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$mensaje_accion = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        try {
            $stmt_check = $bd->prepare("SELECT email FROM cliente WHERE email = :email");
            $stmt_check->execute([':email' => $_POST['email']]);
            if ($stmt_check->rowCount() > 0) {
                $mensaje_accion = "<div class='alert error'>Error: El correo ya existe.</div>";
            } else {
                $sql_insert = "INSERT INTO cliente (nombre, apellido, email, telefono, cumpleaños_cliente, comentarios) VALUES (:nombre, :apellido, :email, :telefono, :cumpleanos, :comentarios)";
                $bd->prepare($sql_insert)->execute([
                    ':nombre' => $_POST['nombre'], ':apellido' => $_POST['apellido'], ':email' => $_POST['email'],
                    ':telefono' => $_POST['telefono'], ':cumpleanos' => $_POST['cumpleanos'], ':comentarios' => $_POST['comentarios']
                ]);
                $mensaje_accion = "<div class='alert success'>Cliente registrado con éxito.</div>";
            }
        } catch (PDOException $e) { $mensaje_accion = "<div class='alert error'>Error: " . $e->getMessage() . "</div>"; }
    }
    
    if ($_POST['accion'] === 'eliminar' && isset($_POST['email_id'])) {
        try {
            $bd->prepare("DELETE FROM cliente WHERE email = :email")->execute([':email' => $_POST['email_id']]);
            $mensaje_accion = "<div class='alert success'>Cliente eliminado correctamente.</div>";
        } catch (PDOException $e) { $mensaje_accion = "<div class='alert error'>Error: " . $e->getMessage() . "</div>"; }
    }
    
    if ($_POST['accion'] === 'editar' && isset($_POST['email_original'])) {
        try {
            $sql_update = "UPDATE cliente SET nombre = :nombre, apellido = :apellido, telefono = :telefono, cumpleaños_cliente = :cumpleanos, comentarios = :comentarios WHERE email = :email_original";
            $bd->prepare($sql_update)->execute([
                ':nombre' => $_POST['nombre'], ':apellido' => $_POST['apellido'], ':telefono' => $_POST['telefono'],
                ':cumpleanos' => $_POST['cumpleanos'], ':comentarios' => $_POST['comentarios'], ':email_original' => $_POST['email_original']
            ]);
            $mensaje_accion = "<div class='alert success'>Cliente actualizado correctamente.</div>";
        } catch (PDOException $e) { $mensaje_accion = "<div class='alert error'>Error: " . $e->getMessage() . "</div>"; }
    }
}

try {
    $clientes = $bd->query("SELECT * FROM cliente")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { die("Error al consultar: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Clientes - DERILU</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .header-panel { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 20px; }
        h1 { color: #6a1b1a; margin: 0; font-size: 24px; }
        .btn-add { background-color: #6a1b1a; color: white; border: none; padding: 10px 18px; border-radius: 4px; font-weight: bold; font-size: 14px; cursor: pointer; }
        .btn-logout { background-color: #721c24; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; }
        th { background-color: #6a1b1a; color: white; font-weight: bold; }
        .btn-action-edit { background-color: #007bff !important; color: white !important; padding: 6px 12px !important; border-radius: 4px !important; font-weight: bold !important; border: none !important; cursor: pointer !important; }
        .btn-action-delete { background-color: #dc3545 !important; color: white !important; padding: 6px 12px !important; border-radius: 4px !important; font-weight: bold !important; border: none !important; cursor: pointer !important; }
        .acciones-wrapper { display: flex !important; gap: 8px !important; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: bold; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .modal-container-box { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content-wrapper { background-color: white; padding: 25px; border-radius: 8px; width: 100%; max-width: 450px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .form-group-item { margin-bottom: 12px; }
        .form-group-item label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .form-group-item input, .form-group-item textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .modal-buttons { display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; }
        .btn-close-modal { background-color: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
        .btn-submit-modal { background-color: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
<div class="header-panel">
    <h1>Clientes Registrados (Cotizaciones)</h1>
    <div>
        <span style="margin-right: 15px; color: #555;">Bienvenido, <strong>admin</strong></span>
        <button class="btn-add" id="btnTriggerCrear">+ Agregar Nuevo Cliente</button>
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</div>

<?php echo $mensaje_accion; ?>

<!-- 1. AGREGAR EL INPUT DEL BUSCADOR JUSTO AQUÍ ARRIBA -->
<!-- 1. CAMPO DE BÚSQUEDA -->
<!-- Barra de búsqueda con botón integrado -->
<!-- Barra de búsqueda con protección contra envíos de formulario -->
<!-- Barra de búsqueda corregida y segura -->
<!-- Barra de búsqueda con botón de limpiar (X) integrado -->
<!-- Barra de búsqueda con botón de limpiar y botón de exportar agrupados -->
<div style="margin-bottom: 20px; display: flex; max-width: 550px; gap: 10px; align-items: center;">
    <div style="position: relative; flex-grow: 1; display: flex; align-items: center; max-width: 380px;">
        <input type="text" id="inputBuscador" placeholder="Buscar cliente..." 
               style="padding: 10px; padding-right: 30px; width: 100%; border: 1px solid #ccc; border-radius: 4px 0 0 4px; font-size: 16px; outline: none; box-sizing: border-box;">
        <span id="btnLimpiar" 
              style="position: absolute; right: 10px; cursor: pointer; color: #999; font-weight: bold; font-size: 16px; display: none; user-select: none;">
            &times;
        </span>
    </div>
    <button type="button" id="btnBuscador" onclick="event.preventDefault(); return false;"
            style="padding: 10px 20px; background-color: #6a1b1a; color: white; border: 1px solid #6a1b1a; border-radius: 0 4px 4px 0; font-size: 16px; cursor: pointer; font-weight: bold; margin-left: -11px;">
        Buscar
    </button>

    <!-- REEMPLAZA TU BOTÓN VERDE ACTUAL POR ESTE -->
<button type="button" id="btnExportarExcel" style="padding: 10px 15px; background-color: #1f7244; color: white; border: 1px solid #1f7244; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 5px;">
    📊 Exportar Excel
</button>


</div>

<!-- 2. CONTENEDOR Y TABLA CON PHP CORREGIDO -->
<div class="table-container">
    <!-- REVISA QUE ESTA LÍNEA DE APERTURA ESTÉ AQUÍ: -->
    <?php if (count($clientes) > 0): ?>
<table id="tablaClientes">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Cumpleaños</th>
            <th>Comentarios</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $cliente): ?>
            <?php $com_limpio = preg_replace('/\s+/', ' ', $cliente['comentarios'] ?? ''); ?>
            <tr>
                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                <td><?php echo htmlspecialchars($cliente['telefono'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($cliente['cumpleaños_cliente'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($cliente['comentarios'] ?: 'Sin comentarios'); ?></td>
                <td>
                    <div class="acciones-wrapper">
                        <button type="button" class="btn-action-edit btn-editar-trigger" 
                                data-email="<?php echo htmlspecialchars($cliente['email']); ?>"
                                data-nombre="<?php echo htmlspecialchars($cliente['nombre']); ?>"
                                data-apellido="<?php echo htmlspecialchars($cliente['apellido']); ?>"
                                data-telefono="<?php echo htmlspecialchars($cliente['telefono']); ?>"
                                data-cumpleanos="<?php echo htmlspecialchars($cliente['cumpleaños_cliente']); ?>"
                                data-comentarios="<?php echo htmlspecialchars($com_limpio); ?>">Editar</button>
                        <form action="panel_admin.php" method="POST" onsubmit="return confirm('¿Eliminar cliente?');" style="display:inline; margin:0;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="email_id" value="<?php echo htmlspecialchars($cliente['email']); ?>">
                            <button type="submit" class="btn-action-delete">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?> <!-- Asegúrate de tener solo un cierre de ciclo aquí -->
    </tbody>
</table>

    <?php else: ?>
        <p style="padding: 15px; text-align: center;">No hay clientes registrados en el sistema.</p>
    <?php endif; ?>
</div>

<!-- 1. CORREGIDO: Enlace completo a la librería de Excel -->
<script src="https://jsdelivr.net"></script>

<!-- 2. CORREGIDO: Lógica del Buscador, Marcado Amarillo, X y Excel unificados -->
<script>
// Función exacta que genera el marcado amarillo en tiempo real
function ejecutarBusqueda() {
    let input = document.getElementById('inputBuscador');
    let btnLimpiar = document.getElementById('btnLimpiar');
    let filtro = input.value.toLowerCase().trim();
    let filas = document.querySelectorAll('#tablaClientes tbody tr');

    // Controlar si se muestra o se oculta la "X"
    if (input.value.length > 0) {
        btnLimpiar.style.display = 'block';
    } else {
        btnLimpiar.style.display = 'none';
    }

    filas.forEach(fila => {
        let coincidenciaEnFila = false;
        let celdas = fila.querySelectorAll('td:not(:last-child)');

        celdas.forEach(celda => {
            if (!celda.hasAttribute('data-original')) {
                celda.setAttribute('data-original', celda.innerHTML);
            }
            
            let textoOriginal = celda.getAttribute('data-original');
            let textoMinusc = textoOriginal.toLowerCase();

            if (filtro !== '' && textoMinusc.includes(filtro)) {
                coincidenciaEnFila = true;
                let expresion = new RegExp(`(${filtro})`, 'gi');
                celda.innerHTML = textoOriginal.replace(expresion, '<mark style="background-color: #ffeb3b; padding: 2px; border-radius: 2px;">$1</mark>');
            } else {
                celda.innerHTML = textoOriginal;
            }
        });

        if (filtro === '' || coincidenciaEnFila) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// 1. Activa la búsqueda y muestra la X en tiempo real al escribir
document.getElementById('inputBuscador').addEventListener('input', ejecutarBusqueda);

// 2. Activa la búsqueda al presionar el botón "Buscar"
document.getElementById('btnBuscador').addEventListener('click', function(e) {
    e.preventDefault();
    ejecutarBusqueda();
});

// 3. Bloquea el envío accidental al presionar Enter
document.getElementById('inputBuscador').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        ejecutarBusqueda();
        return false;
    }
});

// 4. LÓGICA DEL BOTÓN DE LIMPIAR (X)
document.getElementById('btnLimpiar').addEventListener('click', function() {
    let input = document.getElementById('inputBuscador');
    input.value = ''; // Borra el texto de la barra
    ejecutarBusqueda(); // Re-ejecuta la función para restaurar la tabla por completo
    input.focus(); // Regresa el cursor a la barra por si quieren volver a escribir
});

// 5. FUNCIÓN PARA EXPORTAR A EXCEL (Versión Nativa y Segura)
// Reemplaza por completo el punto 5 de tu script por este bloque:
// 5. FUNCIÓN PARA EXPORTAR A EXCEL (Versión Nativa Corregida)
// 5. FUNCIÓN PARA EXPORTAR A EXCEL (Versión Autónoma Garantizada)
// 5. FUNCIÓN PARA EXPORTAR A EXCEL (Compatibilidad Absoluta con Excel)
document.getElementById('btnExportarExcel').addEventListener('click', function(e) {
    e.preventDefault();

    // NOTA VITAL: Añadimos 'sep=;' al inicio para obligar a Excel a separar las columnas de inmediato
    // CORREGIDO: Títulos limpios sin caracteres especiales para evitar errores en Excel
    let contenidoCSV = "sep=;\r\nNombre;Apellido;Correo;Telefono;Cumpleanos;Comentarios\r\n";

    // Obtener todas las filas de la tabla
    let filas = document.querySelectorAll('#tablaClientes tbody tr');

    filas.forEach(fila => {
        // Exportar únicamente las filas visibles que no ocultó el buscador
        if (fila.style.display !== 'none') {
            let celdas = fila.querySelectorAll('td');
            
            // Verificamos que la fila tenga las columnas suficientes
            if (celdas.length >= 6) {
                // Extraemos el texto de cada columna por separado usando su posición exacta
                let nombre      = celdas[0].textContent.replace(/;/g, " ").trim();
                let apellido    = celdas[1].textContent.replace(/;/g, " ").trim();
                let correo      = celdas[2].textContent.replace(/;/g, " ").trim();
                let telefono    = celdas[3].textContent.replace(/;/g, " ").trim();
                let cumpleanos  = celdas[4].textContent.replace(/;/g, " ").trim();
                let comentarios = celdas[5].textContent.replace(/;/g, " ").trim();

                // Unimos los datos usando el punto y coma como separador real de celdas
                contenidoCSV += `${nombre};${apellido};${correo};${telefono};${cumpleanos};${comentarios}\r\n`;
            }
        }
    });

    // Crear el archivo binario con formato UTF-8 compatible con Microsoft Excel
    let blob = new Blob(["\ufeff" + contenidoCSV], { type: 'text/csv;charset=utf-8;' });
    
    // Forzar la descarga directa del archivo en el sistema operativo
    let link = document.createElement("a");
    let url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", "Reporte_Clientes_Filtrados.csv");
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});


</script>



<div id="modalCrear" class="modal-container-box">
    <div class="modal-content-wrapper">
        <h3 style="color:#6a1b1a;">Registrar Nuevo Cliente</h3>
        <form action="panel_admin.php" method="POST">
            <input type="hidden" name="accion" value="crear">
            <div class="form-group-item"><label>Correo (Único):</label><input type="email" name="email" required></div>
            <div class="form-group-item"><label>Nombre:</label><input type="text" name="nombre" required></div>
            <div class="form-group-item"><label>Apellido:</label><input type="text" name="apellido" required></div>
            <div class="form-group-item"><label>Teléfono:</label><input type="text" name="telefono"></div>
            <div class="form-group-item"><label>Cumpleaños:</label><input type="date" name="cumpleanos"></div>
            <div class="form-group-item"><label>Notas:</label><textarea name="comentarios" rows="2"></textarea></div>
            <div class="modal-buttons">
                <button type="button" class="btn-close-modal" id="btnCerrarCrear">Cancelar</button>
                <button type="submit" class="btn-submit-modal" style="background-color:#6a1b1a;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditar" class="modal-container-box">
    <div class="modal-content-wrapper">
        <h3 style="color:#6a1b1a;">Modificar Cliente</h3>
        <form action="panel_admin.php" method="POST">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" id="email_original" name="email_original">
            <div class="form-group-item"><label>Nombre:</label><input type="text" id="edit_nombre" name="nombre" required></div>
            <div class="form-group-item"><label>Apellido:</label><input type="text" id="edit_apellido" name="apellido" required></div>
            <div class="form-group-item"><label>Teléfono:</label><input type="text" id="edit_telefono" name="telefono"></div>
            <div class="form-group-item"><label>Cumpleaños:</label><input type="date" id="edit_cumpleanos" name="cumpleanos"></div>
            <div class="form-group-item"><label>Notas:</label><textarea id="edit_comentarios" name="comentarios" rows="2"></textarea></div>
            <div class="modal-buttons">
                <button type="button" class="btn-close-modal" id="btnCerrarEditar">Cancelar</button>
                <button type="submit" class="btn-submit-modal" style="background-color:#007bff;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
// --- Lógica de Modales ---
document.getElementById('btnTriggerCrear').addEventListener('click', function() {
    document.getElementById('modalCrear').style.display = 'flex';
});
document.getElementById('btnCerrarCrear').addEventListener('click', function() {
    document.getElementById('modalCrear').style.display = 'none';
});
document.querySelectorAll('.btn-editar-trigger').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('email_original').value = this.getAttribute('data-email');
        document.getElementById('edit_nombre').value = this.getAttribute('data-nombre');
        document.getElementById('edit_apellido').value = this.getAttribute('data-apellido');
        document.getElementById('edit_telefono').value = this.getAttribute('data-telefono');
        document.getElementById('edit_cumpleanos').value = this.getAttribute('data-cumpleanos');
        document.getElementById('edit_comentarios').value = this.getAttribute('data-comentarios');
        document.getElementById('modalEditar').style.display = 'flex';
    });
});
document.getElementById('btnCerrarEditar').addEventListener('click', function() {
    document.getElementById('modalEditar').style.display = 'none';
});
window.addEventListener('click', function(e) {
    if (e.target == document.getElementById('modalCrear')) { document.getElementById('modalCrear').style.display = "none"; }
    if (e.target == document.getElementById('modalEditar')) { document.getElementById('modalEditar').style.display = "none"; }
});

// --- Lógica del Buscador en Tiempo Real ---
document.getElementById('inputBuscador').addEventListener('input', function() {
    let filtro = this.value.toLowerCase().trim();
    let filas = document.querySelectorAll('#tablaClientes tbody tr');

    filas.forEach(fila => {
        let textoFila = fila.textContent.toLowerCase();
        
        if (textoFila.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
});
</script>

</body>
</html>
