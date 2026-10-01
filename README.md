# BarbERP — Landing Page

Landing page comercial do BarbERP (produto da DAK Soluções Digitais). Projeto
standalone, independente do sistema BarbERP em si — feito para ser hospedado
em outro serviço.

**Versão 2.0 (redesign):** página reestruturada do zero para apresentar
exclusivamente a V1.0 real do sistema — curta, direta, com aparência de SaaS
empresarial (sem gradientes, sem carrossel, sem recursos futuros). Ver
"Redesign — o que mudou" no final deste documento.

## Estrutura

```
barberp-landing/
├── index.php                    Página principal (9 seções)
├── config.php                   Conexão PDO com o banco de leads
├── processar_lead.php           Endpoint que recebe o formulário (JSON)
├── criar_tabela_leads.sql       Script para criar a tabela Lead
├── assets/
│   ├── css/style.css            Design system completo (tokens, componentes)
│   ├── js/
│   │   ├── main.js              Header, menu mobile, fade-in ao rolar
│   │   ├── faq.js               Accordion do FAQ
│   │   └── form.js              Validação e envio do formulário
│   └── images/barberp/          Capturas reais do sistema (ver abaixo)
└── README.md
```

## 1. Banco de dados

Este projeto usa um banco **separado** do banco principal do BarbERP —
`leads_app_barber`, no mesmo servidor (`comandai_barbearia-padrao-bd:3306`).
Ele só guarda os contatos que preenchem o formulário "Teste gratuito".

Antes de rodar `criar_tabela_leads.sql`, o usuário do banco (o mesmo valor
de `DB_USER`, ex.: `barbearia_user`) precisa ter permissão nesse banco —
por padrão, um usuário do MySQL só enxerga o(s) banco(s) para o(s) qual(is)
foi explicitamente liberado. Se o formulário responder com "Não foi
possível registrar sua solicitação agora" e o log do servidor mostrar
`Access denied for user '...' to database 'leads_app_barber'`, é exatamente
isso: falta liberar o acesso. Rode, conectado como um usuário com privilégio
de administrador (root ou o usuário admin do painel do banco — **não** dá
para rodar isso logado como o próprio `barbearia_user`, pois é ele quem
está sem permissão):

```sql
CREATE DATABASE IF NOT EXISTS leads_app_barber CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON leads_app_barber.* TO 'barbearia_user'@'%';
FLUSH PRIVILEGES;
```

(troque `'barbearia_user'@'%'` pelo usuário/host reais, se forem diferentes)

Só depois disso, rode uma única vez, no banco `leads_app_barber`:

```sql
-- via phpMyAdmin: selecione o banco leads_app_barber -> aba SQL -> cole e
-- execute o conteúdo de criar_tabela_leads.sql
```

`Lead` vem entre crase (`` `Lead` ``) tanto no script quanto no
`processar_lead.php` — a partir do MySQL 8.0, a palavra `LEAD` virou
reservada (é o nome de uma função de janela, `LEAD()`), então usá-la como
nome de tabela sem crase dá erro de sintaxe (`#1064`) ao criar a tabela.

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

Enquanto uma captura real não existe (hoje é o caso só da tela "Agenda"),
a seção "O sistema" mostra uma representação estilizada em CSS/HTML no
lugar dela — não é uma captura falsa, é só um placeholder elegante, sem
nenhum aviso de "em breve" na tela. `index.php` verifica sozinho, com a
função `fotoReal()`, se algum dos nomes de arquivo aceitos já foi
adicionado e troca o mockup pela foto real automaticamente — **não
precisa editar nenhum código**, só colocar o arquivo em
`assets/images/barberp/` com um destes nomes:

```
assets/images/barberp/dashboard.png        (ou dashboard_principal.png)  → Hero (dashboard)
assets/images/barberp/agendamentos.png     (ou agenda.png)               → seção "O sistema" — tela "Agenda"
assets/images/barberp/financeiro.png       (ou dashboard_fin.png)        → seção "O sistema" + seção "Financeiro"
assets/images/barberp/fiados.png           (ou receber_fiado.png)        → seção "Financeiro" (Controle de fiados)
assets/images/barberp/agenda-online01.png  (ou agenda_link01.png)        → seção "O sistema" + seção "Agendamento online"
```

Cada linha aceita **qualquer um** dos nomes indicados — não precisa
renomear o arquivo antes de colocá-lo na pasta, o `fotoReal()` procura
pelos nomes possíveis em ordem e usa o primeiro que encontrar. Hoje só
falta uma captura real: `agendamentos.png` (tela de agenda/horários) —
assim que ela for adicionada, o mockup em CSS é substituído sozinho, sem
precisar mexer em nada.

## 4. Deploy

Qualquer hospedagem PHP 8+ com PDO/MySQL habilitado funciona (o mesmo tipo
de stack usado no BarbERP principal). Não há dependências externas (sem
Composer, sem build step) — é só subir os arquivos e configurar as
variáveis de ambiente acima.

`index.php` já acrescenta sozinho `?v=<data de modificação>` no link do
`style.css` e nos `<script>` (função `versaoAsset()`), então um ajuste de
CSS/JS não fica "preso" em cache no navegador de quem já visitou o site —
cada deploy novo já força a versão mais recente sem precisar de Ctrl+Shift+R.
Se mesmo assim uma mudança visual não aparecer depois do deploy, o motivo
mais comum é o deploy não ter realmente subido o arquivo atualizado (por
exemplo, substituir só o `index.php` sem substituir também
`assets/css/style.css`) — vale conferir se todos os arquivos da pasta
`assets/` também foram atualizados no servidor, não só o `index.php`.

