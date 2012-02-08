<?php
/**
 * Entry point. This is the script to run in cli mode (or use "hoopa" exe file instead)
 * 
 * @author Loic Mathaud <loic@mathaud.net>
 * @link http://github.com/loicm/hoopa
 */

// Include needed stuff (FIXME use autoloading?)
include __DIR__ .'/../vendors/IXR/IXR_Library.php';
include __DIR__ .'/hoopa/hoopaLoader.php';
include __DIR__ .'/hoopa/hoopaUtils.php';
include __DIR__ .'/hoopa/hoopaCmd.php';

if ($_SERVER['argc'] < 2) {
    echo "Error: command is missing.\nRun ".$_SERVER['argv'][0]." help\n";
    exit(1);
}

$argv = $_SERVER['argv'];
$script_name = array_shift($argv); // shift the script name
$command_name = array_shift($argv); // get the command name

$config = hoopaLoader::get_config();
hoopaLoader::run_command($command_name, $config);