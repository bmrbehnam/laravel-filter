## Lendo Task

## Setup

You must install docker and docker-compose. then:

clone project

```bash
git clone https://github.com/bmrbehnam/laravel-filter.git
```

create .env file by copying from .env.example

```bash
cp .env.example .env
```

after create and modify env file you must build service and containers

```bash
docker-compose up -d
```

after that all services pulled and started run composer

```bash
docker-compose exec php sh -c "composer install"
```

then migrate database

```bash
docker-compose exec php sh -c "php artisan migrate --seed"
```

## Test

```bash
docker-compose exec php sh -c "php artisan test"
```

## Postman
```bash
https://api.postman.com/collections/1358090-a82247fe-c28a-4510-8f68-1ba54344f93a?access_key=PMAT-01HNANHKX5K0EDGBADCSAD8T2V
```

##  Routes

#### Order List
```bash
curl -X GET "http://localhost:8090/api/v1/orders?status=paid&nation_code=15&user=1&min_amount=300&max_amount=400&customer_name=Dexter" -H "Content-Type: application/json" -H "Accept: application/json" -d '{"email" : "root@gmail.com","password" : "root" }'
```
