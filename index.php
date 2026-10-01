<?php
/**
 * index.php
 * Landing page comercial do BarbERP (produto da DAK Soluções Digitais) —
 * versão 4.0 (reformulação completa), apresentando a V1.0 real do sistema.
 * O único plano/seção que menciona recursos ainda não existentes é o plano
 * Enterprise e a seção "O BarbERP continua evoluindo", ambos claramente
 * marcados como em desenvolvimento/futuro.
 *
 * Projeto standalone — NÃO faz parte do sistema BarbERP em si (que fica em
 * outro repositório/hospedagem). Esta página só existe para apresentar o
 * produto e captar leads pelo formulário "Teste gratuito", que grava no
 * banco leads_app_barber através de processar_lead.php (ver config.php).
 *
 * IMAGENS (ver README.md, seção 3): o hero e o carrossel de funcionalidades
 * usam nomes de arquivo EXATOS (sem apelidos/alternativas), por instrução
 * explícita — nenhuma imagem é substituída por outra. Quando um arquivo
 * exato não existe em assets/images/barberp/, a vaga mostra um painel
 * neutro (sem simular uma tela falsa) até o arquivo ser adicionado; nesse
 * momento a foto real aparece sozinha, sem precisar mexer em código.
 */

declare(strict_types=1);

$anoAtual = date('Y');

/* -------------------------------------------------------------------------
 * Dados das seções
 * ---------------------------------------------------------------------- */

/* Três motivos para organizar a barbearia agora — texto exato fornecido,
 * sem estatísticas nem percentuais inventados. */
$motivos = [
    [
        'num'   => '01',
        'titulo' => 'Clientes querem praticidade',
        'desc'  => 'Hoje o cliente espera resolver tudo rápido, inclusive marcar seu horário.',
    ],
    [
        'num'   => '02',
        'titulo' => 'WhatsApp sozinho não organiza sua barbearia',
        'desc'  => 'Ele ajuda na conversa, mas não substitui uma agenda estruturada, histórico de clientes e controle financeiro.',
    ],
    [
        'num'   => '03',
        'titulo' => 'Organização traz previsibilidade',
        'desc'  => 'Menos confusão na rotina significa mais controle sobre horários, atendimentos e dinheiro.',
    ],
];

/* Carrossel de funcionalidades — ordem obrigatória, nomes de arquivo
 * exatos. Nenhum apelido/alternativa é aceito aqui (ver fotoRealExata()). */
$carrossel = [
    [
        'arquivo' => 'dashboardprincipal.png',
        'titulo'  => 'Tenha uma visão geral da sua barbearia.',
        'desc'    => 'Acompanhe métricas de agendamentos, evolução mensal, ações rápidas e os principais indicadores do dia.',
    ],
    [
        'arquivo' => 'comoagendar.png',
        'titulo'  => 'Agende um atendimento em poucos passos.',
        'desc'    => 'Busque um cliente ou cadastre um novo, selecione o serviço e organize o horário. Também é possível definir recorrências de 7, 15, 20 ou 30 dias e inativar horários quando necessário.',
    ],
    [
        'arquivo' => 'agendar.png',
        'titulo'  => 'Uma agenda visual para não perder o controle.',
        'desc'    => 'Visualize os horários do dia de forma clara e identifique rapidamente a situação de cada atendimento.',
        'legenda' => [
            ['cor' => '#15803d', 'texto' => 'Disponível'],
            ['cor' => '#dc2626', 'texto' => 'Agendado'],
            ['cor' => '#eab308', 'texto' => 'Indisponível'],
            ['cor' => '#f97316', 'texto' => 'Agendamento via link'],
            ['cor' => '#a78bfa', 'texto' => 'Agendamento duplicado'],
        ],
        'complemento' => 'A agenda também possui lista de espera e permite inativar horários ou dias.',
    ],
    [
        'arquivo' => 'alteraragendamento.png',
        'titulo'  => 'Flexibilidade quando o cliente muda de ideia.',
        'desc'    => 'Altere o horário, troque o agendamento com outro cliente do dia ou reorganize os atendimentos conforme a necessidade.',
    ],
    [
        'arquivo' => 'finalizarservico.png',
        'titulo'  => 'Finalize o atendimento e registre o pagamento.',
        'desc'    => 'Ao concluir o atendimento, confira o cliente e o serviço realizado, altere o serviço caso necessário, registre ausência e selecione a forma de pagamento. Se o pagamento ficar para depois, o valor pode ser direcionado para o controle de fiados.',
    ],
    [
        'arquivo' => 'receberfiado.png',
        'titulo'  => 'Controle seus fiados sem perder o histórico.',
        'desc'    => 'Registre pagamentos parciais ou totais e escolha a forma de pagamento. O cliente pode quitar o valor em diferentes momentos, mantendo o histórico organizado.',
    ],
    [
        'arquivo' => 'dashfinanceiro.png',
        'titulo'  => 'Entenda para onde está indo o dinheiro.',
        'desc'    => 'Visualize entradas e saídas por dia, semana, mês ou ano, acompanhe gráficos e veja a distribuição das formas de pagamento.',
    ],
    [
        'arquivo' => 'cliente01.png',
        'titulo'  => 'Tenha os dados dos seus clientes organizados.',
        'desc'    => 'Consulte as informações do cliente e defina se ele está ativo ou inativo.',
    ],
    [
        'arquivo' => 'cliente02.png',
        'titulo'  => 'Veja os atendimentos realizados.',
        'desc'    => 'Consulte os serviços realizados, datas, horários, valores e status dos atendimentos.',
    ],
    [
        'arquivo' => 'cliente03.png',
        'titulo'  => 'Histórico completo do cliente.',
        'desc'    => 'Consulte pagamentos, valores lançados em fiado e movimentações relacionadas ao cliente. Também é possível lançar um fiado manualmente.',
    ],
];

