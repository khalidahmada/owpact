<?php
    class BaseModuleRegister{

        /**
         * BaseModuleRegister constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        private function boot(){
            $this->RegisterModules();
        }


        private function RegisterModules(){

        }

        private function RegisterModule($module){

        }


    }