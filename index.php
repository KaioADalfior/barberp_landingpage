<?php
/**
 * index.php
 * Landing page comercial do BarbERP (produto da DAK Soluções Digitais) —
 * versão 3.0 (reformulação), apresentando exclusivamente a V1.0 real do
 * sistema: nenhum recurso listado aqui é promessa futura, exceto o plano
 * Enterprise e a seção "Próximas atualizações", ambos claramente marcados
 * como ainda não disponíveis.
 *
 * Projeto standalone — NÃO faz parte do sistema BarbERP em si (que fica em
 * outro repositório/hospedagem). Esta página só existe para apresentar o
 * produto e captar leads pelo formulário "Teste gratuito", que grava no
 * banco leads_app_barber através de processar_lead.php (ver config.php).
 *
 * Capturas reais do sistema ficam em assets/images/barberp/. Cada seção usa
 * fotoReal() com uma lista de nomes aceitos — quando um arquivo ainda não
 * existe, um mockup simples em CSS entra no lugar automaticamente (ver
 * seção "Sistema"), sem nenhum aviso de "em breve" na tela.
 *
 * NOTA (reformulação em andamento): a especificação mais recente pede um
 * carrossel grande com lightbox e 10 capturas reais em nomes de arquivo
 * exatos (dashboardprincipal.png, comoagendar.png, agendar.png,
 * alteraragendamento.png, finalizarservico.png, receberfiado.png,
 * dashfinanceiro.png, cliente01.png, cliente02.png, cliente03.png) e uma
 * nova imagem de hero (agenda.png). Nenhum desses 11 arquivos existe hoje
 * em assets/images/barberp/ — por instrução explícita, essa parte NÃO foi
 * implementada para não inventar imagens/telas que não existem. A seção
 * "Sistema" abaixo continua usando o mesmo mecanismo fotoReal() com as 4
 * capturas reais já disponíveis; assim que os 11 arquivos forem
 * adicionados com os nomes exatos, o carrossel/lightbox pode ser
 * implementado por cima dessa mesma base sem quebrar nada.
 */

declare(strict_types=1);

$anoAtual = date('Y');

/* -------------------------------------------------------------------------
 * Dados das seções
 * ---------------------------------------------------------------------- */

$recursos = [
    ['titulo' => 'Agenda organizada', 'desc' => 'Tenha os horários dos barbeiros organizados em um só lugar.', 'icon' => 'calendar'],
    ['titulo' => 'Controle de fiados', 'desc' => 'Saiba quem está devendo, quanto deve e quando recebeu.', 'icon' => 'wallet'],
    ['titulo' => 'Financeiro', 'desc' => 'Acompanhe entradas, saídas e valores a receber.', 'icon' => 'chart'],
    ['titulo' => 'Clientes', 'desc' => 'Mantenha os dados e o histórico dos seus clientes sempre à mão.', 'icon' => 'users'],
];

/* Três motivos para organizar a barbearia agora — sem estatísticas nem
 * percentuais inventados, só argumentos ligados a recursos reais da V1.0. */
$motivos = [
    [
        'num'   => '01',
        'titulo' => 'Seus clientes já esperam comodidade',
        'desc'  => 'Marcar horário por mensagem, esperar resposta e confirmar manualmente toma tempo seu e do cliente. Com o agendamento online, ele escolhe o serviço, o profissional e o horário sozinho, quando quiser.',
    ],
    [
        'num'   => '02',
        'titulo' => 'O WhatsApp não foi feito para gerenciar uma barbearia',
        'desc'  => 'Conversas se perdem, horários ficam espalhados entre contatos e é fácil esquecer um fiado ou um agendamento. Um sistema dedicado mantém tudo organizado em um só lugar.',
    ],
    [
        'num'   => '03',
        'titulo' => 'Organização gera previsibilidade',
        'desc'  => 'Saber quem vai ser atendido, quanto está por receber e como está o financeiro do mês evita surpresas e facilita decisões no dia a dia da barbearia.',
    ],
];

$planos = [
    [
        'nome'  => 'Básico',
        'preco' => '68,90',
        'desc'  => 'Para barbearias que querem organizar a gestão.',
        'itens' => ['Clientes', 'Serviços', 'Barbeiros', 'Agendamentos', 'Agendamentos recorrentes', 'Controle de fiados', 'Financeiro', 'Dashboard', 'Perfis', 'Configurações'],
        'destaque' => false,
    ],
    [
        'nome'  => 'Pro',
        'preco' => '98,90',
        'desc'  => 'Para quem também quer permitir que os clientes agendem sozinhos.',
        'itens' => ['Tudo do plano Básico', 'Agendamento online', 'Link público de agendamento'],
        'destaque' => true,
        'badge'    => 'Recomendado',
    ],
    [
        'nome'  => 'Enterprise',
        'preco' => '129,90',
        'desc'  => 'Para barbearias maiores, com recursos avançados ainda em desenvolvimento.',
        'itens' => ['Tudo do plano Pro', 'Gestão via WhatsApp', 'Lembretes automáticos'],
        'destaque' => false,
        'badge'    => 'Em desenvolvimento',
        'emDesenvolvimento' => true,
    ],
];

