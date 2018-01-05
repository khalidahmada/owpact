<?php
    namespace pleaseHandler;

    class TraitHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('trait',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){

                // Call Library Core
                $this->CreateFolderTraits();

                $this->Execute($this->argv[3]);


            }else{
                echo "Please Enter the name of trait";
            }
        }

        private function Execute($TraitName)
        {
            $create = new CreteElement("Traits/$TraitName.php",array("_NAME_" => $TraitName),'trait',__DIR__.'/../ressources/src/Trait.php','RegisterTraits');
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