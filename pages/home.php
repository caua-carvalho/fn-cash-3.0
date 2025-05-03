<?php
require_once 'header.php';

require_once 'sidebar.php';
?>
<style>
    .logo-home {
        opacity: 20%;
        transition: all 0.2s ease-in-out;
        width: 50%;
    }

    .container_logo_home {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>

<div class="container_logo_home">
    <img src="../logo/logo_escrita.svg" class="w-50 logo-home" id="logo-home">
</div>
<?php
require_once 'footer.php';
?>