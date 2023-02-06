drop TABLE Offers;
drop TABLE Hosts;
drop TABLE WorksFor;
drop TABLE UsesVehicle;
drop TABLE RestaurantSpecificDiscount;
drop TABLE PlatformWidePromotion;
drop TABLE Promotion;
drop TABLE UserAddress;
drop TABLE User_1;
drop TABLE FoodItem;
drop TABLE Restaurant;
-- drop TABLE Restaurant_2;
-- drop TABLE Restaurant;

drop TABLE DeliveryPerson;
drop TABLE Vehicle1;
drop TABLE Vehicle2;
-- drop TABLE Vehicle;
drop table Platform;


CREATE table Platform
    (
    platformName char(50),
    primary key (platformName) 
    );
grant select on Platform to public;


CREATE TABLE User_1
    (username char(30) NOT NULL,
    userID int,
    account_balance float DEFAULT 0.00,
    platformName char(50) NOT NULL,
    PRIMARY KEY(userID),
    FOREIGN KEY(platformName) REFERENCES Platform ON DELETE CASCADE
    );
grant select on User_1 to public;


CREATE TABLE UserAddress(
    streetAddress char(80),
    city char(50) NOT NULL,
    country char(50) NOT NULL,
    addressType char(20) NOT NULL,
    userID int,
    PRIMARY KEY (streetAddress, city, country),
    FOREIGN KEY(userID) REFERENCES User_1 ON DELETE CASCADE
);
grant select on UserAddress to public;

CREATE TABLE Restaurant(
    name char(50) NOT NULL,
    restaurantID int,
    rating int,
    location char(80) NOT NULL,
    type char(20) NOT NULL,
    PRIMARY KEY (restaurantID),
    UNIQUE (location)
);
grant select on Restaurant to public;

-- CREATE TABLE Restaurant_2(
--     name char(50) NOT NULL,
--     type char(30),
--     PRIMARY KEY (name)
-- );
-- grant select on Restaurant_2 to public;


-- CREATE TABLE Restaurant_1(
--     name char(50) NOT NULL,
--     rating int,
--     location char(80) NOT NULL,
--     id int,
--     PRIMARY KEY (id),
--     UNIQUE (location),
--     -- FOREIGN KEY (name) REFERENCES Restaurant_2 ON DELETE CASCADE
-- );
-- grant select on Restaurant_1 to public;


CREATE TABLE FoodItem(
    name char(50),
    restaurantId int,
    PRIMARY KEY (name, restaurantId),
    FOREIGN KEY (restaurantId) REFERENCES Restaurant ON DELETE CASCADE
);
grant select on FoodItem to public;



CREATE TABLE DeliveryPerson(
    name char(50) NOT NULL,
    id int,
    currentLocation char(50),
    PRIMARY KEY (id)
);
grant select on DeliveryPerson to public;

-- CREATE TABLE Vehicle(
--     model char(30),
--     number_plate char(20),
--     PRIMARY KEY (number_plate)
-- );
-- grant select on Vehicle to public;

CREATE TABLE Vehicle2(
    model char(30),
    make char(30),
    PRIMARY KEY (model)
);
grant select on Vehicle2 to public;

CREATE TABLE Vehicle1(
    number_plate char(10),
    model char(30),
    PRIMARY KEY (number_plate),
    FOREIGN KEY (model) REFERENCES Vehicle2 ON DELETE SET NULL
);
grant select on Vehicle1 to public;


CREATE TABLE Promotion(
    promotionID int,
    discountPercentage float NOT NULL,
    platform char(50) NOT NULL,
    PRIMARY KEY(promotionID),
    FOREIGN KEY (platform) REFERENCES Platform ON DELETE CASCADE
);
grant select on Promotion to public;


CREATE TABLE PlatformWidePromotion(
    redeemCode int,
    promotionID int,
    PRIMARY KEY(promotionID),
    FOREIGN KEY(promotionID) REFERENCES Promotion ON DELETE CASCADE
);
grant select on PlatformWidePromotion to public;


CREATE TABLE RestaurantSpecificDiscount(
    restaurantID int,
    promotionID int,
    PRIMARY KEY(promotionID),
    FOREIGN KEY(promotionID) REFERENCES Promotion ON DELETE CASCADE,
    FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE
);
grant select on PlatformWidePromotion to public;


CREATE TABLE UsesVehicle(
    deliveryPersonID int,
    number_plate char(10),
    PRIMARY KEY(deliveryPersonID, number_plate),
    FOREIGN KEY(deliveryPersonID) REFERENCES DeliveryPerson(id) ON DELETE CASCADE,
    FOREIGN KEY(number_plate) REFERENCES Vehicle1 ON DELETE CASCADE
);
grant select on UsesVehicle to public;


