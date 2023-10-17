const text = "Bienvenido a la biblioteca de Dario Quinde 2DAW";
const header = document.getElementById("typing-header");
let index = 0;

function typeText() {
    if (index < text.length) {
        header.innerHTML += text.charAt(index);
        index++;
        setTimeout(typeText, 100);
    } 
}
typeText();