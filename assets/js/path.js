export const route = `${
  window.parent.location.origin
}${window.parent.location.pathname.replace(
  /\/index\.php$/,
  ""
)}/procesa_datos.inc.php?token=libros`;

export const routeGet = (clave) => {
  // Obtener la URL actual del navegador
  const url = new URL(window.location.href);

  // Obtener los parámetros de la URL
  const params = new URLSearchParams(url.search);

  // Obtener el valor de un parámetro específico
  return params.get(clave) || undefined;
};
