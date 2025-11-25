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
<?php foreach ($taches as $tache): ?>
    <?php 
    // Clase dinámica según si está hecha o no [cite: 80]
    $class = $tache->done ? 'list-group-item-success' : 'list-group-item-warning'; 
    ?>

    <div class="list-group-item <?php echo $class; ?> d-flex justify-content-between align-items-center">
        
        <span><?php echo htmlspecialchars($tache->title); ?></span>

        <form method="post" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $tache->id; ?>"> <button type="submit" name="action" value="toggle" class="btn btn-primary btn-sm"> <?php echo $tache->done ? 'Undo' : 'Done'; ?>
            </button>
            
            <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm"> X
            </button>
        </form>
    </div>
<?php endforeach; ?>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>TodoList</title>

    <!-- Bootstrap 5 CDN -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background-color: #f4f4f4;
        }

        .todo-card {
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR (front-end requirement) -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">TodoList</a>
        </div>
    </nav>

    <div class="container">
        <div class="card todo-card">
            <div class="card-header bg-dark text-white">
                TodoList
            </div>
            <div class="card-body">

                <!-- FORMULAIRE AJOUT TÂCHE -->
                <!-- name du champ texte = "title" (obligation sujet) -->
                <form method="post" class="d-flex gap-2 mb-4">
                    <input type="hidden" name="action" value="new">
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Task Title"
                        required>
                    <button type="submit" class="btn btn-primary">
                        Add
                    </button>
                </form>

                <!-- LISTE DES TÂCHES (utilise la variable Staches) -->
                <ul class="list-group">
                    <?php foreach ($Staches as $task): ?>
                        <?php
                        $isDone = (int)$task['done'] === 1;
                        $itemClass = $isDone
                            ? 'list-group-item list-group-item-success'
                            : 'list-group-item list-group-item-warning';
                        ?>
                        <li class="<?php echo $itemClass; ?> d-flex justify-content-between align-items-center">

                            <!-- Titre tâche -->
                            <span><?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?></span>

                            <!-- FORMULAIRE PAR TÂCHE (toggle / delete) -->
                            <form method="post" class="d-flex gap-2 mb-0">
                                <!-- input hidden avec id de la tâche -->
                                <input type="hidden" name="id" value="<?php echo (int)$task['id']; ?>">

                                <!-- Bouton toggle -->
                                <button
                                    type="submit"
                                    name="action"
                                    value="toggle"
                                    class="btn btn-sm btn-success">
                                    <?php echo $isDone ? 'Undo' : 'Done'; ?>
                                </button>

                                <!-- Bouton delete -->
                                <button
                                    type="submit"
                                    name="action"
                                    value="delete"
                                    class="btn btn-sm btn-danger">
                                    X
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
