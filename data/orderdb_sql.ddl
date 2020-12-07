DROP TABLE review;
DROP TABLE shipment;
DROP TABLE productinventory;
DROP TABLE warehouse;
DROP TABLE orderproduct;
DROP TABLE incart;
DROP TABLE product;
DROP TABLE category;
DROP TABLE ordersummary;
DROP TABLE paymentmethod;
DROP TABLE customer;


CREATE TABLE customer (
    customerId          INT IDENTITY,
    firstName           VARCHAR(40),
    lastName            VARCHAR(40),
    email               VARCHAR(50),
    phonenum            VARCHAR(20),
    address             VARCHAR(50),
    city                VARCHAR(40),
    state               VARCHAR(20),
    postalCode          VARCHAR(20),
    country             VARCHAR(40),
    userid              VARCHAR(20),
    password            VARCHAR(30),
    PRIMARY KEY (customerId)
);

CREATE TABLE paymentmethod (
    paymentMethodId     INT IDENTITY,
    paymentType         VARCHAR(20),
    paymentNumber       VARCHAR(30),
    paymentExpiryDate   DATE,
    customerId          INT,
    PRIMARY KEY (paymentMethodId),
    FOREIGN KEY (customerId) REFERENCES customer(customerid)
        ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE ordersummary (
    orderId             INT IDENTITY,
    orderDate           DATETIME,
    totalAmount         DECIMAL(10,2),
    shiptoAddress       VARCHAR(50),
    shiptoCity          VARCHAR(40),
    shiptoState         VARCHAR(20),
    shiptoPostalCode    VARCHAR(20),
    shiptoCountry       VARCHAR(40),
    customerId          INT,
    PRIMARY KEY (orderId),
    FOREIGN KEY (customerId) REFERENCES customer(customerid)
        ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE category (
    categoryId          INT IDENTITY,
    categoryName        VARCHAR(50),    
    PRIMARY KEY (categoryId)
);

CREATE TABLE product (
    productId           INT IDENTITY,
    productName         VARCHAR(40),
    productPrice        DECIMAL(10,2),
    productImageURL     VARCHAR(100),
    productImage        VARBINARY(MAX),
    productDesc         VARCHAR(1000),
    categoryId          INT,
    PRIMARY KEY (productId),
    FOREIGN KEY (categoryId) REFERENCES category(categoryId)
);

CREATE TABLE orderproduct (
    orderId             INT,
    productId           INT,
    quantity            INT,
    price               DECIMAL(10,2),  
    PRIMARY KEY (orderId, productId),
    FOREIGN KEY (orderId) REFERENCES ordersummary(orderId)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE incart (
    customerId          INT,
    productId           INT,
    productName         VARCHAR(40),
    quantity            INT,
    price               DECIMAL(10,2),
    PRIMARY KEY (customerId, productId),
    FOREIGN KEY (customerId) REFERENCES customer(customerId)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE warehouse (
    warehouseId         INT IDENTITY,
    warehouseName       VARCHAR(30),    
    PRIMARY KEY (warehouseId)
);

CREATE TABLE shipment (
    shipmentId          INT IDENTITY,
    shipmentDate        DATETIME,   
    shipmentDesc        VARCHAR(100),   
    warehouseId         INT, 
    PRIMARY KEY (shipmentId),
    FOREIGN KEY (warehouseId) REFERENCES warehouse(warehouseId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE productinventory ( 
    productId           INT,
    warehouseId         INT,
    quantity            INT,
    price               DECIMAL(10,2),  
    PRIMARY KEY (productId, warehouseId),   
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (warehouseId) REFERENCES warehouse(warehouseId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE review (
    reviewId            INT IDENTITY,
    reviewRating        INT,
    reviewDate          DATETIME,   
    customerId          INT,
    productId           INT,
    reviewComment       VARCHAR(1000),          
    PRIMARY KEY (reviewId),
    FOREIGN KEY (customerId) REFERENCES customer(customerId)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO category(categoryName) VALUES ('Aquarius');
INSERT INTO category(categoryName) VALUES ('Aries');
INSERT INTO category(categoryName) VALUES ('Cancer');
INSERT INTO category(categoryName) VALUES ('Capricornus');
INSERT INTO category(categoryName) VALUES ('Leo');
INSERT INTO category(categoryName) VALUES ('Libra');
INSERT INTO category(categoryName) VALUES ('Pisces');
INSERT INTO category(categoryName) VALUES ('Sagittarius');
INSERT INTO category(categoryName) VALUES ('Scorpius');
INSERT INTO category(categoryName) VALUES ('Taurus');

INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Albali', 1, 'Located at 11°43′',5832000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Sadalsuud',1,'Located at 23°24′',9102000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Sadalmelik',1,'Located at 03°46′',8102000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Skat',1,'Located at 08°52′',9102000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Mesarthim',2,'Located at 03°11′',3972000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Sheratan',2,'Located at 03°58′',2734000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Hamal',2,'Located at 07°40′',1293000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Asellus Australis',3,'Located at 08°43′',8219000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Asellus Borealis',3,'Located at 07°32′',9241000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Acubens',3,'Located at 13°39′',2837000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Deneb Algedi',4,'Located at 23°33′',6438000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Dabih',4,'Located at 04°03′',7139000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Nashira',4,'Located at 21°47′',8291000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Denebola',5,'Located at 21°37′',9183000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Zosma',5,'Located at 11°19′',8129000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Regulus',5,'Located at 29°50′',1249000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Adhafera',5,'Located at 27°34′',1732000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Zubeneschamali',6,'Located at 19°23′',1290000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Zubenelgenubi',6,'Located at 15°05′',7238000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Alpherg',7,'Located at 26°39′',8190000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Alresha',7,'Located at 29°23′',8120000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Ascella',8,'Located at 13°38′',3211000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Kaus Australis',8,'Located at 05°05′',1122000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Kaus Borealis',8,'Located at 06°19′',8201000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Nunki',8,'Located at 12°23′',8120000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Lesath',9,'Located at 24°01′',1393000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Antares',9,'Located at 09°46′', 9201000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Dschubba',9,'Located at 02°34′', 1820000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Acrab',9,'Located at 03°12′', 8201000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Elnath',10,'Located at 22°35′', 9921000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Aldebaran',10,'Located at 09°47′', 2390000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Ain',10,'Located at 08°28′', 1300000.00);
INSERT product(productName, categoryId, productDesc, productPrice) VALUES ('Alcyone',10,'Located at 00°00′', 3331000.00);


INSERT INTO warehouse(warehouseName) VALUES ('Main warehouse');
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (1, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (2, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (3, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (4, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (5, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (6, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (7, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (8, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (9, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (10, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (11, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (12, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (13, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (14, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (15, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (16, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (17, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (18, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (19, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (20, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (21, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (22, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (23, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (24, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (25, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (26, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (27, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (28, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (29, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (30, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (31, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (32, 1, 1, 20);
INSERT INTO productInventory(productId, warehouseId, quantity, price) VALUES (33, 1, 1, 20);

INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Admin', '', 'admin@sfs.ru', '', '', '', '', '', '', 'admin' , 'admin');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Arnold', 'Anderson', 'a.anderson@gmail.com', '204-111-2222', '103 AnyWhere Street', 'Winnipeg', 'MB', 'R3X 45T', 'Canada', 'arnold' , 'test');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Sofia', 'Popov', 's.popov@gmail.com', '204-111-2222', '103 Mercury Street', 'Yekaterinburg', 'GU', '920341', 'Russia', 'sofia' , '1234');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Denis', 'Smirnoff', 'd.smirnoff@gmail.ca', '572-342-8911', '222 Venus Avenue', 'Kazan', 'YU', '222222', 'Russia', 'denis' , '5678');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Nikita', 'Petrov', 'n.petrov@gmail.com', '333-444-5555', '333 Mars Crescent', 'Chelyabinsk', 'SH', '333333', 'Russia', 'nikita' , '1357');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Ivan', 'Semenov', 'ivan.semenov@gmail.com', '342-807-2222', '444 Jupiter Lane', 'Samara', 'VO', '234161', 'Russia', 'ivan' , '2468');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Vera', 'Fedorov', 'v.fedorov@gmail.com', '555-666-7777', '555 Saturn Street', 'Omsk', 'OL', '522241', 'Russia', 'vera' , '3579');

-- Order 1 can be shipped as have enough inventory
DECLARE @orderId int
INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (1, '2019-10-15 10:25:55', 91.70)
SELECT @orderId = @@IDENTITY
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 1, 1, 18)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 5, 2, 21.35)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 10, 1, 31);

DECLARE @orderId int
INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (2, '2019-10-16 18:00:00', 106.75)
SELECT @orderId = @@IDENTITY
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 5, 5, 21.35);

-- Order 3 cannot be shipped as do not have enough inventory for item 7
DECLARE @orderId int
INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (3, '2019-10-15 3:30:22', 140)
SELECT @orderId = @@IDENTITY
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 6, 2, 25)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 7, 3, 30);

DECLARE @orderId int
INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (2, '2019-10-17 05:45:11', 327.85)
SELECT @orderId = @@IDENTITY
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 3, 4, 10)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 8, 3, 40)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 13, 3, 23.25)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 28, 2, 21.05)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 29, 4, 14);

DECLARE @orderId int
INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (5, '2019-10-15 10:25:55', 277.40)
SELECT @orderId = @@IDENTITY
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 5, 4, 21.35)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 19, 2, 81)
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@orderId, 20, 3, 10);

-- New SQL DDL for lab 8
UPDATE Product SET productImageURL = 'img/albali.jpg' WHERE ProductId = 1;
UPDATE Product SET productImageURL = 'img/sadalsuud.jpg' WHERE ProductId = 2;
UPDATE Product SET productImageURL = 'img/sadalmelik.jpg' WHERE ProductId = 3;
UPDATE Product SET productImageURL = 'img/skat.jpg' WHERE ProductId = 4;
UPDATE Product SET productImageURL = 'img/mesarthim.jpg' WHERE ProductId = 5;
UPDATE Product SET productImageURL = 'img/sheratan.jpg' WHERE ProductId = 6;
UPDATE Product SET productImageURL = 'img/hamal.png' WHERE ProductId = 7;
UPDATE Product SET productImageURL = 'img/asellus-australis.jpg' WHERE ProductId = 8;
UPDATE Product SET productImageURL = 'img/asellus-borealis.jpg' WHERE ProductId = 9;
UPDATE Product SET productImageURL = 'img/acubens.jpg' WHERE ProductId = 10;
UPDATE Product SET productImageURL = 'img/deneb-algedi.jpg' WHERE ProductId = 11;
UPDATE Product SET productImageURL = 'img/dabih.jpg' WHERE ProductId = 12;
UPDATE Product SET productImageURL = 'img/nashira.jpg' WHERE ProductId = 13;
UPDATE Product SET productImageURL = 'img/denebola.jpg' WHERE ProductId = 14;
UPDATE Product SET productImageURL = 'img/zosma.jpg' WHERE ProductId = 15;
UPDATE Product SET productImageURL = 'img/regulus.jpg' WHERE ProductId = 16;
UPDATE Product SET productImageURL = 'img/adhafera.jpg' WHERE ProductId = 17;
UPDATE Product SET productImageURL = 'img/zubeneschamali.jpg' WHERE ProductId = 18;
UPDATE Product SET productImageURL = 'img/zubenelgenubi.jpg' WHERE ProductId = 19;
UPDATE Product SET productImageURL = 'img/alpherg.jpg' WHERE ProductId = 20;
UPDATE Product SET productImageURL = 'img/alresha.jpg' WHERE ProductId = 21;
UPDATE Product SET productImageURL = 'img/ascella.jpg' WHERE ProductId = 22;
UPDATE Product SET productImageURL = 'img/kaus-australis.jpg' WHERE ProductId = 23;
UPDATE Product SET productImageURL = 'img/kaus-borealis.jpg' WHERE ProductId = 24;
UPDATE Product SET productImageURL = 'img/nunki.jpg' WHERE ProductId = 25;
UPDATE Product SET productImageURL = 'img/lesath.jpg' WHERE ProductId = 26;
UPDATE Product SET productImageURL = 'img/antares.jpg' WHERE ProductId = 27;
UPDATE Product SET productImageURL = 'img/dschubba.jpg' WHERE ProductId = 28;
UPDATE Product SET productImageURL = 'img/acrab.jpg' WHERE ProductId = 29;
UPDATE Product SET productImageURL = 'img/elnath.jpg' WHERE ProductId = 30;
UPDATE Product SET productImageURL = 'img/aldebaran.jpg' WHERE ProductId = 31;
UPDATE Product SET productImageURL = 'img/ain.png' WHERE ProductId = 32;
UPDATE Product SET productImageURL = 'img/alcyone.jpg' WHERE ProductId = 33;
