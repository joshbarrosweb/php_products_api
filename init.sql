-- Create the tables

CREATE TABLE IF NOT EXISTS products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  quantity INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS product_types (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS taxes (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  rate DECIMAL(5, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sales (
  id SERIAL PRIMARY KEY,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  total DECIMAL(10, 2) NOT NULL,
  tax_amount DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample data

INSERT INTO products (name, price, quantity) VALUES
  ('Product 1', 10.99, 100),
  ('Product 2', 15.99, 50),
  ('Product 3', 20.99, 200);

INSERT INTO product_types (name) VALUES
  ('Type 1'),
  ('Type 2'),
  ('Type 3');

INSERT INTO taxes (name, rate) VALUES
  ('Tax 1', 10),
  ('Tax 2', 15),
  ('Tax 3', 20);
