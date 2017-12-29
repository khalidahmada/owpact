<?php

    class _NAME_Module{

        /**
         * _NAME_Module constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        private function Register($module){
            $file_name = $module;
            if(!strpos($module,'.php')){
                $file_name .=  ".php";
            }
            require_once($file_name);
        }



        private function boot()
        {
            // Boot Entry for your Module
        }


    }

    new _NAME_Module();