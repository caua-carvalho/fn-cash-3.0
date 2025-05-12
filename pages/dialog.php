<?php
function confirmar($mensagem, $pagina, $titulo = "Confirmação") {
    echo '
    <div id="customModal" class="modal fade">
        <div class="modal-backdrop"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content scale-on-load">
                <div class="modal-header">
                    <h5 class="modal-title">' . $titulo . '</h5>
                    <button type="button" class="close" onclick="fecharModal(\'customModal\')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>' . $mensagem . '</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="window.location.href=\'' . $pagina . '\';">
                        Confirmar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="fecharModal(\'customModal\')">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("customModal").classList.add("show");
            document.body.classList.add("modal-open");
        });
        
        function fecharModal(id) {
            document.getElementById(id).classList.remove("show");
            document.body.classList.remove("modal-open");
            setTimeout(function() {
                document.getElementById(id).style.display = "none";
            }, 300);
        }
    </script>
    ';
}

function erro($mensagem, $titulo = "Erro") {
    echo '
    <div id="errorModal" class="modal fade">
        <div class="modal-backdrop"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content scale-on-load">
                <div class="modal-header">
                    <h5 class="modal-title">' . $titulo . '</h5>
                    <button type="button" class="close" onclick="fecharModal(\'errorModal\')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p>' . $mensagem . '</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="history.go(-1);">
                        Voltar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("errorModal").classList.add("show");
            document.body.classList.add("modal-open");
        });
        
        function fecharModal(id) {
            document.getElementById(id).classList.remove("show");
            document.body.classList.remove("modal-open");
            setTimeout(function() {
                document.getElementById(id).style.display = "none";
            }, 300);
        }
    </script>
    ';
}

// Função adicional para confirmação de exclusão com mais ênfase
function confirmarExclusao($mensagem, $pagina, $itemNome = "", $titulo = "Confirmar Exclusão") {
    echo '
    <div id="deleteModal" class="modal fade">
        <div class="modal-backdrop"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content scale-on-load">
                <div class="modal-header">
                    <h5 class="modal-title">' . $titulo . '</h5>
                    <button type="button" class="close" onclick="fecharModal(\'deleteModal\')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>' . $mensagem . '</p>
                    ' . ($itemNome ? '<p><strong id="itemExcluir">' . $itemNome . '</strong></p>' : '') . '
                    <p class="small text-danger">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="fecharModal(\'deleteModal\')">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href=\'' . $pagina . '\';">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("deleteModal").classList.add("show");
            document.body.classList.add("modal-open");
        });
        
        function fecharModal(id) {
            document.getElementById(id).classList.remove("show");
            document.body.classList.remove("modal-open");
            setTimeout(function() {
                document.getElementById(id).style.display = "none";
            }, 300);
        }
    </script>
    ';
}

function alerta($mensagem, $tipo = "success", $duracao = 3000) {
    $icones = [
        "success" => "fas fa-check-circle",
        "danger" => "fas fa-exclamation-circle",
        "warning" => "fas fa-exclamation-triangle",
        "info" => "fas fa-info-circle"
    ];
    
    $icone = isset($icones[$tipo]) ? $icones[$tipo] : $icones["info"];
    
    $id = "alerta_" . uniqid();
    
    echo '
    <div id="' . $id . '" class="fade-in position-fixed bottom-0 end-0 m-4" style="z-index: 9999; display: none;">
        <div class="card shadow-lg border-' . $tipo . ' mb-0" style="max-width: 400px;">
            <div class="card__body p-3 d-flex align-items-center gap-3">
                <i class="' . $icone . ' text-' . $tipo . ' fa-lg"></i>
                <p class="mb-0">' . $mensagem . '</p>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alerta = document.getElementById("' . $id . '");
            alerta.style.display = "block";
            
            setTimeout(function() {
                alerta.classList.add("fade-out");
                setTimeout(function() {
                    alerta.remove();
                }, 300);
            }, ' . $duracao . ');
        });
    </script>
    ';
}
?>