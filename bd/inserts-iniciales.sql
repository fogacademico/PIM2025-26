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

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (1, "Masa estándar pequeña fina", 2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (2, "Masa estándar pequeña normal", 2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (3, "Masa estándar pequeña gruesa", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (4, "Masa estándar mediana fina", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (5, "Masa estándar mediana normal", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (6, "Masa estándar mediana gruesa", 4, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (7, "Masa estándar familiar fina", 4, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (8, "Masa estándar familiar normal", 4, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (9, "Masa estándar familiar gruesa", 5, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (10, "Masa sin gluten pequeña fina", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (11, "Masa sin gluten pequeña normal", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (12, "Masa sin gluten pequeña gruesa", 3, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (13, "Masa sin gluten mediana fina", 3, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (14, "Masa sin gluten mediana normal", 3, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (15, "Masa sin gluten mediana gruesa", 4, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (16, "Masa sin gluten familiar fina", 4, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (17, "Masa sin gluten familiar normal", 4, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (18, "Masa sin gluten familiar gruesa", 5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (19, "Salsa de tomate", 1, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (20, "Mozzarella", 1, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (21, "Queso Vegano", 1.10, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (22, "Albahaca", 0.5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (23, "Jamón cocido", 1.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (24, "Champiñones", 1.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (25, "Bacon", 1.5, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (26, "Salchichón tipo Salsiccia", 2.2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (27, "Cebolla", 1, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (28, "Atún", 2.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (29, "Gambas", 2.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (30, "Ternera picada", 3, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (31, "Pollo", 2.2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (32, "Salsa barbacoa", 1.5, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (33, "Pimiento verde", 1.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (34, "Aceitunas", 1.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (35, "Queso Gorgonzola", 2, 0);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (36, "Queso Fontina", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (37, "Queso Grana Padano", 2.5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (38, "Anchoas", 2.75, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (39, "Nata", 1.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (40, "Queso Crema", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (41, "Pollo Marinado", 3.25, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (42, "Tomate en rodajas", 1.5, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (43, "Rúcula", 2, 1);

INSERT INTO ingrediente (id_ing, nombre_ing, precio_ing, aptoparaceliacos_ing)
VALUES (44, "Calabacín", 1.75, 1);


// --- PRODUCTOS

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (1, "Reserva de mesa", 0);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (2, "Refresco de limón 30cl", 2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (3, "Refresco de cola 30cl", 2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (4, "Refresco de naranja 30cl", 2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (5, "Refresco de fresa amargo 30cl", 2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (6, "Refresco de lima-limón 30cl", 2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (7, "Cerveza Malmuerta 33cl", 2.25);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (8, "Refresco de té de limón 30cl", 2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (9, "Refresco de té de maracuyá 30cl", 2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (10, "Gaseosa 30cl", 1.25);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (11, "Pan de ajo", 1.75);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (12, "Focaccia de romero", 2.2);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (13, "Focaccia de romero sin gluten", 2.3);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (14, "Patatas fritas -sin gluten-", 2.5);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (15, "Piadina pequeña de pollo", 3);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (16, "Piadina pequeña vegana", 2.75);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (17, "Ensalada vinagreta -sin gluten-", 4);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (18, "Helado Stracciatela pequeño -sin gluten-", 2.75);

INSERT INTO producto (id_prod, nombre, precio_ud)
VALUES (19, "Botella de agua 0.5L", 1.5);