CREATE TABLE WorksFor(
    deliveryPersonID int,
    platform char(50),
    PRIMARY KEY(deliveryPersonID, platform),
    FOREIGN KEY(deliveryPersonID) REFERENCES DeliveryPerson(id) ON DELETE CASCADE,
    FOREIGN KEY(platform) REFERENCES Platform(platformName) ON DELETE CASCADE
);
grant select on WorksFor to public;


CREATE TABLE Hosts(
    platform char(50),
    restaurantID int,
    PRIMARY KEY(platform, restaurantID),
    FOREIGN KEY(platform) REFERENCES Platform(platformName) ON DELETE CASCADE,
    FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE
);
grant select on Hosts to public;



CREATE TABLE Offers(
    restaurantID int,
    promotionID int,
    PRIMARY KEY(restaurantID, promotionID),
    FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE,
    FOREIGN KEY(promotionID) REFERENCES Promotion ON DELETE CASCADE
);
grant select on Offers to public;

-- insert into Platform
-- values('Fantuan');
INSERT INTO Platform VALUES ('DoorDash');
INSERT INTO Platform VALUES ('UberEats');
INSERT INTO Platform VALUES ('SkipTheDishes');
INSERT INTO Platform VALUES ('Fantuan');
INSERT INTO Platform VALUES ('Grubhub');

-- insert into User_1
-- values('Yitong', '1', '10', 'Fantuan');
INSERT INTO User_1 VALUES ('foodlover2', 1001, 43.75, 'DoorDash');
INSERT INTO User_1 VALUES ('bluesweater', 1002, 2.54, 'UberEats');
INSERT INTO User_1 VALUES ('catperson', 1003, 20.00, 'DoorDash');
INSERT INTO User_1 VALUES ('sunflower34', 1004, 78.88, 'DoorDash');
INSERT INTO User_1 VALUES ('abcdef', 1005, 0.00, 'SkipTheDishes');
INSERT INTO User_1 VALUES ('paperkite', 1006, 13.99, 'UberEats');

-- insert into UserAddress
-- values('Student Union Blvd', 'Vancouver', 'Canada', 'Home', '1');
INSERT INTO UserAddress VALUES ('1 Apple Dr', 'Vancouver', 'Canada', 'delivery', 1001);
INSERT INTO UserAddress VALUES ('2 Banana St', 'Vancouver', 'Canada', 'billing', 1002);
INSERT INTO UserAddress VALUES ('3 Cherry Rd', 'Vancouver', 'Canada', 'delivery', 1003);
INSERT INTO UserAddress VALUES ('4 Donut Dr', 'Vancouver', 'Canada', 'delivery', 1004);
INSERT INTO UserAddress VALUES ('5 Eclair St', 'Vancouver', 'Canada', 'delivery', 1005);
INSERT INTO UserAddress VALUES ('6 Fig Rd', 'Vancouver', 'Canada', 'billing', 1005);
INSERT INTO UserAddress VALUES ('7 Grape Dr', 'Vancouver', 'Canada', 'delivery', 1006);

-- insert into Restaurant
-- values ('KFC', '1', '4', 'broad way', 'fast food');
INSERT INTO Restaurant VALUES ('McDonalds', 5001, 3, '15 Fries St', 'fast food');
INSERT INTO Restaurant VALUES ('Kung Fu Noodle', 5002, 4, '25 Panda St', 'restaurant');
INSERT INTO Restaurant VALUES ('Yunshang Rice Noodle', 5003, 5, '35 Noodle St', 'restaurant');
INSERT INTO Restaurant VALUES ('Nori', 5004, 5, '45 Seaweed St', 'restaurant');
INSERT INTO Restaurant VALUES ('Chatime', 5005, 5, '55 Tea St', 'bubble tea');

-- insert into FoodItem
-- values ('fried chicken wings', '1');
INSERT INTO FoodItem VALUES ('chicken wings',5001);
INSERT INTO FoodItem VALUES ('fries',5001);
INSERT INTO FoodItem VALUES ('hamburger',5001);
INSERT INTO FoodItem VALUES ('fried rice',5002);
INSERT INTO FoodItem VALUES ('chicken rice noodle',5003);

-- insert into DeliveryPerson
-- values ('Henry', '100', 'UBC aquatic center');
INSERT INTO DeliveryPerson VALUES ('John Doe', 4001, '14 Hello St');
INSERT INTO DeliveryPerson VALUES ('Jane Deer', 4002, '24 High St');
INSERT INTO DeliveryPerson VALUES ('Jeremy Don', 4003, '34 Hay Rd');
INSERT INTO DeliveryPerson VALUES ('Jack Donald', 4004, '44 Greetings Ln');
INSERT INTO DeliveryPerson VALUES ('Jill Dew', 4005, '54 Salutations St');

