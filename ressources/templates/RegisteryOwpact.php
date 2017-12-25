<?php
    /* Registery Owpact
    */


    class RegisteryOwpact  {

        /**
         * RegisteryOwpact constructor.
         */
        public function __construct()
        {
            $this->boot();
        }



        private function boot()
        {
            // Helpers
            $this->RegisterHelpers();

            // Register Base Class
            $this->RegisterBases();


            $this->RegisterHooks();
            $this->RegisterRepo();
            $this->RegisterModules();
            $this->RegisterAjax();
        }


        private function RegisterHelpers(){

        }

        private function RegisterAjax(){

        }

        private function RegisterBases(){
            $this->RegisterModule('Repo/BaseRepo');
            $this->RegisterModule('Ajax/BaseAjax');
        }

        private function RegisterHooks(){

        }

        private function RegisterModules(){

        }

        private function RegisterRepo(){

        }

        private function RegisterModule($module){
            require_once($module.'.php');
        }


    }

    new RegisteryOwpact();