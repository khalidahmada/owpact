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
                $this->PrepareForSetUpModules($this->argv[3]);
                $this->Execute($this->argv[3]);
            }else{
                $this->error("Please enter your module name");
            }
        }

        private function Execute($file_name)
        {
            $replaces = array("_NAME_" => $file_name);

            $create = new CreteElement(
                                        "Modules/$file_name/$file_name.php",
                                        $replaces,'Module',__DIR__.'/../ressources/src/Module.php',
                                        'RegisterModules'
            );

            $create->CreateItem();
            die();

        }

        private function PrepareForSetUpModules($nameModule)
        {
            CreteElement::CreateDirectory('Modules');
            CreteElement::CreateDirectory('Modules/'.$nameModule);

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