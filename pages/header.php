<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
	header("Location: ../index.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../logo/icone.svg">
	<title>Fn-Cash</title>

	<!-- CDN icons bootstrap -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- CSS CUSTOM - AGORA DEPOIS DO BOOTSTRAP -->
    <link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="sidebar/sidebar.css">

	<!-- GOOGLE FONTS MONTSERRAT -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

	<!-- Chart.js para os gráficos -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<!-- JS PARA MODAIS -->
	<script src="../JavaScript/scirpt.js"></script> 
	
</head>
<body class="dark-theme">
