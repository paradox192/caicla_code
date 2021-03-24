CREATE TABLE `container` (
  `Container_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Size` varchar(2) NOT NULL,
  `Description` varchar(100) NOT NULL,
  PRIMARY KEY (`Container_Id`)
);

CREATE TABLE `customers` (
  `Customer_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Customer_Name` varchar(100) NOT NULL,
  `Address` varchar(100) NOT NULL
  PRIMARY KEY (`Customer_Id`)
);

CREATE TABLE `function` (
  `Function_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Function_Name` varchar(25) NOT NULL,
  `Description` text NOT NULL
  PRIMARY KEY (`Fun_Id`)
);

CREATE TABLE `Weight_History` (
  `Customer_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Material_Name` int(11) NOT NULL,
  `Weight` int(11) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY(`Customer_Id`,`Material_Name`)
);

CREATE TABLE `material` (
  `Material_Name` varchar(25) NOT NULL,
  `Description` varchar(100) NOT NULL,
  PRIMARY KEY(`Material_Name`)
);

CREATE TABLE `smart_station` (
  `Station_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Customer_Id` int(11) NOT NULL,
  `Station_Name` varchar(25) NOT NULL,
  `Position_Description` varchar(100),
  PRIMARY KEY(Station_Id)
);

CREATE TABLE `quiz_toplist` (
  `Toplist_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Toplist_Name` varchar(50) NOT NULL,
  `Score` int(11) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY(`Toplist_Id`)
);

INSERT INTO `container` (`Container_Id`, `Size`, `Description`) VALUES
(NULL, 'L', 'Big'),
(NULL, 'M', 'Medium'),
(NULL, 'S', 'Small');

INSERT INTO `customers` (`Customer_Id`, `Customer_Name`, `Address`) VALUES
(NULL, 'Galactic Empire', 'Death Star'),
(NULL, 'Rebel Alliance', 'Echo Base'),
(NULL, 'Rebel Alliance', 'Base One'),
(NULL, 'Jedi Order', 'Jedi Temple');

INSERT INTO `function` (`Function_Id`, `Function_Name`, `Description`) VALUES
(NULL, 'Komprimering', 'Minskar Luft i Kärlen');

INSERT INTO `material` (`Material_Name`, `Description`) VALUES
('Papper','Det är papper'),
('Metall','Det är metall'),
('Plast','Det är plast'),
('Tidningar','Det är Tidningar'),
('O_Glas','Det är ofärgat glass'),
('F_Glas','Det är färgat glass');

INSERT INTO `smart_station` (`Station_Id`, `Customer_Id`, `Station_Name`, `Position_Description`) VALUES
(NULL, 1, 'Death Station', 'Kitchen'),
(NULL, 2, 'Base Station', 'Kitchen'),
(NULL, 3, 'One Station', 'Kitchen');