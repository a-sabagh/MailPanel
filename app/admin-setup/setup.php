<?php
require_once __DIR__ . '/helper.php';

$filename = __DIR__ . "/appKey";

if (file_exists($filename)) {
    return;
} else {
    app_redirect("admin-setup/setup-schema.php");
}