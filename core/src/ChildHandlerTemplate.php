<?php
    namespace pleaseHandler\AdminChildHandlers;


    use Console;
    use pleaseHandler\ChildHandler;
    use pleaseHandler\CreteElement;
    use pleaseHandler\HandlerPlease;

    class _NAME_Handler extends ChildHandler{


        public function __construct($argv)
        {

            parent::__construct('c',$argv,'');

            $this->isTrigger();
            $this->Handler();
        }

        /*
         * Is Trigger Your Logic to test
         * If is the current Handler
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

            if(isset($this->argv[2])){
                $this->Execute();
                die();
            }else{
                $this->error("Alert for params");
            }
        }

        /*
         * Execution Job
         */
        protected function Execute()
        {
            // Test execution
            $this->success('welcome');
            die();

        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => '_TRIGGER_',
                'demo' => "php owp _PARENT_TRIGGER_ _TRIGGER_",
                'doc' => "Your Doc for _TRIGGER_",
            );
        }


    }