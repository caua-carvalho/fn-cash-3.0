// Get elements
const cardLogin = document.querySelector('.cardLogin');
const signInBtn = document.getElementById('signInBtn');
const signUpBtn = document.getElementById('signUpBtn');

// Função pra aplicar o estado salvo
function aplicarEstado() {
  const flipped = localStorage.getItem('cardFlipped') === 'true';
  if (flipped) {
    cardLogin.classList.add('flipped');
  } else {
    cardLogin.classList.remove('flipped');
  }
}

// Event listeners que também salvam no localStorage
signInBtn.addEventListener('click', () => {
  cardLogin.classList.add('flipped');
  localStorage.setItem('cardFlipped', 'true');
});

signUpBtn.addEventListener('click', () => {
  cardLogin.classList.remove('flipped');
  localStorage.setItem('cardFlipped', 'false');
});

// Na carga da página, aplicamos o estado
document.addEventListener('DOMContentLoaded', aplicarEstado);
