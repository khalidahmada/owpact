<?php
    namespace pleaseHandler;

    class HelperHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('helper',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){
                $this->Execute($this->argv[3]);
            }else{
                $this->error("Please enter Helper Name");
                die();
            }
        }

        private function Execute($file_name)
        {
            $replaces = array(
                '__NAME__' => $file_name
            );

            $create = new CreteElement("Helpers/$file_name.php",$replaces,'Helpers',__DIR__.'/../ressources/src/Helper.php','RegisterHelpers');
            $create->CreateItem();
            die();
        }
        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'helper',
                'demo' => "php owp make helper NameOfHelper(s)",
                'doc' => "if you want create helper functions",
            );
        }
    }