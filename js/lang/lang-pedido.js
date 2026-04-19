const translations = {
    es: {
        slogan: "Pizzas para todos los gustos",
        form_title_delivery: "Información de la entrega",
        form_title_takeaway: "Hacer pedido",
        name_title1: "Nombre:",
        address: "Dirección",
        phone: "Teléfono",
        customer_name: "Pedido a nombre de...",
        form_title_tables: "Menú de reserva de mesas",
        table_booking_question: "¿Quieres hacer ya el pedido, o sólo reservar mesa?",
        table_booking1: "Sólo reservar mesa.",
        table_booking2: "Reservar mesa y hacer pedido.",
        booking_date: "Elige fecha: ",
        booking_hour: "Elige hora: ",
        guests: "Elige número de comensales: ",
        submit_button: "Continuar",
        goback_button: "Volver atrás",
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
        form_title_tables: "Table booking menu",
        table_booking_question: "Do you want to book a table and order?",
        table_booking1: "I just want to book a table. I'll order at the restaurant.",
        table_booking2: "I want to book a table and order already.",
        booking_date: "Choose a date: ",
        booking_hour: "Choose an hour: ",
        guests: "How many will you be? ",
        submit_button: "Continue",
        goback_button: "Go back",
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
    // document.getElementById("form_idioma").value = lang;
    applyLanguage(lang);

    botonEs.addEventListener("click", function(e){
        setLanguage("es");
        // document.getElementById("form_idioma").value = "es";
        applyLanguage("es");
    });

    botonEn.addEventListener("click", function(e){
        setLanguage("en");
        // document.getElementById("form_idioma").value = "en";
        applyLanguage("en");
    });
});
