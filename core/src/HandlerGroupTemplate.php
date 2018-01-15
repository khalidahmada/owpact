<?php
    namespace pleaseHandler;


    use Console;

    class _NAME_GroupHandler extends HandlerGroup{

        /**
         * AdminGroupHandler constructor.
         */
        protected $handlers = array();
        protected $docs = array();


        public function __construct($argv)
        {

            parent::__construct('_TRIGGER_',$argv,'',array(
                // Your Trigger Childs
            ));

            $this->Handler();
        }


        /*
         * Handler Event
         */
        protected function Handler()
        {

            if(!$this->match) return;


            if(isset($this->argv[2])){

                $this->initHandlers();
                $this->PrintDocs();
                die();
            }else{
                $this->error("Please enter command child");
                $this->PrintDocs();
                die();
            }

        }

        /*
        * Required Handler Childs  file
        */
        protected function RequireLibs()
        {
            // Enter your Chids files paths
           // $this->RegisterHandler(__DIR__.'/'.);
        }

        /*
         * Call Your handlers
         * here
         */
        protected function LoadHandlers()
        {
            $this->handlers = array(
                // Your child here
                //childclass::class
            );
        }


        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => '_TRIGGER_',
                'demo' => "php _TRIGGER_",
                'doc' => "Group of commands for package _TRIGGER_",
            );
        }







    }