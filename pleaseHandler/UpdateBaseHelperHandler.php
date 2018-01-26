<?php
    namespace pleaseHandler;


    class UpdateBaseHelperHandler extends HandlerPlease{


        public function __construct($argv)
        {

            parent::__construct('base-helper',$argv,'update');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            $this->Execute();

            die();
        }

        private function Execute()
        {
            $helperBaseTemplate = __DIR__.'/../ressources/templates/Helpers/BaseHelper.php';
            $helperPath = \OWPactConfig::getOWPDir().'/Helpers/BaseHelper.php';

            if (!copy($helperBaseTemplate, $helperPath)) {
                $this->error("Cant Update Helpers/BaseHelper.php please check you config");
            }else{
                $this->success("Helpers/BaseHelper.php Updated successfully!");
            }


        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'base-helper',
                'demo' => "php owp update base-helper",
                'doc' => "Update current base helper core",
            );
        }






    }