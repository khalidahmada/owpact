<?php
    namespace pleaseHandler;

    class ExtraHandler extends HandlerPlease{
        public function __construct($argv)
        {
            parent::__construct('extra',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){
                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[3],'Extra');
                $this->Execute($parse[1],$parse[0]);
            }else{
                $this->error("Please Enter name of your extra class");
                die();
            }
        }

        private function Execute($file_name,$baseDir)
        {
            $file_dist = $this->getFullName($baseDir,$file_name,'Extra');
            $create = new CreteElement($file_dist,array("_NAME_" => $file_name),'Extra',__DIR__.'/../ressources/src/Extra.php','RegisterExtra');
            $create->CreateItem();
            die();

        }


        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'extra',
                'demo' => "php owp make extra ExtraName ",
                'doc' => "Some time your want crete extra class for specific logic into WordPres",
            );
        }
    }