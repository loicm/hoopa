<?php
/**
 * Entry point. This is the script to run in cli mode (or use "hoopa" exe file instead)
 * 
 * @author Loic Mathaud <loic@mathaud.net>
 * @link http://github.com/loicm/hoopa
 */

// Include needed stuff (FIXME use autoloading?)
define('HOOPA_PATH', __DIR__.'/');
include HOOPA_PATH .'../vendors/IXR/IXR_Library.php';
include HOOPA_PATH .'hoopa/commands.php';
include HOOPA_PATH .'hoopa/config.php';
include HOOPA_PATH .'hoopa/hoopa.php';
include HOOPA_PATH .'hoopa/hoopaUtils.php';

if ($_SERVER['argc'] < 2) {
    echo "Error: command is missing.\nRun ".$_SERVER['argv'][0]." help\n";
    exit(1);
}

$argv = $_SERVER['argv'];
$script_name = array_shift($argv); // shift the script name
$command_name = array_shift($argv); // get the command name

$config = new loicm\hoopa\config();
$hoopa = new loicm\hoopa\hoopa($config);
$hoopa->run($command_name);