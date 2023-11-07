function primera_letra_mayus(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

if(window.location.search !== '') {
    const titl = window.location.search.split('=')
    document.title = `${primera_letra_mayus(titl[1])} - Mundo Libros`
}