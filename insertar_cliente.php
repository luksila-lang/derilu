<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];
        
        $bd = new PDO('mysql:host=localhost;port=3308;dbname=cotizacion_barras', 'root', '', $opciones);

        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $cumpleanos = !empty($_POST['cumpleanos']) ? $_POST['cumpleanos'] : null;
        
        // 1. Capturamos el nuevo campo de comentarios de forma segura
        $comentarios = $_POST['comentarios'] ?? '';

        // 2. Modificamos la consulta SQL para incluir la nueva columna
        $sql = "INSERT INTO cliente (nombre, apellido, email, telefono, cumpleaños_cliente, comentarios) 
                VALUES (:nombre, :apellido, :email, :telefono, :cumpleanos, :comentarios)";
        
        $stmt = $bd->prepare($sql);

        // 3. Vinculamos el nuevo parámetro en la ejecución
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':telefono' => $telefono,
            ':cumpleanos' => $cumpleanos,
            ':comentarios' => $comentarios
        ]);

        echo "<script>
                alert('¡Gracias por tus comentarios, nos pondremos en contacto!');
                window.location.href = 'contacto.html';
              </script>";
        exit;

    } catch (PDOException $e) {
        $error_mensaje = addslashes($e->getMessage());
        echo "<script>
                alert('Error al registrar el cliente: " . $error_mensaje . "');
                window.history.back();
              </script>";
        exit;
    }
} else {
    header("Location: contacto.html");
    exit;
}
?>
