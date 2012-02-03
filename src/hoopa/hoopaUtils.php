<?php

class hoopaUtils {

    /**
    * Return the html file from its name
    * @param string $idea_name
    * @return string file name
    */
    public static function data_file($idea_name) {
        return $idea_name;
    }
    
    /**
    * Get the list of files in lockerroom
    */
    public static function get_ideas($config) {
        $ideas = array();
        
        if ($dh = opendir($config['data_dir'])) {
            while (($file = readdir($dh)) !== false) {
                if(is_file($config['data_dir'] . $file) ) {
                    $ideas[] = $file;
                }
            }
            closedir($dh);
        }
        
        return $ideas;
    }
    
    /**
    * Check if an idea already exists
    * @param string $idea_name
    * @param array $config
    * @
    */
    public static function idea_exists($idea_name, $config) {
        $ideas = self::get_ideas($config);
        
        $data_file = self::data_file($idea_name);
        
        foreach($ideas as $idea) {
            if ($idea == $data_file) {
                return $data_file;
            }
            if (preg_match('/^'. $data_file .'\.(\d+)$/', $idea, $match)) {
                return $match[0];
            }
        }
        
        return false;
    }
    
    /**
    * Check if an idea is already published
    * @param string $idea_name
    * @param array $config
    * @return mixed the post id or false
    */
    public static function idea_published($idea_name, $config) {
        if (!self::idea_exists($idea_name, $config)) {
            echo 'Error: idea does not exists'."\n";
            exit(1);
        }
        
        $ideas = self::get_ideas($config);
        
        $data_file = self::data_file($idea_name);
        
        foreach($ideas as $idea) {
            if ($idea == $data_file) {
                return false;
            } elseif (preg_match('/^'. $data_file .'\.(\d+)$/', $idea, $match)) {
                return $match[1];
            }
        }
        
        return false;
    }
    
    public static function get_file_content($file) {
        $lines = file($file);

        $content['description'] = '';
        foreach ($lines as $line) {
            if (preg_match('/^@slug:(.+)$/', $line, $match)) {
                $content['wp_slug'] = trim($match[1]);
            } elseif (preg_match('/^@title:(.+)$/', $line, $match)) {
                $content['title'] = trim($match[1]);
            } elseif (preg_match('/^@category:(.+)$/', $line, $match)) {
                $content['categories'][] = trim($match[1]);
            } else {
                $content['description'].= $line."\n";
            }
        }
        
        return $content;
    }
}