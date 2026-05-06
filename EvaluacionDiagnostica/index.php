<?php
    require_once 'conn.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $task = $_POST['taskToDo'] ?? '';
        if (!empty($task)) {
            $stmt = $conn->prepare("INSERT INTO tareas (descripcion) VALUES (:descripcion)");
            $stmt->bindParam(':descripcion', $task);
            $stmt->execute();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    if (isset($_POST['deleteTask'])) {
        $taskId = $_POST['deleteTask'];
        $stmt = $conn->prepare("DELETE FROM tareas WHERE idTarea = :idTarea");
        $stmt->bindParam(':idTarea', $taskId);
        $stmt->execute();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if(isset($_POST['completeTask'])) {
        $taskId = $_POST['completeTask'];
        $stmt = $conn->prepare("UPDATE tareas SET completada = 1 WHERE idTarea = :idTarea");
        $stmt->bindParam(':idTarea', $taskId);
        $stmt->execute();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <h1>TO-DO LIST</h1>
        <section class="task-input">
            <form method="POST" action="">
                <input type="text" name="taskToDo" placeholder="Escriba su tarea aquí...">
                <button type="submit">Agregar Tarea</button>
            </form>
        </section>
        <section class="taskList">
            <h3>Tareas</h3>
            <form method="POST" action="">
                <ul>
                    <?php
                        $stmt = $conn->query("SELECT * FROM tareas WHERE completada = 0");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $clase = $row['completada'] ? 'completada' : 'pendiente';
                            echo "<li class='$clase'>" . htmlspecialchars($row['descripcion']) . "</li>" .  
                            "<button type='submit' name='deleteTask' value='" . $row['idTarea'] . "'>Eliminar</button>" . 
                            "<button type='submit' name='completeTask' value='" . $row['idTarea'] . "'>Completar</button>";
                        }
                    ?>
                </ul>
                <p>Tareas por hacer: <?php echo $stmt->rowCount(); ?></p>
                <p>
                    Tareas completadas: 
                    <?php
                        $stmt = $conn->query("SELECT COUNT(*) FROM tareas WHERE completada = 1");
                        echo $stmt->fetchColumn();
                    ?>
                </p>
            </form>
        </section>
    </div>
</body>
</html>