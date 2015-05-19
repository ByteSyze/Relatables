<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$id = $_GET['id'];
	$redirect = $_GET['redirect'];
	
	if($_SESSION['id'] == null || $id == null)
		die();
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare('UPDATE notifications SET seen=1 WHERE id=(?) AND uid=(?)'))
	{
		$statement->bind_param('ii',$id, $_SESSION['id']);	
		$statement->execute();
	}
	
	if($redirect)
		header('Location: ' . $redirect);
?>