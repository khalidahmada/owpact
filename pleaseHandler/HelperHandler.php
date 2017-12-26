<?php
    namespace pleaseHandler;

    class HelperHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('helper',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){
                $this->Execute($this->argv[3]);
            }else{
                echo "Please enter Helper Name";
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
    }