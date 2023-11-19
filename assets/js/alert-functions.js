// En tu módulo con las funciones de diálogo
export const showConfirmationDialog = async (
  title,
  text,
  confirmation = "Si"
) => {
  try {
    const result = await Swal.fire({
      title,
      text,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: confirmation,
      cancelButtonText: "Cancelar",
    });

    return result.isConfirmed;
  } catch (error) {
    console.log(error);
    return false; // Devolver false en caso de error
  }
};

export const showInformationMessage = (message) => {
  return Swal.fire({
      title: "¡Atención!",
      text: message,
      icon: "info",
  });
};


export const showSuccessMessage = (title, text = "") => {
  Swal.fire({
    title,
    text,
    icon: "success",
    showCancelButton: false,
    showConfirmButton: false,
    timer: 1500,
  });
};

export const showErrorMessage = (title, html) => {
  Swal.fire({
    title,
    html,
    icon: "error",
  });
};

export const showTime = (title, html = '' ,_timer = 3000) => {
  let timerInterval;
  Swal.fire({
    title,
    html,
    timer: _timer,
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading();
      const timer = Swal.getPopup().querySelector("b");
      timerInterval = setInterval(() => {
        timer.textContent = `${Swal.getTimerLeft()}`;
      }, 100);
    },
    willClose: () => {
      clearInterval(timerInterval);
    }
  })
}

export const  handleConfirmation = async (url, type, search, page, mess , mess2 = 'eliminarlo' , success= 'Eliminado' , succes2 = 'eliminado') => {
  const isConfirmed = await showConfirmationDialog(
    "¿Estás seguro?",
    `¿Deseas ${mess2} a este ${mess}?`,
    `Si, ${mess2}`
  );

  if (isConfirmed) {
    console.log("pinchado");
    showSuccessMessage(`¡${success}!`, `El ${mess} ha sido ${succes2}.`);

    if (type && search) {
      url += `&search=${search}&type=${type}`;
      if (page) {
        url += `&page=${page}`;
      }
    }
  } else {
    url = null;
  }

  return url;
};