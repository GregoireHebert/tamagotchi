#!/bin/sh
set -xe

# Detect the host IP
export DOCKER_BRIDGE_IP=$(ip ro | grep default | cut -d' ' -f 3)

# Permissions hack because setfacl does not work on Mac and Windows
chown -R www-data var

exec php-fpm
