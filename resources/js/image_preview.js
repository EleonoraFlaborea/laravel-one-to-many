
const input = document.getElementById('image');
const placeholder = 'https://marcolanci.it/boolean/assets/placeholder.png';
const preview = document.getElementById('preview');

input.addEventListener('input', () => {
    preview.src = input.value ? input.value : placeholder;
})
