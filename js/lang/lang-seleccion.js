let translations = {
    es: {
        ordertype_title: "Tipo de pedido: ",
        order_type_recoger: "A recoger",
        order_type_domicilio: "A domicilio",
        order_type_mesa: "En mesa",
        order_type_error: "ERROR. Se ha perdido el dato del tipo del pedido. Por favor vuelva atrás.",
        goback_button: "Volver atrás",
        products_menu_button: "Ver menú de productos",
        pizzas_menu_button: "Ver menú de pizzas",
        products_title: "Productos",
        add_product: "Añadir al pedido",
        pizzas_title: "Pizzas",
        dough_title: "Masa",
        dough_desc: "Elige una masa",
        glutenfree_dough: "Mostrar masas sin gluten",
        base_ingr_title: "Bases",
        base_ingr_desc: "Elegir al menos una.",
        toppings_title: "Ingredientes",
        toppings_desc: "Elegir máximo 5.",
        pizza_amount: "Cantidad de pizzas de este tipo: ",
        add_pizza: "Añadir pizza al pedido",
        order_title: "PEDIDO:",
        order_import_title: "Total: ",
        send_order: "Confirmar selección y enviar",
    },
    en: {
        ordertype_title: "Order type: ",
        order_type_recoger: "Take away",
        order_type_domicilio: "Home delivery",
        order_type_mesa: "Serve in table",
        order_type_error: "ERROR. Order type data got lost. Please go back.",
        goback_button: "Go back",
        products_menu_button: "See products",
        pizzas_menu_button: "See pizzas",
        products_title: "Products",
        add_product: "Add it to your order",
        pizzas_title: "Pizzas",
        dough_title: "Dough",
        dough_desc: "Select your pizza's dough",
        glutenfree_dough: "Show gluten-free crusts",
        base_ingr_title: "Base ingredients",
        base_ingr_desc: "Select at least one.",
        toppings_title: "Toppings",
        toppings_desc: "Choose between 0 and 5 toppings.",
        pizza_amount: "Amount of pizzas of this type: ",
        add_pizza: "Add pizza to the order",
        order_title: "ORDER:",
        order_import_title: "Import: ",
        send_order: "Confirm selection and send",
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
