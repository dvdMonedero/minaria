async function obtenerRecursos() {
  const respuesta = await fetch("obtener-recursos.php");
  const datos = await respuesta.json();

  if (datos.error) {
    console.error("Error:", datos.error);
    return;
  }

  console.log("Recursos actuales:", datos);

  // Actualizar la interfaz del jugador
  document.getElementById("recurso-piedra").textContent = reducirNumero(
    Math.floor(datos.piedra)
  );
  document.getElementById("recurso-metal").textContent = reducirNumero(
    Math.floor(datos.metal)
  );
  document.getElementById("recurso-madera").textContent = reducirNumero(
    Math.floor(datos.madera)
  );
  document.getElementById("recurso-comida").textContent = reducirNumero(
    Math.floor(datos.comida)
  );
  document.getElementById("recurso-oro").textContent = reducirNumero(
    Math.floor(datos.oro)
  );
  document.getElementById("recurso-mana").textContent = reducirNumero(
    Math.floor(datos.mana)
  );
}

// Llamar a la función al cargar la página
obtenerRecursos();

let recursosIniciales = {};
let produccionPorHora = {};
let fechaUltimaActualizacion = 0;
let recursosActuales = {};

async function cargarDatosIniciales() {
    try {
        const resp = await fetch('datos-iniciales.php');
        const data = await resp.json();
        recursosIniciales = data.recursosIniciales;
        produccionPorHora = data.produccionPorHora;
        fechaUltimaActualizacion = data.fechaUltimaActualizacion;
        recursosActuales = { ...recursosIniciales };
    } catch (e) {
        console.error('Error cargando datos iniciales:', e);
    }
}

cargarDatosIniciales().then(() => {
    if (document.readyState !== 'loading') {
        iniciarActualizacionEnVivo();
    } else {
        document.addEventListener('DOMContentLoaded', iniciarActualizacionEnVivo);
    }
});
function iniciarActualizacionEnVivo() {
  console.log('Iniciando actualización en vivo de recursos');
  const startTimestamp = Math.floor(Date.now() / 1000);
  const elementos = document.querySelectorAll('[id^="recurso-"][data-rate][data-amount]');
  console.log('Elementos encontrados para actualización en vivo:', elementos.length);
  elementos.forEach(el => {
    const rate = parseFloat(el.dataset.rate);
    const base = parseFloat(el.dataset.amount) || 0;
    if (isNaN(rate) || rate === 0) return;
    setInterval(() => {
      const now = Math.floor(Date.now() / 1000);
      const elapsed = now - startTimestamp;
      const nuevo = base + rate * elapsed;
      el.textContent = reducirNumero(Math.floor(nuevo));
    }, 1000);
  });
}

// Iniciar actualización en vivo tan pronto como sea posible
if (document.readyState !== 'loading') {
  iniciarActualizacionEnVivo();
} else {
  document.addEventListener('DOMContentLoaded', iniciarActualizacionEnVivo);
}

