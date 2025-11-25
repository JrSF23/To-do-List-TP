<?php
// --- TU CÓDIGO: CONFIGURACIÓN Y LÓGICA DE CONTROL ---
// 1. Conexión a la Base de Datos
define('DB_USER', 'root');
define('DB_PASS', 'Jr2sintia#23102003');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage()); 
}

// 2. Lógica de acciones (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;

    if ($action === 'new' && !empty($title)) {
        // Agrega nueva tarea [cite: 65]
        $stmt = $pdo->prepare("INSERT INTO todo (title) VALUES (:title)");
        $stmt->execute(['title' => $title]);
    }
    elseif ($action === 'delete' && $id) {
        // Elimina tarea [cite: 66]
        $stmt = $pdo->prepare("DELETE FROM todo WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    elseif ($action === 'toggle' && $id) {
        // Alterna el estado 'done' [cite: 67, 68]
        $stmt = $pdo->prepare("UPDATE todo SET done = 1 - done WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    
    header("Location: index.php");
    exit();
}

// 3. Recuperación de tareas (para la vista)
// Lee la lista y la almacena en $taches, ordenada por fecha descendente [cite: 60, 61, 62]
$stmt = $pdo->query("SELECT * FROM todo ORDER BY created_at DESC");
$taches = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
