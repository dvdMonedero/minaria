function cambiarEstilo(modo) {
  const link = document.getElementById("estilo-css");

  switch (modo) {
    //Ha elegido visualización simple
    case "simple":
      link.href = "css/style-simple.css";
      document.getElementById("btn-simple").style.display = "none";
      document
        .getElementById("btn-ultrasimple")
        .style.removeProperty("display");
      document.getElementById("btn-visual").style.removeProperty("display");
      break;
    //Ha elegido visualización ultrasimple
    case "ultrasimple":
      link.href = "css/style-ultrasimple.css";
      document.getElementById("btn-ultrasimple").style.display = "none";
      document.getElementById("btn-simple").style.removeProperty("display");
      document.getElementById("btn-visual").style.removeProperty("display");
      break;
    //Por defecto, visualización normal
    default:
      link.href = "css/style-visual.css";
      document.getElementById("btn-visual").style.display = "none";
      document
        .getElementById("btn-ultrasimple")
        .style.removeProperty("display");
      document.getElementById("btn-simple").style.removeProperty("display");
  }
  if (document.body) {
    document.body.className = modo;
  }
  setCookie("modo_estilo", modo, 365);
}

function setCookie(nombre, valor, dias) {
  const d = new Date();
  d.setTime(d.getTime() + dias * 24 * 60 * 60 * 1000);
  document.cookie = `${nombre}=${valor};expires=${d.toUTCString()};path=/`;
}

function getCookie(nombre) {
  const nombreEQ = nombre + "=";
  const ca = document.cookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i].trim();
    if (c.indexOf(nombreEQ) === 0)
      return c.substring(nombreEQ.length, c.length);
  }
  return "";
}
