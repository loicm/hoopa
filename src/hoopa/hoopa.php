<?php
namespace loicm\hoopa;

/**
 * hoopa class, lauching commands
 * 
 * @author Loic Mathaud <loic@mathaud.net>
 */
class hoopa {

    protected $config = array();

    /**
     * Instanciate hoopa
     * @param array $config configuration
     */
    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * Run a command
     * @param  string $command_name name of the command
     */
    public function run($command_name = 'help') {
        if (!method_exists('commands', $command_name)) {
            echo 'Error: command '. $command_name .' does not exists'."\n";
            echo 'See '.$_SERVER['argv'][0].' help'."\n";
            exit(1);
        }
        
        $cmd = new commands($this->config);
        $cmd->$command_name();
    }
}