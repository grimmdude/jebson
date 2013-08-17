<?php
require_once 'lib/jebson.php';
Jebson::buildPage();
print_r(Jebson::$request);