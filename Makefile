#######################  VARS  ##########################
OAUTH_SERVER_NETWORK = "sso-oauth2-server_default"

#######################  INITIONs  ##########################
init: down-clear \
	api-clear api-permissions \
	pull build up\
	composer-i \
#	api-check
#api-db-init \

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine && sudo sh -c 'rm -rf api/var/*'
api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine && sudo sh -c 'chmod -R 777 *'
api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30
api-db-init: api-wait-db \
	api-diff \
	api-migrate \
	api-schema-v
api-check: lint test #psalm-no-diff


#######################  DOCKER  ##########################
up:
	docker-compose up -d
down:
	docker-compose down --remove-orphans
stop-all:
	docker stop $$(docker ps -a -q)
restart: down up
down-clear:
	docker-compose down -v --remove-orphans
build:
	docker-compose build --pull
pull:
	docker-compose pull
pure-docker:
	docker system prune -af
get-ip:
	docker network inspect ${OAUTH_SERVER_NETWORK} | sh -c 'grep -Eoh "(\"Gateway\": \")+[0-9]{1,3}(\.[0-9]{1,3}){3}\""' | sh -c 'grep -Eoh "[0-9]{1,3}(\.[0-9]{1,3}){3}"'


#######################  DOCTRINE  ##########################
api-migrate:
	docker-compose run --rm api-php-cli php bin/app.php --ansi migrations:migrate --no-interaction
api-diff:
	docker-compose run --rm api-php-cli php bin/app.php --ansi migrations:diff --no-interaction --allow-empty-diff
api-fixtures:
	docker-compose run --rm api-php-cli php bin/app.php --ansi fixtures:load
api-schema-v:
	docker-compose run --rm api-php-cli php bin/app.php --ansi orm:validate-schema

#######################  COMPOSER  ##########################
composer:
	docker-compose run --rm api-php-cli composer ${arg}
composer-i:
	docker-compose run --rm api-php-cli composer install
composer-da:
	docker-compose run --rm api-php-cli composer dump-autoload
composer-u:
	docker-compose run --rm api-php-cli composer update
composer-u-list:
	docker-compose run --rm api-php-cli composer outdated
composer-u-list-direct:
	docker-compose run --rm api-php-cli composer outdated --direct
composer-rq:
	docker-compose run --rm api-php-cli composer require ${arg}
composer-rm:
	docker-compose run --rm api-php-cli composer remove ${arg}


#######################  CODE STYLE  ####################
lint:
	docker-compose run --rm api-php-cli vendor/bin/phplint
	docker-compose run --rm api-php-cli vendor/bin/phpcs
cs-fix:
	docker-compose run --rm api-php-cli vendor/bin/phpcbf
psalm:
	docker-compose run --rm api-php-cli vendor/bin/psalm
psalm-no-diff:
	docker-compose run --rm api-php-cli vendor/bin/psalm --no-diff


#######################  TESTs  ####################
test:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always
coverage:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always --coverage-html var/test/coverage
phpunit:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always --coverage-html var/coverage --testsuite=unit ${arg}
phpunit-functional:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always --coverage-html var/coverage --testsuite=functional ${arg}


#######################  CLI  ####################
cli:
	docker-compose run --rm api-php-cli php cli.php ${arg}
console:
	docker-compose run --rm api-php-cli php bin/app.php --ansi ${arg}

