<?php
namespace loicm\hoopa\tests\units;

require_once __DIR__.'/../mageekguy.atoum.phar';
include __DIR__.'/../../src/hoopa/config.php';

use \mageekguy\atoum;
use \loicm\hoopa;

class config extends atoum\test {

    public function test() {

        $this->assert
                ->exception(function() {
                    new hoopa\config();
                })
                ->isInstanceOf('RuntimeException');

        
        $tmp_dir = sys_get_temp_dir().'/hoopa';
        if (is_dir($tmp_dir)) {
            rmdir($tmp_dir);
        }


        $tmp_conf = tempnam($tmp_dir, "config_ini_");
        $conf_str = 'rpc_url = ""
blog_id = ""
username = ""
password = ""
editor = ""
data_dir = ""';
        file_put_contents($tmp_conf, $conf_str);

        $this->assert
                ->exception(function() use($tmp_conf) {
                    new hoopa\config($tmp_conf);
                })
                ->isInstanceOf('RuntimeException')
                ->hasMessage('Config file: rpc_url not found or empty');


        $tmp_conf = tempnam($tmp_dir, "config_ini_");
        $conf_str = 'rpc_url = "http://example.com/xmlrpc"
blog_id = ""
username = ""
password = ""
editor = ""
data_dir = ""';
        file_put_contents($tmp_conf, $conf_str);

        $this->assert
                ->exception(function() use($tmp_conf) {
                    new hoopa\config($tmp_conf);
                })
                ->isInstanceOf('RuntimeException')
                ->hasMessage('Config file: blog_id not found or empty');


        $tmp_conf = tempnam($tmp_dir, "config_ini_");
        $conf_str = 'rpc_url = "http://example.com/xmlrpc"
blog_id = "default"
username = ""
password = ""
editor = ""
data_dir = ""';
        file_put_contents($tmp_conf, $conf_str);

        $this->assert
                ->exception(function() use($tmp_conf) {
                    new hoopa\config($tmp_conf);
                })
                ->isInstanceOf('RuntimeException')
                ->hasMessage('Config file: username not found or empty');


        $tmp_conf = tempnam($tmp_dir, "config_ini_");
        $conf_str = 'rpc_url = "http://example.com/xmlrpc"
blog_id = "default"
username = "myname"
password = ""
editor = ""
data_dir = ""';
        file_put_contents($tmp_conf, $conf_str);

        $this->assert
                ->exception(function() use($tmp_conf) {
                    new hoopa\config($tmp_conf);
                })
                ->isInstanceOf('RuntimeException')
                ->hasMessage('Config file: password not found or empty');

        
        $tmp_conf = tempnam($tmp_dir, "config_ini_");
        $conf_str = 'rpc_url = "http://example.com/xmlrpc"
blog_id = "default"
username = "myname"
password = "mypass"
editor = ""
data_dir = ""';
        file_put_contents($tmp_conf, $conf_str);

        $this->assert
                ->exception(function() use($tmp_conf) {
                    new hoopa\config($tmp_conf);
                })
                ->isInstanceOf('RuntimeException')
                ->hasMessage('Config file: editor not found or empty');


        $tmp_conf = tempnam($tmp_dir, "config_ini_");
        $conf_str = 'rpc_url = "http://example.com/xmlrpc"
blog_id = "default"
username = "myname"
password = "mypass"
editor = "sublime-text-2"
data_dir = ""';
        file_put_contents($tmp_conf, $conf_str);

        $config = new hoopa\config($tmp_conf);
        $config_data = $config->getConfig();

        $this->assert
                ->string($config_data['rpc_url'])
                    ->isNotEmpty()
                    ->isEqualTo('http://example.com/xmlrpc')
                ->string($config_data['blog_id'])
                    ->isNotEmpty()
                    ->isEqualTo('default')
                ->string($config_data['username'])
                    ->isNotEmpty()
                    ->isEqualTo('myname')
                ->string($config_data['password'])
                    ->isNotEmpty()
                    ->isEqualTo('mypass')
                ->string($config_data['editor'])
                    ->isNotEmpty()
                    ->isEqualTo('sublime-text-2')
                ->string($config_data['data_dir'])
                    ->isNotEmpty();
    }
}