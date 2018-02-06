<?php
    namespace pleaseHandler;


    abstract class ChildHandler extends HandlerPlease{

        abstract protected function isTrigger();
        //abstract protected function Execute($args);
        /**
         * HandlerGroup constructor.
         */
        public function __construct($trigger,$argv,$scope,$trigger_child=array())
        {
            parent::__construct($trigger,$argv,$scope,$trigger_child);
        }
    }