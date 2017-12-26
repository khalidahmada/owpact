<?php
    namespace pleaseHandler;


    class AjaxHandler extends HandlerPlease{


        public function __construct($argv)
        {
            parent::__construct('ajax',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){
                $this->Execute($this->argv[3],$this->argv[4]);
            }else{
                echo "Please Enter two params AjaxController Name and the action name";
            }
        }

        private function Execute($file_name, $action_name)
        {
            $create = new CreteElement("Ajax/$file_name.php",array("_NAME_" => $file_name,'_ACTION_'=>$action_name),'Ajax',__DIR__.'/../ressources/src/Ajax.php','RegisterAjax');
            $create->CreateItem();
            die();

        }


    }