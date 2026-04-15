// --- USUARIOS (No introducir mediante SGBD)

INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango)
VALUES ("cliente", ? , "Cliente", "customer");

INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango)
VALUES ("josostmal", ? , "José Ostos Maldonado", "employee");

INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango)
VALUES ("soylajefa", ? , "Chiara Berzagli", "admin");

INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango)
VALUES ("gabramram", ? , "Gabriela María Ramírez Ramos", "employee");

INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango)
VALUES ("tontorron", ? , "Toni Torcal Ronelles", "employee");

INSERT INTO usuario (nombre_cuenta, contrasenna, nombre_usuario, rango)
VALUES ("dievelgar", ? , "Diego Vélez García", "admin");

// --- INGREDIENTES

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (1, "Masa estándar pequeña fina", "Small standard thin pizza dough", 2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (2, "Masa estándar pequeña normal", "Small standard normal pizza dough", 2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (3, "Masa estándar pequeña gruesa", "Small standard thick pizza dough", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (4, "Masa estándar mediana fina", "Medium-sized standard thin pizza dough", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (5, "Masa estándar mediana normal", "Medium-sized standard normal pizza dough", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (6, "Masa estándar mediana gruesa", "Medium-sized standard thick pizza dough", 4, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (7, "Masa estándar familiar fina", "Large standard thin pizza dough", 4, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (8, "Masa estándar familiar normal", "Large standard normal pizza dough", 4, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (9, "Masa estándar familiar gruesa", "Large standard thick pizza dough", 5, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (10, "Masa sin gluten pequeña fina", "Glutenfree small thin pizza dough", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (11, "Masa sin gluten pequeña normal", "Glutenfree small normal pizza dough", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (12, "Masa sin gluten pequeña gruesa", "Glutenfree small thick pizza dough", 3, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (13, "Masa sin gluten mediana fina", "Glutenfree medium-sized thin pizza dough", 3, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (14, "Masa sin gluten mediana normal", "Glutenfree medium-sized normal pizza dough", 3, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (15, "Masa sin gluten mediana gruesa", "Glutenfree medium-sized thick pizza dough", 4, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (16, "Masa sin gluten familiar fina", "Glutenfree large thin pizza dough", 4, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (17, "Masa sin gluten familiar normal", "Glutenfree large normal pizza dough", 4, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (18, "Masa sin gluten familiar gruesa", "Glutenfree large thick pizza dough", 5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (19, "Salsa de tomate", "Tomato sauce", 1, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (20, "Mozzarella", "Mozzarella", 1, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (21, "Queso Vegano", "Vegan cheese", 1.10, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (22, "Albahaca", "Basil", 0.5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (23, "Jamón cocido", "Ham", 1.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (24, "Champiñones", "Mushrooms", 1.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (25, "Bacon", "Bacon", 1.5, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (26, "Salchichón tipo Salsiccia", "Salsiccia sausage", 2.2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (27, "Cebolla", "Onion", 1, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (28, "Atún", "Tuna", 2.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (29, "Gambas", "Shrimps", 2.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (30, "Ternera picada", "Minced veal", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (31, "Pollo", "Chicken", 2.2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (32, "Salsa barbacoa", "Barbecue sauce", 1.5, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (33, "Pimiento verde", "Green pepper", 1.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (34, "Aceitunas", "Olives", 1.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (35, "Queso Gorgonzola", "Gorgonzola cheese", 2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (36, "Queso Fontina", "Fontina cheese", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (37, "Queso Grana Padano", "Grana Padano Cheese", 2.5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (38, "Anchoas", "Anchovies", 2.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (39, "Nata", "Cream", 1.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (40, "Queso Crema", "Cream cheese", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (41, "Pollo Marinado", "Marinated chicken", 3.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (42, "Tomate en rodajas", "Tomato slices", 1.5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (43, "Rúcula", "Arugula", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, nombre_ing_en, precio_ing, aptoparaceliacos_ing)
VALUES (44, "Calabacín", "Zucchini", 1.75, 1);


// --- PRODUCTOS

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (1, "Reserva de mesa", "Tabl. Booking", 0);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (2, "Refresco de limón 30cl", "Lemon drink 30cl", 2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (3, "Refresco de cola 30cl", "Cola drink 30cl", 2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (4, "Refresco de naranja 30cl", "Orange drink 30cl", 2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (5, "Refresco de fresa amargo 30cl", "Bitter strawberry drink 30cl", 2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (6, "Refresco de lima-limón 30cl", "Lime-Lemon drink", 2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (7, "Cerveza Malmuerta 33cl", "Malmuerta Beer 33cl", 2.25);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (8, "Refresco de té de limón 30cl", "Lemon Tea-Flavoured drink 30cl", 2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (9, "Refresco de té de maracuyá 30cl", "Passionfruit Tea-Flavoured drink 30cl", 2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (10, "Gaseosa 30cl", "Fizzy drink 30cl", 1.25);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (11, "Pan de ajo", "Garlic bread",1.75);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (12, "Focaccia de romero", "Rosemary focaccia", 2.2);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (13, "Focaccia de romero sin gluten", "Gluten free rosemary focaccia", 2.3);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (14, "Patatas fritas -sin gluten-", "Fries -gluten free-", 2.5);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (15, "Piadina pequeña de pollo", "Small chicken wrap", 3);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (16, "Piadina pequeña vegana", "Small vegan wrap", 2.75);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (17, "Ensalada vinagreta -sin gluten-", "Vinaigrette salad -gluten free-", 4);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (18, "Helado Stracciatella pequeño -sin gluten-", "Small Stracciatella ice cream", 2.75);

INSERT INTO producto (id_prod, nombre, nombre_en, precio_ud)
VALUES (19, "Botella de agua 0.5L", "Water bottle", 1.5);
