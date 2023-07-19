<?php

include 'conexion_Examen.php';

$pdo=new Conexion();

if($_SERVER['REQUEST_METHOD']=='POST')
{
	$sql="INSERT INTO registro_producto (id_registro, Nombre, Marca_Pro, Presentacion, Precio) VALUES (:id1, :nom, :mar, :des, :cos)";
	$stmt=$pdo->prepare($sql);	
	$stmt->bindValue(':id1',$_POST['id_registro']);
	$stmt->bindValue(':nom',$_POST['Nombre']);
	$stmt->bindValue(':mar',$_POST['Marca_Pro']);
	$stmt->bindValue(':des',$_POST['Presentacion']);
	$stmt->bindValue(':cos',$_POST['Precio']);
	$stmt->execute();	
	$idPost=$pdo->lastInsertId();
	$sql="INSERT INTO productos_inventario (id_inventario,cantidad) VALUES (:id1,0)";
	$stmt=$pdo->prepare($sql);	
	$stmt->bindValue(':id1',$_POST['id_registro']);
	$stmt->execute();
	$idPost=$pdo->lastInsertId();
	
	if($idPost)
	{
		header ("http/1.1 200 OK");
		echo json_encode($id_inventarioPost);
		exit;
	}
	header("HTTP/1.1 400 Bad REQUEST_METHOD");
}
?>