-- insert into Vehicle
-- values ('Honda', 'x86');
INSERT INTO Vehicle2 VALUES ('Elantra', 'Hyundai');
INSERT INTO Vehicle2 VALUES ('Everest', 'Ford');
INSERT INTO Vehicle2 VALUES ('Civic', 'Honda');
INSERT INTO Vehicle2 VALUES ('Camry', 'Toyota');
INSERT INTO Vehicle2 VALUES ('X5', 'BMW');

INSERT INTO Vehicle1 VALUES ('123 ABC', 'Elantra');
INSERT INTO Vehicle1 VALUES ('124 ABC', 'Everest');
INSERT INTO Vehicle1 VALUES ('125 ABC', 'Civic');
INSERT INTO Vehicle1 VALUES ('126 ABC', 'Camry');
INSERT INTO Vehicle1 VALUES ('127 ABC', 'X5');

-- insert into Promotion
-- values ('48743', '0.1', 'Fantuan');
INSERT INTO Promotion VALUES (2001, 0.15, 'DoorDash');
INSERT INTO Promotion VALUES (2002, 0.26, 'UberEats');
INSERT INTO Promotion VALUES (2003, 0.37, 'SkipTheDishes');
INSERT INTO Promotion VALUES (2004, 0.48, 'Fantuan');
INSERT INTO Promotion VALUES (2005, 0.59, 'Grubhub');
INSERT INTO Promotion VALUES (2006, 0.25, 'DoorDash');
INSERT INTO Promotion VALUES (2007, 0.36, 'UberEats');
INSERT INTO Promotion VALUES (2008, 0.47, 'SkipTheDishes');
INSERT INTO Promotion VALUES (2009, 0.58, 'Fantuan');
INSERT INTO Promotion VALUES (2010, 0.69, 'Grubhub');

INSERT INTO PlatformWidePromotion VALUES (111000, 2001);
INSERT INTO PlatformWidePromotion VALUES (222000, 2002);
INSERT INTO PlatformWidePromotion VALUES (333000, 2003);
INSERT INTO PlatformWidePromotion VALUES (444000, 2004);
INSERT INTO PlatformWidePromotion VALUES (555000, 2005);

-- insert into RestaurantSpecificDiscount
-- values ('1', '48743');
INSERT INTO RestaurantSpecificDiscount VALUES (5001, 2006);
INSERT INTO RestaurantSpecificDiscount VALUES (5002, 2007);
INSERT INTO RestaurantSpecificDiscount VALUES (5003, 2008);
INSERT INTO RestaurantSpecificDiscount VALUES (5004, 2009);
INSERT INTO RestaurantSpecificDiscount VALUES (5005, 2010);

-- insert into UsesVehicle
-- values ('100', 'x86');
INSERT INTO UsesVehicle VALUES (4001, '123 ABC');
INSERT INTO UsesVehicle VALUES (4002, '124 ABC');
INSERT INTO UsesVehicle VALUES (4003, '125 ABC');
INSERT INTO UsesVehicle VALUES (4004, '126 ABC');
INSERT INTO UsesVehicle VALUES (4005, '127 ABC');

-- insert into WorksFor
-- values ('100', 'Fantuan');
INSERT INTO WorksFor VALUES (4001, 'DoorDash');
INSERT INTO WorksFor VALUES (4002, 'UberEats');
INSERT INTO WorksFor VALUES (4003, 'DoorDash');
INSERT INTO WorksFor VALUES (4004, 'UberEats');
INSERT INTO WorksFor VALUES (4005, 'SkipTheDishes');

-- insert into Hosts
-- values ('Fantuan', '1');
INSERT INTO Hosts VALUES ('DoorDash', 5001);
INSERT INTO Hosts VALUES ('Fantuan', 5001);
INSERT INTO Hosts VALUES ('UberEats', 5001);
INSERT INTO Hosts VALUES ('SkipTheDishes', 5001);
INSERT INTO Hosts VALUES ('Grubhub', 5001);
INSERT INTO Hosts VALUES ('UberEats', 5002);
INSERT INTO Hosts VALUES ('Fantuan', 5003);
INSERT INTO Hosts VALUES ('Fantuan', 5004);
INSERT INTO Hosts VALUES ('Grubhub', 5005);

-- insert into Offers
-- values ('1', '48743');
INSERT INTO Offers VALUES (5001, 2006);
INSERT INTO Offers VALUES (5002, 2007);
INSERT INTO Offers VALUES (5003, 2008);
INSERT INTO Offers VALUES (5004, 2009);
INSERT INTO Offers VALUES (5005, 2010);