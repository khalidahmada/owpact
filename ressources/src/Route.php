<?php
    class _HANDLER_{

        /**
         * _HANDLER_ constructor.
         */
        public function __construct()
        {
            Routes::map('_PATH_', array($this,'_HANDLER_Handler'));
        }

        public function _HANDLER_Handler($params){
            // Your Logic Here



            // End Logic
            die();
        }
    }

    new _HANDLER_();