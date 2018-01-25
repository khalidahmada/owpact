<?php
    namespace pleaseHandler;


    class ModuleHandler extends HandlerPlease{


        public function __construct($argv)
        {
            parent::__construct('module',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){

                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[3],'Modules');
                $this->Execute($parse[1],$parse[0]);

            }else{

                $this->error("Please enter your module name");
                die();

            }
        }

        private function Execute($file_name,$baseDir)
        {
            $replaces = array("_NAME_" => $file_name);

            $file_dist = $this->getFullName($baseDir,$file_name,'Modules');

            $create = new CreteElement(
                                        $file_dist,
                                        $replaces,'Module',__DIR__.'/../ressources/src/Module.php',
                                        'RegisterModules'
            );

            $create->CreateItem();
            die();

        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'module',
                'demo' => "php owp make model YourModuleName",
                'doc' => "Some time you want to create your own module on project with separates files and logic",
            );
        }


    }