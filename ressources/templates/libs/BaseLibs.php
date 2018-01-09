<?php
    class BaseLibs{

        /**
         * BaseLibs constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        private function boot()
        {
            $this->RegisterModules();
        }

        /*
         * Register Modules
         */
        private function RegisterModules(){

        }


        private function RegisterModule($module)
        {
            $file_name = $module;
            if(!strpos($module,'.php')){
                $file_name .=  ".php";
            }
            require_once($file_name);
        }

        /*
         * Don't call this function if you don't need custom Route
         * Router
         */
        private function RegisterRouterCore()
        {
            $this->RegisterModule('Routes/AltoRouter');
            $this->RegisterModule('Routes/Routes');
        }

    }

    new BaseLibs();