/* Comparativo — cada recurso aceita true, false ou 'dev' (ainda em
 * desenvolvimento, só no Enterprise, nunca apresentado como disponível). */
$compareFeatures = [
    ['label' => 'Clientes', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Serviços', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Barbeiros', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Agenda', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Agendamentos recorrentes', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Controle de fiados', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Financeiro', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Dashboard', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Agendamento online', 'basico' => false, 'pro' => true, 'enterprise' => true],
    ['label' => 'WhatsApp', 'basico' => false, 'pro' => false, 'enterprise' => 'dev'],
    ['label' => 'Lembretes', 'basico' => false, 'pro' => false, 'enterprise' => 'dev'],
];

/* Próximas atualizações — recursos ainda NÃO disponíveis na V1.0. Não
 * pertencem a nenhum plano, não têm preço e não devem ser confundidos com
 * os recursos reais acima. */
$atualizacoesFuturas = [
    ['titulo' => 'Integração com WhatsApp', 'desc' => 'Comunicação com clientes direto pelo WhatsApp.', 'status' => 'Em desenvolvimento', 'icon' => 'whatsapp'],
    ['titulo' => 'Lembretes automáticos', 'desc' => 'Avisos automáticos de agendamentos para os clientes.', 'status' => 'Em desenvolvimento', 'icon' => 'bell'],
    ['titulo' => 'Controle de estoque', 'desc' => 'Produtos, entradas, saídas e movimentações.', 'status' => 'Planejado', 'icon' => 'package'],
    ['titulo' => 'Controle de comissões', 'desc' => 'Comissões e desempenho por barbeiro.', 'status' => 'Planejado', 'icon' => 'percent'],
    ['titulo' => 'Relatórios avançados', 'desc' => 'Indicadores e análises mais detalhadas.', 'status' => 'Planejado', 'icon' => 'bars'],
];

$faqs = [
    ['p' => 'Preciso instalar algum programa?', 'r' => 'Não. O BarbERP funciona direto do navegador, sem instalação.'],
    ['p' => 'Posso acessar pelo celular?', 'r' => 'Sim. O BarbERP funciona no computador, notebook, tablet ou celular, direto pelo navegador.'],
    ['p' => 'Meu cliente pode agendar sozinho?', 'r' => 'Sim, no plano Pro, com o agendamento online e o link público de agendamento.'],
    ['p' => 'Posso controlar fiados?', 'r' => 'Sim. Você registra os valores pendentes e acompanha os pagamentos, parciais ou totais.'],
    ['p' => 'O período gratuito tem algum custo?', 'r' => 'Não. São 30 dias grátis, sem necessidade de cartão de crédito.'],
    ['p' => 'Como eu começo?', 'r' => 'Preencha o formulário de teste gratuito nesta página. Nossa equipe entra em contato para liberar seu acesso.'],
];

/* -------------------------------------------------------------------------
 * Fotos reais do sistema — aceita mais de um nome de arquivo possível para
 * a mesma foto, assim um arquivo salvo com o nome "errado" continua
 * funcionando em vez de cair silenciosamente no mockup em CSS.
 * ---------------------------------------------------------------------- */
function fotoReal(array $nomesPossiveis): ?string
{
    foreach ($nomesPossiveis as $nome) {
        $caminhoRelativo = 'assets/images/barberp/' . $nome;
        if (file_exists(__DIR__ . '/' . $caminhoRelativo)) {
            return $caminhoRelativo;
        }
    }
    return null;
}

/* -------------------------------------------------------------------------
 * Cache-busting automático para CSS/JS — acrescenta ?v=<data de modificação
 * do arquivo> em cada link, para que um deploy novo nunca fique "preso" em
 * cache de CSS/JS do navegador do visitante.
 * ---------------------------------------------------------------------- */
function versaoAsset(string $caminhoRelativo): string
{
    $caminhoAbsoluto = __DIR__ . '/' . $caminhoRelativo;
    $versao = file_exists($caminhoAbsoluto) ? (string) filemtime($caminhoAbsoluto) : '1';
    return '/' . $caminhoRelativo . '?v=' . $versao;
}

/* -------------------------------------------------------------------------
 * Ícones — SVGs simples, inline, sem dependências externas.
 * ---------------------------------------------------------------------- */
