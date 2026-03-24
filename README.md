# Sitema-de-Calamidades-Publicas-ConectaSolid-ria-
Guia Técnico e Explicação do Código: Sistema "ConectaSolidária"
Este documento detalha a arquitetura e a lógica de programação por trás do sistema web da Barbearia Etc. O foco é explicar como HTML, CSS e JavaScript interagem para criar as funcionalidades do site.

1. Arquitetura e Estilização Global
O sistema segue o padrão MVC (Model-View-Controller) adaptado para um contexto estático.

Visualizações: Pastas HTML ( /views) contendo uma estrutura.
Público: Pasta de recursos ( /public) contendo CSS e Imagens.
O Arquivo style.css(O Motor Visual)
Todo o design é centralizado em um único arquivo para consistência.

Responsividade sem Media Queries: Na classe .service-liste .gallery-grid, utilizamos uma técnica avançada de CSS Grid:
grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
Explicação: Isso diz ao navegador: "Crie tantas colunas quanto couberem ( auto-fit). Cada coluna deve ter no mínimo 280px. Se sobrar espaço, divida-o igualmente ( 1fr)." Isso faz com que o layout se adapte a celulares e PCs automaticamente.
Técnica "Visual Hidden": Para atender à exigência de usar apenas o logotipo no cabeçalho sem dificuldades a semântica (SEO) da página, criamos a classe .visual-hidden:
.visual-hidden { display: none; }
Isso mantém a tag <h1>no código (importante para a estrutura), mas a esconde visualmente.
2. Fluxo de Login e Cadastro
index.html(Conecte-se)
A "mágica" de login simulado acontece no formulário HTML.

Código Chave:
<form action="views/home.html" method="GET">
Explicação: O atributo actiondefine para onde o navegador deve ir quando o formulário for enviado. Ao apontar para views/home.html, criamos a ilusão de um login bem-sucedido sem a necessidade de um servidor real.
views/cadastro.htmlecadastro_sucesso.html
Aqui apresentamos a passagem de dados via URL .

Envio: O formulário de cadastro usa method="GET". Isso faz com que os dados digitados (ex: nome="João") sejam aplicados na URL da próxima página: ...html?nome=Joao&email=....
Recepção (JavaScript): Na página de sucesso, use JavaScript para ler essa URL:
const params = new URLSearchParams(window.location.search);
document.getElementById('cad-nome').innerText = params.get('nome');
Explicação: URLSearchParams é uma ferramenta nativa do navegador que "desempacota" uma URL. O script pega o valor do campo 'nome' e injeta na tag HTML com id cad-nome, personalizando a mensagem.
3. Funcionalidades Principais
views/agendamento.html(Formulários Avançados)
Esta página demonstra o uso correto dos tipos de entrada do HTML5 para melhorar a experiência em dispositivos móveis.

Entradas Específicas:
<input type="date">: Abre o calendário nativo do celular.
<input type="time">: Abra o seletor de hora.
<input type="tel">: Abra o teclado numérico no celular.
Selecione com Valores:
<option value="corte">Corte de Cabelo (R$ 40,00)</option>
Note que o value("corte") é um código simples, diferente do texto que o usuário vê. Isso facilita o processamento dos dados depois.
views/sucesso.html(Lógica de Dados)
Esta página contém a lógica mais complexa do sistema. Ela recebe os dados "crus" do agendamento e os formatos.

Mapeamento de Dados (Dicionário): Como o formulário envia apenas códigos como "corte" ou "pezinho", usamos um objeto JavaScript para traduzir isso:
const servicosMap = {
    'corte': 'Corte de Cabelo (R$ 40,00)',
    'pezinho': 'Pezinho (R$ 15,00)',
    // ...
};
Tratamento de Dados: Dados recebidos de formulários podem ter problemas de fuso horário. O script inclui uma correção técnica:
dataObj.setMinutes(dataObj.getMinutes() + dataObj.getTimezoneOffset());
Isso garante que o dia exibido na confirmação seja exatamente o dia que o usuário selecionou, sem voltar um dia devido ao fuso horário UTC.
views/galeria.htmle views/detalhe.html(Sistema Dinâmico)
Para evitar criar 8 arquivos HTML (um para cada foto), criamos um sistema de Template Único .

O Link Inteligente (Galeria):
<a href="detalhe.html?id=1">...</a>
Cada foto envia um número identificador ( id) diferente.
O "Banco de Dados" Local (Detalhe): Dentro do JavaScript da página de detalhes, existe um objeto const portfolioque guarda as informações de todos os cortes.
A Renderização: O script lê o ID da URL ( params.get('id')), busca as informações correspondentes no objeto portfolioe atualiza a imagem ( src) e os textos ( innerText) da página em tempo real. Benefício: Isso torna o site extremamente leve e fácil de manter. Para mudar a descrição de um corte, basta editar o script, sem mexer no HTML.
4. Estrutura Semântica
Header/Nav: O uso da lista não ordenada ( <ul>) dentro da tag <nav>é o padrão semântico correto para menus, garantindo acessibilidade.
Links Ativos: A classe .activeé aplicada manualmente no HTML de cada página para dar feedback visual ao usuário de onde ele está.
