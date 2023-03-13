<?php
$ip = getHostByName(getHostName());
$command = "php artisan serve --host=$ip --port=8000";
exec($command);
