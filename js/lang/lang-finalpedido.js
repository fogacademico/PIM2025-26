let translations = {
    es: {
        error_msg1: "Ha habido un error al enviar tu pedido. Llama por teléfono a Customizza o contacta con un empleado de la pizzería para comprobar si tu pedido se ha recibido correctamente o no.",
        error_msg2: "El número del pedido potencialmente afectado es el",
        error_msg3: "No se ha recibido ningún pedido detallado.",
        error_msg4: "Tipo de pedido no especificado o incorrecto. No sabemos si vienes tú a por la pizza o te la llevamos nosotros.",
        error_msg5: "Domicilio no especificado en un pedido a domicilio.",
        phone_text: "Teléfono de Customizza: ",
        success_msg1: "Lograste enviar un pedido.",
        success_msg2: "El número de tu último pedido es el",
        order_info1: "Un encargo promedio suele tardar entre 5 y 15 minutos en prepararse para recoger en local y entre 25 y 40 minutos en prepararse y entregarse a domicilio.",
        order_info2: "Customizza se reserva el derecho de no aceptar pedidos muy grandes. Si disponemos de un número de teléfono, te avisaremos por llamada. Las cancelaciones se hacen por teléfono o en persona.",
        go_back: "Volver al menú principal.",
    },
    en: {
        error_msg1: "An error occurred while your order was being sent. Phone Customizza or contact with an employee at the restaurant to check out if your order has been received correctly.",
        error_msg2: "The potentially affected order's number is",
        error_msg3: "We did not receive the details of any order.",
        error_msg4: "Unspecified or wrong order type. We do not know whether you will take your pizza at the restaurant or it is a delivery order.",
        error_msg5: "Unspecified address in a delivery order.",
        phone_text: "Customizza's phone number: ",
        success_msg1: "You sent an order successfully.",
        success_msg2: "Your last order number is",
        order_info1: "An average order usually takes between 5 and 15 minutes to be good to go at the restaurant, and between 25 and 40 minutes to be prepared and delivered.",
        order_info2: "Customizza may not accept orders if they contain too many products. If you have sent us a phone number, we will call you to inform you about your order. Orders can only be cancelled by phone or in person.",
        go_back: "Go back to the main menu.",
    }
};

let productsInputs = document.getElementsByClassName("input-producto");
let ingredientesInputs = document.getElementsByClassName("input-ing");
let botonEs = document.getElementById("boton-es");
let botonEn = document.getElementById("boton-en");


Object.values(productsInputs).forEach((x) => {
  translations["es"][x.id] = x.dataset.nombreprod; 
  translations["en"][x.id] = x.dataset.nombreproden; 
});

Object.values(ingredientesInputs).forEach((x) => {
  translations["es"][x.id] = x.dataset.nombreingr; 
  translations["en"][x.id] = x.dataset.nombreingren; 
});

function setLanguage(lang) {
    localStorage.setItem("language", lang);
    applyLanguage(lang);
}

function applyLanguage(lang) {
    document.querySelectorAll("[data-i18n]").forEach(el => {
        const key = el.getAttribute("data-i18n");
        el.textContent = translations[lang][key];
    });

    document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
        const key = el.getAttribute("data-i18n-placeholder");
        el.placeholder = translations[lang][key];
    });

    document.querySelectorAll("[data-i18n-value]").forEach(el => {
        const key = el.getAttribute("data-i18n-value");
        el.value = translations[lang][key];
    });   
}


document.addEventListener("DOMContentLoaded", () => {

    const lang = localStorage.getItem("language") || "es";
    applyLanguage(lang);

    botonEs.addEventListener("click", function(e){
        setLanguage("es");
        applyLanguage("es");
    });

    botonEn.addEventListener("click", function(e){
        setLanguage("en");
        applyLanguage("en");
    });
});
