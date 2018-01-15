<?php
    namespace pleaseHandler;


    use Console;

    abstract class HandlerGroup extends HandlerPlease{

        /**
         * AdminGroupHandler constructor.
         */
        protected $handlers = array();
        protected $docs = array();

        /**
         * HandlerGroup constructor.
         */
        public function __construct($trigger,$argv,$scope,$trigger_child)
        {
            parent::__construct($trigger,$argv,$scope,$trigger_child);
        }

        abstract protected function LoadHandlers();


        /*
         * Init Handlers child
         */
        protected function initHandlers()
        {
            $this->RequireLibs();

            $this->LoadHandlers();

            $this->createDocs();

            // Apply handlers
            $this->ApplyHandlers();
        }

        /*
         * Create Doc
         */
        protected function  createDocs(){
            if($this->handlers){
                foreach($this->handlers as $handler){
                    $this->docs[]=$handler::getDoc();
                }
            }
        }

        /*
         * Print Document
         */
        protected function PrintDocs()
        {
            echo "\n";
            foreach ($this->docs as $doc) {
                echo "\n";
                Console::log($doc['trigger'], 'cyan', false);
                Console::log(" ||  ", 'bold', false);
                Console::log($doc['demo'] . ' : ', 'green', false);
                Console::log($doc['doc'], 'white', false, 'brown');
                echo "\n";
            }
            echo "\n";
        }

        /*
         * Apply Handlers
         */
        protected function ApplyHandlers()
        {
            if($this->handlers){
                foreach($this->handlers as $handler){
                    new $handler($this->argv);
                }
            }
        }




        protected function RegisterHandler($handler)
        {
            require_once $handler.'.php';
        }



    }