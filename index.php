<?php
// ----- EXAMPLE DATA (front-end test only) -----
// When you connect the real back-end, remove this and
// fill $Staches from the database.
$Staches = [
    ['id' => 1, 'title' => 'Arroser les plantes', 'done' => 0],
    ['id' => 2, 'title' => 'Terminer l\'activité 7 du module Approche agile', 'done' => 0],
    ['id' => 3, 'title' => 'Appeler le technicien qui répare mon ancien tél', 'done' => 1],
    ['id' => 4, 'title' => 'Acheter le riz et du lait', 'done' => 0],
];
?>
<!doctype html>
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