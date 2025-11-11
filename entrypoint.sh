#!/usr/bin/env sh

cat > /var/www/html/admin-setup/config.php << EOFPHP
<?php
define('HOST', '${HOST}');
define('USER', '${USER}');
define('PASSWORD', '${PASSWORD}');
define('DB', '${DB}');
EOFPHP

exec "$@"