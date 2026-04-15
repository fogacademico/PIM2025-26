const translations = {
    es: {
        slogan: "Pizzas para todos los gustos",
        form_title_delivery: "Información de la entrega",
        form_title_takeaway: "Hacer pedido",
        name_title1: "Nombre:",
        address: "Dirección",
        phone: "Teléfono",
        customer_name: "Pedido a nombre de...",
        submit_button: "Continuar",
        footer: "2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web"
    },
    en: {
        slogan: "A pizza for each taste",
        form_title_delivery: "Delivery's details",
        form_title_takeaway: "Make an order",
        name_title1: "Name:",
        address: "Address",
        phone: "Phone",
        customer_name: "Order made by...",
        submit_button: "Continue",
        footer: "2026 Customizza | Web Applications Development Degree's 2nd year Project."
    }
};

let botonEs = document.getElementById("boton-es");
let botonEn = document.getElementById("boton-en");

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
    document.getElementById("form_idioma").value = lang;
    applyLanguage(lang);

    botonEs.addEventListener("click", function(e){
        setLanguage("es");
        document.getElementById("form_idioma").value = "es";
        applyLanguage("es");
    });

    botonEn.addEventListener("click", function(e){
        setLanguage("en");
        document.getElementById("form_idioma").value = "en";
        applyLanguage("en");
    });
});
