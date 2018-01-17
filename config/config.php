<?php

    class OWPactConfig
    {

        public static $project_config=false;

        /**
         * OWPactConfig constructor.
         */

        public function __construct()
        {
            $this->boot();
        }

        /*
         * Entry point
         * boot
         */
        private function boot()
        {
            $this->RegisterConfigs();
            $this->parseProject();
        }

        /*
         * Register Configs
         */
        private function RegisterConfigs()
        {
            $this->RegisterConfig('global');
        }


        /*
         * Parse Project
         */
        private static function parseProject()
        {
            $file=__DIR__ . '/project.json';
            if (!is_file($file)) {
                copy(__DIR__ . '/project.json.dist', $file);
            }
            $project_json=file_get_contents(__DIR__ . '/project.json');
            static::$project_config=json_decode($project_json);
        }


        public static function getProjectConfig()
        {

            if (static::$project_config) {
                return static::$project_config;
            } else {
                static::parseProject();
            }

            return static::$project_config;

        }

        public static function getDirectory()
        {
            return __DIR__ . '/' . static::getCurrentDist();
        }

        /*
         * get current
         */
        public static function getCurrentDist()
        {
            $projects = false;
            $dist_prj = false;

            if(isset(static::$project_config->projects)){
                $projects =static::$project_config->projects;
            }

            $dist=static::$project_config->{'current_dist'};


            foreach($projects as $project){
                if(isset($project->{$dist})){
                    $dist_prj = $project->{$dist};
                }
            }

            return $dist_prj;
        }

        public static function getCurrentDistVal()
        {
            return static::$project_config->{'current_dist'};
        }

        /*
         * Get list Available list
         */
        public static function getListProjectAvailable()
        {

            $projects = array();
            $projectList = array();

            if(isset(static::$project_config->projects)){
                $projects =static::$project_config->projects;
            }


            foreach($projects as $project){
                foreach($project as $key =>  $proj){
                    $projectList[] = $key;
                }

                //print_r($project);// $key;
               // $projectList[] = $projects->$key;
            }

            return $projectList;
        }

        public static function getDir()
        {
            return static::getDirectory() . static::$project_config->dir_inc;
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
            return static::getDirectory() . static::$project_config->dir_owp;
        }

        /*
         * Register Configs
         */
        private function RegisterConfig($file)
        {
            require_once $file . '.php';
        }

        /*
         * get file function of current theme
         */
        public static function getFunctionFilePath()
        {
            return static::getDirectory() . 'functions.php';
        }

        /*
         * get Pages dir into config
         */
        public static function getPagesDir()
        {
            return static::getDirectory() . static::$project_config->pages_dist;
        }

        /*
         * Switch To Theme
         */
        public static function SwitchToTheme($theme_to_switch)
        {
            // we get the current Value
            static::parseProject();

            static::$project_config->current_dist=$theme_to_switch;

            $newObject=json_encode(static::$project_config, JSON_PRETTY_PRINT);

            $newObject=str_replace('\/', '/', $newObject);

            $status=file_put_contents(__DIR__ . '/project.json', $newObject);

            // we get the current Value
            static::parseProject();

            return $status;

        }

        /*
         * Get the Registry Path
         */
        public static function getRegistryPath()
        {
            return static::getOWPDir() . '/RegistryOwpact.php';
        }



    }

    new OWPactConfig();