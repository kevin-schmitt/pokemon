DOCKER ?= docker-compose exec php-service

quality:
	$(DOCKER) ./php-cs-fixer fix --rules=@Symfony src
	$(DOCKER) vendor/bin/phpstan --memory-limit=1G analyse -c phpstan.neon src

install:
	docker-compose up -d
	$(DOCKER) composer install
	$(DOCKER) bin/console doctrine:database:create --if-not-exists
	$(DOCKER) bin/console doctrine:schema:update  --force
	$(DOCKER) bin/console doctrine:fixture:load