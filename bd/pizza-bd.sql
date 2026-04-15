CREATE DATABASE pizzeria;
USE pizzeria;

CREATE TABLE usuario (
    nombre_cuenta VARCHAR(100),
    contrasenna VARCHAR(100) NOT NULL,
    nombre_usuario VARCHAR(100) NOT NULL,
    rango VARCHAR(50),
    PRIMARY KEY (nombre_cuenta)
);

CREATE TABLE ingrediente (
    id_ing INT(5) AUTO_INCREMENT,
    nombre_ing VARCHAR(100) NOT NULL,
    nombre_ing_en VARCHAR(100) NOT NULL,
    precio_ing DECIMAL(6,2),
    aptoparaceliacos_ing TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id_ing)
);

CREATE TABLE pedido (
    id_pedido INT(8) AUTO_INCREMENT,
    nombre_cliente VARCHAR(60),
    nombre_cuenta VARCHAR (100),
    precio_total DECIMAL(8,2),
    fecha DATETIME NOT NULL,
    estado VARCHAR(50),
    PRIMARY KEY (id_pedido),
    FOREIGN KEY (nombre_cuenta) REFERENCES usuario(nombre_cuenta)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE mesa (
    id_pedido INT (8),
    nmesa INT (3) NOT NULL,
    hora_reserva DATETIME,
    comensales INT (4),
    PRIMARY KEY (id_pedido, nmesa),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE recoger (
    id_pedido INT (8),
    PRIMARY KEY (id_pedido),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE domicilio (
    id_pedido INT (8),
    direccion VARCHAR(200) NOT NULL,
    tlf INT (9),
    PRIMARY KEY (id_pedido),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE producto (
    id_prod INT (6) AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    nombre_en VARCHAR(100) NOT NULL,
    precio_ud DECIMAL(6,2) NOT NULL,
    PRIMARY KEY (id_prod)
);

CREATE TABLE pizza (
    id_pizza INT (9) AUTO_INCREMENT,
    precio DECIMAL(6,2) NOT NULL,
    PRIMARY KEY (id_pizza)
);

CREATE TABLE linea (
    id_linea INT(11) AUTO_INCREMENT,
    id_pedido INT(8), 
    cantidad INT (4) NOT NULL,
    precio_total DECIMAL(8,2),
    PRIMARY KEY (id_linea),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE linea_producto (
    id_linea INT (11),
    id_prod INT (6),
    id_pizza INT (9),
    PRIMARY KEY (id_linea, id_prod),
    FOREIGN KEY (id_linea) REFERENCES linea(id_linea)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_prod) REFERENCES producto(id_prod)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE linea_pizza (
    id_linea INT (11),
    id_pizza INT (9),
    PRIMARY KEY (id_linea, id_pizza),
    FOREIGN KEY (id_linea) REFERENCES linea(id_linea)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_pizza) REFERENCES pizza(id_pizza)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE pizza_ingrediente (
    id_pizza INT (9),
    id_ing INT (5),
    PRIMARY KEY (id_pizza, id_ing),
    FOREIGN KEY (id_pizza) REFERENCES pizza(id_pizza)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_ing) REFERENCES ingrediente(id_ing)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


