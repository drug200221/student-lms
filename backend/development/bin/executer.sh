#!/usr/bin/env sh
docker compose exec \
  --user "$(id -u):$(id -g)" \
  -w /var/www/student st-apache $@
