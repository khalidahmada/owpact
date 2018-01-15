<?php
    namespace pleaseHandler;


    class AjaxHandler extends HandlerPlease{


        public function __construct($argv)
        {

            parent::__construct('ajax',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){
                $this->Execute($this->argv[3],$this->argv[4]);
            }else{
                $this->error("Please Enter two params AjaxController Name and the action name");
            }
        }

        private function Execute($file_name, $action_name)
        {
            $create = new CreteElement("Ajax/$file_name.php",array("_NAME_" => $file_name,'_ACTION_'=>$action_name),'Ajax',__DIR__.'/../ressources/src/Ajax.php','RegisterAjax');
            $create->CreateItem();
            die();

        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'ajax',
                'demo' => "php owp make ajax YourAjaxControllerName ajax_action_name ",
                'doc' => "Create Ajax file to handler ajax request the action name will be the action value on Wordpress ajax requests",
            );
        }






    }