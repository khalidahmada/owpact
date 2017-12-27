<?php
    namespace pleaseHandler;

    class ExtraHandler extends HandlerPlease{
        public function __construct($argv)
        {
            parent::__construct('extra',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){
                $this->Execute($this->argv[3]);
            }else{
                echo "Please Enter name of your extra class";
            }
        }

        private function Execute($className)
        {
            $create = new CreteElement("Extra/$className.php",array("_NAME_" => $className),'Extra',__DIR__.'/../ressources/src/Extra.php','RegisterExtra');
            $create->CreateItem();
            die();

        }
    }