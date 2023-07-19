<?php
include 'conexion_Examen.php';

$pdo = new Conexion();

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $sqlVerificar = "SELECT * FROM productos_inventario WHERE id_inventario=:id";
    $stmtVerificar = $pdo->prepare($sqlVerificar);
    $stmtVerificar->bindValue(':id',$_GET['id_inventario']);
    $stmtVerificar->execute();

    if ($stmtVerificar->fetchColumn() > 0) {
        
		
        $sql="UPDATE productos_inventario SET cantidad==0 :can + cantidad WHERE  id_inventario=:id";
	$stmt=$pdo->prepare($sql);	
	$stmt->bindValue(':id',$_GET['id_inventario]);

	$stmt->bindValue(':can',$_GET['cantidad']);
	$stmt->execute();
        
        header("HTTP/1.1 200 OK");
        echo json_encode("producto actualizado registrado");
        exit;
     } else {
        
        header("HTTP/1.1 404 Not Found");
        echo json_encode("El Producto no existe en el registro. Verifica su ID");
        exit;
       }
}

header("HTTP/1.1 400 Bad Request");

?>
