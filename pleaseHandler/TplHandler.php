<?php
    namespace pleaseHandler;

    use Handleable;
    use OWPactConfig;

    class TplHandler extends HandlerPlease{

        use Handleable;

        protected $FnName   = 'InitializeLayoutStrates';
        protected $PathDist = 'Layouts/Strates';
        protected $baseLayout = 'BaseStrateLayout.php';

        protected $tpl_source = false;

        public function __construct($argv)
        {

            parent::__construct('tpl',$argv,'');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            $this->tpl_source = OWPactConfig::getTplConfig();


            var_dump($this->tpl_source);

            if(!$this->tpl_source){
                $this->error("There is no file please check your config/config.json and param tpl_config");
                die();
            }

            // if is Set
            if(isset($this->argv[3]) && isset($this->argv[4])){

                $cmd = $this->argv[3];

                if($tpl_object = $this->CmdExist($cmd)){
                    if($this->ObjectTplIstValid($tpl_object)){
                        $file_src = OWPactConfig::getPathFromOwp($tpl_object->src);

                        if(is_file($file_src)){
                            $this->HandleTplWithObject($tpl_object);
                        }

                    }else{
                        $this->error("Please your entry $cmd need parms  src,dist,fn");
                        die();
                    }
                }else{
                    $keys = $this->getKeysOfConfig();
                    $this->error("Please check your templates key or add new your available is $keys");
                    die();
                }
            }

            $this->error("Please enter template name and params");
            die();


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

        /*
         * Cmd Exist
         */
        protected function CmdExist($cmd){

           $tpl = $this->tpl_source;
            if($tpl){
                if(isset($tpl->alias) && $tpl->alias){
                    foreach($tpl->alias as $tpl_item){
                        if(isset($tpl_item->key) && $tpl_item->key == $cmd){
                            return $tpl_item;
                        }
                    }
                }
            }

            return false;
        }

        protected function getKeysOfConfig(){

            $tpl = $this->tpl_source;
            $keys = false;

            if($tpl){
                if(isset($tpl->alias) && $tpl->alias){
                    foreach($tpl->alias as $tpl_item){
                        $keys[] = $tpl_item->key;
                    }
                }
            }

            return join(',',$keys);

        }

        private function ObjectTplIstValid($tpl_object)
        {
            if(isset($tpl_object->src) && isset($tpl_object->dist) && isset($tpl_object->fn)){
                return true;
            }else{
                return false;
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


        private function HandleTplWithObject($tpl_object)
        {
            CreteElement::CreateDirectory($tpl_object->dist);
            $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[4],$tpl_object->dist);

            CreteElement::CreateMethodIntoRegistry($tpl_object->fn);

            $this->Execute($this->argv[3],$parse[1],$parse[0]);
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
                'trigger' => 'tpl',
                'demo' => "php owp tpl ",
                'doc' => "",
            );
        }



    }

