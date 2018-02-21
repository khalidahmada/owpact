<?php
    namespace pleaseHandler;

    class TraitHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('trait',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){

                // Call Library Core
                $this->CreateFolderTraits();
                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[3],'Traits');

                $this->Execute($parse[1],$parse[0]);


            }else{
                $this->error("Please Enter the name of trait");
                die();
            }
        }

        private function Execute($TraitName,$baseDir)
        {
            $replacements = array("_NAME_" => $TraitName);

            $file_dist = $this->getFullName($baseDir,$TraitName,'Traits');
            $create = new CreteElement($file_dist,$replacements,'trait',__DIR__.'/../ressources/src/Trait.php','RegisterTraits');
            $create->CreateItem();
            die();

        }

        private function CreateFolderTraits()
        {
            CreteElement::CreateDirectory('Traits');
        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'trait',
                'demo' => "php owp trait TraitName",
                'doc' => "Create easy Your Trait"
            );
        }
    }