function icon(string $nome, string $classe = ''): string
{
    $atributos = 'width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
        . 'stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';
    if ($classe !== '') {
        $atributos .= ' class="' . htmlspecialchars($classe, ENT_QUOTES) . '"';
    }

    $paths = [
        'users'       => '<circle cx="9" cy="8" r="3.2"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><circle cx="17" cy="9" r="2.6"/><path d="M15.8 14.2c2.6.5 4.2 2.4 4.2 5.3"/>',
        'calendar'    => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.4"/><path d="M3.5 9.8h17M8 3v3.5M16 3v3.5"/>',
        'wallet'      => '<rect x="3" y="6.5" width="18" height="12.5" rx="2.2"/><path d="M3 10.5h18"/><circle cx="16.5" cy="14.5" r="1.2"/>',
        'chart'       => '<path d="M4 20V10M11 20V4M18 20v-7"/><path d="M2.5 20h19"/>',
        'check'       => '<path d="M5 12.5 9.5 17 19 7.5"/>',
        'x'           => '<path d="M6 6l12 12M18 6 6 18"/>',
        'shield'      => '<path d="M12 3.5 19.5 7v5.3c0 4.4-3.1 7.6-7.5 8.7-4.4-1.1-7.5-4.3-7.5-8.7V7L12 3.5Z"/><path d="m9 12 2.2 2.2L15.5 10"/>',
        'plus'        => '<path d="M12 5v14M5 12h14"/>',
        'minus'       => '<path d="M5 12h14"/>',
        'instagram'   => '<rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.3" cy="6.7" r="0.6" fill="currentColor" stroke="none"/>',
        'whatsapp'    => '<path d="M20.5 11.5a8.5 8.5 0 0 1-12.4 7.5L4 20l1.1-4a8.5 8.5 0 1 1 15.4-4.5Z"/><path d="M9 9.2c.2-.6.5-.6.8-.6.2 0 .4 0 .6.4.2.5.6 1.4.6 1.6 0 .1 0 .3-.1.4-.2.3-.4.5-.6.7-.1.2-.3.3 0 .6.4.6 1 1.2 1.6 1.6.6.4 1 .6 1.3.7.2.1.4.1.5-.1.2-.2.6-.7.8-.9.2-.2.3-.2.5-.1.2.1 1.4.6 1.6.8.2.1.3.2.3.3 0 .2 0 .7-.3 1.1-.3.4-1.2.8-1.7.8s-1.4-.1-2.9-1c-2.2-1.3-3.6-3.5-3.7-3.7-.1-.2-.9-1.2-.9-2.1 0-.9.5-1.3.7-1.5Z"/>',
        'package'     => '<path d="M3.5 7.5 12 3l8.5 4.5V16.5L12 21l-8.5-4.5Z"/><path d="M3.8 7.7 12 12l8.2-4.3M12 12v9"/>',
        'percent'     => '<path d="M6 18 18 6"/><circle cx="7.5" cy="7.5" r="2"/><circle cx="16.5" cy="16.5" r="2"/>',
        'bag'         => '<path d="M5 8.5h14l-1 11.5H6L5 8.5Z"/><path d="M8.5 8.5v-2a3.5 3.5 0 0 1 7 0v2"/>',
        'bars'        => '<rect x="4" y="12" width="3.6" height="8" rx="0.8"/><rect x="10.2" y="7" width="3.6" height="13" rx="0.8"/><rect x="16.4" y="3.5" width="3.6" height="16.5" rx="0.8"/>',
        'heart'       => '<path d="M12 20.2S4 15.4 4 9.8a4.3 4.3 0 0 1 8-2.1A4.3 4.3 0 0 1 20 9.8c0 5.6-8 10.4-8 10.4Z"/>',
        'building'    => '<rect x="4" y="3.5" width="10" height="17" rx="1.2"/><rect x="14" y="9.5" width="6" height="11" rx="1.2"/><path d="M7 7.5h1.5M7 11h1.5M7 14.5h1.5"/>',
        'bell'        => '<path d="M12 3.5c-3 0-4.6 2.3-4.6 5.4v2.6c0 .9-.3 1.7-.9 2.4l-.8 1h12.6l-.8-1c-.6-.7-.9-1.5-.9-2.4V8.9c0-3.1-1.6-5.4-4.6-5.4Z"/><path d="M9.8 19a2.2 2.2 0 0 0 4.4 0"/>',
        'globe'       => '<circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.2 2.3 3.4 5.3 3.4 8.5s-1.2 6.2-3.4 8.5c-2.2-2.3-3.4-5.3-3.4-8.5S9.8 5.8 12 3.5Z"/>',
        'quote'       => '<path d="M7.5 9.5c-1.7 0-3 1.3-3 3v2.5c0 1.1.9 2 2 2h2.5v-4.5h-2v-.5c0-1.1.9-2 2-2V9.5h-1.5Z"/><path d="M16 9.5c-1.7 0-3 1.3-3 3v2.5c0 1.1.9 2 2 2h2.5v-4.5h-2v-.5c0-1.1.9-2 2-2V9.5H16Z"/>',
    ];

    $miolo = $paths[$nome] ?? $paths['check'];

    return "<svg {$atributos}>{$miolo}</svg>";
}

