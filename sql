
CREATE TABLE users (id INT PRIMARY KEY AUTO_INCREMENT, login VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, status_register BOOL DEFAULT 0, UNIQUE(login, email));

//сайт репетиторов
CREATE TABLE `teachorg`.`teacher` ( `teacher_id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NULL , `surname` VARCHAR(255) NULL , `password` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `token` VARCHAR(32) NOT NULL , `inn` VARCHAR(12) NULL , `active` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`teacher_id`), UNIQUE (`email`), UNIQUE (`inn`)) ENGINE = InnoDB;