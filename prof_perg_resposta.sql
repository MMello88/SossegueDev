

/*Table structure for table `tbl_prof_pergunta_resposta_pedido` */

DROP TABLE IF EXISTS `tbl_prof_pergunta_resposta_pedido`;

CREATE TABLE `tbl_prof_pergunta_resposta_pedido` (  
  `id_prof_pergunta_resposta_pedido` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Resposta_prof_perguntas_pedido',
  `id_prof_pergunta_resposta` int(11) not null comment 'Prof_pergunta_resposta - é só histórico este campo',
  `id_orcamento` int(11) not null comment 'Orcamento',
  `id_pedido` int(11) null comment 'Pedido',
  `id_profissional` int(11) not null comment 'Profissional',
  `id_prof_subcateg` int(11) NOT NULL COMMENT 'Prof_subcategs',
  `id_prof_pergunta` int(11) NOT NULL COMMENT 'Prof_perguntas',
  `id_prof_enunciado` int(11) NOT NULL COMMENT 'Prof_enunciado',
  `id_subcategoria` INT(11) NOT NULL,
  `vlr_primeiro` decimal(17,2) DEFAULT NULL,
  `vlr_adicional` decimal(17,2) DEFAULT NULL,
  `vlr_porcent` decimal(10,0) DEFAULT NULL,
  `qntd` decimal(17,2) DEFAULT NULL,
  `checkbox` char(1) DEFAULT NULL COMMENT 's/n',
  `faz_servico` char(1) DEFAULT NULL COMMENT 's/n',
  `respondido` char(1) NOT NULL COMMENT 's/n',
  `sn_pula_proxima_pergunta` char(1) DEFAULT NULL,
  `sn_pula_categoria` char(1) DEFAULT NULL,
  `sn_vlr_visita` char(1) DEFAULT NULL,
  `sn_preco_por_qntde` char(1) DEFAULT NULL,
  `sn_qntd_por_add` char(1) DEFAULT NULL,
  `sinal` char(1) DEFAULT NULL COMMENT 'sinais < / > / =',
  `sn_vlr_sinal` char(1) DEFAULT NULL,
  `sn_vlr_sinal_todos_categ` char(1) DEFAULT NULL COMMENT 'descricao do servico',
  `ds_servico` varchar(255) DEFAULT NULL COMMENT 'descricao do servico',
  `ds_servico_remetente` varchar(255) DEFAULT NULL COMMENT 'descricao do servico vindo de para o ds_servico',
  `sn_vlr_sinal_off` char(1) DEFAULT NULL COMMENT 'pergunta que vai conter o sinal / porcentagem (se ouver) / e se é off para não mostrar na tela e executar',
  `valor_sinal_off` decimal(4,2) DEFAULT NULL COMMENT 'valor q vai aplicar automaticamente',
  `tipo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_prof_pergunta_resposta_pedido`),
  KEY `id_prof_subcateg` (`id_prof_subcateg`),
  KEY `id_prof_pergunta` (`id_prof_pergunta`),
  KEY `id_prof_enunciado` (`id_prof_enunciado`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_1` FOREIGN KEY (`id_prof_subcateg`) REFERENCES `tbl_prof_subcateg` (`id_prof_subcateg`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_2` FOREIGN KEY (`id_prof_pergunta`) REFERENCES `tbl_prof_pergunta` (`id_prof_pergunta`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_3` FOREIGN KEY (`id_prof_enunciado`) REFERENCES `tbl_prof_enunciado` (`id_prof_enunciado`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_4` FOREIGN KEY (`id_profissional`) REFERENCES `tbl_profissional` (`id_profissional`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_5` FOREIGN KEY (`id_subcategoria`) REFERENCES `tbl_subcategoria`(`id_subcategoria`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_6` FOREIGN KEY (`id_pedido`) REFERENCES `tbl_pedido`(`id_pedido`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_7` FOREIGN KEY (`id_orcamento`) REFERENCES `tbl_orcamento`(`id_orcamento`),
  CONSTRAINT `tbl_prof_pergunta_resposta_pedido_ibfk_8` FOREIGN KEY (`id_prof_pergunta_resposta`) REFERENCES `tbl_prof_pergunta_resposta`(`id_prof_pergunta_resposta`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Resposta das perguntas do pedido';

ALTER TABLE `sosse694_teste`.`tbl_pedido`   
  ADD COLUMN `id_subcategoria` INT(11) NULL AFTER `status`,
  ADD COLUMN `id_categoria_servico` INT(11) NULL AFTER `id_subcategoria`,
  ADD CONSTRAINT `FK_PEDIDO_SUBCATEGORIA` FOREIGN KEY (`id_subcategoria`) REFERENCES `sosse694_teste`.`tbl_subcategoria`(`id_subcategoria`),
  ADD CONSTRAINT `FK_PEDIDO_CATG_SERV` FOREIGN KEY (`id_categoria_servico`) REFERENCES `sosse694_teste`.`tbl_categoria_servico`(`id_categoria_servico`);

