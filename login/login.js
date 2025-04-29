// Get elements
const cardLogin = document.querySelector('.cardLogin');
const signInBtn = document.getElementById('signInBtn');
const signUpBtn = document.getElementById('signUpBtn');

// Add event listeners
signInBtn.addEventListener('click', () => {
  cardLogin.classList.add('flipped');
});

signUpBtn.addEventListener('click', () => {
  cardLogin.classList.remove('flipped');
});