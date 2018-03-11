CREATE TABLE products (
	id SERIAL PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	image VARCHAR(100) NOT NULL,
	description TEXT NOT NULL,
	brand VARCHAR(100) NOT NULL,
	price DECIMAL(10,2) NOT NULL
);


CREATE TABLE accounts (
    email VARCHAR(255) PRIMARY KEY,
    pass VARCHAR(50) 
    firstName VARCHAR(100) NOT NULL, 
    lastName VARCHAR(100) NOT NULL, 
    address TEXT NOT NULL 
);

CREATE TABLE orders (
	id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    FOREIGN KEY (email) REFERENCES accounts(email)
);

CREATE TABLE orderItems (
	id SERIAL PRIMARY KEY,
    order_id bigint(20) unsigned,
    product_id bigint(20) unsigned,
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    productName VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    email VARCHAR(255) NOT NULL
);

INSERT INTO products (name, imagePath, description, brand, price)
VALUES ('Atari Pong', './images/atari/atari6.jpg', 'The orignal ping pong video game console.', 'atari', 10.00);

UPDATE accounts
SET email=email, pass=pass, firstName=firstName, lastName=lastName, address=address
WHERE email=email;

INSERT INTO orders (email) VALUES ("test@gmail.com");
INSERT INTO orderItems (order_id, product_id, quantity) VALUES(1, 4, 2);
INSERT INTO orderItems (order_id, product_id, quantity) VALUES(1, 6, 5);
INSERT INTO orderItems (order_id, product_id, quantity) VALUES(1, 2, 1);

SELECT id FROM orders WHERE email="test@gmail.com";


select orderItems.product_id, orderItems.quantity, products.id, products.name, products.imagePath, products.price
from orderItems
inner join products on products.id = orderItems.product_id
where orderItems.order_id = 1



