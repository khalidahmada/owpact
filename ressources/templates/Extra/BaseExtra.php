<?php
    class BaseExtra{

        /**
         * BaseExtra constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        private function boot(){
            $this->RegisterModules();
        }


        private function RegisterModules(){
            $this->RegisterModule('images_sizes');
        }

        private function RegisterModule($module){
            $file_name = $module;
            if(!strpos($module,'.php')){
                $file_name .=  ".php";
            }
            require_once($file_name);
        }
    }

    new BaseExtra();