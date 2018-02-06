<?php
    namespace pleaseHandler\AdminChildHandlers;


    use Console;
    use pleaseHandler\ChildHandler;
    use pleaseHandler\CreteElement;
    use pleaseHandler\HandlerPlease;

    class overrideListHandler extends ChildHandler{

        protected $FnName   = 'AcfOverrides';
        protected $PathDist = 'Filters/ACF/Overrides';

        public function __construct($argv)
        {

            parent::__construct('override-list',$argv,'');

            $this->isTrigger();

            $this->Handler();
        }

        /*
         * is Trigger
         */
        protected function isTrigger()
        {
            return $this->argv[2] == $this->trigger;
        }

        /*
         * Handler
         */
        protected function Handler()
        {
            if(!$this->isTrigger()) return;

            if(isset($this->argv[3])){

                $this->createFilterDirectory();
                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[3],$this->PathDist);
                $this->Execute($parse[1],$parse[0]);
                die();

            }else{
                $this->error("Please enter the acf field name");
            }
        }

        /*
         * The logic to Execute this
         */
        protected function Execute($file_name,$path)
        {

            $replacements = array(
                'ACF_LIST_NAME'=>$file_name
            );

            $tpl = __DIR__.'/../../ressources/src/acf/OverrideList.php';

            CreteElement::CreateMethodIntoRegistry($this->FnName);

            $file_dist = $this->getFullName($path,$file_name.'OverrideList',$this->PathDist);

            $create = new CreteElement($file_dist,$replacements,'Override',$tpl,$this->FnName);
            $create->CreateItem();
            die();

        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'override-list',
                'demo' => "php owp acf override-list",
                'doc' => "Override select list of acf field by your own data",
            );
        }

        private function createFilterDirectory()
        {
            CreteElement::CreateDirectory($this->PathDist);
        }


    }