<?php
    namespace pleaseHandler;

    class EmailHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('email',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            $argv = $this->argv;
            if(isset($argv[3]) && isset($argv[4])){
                $this->Execute($argv[3],$argv[4]);
            }else{
                echo "Please enter email name and email template name";
            }
        }

        private function Execute($file_name, $action_name)
        {
            $create = new CreteElement("Ajax/$file_name.php",array("_NAME_" => $file_name,'_ACTION_'=>$action_name),'Ajax',__DIR__.'/../ressources/src/Ajax.php','RegisterAjax');
            $create->CreateItem();
            die();

        }
    }