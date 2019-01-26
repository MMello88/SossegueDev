ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sn_pula_proxima_pergunta`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sn_pula_categoria`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sn_vlr_visita`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sn_preco_por_qntde`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sn_qntd_por_add`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `valor_sinal_off`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sn_vlr_sinal_off`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `ds_servico_remetente`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `ds_servico`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sn_vlr_sinal_todos_categ`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  CHANGE `sinal` `sinal` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL  COMMENT 'sinais < / > / ='  AFTER `tem_faz_servico`,
  CHANGE `ordem` `ordem` DECIMAL(10,0) NOT NULL  AFTER `tipo`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_filtro`   
  DROP COLUMN `ds_filtro`, 
  ADD COLUMN `id_categoria` INT(11) NULL AFTER `id_prof_pergunta`,
  CHANGE `id_categoria_servico` `id_categoria_servico` INT(11) NULL  AFTER `id_categoria`,
  CHANGE `id_filtro` `id_filtro` INT(11) NULL  AFTER `id_servico`,
  CHANGE `tipo` `tipo` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL  COMMENT 'c - config_porc / v - valor_porc / o - outros'  AFTER `id_filtro`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_filtro`   
  ADD COLUMN `id_servico_origem` INT(11) NULL  COMMENT 'utilizar para saber a sua origem e salvar as resposta' AFTER `tipo`;

SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`   
  DROP COLUMN `id_prof_pergunta`, 
  DROP COLUMN `id_prof_enunciado`, 
  DROP COLUMN `sn_pula_categoria`, 
  DROP COLUMN `sn_vlr_visita`, 
  DROP COLUMN `sn_preco_por_qntde`, 
  DROP COLUMN `sn_qntd_por_add`, 
  DROP COLUMN `sinal`, 
  DROP COLUMN `sn_vlr_sinal`, 
  DROP COLUMN `sn_vlr_sinal_todos_categ`, 
  DROP COLUMN `ds_servico`, 
  DROP COLUMN `ds_servico_remetente`, 
  DROP COLUMN `sn_vlr_sinal_off`, 
  DROP COLUMN `valor_sinal_off`, 
  DROP COLUMN `tipo`, 
  ADD COLUMN `id_prof_pergunta` INT(11) NOT NULL AFTER `id_prof_pergunta_resposta`,
  ADD COLUMN `resp_perg_ini` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL  AFTER `id_prof_pergunta`,
  CHANGE `vlr_primeiro` `vlr_primeiro` DECIMAL(17,2) NULL  AFTER `resp_perg_ini`,
  CHANGE `qntd` `vlr_qntd` DECIMAL(17,2) NULL,
  CHANGE `faz_servico` `faz_servico` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL  AFTER `vlr_qntd`,
  CHANGE `respondido` `sinal` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL,
  CHANGE `sn_pula_proxima_pergunta` `vlr_sinal` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL, 
  DROP INDEX `id_prof_subcateg`,
  DROP INDEX `id_prof_pergunta`,
  DROP INDEX `id_prof_enunciado`,
  DROP FOREIGN KEY `tbl_prof_pergunta_resposta_ibfk_1`,
  DROP FOREIGN KEY `tbl_prof_pergunta_resposta_ibfk_2`,
  DROP FOREIGN KEY `tbl_prof_pergunta_resposta_ibfk_3`,
  ADD CONSTRAINT `fk_pergunta_pergunta_resposta` FOREIGN KEY (`id_prof_pergunta`) REFERENCES `sosse694_teste`.`tbl_prof_pergunta`(`id_prof_pergunta`);

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`  
  ADD CONSTRAINT `fk_prof_sub_pergunta` FOREIGN KEY (`id_prof_subcateg`) REFERENCES `sosse694_teste`.`tbl_prof_subcateg`(`id_prof_subcateg`);

SET FOREIGN_KEY_CHECKS=1;


ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta_pedido`   
  DROP COLUMN `vlr_primeiro`, 
  DROP COLUMN `vlr_adicional`, 
  DROP COLUMN `vlr_porcent`, 
  DROP COLUMN `qntd`, 
  DROP COLUMN `checkbox`, 
  DROP COLUMN `faz_servico`, 
  DROP COLUMN `respondido`, 
  DROP COLUMN `sn_pula_proxima_pergunta`, 
  DROP COLUMN `sn_pula_categoria`, 
  DROP COLUMN `sn_vlr_visita`, 
  DROP COLUMN `sn_preco_por_qntde`, 
  DROP COLUMN `sn_qntd_por_add`, 
  DROP COLUMN `sinal`, 
  DROP COLUMN `sn_vlr_sinal`, 
  DROP COLUMN `sn_vlr_sinal_todos_categ`, 
  DROP COLUMN `ds_servico`, 
  DROP COLUMN `ds_servico_remetente`, 
  DROP COLUMN `sn_vlr_sinal_off`, 
  DROP COLUMN `valor_sinal_off`, 
  DROP COLUMN `tipo`;


ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`   
  DROP COLUMN `resp_perg_ini`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `perg_ini`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta`   
  DROP COLUMN `sinal`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`   
  ADD COLUMN `tipo` VARCHAR(100) NULL AFTER `vlr_sinal`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`   
  DROP COLUMN `sinal`;

ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`   
  CHANGE `faz_servico` `vlr_faz_servico` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL,
  CHANGE `tipo` `vlr_tipo` VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci NULL,
  CHANGE `checkbox` `vlr_checkbox` CHAR(1) CHARSET utf8 COLLATE utf8_general_ci NULL;


ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`   
  CHANGE `vlr_checkbox` `vlr_checkbox` CHAR(2) CHARSET utf8 COLLATE utf8_general_ci NULL;


ALTER TABLE `sosse694_teste`.`tbl_prof_pergunta_resposta`   
  CHANGE `vlr_faz_servico` `vlr_faz_servico` CHAR(2) CHARSET utf8 COLLATE utf8_general_ci NULL,
  CHANGE `vlr_sinal` `vlr_sinal` CHAR(2) CHARSET utf8 COLLATE utf8_general_ci NULL;