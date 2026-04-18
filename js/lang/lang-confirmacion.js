let translations = {
    es: {
        products_title: "Productos",
        pizzas_title: "Pizzas",
        order_title: "Pedido registrado:",
        import_title: "Total: ",
        navigation: "¿Qué quieres hacer con tu pedido?",
        error_wrong_order_type: "Tipo de pedido no válido. Quizá se deba a un fallo de conexión.",
        back_to_menu: "Volver al menú principal.",
        send_order: "Confirmar pedido",
        back_to_selection: "Cambiar pedido",
    },
    en: {
        products_title: "Products",
        pizzas_title: "Pizzas",
        order_title: "Registered order:",
        import_title: "Import: ",
        navigation: "What do you want to do with your order?",
        error_wrong_order_type: "Invalid order type. This error might be caused by a connection failure.",
        back_to_menu: "Back to the main menu.",
        send_order: "Confirm order",
        back_to_selection: "Change order",
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
