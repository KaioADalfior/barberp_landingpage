<?php
/**
 * index.php
 * Landing page comercial do BarbERP (produto da DAK Soluções Digitais).
 *
 * Projeto standalone — NÃO faz parte do sistema BarbERP em si (que fica em
 * outro repositório/hospedagem). Esta página só existe para apresentar o
 * produto e captar leads pelo formulário "Teste gratuito", que grava no
 * banco leads_app_barber através de processar_lead.php (ver config.php).
 *
 * O conteúdo abaixo reflete apenas funcionalidades reais do sistema.
 * As telas do carrossel são mockups elegantes em CSS/HTML — quando as
 * capturas reais chegarem, basta colocá-las em assets/images/barberp/
 * (dashboard.png, agendamentos.png, clientes.png, agenda-online01.png,
 * agenda-online02.png para o carrossel; financeiro.png e fiados.png para
 * a seção "Financeiro") e o PHP troca o mockup pela foto sozinho — ver
 * file_exists() em cada seção.
 */

declare(strict_types=1);

$anoAtual = date('Y');

/* -------------------------------------------------------------------------
 * Dados das seções (mantidos em arrays para reaproveitar no HTML abaixo)
 * ---------------------------------------------------------------------- */

$funcionalidades = [
    [
        'titulo' => 'Clientes',
        'desc'   => 'Cadastre clientes com nome, telefone e e-mail, e mantenha o histórico de atendimentos de cada um.',
        'icon'   => 'users',
    ],
    [
        'titulo' => 'Serviços',
        'desc'   => 'Cadastre os serviços da barbearia com preço e duração, prontos para usar na hora de agendar.',
        'icon'   => 'scissors',
    ],
    [
        'titulo' => 'Agendamentos',
        'desc'   => 'Organize a agenda de cada barbeiro, com horários, status e histórico de cada atendimento.',
        'icon'   => 'calendar',
    ],
    [
        'titulo' => 'Agendamentos recorrentes',
        'desc'   => 'Configure clientes fiéis para retornar automaticamente a cada 7, 15, 20 ou 30 dias, sem remarcar toda vez.',
        'icon'   => 'repeat',
    ],
    [
        'titulo' => 'Controle de Fiados',
        'desc'   => 'Registre atendimentos fiados, acompanhe quem está devendo e dê baixa assim que o pagamento chegar.',
        'icon'   => 'wallet',
    ],
    [
        'titulo' => 'Financeiro',
        'desc'   => 'Veja receitas, despesas, valores a receber e o saldo da barbearia em um único painel.',
        'icon'   => 'chart',
    ],
    [
        'titulo' => 'Dashboard',
        'desc'   => 'Acompanhe os números do dia e do mês — atendimentos, faturamento e agenda — em uma só tela.',
        'icon'   => 'dashboard',
    ],
    [
        'titulo' => 'Perfil personalizado',
        'desc'   => 'Cada barbeiro tem seu próprio perfil dentro do sistema, com foto e dados de contato.',
        'icon'   => 'user-circle',
    ],
    [
        'titulo' => 'Configurações',
        'desc'   => 'Ajuste os dados da barbearia, controle tentativas de login e mantenha o sistema do seu jeito.',
        'icon'   => 'settings',
    ],
    [
        'titulo' => 'Agenda online',
        'desc'   => 'Compartilhe um link para os clientes agendarem horário sozinhos, sem precisar ligar.',
        'icon'   => 'globe',
        'tag'    => 'Disponível nos planos com Agendamento Online',
    ],
];

$telas = [
    [
        'id'       => 'inicio-tela',
        'titulo'   => 'Início',
        'legenda'  => 'Painel do dia: atendimentos, faturamento e agenda em um relance.',
        'imagem'   => fotoReal(['dashboard.png', 'dashboard_principal.png']),
        'mockup'   => 'inicio',
    ],
    [
        'id'       => 'agendamentos-tela',
        'titulo'   => 'Agendamentos',
        'legenda'  => 'Agenda organizada por horário, com status de cada atendimento.',
        'imagem'   => fotoReal(['agendamentos.png']),
        'mockup'   => 'agendamentos',
    ],
    [
        'id'       => 'clientes-tela',
        'titulo'   => 'Clientes',
        'legenda'  => 'Cadastro de cada cliente, com telefone, e-mail e histórico à mão.',
        'imagem'   => fotoReal(['clientes.png']),
        'mockup'   => 'clientes',
    ],
    [
        'id'       => 'agenda-online-tela',
        'titulo'   => 'Agenda Online',
        'legenda'  => 'Cliente escolhe o horário pelo celular, sem precisar ligar.',
        'imagem'   => fotoReal(['agenda-online01.png']),
        'mockup'   => 'agenda-online',
    ],
    [
        'id'       => 'agenda-online-confirmacao-tela',
        'titulo'   => 'Confirmação',
        'legenda'  => 'Confirmação automática, sem trocar mensagem com o cliente.',
        'imagem'   => fotoReal(['agenda-online02.png']),
        'mockup'   => 'agenda-online-confirmacao',
    ],
];

$diferenciais = [
    [
        'titulo' => 'Gestão centralizada',
        'desc'   => 'Clientes, serviços, barbeiros e agenda no mesmo lugar, sem planilhas soltas.',
        'icon'   => 'layers',
    ],
    [
        'titulo' => 'Recorrência',
        'desc'   => 'Agendamentos que se repetem sozinhos, para os clientes que já têm o dia certo de cortar o cabelo.',
        'icon'   => 'repeat',
    ],
    [
        'titulo' => 'Controle financeiro',
        'desc'   => 'Saiba exatamente quanto entrou, quanto falta receber e quanto está em aberto em fiados.',
        'icon'   => 'wallet',
    ],
    [
        'titulo' => 'Agendamento online',
        'desc'   => 'Deixe seus clientes marcarem horário pelo celular, a qualquer hora do dia.',
        'icon'   => 'globe',
    ],
];

