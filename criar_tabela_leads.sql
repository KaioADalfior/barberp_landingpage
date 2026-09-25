-- criar_tabela_leads.sql
-- Cria a tabela que recebe os contatos do formulário de teste gratuito
-- da landing page do BarbERP.
--
-- Rode este script uma vez no banco "leads_app_barber"
-- (via phpMyAdmin: selecione o banco leads_app_barber -> aba SQL -> cole
-- e execute este arquivo).

USE leads_app_barber;

CREATE TABLE IF NOT EXISTS `Lead` (
    idLead          INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo   VARCHAR(150) NOT NULL,
    telefone        VARCHAR(20)  NOT NULL,
    email           VARCHAR(150) NOT NULL,
    descricao       VARCHAR(1000) NULL,
    plano_interesse VARCHAR(80)  NULL,
    origem          VARCHAR(80)  NOT NULL DEFAULT 'landing-barberp',
    criado_em       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);
