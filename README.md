Este projeto tem como objetivo permitir o cadastro, edição, listagem e exclusão de orçamentos de uma oficina, utilizando PHP puro e MySQL.

Funcionalidades
Cadastro de novos orçamentos
Edição de orçamentos existentes
Exclusão de orçamentos
Filtro/pesquisa por cliente, vendedor e período
Validação de dados no front-end (HTML) e back-end (PHP)

Script de criaçao da tabela no MySQL:
CREATE TABLE `orcamento`.`orcamento_estoque` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cliente` VARCHAR(100) NOT NULL,
  `dta_hora_orcamento` DATETIME NOT NULL,
  `vendedor` VARCHAR(100) NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `valor_orcado` DECIMAL(18,2) NOT NULL,
  PRIMARY KEY (`id`));

Configure o arquivo de conexão com o banco (conexao_DB.php) com os dados corretos da sua máquina (host, usuário, senha, nome do banco).

O sistema foi desenvolvido de forma simples, sem uso de frameworks.

As validações básicas estão implementadas tanto no HTML (campos obrigatórios) quanto no PHP (tipo de dado e segurança mínima).