/* Fotos usadas nas seções abaixo */
$fotoDashboard   = fotoReal(['dashboard.png', 'dashboard_principal.png']);
$fotoAgenda      = fotoReal(['agendamentos.png', 'agenda.png']);
$fotoFinanceiro  = fotoReal(['financeiro.png', 'dashboard_fin.png']);
$fotoFiados      = fotoReal(['fiados.png', 'receber_fiado.png']);
$fotoAgendaLink  = fotoReal(['agenda-online01.png', 'agenda_link01.png', 'agendar_link01.png']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BarbERP | Gestão simples para barbearias</title>
<meta name="description" content="Agenda, clientes, fiados e financeiro da sua barbearia em um só lugar. Conheça o BarbERP e teste grátis por 30 dias.">
<meta name="theme-color" content="#ffffff">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%232554E8'/%3E%3Ctext x='12' y='17' font-size='13' font-family='Arial,Helvetica,sans-serif' font-weight='700' fill='white' text-anchor='middle'%3EB%3C/text%3E%3C/svg%3E">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:locale" content="pt_BR">
<meta property="og:title" content="BarbERP | Gestão simples para barbearias">
<meta property="og:description" content="Agenda, clientes, fiados e financeiro em um só lugar. Teste o BarbERP grátis por 30 dias.">
<meta property="og:site_name" content="BarbERP">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">

<link rel="preconnect" href="/">
<link rel="stylesheet" href="<?= htmlspecialchars(versaoAsset('assets/css/style.css')) ?>">

<!-- Sem JavaScript: menu mobile fica sempre visível, e o FAQ mostra todas
     as respostas abertas, sem depender de clique para revelar. -->
<noscript>
<style>
    .hamburger { display: none; }
    .header__cta { display: inline-flex; }
    .mobile-nav {
        position: static;
        inset: auto;
        transform: none;
        visibility: visible;
        opacity: 1;
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-4);
        align-items: center;
        padding: var(--space-4) var(--space-5);
        background: var(--bg-subtle);
        border-bottom: 1px solid var(--border);
    }
    .mobile-nav__link { border-bottom: none; padding: var(--space-2); font-size: var(--fs-sm); }
    .mobile-nav .btn { margin-top: 0; width: auto; }
    .faq-item__panel { height: auto !important; }
</style>
</noscript>
</head>
<body>

<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

<!-- ======================= HEADER ======================= -->
<header class="header" id="topo" data-header>
    <div class="container header__inner">
        <a href="#inicio" class="logo" aria-label="BarbERP, página inicial">
            <span class="logo__mark">Barb<span>ERP</span></span>
            <span class="logo__by">by DAK Soluções Digitais</span>
        </a>

        <nav class="nav" aria-label="Navegação principal">
            <ul class="nav__list">
                <li><a class="nav__link" href="#recursos">Recursos</a></li>
                <li><a class="nav__link" href="#planos">Planos</a></li>
                <li><a class="nav__link" href="#faq">FAQ</a></li>
            </ul>
        </nav>

        <div class="header__actions">
            <a href="#teste-gratuito" class="btn btn--primary header__cta" data-cta-plano="">Testar grátis</a>
            <button type="button" class="hamburger" data-menu-toggle aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-nav">
                <span class="hamburger__bar"></span>
                <span class="hamburger__bar"></span>
                <span class="hamburger__bar"></span>
            </button>
        </div>
    </div>
</header>

<nav id="mobile-nav" class="mobile-nav" aria-label="Navegação mobile" data-mobile-nav>
    <a class="mobile-nav__link" href="#recursos" data-menu-link>Recursos</a>
    <a class="mobile-nav__link" href="#planos" data-menu-link>Planos</a>
    <a class="mobile-nav__link" href="#faq" data-menu-link>FAQ</a>
    <a href="#teste-gratuito" class="btn btn--primary btn--block" data-menu-link data-cta-plano="">Testar grátis</a>
</nav>

