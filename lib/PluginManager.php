<?php
class PluginManager {
    public static function init() {
        $dir = scandir(__DIR__.'/../plugins/');
        $plugins = array();
        foreach($dir as $file) {
            $name = explode('.', $file);
            if($file != '.' && $file != '..' && $name[1] == 'php') {
                $plugins[] = $name[0];
            }
        }
        return $plugins;
    }
}