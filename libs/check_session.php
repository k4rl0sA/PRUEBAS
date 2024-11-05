<?php
session_name('us_sds_test_unico');
session_start();
echo "Valor en sesión: ";
var_dump($_SESSION);
