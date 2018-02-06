<?php
    namespace pleaseHandler;

    use Handleable;

    class LayoutHandler extends HandlerPlease{

        use Handleable;

        protected $FnName   = 'InitializeLayoutStrates';
        protected $PathDist = 'Layouts/Strates';
        protected $baseLayout = 'BaseStrateLayout.php';

        public function __construct($argv)
        {
            parent::__construct('layout',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){

                // Create directory
                $this->createStratesDirectory();
                $this->CloneBaseLayoutFolder();

                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[4],$this->PathDist);

                // Register method
                $this->RegisterFilters();

                $this->Execute($this->argv[3],$parse[1],$parse[0]);

            }else{
                $this->error("Please enter two params ACF layout name the LayoutName");
                die();
            }
        }


        private function Execute($acf_name,$fnc_name,$baseDir)
        {

            $replacements = array(
                "_ACF_NAME_" => $acf_name,
                '_LAYOUT_NAME_'=>$fnc_name
            );


            $file_dist = $this->getFullName($baseDir,$fnc_name.'Layout',$this->PathDist);
            $create = new CreteElement($file_dist,$replacements,'Layout',__DIR__.'/../ressources/src/Layout.php',$this->FnName);
            $create->CreateItem();
            die();
        }

        /*
         * Register Function
         * RegisterFilters
         *
         */
        private function RegisterFilters(){
            CreteElement::CreateMethodIntoRegistry($this->FnName);
        }

        private function createStratesDirectory()
        {
            CreteElement::CreateDirectory($this->PathDist);

            // Create Strates folder
            $this->CreateFolderIntoDir('template-parts/strates');
        }

        /*
         * Clone Folder Base
         * Layout
         */
        private function CloneBaseLayoutFolder(){

            $status = $this->copyFileToDir('extra/Layouts/'.$this->baseLayout,$this->PathDist.'/'.$this->baseLayout);

            if($status){
              $basePath = $this->PathDist."/BaseStrateLayout.php";
              CreteElement::RegisterModuleIntoRegisterBase($basePath);
            }

        }


        /*
         * get The Documentation
         */
        public static function getDoc()
        {
            return array(
                'trigger' => 'filter',
                'demo' => "php owp make filter name filterCallback name ",
                'doc' => "To create your filter into separate file example a filter for custom logic",
            );
        }


    }

