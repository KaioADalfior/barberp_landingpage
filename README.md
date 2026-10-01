# BarbERP — Landing Page

Landing page comercial do BarbERP (produto da DAK Soluções Digitais). Projeto
standalone, independente do sistema BarbERP em si — feito para ser hospedado
em outro serviço.

**Versão 4.0 (reformulação visual completa):** carrossel de produto com
lightbox, nova direção visual (estilo SaaS/B2B), copy revisada em quase
todas as seções e os mesmos 3 planos (Básico/Pro/Enterprise) da versão 3.0.
Ver "Reformulação — o que mudou (versão 4.0)" no final deste documento. As
versões 3.0 e 2.0 continuam descritas logo abaixo dela, para referência
histórica.

## Estrutura

```
barberp-landing/
├── index.php                    Página principal (13 seções)
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
assets/images/barberp/financeiro.png       (ou dashboard_fin.png)        → seção "O sistema" — "Dashboard financeiro"
assets/images/barberp/fiados.png           (ou receber_fiado.png)        → seção "O sistema" — "Controle de fiados"
assets/images/barberp/agenda-online01.png  (ou agenda_link01.png)        → seção "O sistema" — "Agendamento online"
```

Cada linha aceita **qualquer um** dos nomes indicados — não precisa
renomear o arquivo antes de colocá-lo na pasta, o `fotoReal()` procura
pelos nomes possíveis em ordem e usa o primeiro que encontrar. Hoje só
falta uma captura real: `agendamentos.png` (tela de agenda/horários) —
assim que ela for adicionada, o mockup em CSS é substituído sozinho, sem
precisar mexer em nada.

### Carrossel + lightbox (mecanismo pronto, imagens pendentes)

A partir da versão 4.0 o carrossel e o lightbox **já estão totalmente
implementados** (`assets/js/carousel.js`, `assets/js/lightbox.js`,
estilos em `style.css`) e funcionam com dados de teste. O que falta são
só os arquivos de imagem em si — `index.php` procura por **nome exato**
(sem aceitar apelidos/variações, via `fotoRealExata()`) e, quando não
encontra, mostra um placeholder neutro (nunca uma captura falsa, nunca um
texto de "em breve") no lugar da imagem.

Nomes exatos aceitos, todos dentro de `assets/images/barberp/`:

```
agenda.png                → Hero (imagem principal)
dashboardprincipal.png    → Carrossel, slide 1
comoagendar.png           → Carrossel, slide 2
agendar.png               → Carrossel, slide 3
alteraragendamento.png    → Carrossel, slide 4
finalizarservico.png      → Carrossel, slide 5
receberfiado.png          → Carrossel, slide 6
dashfinanceiro.png        → Carrossel, slide 7
cliente01.png             → Carrossel, slide 8
cliente02.png             → Carrossel, slide 9
cliente03.png             → Carrossel, slide 10
tresjuntos.png            → Seção "Acesse de onde estiver" (opcional)
```

**Nenhum desses 12 arquivos existe hoje no projeto** (confirmado por
busca exaustiva). Assim que forem adicionados com esses nomes exatos,
cada imagem aparece automaticamente no lugar certo — não é preciso mexer
em nenhum código. Os 4 arquivos reais que já existiam antes da v4.0
(`dashboard.png`, `financeiro.png`, `fiados.png`, `agenda_link01.png`)
continuam na pasta mas **não são usados em nenhuma seção da v4.0** — por
instrução explícita, uma imagem existente nunca é usada no lugar de outra
que tenha nome diferente do pedido, então eles ficam disponíveis caso
sejam renomeados/reaproveitados manualmente no futuro, ou podem ser
removidos se não forem mais necessários.

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

## 7. Reformulação — o que mudou (versão 3.0)

Nova estrutura de 13 seções, substituindo a versão 2.0 anterior:

Header → Hero → Frase de impacto → Três motivos → Recursos → O sistema →
Acesse de onde estiver → Avaliações → Planos → Próximas atualizações → FAQ
→ Teste gratuito → Contato → CTA final → Footer.

- **Novo:** "Frase de impacto" (seção curta logo após o Hero), "Três
  motivos para parar de adiar a organização" (3 blocos numerados, sem
  estatísticas nem percentuais inventados), "Acesse de onde estiver"
  (acesso via navegador, sem afirmar suporte offline), "Avaliações"
  (estrutura visual preparada para depoimentos futuros — sem nomes,
  barbearias ou estrelas inventados, já que nenhuma avaliação real existe
  ainda no projeto) e "Contato" (reaproveita os links reais de WhatsApp e
  Instagram já usados no rodapé).
- **Planos alterados para 3 (antes eram 2):** Básico (R$ 68,90), Pro
  (R$ 98,90, selo "Recomendado") e **Enterprise (R$ 129,90)** — este
  último claramente marcado como "Em desenvolvimento", com botão
  desativado (não é possível contratá-lo) porque os recursos que ele lista
  (WhatsApp, lembretes) ainda não existem no sistema. O comparativo de
  planos ganhou 2 linhas novas ("WhatsApp" e "Lembretes"), marcadas
  "Em desenvolvimento*" apenas na coluna Enterprise, com nota de rodapé
  explicando que não têm data de lançamento confirmada.
