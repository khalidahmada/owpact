<?php
    class OWPactBase{

        /**
         * OWPactBase constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        private function boot()
        {
            $this->RegisterBase();
        }

        public function RegisterBase()
        {
            require_once("config/config.php");
        }

    }

    new OWPactBase();