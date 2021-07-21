# CRUD de Desenvolvedores

## Instalação
* Este projeto usa containers Docker e foi testado com Docker Engine 20.10.5 e Compose 1.29.
* Certifique-se de encerrar aplicações que estejam usando as portas 3306, 8000 e 8080.
* Abra um terminal na raiz deste projeto e execute "docker-compose up".
* Após o fim da composição, acesse a "http://localhost:8080" em qualquer navegador web (Chrome).
* Pronto, o CRUD estará disponível e funcional.


## Funcionalidades
* Os desenvolvedores são mostrados em uma lista vertical, com botões para ações comuns.
* É possível ordenar por qualquer das propriedades de um desenvolvedor, em ordem crescente ou descrescente.
* É possível buscar algum termo ou número presente em qualquer das propriedades de um item (ex: "masc").
* Existe controle de paginação dos itens listados, podendo inclusive mudar o limite de itens por página.
* Algumas ações fornecem feedback através de alertas de texto flutuantes.


## Frontend
No frontend foi utilizado o framework VueJS na versão 3.
O estilo é próprio e oferece uma alternativa às tendências atuais de design material ou flat.


## Backend API
No backend foi utilizado o framework Lumen na versão 8.
Há cobertura por testes unitários para verificar integridade do model e das ações do controller.
