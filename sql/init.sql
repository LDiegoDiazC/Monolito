-- Introducción a Microservicios: Arquitectura y Contenedores
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_email VARCHAR(255),
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, price, description) VALUES
('Laptop Gamer', 1200.00, 'Super rapida con luces RGB'),
('Cafetera Pro', 300.50, 'Hace cafe mientras duermes o trabajas'),
('Teclado Mecanico', 150.00, 'Sonido como de una maquina de escribir');