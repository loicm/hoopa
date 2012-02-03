<?php

class hoopaLoader {

    /**
    * Get config from hoopa config file
    * File is under your home .config/hoopa/ directory
    * hoopa.ini :
    *    - rpc_url = '' # XML/RPC entrypoint
    *    - blog_id = '' # your blog id
    *    - username = ''
    *    - password = ''
    *    - editor = ''
    */
    public static function get_config() {
        $config_file = getenv('HOME').'/.config/hoopa/hoopa.ini';
        
        if (!file_exists($config_file)) {
            echo 'Error: config file not found at '. $config_file."\n";
            exit(1);
        }
        
        $config = parse_ini_file($config_file);
        
        $config['data_dir'] = __DIR__.'/../../lockerroom/';
        
        return $config;
    }
    
    /**
    * Run the command
    */
    public static function run_command($command_name, $config) {
        if (!method_exists('hoopaCmd', $command_name)) {
            echo 'Error: command '. $command_name .' does not exists'."\n";
            echo 'See '.$_SERVER['argv'][0].' help'."\n";
            exit(1);
        }
        
        $hoopa = new hoopaCmd($config);
        $hoopa->$command_name();
    }
}