<?php
    namespace pleaseHandler;

    use Handleable;

    class HookHandler extends HandlerPlease{

        use Handleable;

        public function __construct($argv)
        {
            parent::__construct('hook',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){

               $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[4],'Hooks');

               $this->Execute($this->argv[3],$parse[1],$parse[0]);

            }else{
                $this->error("Please enter two params hook name and the function callback name");
                die();
            }
        }


        private function Execute($hook_name,$fnc_name,$baseDir)
        {

            $replacements = array(
                                    "_HOOK_NAME" => $hook_name,
                                    '__CALLBACK_NAME'=>$fnc_name
            );


           $file_dist = $this->getFullName($baseDir,$fnc_name,'Hooks');

            $create = new CreteElement($file_dist,$replacements,'Hooks',__DIR__.'/../ressources/src/Hook.php','RegisterHooks');
            $create->CreateItem();
            die();
        }

        /*
         * get The Documentation
         */
        public static function getDoc()
        {
            return array(
                'trigger' => 'hook',
                'demo' => "php owp make hook name hookCallback name ",
                'doc' => "To create your hook into separate file example a hook whene admin_init doSomething with specific logic",
            );
        }
    }

