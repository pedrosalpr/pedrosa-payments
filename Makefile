ifneq ("$(strip $(wildcard .env))","")
  include .env
else
  $(shell cp .env.example .env)
  include .env
endif

CONTAINER_ENVIRONMENT=${APP_ENV}
CONTAINER_VERSION=${APP_VERSION}
CONTAINER_TIMEZONE=${APP_TIMEZONE}
CONTAINER_NAME=${CONTAINER_APP_NAME}-${CONTAINER_ENVIRONMENT}-${CONTAINER_VERSION}
IMAGE_NAME=pedrosalpr/${CONTAINER_APP_NAME}-${CONTAINER_ENVIRONMENT}:${CONTAINER_VERSION}

scan-grype:
	docker run --rm \
		--volume /var/run/docker.sock:/var/run/docker.sock \
		--name Grype anchore/grype:latest \
		${IMAGE_NAME} --scope all-layers

scan-trivy-image:
	docker run --rm \
		--volume /var/run/docker.sock:/var/run/docker.sock \
		--name trivy aquasec/trivy:latest \
		image ${IMAGE_NAME}

scan-trivy-filesystem:
	docker run --rm \
		--volume /var/run/docker.sock:/var/run/docker.sock \
		--name trivy aquasec/trivy:latest \
		filesystem --scanners config,secret,vuln .

up:
	docker compose up -d

down:
	docker compose down

logs:
	docker logs -f ${CONTAINER_NAME}

optimize:
	docker exec ${CONTAINER_NAME} php artisan optimize:clear

generate-key:
	docker exec ${CONTAINER_NAME} php artisan key:generate

composer-install:
	docker exec ${CONTAINER_NAME} composer install

install:
	make composer-install

test:
	docker exec -it -e TERM=xterm ${CONTAINER_NAME} php artisan test

bash:
	docker exec -it -e TERM=xterm ${CONTAINER_NAME} /bin/sh

lint-diff:
	docker exec ${CONTAINER_NAME} composer lint-diff

lint-diff-staged:
	docker exec ${CONTAINER_NAME} composer lint-diff-staged

lint-fix:
	docker exec ${CONTAINER_NAME} composer lint-fix

lint-fix-staged:
	docker exec ${CONTAINER_NAME} composer lint-fix-staged

code-analyse-stan:
	docker exec ${CONTAINER_NAME} composer code-analyse-stan
