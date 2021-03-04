CREATE DATABASE thomas_adams_dw;

use thomas_adams_dw;

CREATE TABLE users(
	uid INT NOT NULL AUTO_INCREMENT,
	firstname VARCHAR(55) NOT NULL,
	surname VARCHAR(55) NOT NULL,
	username VARCHAR(55) NOT NULL UNIQUE,
	email VARCHAR(255) NOT NULL UNIQUE,
	password VARCHAR(200) NOT NULL,
	dob DATE NOT NULL, 
	postal_addr VARCHAR(255),
	PRIMARY KEY(uid)
);

CREATE TABLE faq(
	faqid INT NOT NULL AUTO_INCREMENT,
	question VARCHAR(155),
	response TEXT NULL,
	PRIMARY KEY(faqid)
);