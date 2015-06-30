<?php
	session_start();
	
	if($_SESSION['test_acc'])
		$_SESSION['test_acc'] = false;
	else
		$_SESSION['test_acc'] = true;