# Recebimento de medições

`POST http://142.93.194.124/Apidados` (sem prefixo `/api`, sem sessão ou CSRF).

## Configuração no servidor

Publique os arquivos deste projeto no servidor que atende o IP e configure o document root do servidor HTTP para a pasta `public` do Laravel, com encaminhamento das rotas para `public/index.php`.

No `.env` do servidor, configure o banco. Durante os testes, o endpoint está aberto, sem autenticação: não exige Bearer token nem configuração de `APIDADOS_TOKEN`.

Execute no diretório do projeto no servidor:

```sh
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan route:list --path=Apidados
```

A migration cria `medicoes`, contendo os cinco valores, o horário de recebimento e `idempotency_key` com índice único. Não exige execução ou localização geográfica. Confira as demais migrations pendentes antes de executá-las em uma instalação existente.

## Contrato

Cabeçalhos obrigatórios:

```text
Content-Type: application/json
Idempotency-Key: identificador-unico-da-medicao
```

```json
{"x":0.04,"y":0.0,"z":0.15,"umidade":64.0,"temperatura":25.0}
```

Os cinco campos são números JSON finitos; strings numéricas, booleanos e null são rejeitados. Umidade deve estar entre 0 e 100. A chave tem de 1 a 128 caracteres ASCII visíveis, sem espaços, e deve permanecer igual em todos os reenvios de uma medição. Cada nova medição precisa de uma chave diferente. O corpo não substitui o cabeçalho da chave.

- **201**: transação concluída, medição salva.
- **200**: chave já salva, sem nova inserção ou alteração dos valores originais (o reenvio também deve ter corpo válido).
- **422**: JSON, campos ou chave ausentes/inválidos.
- **415**: tipo de conteúdo diferente de `application/json`.
- **429**: limite da API existente, 60 requisições por minuto.
- **500**: falha de persistência; o cliente deve manter a medição pendente.

As respostas 200/201 são `{"sucesso":true,"mensagem":"Medição salva"}`. Erros nunca são confirmados com 2xx. O índice único também impede duplicação em reenvios simultâneos.

Exemplo no Python (o cliente deve persistir a chave junto da medição antes da primeira tentativa):

```python
import requests

resposta = requests.post(
    "http://142.93.194.124/Apidados",
    json={"x": 0.04, "y": 0.0, "z": 0.15, "umidade": 64.0, "temperatura": 25.0},
    headers={
        "Idempotency-Key": id_medicao,
    },
    timeout=15,
)
resposta.raise_for_status()
```

## Testes

Com dependências de desenvolvimento instaladas e extensão `pdo_sqlite` habilitada:

```sh
php vendor/phpunit/phpunit --filter MedicaoApiTest
```

Os testes usam SQLite em memória e criam apenas a tabela nova. Não modificam o banco configurado para produção.
