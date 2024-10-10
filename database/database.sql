CREATE TABLE User (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	name TEXT,
	username TEXT,
	password VARCHAR(32),
	email TEXT,
	image_path TEXT,
	admin BOOLEAN
);

CREATE TABLE Category (
	name TEXT PRIMARY KEY
);

CREATE TABLE Size (
	name TEXT PRIMARY KEY
);

CREATE TABLE Conditions (
	name TEXT PRIMARY KEY
);

CREATE TABLE Item (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	price FLOAT,
	title TEXT,
	description TEXT,
	gender TEXT,
	category TEXT REFERENCES Category(name),
	brand TEXT,
	size TEXT REFERENCES Size(size),
	conditions TEXT REFERENCES Conditions(name),
	image_path TEXT,
	seller_id INT,
    FOREIGN KEY (seller_id) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE Purchase ( 
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    buyer_id INT REFERENCES User(id),
	item_id INT,
	FOREIGN KEY (item_id) REFERENCES Item(id) ON DELETE CASCADE

);

CREATE TABLE Cart(
    user_id INT REFERENCES User(id),
    item_id INT REFERENCES Item(id),
    PRIMARY KEY (user_id, item_id)
);

CREATE TABLE Message(
    sender_id INT REFERENCES USER(id),
    receiver_id INT REFERENCES USER (id),
	date TIMESTAMP,
    msg TEXT,
    PRIMARY KEY (sender_id,receiver_id, date)
);

INSERT INTO User (id, name, username, password, email, image_path, admin) VALUES
(1, "John Doe", "johndoe", "$2y$10$8.5LgKrCQD4afGXPwxhtLeRIqEPDVy2NhoAHmz2Lm6vNK1m.TT/1C", "john@example.com", "58c68b16391532eba2fdda2d1bbd4173.jpg", false),
(2, "Jane Smith", "janesmith", "$2y$10$FPd6QaQ/EaJBgs0iq9VSUe9f0uO9P1q1fiijCxDCb7VLW0oCyYpii", "jane@example.com", "default.png", false),
(3, "Admin User", "admin", "$2y$10$518mHrOd4Nxi1bybEiz2SO6oHT84YKVPOTIe9BM5Qi79Px3hZPa0S", "admin@example.com", "default.png", true);

INSERT INTO Category (name) VALUES 
('Tops & T-Shirts'),
('Hoodies & Sweatshirts'),
('Jackets'),
('Trousers & Tights'),
('Skirts & Dresses'),
('Tracksuits'),
('Shorts'),
('Swimwear'),
('Shoes');

INSERT INTO Size (name) VALUES 
('XS'),
('S'),
('M'),
('L'),
('XL'),
('8'),
('10'),
('12'),
('14');

INSERT INTO Conditions (name) VALUES 
('New with tags'),
('New without tags'),
('Very good'),
('Good'),
('Used');

INSERT INTO Item (price, title, description, gender, category, brand, size, conditions, image_path, seller_id) VALUES
(29.99, 'Black T-Shirt', 'A stylish black t-shirt for casual wear.', 'Men', 'Tops & T-Shirts', 'BrandX', 'M', 'New with tags', "58c68b16391532eba2fdda2d1bbd4173.jpg", 1),
(49.99, 'Hooded Sweatshirt', 'A comfortable hooded sweatshirt for cold days.', 'Men', 'Hoodies & Sweatshirts', 'BrandY', 'L', 'Very good', "itemdefault.png", 2),
(79.99, 'Leather Jacket', 'A trendy leather jacket for all seasons.', 'Men', 'Jackets', 'BrandZ', 'XL', 'Good', "itemdefault.png", 1),
(39.99, 'Denim Jeans', 'Classic denim jeans for everyday wear.', 'Women', 'Trousers & Tights', 'BrandA', '32', 'Used', "ba1f97be7a023a5f2b6f2cc9c5425a34.jpg", 2),
(24.99, 'Training Tracksuit', 'A comfortable tracksuit for workouts.', 'Women', 'Tracksuits', 'BrandC', 'XL', 'Very good', "bb96917859dd6ac2b9c262ed5a34b7cf.jpg", 2),
(19.99, 'Denim Shorts', 'Classic denim shorts for casual wear.', 'Women', 'Shorts', 'BrandD', '30', 'Good', "ba1f97be7a023a5f2b6f2cc9c5425a34.jpg", 2),
(69.99, 'Running Shoes', 'Lightweight running shoes for athletes.', 'Women', 'Shoes', 'BrandF', '10', 'Very good', "77c687438e27555b9abc6375b5c3d590.jpg", 1),
(54.99, 'Fleece Jacket', 'A warm fleece jacket with a full zip.', 'Women', 'Jackets', 'BrandH', 'XL', 'Used', "c01d047d67d33fd66ea564f7264d6020.jpg", 1),
(79.99, 'Ankle Boots', 'Stylish ankle boots with a stacked heel.', 'Kids', 'Shoes', 'BrandM', '9', 'New with tags', "77c687438e27555b9abc6375b5c3d590.jpg", 1),
(64.99, 'Down Jacket', 'A lightweight down jacket for outdoor adventures.', 'Kids', 'Jackets', 'BrandO', 'L', 'Good', "c01d047d67d33fd66ea564f7264d6020.jpg", 2);


INSERT INTO Cart (user_id, item_id) VALUES
(1, 2),
(1, 3),
(1, 4),
(2, 1);

INSERT INTO Message (sender_id, receiver_id, date, msg) VALUES
(2, 1, '2024-04-12 11:15:00', 'primeira mensagem 2!'),
(1, 2, '2024-04-12 11:16:00', 'segunda 2!'),
(1, 2, '2024-04-12 11:17:00', 'terceira 2!'),
(2, 1, '2024-04-12 11:18:00', 'quarta mensagem 2!'),
(2, 1, '2024-04-12 11:19:00', 'quinta mensagem 2!'),
(1, 2, '2024-04-12 11:20:00', 'sexta 2!'),
(3, 1, '2024-04-12 11:15:00', 'primeira mensagem 3!'),
(1, 3, '2024-04-12 11:16:00', 'segunda 3!'),
(1, 3, '2024-04-12 11:17:00', 'terceira 3!'),
(3, 1, '2024-04-12 11:18:00', 'quarta mensagem 3!'),
(3, 1, '2024-04-12 11:19:00', 'quinta mensagem 3!'),
(1, 3, '2024-04-12 11:20:00', 'sexta 3!'),
(2, 3, '2024-04-12 11:20:00', 'Glad to hear that, John! Let me know if you need anything else.');
