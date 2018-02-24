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