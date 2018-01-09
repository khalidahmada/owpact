<?php
    class OWPactConfig{

        /**
         * OWPactConfig constructor.
         */

        public static $project_config=false;

        public function __construct()
        {
            $this->boot();
        }

        private function boot()
        {
            $this->RegisterConfigs();
            $this->parseProject();
        }

        private function RegisterConfigs()
        {
            $this->RegisterConfig('global');
        }


        private  static function parseProject()
        {
            $project_json = file_get_contents(__DIR__.'/project.json');
            static::$project_config = json_decode($project_json);
        }


        public static function getProjectConfig()
        {

            if(static::$project_config){
                return static::$project_config;
            }else{
                static::parseProject();
            }

            return static::$project_config;

        }

        public static function getDirectory()
        {
            return __DIR__.'/'.static::getCurrentDist();
        }

        /*
         * get current
         */
        public static function getCurrentDist()
        {
            $dist = static::$project_config->{'current_dist'};
            return static::$project_config->{$dist};
        }

        public static function getDir()
        {
            return static::getDirectory().static::$project_config->dir_inc;
        }

        public static function getGlobalPath()
        {
            return static::getCurrentDist();
        }

        /*
         * get Dir the OWP
         */
        public static function getOWPDir()
        {
            return static::getDirectory().static::$project_config->dir_owp;
        }

        /*
         * Register Configs
         */
        private function RegisterConfig($file)
        {
            require_once $file.'.php';
        }

        public  static function getFunctionFilePath(){
            return static::getDirectory().'functions.php';
        }

        public static function getPagesDir()
        {
            return static::getDirectory().static::$project_config->pages_dist;
        }


    }

    new OWPactConfig();