<?php

if (!empty($_SERVER['AUTOLOADER'])) {
  require_once $_SERVER['AUTOLOADER'];
}
set_include_path($_SERVER['PHING_DIR'].'/classes' . PATH_SEPARATOR . get_include_path());

require_once $_SERVER['PROPEL_DIR'] . '/runtime/lib/Propel.php';
require_once $_SERVER['PROPEL_DIR']. '/generator/lib/util/PropelQuickBuilder.php';
require_once __DIR__ . '/TestCase.php';
