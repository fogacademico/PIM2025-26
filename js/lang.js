const translations = {
    es: {
        home: "Inicio",
        store: "Tienda",
        library: "Biblioteca",
        login: "Iniciar sesión",
        hero_title: "Tu centro definitivo de videojuegos",
        hero_text: "Compra, descarga y gestiona tus juegos y aplicaciones en un solo lugar.",
        register: "Registrarse gratis",
        features: "Características principales",
        feature1: "Biblioteca personal",
        feature1_text: "Accede a todos tus juegos comprados.",
        feature2: "Modo offline",
        feature2_text: "Disfruta de tus juegos sin conexión.",
        feature3: "Puntos de tienda",
        feature3_text: "Consigue descuentos por tus compras.",
        admin_text: "Gestiona usuarios y contenido.",
        newsletter_title: "Suscríbete a la newsletter",
        newsletter_text: "Recibe ofertas, eventos y novedades directamente en tu correo.",
        newsletter_placeholder: "Tu correo electrónico",
        newsletter_button: "Suscribirme",
        footer: "© 2026 Gaming Hub | Proyecto 2º DAW"
    },
    en: {
        home: "Home",
        store: "Store",
        library: "Library",
        login: "Login",
        hero_title: "Your ultimate gaming hub",
        hero_text: "Buy, download and manage your games and applications in one place.",
        register: "Sign up for free",
        features: "Main features",
        feature1: "Personal library",
        feature1_text: "Access all your purchased games.",
        feature2: "Offline mode",
        feature2_text: "Enjoy your games without internet.",
        feature3: "Store points",
        feature3_text: "Get discounts with every purchase.",
        newsletter_title: "Subscribe to the newsletter",
        newsletter_text: "Receive offers, events and news directly in your inbox.",
        newsletter_placeholder: "Your email address",
        newsletter_button: "Subscribe",
        footer: "© 2026 Gaming Hub | 2nd DAW Project"
    }
};

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
});
