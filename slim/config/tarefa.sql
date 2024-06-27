CREATE SCHEMA IF NOT EXISTS `tarefa` DEFAULT CHARACTER SET utf8 ;
USE `tarefa` ;


CREATE TABLE IF NOT EXISTS `tarefa`.`usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`));


CREATE TABLE IF NOT EXISTS `tarefa`.`carro` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(500) NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_carro_usuario_idx` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `fk_carro_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `tarefa`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


INSERT INTO `tarefa`.`usuario` (`nome`) VALUES ('Rafael');


INSERT INTO `tarefa`.`carro` (`nome`, `usuario_id`) VALUES ('Kwid', '1');