### Rotas inválidas / arquivos sensíveis

O projeto inclui `.nixpacks/assets/nginx.template.conf`, que substitui o
template padrão do Nixpacks (usado automaticamente pelo EasyPanel ao
detectar PHP). Duas coisas são garantidas por esse arquivo, sem precisar
configurar nada manualmente no EasyPanel:

- **Qualquer URL que não exista** (ex.: `barberp.daksolucoes.com.br/acess`)
  cai de volta para o `index.php` em vez de gerar um erro cru do nginx
  (404 sem estilo nenhum). Isso também evita que alguém descubra a
  estrutura de pastas do projeto tentando URLs aleatórias.
- **Acesso HTTP direto fica bloqueado** para `config.php` (credenciais do
  banco), qualquer `.sql` (`criar_tabela_leads.sql`), `.md` (este próprio
  README) e `.env`. Esses arquivos continuam funcionando normalmente
  quando usados internamente pelo PHP (`require`/`include`) — o bloqueio
  é só contra alguém abrir o link diretamente no navegador.

Se o deploy for feito em uma hospedagem que **não** usa Nixpacks (ex.:
Apache), esse arquivo é ignorado e o equivalente precisa ser feito via
`.htaccess`:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]

<FilesMatch "\.(sql|md|env)$">
    Require all denied
</FilesMatch>
<Files "config.php">
    Require all denied
</Files>
```

Depois do deploy, para conferir que pegou: abrir
`barberp.daksolucoes.com.br/qualquercoisa` deve mostrar a landing page
normal (não um erro), e `barberp.daksolucoes.com.br/config.php` ou
`/criar_tabela_leads.sql` deve dar "não encontrado".

## 5. O que já foi testado

- Todas as validações do formulário (nome, e-mail, telefone, honeypot
  anti-spam) testadas localmente, incluindo o caminho de erro sem
  credenciais de banco configuradas — comportamento idêntico ao de antes
  do redesign, nada no formulário foi tocado.
- Responsividade sem overflow horizontal em 375/390/430/768/1024/1440px.
- Acessibilidade verificada com axe-core (0 violações WCAG 2 A/AA): labels
  em todos os campos, foco visível, navegação por teclado no FAQ/menu
  mobile, `aria-*` no accordion.
- SEO básico: título, meta description, Open Graph, H1 único, hierarquia
  H2/H3 sem saltos.
- Funciona sem JavaScript: o formulário envia via POST tradicional, o FAQ
  mostra todas as respostas abertas e o menu mobile aparece sempre visível
  (ver bloco `<noscript>` em `index.php`).

## 5b. Seção "Próximas atualizações"

Logo abaixo dos planos/comparativo (antes do formulário de teste gratuito)
há um card único e discreto — fundo neutro, borda tracejada — com recursos
que **ainda não existem na V1.0** (integração com WhatsApp, estoque,
comissões, produtos e vendas, relatórios avançados, fidelização e
multiunidade). Cada item tem um status ("Em desenvolvimento" ou
"Planejado"), nunca uma data ou número de versão. Essa seção:

- **não pertence a nenhum plano** — os dois planos (`$planos` em
  `index.php`) continuam com exatamente os mesmos itens e preços de antes;
- **não é clicável/vendável** — não tem CTA próprio, só o aviso final de
  que são recursos futuros;
- fica em `$atualizacoesFuturas` no topo de `index.php`, caso precise
  adicionar, remover ou reordenar algum item depois.

## 6. Redesign — o que mudou (versão 2.0)

Reestruturação visual completa da landing, para apresentar só a V1.0 real
do BarbERP:

- **Removido:** carrossel de telas (`carousel.js` e o lightbox de
  ampliação), a seção gigantesca de 10 funcionalidades, o plano de
  WhatsApp (R$ 139,90 / R$ 219,90) e toda menção a "em desenvolvimento",
  WhatsApp automático, confirmação automática ou qualquer recurso que
  ainda não existe no sistema. A página caiu de ~17 para 9 seções.
- **Novo visual:** tema claro (branco/cinza claro, azul institucional,
  verde só em confirmações), tipografia Poppins com hierarquia mais forte,
  screenshots reais emolduradas como "janela de navegador", muito mais
  espaço em branco — inspirado na simplicidade de SaaS como Linear/Stripe/
  Vercel, sem gradiente, sem glassmorphism, sem neon.
- **Planos atualizados:** só os 2 planos realmente disponíveis — Gestão
  (R$ 69,90) e Gestão + Agendamento Online (R$ 99,90, com selo
  "Recomendado"). Comparativo simplificado para 9 linhas, com versão em
  cards no mobile (sem tabela horizontal quebrada).
- **Formulário "Teste gratuito": mantido 100% intacto** — mesmo HTML
  (nomes/ids dos campos, honeypot, `data-*`), mesmo `form.js`, mesmo
  `processar_lead.php`, mesma validação e mesmo endpoint. Só a moldura ao
  redor (cores, espaçamento, título) mudou.
