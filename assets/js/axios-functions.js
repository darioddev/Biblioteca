// Función para realizar una solicitud GET
export const getData = async (url) => {
  try {
    const response = await axios.get(url);
    return response.data;
  } catch (error) {
    console.error("Error en fetchData:", error);
    throw error;
  }
};

// Función para realizar una solicitud POST
export const postData = async (url, data) => {
  try {
    const response = await axios.post(url, data);
    return response.data;
  } catch (error) {
    console.error("Error en postData:", error);
    throw error;
  }
};
