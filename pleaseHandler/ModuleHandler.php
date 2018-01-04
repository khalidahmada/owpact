<?php
    namespace pleaseHandler;


    class ModuleHandler extends HandlerPlease{


        public function __construct($argv)
        {
            parent::__construct('module',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){
                $this->PrepareForSetUpModules($this->argv[3]);
                $this->Execute($this->argv[3]);
            }else{
                echo "Please enter your module name";
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


    }