- **"Próximas atualizações" reduzida de 7 para 5 itens:** Integração com
  WhatsApp, Lembretes automáticos (novo), Controle de estoque, Controle de
  comissões e Relatórios avançados. "Produtos e vendas", "Fidelização de
  clientes" e "Multiunidade" saíram dessa lista pública (seguem fora da
  V1.0, apenas não aparecem mais nesta seção).
- **FAQ reduzido de 7 para 6 perguntas**, com foco em acesso, agendamento
  online (agora no plano Pro), fiados e no período de teste de 30 dias.
- **Teste gratuito:** reposicionado para depois do FAQ (antes vinha antes),
  com novo título ("30 dias grátis. Sem cartão. Sem compromisso.") e novo
  texto de apoio. O formulário em si (campos, validação, endpoint,
  `form.js`) **não foi tocado**.
- **Seções antigas "Agendamento online" e "Financeiro + Fiados"** foram
  removidas como seções dedicadas — o conteúdo real que elas mostravam
  (agendamento online, financeiro, fiados) continua visível na seção "O
  sistema", agora com 4 capturas em vez de 3 (a aba de fiados ganhou seu
  próprio card ali).
- **Carrossel + lightbox com 10 imagens:** pedidos pela especificação mais
  recente, mas **não implementados** nesta rodada — nenhum dos 11 arquivos
  de imagem com nome exato pedido existe no projeto (ver seção 3 acima).
  A seção "O sistema" atual ocupa esse lugar na nova ordem até que as
  imagens sejam adicionadas.

## 8. Reformulação — o que mudou (versão 4.0)

Reformulação visual completa, construída em cima da estrutura da versão
3.0 (mesma ordem de 13 seções: Header → Hero → Frase de impacto → Três
motivos → O sistema (carrossel) → Acesse de onde estiver → Avaliações →
Planos → Futuras atualizações → FAQ → Teste gratuito → Contato → CTA
final → Footer).

- **Carrossel de produto implementado do zero** (`assets/js/carousel.js`):
  10 slides, imagem grande à esquerda + texto à direita no desktop,
  imagem em cima + texto embaixo no mobile, com setas, indicadores (dots),
  navegação por teclado (setas ←/→ quando o carrossel está visível) e
  arraste por toque (swipe) no celular. Funciona sem JavaScript: o CSS
  dentro do `<noscript>` empilha todas as slides visíveis.
- **Lightbox implementado do zero** (`assets/js/lightbox.js`): clique em
  qualquer imagem do Hero ou do carrossel abre um modal (~90% da tela,
  fundo escuro), que fecha com o botão "X", clicando fora da imagem ou com
  Esc. Nunca navega para outra página nem altera os arquivos originais.
- **Busca de imagem por nome exato** (`fotoRealExata()`, nova função):
  diferente da antiga `fotoReal()` (que aceitava apelidos), essa versão
  não aceita variações de nome nem substitui uma imagem por outra — se o
  arquivo pedido não existir, aparece um placeholder neutro (nunca uma
  captura falsa). Ver seção 3 acima para a lista completa dos 12 arquivos
  ainda pendentes.
- **Copy revisada** em: Hero (texto de apoio abaixo dos botões), "Frase de
  impacto", "Três motivos" (texto definitivo, sem estatísticas
  inventadas), "Acesse de onde estiver" (ícones de computador/notebook/
  tablet/celular, sem afirmar que funciona offline), "Avaliações" (1
  depoimento real — Alex Monteiro, sobre trocar a agenda manual pelos
  agendamentos recorrentes de 15 e 20 dias — mais 2 cards com aviso
  honesto "Depoimento real de cliente será inserido aqui.", sem nomes,
  notas ou barbearias inventadas), comparativo de planos (rótulos mais
  curtos), "Futuras atualizações" (texto corrido mais simples, sem grid de
  cards) e FAQ (perguntas reformuladas, mesmas 6 do v3.0).
- **Seções antigas removidas:** o grid estático de 4 funcionalidades, o
  grid de imagens "Produto", a seção dedicada "Agendamento Online" e a
  seção "Financeiro + Fiados" — todas substituídas pelo novo carrossel
  único, que concentra as 10 funcionalidades em um só componente.
- **Design:** tipografia Poppins mantida, paleta ajustada para tons mais
  institucionais (azul/azul-escuro/branco/cinza claro, verde só em estados
  positivos), sem gradientes, glassmorphism, neon ou sombras pesadas.
- **Planos:** sem mudança de preços/estrutura em relação à v3.0 (Básico
  R$ 68,90 / Pro R$ 98,90 "Recomendado" / Enterprise R$ 129,90 "Em
  desenvolvimento", com botão desativado).
- **Formulário "Teste gratuito": não foi tocado** — mesmos campos, ids,
  `name`s, validação (`form.js`), endpoint (`processar_lead.php`) e banco
  (`config.php`, `criar_tabela_leads.sql`) de antes.