$planos = [
    [
        'nome'  => 'Gestão',
        'preco' => '119,90',
        'desc'  => 'A gestão completa da barbearia, do jeito que ela já funciona no dia a dia.',
        'itens' => [
            'Clientes',
            'Serviços',
            'Barbeiros',
            'Agendamentos',
            'Agendamentos recorrentes',
            'Controle de Fiados',
            'Financeiro completo',
            'Dashboard',
            'Perfil personalizado',
            'Configurações',
        ],
        'destaque' => false,
    ],
    [
        'nome'  => 'Gestão + Agendamento Online',
        'preco' => '179,90',
        'desc'  => 'Tudo do plano Gestão, com um link para seus clientes agendarem sozinhos.',
        'itens' => [
            'Tudo do plano Gestão',
            'Agenda online (link público)',
            'Cliente escolhe o horário sozinho',
        ],
        'destaque' => true,
        'badge'    => 'Mais escolhido',
    ],
    [
        'nome'  => 'Gestão + Agendamento + WhatsApp',
        'preco' => '219,90',
        'desc'  => 'Tudo do plano anterior, com lembretes automáticos pelo WhatsApp.',
        'itens' => [
            'Tudo do plano Gestão + Agendamento Online',
            'Lembretes de agendamento via WhatsApp',
            'Confirmações automáticas via WhatsApp',
        ],
        'destaque'         => false,
        'emDesenvolvimento' => true,
    ],
];

$comparativo = [
    ['Clientes', true, true, true],
    ['Serviços', true, true, true],
    ['Barbeiros', true, true, true],
    ['Agendamentos', true, true, true],
    ['Agendamentos recorrentes', true, true, true],
    ['Controle de Fiados', true, true, true],
    ['Financeiro completo', true, true, true],
    ['Dashboard', true, true, true],
    ['Perfil personalizado', true, true, true],
    ['Configurações', true, true, true],
    ['Agenda online (link público)', false, true, true],
    ['Lembretes via WhatsApp', false, false, true],
];

$faqs = [
    [
        'p' => 'O BarbERP funciona para barbearias de qualquer tamanho?',
        'r' => 'Sim. Funciona bem tanto para quem trabalha sozinho quanto para barbearias com vários barbeiros, cada um com sua própria agenda e perfil.',
    ],
    [
        'p' => 'Preciso instalar algum programa?',
        'r' => 'Não. O BarbERP funciona direto no navegador, no computador ou no celular, sem instalação.',
    ],
    [
        'p' => 'Meus clientes conseguem agendar sozinhos pela internet?',
        'r' => 'Sim, nos planos com Agendamento Online. Você compartilha um link e o cliente escolhe o horário direto, sem precisar ligar.',
    ],
    [
        'p' => 'Como funcionam os agendamentos recorrentes?',
        'r' => 'Você configura a recorrência de um cliente — a cada 7, 15, 20 ou 30 dias, por exemplo — e o sistema já organiza os próximos horários automaticamente.',
    ],
    [
        'p' => 'Dá para controlar fiado pelo sistema?',
        'r' => 'Sim. Você registra o atendimento como fiado, acompanha quem está devendo e dá baixa quando o pagamento for feito.',
    ],
    [
        'p' => 'O sistema mostra o financeiro da barbearia?',
        'r' => 'Sim. Você acompanha receitas, despesas, valores a receber e o saldo, tudo em um painel só.',
    ],
    [
        'p' => 'Cada barbeiro tem acesso próprio?',
        'r' => 'Sim. Cada barbeiro tem seu perfil e sua agenda dentro do mesmo sistema, sem misturar os atendimentos.',
    ],
    [
        'p' => 'Como funciona o teste gratuito?',
        'r' => 'Você preenche o formulário abaixo e nossa equipe entra em contato para liberar seu acesso e ajudar você a começar.',
    ],
    [
        'p' => 'Posso mudar de plano depois?',
        'r' => 'Sim. Você pode ajustar seu plano conforme a necessidade da sua barbearia for mudando.',
    ],
    [
        'p' => 'O que muda entre os planos?',
        'r' => 'Os três planos incluem toda a gestão da barbearia. A diferença está no Agendamento Online, disponível a partir do plano intermediário. Os lembretes por WhatsApp ainda estão em desenvolvimento e serão liberados em breve.',
    ],
];

