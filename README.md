# API REST - Cadastro de Usuários (PHP + SQLite)

Este é o projeto prático que desenvolvi para a minha prova aula. 
A ideia foi criar uma API REST funcional e enxuta para gerenciar usuários, aplicando os conceitos de arquitetura cliente-servidor, métodos HTTP e integração com banco de dados.

TECNOLOGIAS UTILIZADAS
- PHP 8+ (rodando com o servidor embutido)
- SQLite (escolhi por ser um banco de dados em arquivo. Assim, o projeto roda sem precisar instalar ou configurar um servidor MySQL, o que facilita muito os testes).
- PDO com Prepared Statements (para garantir a segurança das queries e evitar SQL Injection).

COMO EXECUTAR O PROJETO
Para testar a API, você só precisa ter o PHP instalado na máquina (com a extensão pdo_sqlite ativada no php.ini).

1. Abra o terminal na pasta do projeto e inicie o servidor local. Eu costumo usar a porta 3000:
php -S localhost:3000

2. O arquivo do banco de dados (database.sqlite) e a tabela de usuários serão criados automaticamente na primeira vez que você fizer uma requisição.

ROTAS E FUNCIONALIDADES (ENDPOINTS)
A URL base para os testes é http://localhost:3000/usuarios.

- GET /usuarios — Lista todos os usuários cadastrados. Retorna status 200 OK.
- POST /usuarios — Cria um novo usuário. Retorna status 201 Created.
- PUT /usuarios — Atualiza os dados de um usuário existente. Retorna status 200 OK.
- DELETE /usuarios — Remove um usuário do banco. Retorna status 204 No Content.

Exemplo de JSON para o POST:
{
  "nome": "Júlio Pinheiro",
  "email": "julio@email.com"
}

BOAS PRÁTICAS E VERSIONAMENTO
Durante o desenvolvimento, segui o fluxo padrão de mercado com Git:
- Criei uma branch específica para a feature (feature/usuarios).
- Fiz os commits de forma atômica (cada commit com uma mensagem clara do que foi feito).
- O código passou por revisão (Pull Request) antes de ir para a branch principal.
- Por fim, fiz o merge e marquei a versão com uma tag (v1.0.0).