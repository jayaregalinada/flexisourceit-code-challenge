REPOSITORY_URL:=127.0.0.1:5000
REPOSITORY:=${REPOSITORY_URL}/flexisourceit/code-challenge
VERSION:=latest
DOCKERFILE=Dockerfile
PORT:=80
DB_HOST:=host.docker.internal
DB_DATABASE:=project_flexisourceit
DB_USERNAME:=project
DB_PASSWORD:=project
APP_DEBUG:=true
APP_ENV:=local
APP_KEY:=${APP_KEY}

.PHONY: build
build:
	docker build \
		-t ${REPOSITORY}:${VERSION} \
		-f ${DOCKERFILE} \
		--rm \
		.

.PHONY: run
run:
	docker run \
		-e APP_DEBUG=${APP_DEBUG} \
		-e APP_ENV=${APP_ENV} \
		-e DB_HOST=${DB_HOST} \
		-e DB_DATABASE=${DB_DATABASE} \
		-e DB_USERNAME=${DB_USERNAME} \
		-e DB_PASSWORD=${DB_PASSWORD} \
		-e APP_KEY=${APP_KEY} \
		-p ${PORT}:80 \
		${REPOSITORY}:${VERSION}

.PHONY: push
push:
	docker push ${REPOSITORY}:${VERSION}

.PHONY: start
start:
	php -S 0.0.0.0:${PORT} -t public
