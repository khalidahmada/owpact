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

                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[4],'Ajax');
                $this->Execute($this->argv[3],$parse[1],$parse[0]);

            }else{
                $this->error("Please Enter two params the action name and AjaxController name");
                die();
            }
        }

        private function Execute($action_name,$file_name,$baseDir)
        {

            $replacements = array(
                                    "_NAME_" => $file_name,
                                    '_ACTION_'=>$action_name
            );

            $file_dist = $this->getFullName($baseDir,$file_name,'Ajax');

            $create = new CreteElement($file_dist,$replacements,'Ajax',__DIR__.'/../ressources/src/Ajax.php','RegisterAjax');
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
                'demo' => "php owp make ajax ajax_action_name YourAjaxControllerName",
                'doc' => "Create Ajax file to handler ajax request the action name will be the action value on Wordpress ajax requests",
            );
        }






    }