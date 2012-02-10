<?php
namespace loicm\hoopa;

/**
 * class managing the commands
 * 
 * @author Loic Mathaud <loic@mathaud.net>
 */
class commands {
    
    protected $config;
    
    
    public function __construct($config) {
        $this->config = $config;
    }
    
    /**
    * Display help
    */
    public function help() {
        echo 'hoopa v0.3'."\n";
        echo 'commands available:'."\n";
        echo '  - help'."\t".'display this help'."\n";
        echo '  - idea name'."\t".'create a new note'."\n";
        echo '  - edit name'."\t".'edit a note'."\n";
        echo '  - publish name'."\t".'publish a note'."\n";
        echo '  - ideas'."\t".'list notes'."\n";
        echo '  - cats'."\t".'list categories'."\n";
    }
    
    /**
    * Create an idea
    */
    public function idea() {
        if ($_SERVER['argc'] != 3) {
            echo 'Error: idea name missing'."\n";
            exit(1);
        }
        // Get idea name
        $idea_name = $_SERVER['argv'][2];
        
        // Check an idea with this name does not exist
        if (utils::idea_exists($idea_name, $this->config)) {
            echo 'Error: an idea with this name already exists'."\n";
            exit(1);
        }
        
        // Get data file
        $data_file = utils::data_file($idea_name);
        
        // Copy template file into locker room
        copy(
            __DIR__.'/idea.empty',
            $this->config['data_dir'].$data_file
        );
        
        // Open editor
        system($this->config['editor'].' '.$this->config['data_dir'].$data_file .' &> /dev/null');
    }
    
    /**
    * Create an idea
    */
    public function edit() {
        if ($_SERVER['argc'] != 3) {
            echo 'Error: idea name missing'."\n";
            exit(1);
        }
        // Get idea name
        $idea_name = $_SERVER['argv'][2];
        
        // Check idea exists
        $data_file = utils::idea_exists($idea_name, $this->config);
        if (!$data_file) {
            echo 'Error: idea does not exist'."\n";
            exit(1);
        }
        
        // Open editor
        system($this->config['editor'].' '.$this->config['data_dir'].$data_file . ' &> /dev/null');
    }
    
    /**
    * List ideas
    */
    public function ideas() {
        $ideas = utils::get_ideas($this->config);
        
        foreach($ideas as $idea) {
            $idea = str_replace('.html', '', $idea);
            
            if (preg_match('/\.(\d)+$/', $idea, $match)) {
                echo str_replace($match[0], '', $idea).'  [x] published';
            } else {
                echo $idea . '  [ ]';
            }
            echo "\n";
        }
    }
    
    /**
    * Publish an idea on the blog :)
    */
    public function publish() {
        if ($_SERVER['argc'] != 3) {
            echo 'Error: idea name missing'."\n";
            exit(1);
        }
        // Get idea name
        $idea_name = $_SERVER['argv'][2];
        
        // Get name of the data file
        $data_file = utils::data_file($idea_name);
        
        // Get post id (if published)
        $post_id = utils::idea_published($idea_name, $this->config);
        
        // Get content from the data file
        if ($post_id) {
            $data_file = $this->config['data_dir'].$data_file.'.'.$post_id;
        } else {
            $data_file = $this->config['data_dir'].$data_file;
        }
        $content = utils::get_file_content($data_file);
        
        if ($content['description'] == '') {
            echo 'Error: no content to publish'."\n";
            exit(1);
        }
        if ($content['wp_slug'] == '') {
            echo 'Error: no URL for that idea'."\n";
            exit(1);
        }
        if ($content['title'] == '') {
            echo 'Error: no title for that idea'."\n";
            exit(1);
        }
        if (!isset($content['categories'][0]) || $content['categories'][0] == '') {
            echo 'Error: no category for that idea'."\n";
            exit(1);
        }
        
        $client = new \IXR_Client($this->config['rpc_url']);
        
        // Publish post (edit)
        if ($post_id) {
            $client->query(
                'metaWeblog.editPost',
                $post_id ,
                $this->config['username'],
                $this->config['password'],
                $content,
                true);
                
            $response = $client->getResponse();
            
            if ($response == '1') {
                echo $idea_name . ' updated !'."\n";
            }
        
        // Publish post (create)
        } else {
            $client->query(
                'metaWeblog.newPost',
                '1',
                $this->config['username'],
                $this->config['password'],
                $content,
                true);

            $post_id = $client->getResponse();
            
            if (is_int((int)$post_id)) {
                rename(
                    $data_file,
                    $data_file.'.'.$post_id
                );
                
                echo $idea_name. ' published !'."\n";
            }
        }
    }
    
    /**
    * Display post categories of the blog
    */
    public function cats() {
        $client = new IXR_Client($this->config['rpc_url']);
        
        $client->query(
            'mt.getCategoryList',
            '1',
            $this->config['username'],
            $this->config['password']);

        $cats = $client->getResponse();
        foreach($cats as $id => $cat) {
            echo '  - ' .$cat['categoryId']."\n";
        }
    }
}