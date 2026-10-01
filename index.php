<?php
/**
 * index.php
 * Landing page comercial do BarbERP (produto da DAK Soluções Digitais) —
 * versão 2.0 (redesign), apresentando exclusivamente a V1.0 real do
 * sistema: nenhum recurso listado aqui é promessa futura.
 *
 * Projeto standalone — NÃO faz parte do sistema BarbERP em si (que fica em
 * outro repositório/hospedagem). Esta página só existe para apresentar o
 * produto e captar leads pelo formulário "Teste gratuito", que grava no
 * banco leads_app_barber através de processar_lead.php (ver config.php).
 *
 * Capturas reais do sistema ficam em assets/images/barberp/. Cada seção usa
 * fotoReal() com uma lista de nomes aceitos — quando um arquivo ainda não
 * existe, um mockup simples em CSS entra no lugar automaticamente (ver
 * seção "Produto"), sem nenhum aviso de "em breve" na tela.
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

$planos = [
    [
        'nome'  => 'Gestão',
        'preco' => '69,90',
        'desc'  => 'Para barbearias que querem organizar a gestão.',
        'itens' => ['Clientes', 'Serviços', 'Barbeiros', 'Agendamentos', 'Agendamentos recorrentes', 'Controle de fiados', 'Financeiro', 'Dashboard', 'Perfis', 'Configurações'],
        'destaque' => false,
    ],
    [
        'nome'  => 'Gestão + Agendamento Online',
        'preco' => '99,90',
        'desc'  => 'Para quem também quer permitir que os clientes agendem sozinhos.',
        'itens' => ['Tudo do plano Gestão', 'Agendamento online', 'Link público de agendamento'],
        'destaque' => true,
        'badge'    => 'Recomendado',
    ],
];

$compareFeatures = [
    ['label' => 'Clientes', 'gestao' => true, 'online' => true],
    ['label' => 'Serviços', 'gestao' => true, 'online' => true],
    ['label' => 'Barbeiros', 'gestao' => true, 'online' => true],
    ['label' => 'Agenda', 'gestao' => true, 'online' => true],
    ['label' => 'Agendamentos recorrentes', 'gestao' => true, 'online' => true],
    ['label' => 'Controle de fiados', 'gestao' => true, 'online' => true],
    ['label' => 'Financeiro', 'gestao' => true, 'online' => true],
    ['label' => 'Dashboard', 'gestao' => true, 'online' => true],
    ['label' => 'Agendamento online', 'gestao' => false, 'online' => true],
];

$faqs = [
    ['p' => 'Preciso instalar algum programa?', 'r' => 'Não. O BarbERP funciona pelo navegador, no computador ou celular.'],
    ['p' => 'O BarbERP funciona para quem trabalha sozinho?', 'r' => 'Sim. A gestão pode ser utilizada por profissionais individuais e barbearias com equipe.'],
    ['p' => 'Meus clientes podem agendar online?', 'r' => 'Sim. O recurso está disponível no plano Gestão + Agendamento Online.'],
    ['p' => 'Posso controlar fiados?', 'r' => 'Sim. Você registra os valores pendentes e acompanha os pagamentos.'],
    ['p' => 'O sistema possui financeiro?', 'r' => 'Sim. É possível acompanhar entradas, saídas, valores a receber e saldo.'],
    ['p' => 'Posso mudar de plano?', 'r' => 'Sim. O plano pode ser alterado conforme a necessidade da barbearia.'],
    ['p' => 'O teste é gratuito?', 'r' => 'Sim. Após o envio do formulário, a equipe entra em contato para liberar o acesso.'],
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
<meta name="description" content="Agenda, clientes, fiados e financeiro da sua barbearia em um só lugar. Conheça o BarbERP e teste gratuitamente.">
<meta name="theme-color" content="#ffffff">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%232554E8'/%3E%3Ctext x='12' y='17' font-size='13' font-family='Arial,Helvetica,sans-serif' font-weight='700' fill='white' text-anchor='middle'%3EB%3C/text%3E%3C/svg%3E">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:locale" content="pt_BR">
<meta property="og:title" content="BarbERP | Gestão simples para barbearias">
<meta property="og:description" content="Agenda, clientes, fiados e financeiro em um só lugar. Conheça o BarbERP e teste gratuitamente.">
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
                <li><a class="nav__link" href="#agendamento-online">Agendamento Online</a></li>
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
    <a class="mobile-nav__link" href="#agendamento-online" data-menu-link>Agendamento Online</a>
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
                    <span class="hero__meta-item"><?= icon('check') ?> Teste gratuito</span>
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

    <!-- ======================= PRODUTO (3 screenshots) ======================= -->
    <section class="section section--alt" id="produto">
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
                    <?php if ($fotoAgendaLink !== null): ?>
                    <div class="browser-frame browser-frame--phone">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoAgendaLink) ?>" alt="Tela de agendamento online: cliente escolhe data e horário" loading="lazy" decoding="async"></div>
                    </div>
                    <?php endif; ?>
                    <p class="product-item__caption">Agendamento online</p>
                    <p class="product-item__desc">Cliente escolhe o serviço, o barbeiro e o horário sozinho.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= AGENDAMENTO ONLINE ======================= -->
    <section class="section" id="agendamento-online">
        <div class="container">
            <div class="online-booking reveal">
                <div class="online-booking__content">
                    <span class="section-eyebrow">Agendamento online</span>
                    <h2 class="section-title">Deixe seus clientes agendarem sozinhos.</h2>
                    <p class="section-subtitle">Compartilhe seu link de agendamento no WhatsApp, Instagram ou onde quiser. O cliente escolhe o serviço, o barbeiro e o horário disponível.</p>
                    <div style="margin-top:var(--space-6);">
                        <a href="#teste-gratuito" class="btn btn--primary btn--block" data-cta-plano="Gestão + Agendamento Online">Quero agendamento online</a>
                    </div>
                    <div class="online-booking__note">
                        <?= icon('shield') ?>
                        <span>Disponível no plano Gestão + Agendamento Online.</span>
                    </div>
                </div>
                <div class="online-booking__visual">
                    <?php if ($fotoAgendaLink !== null): ?>
                    <div class="browser-frame browser-frame--phone">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoAgendaLink) ?>" alt="Cliente escolhendo data e horário na tela pública de agendamento" loading="lazy" decoding="async"></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= FINANCEIRO + FIADOS ======================= -->
    <section class="section section--alt" id="financeiro">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Financeiro</span>
                <h2 class="section-title">Saiba o que entrou, o que saiu e o que ainda falta receber.</h2>
            </div>

            <div class="finance-split">
                <div class="finance-panel reveal">
                    <div class="finance-panel__head">
                        <span class="finance-panel__icon"><?= icon('chart') ?></span>
                        <h3 class="finance-panel__title">Financeiro</h3>
                    </div>
                    <ul class="finance-panel__list">
                        <li><?= icon('check') ?>Entradas</li>
                        <li><?= icon('check') ?>Saídas</li>
                        <li><?= icon('check') ?>Valores a receber</li>
                        <li><?= icon('check') ?>Saldo</li>
                    </ul>
                    <?php if ($fotoFinanceiro !== null): ?>
                    <div class="browser-frame">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoFinanceiro) ?>" alt="Dashboard financeiro do BarbERP" loading="lazy" decoding="async"></div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="finance-panel reveal">
                    <div class="finance-panel__head">
                        <span class="finance-panel__icon"><?= icon('wallet') ?></span>
                        <h3 class="finance-panel__title">Controle de fiados</h3>
                    </div>
                    <ul class="finance-panel__list">
                        <li><?= icon('check') ?>Valores pendentes</li>
                        <li><?= icon('check') ?>Histórico</li>
                        <li><?= icon('check') ?>Baixa de pagamento</li>
                    </ul>
                    <?php if ($fotoFiados !== null): ?>
                    <div class="browser-frame">
                        <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                        <div class="browser-frame__body"><img src="/<?= htmlspecialchars($fotoFiados) ?>" alt="Tela de baixa de fiado do BarbERP" loading="lazy" decoding="async"></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= PLANOS ======================= -->
    <section class="section" id="planos">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Planos</span>
                <h2 class="section-title">Quanto custa?</h2>
            </div>

            <div class="plans-grid">
                <?php foreach ($planos as $p): ?>
                <div class="card plan-card<?= $p['destaque'] ? ' plan-card--featured' : '' ?> reveal">
                    <?php if (!empty($p['badge'])): ?>
                        <span class="badge badge--highlight plan-card__badge"><?= htmlspecialchars($p['badge']) ?></span>
                    <?php endif; ?>
                    <h3 class="plan-card__name"><?= htmlspecialchars($p['nome']) ?></h3>
                    <p class="plan-card__desc"><?= htmlspecialchars($p['desc']) ?></p>
                    <div class="plan-card__price">
                        <span class="plan-card__price-value">R$ <?= htmlspecialchars($p['preco']) ?></span>
                        <span class="plan-card__price-period">/mês</span>
                    </div>
                    <ul class="plan-card__list">
                        <?php foreach ($p['itens'] as $item): ?>
                        <li><?= icon('check') ?><span><?= htmlspecialchars($item) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="plan-card__cta">
                        <a href="#teste-gratuito" class="btn <?= $p['destaque'] ? 'btn--primary' : 'btn--secondary' ?> btn--block" data-cta-plano="<?= htmlspecialchars($p['nome']) ?>">Começar agora</a>
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
                            <th scope="col">Gestão</th>
                            <th scope="col">Gestão + Online</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compareFeatures as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['label']) ?></td>
                            <td><?= $f['gestao'] ? '<span class="ok">' . icon('check') . '</span>' : '<span class="no">—</span>' ?></td>
                            <td><?= $f['online'] ? '<span class="ok">' . icon('check') . '</span>' : '<span class="no">—</span>' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="compare-cards">
                    <?php foreach ($planos as $p): ?>
                    <div class="compare-card">
                        <p class="compare-card__name"><?= htmlspecialchars($p['nome']) ?></p>
                        <?php foreach ($compareFeatures as $f):
                            $chave = $p['nome'] === 'Gestão' ? 'gestao' : 'online';
                            $tem = $f[$chave];
                        ?>
                        <div class="compare-card__row">
                            <span><?= htmlspecialchars($f['label']) ?></span>
                            <?= $tem ? '<span class="ok">' . icon('check') . '</span>' : '<span class="no">—</span>' ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= TESTE GRATUITO ======================= -->
    <section class="section section--alt" id="teste-gratuito">
        <div class="container lead-section">
            <div class="lead-form-col reveal">
                <span class="section-eyebrow">Teste gratuito</span>
                <h2 class="section-title">Teste o BarbERP na sua barbearia.</h2>
                <p class="section-subtitle" style="margin-bottom:var(--space-6);">Conheça o sistema na prática e veja como ele pode organizar sua rotina.</p>

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
                    <div class="lead-aside__item"><?= icon('check') ?><span>Sem compromisso — o teste é gratuito.</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= FAQ ======================= -->
    <section class="section" id="faq">
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

    <!-- ======================= CTA FINAL ======================= -->
    <section class="section cta-final">
        <div class="container">
            <h2 class="cta-final__title">Organize sua barbearia hoje.</h2>
            <p class="cta-final__text">Tenha sua agenda, clientes, fiados e financeiro em um só lugar.</p>
            <div class="cta-final__actions">
                <a href="#teste-gratuito" class="btn btn--primary btn--lg" data-cta-plano="">Testar grátis</a>
            </div>
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