const translations = {
    es: {
        slogan: "Pizzas para todos los gustos",
        question1: "¿Qué deseas hacer?",
        delivery: "Pedir a domicilio",
        takeaway: "Pedir para recoger en local",
        book_table: "Reservar mesa",
        login_employees: "Acceso para trabajadores",
        goback_button: "Volver atrás",
        footer: "2026 Customizza | Proyecto 2º Desarrollo de Aplicaciones Web"
    },
    en: {
        slogan: "A pizza for each taste",
        question1: "What would you want to do?",
        delivery: "Order delivery",
        takeaway: "Order takeaway",
        book_table: "Book a table",
        login_employees: "Login (for employees)",
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
