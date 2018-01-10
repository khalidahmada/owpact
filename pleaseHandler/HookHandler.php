<?php
    namespace pleaseHandler;

    class HookHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('hook',$argv,'make');

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){
                $this->Execute($this->argv[3],$this->argv[4]);
            }else{
                $this->error("Please enter two params hook name and the function callback name");
            }
        }

        private function Execute($hook_name,$fnc_name)
        {
            $create = new CreteElement("Hooks/$fnc_name.php",array("_HOOK_NAME" => $hook_name,'__CALLBACK_NAME'=>$fnc_name),'Hooks',__DIR__.'/../ressources/src/Hook.php','RegisterHooks');
            $create->CreateItem();
            die();
        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'hook',
                'demo' => "php owp make hook name hookCallback name ",
                'doc' => "To create your hook into separate file example a hook whene admin_init doSomething with specific logic",
            );
        }
    }

