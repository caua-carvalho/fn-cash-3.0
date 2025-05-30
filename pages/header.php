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
	<script src="../JavaScript/script.js"></script> 
	
</head>

<style>
  #theme-toggle {
    position: fixed;
    top: 10px;
    left: 10px; /* ajuste se sua sidebar estiver em outro lugar */
    width: 60px;
    height: 30px;
    background: #ccc;
    border-radius: 15px;
    display: flex;
    align-items: center;
    padding: 2px;
    cursor: pointer;
    z-index: 9999;
  }

  .toggle-thumb {
    width: 26px;
    height: 26px;
    background: #000;
    border-radius: 50%;
    transition: transform 0.3s ease;
    position: absolute;
    left: 2px;
  }

  body.light-theme #theme-toggle .toggle-thumb {
  transform: translateX(30px);
    background: #fff;
  }

  .icons {
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 5px;
    font-size: 14px;
    color: #000;
    z-index: 10;
  }

  body.light-theme .icons {
  color: #000;
  }
</style>
<script>
  window.addEventListener('load', function () {
    const toggle = document.getElementById('theme-toggle');
    const body = document.querySelector('body');

    // Aplica tema salvo
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'light-theme') {
	body.classList.remove('dark-theme');
		body.classList.add('light-theme');
    } else {
		body.classList.remove('light-theme');
		body.classList.add('dark-theme');
    }

    // Alterna ao clicar
    toggle.addEventListener('click', function () {
      if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        body.classList.add('light-theme');
        localStorage.setItem('theme', 'light-theme');
      } else {
        body.classList.remove('light-theme');
        body.classList.add('dark-theme');
        localStorage.setItem('theme', 'dark-theme');
      }
    });
  });
</script>
