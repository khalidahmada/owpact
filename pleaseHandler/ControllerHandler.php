<?php
    namespace pleaseHandler;

    use Handleable;

    class ControllerHandler extends HandlerPlease{

        use Handleable;

        public function __construct($argv)
        {
            parent::__construct('controller',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){

                // Create directory
                $this->createFilterDirectory();
                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[3],'Controllers');

                // Register method
                $this->RegisterFilters();

                $this->Execute($parse[1],$parse[0]);

            }else{
                $this->error("Please controller name");
                die();
            }
        }


        private function Execute($fnc_name,$baseDir)
        {

            $replacements = array(
                '__NAME__'=>$fnc_name
            );


            $file_dist = $this->getFullName($baseDir,$fnc_name,'Controllers');

            $create = new CreteElement($file_dist,$replacements,'Controller',__DIR__.'/../ressources/src/Controller.php','RegisterControllers');
            $create->CreateItem();
            die();
        }

        /*
         * Register Function
         * RegisterFilters
         *
         */
        private function RegisterFilters(){
            CreteElement::CreateMethodIntoRegistry('RegisterControllers');
        }

        /*
         * get The Documentation
         */
        public static function getDoc()
        {
            return array(
                'trigger' => 'controller',
                'demo' => "php owp make controller Your controller name",
                'doc' => "Custom controller",
            );
        }

        private function createFilterDirectory()
        {
            CreteElement::CreateDirectory('Controllers');
        }
    }