/*
//Esto es para cargar al inicio cuánto material hay, pero no funciona muy bien
function inicializarRecursos() {
    for (const [recurso, elemento] of Object.entries(recursosElementos)) {
        if (elemento) {
            // Usa el atributo `data-valor-inicial` si está presente
            const valorInicial = elemento.getAttribute("data-valor-inicial");
            if (valorInicial) {
                elemento.textContent = valorInicial;
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", inicializarRecursos);


// Elementos del DOM donde se mostrarán los recursos
const recursosElementos = {
	piedra: document.getElementById('recurso-piedra'),
	metal: document.getElementById('recurso-metal'),
	madera: document.getElementById('recurso-madera'),
	comida: document.getElementById('recurso-comida'),
	oro: document.getElementById('recurso-oro'),
	mana: document.getElementById('recurso-mana')
};

// Función para actualizar los recursos según el tiempo transcurrido
function actualizarRecursos() {
    const timestampActual = Math.floor(Date.now() / 1000); // Obtiene el tiempo actual en segundos
    const segundosTranscurridos = timestampActual - fechaUltimaActualizacion; // Tiempo desde la última actualización

    // Calcula los recursos acumulados desde la última actualización
    for (const recurso in recursosActuales) {
        if (produccionPorHora[recurso] > 0) {
            const produccionPorSegundo = produccionPorHora[recurso] / 3600; // Conversión de hora a segundo
            recursosActuales[recurso] = 
                recursosIniciales[recurso] + produccionPorSegundo * segundosTranscurridos;
        }
    }

    // Actualiza los valores en el DOM
    for (const [recurso, elemento] of Object.entries(recursosElementos)) {
        if (elemento) {
			// Redondea a entero y lo reducimos con la función reducirNumero
            elemento.textContent = reducirNumero(Math.floor(recursosActuales[recurso]));
        }
    }
}

// Llama a la función cada segundo para mantener los valores actualizados en tiempo real
setInterval(actualizarRecursos, 1000);
*/

// Expandir/Contraer elementos asociados
document.querySelectorAll(".toggle").forEach((toggle) => {
  // Encuentra el contenedor más cercano con una clase que termine en "-header"
  const header = toggle.closest("[class$='-header']");

  if (header) {
    const container = header.parentElement; // Obtiene el contenedor padre inmediato
    const baseClass = header.className.replace(/-header$/, ""); // Extrae la base de la clase
    const content = container.querySelector(`.${baseClass}-content`); // Busca el contenido asociado dentro del contenedor

    if (content) {
      header.addEventListener("click", () => {
        const isCollapsed =
          content.style.display === "none" || !content.style.display;
        content.style.display = isCollapsed ? "block" : "none"; // Cambia el estado del contenido
        toggle.classList.toggle("alreves", !isCollapsed); // Cambia el estado visual del toggle
      });
    }
  }
});

//Para navegar entre las regiones pulsando 1-9 y al reino pulsanro R
document.addEventListener("keydown", function (event) {
  let hay_comando_para_esta_tecla = 0;
  // Comprueba si la tecla presionada es un número entre 1 y 9
  if (event.key >= "1" && event.key <= "9") {
    event.preventDefault(); // Evita la acción predeterminada
    hay_comando_para_esta_tecla = 1;

    // La tecla presionada será el valor de i
    const i = event.key;

    // Busca todos los elementos cuyo id comience con "region-i-"
    const regionElements = document.querySelectorAll(`[id^="region-${i}-"]`);

    if (regionElements.length > 0) {
      // Encuentra el primer elemento válido en la lista
      for (const element of regionElements) {
        if (element.tagName === "A" && element.href) {
          console.log(`Redirigiendo a ${element.href}`);
          window.location.href = element.href;
          return; // Sal del bucle después de redirigir
        }
      }
    }
  }

  // Si pulsa R o r → ir a la URL indicada
  if (event.key.toLowerCase() === "r") {
    event.preventDefault(); // Evita la acción predeterminada
    hay_comando_para_esta_tecla = 1;

    window.location.href = "reino.php";
    return;
  }

  if (hay_comando_para_esta_tecla == 0) {
    console.log(`No se encontró un enlace válido para la tecla "${i}"`);
  }
});

// Convierte un número a un formato reducido sin decimales, redondeando hacia abajo
function reducirNumero(numero) {
  numero = Math.floor(numero);
  if (numero < 1000) {
    return numero.toString();
  }
  const unidades = ["", "K", "M", "B", "T"]; // Abreviaturas para miles, millones, etc.
  let indice = Math.floor(Math.log10(numero) / 3); // Calcula el índice para las unidades
  if (indice >= unidades.length) {
    indice = unidades.length - 1;
  }
  const divisor = Math.pow(1000, indice); // Divide por 1000, 1M, 1B, etc.
  const reducido = Math.floor(numero / divisor);

  return `${reducido}${unidades[indice]}`;
}
