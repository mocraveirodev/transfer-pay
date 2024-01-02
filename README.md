# TransferPay

Aplicação feita em PHP com Laravel 10 para realização de transferências de dinheiro em Usuários e Lojistas.

## Como rodar o projeto:
- ### Precisa já ter instalado:
   * [Docker](https://www.docker.com/)
   * [Git](https://git-scm.com/)

```bash
git clone https://github.com/mocraveirodev/transfer-pay.git
cp .env.example .env
docker compose up --build -d
docker exec -it transfer-pay-app-1 bash
php artisan migrate 
```
- #### Como rodar os testes
``` bash 
php artisan test
```
- Para iniciar a fila de envio de notificações é necessário rodar o seguinte comando:
``` bash 
php artisan queue:work
```

## Detalhes do projeto:
- Os princípios do DDD (Domain Driven Design) foram utilizados, visando simplificar a execução das regras de negócio e processos complexos, com o intuito de distribuir responsabilidades em camadas distintas. Com isso, possibilitando escalabilidade, organização e com a linguagem ubíqua, aproxima o código dos requisitos. 
- Tentei usar Onion Architecture para trazer flexibilidade, sustentabilidade, capacidade de manutenção, testabilidade e portabilidade ao projeto, além de desacoplamento e separação de camadas.
- Foram usados princípios de SOLID.
- Utilizado filas para o serviço de notificação.
- Utilizei o conceito de Factories para separação dos processos para Usuários e Lojistas.
- Há Logs em algumas partes do código.