<?php
    require_once "config/connection.php";
    /*
        Recibir POST mesa, cliente y un arreglo platillos[] cada uno con nombre, precio y cantidad
        1. Insertar el pedido en la tabla pedidos (id, mesa, cliente, total)
        2. Obtener el id del pedido recién insertado
        3. Insertar cada platillo en la tabla detalle_pedido
        4. Calcular el total(sum(precio*cantidad)) y hacer update a pedidos.total
        5. Validar que mesa > 0, cliente no vacio, y al menos 1 platillo
    */
    if (isset($_POST['mesa']) && isset($_POST['cliente']) && !empty($_POST['mesa']) && !empty($_POST['cliente'])) {
        $stmt = $conn->prepare("INSERT INTO pedidos (mesa, cliente, total) VALUES (?, ?, 0)");
        $stmt->bind_param("is", $_POST['mesa'], $_POST['cliente']);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Restaurante</title>
</head>
<body>
    <h1>Toma de Pedido</h1>
    <form action="#" method="POST">
        <label for="mesa">Número de Mesa:</label>
        <input type="number" id="mesa" name="mesa" required><br><br>

        <label for="cliente">Nombre del Cliente:</label>
        <input type="text" id="cliente" name="cliente" required><br><br>

        <h3>Platillos</h3>
        <div id="platillos-container">
            <div class="platillo">
                <label for="nombre">Nombre:</label>
                <input type="text" name="platillos[0][nombre]" required>

                <label for="precio">Precio:</label>
                <input type="number" name="platillos[0][precio]" step="0.01" required>

                <label for="cantidad">Cantidad:</label>
                <input type="number" name="platillos[0][cantidad]" required><br><br>
            </div>
        </div>

        <button type="button" onclick="agregarPlatillo()">Agregar Otro Platillo</button><br><br>
        <input type="submit" value="Enviar Pedido">
    </form>
</body>
</html>