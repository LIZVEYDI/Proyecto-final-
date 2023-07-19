<?php

include'conexionExa.php';


// API para registrar un producto y actualizar el inventario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $Nombre = $_POST['Nombre'];
    $Marca_Pro = $_POST['Marca_Pro'];
    $Presentacion = $_POST['Presentacion'];
    $Precio = $_POST['Precio'];

    // Insertar el producto en la tabla registro_productos
    $sql = "INSERT INTO registro_productos (Nombre, Marca_Pro, Presentacion, Precio)
            VALUES ('$Nombre', '$Marca_Pro', '$Presentacion', $Precio)";
    if ($conn->query($sql) === TRUE) {
        $Id_Prod = $conn->insert_id;

        // Insertar el producto en la tabla productos_inventario con cantidad 0
        $sql = "INSERT INTO productos_inventario (id_inventario, cantidad)
                VALUES ($Id_Prod, 0)";
        if ($conn->query($sql) === TRUE) {
            // Éxito al registrar el producto y actualizar el inventario
            echo "Producto registrado y inventario actualizado.";
        } else {
            // Error al actualizar el inventario
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Error al registrar el producto
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// API para actualizar el inventario
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Obtener los datos del formulario
    $id_inventario = $_GET['id_inventario'];
    $cantidad = $_GET['cantidad'];

    // Verificar si el producto existe en la tabla productos_inventario
    $sql = "SELECT * FROM Productos_inventario WHERE id_inventario = $id_inventario";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // El producto existe, actualizar el inventario
        $sql = "UPDATE productos_inventario SET cantidad = cantidad + $Cantidad WHERE id_inventario = $id_inventario";
        if ($conn->query($sql) === TRUE) {
            // Éxito al actualizar el inventario
            echo "Inventario actualizado.";
        } else {
            // Error al actualizar el inventario
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // El producto no existe en el inventario
        echo "El producto no está registrado en el inventario.";
    }
}

// API para realizar una venta de productos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener los datos del formulario
    $id_inventario = $_GET['id_inventario'];
    $cantidad = $_GET['cantidad'];

    // Verificar si el producto existe en el inventario y obtener su información
    $sql = "SELECT * FROM productos_inventario INNER JOIN registro_productos ON productos_inventario. id_inventario = registro_productos.id_inventario WHERE productos_inventario.id-inventario = $id_inventario";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $Nombre = $row['Nombre'];
        $Presentacion = $row['Presentacion'];
		$Precio = $row['Precio'];

        // Verificar si hay suficiente cantidad en el inventario
        if ($row['cantidad'] >= $cantidad) {
            // Calcular el total de pago
            $total_pago = $Precio * $cantidad;

            // Actualizar el inventario restando la cantidad vendida
            $sql = "UPDATE productos_inventario SET cantidad = cantidad - $cantidad WHERE Id_Prod = $id_inventario";
            if ($conn->query($sql) === TRUE) {
                // Éxito al actualizar el inventario
                // Aquí puedes almacenar la información de la venta en la tabla Ventas_Productos si es necesario
                echo "Venta realizada. Total a pagar: $total_pago.";
            } else {
                // Error al actualizar el inventario
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // No hay suficiente cantidad en el inventario
            echo "No hay suficiente cantidad en el inventario. Cantidad disponible: " . $row['cantidad'];
        }
    } else {
        // El producto no existe en el inventario
        echo "El producto no está registrado en el inventario.";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>