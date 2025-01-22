.PHONY: help

help: ## Affiche l'aide pour les commandes Makefile disponibles.
	@findstr /R /B /C:"^[a-zA-Z_-][a-zA-Z_-]*:.*##" $(MAKEFILE_LIST)
#GIT
lolapush: ## Permet de push sur lola.
	git add .
	git commit -m "$(m)"
	git push origin lola

lola-sync-dev: ## Permet de syncronyser la branche dev avec celle de lola
	git checkout dev
	git pull origin lola
	git push origin dev --force-with-lease
	git checkout lola

#Docker
#docker exec dive_and_collect_franken-php-1 php bin/console make:migration
#docker exec dive_and_collect_franken-php-1 php bin/console doctrine:migrations:migrate

# /!\ La commande ci-dessous va supprimer toutes les données de la BDD et executer les fixtures /!\
#docker exec dive_and_collect_franken-php-1 php bin/console doctrine:fixtures:load --no-interaction

docker-mysql: ## Connecter sur mysql de docker.
	docker exec -it dive_and_collect_franken-database-1 bash
	mysql -u root -p

docker-build: ## lance le build et les images de docker.make compil-r
	docker compose build --no-cache
	docker compose up --pull always -d --wait

docker-start: ## lance l'image de docker.
	docker compose up --pull always -d --wait
docker-reset: ## supprime entiérement les images de docker.
	docker stop $$(docker ps -aq)
	docker rm $$(docker ps -aq)
	docker-compose down --remove-orphans
	docker image prune -a -f
	docker volume prune -f
	docker system prune --all --volumes --force

#Compilation Tailwind et AssetMapper
compil:
	php bin/console tailwind:build --minify
	php bin/console asset-map:compile

tests-unit:
	php bin/phpunit --testsuite=Unit

tests-inte:
	php bin/phpunit --testsuite=Inte

tests-all:
	php bin/phpunit --testsuite=all
