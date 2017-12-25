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
            $this->Publish();
        }


        public function Publish()
        {
            if(!$this->checkDir()){
                $this->PublishFolder();
            }else{
                echo "Publish is Already Done go to ".OWPactConfig::getOWPDir();
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
            copy_dir(__DIR__."/../ressources/templates" , OWPactConfig::getOWPDir());
            echo "Donee";
        }
    }

    new PublishHandler();