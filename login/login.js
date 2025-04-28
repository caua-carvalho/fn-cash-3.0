// Get elements
const card = document.querySelector('.card');
const signInBtn = document.getElementById('signInBtn');
const signUpBtn = document.getElementById('signUpBtn');

// Add event listeners
signInBtn.addEventListener('click', () => {
  card.classList.add('flipped');
});

signUpBtn.addEventListener('click', () => {
  card.classList.remove('flipped');
});