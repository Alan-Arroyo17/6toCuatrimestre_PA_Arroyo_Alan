<?php
    require_once 'conn.php';
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
        <section class="task-navbar">
            <input type="text" name="taskToDo" placeholder="Escriba su tarea aquí...">
            <button type="submit">Agregar Tarea</button>
        </section>
        <section class="taskList">
            <h3>Tareas</h3>
            <ul>
                <!-- Las tareas se mostrarán aquí -->
            </ul>
        </section>
    </div>
</body>
</html>