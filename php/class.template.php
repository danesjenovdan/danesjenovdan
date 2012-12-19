<?php

class template {

    public function set($name, $value) {
        $this->$name = $value;
    }

    public function display($file) {

        include($file);
    }
    
    public function fetch($file) {
        ob_start();
        include ($file);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

}

?>