<?php
/**
 * config.php
 * Conexão com o banco de leads da landing page (leads_app_barber).
 *
 * Este banco é separado do banco da aplicação BarbERP (cada barbearia
 * cliente tem o seu próprio, ex: "alexbarber") — aqui só ficam os
 * contatos que preenchem o formulário de teste gratuito desta landing
 * page, num banco compartilhado no mesmo servidor.
 *
 * Credenciais vêm de variáveis de ambiente, configuradas no painel do
 * serviço onde esta landing page for hospedada (EasyPanel ou similar).
 * Não existe usuário/senha fixo no código — se as variáveis não forem
 * definidas, a conexão falha de forma explícita (ver tratamento abaixo)
 * em vez de tentar credenciais adivinhadas.
 *
 * Variáveis de ambiente esperadas:
 *   DB_HOST  (padrão: comandai_barbearia-padrao-bd)
 *   DB_PORT  (padrão: 3306)
 *   DB_NAME  (padrão: leads_app_barber)
 *   DB_USER  (obrigatório — sem padrão)
 *   DB_PASS  (obrigatório — sem padrão)
 */

$DB_HOST = getenv('DB_HOST') ?: 'comandai_barbearia-padrao-bd';
$DB_PORT = getenv('DB_PORT') ?: '3306';
$DB_NAME = getenv('DB_NAME') ?: 'leads_app_barber';
$DB_USER = getenv('DB_USER') ?: null;
$DB_PASS = getenv('DB_PASS') ?: null;

/**
 * Abre (ou reaproveita) a conexão PDO com o banco de leads.
 * Lança RuntimeException com mensagem clara se as credenciais não
 * estiverem configuradas, para facilitar o diagnóstico no primeiro
 * deploy — sem nunca ecoar usuário/senha em nenhuma mensagem.
 */
function conectarBancoLeads(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    global $DB_HOST, $DB_PORT, $DB_NAME, $DB_USER, $DB_PASS;

    if (!$DB_USER || !$DB_PASS) {
        throw new RuntimeException(
            'Conexão com o banco de leads não configurada: defina as variáveis ' .
            'de ambiente DB_USER e DB_PASS no serviço onde esta landing page ' .
            'está hospedada.'
        );
    }

    $pdo = new PDO(
        "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );

    return $pdo;
}
