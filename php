#!/bin/sh
PHPRC=/home/elawliet/lampstack-5.6.28/php/etc
export PHPRC
PHP_PEAR_SYSCONF_DIR=/home/elawliet/lampstack-5.6.28/php/etc
export PHP_PEAR_SYSCONF_DIR
. /home/elawliet/lampstack-5.6.28/php/../scripts/setenv.sh
exec /home/elawliet/lampstack-5.6.28/php/bin/php.bin "$@"
