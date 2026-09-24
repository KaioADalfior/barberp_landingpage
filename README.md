# BarbERP — Landing Page

Landing page comercial do BarbERP (produto da DAK Soluções Digitais). Projeto
standalone, independente do sistema BarbERP em si — feito para ser hospedado
em outro serviço.

## Estrutura

```
barberp-landing/
├── index.php                    Página principal (todas as 12 seções)
├── config.php                   Conexão PDO com o banco de leads
├── processar_lead.php           Endpoint que recebe o formulário (JSON)
├── criar_tabela_leads.sql       Script para criar a tabela Lead
├── assets/
│   ├── css/style.css            Design system completo (tokens, componentes)
│   ├── js/
│   │   ├── main.js              Header, menu mobile, fade-in ao rolar
│   │   ├── carousel.js          Carrossel de telas (setas, swipe, lightbox)
│   │   ├── faq.js               Accordion do FAQ
│   │   └── form.js              Validação e envio do formulário
│   └── images/barberp/          Capturas reais do sistema (ver abaixo)
└── README.md
```

## 1. Banco de dados

Este projeto usa um banco **separado** do banco principal do BarbERP —
`leads_app_barber`, no mesmo servidor (`comandai_barbearia-padrao-bd:3306`).
Ele só guarda os contatos que preenchem o formulário "Teste gratuito".

Rode uma única vez, no banco `leads_app_barber`:

```sql
-- via phpMyAdmin: selecione o banco leads_app_barber -> aba SQL -> cole e
-- execute o conteúdo de criar_tabela_leads.sql
```

## 2. Variáveis de ambiente

Configure no painel do serviço onde a landing page for hospedada (ex.:
EasyPanel):

| Variável   | Obrigatória | Padrão                          |
|------------|:-----------:|----------------------------------|
| `DB_HOST`  | não         | `comandai_barbearia-padrao-bd`  |
| `DB_PORT`  | não         | `3306`                           |
| `DB_NAME`  | não         | `leads_app_barber`               |
| `DB_USER`  | **sim**     | —                                 |
| `DB_PASS`  | **sim**     | —                                 |

Sem `DB_USER`/`DB_PASS` definidos, `processar_lead.php` responde com um erro
genérico ao visitante (nunca expõe credenciais) e registra o motivo real nos
logs do servidor (`error_log`).

## 3. Imagens reais do sistema

Enquanto as capturas reais não são adicionadas, o carrossel de telas e o
mockup do hero mostram uma representação estilizada em CSS/HTML (não são
capturas falsas — são só um placeholder elegante). Para trocar pelas
imagens reais, adicione os arquivos em:

```
assets/images/barberp/dashboard.png
assets/images/barberp/agendamentos.png
assets/images/barberp/financeiro.png
assets/images/barberp/fiados.png
assets/images/barberp/agenda-online.png
```

e, em `index.php`, na seção "CARROSSEL DE TELAS", troque o bloco
`<?php if ($t['mockup'] === 'inicio'): ?> ... <?php endif; ?>` de cada slide
por uma tag simples:

```php
<img src="/<?= htmlspecialchars($t['imagem']) ?>" alt="<?= htmlspecialchars($t['legenda']) ?>">
```

(o array `$telas`, no topo do arquivo, já traz o caminho de cada imagem em
`imagem` e o texto da legenda em `legenda`). O mesmo vale para o mockup do
hero, no início da seção `<section class="hero" ...>`.

## 4. Deploy

Qualquer hospedagem PHP 8+ com PDO/MySQL habilitado funciona (o mesmo tipo
de stack usado no BarbERP principal). Não há dependências externas (sem
Composer, sem build step) — é só subir os arquivos e configurar as
variáveis de ambiente acima.

## 5. O que já foi testado

- Todas as validações do formulário (nome, e-mail, telefone, honeypot
  anti-spam) testadas localmente contra `leads_app_barber`, incluindo o
  caminho de erro sem credenciais configuradas.
- Responsividade sem overflow horizontal em 360/390/430/768/1024/1280/1440px.
- Acessibilidade verificada com axe-core (0 violações WCAG 2 A/AA):
  labels em todos os campos, foco visível, navegação por teclado no
  carrossel/FAQ/menu mobile, `aria-*` no accordion e no carrossel.
- SEO básico: título, meta description, Open Graph, H1 único, hierarquia
  H2/H3 sem saltos.
- Funciona sem JavaScript: o formulário envia via POST tradicional, o FAQ
  mostra todas as respostas abertas e o menu mobile aparece sempre visível
  (ver bloco `<noscript>` em `index.php`).