<main id="conteudo">

    <!-- ======================= HERO ======================= -->
    <section class="hero" id="inicio">
        <div class="container hero__grid">
            <div class="hero__content reveal">
                <span class="badge badge--blue hero__badge">BarbERP 1.0</span>
                <h1 class="hero__title">A gestão da sua barbearia, simples e organizada.</h1>
                <p class="hero__subtitle">Agenda, clientes, fiados e financeiro em um só lugar.</p>
                <div class="hero__actions">
                    <a href="#teste-gratuito" class="btn btn--primary btn--lg" data-cta-plano="">Testar grátis</a>
                    <a href="#recursos" class="btn btn--secondary btn--lg">Conhecer o sistema</a>
                </div>
                <div class="hero__meta">
                    <span class="hero__meta-item"><?= icon('check') ?> Sem instalação</span>
                    <span class="hero__meta-item"><?= icon('check') ?> Acesso pelo navegador</span>
                    <span class="hero__meta-item"><?= icon('check') ?> 30 dias grátis</span>
                </div>
            </div>

            <div class="hero__visual reveal">
                <?php if ($fotoDashboard !== null): ?>
                <div class="browser-frame">
                    <div class="browser-frame__bar">
                        <span class="browser-frame__dot"></span>
                        <span class="browser-frame__dot"></span>
                        <span class="browser-frame__dot"></span>
                    </div>
                    <div class="browser-frame__body">
                        <img src="/<?= htmlspecialchars($fotoDashboard) ?>" alt="Dashboard do BarbERP com indicadores de atendimentos, faturamento e agenda" loading="eager" decoding="async">
                    </div>
                </div>
                <?php else: ?>
                <div class="browser-frame">
                    <div class="browser-frame__bar">
                        <span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span>
                    </div>
                    <div class="browser-frame__body agenda-mock" style="padding:28px 22px;">
                        <div class="agenda-mock__head"><span class="agenda-mock__title">Painel do dia</span><span class="agenda-mock__sub">Hoje</span></div>
                        <div class="agenda-mock__cols">
                            <span></span><span class="agenda-mock__colhead">Atendimentos</span><span class="agenda-mock__colhead">Faturamento</span>
                            <span class="agenda-mock__time"></span>
                            <span class="agenda-mock__cell agenda-mock__cell--blue"></span><span class="agenda-mock__cell agenda-mock__cell--green"></span>
                            <span class="agenda-mock__time"></span>
                            <span class="agenda-mock__cell"></span><span class="agenda-mock__cell"></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ======================= FRASE DE IMPACTO ======================= -->
    <section class="section section--tight impact">
        <div class="container">
            <div class="impact__box reveal">
                <h2 class="impact__title">Pare de administrar sua barbearia no improviso.</h2>
                <p class="impact__text">Tenha agenda, clientes, fiados e financeiro organizados em um só lugar, sem depender de anotações espalhadas ou conversas perdidas.</p>
            </div>
        </div>
    </section>

    <!-- ======================= TRÊS MOTIVOS ======================= -->
    <section class="section section--alt" id="motivos">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <h2 class="section-title">Três motivos para parar de adiar a organização</h2>
            </div>

            <div class="reasons-grid">
                <?php foreach ($motivos as $m): ?>
                <div class="reason-card reveal">
                    <span class="reason-card__num"><?= htmlspecialchars($m['num']) ?></span>
                    <h3 class="reason-card__title"><?= htmlspecialchars($m['titulo']) ?></h3>
                    <p class="reason-card__desc"><?= htmlspecialchars($m['desc']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ======================= RECURSOS (4 cards) ======================= -->
    <section class="section section--tight" id="recursos">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Recursos</span>
                <h2 class="section-title">Feito para a rotina da barbearia.</h2>
            </div>

            <div class="features-grid">
                <?php foreach ($recursos as $r): ?>
                <div class="feature-card reveal">
                    <div class="feature-card__icon"><?= icon($r['icon']) ?></div>
                    <h3 class="feature-card__title"><?= htmlspecialchars($r['titulo']) ?></h3>
                    <p class="feature-card__desc"><?= htmlspecialchars($r['desc']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ======================= SISTEMA (capturas reais) =======================
         Carrossel + lightbox com as 10 imagens pedidas ficam pendentes: nenhum
         dos arquivos com nome exato existe ainda em assets/images/barberp/.
         Enquanto isso, esta seção mostra as 4 capturas reais já disponíveis
         (ou o mockup em CSS, sem nenhum aviso de "em breve" visível). -->
    <section class="section section--alt" id="sistema">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">O sistema</span>
                <h2 class="section-title">Tudo o que você precisa para organizar o dia a dia.</h2>
            </div>

            <div class="product-grid">
                <div class="product-item reveal">
                    <?php if ($fotoAgenda !== null): ?>
                    <div class="browser-frame">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoAgenda) ?>" alt="Agenda do BarbERP organizada por barbeiro e horário" loading="lazy" decoding="async"></div>
                    </div>
                    <?php else: ?>
                    <div class="browser-frame">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body agenda-mock">
                            <div class="agenda-mock__head"><span class="agenda-mock__title">Agenda de hoje</span><span class="agenda-mock__sub">2 barbeiros</span></div>
                            <div class="agenda-mock__cols">
                                <span></span><span class="agenda-mock__colhead">João</span><span class="agenda-mock__colhead">Pedro</span>
                                <span class="agenda-mock__time">09:00</span><span class="agenda-mock__cell agenda-mock__cell--blue"></span><span class="agenda-mock__cell"></span>
                                <span class="agenda-mock__time">10:00</span><span class="agenda-mock__cell"></span><span class="agenda-mock__cell agenda-mock__cell--green"></span>
                                <span class="agenda-mock__time">11:00</span><span class="agenda-mock__cell agenda-mock__cell--blue"></span><span class="agenda-mock__cell"></span>
                                <span class="agenda-mock__time">12:00</span><span class="agenda-mock__cell"></span><span class="agenda-mock__cell"></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <p class="product-item__caption">Agenda</p>
                    <p class="product-item__desc">Horários organizados por barbeiro, com status de cada atendimento.</p>
                </div>

                <div class="product-item reveal">
                    <?php if ($fotoFinanceiro !== null): ?>
                    <div class="browser-frame">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoFinanceiro) ?>" alt="Dashboard financeiro do BarbERP com entradas, saídas e saldo" loading="lazy" decoding="async"></div>
                    </div>
                    <?php endif; ?>
                    <p class="product-item__caption">Dashboard financeiro</p>
                    <p class="product-item__desc">Entradas, saídas e saldo sempre à vista.</p>
                </div>

                <div class="product-item reveal">
                    <?php if ($fotoFiados !== null): ?>
                    <div class="browser-frame">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoFiados) ?>" alt="Tela de baixa de fiado do BarbERP" loading="lazy" decoding="async"></div>
                    </div>
                    <?php endif; ?>
                    <p class="product-item__caption">Controle de fiados</p>
                    <p class="product-item__desc">Valores pendentes, histórico e baixa de pagamento.</p>
                </div>

                <div class="product-item reveal">
                    <?php if ($fotoAgendaLink !== null): ?>
                    <div class="browser-frame browser-frame--phone">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoAgendaLink) ?>" alt="Tela de agendamento online: cliente escolhe data e horário" loading="lazy" decoding="async"></div>
                    </div>
                    <?php endif; ?>
                    <p class="product-item__caption">Agendamento online</p>
                    <p class="product-item__desc">Cliente escolhe o serviço, o barbeiro e o horário sozinho. Disponível no plano Pro.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= ACESSE DE ONDE ESTIVER ======================= -->
    <section class="section section--tight access">
        <div class="container">
            <div class="access__box reveal">
                <span class="access__icon"><?= icon('globe') ?></span>
                <h2 class="access__title">Acesse de onde estiver</h2>
                <p class="access__text">O BarbERP funciona direto do navegador, no computador, notebook, tablet ou celular — sem precisar instalar nada.</p>
            </div>
        </div>
    </section>

    <!-- ======================= AVALIAÇÕES ======================= -->
    <section class="section" id="avaliacoes">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Avaliações</span>
                <h2 class="section-title">Quem usa, conta.</h2>
            </div>

            <div class="testimonials-grid">
                <?php for ($i = 0; $i < 3; $i++): ?>
                <div class="testimonial-skeleton reveal">
                    <span class="testimonial-skeleton__icon"><?= icon('quote') ?></span>
                    <span class="testimonial-skeleton__line testimonial-skeleton__line--w80"></span>
                    <span class="testimonial-skeleton__line testimonial-skeleton__line--w60"></span>
                    <span class="testimonial-skeleton__line testimonial-skeleton__line--w40"></span>
                </div>
                <?php endfor; ?>
            </div>
            <p class="testimonials-note">Em breve, depoimentos reais de barbearias que usam o BarbERP aparecerão aqui.</p>
        </div>
    </section>

    <!-- ======================= PLANOS ======================= -->
    <section class="section section--alt" id="planos">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Planos</span>
                <h2 class="section-title">Quanto custa?</h2>
            </div>

            <div class="plans-grid">
                <?php foreach ($planos as $p): $dev = !empty($p['emDesenvolvimento']); ?>
                <div class="card plan-card<?= $p['destaque'] ? ' plan-card--featured' : '' ?><?= $dev ? ' plan-card--dev' : '' ?> reveal">
                    <?php if (!empty($p['badge'])): ?>
                        <span class="badge <?= $dev ? 'badge--neutral' : 'badge--highlight' ?> plan-card__badge"><?= htmlspecialchars($p['badge']) ?></span>
                    <?php endif; ?>
                    <h3 class="plan-card__name"><?= htmlspecialchars($p['nome']) ?></h3>
                    <p class="plan-card__desc"><?= htmlspecialchars($p['desc']) ?></p>
                    <div class="plan-card__price">
                        <span class="plan-card__price-value">R$ <?= htmlspecialchars($p['preco']) ?></span>
                        <span class="plan-card__price-period">/mês</span>
                    </div>
                    <ul class="plan-card__list<?= count($p['itens']) <= 5 ? ' plan-card__list--compact' : '' ?>">
                        <?php foreach ($p['itens'] as $item): ?>
                        <li><?= icon('check') ?><span><?= htmlspecialchars($item) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="plan-card__cta">
                        <?php if ($dev): ?>
                        <span class="btn btn--secondary btn--block plan-card__cta--disabled" aria-disabled="true">Em desenvolvimento</span>
                        <p class="plan-card__dev-note">Este plano ainda não está disponível para contratação.</p>
                        <?php else: ?>
                        <a href="#teste-gratuito" class="btn <?= $p['destaque'] ? 'btn--primary' : 'btn--secondary' ?> btn--block" data-cta-plano="<?= htmlspecialchars($p['nome']) ?>">Começar agora</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Comparativo -->
            <div class="compare" style="margin-top:var(--space-8);">
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th scope="col">Recurso</th>
                            <th scope="col">Básico</th>
                            <th scope="col">Pro</th>
                            <th scope="col">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compareFeatures as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['label']) ?></td>
                            <?php foreach (['basico', 'pro', 'enterprise'] as $coluna): $v = $f[$coluna]; ?>
                            <td>
                                <?php if ($v === 'dev'): ?>
                                    <span class="dev">Em desenvolvimento*</span>
                                <?php elseif ($v): ?>
                                    <span class="ok"><?= icon('check') ?></span>
                                <?php else: ?>
                                    <span class="no">—</span>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="compare-note">*Recursos ainda em desenvolvimento, sem data de lançamento confirmada.</p>

                <div class="compare-cards">
                    <?php foreach ($planos as $p):
                        $chave = $p['nome'] === 'Básico' ? 'basico' : ($p['nome'] === 'Pro' ? 'pro' : 'enterprise');
                    ?>
                    <div class="compare-card">
                        <p class="compare-card__name"><?= htmlspecialchars($p['nome']) ?></p>
                        <?php foreach ($compareFeatures as $f): $v = $f[$chave]; ?>
                        <div class="compare-card__row">
                            <span><?= htmlspecialchars($f['label']) ?></span>
                            <?php if ($v === 'dev'): ?>
                                <span class="dev">Em desenvolvimento*</span>
                            <?php elseif ($v): ?>
                                <span class="ok"><?= icon('check') ?></span>
                            <?php else: ?>
                                <span class="no">—</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= PRÓXIMAS ATUALIZAÇÕES ======================= -->
    <section class="section section--tight" id="atualizacoes">
        <div class="container">
            <div class="roadmap-card reveal">
                <div class="roadmap-card__head">
                    <span class="roadmap-card__label">Roadmap</span>
                    <h2 class="roadmap-card__title">Próximas atualizações</h2>
                    <p class="roadmap-card__text">O BarbERP continua evoluindo. Novos recursos serão adicionados gradualmente para tornar a gestão da sua barbearia ainda mais completa.</p>
                </div>

                <div class="roadmap-grid">
                    <?php foreach ($atualizacoesFuturas as $rf): ?>
                    <div class="roadmap-item">
                        <span class="roadmap-item__icon"><?= icon($rf['icon']) ?></span>
                        <div class="roadmap-item__body">
                            <p class="roadmap-item__title"><?= htmlspecialchars($rf['titulo']) ?></p>
                            <p class="roadmap-item__desc"><?= htmlspecialchars($rf['desc']) ?></p>
                            <span class="badge roadmap-item__status<?= $rf['status'] === 'Em desenvolvimento' ? ' roadmap-item__status--dev' : '' ?>"><?= htmlspecialchars($rf['status']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <p class="roadmap-card__note">Os recursos apresentados acima não fazem parte da versão 1.0 atual e poderão ser disponibilizados gradualmente em futuras atualizações.</p>
            </div>
        </div>
    </section>

    <!-- ======================= FAQ ======================= -->
    <section class="section section--alt" id="faq">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Perguntas frequentes</span>
                <h2 class="section-title">E minhas dúvidas?</h2>
            </div>

            <div class="faq" data-faq>
                <?php foreach ($faqs as $i => $item): $faqId = 'faq-' . ($i + 1); ?>
                <div class="faq-item reveal" data-open="false">
                    <h3>
                        <button type="button" class="faq-item__question" id="<?= $faqId ?>-q" aria-expanded="false" aria-controls="<?= $faqId ?>-p" data-faq-trigger>
                            <span><?= htmlspecialchars($item['p']) ?></span>
                            <span class="faq-item__icon"><?= icon('plus') ?></span>
                        </button>
                    </h3>
                    <div class="faq-item__panel" id="<?= $faqId ?>-p" role="region" aria-labelledby="<?= $faqId ?>-q" data-faq-panel>
                        <div class="faq-item__panel-inner">
                            <p><?= htmlspecialchars($item['r']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ======================= TESTE GRATUITO ======================= -->
    <section class="section" id="teste-gratuito">
        <div class="container lead-section">
            <div class="lead-form-col reveal">
                <span class="section-eyebrow">Teste gratuito</span>
                <h2 class="section-title">30 dias grátis. Sem cartão. Sem compromisso.</h2>
                <p class="section-subtitle" style="margin-bottom:var(--space-6);">Crie sua conta, configure seus serviços e veja como é ter a rotina da sua barbearia organizada. Se não fizer sentido para você, é só cancelar.</p>

                <span class="badge badge--highlight lead-form__plan-tag" data-plan-tag>
                    <?= icon('check') ?><span data-plan-tag-text>Plano selecionado</span>
                </span>

                <form class="lead-form" id="form-teste-gratuito" action="/processar_lead.php" method="post" novalidate data-lead-form>
                    <div class="form-group">
                        <label class="form-label" for="nome_completo">Nome completo</label>
                        <input class="form-input" type="text" id="nome_completo" name="nome_completo" placeholder="Seu nome completo" autocomplete="name" required minlength="3">
                        <span class="form-error-msg" data-error-for="nome_completo">Informe seu nome completo.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">E-mail</label>
                        <input class="form-input" type="email" id="email" name="email" placeholder="seuemail@exemplo.com" autocomplete="email" required>
                        <span class="form-error-msg" data-error-for="email">Informe um e-mail válido.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="telefone">Telefone</label>
                        <input class="form-input" type="tel" id="telefone" name="telefone" placeholder="(11) 99999-9999" autocomplete="tel" inputmode="tel" required>
                        <span class="form-error-msg" data-error-for="telefone">Informe um telefone válido com DDD.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="descricao">Descrição <span class="optional">(opcional)</span></label>
                        <textarea class="form-textarea" id="descricao" name="descricao" placeholder="Conte um pouco sobre sua barbearia: quantos barbeiros, se já usa algum sistema, etc."></textarea>
                    </div>

                    <input type="hidden" name="plano_interesse" id="plano_interesse" value="" data-plano-interesse>

                    <div class="form-honeypot" aria-hidden="true">
                        <label for="empresa">Não preencha este campo</label>
                        <input type="text" id="empresa" name="empresa" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-feedback form-feedback--success" role="status" data-form-success>
                        <?= icon('check') ?>
                        <span>Recebemos sua solicitação! Nossa equipe vai entrar em contato em breve.</span>
                    </div>
                    <div class="form-feedback form-feedback--error" role="alert" data-form-error>
                        <?= icon('x') ?>
                        <span data-form-error-text>Não foi possível registrar sua solicitação agora. Tente novamente em instantes.</span>
                    </div>

                    <button type="submit" class="btn btn--primary btn--lg btn--block" data-submit-btn>
                        <span class="spinner" aria-hidden="true"></span>
                        <span class="btn__label">Solicitar teste gratuito</span>
                    </button>
                </form>
            </div>

            <div class="lead-aside reveal">
                <div class="card">
                    <h3 style="margin-bottom:var(--space-4); font-size:var(--fs-md);">O que acontece depois do envio?</h3>
                    <div class="lead-aside__item"><?= icon('check') ?><span>Nossa equipe entra em contato pelo telefone ou e-mail informado.</span></div>
                    <div class="lead-aside__item"><?= icon('check') ?><span>Você recebe acesso para conhecer o sistema na prática.</span></div>
                    <div class="lead-aside__item"><?= icon('check') ?><span>Sem compromisso — são 30 dias grátis.</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= CONTATO ======================= -->
    <section class="section section--tight section--alt contact">
        <div class="container">
            <div class="contact__box reveal">
                <p class="contact__text">Prefere falar diretamente com a gente? Entre em contato pelo WhatsApp ou Instagram.</p>
                <div class="contact__links">
                    <a href="https://wa.me/5528999430511" target="_blank" rel="noopener noreferrer" class="btn btn--secondary"><?= icon('whatsapp') ?> WhatsApp</a>
                    <a href="https://www.instagram.com/daksolucoes.oficial" target="_blank" rel="noopener noreferrer" class="btn btn--secondary"><?= icon('instagram') ?> Instagram</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= CTA FINAL ======================= -->
    <section class="section cta-final">
        <div class="container">
            <h2 class="cta-final__title">Pronto para deixar o improviso para trás?</h2>
            <p class="cta-final__text">Comece a organizar sua barbearia com o BarbERP.</p>
            <div class="cta-final__actions">
                <a href="#teste-gratuito" class="btn btn--primary btn--lg" data-cta-plano="">Testar grátis por 30 dias</a>
            </div>
            <p class="cta-final__secondary">Ou fale diretamente conosco pelo WhatsApp ou Instagram.</p>
        </div>
    </section>

</main>

<!-- ======================= FOOTER ======================= -->
<footer class="footer">
    <div class="container">
        <div class="footer__top">
            <div class="footer__brand">
                <span class="logo__mark">Barb<span>ERP</span></span>
                <p class="footer__tagline">Uma solução da DAK Soluções Digitais.</p>
                <span class="badge footer__version">Versão 1.0</span>
            </div>

            <div class="footer__col">
                <h3 class="footer__col-title">Links</h3>
                <nav class="footer__links" aria-label="Links da página">
                    <a href="#recursos">Recursos</a>
                    <a href="#planos">Planos</a>
                    <a href="#faq">FAQ</a>
                    <a href="#teste-gratuito">Testar grátis</a>
                </nav>
            </div>

            <div class="footer__col">
                <h3 class="footer__col-title">Contato</h3>
                <nav class="footer__links" aria-label="Contato da DAK Soluções Digitais">
                    <a href="https://www.instagram.com/daksolucoes.oficial" target="_blank" rel="noopener noreferrer"><?= icon('instagram') ?> @daksolucoes.oficial</a>
                    <a href="https://wa.me/5528999430511" target="_blank" rel="noopener noreferrer"><?= icon('whatsapp') ?> (28) 99943-0511</a>
                </nav>
            </div>
        </div>

        <div class="footer__bottom">
            <span>© <?= htmlspecialchars($anoAtual) ?> DAK Soluções Digitais. Todos os direitos reservados.</span>
        </div>
    </div>
</footer>

<script src="<?= htmlspecialchars(versaoAsset('assets/js/main.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/faq.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/form.js')) ?>" defer></script>
</body>
</html>