/* -------------------------------------------------------------------------
 * Fotos reais do sistema — aceita mais de um nome de arquivo possível para
 * a mesma foto (ex.: o nome "oficial" e o nome original do print enviado),
 * assim um arquivo salvo com o nome "errado" continua funcionando em vez
 * de cair silenciosamente no mockup em CSS.
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
 * do arquivo> em cada link. Assim, toda vez que um desses arquivos for
 * atualizado no servidor, o navegador do visitante busca a versão nova
 * sozinho, em vez de continuar usando uma cópia antiga guardada em cache
 * (o que fazia ajustes de CSS/JS "não aparecerem" mesmo após o deploy).
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
        'scissors'    => '<circle cx="6" cy="6" r="2.4"/><circle cx="6" cy="18" r="2.4"/><path d="M8.2 7.6 20 18M20 6 8.2 16.4"/>',
        'calendar'    => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.4"/><path d="M3.5 9.8h17M8 3v3.5M16 3v3.5"/>',
        'repeat'      => '<path d="M4 8.5A6.5 6.5 0 0 1 19 6.3M20 4v3.5h-3.5"/><path d="M20 15.5A6.5 6.5 0 0 1 5 17.7M4 20v-3.5h3.5"/>',
        'wallet'      => '<rect x="3" y="6.5" width="18" height="12.5" rx="2.2"/><path d="M3 10.5h18"/><circle cx="16.5" cy="14.5" r="1.2"/>',
        'chart'       => '<path d="M4 20V10M11 20V4M18 20v-7"/><path d="M2.5 20h19"/>',
        'dashboard'   => '<rect x="3.2" y="3.2" width="7.6" height="7.6" rx="1.6"/><rect x="13.2" y="3.2" width="7.6" height="4.8" rx="1.6"/><rect x="13.2" y="10.2" width="7.6" height="10.6" rx="1.6"/><rect x="3.2" y="13" width="7.6" height="7.8" rx="1.6"/>',
        'user-circle' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="10" r="3"/><path d="M6.2 18.5c1.1-2.4 3.2-3.6 5.8-3.6s4.7 1.2 5.8 3.6"/>',
        'settings'    => '<circle cx="12" cy="12" r="3.2"/><path d="M12 3v2.4M12 18.6V21M21 12h-2.4M5.4 12H3M18.4 5.6l-1.7 1.7M7.3 16.7l-1.7 1.7M18.4 18.4l-1.7-1.7M7.3 7.3 5.6 5.6"/>',
        'globe'       => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.4 2.6 3.7 5.7 3.7 9s-1.3 6.4-3.7 9c-2.4-2.6-3.7-5.7-3.7-9S9.6 5.6 12 3Z"/>',
        'layers'      => '<path d="M12 3.5 21 8l-9 4.5L3 8l9-4.5Z"/><path d="m3 12 9 4.5 9-4.5M3 16l9 4.5 9-4.5"/>',
        'check'       => '<path d="M5 12.5 9.5 17 19 7.5"/>',
        'x'           => '<path d="M6 6l12 12M18 6 6 18"/>',
        'shield'      => '<path d="M12 3.5 19.5 7v5.3c0 4.4-3.1 7.6-7.5 8.7-4.4-1.1-7.5-4.3-7.5-8.7V7L12 3.5Z"/><path d="m9 12 2.2 2.2L15.5 10"/>',
        'clock'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5.3l3.6 2.1"/>',
        'arrow-right' => '<path d="M4.5 12h15M13.5 6l6 6-6 6"/>',
        'chevron'     => '<path d="M9 6l7 6-7 6"/>',
        'plus'        => '<path d="M12 5v14M5 12h14"/>',
        'trend-up'    => '<path d="M3.5 16.5 10 10l4 4 6.5-6.5"/><path d="M15 7.5h5.5V13"/>',
        'instagram'   => '<rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.3" cy="6.7" r="0.6" fill="currentColor" stroke="none"/>',
        'whatsapp'    => '<path d="M20.5 11.5a8.5 8.5 0 0 1-12.4 7.5L4 20l1.1-4a8.5 8.5 0 1 1 15.4-4.5Z"/><path d="M9 9.2c.2-.6.5-.6.8-.6.2 0 .4 0 .6.4.2.5.6 1.4.6 1.6 0 .1 0 .3-.1.4-.2.3-.4.5-.6.7-.1.2-.3.3 0 .6.4.6 1 1.2 1.6 1.6.6.4 1 .6 1.3.7.2.1.4.1.5-.1.2-.2.6-.7.8-.9.2-.2.3-.2.5-.1.2.1 1.4.6 1.6.8.2.1.3.2.3.3 0 .2 0 .7-.3 1.1-.3.4-1.2.8-1.7.8s-1.4-.1-2.9-1c-2.2-1.3-3.6-3.5-3.7-3.7-.1-.2-.9-1.2-.9-2.1 0-.9.5-1.3.7-1.5Z"/>',
    ];

    $miolo = $paths[$nome] ?? $paths['check'];

    return "<svg {$atributos}>{$miolo}</svg>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BarbERP | Sistema de Gestão para Barbearias</title>
<meta name="description" content="Sistema de gestão para barbearias: clientes, agendamentos recorrentes, fiados e financeiro em um só lugar. Teste gratuitamente.">
<meta name="theme-color" content="#0B0E17">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%230B0E17'/%3E%3Ctext x='12' y='17' font-size='13' font-family='Arial,Helvetica,sans-serif' font-weight='700' fill='%2338BDF8' text-anchor='middle'%3EB%3C/text%3E%3C/svg%3E">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:locale" content="pt_BR">
<meta property="og:title" content="BarbERP | Sistema de Gestão para Barbearias">
<meta property="og:description" content="Clientes, agendamentos recorrentes, fiados e financeiro em um só lugar. Conheça o BarbERP e teste gratuitamente.">
<meta property="og:site_name" content="BarbERP">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">

<link rel="preconnect" href="/">
<link rel="stylesheet" href="<?= htmlspecialchars(versaoAsset('assets/css/style.css')) ?>">

<!-- Sem JavaScript: menu mobile fica sempre visível (não há como abrir o
     painel), o FAQ mostra todas as respostas abertas, e o carrossel vira
     uma lista simples que rola, em vez de depender das setas/toque. -->
<noscript>
<style>
    .hamburger { display: none; }
    .header__cta { display: inline-flex; }
    .mobile-nav {
        position: static;
        inset: auto;
        transform: none;
        visibility: visible;
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-4);
        align-items: center;
        padding: var(--space-4) var(--space-5);
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
    }
    .mobile-nav__link { border-bottom: none; padding: var(--space-2); font-size: var(--fs-sm); }
    .mobile-nav .btn { margin-top: 0; width: auto; }
    .faq-item__panel { height: auto !important; }
    .carousel__track { flex-wrap: wrap; transform: none !important; }
    .carousel__slide { flex: 1 1 280px; }
    .carousel__arrow, .carousel__dots { display: none; }
</style>
</noscript>
</head>
<body>

<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

<!-- ======================= HEADER ======================= -->
<header class="header" id="topo" data-header>
    <div class="container header__inner">
        <a href="#inicio" class="logo" aria-label="BarbERP, página inicial">
            <span class="logo__mark">BARB<span>ERP</span></span>
            <span class="logo__by">by DAK Soluções Digitais</span>
        </a>

        <nav class="nav" aria-label="Navegação principal">
            <ul class="nav__list">
                <li><a class="nav__link" href="#inicio">Início</a></li>
                <li><a class="nav__link" href="#funcionalidades">Funcionalidades</a></li>
                <li><a class="nav__link" href="#demonstracao">Demonstração</a></li>
                <li><a class="nav__link" href="#planos">Planos</a></li>
                <li><a class="nav__link" href="#faq">FAQ</a></li>
            </ul>
        </nav>

        <div class="header__actions">
            <a href="#teste-gratuito" class="btn btn--primary header__cta">Testar gratuitamente</a>
            <button type="button" class="hamburger" data-menu-toggle aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-nav">
                <span class="hamburger__bar"></span>
                <span class="hamburger__bar"></span>
                <span class="hamburger__bar"></span>
            </button>
        </div>
    </div>
</header>

<nav id="mobile-nav" class="mobile-nav" aria-label="Navegação mobile" data-mobile-nav>
    <a class="mobile-nav__link" href="#inicio" data-menu-link>Início</a>
    <a class="mobile-nav__link" href="#funcionalidades" data-menu-link>Funcionalidades</a>
    <a class="mobile-nav__link" href="#demonstracao" data-menu-link>Demonstração</a>
    <a class="mobile-nav__link" href="#planos" data-menu-link>Planos</a>
    <a class="mobile-nav__link" href="#faq" data-menu-link>FAQ</a>
    <a href="#teste-gratuito" class="btn btn--primary btn--block" data-menu-link>Testar gratuitamente</a>
</nav>

<main id="conteudo">

    <!-- ======================= HERO ======================= -->
    <section class="hero" id="inicio">
        <div class="container hero__grid">
            <div class="hero__content reveal">
                <h1 class="hero__title">Tenha o controle da sua barbearia na palma da mão.</h1>
                <p class="hero__subtitle">Agenda, clientes, fiados e financeiro organizados em um só sistema — simples de usar, do jeito que a sua barbearia já funciona no dia a dia.</p>
                <div class="hero__actions">
                    <a href="#teste-gratuito" class="btn btn--primary btn--lg" data-cta-plano="">Quero testar gratuitamente</a>
                    <a href="#funcionalidades" class="btn btn--secondary btn--lg">Conhecer o sistema</a>
                </div>
                <div class="hero__meta">
                    <span class="hero__meta-item"><?= icon('check') ?> Sem instalação</span>
                    <span class="hero__meta-item"><?= icon('check') ?> Acesso pelo navegador</span>
                    <span class="hero__meta-item"><?= icon('check') ?> Teste sem compromisso</span>
                </div>
            </div>

            <div class="hero__visual reveal">
                <?php $fotoDashboard = fotoReal(['dashboar_principal.png', 'dashboard_principal.png']); ?>
                <?php if ($fotoDashboard !== null): ?>
                    <div class="mockup mockup--photo">
                        <img
                            src="/<?= htmlspecialchars($fotoDashboard) ?>"
                            alt="Painel do BarbERP mostrando atendimentos do dia, faturamento e agenda"
                            loading="eager"
                            decoding="async"
                        >
                    </div>
                    <span class="mockup__floating mockup__floating--1">
                        <?= icon('trend-up') ?> +18% neste mês
                    </span>
                    <span class="mockup__floating mockup__floating--2">
                        <?= icon('check') ?> Horário confirmado
                    </span>
                <?php else: ?>
                    <div class="mockup" role="img" aria-label="Ilustração do painel do BarbERP mostrando atendimentos do dia, faturamento e agenda">
                        <div class="mockup__topbar">
                            <span class="mockup__dot"></span><span class="mockup__dot"></span><span class="mockup__dot"></span>
                        </div>
                        <div class="mockup__body">
                            <div class="mockup__sidebar">
                                <span class="mockup__sidebar-icon mockup__sidebar-icon--active"></span>
                                <span class="mockup__sidebar-icon"></span>
                                <span class="mockup__sidebar-icon"></span>
                                <span class="mockup__sidebar-icon"></span>
                                <span class="mockup__sidebar-icon"></span>
                            </div>
                            <div class="mockup__content">
                                <div class="mockup__row">
                                    <div class="mockup__stat">
                                        <div class="mockup__stat-label">ATENDIMENTOS HOJE</div>
                                        <div class="mockup__stat-value">12</div>
                                    </div>
                                    <div class="mockup__stat">
                                        <div class="mockup__stat-label">FATURAMENTO DO DIA</div>
                                        <div class="mockup__stat-value mockup__stat-value--accent">R$ 640</div>
                                    </div>
                                    <div class="mockup__stat">
                                        <div class="mockup__stat-label">A RECEBER</div>
                                        <div class="mockup__stat-value">R$ 180</div>
                                    </div>
                                </div>
                                <div class="mockup__chart" aria-hidden="true">
                                    <span style="height:40%"></span><span style="height:65%"></span><span style="height:50%"></span>
                                    <span style="height:80%"></span><span style="height:55%"></span><span style="height:70%"></span>
                                    <span style="height:45%"></span>
                                </div>
                                <div class="mockup__list">
                                    <div class="mockup__list-row"><span>14:00 — Corte + Barba</span><span>Confirmado</span></div>
                                    <div class="mockup__list-row"><span>14:30 — Corte</span><span>Aguardando</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="mockup__floating mockup__floating--1">
                        <?= icon('trend-up') ?> +18% neste mês
                    </span>
                    <span class="mockup__floating mockup__floating--2">
                        <?= icon('check') ?> Horário confirmado
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ======================= PROBLEMA / SOLUÇÃO ======================= -->
    <section class="section section--alt">
        <div class="container split">
            <div class="split__col split__col--problema reveal">
                <span class="badge">O dia a dia sem organização</span>
                <h2>Agenda no caderno, fiado de cabeça e financeiro na planilha.</h2>
                <ul class="split__list">
                    <li><?= icon('x') ?> Cliente esquecido, horário duplicado ou marcado errado.</li>
                    <li><?= icon('x') ?> Fiado que ninguém lembra quem já pagou.</li>
                    <li><?= icon('x') ?> Faturamento do mês só descoberto no fim do mês.</li>
                    <li><?= icon('x') ?> Cliente que só consegue marcar horário ligando.</li>
                </ul>
            </div>
            <div class="split__col split__col--solucao reveal">
                <span class="badge badge--highlight">Com o BarbERP</span>
                <h2>Organize sua agenda. Controle seus fiados. Veja o financeiro em tempo real.</h2>
                <ul class="split__list">
                    <li><?= icon('check') ?> Agenda por barbeiro, com recorrência automática.</li>
                    <li><?= icon('check') ?> Fiados registrados e com baixa em um clique.</li>
                    <li><?= icon('check') ?> Financeiro atualizado a cada atendimento.</li>
                    <li><?= icon('check') ?> Link de agendamento online para os clientes.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ======================= FUNCIONALIDADES ======================= -->
    <section class="section" id="funcionalidades">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Funcionalidades</span>
                <h2 class="section-title">Tudo que a sua barbearia precisa, em um só sistema.</h2>
                <p class="section-subtitle">Cada módulo do BarbERP resolve uma parte real da rotina da barbearia — sem telas complicadas.</p>
            </div>

            <div class="features-grid">
                <?php foreach ($funcionalidades as $f): ?>
                <div class="card feature-card reveal">
                    <div class="feature-card__icon"><?= icon($f['icon']) ?></div>
                    <h3 class="feature-card__title"><?= htmlspecialchars($f['titulo']) ?></h3>
                    <p class="feature-card__desc"><?= htmlspecialchars($f['desc']) ?></p>
                    <?php if (!empty($f['tag'])): ?>
                        <span class="badge badge--highlight feature-card__tag"><?= htmlspecialchars($f['tag']) ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ======================= CARROSSEL DE TELAS ======================= -->
    <section class="section section--alt" id="demonstracao">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Demonstração</span>
                <h2 class="section-title">Veja o BarbERP por dentro.</h2>
                <p class="section-subtitle">Um passeio rápido pelas principais telas do sistema.</p>
            </div>

            <div class="carousel reveal" data-carousel>
                <div class="carousel__viewport">
                    <div class="carousel__track" data-carousel-track>
                        <?php foreach ($telas as $i => $t):
                            // Se a captura real já foi adicionada em assets/images/barberp/,
                            // usa a imagem de verdade (fotoReal() já resolveu o caminho ao
                            // montar $telas). Enquanto não existir, mostra o mockup elegante
                            // em CSS/HTML como alternativa.
                            $temImagemReal = $t['imagem'] !== null;
                        ?>
                        <figure class="carousel__slide" aria-roledescription="slide" aria-label="<?= $i + 1 ?> de <?= count($telas) ?>: <?= htmlspecialchars($t['titulo']) ?>">
                            <?php if ($temImagemReal): ?>
                                <img
                                    src="/<?= htmlspecialchars($t['imagem']) ?>"
                                    alt="Captura de tela do BarbERP — <?= htmlspecialchars($t['titulo']) ?>: <?= htmlspecialchars($t['legenda']) ?>"
                                    loading="lazy"
                                    decoding="async"
                                    draggable="false"
                                    data-lightbox-trigger
                                    data-slide-title="<?= htmlspecialchars($t['titulo']) ?>"
                                    data-slide-src="/<?= htmlspecialchars($t['imagem']) ?>"
                                >
                            <?php else: ?>
                            <div class="mockup mockup--slide" data-lightbox-trigger data-slide-title="<?= htmlspecialchars($t['titulo']) ?>">
                                <div class="mockup__topbar">
                                    <span class="mockup__dot"></span><span class="mockup__dot"></span><span class="mockup__dot"></span>
                                </div>
                                <div class="mockup__content">
                                    <div class="mockup__slide-title"><?= htmlspecialchars($t['titulo']) ?></div>

                                    <?php if ($t['mockup'] === 'inicio'): ?>
                                        <div class="mockup__row">
                                            <div class="mockup__stat"><div class="mockup__stat-label">ATENDIMENTOS</div><div class="mockup__stat-value">12</div></div>
                                            <div class="mockup__stat"><div class="mockup__stat-label">FATURAMENTO</div><div class="mockup__stat-value mockup__stat-value--accent">R$ 640</div></div>
                                            <div class="mockup__stat"><div class="mockup__stat-label">CLIENTES ATIVOS</div><div class="mockup__stat-value">238</div></div>
                                        </div>
                                        <div class="mockup__chart" aria-hidden="true">
                                            <span style="height:35%"></span><span style="height:60%"></span><span style="height:48%"></span>
                                            <span style="height:75%"></span><span style="height:52%"></span>
                                        </div>
                                    <?php elseif ($t['mockup'] === 'agendamentos'): ?>
                                        <div class="mockup__list">
                                            <div class="schedule-row"><span class="schedule-row__time">09:00</span><span class="schedule-row__name">Marcos Souza — Corte</span><span class="status-badge status-badge--confirmado">Confirmado</span></div>
                                            <div class="schedule-row"><span class="schedule-row__time">09:30</span><span class="schedule-row__name">Igor Peixoto — Barba</span><span class="status-badge status-badge--aguardando">Aguardando</span></div>
                                            <div class="schedule-row"><span class="schedule-row__time">10:00</span><span class="schedule-row__name">Caio Freitas — Corte + Barba</span><span class="status-badge status-badge--confirmado">Confirmado</span></div>
                                        </div>
                                    <?php elseif ($t['mockup'] === 'clientes'): ?>
                                        <div class="mockup__list">
                                            <div class="schedule-row"><span class="schedule-row__name">Marcos Souza</span><span class="status-badge status-badge--confirmado">Ativo</span></div>
                                            <div class="schedule-row"><span class="schedule-row__name">Igor Peixoto</span><span class="status-badge status-badge--confirmado">Ativo</span></div>
                                            <div class="schedule-row"><span class="schedule-row__name">Caio Freitas</span><span class="status-badge status-badge--confirmado">Ativo</span></div>
                                            <div class="schedule-row"><span class="schedule-row__name">Renato Fonseca</span><span class="status-badge status-badge--aguardando">Inativo</span></div>
                                        </div>
                                    <?php elseif ($t['mockup'] === 'agenda-online'): ?>
                                        <div class="booking-phone">
                                            <div class="booking-phone__screen">
                                                <div class="booking-phone__header">Agendar horário</div>
                                                <div class="booking-phone__slot">09:00</div>
                                                <div class="booking-phone__slot booking-phone__slot--selected">09:30</div>
                                                <div class="booking-phone__slot">10:00</div>
                                                <div class="booking-phone__btn">Confirmar agendamento</div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="booking-phone">
                                            <div class="booking-phone__screen">
                                                <div class="booking-phone__header">Agendamento confirmado</div>
                                                <div class="booking-phone__slot booking-phone__slot--selected">09:30 · Corte + Barba</div>
                                                <span class="status-badge status-badge--confirmado">Confirmado</span>
                                                <div class="booking-phone__btn">Ver detalhes</div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <figcaption class="carousel__caption"><?= htmlspecialchars($t['legenda']) ?></figcaption>
                        </figure>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="button" class="carousel__arrow carousel__arrow--prev" data-carousel-prev aria-label="Slide anterior">
                    <?= icon('chevron', 'icon-flip') ?>
                </button>
                <button type="button" class="carousel__arrow carousel__arrow--next" data-carousel-next aria-label="Próximo slide">
                    <?= icon('chevron') ?>
                </button>

                <div class="carousel__dots" role="tablist" aria-label="Selecionar tela" data-carousel-dots>
                    <?php foreach ($telas as $i => $t): ?>
                    <button type="button" class="carousel__dot<?= $i === 0 ? ' is-active' : '' ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Ir para a tela <?= htmlspecialchars($t['titulo']) ?>" data-carousel-dot="<?= $i ?>"></button>
                    <?php endforeach; ?>
                </div>

                <p class="visually-hidden" role="status" aria-live="polite" data-carousel-status></p>
            </div>
        </div>

        <!-- Lightbox -->
        <div class="lightbox" data-lightbox role="dialog" aria-modal="true" aria-label="Visualização ampliada da tela">
            <button type="button" class="lightbox__close" data-lightbox-close aria-label="Fechar visualização"><?= icon('x') ?></button>
            <div class="lightbox__content">
                <img class="lightbox__img" data-lightbox-img alt="" hidden>
                <div class="card" style="padding:var(--space-7); text-align:center;" data-lightbox-placeholder>
                    <p style="color:var(--white); font-weight:600;" data-lightbox-title>Tela do sistema</p>
                    <p style="margin-top:8px;">Prévia ampliada — capturas reais serão adicionadas em breve.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= AGENDA ======================= -->
    <section class="section">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Agenda</span>
                <h2 class="section-title">Uma agenda que trabalha por você.</h2>
                <p class="section-subtitle">Recorrência automática, bloqueios de horário e lista de espera — para a agenda nunca ficar bagunçada.</p>
            </div>

            <div class="agenda-grid">
                <div class="card agenda-card reveal">
                    <div class="agenda-card__icon"><?= icon('repeat') ?></div>
                    <h3>Recorrência automática</h3>
                    <p>Clientes fiéis voltam a agendar sozinhos, no intervalo que você configurar.</p>
                    <div class="recurrence-pills">
                        <span class="recurrence-pill">7 dias</span>
                        <span class="recurrence-pill">15 dias</span>
                        <span class="recurrence-pill">20 dias</span>
                        <span class="recurrence-pill">30 dias</span>
                    </div>
                </div>
                <div class="card agenda-card reveal">
                    <div class="agenda-card__icon"><?= icon('x') ?></div>
                    <h3>Bloqueios de horário</h3>
                    <p>Bloqueie horários de almoço, folgas ou imprevistos direto na agenda, sem afetar o resto do dia.</p>
                </div>
                <div class="card agenda-card reveal">
                    <div class="agenda-card__icon"><?= icon('clock') ?></div>
                    <h3>Lista de espera</h3>
                    <p>Quando um horário lota, o cliente entra na lista de espera e o barbeiro recebe um lembrete caso uma vaga abra.</p>
                </div>
                <div class="card agenda-card reveal">
                    <div class="agenda-card__icon"><?= icon('calendar') ?></div>
                    <h3>Confirmação e histórico</h3>
                    <p>Veja o histórico completo de cada horário: quem agendou, quando e qual serviço.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= FINANCEIRO ======================= -->
    <section class="section section--alt">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Financeiro</span>
                <h2 class="section-title">Saiba exatamente quanto entra e quanto sai.</h2>
                <p class="section-subtitle">Receitas, despesas, valores a receber e fiados, tudo atualizado a cada atendimento.</p>
            </div>

            <?php
                $fotoFinanceiro    = fotoReal(['financeiro.png', 'dashboard_fin.png']);
                $fotoFiados        = fotoReal(['fiados.png', 'receber_fiado.png']);
                $temFotoFinanceiro = $fotoFinanceiro !== null;
                $temFotoFiados     = $fotoFiados !== null;
            ?>
            <?php if ($temFotoFinanceiro || $temFotoFiados): ?>
                <div class="finance-gallery reveal">
                    <?php if ($temFotoFinanceiro): ?>
                    <figure class="finance-gallery__item">
                        <img
                            src="/<?= htmlspecialchars($fotoFinanceiro) ?>"
                            alt="Dashboard financeiro do BarbERP, com entradas, saídas e formas de pagamento"
                            loading="lazy"
                            decoding="async"
                        >
                        <figcaption>Dashboard financeiro</figcaption>
                    </figure>
                    <?php endif; ?>
                    <?php if ($temFotoFiados): ?>
                    <figure class="finance-gallery__item">
                        <img
                            src="/<?= htmlspecialchars($fotoFiados) ?>"
                            alt="Tela de contas a receber do BarbERP, com o recebimento de um fiado em aberto"
                            loading="lazy"
                            decoding="async"
                        >
                        <figcaption>Controle de Fiados</figcaption>
                    </figure>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="finance-grid reveal">
                    <div class="card finance-card">
                        <span class="finance-card__label">Receitas</span>
                        <span class="finance-card__value finance-card__value--positive">R$ 6.420,00</span>
                    </div>
                    <div class="card finance-card">
                        <span class="finance-card__label">Despesas</span>
                        <span class="finance-card__value finance-card__value--neutral">R$ 1.180,00</span>
                    </div>
                    <div class="card finance-card">
                        <span class="finance-card__label">A receber</span>
                        <span class="finance-card__value finance-card__value--warning">R$ 340,00</span>
                    </div>
                    <div class="card finance-card">
                        <span class="finance-card__label">Fiados</span>
                        <span class="finance-card__value finance-card__value--warning">R$ 105,00</span>
                    </div>
                    <div class="card finance-card">
                        <span class="finance-card__label">Saldo</span>
                        <span class="finance-card__value finance-card__value--positive">R$ 5.240,00</span>
                    </div>
                </div>
                <p class="text-center" style="margin-top:var(--space-5); font-size:var(--fs-xs);">Valores meramente ilustrativos, para representar as informações exibidas no painel financeiro.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ======================= DIFERENCIAIS ======================= -->
    <section class="section">
        <div class="container">
            <div class="section-header section-header--center reveal">
                <span class="section-eyebrow">Por que o BarbERP</span>
                <h2 class="section-title">O que faz diferença no dia a dia.</h2>
            </div>
            <div class="diff-grid">
                <?php foreach ($diferenciais as $d): ?>
                <div class="diff-card reveal">
                    <div class="diff-card__icon"><?= icon($d['icon']) ?></div>
                    <h3><?= htmlspecialchars($d['titulo']) ?></h3>
                    <p><?= htmlspecialchars($d['desc']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ======================= AGENDAMENTO ONLINE ======================= -->
    <section class="section section--alt">
        <div class="container">
            <div class="online-booking reveal">
                <div class="online-booking__content">
                    <span class="section-eyebrow">Agendamento online</span>
                    <h2 class="section-title">Seus clientes marcam o próprio horário.</h2>
                    <p class="section-subtitle">Compartilhe um link — no WhatsApp, no Instagram ou onde preferir — e o cliente escolhe o barbeiro e o horário sozinho, sem precisar ligar.</p>
                    <a href="#teste-gratuito" class="btn btn--primary" data-cta-plano="Gestão + Agendamento Online">Quero oferecer agendamento online</a>
                    <div class="online-booking__note">
                        <?= icon('shield') ?>
                        <span>Disponível a partir do plano Gestão + Agendamento Online.</span>
                    </div>
                </div>
                <div class="online-booking__visual">
                    <?php $fotoAgendaLink = fotoReal(['agendar_link01.png', 'agendar_link01.png']); ?>
                    <?php if ($fotoAgendaLink !== null): ?>
                        <img
                            src="/<?= htmlspecialchars($fotoAgendaLink) ?>"
                            alt="Tela de agendamento online do BarbERP, onde o cliente escolhe a data e o horário"
                            loading="lazy"
                            decoding="async"
                            class="online-booking__photo"
                        >
                    <?php else: ?>
                        <div class="booking-phone" style="max-width:260px;">
                            <div class="booking-phone__screen">
                                <div class="booking-phone__header">Escolha o horário</div>
                                <div class="booking-phone__slot">09:00</div>
                                <div class="booking-phone__slot booking-phone__slot--selected">09:30</div>
                                <div class="booking-phone__slot">10:00</div>
                                <div class="booking-phone__slot">10:30</div>
                                <div class="booking-phone__btn">Confirmar agendamento</div>
                            </div>
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
                <h2 class="section-title">Um plano para cada momento da sua barbearia.</h2>
                <p class="section-subtitle">Sem contrato de fidelidade e sem funcionalidades escondidas — o que está listado é o que você recebe.</p>
            </div>

            <div class="plans-grid">
                <?php foreach ($planos as $p): $emDesenvolvimento = !empty($p['emDesenvolvimento']); ?>
                <div class="card plan-card<?= $p['destaque'] ? ' plan-card--featured' : '' ?><?= $emDesenvolvimento ? ' plan-card--em-breve' : '' ?> reveal">
                    <?php if (!empty($p['badge'])): ?>
                        <span class="badge badge--highlight plan-card__badge"><?= htmlspecialchars($p['badge']) ?></span>
                    <?php elseif ($emDesenvolvimento): ?>
                        <span class="badge plan-card__badge">Em desenvolvimento</span>
                    <?php endif; ?>
                    <h3 class="plan-card__name"><?= htmlspecialchars($p['nome']) ?></h3>
                    <p class="plan-card__desc"><?= htmlspecialchars($p['desc']) ?></p>
                    <div class="plan-card__price">
                        <span class="plan-card__price-value">R$ <?= htmlspecialchars($p['preco']) ?></span>
                        <span class="plan-card__price-period">/mês</span>
                    </div>
                    <ul class="plan-card__list<?= count($p['itens']) > 5 ? ' plan-card__list--grid' : '' ?>">
                        <?php foreach ($p['itens'] as $item): ?>
                        <li><?= icon('check') ?><span><?= htmlspecialchars($item) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="plan-card__cta">
                        <?php if ($emDesenvolvimento): ?>
                            <button type="button" class="btn btn--secondary btn--block" disabled aria-disabled="true">Em breve</button>
                            <p class="plan-card__note">Este plano ainda está em desenvolvimento e não está disponível para contratação.</p>
                        <?php else: ?>
                            <a href="#teste-gratuito" class="btn <?= $p['destaque'] ? 'btn--primary' : 'btn--secondary' ?> btn--block" data-cta-plano="<?= htmlspecialchars($p['nome']) ?>">Quero este plano</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="compare-wrap reveal" style="margin-top:var(--space-8);">
                <table class="compare-table">
                    <caption>Comparativo detalhado entre os planos — role para o lado no celular.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Funcionalidade</th>
                            <th scope="col">Gestão</th>
                            <th scope="col">Gestão + Online</th>
                            <th scope="col">Gestão + Online + WhatsApp<br><span class="badge" style="font-size:10px; margin-top:4px;">Em desenvolvimento</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparativo as $linha): [$nomeFn, $p1, $p2, $p3] = $linha; ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($nomeFn) ?></th>
                            <td><?php if ($p1): ?><span class="compare-yes" aria-label="Incluso">✓</span><?php else: ?><span class="compare-no" aria-label="Não incluso">—</span><?php endif; ?></td>
                            <td><?php if ($p2): ?><span class="compare-yes" aria-label="Incluso">✓</span><?php else: ?><span class="compare-no" aria-label="Não incluso">—</span><?php endif; ?></td>
                            <td><?php if ($p3): ?><span class="compare-yes" aria-label="Incluso">✓</span><?php else: ?><span class="compare-no" aria-label="Não incluso">—</span><?php endif; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ======================= TESTE GRATUITO ======================= -->
    <section class="section section--alt" id="teste-gratuito">
        <div class="container lead-section">
            <div class="lead-form-col reveal">
                <span class="section-eyebrow">Teste gratuito</span>
                <h2 class="section-title">Experimente o BarbERP na sua barbearia.</h2>
                <p class="section-subtitle" style="margin-bottom:var(--space-6);">Preencha os dados abaixo — nossa equipe entra em contato para liberar seu acesso.</p>

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
                    <h3 style="margin-bottom:var(--space-4);">O que acontece depois do envio?</h3>
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
                <h2 class="section-title">Ainda com dúvidas?</h2>
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
            <h2 class="cta-final__title">Sua barbearia merece uma gestão mais organizada.</h2>
            <div class="cta-final__actions">
                <a href="#teste-gratuito" class="btn btn--primary btn--lg">Quero testar gratuitamente</a>
                <a href="#planos" class="btn btn--secondary btn--lg">Ver planos</a>
            </div>
        </div>
    </section>

</main>

<!-- ======================= FOOTER ======================= -->
<footer class="footer">
    <div class="container">
        <div class="footer__top">
            <div class="footer__brand">
                <span class="logo__mark">BARB<span>ERP</span></span>
                <p class="footer__tagline">Uma solução da DAK Soluções Digitais.</p>
            </div>

            <div class="footer__col">
                <h3 class="footer__col-title">Produto</h3>
                <nav class="footer__links" aria-label="Links do produto">
                    <a href="#funcionalidades">Funcionalidades</a>
                    <a href="#demonstracao">Demonstração</a>
                    <a href="#planos">Planos</a>
                </nav>
            </div>

            <div class="footer__col">
                <h3 class="footer__col-title">Suporte</h3>
                <nav class="footer__links" aria-label="Links de suporte">
                    <a href="#faq">Perguntas frequentes</a>
                    <a href="#teste-gratuito">Teste gratuito</a>
                </nav>
            </div>

            <div class="footer__col">
                <h3 class="footer__col-title">BarbERP</h3>
                <nav class="footer__links" aria-label="Links institucionais">
                    <a href="#inicio">Início</a>
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
<script src="<?= htmlspecialchars(versaoAsset('assets/js/carousel.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/faq.js')) ?>" defer></script>
<script src="<?= htmlspecialchars(versaoAsset('assets/js/form.js')) ?>" defer></script>
</body>
</html>