<?php
namespace loicm\hoopa;

/**
 * class managing the configuration of hoopa
 * 
 * @author Loic Mathaud <loic@mathaud.net>
 */
class config {

    /**
     * Path to the config file
     * @var string
     */
    protected $config_file = '';

    /**
     * Configuration of hoopa
     * @var array
     */
    protected $config = array();


    /**
     * Init config
     * @param string $config_file path to the config file (default is config/hoopa.ini)
     */
    public function __construct($config_file = '') {
        $this->config_file = $config_file;

        if ($this->config_file == '') {
            $this->config_file = __DIR__.'/../../config/hoopa.ini';
        }
        
        if (!is_file($this->config_file)) {
            throw new \RuntimeException('Config file not found ('. $this->config_file.')');
        }
        
        $this->loadConfig();
    }

    /**
     * Return the configuration
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Parse the config file and check mandatory keys exist
     * 
     * Config file is an ini file with the following keys:
     * rpc_url = ""
     * blog_id = ""
     * username = ""
     * password = ""
     * editor = ""
     * data_dir = ""  ; optional, "lockerroom/ dir by default"
     */
    protected function loadConfig() {
        $this->config = parse_ini_file($this->config_file);

        if (!array_key_exists('rpc_url', $this->config) || $this->config['rpc_url'] == '') {
            throw new \RuntimeException('Config file: rpc_url not found or empty');
        }
        if (!array_key_exists('blog_id', $this->config) || $this->config['blog_id'] == '') {
            throw new \RuntimeException('Config file: blog_id not found or empty');
        }
        if (!array_key_exists('username', $this->config) || $this->config['username'] == '') {
            throw new \RuntimeException('Config file: username not found or empty');
        }
        if (!array_key_exists('password', $this->config) || $this->config['password'] == '') {
            throw new \RuntimeException('Config file: password not found or empty');
        }
        if (!array_key_exists('editor', $this->config) || $this->config['editor'] == '') {
            throw new \RuntimeException('Config file: editor not found or empty');
        }

        if (!array_key_exists('data_dir', $this->config) || $this->config['data_dir'] == '') {
            $this->config['data_dir'] = __DIR__.'/../../lockerroom/';
        }
        $this->createDataDir($this->config['data_dir']);
    }

    /**
     * Create data_dir directory if not exists.
     * data_dir is the directory where to store blog posts
     * @param  string $dir path to the directory
     */
    protected function createDataDir($dir) {
        if (!file_exists($dir)) {
            mkdir($dir);
        }
    }
}