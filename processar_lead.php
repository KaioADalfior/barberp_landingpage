<?php
/**
 * processar_lead.php
 * Recebe o envio do formulário "Teste gratuito" da landing page e grava
 * o contato na tabela Lead do banco leads_app_barber (ver config.php e
 * criar_tabela_leads.sql).
 *
 * Chamado via fetch() por assets/js/form.js, POST com JSON:
 *   { nome_completo, telefone, email, descricao, plano_interesse, empresa }
 * Responde sempre em JSON: { ok: bool, erro?: string }
 *
 * "empresa" é um campo honeypot (ver form.js) — fica escondido no
 * formulário; só um robô preenche. Se vier com conteúdo, a submissão é
 * silenciosamente descartada como sucesso, sem gravar nada.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';

function responder(bool $ok, ?string $erro = null): never
{
    http_response_code($ok ? 200 : 400);
    echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'erro' => $erro], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'erro' => 'Método não permitido.']);
    exit;
}

$corpo  = file_get_contents('php://input');
$dados  = json_decode($corpo, true);
if (!is_array($dados)) {
    // Também aceita envio como formulário tradicional (fallback sem JS)
    $dados = $_POST;
}

// Honeypot: campo que deve chegar sempre vazio. Preenchido = robô.
$honeypot = trim((string) ($dados['empresa'] ?? ''));
if ($honeypot !== '') {
    responder(true); // finge sucesso, não grava nada
}

$nomeCompleto   = trim((string) ($dados['nome_completo'] ?? ''));
$telefone       = trim((string) ($dados['telefone'] ?? ''));
$email          = trim((string) ($dados['email'] ?? ''));
$descricao      = trim((string) ($dados['descricao'] ?? ''));
$planoInteresse = trim((string) ($dados['plano_interesse'] ?? ''));

if ($nomeCompleto === '' || mb_strlen($nomeCompleto) < 3) {
    responder(false, 'Informe seu nome completo.');
}

// Telefone: exige ao menos 10 dígitos (DDD + número), ignorando
// formatação como parênteses, espaço e hífen.
$telefoneDigitos = preg_replace('/\D+/', '', $telefone);
if ($telefoneDigitos === null || strlen($telefoneDigitos) < 10 || strlen($telefoneDigitos) > 11) {
    responder(false, 'Informe um telefone válido com DDD.');
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responder(false, 'Informe um e-mail válido.');
}

if (mb_strlen($nomeCompleto) > 150) {
    $nomeCompleto = mb_substr($nomeCompleto, 0, 150);
}
if (mb_strlen($email) > 150) {
    responder(false, 'E-mail muito longo.');
}
if (mb_strlen($descricao) > 1000) {
    $descricao = mb_substr($descricao, 0, 1000);
}
if (mb_strlen($planoInteresse) > 80) {
    $planoInteresse = mb_substr($planoInteresse, 0, 80);
}

try {
    $pdo = conectarBancoLeads();
} catch (Throwable $e) {
    error_log('[BarbERP Landing] Falha ao conectar no banco de leads: ' . $e->getMessage());
    responder(false, 'Não foi possível registrar sua solicitação agora. Tente novamente em instantes.');
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO `Lead` (nome_completo, telefone, email, descricao, plano_interesse)
         VALUES (:nome_completo, :telefone, :email, :descricao, :plano_interesse)'
    );
    $stmt->execute([
        'nome_completo'   => $nomeCompleto,
        'telefone'        => $telefone,
        'email'           => $email,
        'descricao'       => $descricao !== '' ? $descricao : null,
        'plano_interesse' => $planoInteresse !== '' ? $planoInteresse : null,
    ]);
} catch (Throwable $e) {
    error_log('[BarbERP Landing] Falha ao gravar lead: ' . $e->getMessage());
    responder(false, 'Não foi possível registrar sua solicitação agora. Tente novamente em instantes.');
}

responder(true);