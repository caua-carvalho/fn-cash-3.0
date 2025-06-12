const $ = (el) => document.querySelector(el);

const logInBtn = $(".container__toggle-btn--login");
const registerBtn = $(".container__toggle-btn--register");
const btnHighlightEl = $(".container__btn-highlight");
const loginForm = $("#login");
const registerForm = $("#register");

logInBtn.onclick = () => {
  loginForm.style.transform = "translateX(0%)";
  registerForm.style.transform = "translateX(100%)";
  btnHighlightEl.style.left = "0";
};

registerBtn.onclick = () => {
  loginForm.style.transform = "translateX(100%)";
  registerForm.style.transform = "translateX(0%)";
  btnHighlightEl.style.left = "110px";
};

// Função para alternar entre mostrar/esconder a senha
        function togglePassword(inputId, el) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                el.textContent = "🔓";
            } else {
                input.type = "password";
                el.textContent = "🔒";
            }
        }
        
// Script para fechar o toast após 3 segundos
window.onload = function() {
    // Verificar se há uma mensagem de sucesso
    const successMessage = "<?php echo isset($_SESSION['success_message']) ? $_SESSION['success_message'] : ''; ?>";
    
    if (successMessage) {
        setTimeout(function() {
            const toast = document.querySelector(".toast-container");
            if (toast) {
                toast.style.display = "none";
            }
        }, 3000); // 3 segundos
    }
};