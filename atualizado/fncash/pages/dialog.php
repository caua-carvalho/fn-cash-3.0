<?php
function confirmar($mensagem, $pagina) {
    echo "
    <div id='customModal' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;'>
        <div style='background: white; padding: 20px; border-radius: 8px; text-align: center; width: 300px;'>
            <p style='margin-bottom: 20px;'>$mensagem</p>
            <button onclick=\"window.location.href='$pagina';\" style='padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer;'>OK</button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('customModal').style.display = 'flex';
        });
    </script>
    ";
}

function erro($mensagem) {
    echo "
    <div id='errorModal' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;'>
        <div style='background: white; padding: 20px; border-radius: 8px; text-align: center; width: 300px;'>
            <p style='margin-bottom: 20px;'>$mensagem</p>
            <button onclick=\"history.go(-1);\" style='padding: 10px 20px; background-color: #DC3545; color: white; border: none; border-radius: 4px; cursor: pointer;'>Voltar</button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('errorModal').style.display = 'flex';
        });
    </script>
    ";
}

?>