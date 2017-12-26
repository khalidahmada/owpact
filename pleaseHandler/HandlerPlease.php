<?php
    namespace pleaseHandler;

    abstract class HandlerPlease{

        public $trigger;
        public $argv;
        public $match = false;

        /**
         * HandlerPlease constructor.
         * @param $trigger
         * @param $argv
         */
        public function __construct($trigger, $argv)
        {
            $this->trigger=$trigger;
            $this->argv=$argv;

            if($this->argv[2] == $this->trigger) $this->match = true;

        }


    }