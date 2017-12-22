<?php
    class PublishHandler
    {

        /**
         * PublishHandler constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        public function boot()
        {
            if(!$this->checkDir()){
                $this->PublishFolder();
            }
        }

        public static function  checkDir()
        {
           return  is_dir( OWPactConfig::getOWPDir());
        }

        /*
         *
         */
        private function PublishFolder()
        {
            copy("../ressources/templates" , OWPactConfig::getOWPDir());
            echo "Donee";
        }
    }

    new PublishHandler();