<?php
    /* Registery Owpact
    */
    // Config Object
    class WPTCONF {
        public static $conf=false;

        public static function get(){
            return static::$conf;
        }
    }

    class RegistryOwpact  {

        /**
         * RegisteryOwpact constructor.
         */
        public function __construct()
        {
            $this->boot();
        }


        private function boot(){
            // Load Configs
            $this->LoadConfigs();
            // Helpers
            $this->RegisterHelpers();
            $this->RegisterTraits();

            // Register Base Class
            $this->RegisterBases();
            $this->RegisterTableViews();
            $this->RegisterRoute();

            $this->RegisterEmails();

            $this->RegisterHooks();
            $this->RegisterRepo();
            $this->RegisterExtra();
            $this->RegisterModules();
            $this->RegisterAjax();
            $this->RegisterQueues();
            $this->RegisterFunctions();

            // call extra
            $this->RegisterExtraHandlers();
        }

        private function RegisterExtraHandlers(){

        }


        private function RegisterHelpers(){

        }

        private function RegisterAjax(){

        }

        private function RegisterBases(){
            $this->RegisterModule('Helpers/BaseHelper');
            $this->RegisterModule('libs/BaseLibs');
            $this->RegisterModule('Extra/BaseExtra');
            $this->RegisterModule('Emails/BaseEmail');
            $this->RegisterModule('Repo/BaseRepo');
            $this->RegisterModule('Repo/TraitRepo');
            $this->RegisterModule('Ajax/BaseAjax');
        }

        /*
         * Entry Functions
         */

        private function RegisterHooks(){

        }

        private function RegisterModules(){

        }

        private function RegisterFunctions(){

        }

        private function RegisterRepo(){

        }

        private function RegisterEmails(){

        }

        private function RegisterExtra(){

        }

        private function RegisterRoute(){

        }

        private function RegisterTableViews(){

        }

        private function RegisterTraits(){

        }
        private function RegisterQueues(){

        }

        private function RegisterModule($module){
            $file_name = $module;
            if(!strpos($module,'.php')){
                $file_name .=  ".php";
            }
            require_once($file_name);
        }


        private function LoadConfigs()
        {
            $configs = file_get_contents(__DIR__.'/config/config.json');
            if($configs){
                WPTCONF::$conf = json_decode($configs);
            }
        }


    }

    new RegistryOwpact();