$planos = [
    [
        'nome'  => 'Básico',
        'preco' => '68,90',
        'desc'  => 'Para organizar a rotina e ter controle da sua barbearia.',
        'itens' => ['Clientes', 'Serviços', 'Barbeiros', 'Agendamentos', 'Agendamentos recorrentes', 'Controle de fiados', 'Financeiro', 'Dashboard', 'Perfis', 'Configurações'],
        'destaque' => false,
    ],
    [
        'nome'  => 'Pro',
        'preco' => '98,90',
        'desc'  => 'Para barbearias que também querem oferecer agendamento online.',
        'itens' => ['Tudo do plano Básico', 'Agendamento online', 'Link público de agendamento'],
        'destaque' => true,
        'badge'    => 'Recomendado',
    ],
    [
        'nome'  => 'Enterprise',
        'preco' => '129,90',
        'desc'  => 'Para operações maiores e necessidades específicas.',
        'itens' => ['Tudo do plano Pro', 'Gestão via WhatsApp', 'Lembretes', 'Mais de 5 barbeiros', 'Até 2 barbearias'],
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
    ['label' => 'Agendamentos', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Recorrência', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Fiados', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Financeiro', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Dashboard', 'basico' => true, 'pro' => true, 'enterprise' => true],
    ['label' => 'Agendamento online', 'basico' => false, 'pro' => true, 'enterprise' => true],
    ['label' => 'WhatsApp', 'basico' => false, 'pro' => false, 'enterprise' => 'dev'],
    ['label' => 'Lembretes', 'basico' => false, 'pro' => false, 'enterprise' => 'dev'],
];

$faqs = [
    ['p' => 'Preciso instalar alguma coisa?', 'r' => 'Não. O BarbERP funciona pelo navegador.'],
    ['p' => 'Posso acessar pelo celular?', 'r' => 'Sim. O sistema é web e pode ser acessado em dispositivos com navegador e internet.'],
    ['p' => 'O cliente pode agendar sozinho?', 'r' => 'Sim. O agendamento online está disponível no plano Pro.'],
    ['p' => 'Posso controlar os fiados?', 'r' => 'Sim. O BarbERP possui controle de fiados e histórico de pagamentos.'],
    ['p' => 'Existe período gratuito?', 'r' => 'Sim. O teste é de 30 dias e não exige cartão.'],
    ['p' => 'Como faço para começar?', 'r' => 'Preencha o formulário de teste gratuito ou entre em contato conosco pelo WhatsApp ou Instagram.'],
];

/* -------------------------------------------------------------------------
 * Fotos reais do sistema.
 * ---------------------------------------------------------------------- */

/* Mantida para compatibilidade com nomes alternativos já usados no projeto
 * (não se aplica às imagens novas do hero/carrossel — ver fotoRealExata). */
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

/* Hero e carrossel exigem o nome de arquivo EXATO pedido — nunca um
 * apelido nem outra foto real do projeto no lugar dela, para nunca
 * apresentar uma imagem como se fosse outra. */
function fotoRealExata(string $nomeExato): ?string
{
    $caminhoRelativo = 'assets/images/barberp/' . $nomeExato;
    return file_exists(__DIR__ . '/' . $caminhoRelativo) ? $caminhoRelativo : null;
}

/* -------------------------------------------------------------------------
 * Cache-busting automático para CSS/JS.
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
        'bars'        => '<rect x="4" y="12" width="3.6" height="8" rx="0.8"/><rect x="10.2" y="7" width="3.6" height="13" rx="0.8"/><rect x="16.4" y="3.5" width="3.6" height="16.5" rx="0.8"/>',
        'bell'        => '<path d="M12 3.5c-3 0-4.6 2.3-4.6 5.4v2.6c0 .9-.3 1.7-.9 2.4l-.8 1h12.6l-.8-1c-.6-.7-.9-1.5-.9-2.4V8.9c0-3.1-1.6-5.4-4.6-5.4Z"/><path d="M9.8 19a2.2 2.2 0 0 0 4.4 0"/>',
        'globe'       => '<circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.2 2.3 3.4 5.3 3.4 8.5s-1.2 6.2-3.4 8.5c-2.2-2.3-3.4-5.3-3.4-8.5S9.8 5.8 12 3.5Z"/>',
        'quote'       => '<path d="M7.5 9.5c-1.7 0-3 1.3-3 3v2.5c0 1.1.9 2 2 2h2.5v-4.5h-2v-.5c0-1.1.9-2 2-2V9.5h-1.5Z"/><path d="M16 9.5c-1.7 0-3 1.3-3 3v2.5c0 1.1.9 2 2 2h2.5v-4.5h-2v-.5c0-1.1.9-2 2-2V9.5H16Z"/>',
        'monitor'     => '<rect x="2.5" y="4.5" width="19" height="13" rx="1.6"/><path d="M8 20.5h8M12 17.5v3"/>',
        'laptop'      => '<rect x="4" y="4.5" width="16" height="10.5" rx="1.4"/><path d="M2.5 19.5h19l-1.3-3H3.8l-1.3 3Z"/>',
        'tablet'      => '<rect x="5" y="2.8" width="14" height="18.4" rx="2"/><path d="M11.4 18.3h1.2"/>',
        'smartphone'  => '<rect x="7" y="2.5" width="10" height="19" rx="2.2"/><path d="M11.3 18.2h1.4"/>',
        'chevron-left'  => '<path d="M14.5 5 7.5 12l7 7"/>',
        'chevron-right' => '<path d="M9.5 5 16.5 12l-7 7"/>',
        'expand'      => '<path d="M9 4H4v5M15 4h5v5M9 20H4v-5M15 20h5v-5"/>',
    ];

    $miolo = $paths[$nome] ?? $paths['check'];

    return "<svg {$atributos}>{$miolo}</svg>";
}

/* Fotos usadas na seção "Acesse de onde estiver" — mantém o padrão
 * resiliente de nomes alternativos, já que não foi pedida como parte do
 * conjunto de nomes exatos do carrossel. */
$fotoTresJuntos = fotoReal(['tresjuntos.png']);

/* Hero e carrossel: nome de arquivo exato, sem substituição. */
$fotoHero = fotoRealExata('agenda.png');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BarbERP — Sistema de Gestão para Barbearias</title>
<meta name="description" content="Organize agenda, clientes, fiados e financeiro da sua barbearia com o BarbERP. Teste grátis por 30 dias.">
<meta name="theme-color" content="#ffffff">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%232554E8'/%3E%3Ctext x='12' y='17' font-size='13' font-family='Arial,Helvetica,sans-serif' font-weight='700' fill='white' text-anchor='middle'%3EB%3C/text%3E%3C/svg%3E">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:locale" content="pt_BR">
<meta property="og:title" content="BarbERP — Sistema de Gestão para Barbearias">
<meta property="og:description" content="Organize agenda, clientes, fiados e financeiro da sua barbearia com o BarbERP. Teste grátis por 30 dias.">
<meta property="og:site_name" content="BarbERP">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">

<link rel="preconnect" href="/">
<link rel="stylesheet" href="<?= htmlspecialchars(versaoAsset('assets/css/style.css')) ?>">

<!-- Sem JavaScript: menu mobile fica sempre visível, o FAQ mostra todas as
     respostas abertas e o carrossel mostra todas as slides empilhadas (sem
     depender de clique para revelar nenhum conteúdo). -->
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
    .carousel__track { display: block !important; transform: none !important; }
    .carousel__slide { display: grid !important; margin-bottom: var(--space-6); }
    .carousel__controls { display: none !important; }
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
                <?php if ($fotoHero !== null): ?>
                <button type="button" class="img-trigger" data-lightbox-src="/<?= htmlspecialchars($fotoHero) ?>" data-lightbox-alt="Tela do BarbERP">
                    <div class="browser-frame">
                        <div class="browser-frame__bar">
                            <span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span>
                        </div>
                        <div class="browser-frame__body">
                            <img src="/<?= htmlspecialchars($fotoHero) ?>" alt="Tela do BarbERP" loading="eager" decoding="async">
                        </div>
                    </div>
                    <span class="img-trigger__hint"><?= icon('expand') ?></span>
                </button>
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

    <!-- ======================= SISTEMA / CARROSSEL ======================= -->
    <section class="section section--tight" id="recursos">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">O sistema</span>
                <h2 class="section-title">Tudo o que você precisa para organizar o dia a dia.</h2>
                <p class="section-subtitle">Conheça algumas das principais funções do BarbERP na prática.</p>
            </div>
        </div>

        <div class="carousel-wrap reveal" data-carousel>
            <div class="carousel__viewport">
                <div class="carousel__track" data-carousel-track>
                    <?php foreach ($carrossel as $i => $item): $foto = fotoRealExata($item['arquivo']); ?>
                    <div class="carousel__slide" data-carousel-slide aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
                        <div class="carousel__media">
                            <?php if ($foto !== null): ?>
                            <button type="button" class="img-trigger" data-lightbox-src="/<?= htmlspecialchars($foto) ?>" data-lightbox-alt="<?= htmlspecialchars($item['titulo']) ?>">
                                <div class="browser-frame">
                                    <div class="browser-frame__bar"><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span><span class="browser-frame__dot"></span></div>
                                    <div class="browser-frame__body"><img src="/<?= htmlspecialchars($foto) ?>" alt="<?= htmlspecialchars($item['titulo']) ?>" loading="lazy" decoding="async"></div>
                                </div>
                                <span class="img-trigger__hint"><?= icon('expand') ?></span>
                            </button>
                            <?php else: ?>
                            <div class="media-placeholder" aria-hidden="true">
                                <?= icon('chart') ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="carousel__info">
                            <span class="carousel__step">Funcionalidade <?= $i + 1 ?> de <?= count($carrossel) ?></span>
                            <h3 class="carousel__title"><?= htmlspecialchars($item['titulo']) ?></h3>
                            <p class="carousel__desc"><?= htmlspecialchars($item['desc']) ?></p>

                            <?php if (!empty($item['legenda'])): ?>
                            <div class="carousel__legend">
                                <?php foreach ($item['legenda'] as $leg): ?>
                                <span class="carousel__legend-item"><span class="carousel__legend-dot" style="background:<?= htmlspecialchars($leg['cor']) ?>"></span><?= htmlspecialchars($leg['texto']) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($item['complemento'])): ?>
                            <p class="carousel__complement"><?= htmlspecialchars($item['complemento']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="carousel__controls">
                <button type="button" class="carousel__arrow" data-carousel-prev aria-label="Funcionalidade anterior"><?= icon('chevron-left') ?></button>
                <div class="carousel__dots" data-carousel-dots></div>
                <button type="button" class="carousel__arrow" data-carousel-next aria-label="Próxima funcionalidade"><?= icon('chevron-right') ?></button>
            </div>
        </div>
    </section>

    <!-- ======================= ACESSE DE ONDE ESTIVER ======================= -->
    <section class="section section--alt access">
        <div class="container access__grid">
            <div class="access__content reveal">
                <span class="access__icon"><?= icon('globe') ?></span>
                <h2 class="access__title">Acesse de onde estiver.</h2>
                <p class="access__text">O BarbERP é um sistema web. Não precisa instalar programas e pode ser acessado pelo navegador em computadores, notebooks, tablets e celulares, de qualquer lugar com acesso à internet.</p>
                <div class="access__devices">
                    <span class="access__device"><?= icon('monitor') ?>Computador</span>
                    <span class="access__device"><?= icon('laptop') ?>Notebook</span>
                    <span class="access__device"><?= icon('tablet') ?>Tablet</span>
                    <span class="access__device"><?= icon('smartphone') ?>Celular</span>
                </div>
            </div>
            <?php if ($fotoTresJuntos !== null): ?>
            <div class="access__visual reveal">
                <button type="button" class="img-trigger" data-lightbox-src="/<?= htmlspecialchars($fotoTresJuntos) ?>" data-lightbox-alt="BarbERP em computador, tablet e celular">
                    <img src="/<?= htmlspecialchars($fotoTresJuntos) ?>" alt="BarbERP em computador, tablet e celular" loading="lazy" decoding="async" class="access__img">
                </button>
            </div>
            <?php endif; ?>
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
                <div class="testimonial-card reveal">
                    <span class="testimonial-card__icon"><?= icon('quote') ?></span>
                    <p class="testimonial-card__text">"Troquei a agenda manual pela função de agendamentos recorrentes, de 15 em 15 e de 20 em 20 dias. Hoje me sinto muito mais organizado e realizado com o BarbERP."</p>
                    <p class="testimonial-card__name">Alex Monteiro</p>
                </div>

                <div class="testimonial-skeleton reveal">
                    <span class="testimonial-skeleton__icon"><?= icon('quote') ?></span>
                    <p class="testimonial-skeleton__placeholder">Depoimento real de cliente será inserido aqui.</p>
                </div>

                <div class="testimonial-skeleton reveal">
                    <span class="testimonial-skeleton__icon"><?= icon('quote') ?></span>
                    <p class="testimonial-skeleton__placeholder">Depoimento real de cliente será inserido aqui.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= PLANOS ======================= -->
    <section class="section section--alt" id="planos">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Planos</span>
                <h2 class="section-title">Escolha o plano para sua barbearia.</h2>
                <p class="section-subtitle">Comece com o essencial e evolua conforme sua necessidade.</p>
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
                        <p class="plan-card__dev-note">Alguns recursos deste plano serão disponibilizados em futuras atualizações.</p>
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

    <!-- ======================= O BARBERP CONTINUA EVOLUINDO ======================= -->
    <section class="section section--tight evolving">
        <div class="container">
            <div class="evolving__box reveal">
                <span class="section-eyebrow">Roadmap</span>
                <h2 class="evolving__title">O BarbERP continua evoluindo.</h2>
                <p class="evolving__text">A DAK Soluções Digitais conversa com cada cliente para entender suas necessidades. Novas funcionalidades poderão ser desenvolvidas e adicionadas às próximas versões do sistema.</p>
                <p class="evolving__list">WhatsApp · Lembretes · Estoque · Comissões · Relatórios avançados</p>
                <p class="evolving__note">Recursos sujeitos a desenvolvimento e disponibilidade em futuras versões.</p>
            </div>
        </div>
    </section>

    <!-- ======================= FAQ ======================= -->
    <section class="section section--alt" id="faq">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">FAQ</span>
                <h2 class="section-title">Perguntas frequentes</h2>
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

<!-- ======================= LIGHTBOX (hero + carrossel) ======================= -->
<div class="lightbox-overlay" data-lightbox-overlay role="dialog" aria-modal="true" aria-label="Visualização de imagem">
    <div class="lightbox-overlay__content">
        <button type="button" class="lightbox-overlay__close" data-lightbox-close aria-label="Fechar"><?= icon('x') ?></button>
        <img data-lightbox-img alt="">
    </div>
</div>

<script src="<?= htmlspecialchars(versaoAsset('assets/js/main.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/faq.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/form.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/carousel.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/lightbox.js')) ?>" defer></script>
</body>
</html>
