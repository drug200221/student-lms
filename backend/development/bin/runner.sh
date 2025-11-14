#!/usr/bin/env sh
docker compose run \
  --rm \
  --user "$(id -u):$(id -g)" \
  -v "${HOME}/.composer/config.json:/.composer/config.json:ro" \
  -w /var/www/student st-apache $@
