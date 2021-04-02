# Co-Api

The Co-Api created as test case.

## Container Usage

Run `docker-compose up -d --build`.

Containers created, and their ports (if used) are as follows:

- **nginx** - `:yourPort`
- **mysql** - `:yourPort`
- **php** - `:9000`

## Application Installation
- Provide custom credentials in `src/.env` file.

- Start Docker containers.
```
docker-compose up -d
```

- Install dependencies.
```
docker-compose run --rm app composer install
```

- Generating JWT keys
```
docker-compose run --rm app php bin/console lexik:jwt:generate-keypair
```

- Run database migrations.
```
docker-compose run --rm app php bin/console doctrine:migrations:migrate
```

- (Optional) Run database fixtures if it's the first time you're setting the project up.
```
docker-compose run --rm app php bin/console doctrine:fixtures:load
```

- Available routes
```
docker-compose run --rm app php bin/console debug:router
```

## XDebug Installation
To install xDebug you have to set environment argument for app container. By default, is enabled.
```
env: development
```

- Enable xDebug
```
docker exec -u root -it co-api-app /./usr/local/bin/php-xdebug
```

- Disable xDebug
```
docker exec -u root -it co-api-app /./usr/local/bin/php-xdebug
```
