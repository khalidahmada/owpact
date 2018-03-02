<?php
    class _NAME_ extends BaseAjax
    {
        /*
         *
         */
        protected static $action_ajax ="_ACTION_";
        protected static $nonce_verify = "nonce-_ACTION_";


        /**
         * _NAME_ constructor.
         */
        public function __construct()
        {
            add_action('wp_ajax_'.static::$action_ajax,array($this,'_NAME_Handler'));
            add_action('wp_ajax_nopriv_'.static::$action_ajax,array($this,'_NAME_Handler'));
        }

        /*
         * Your Logic Here
         */
        public function _NAME_Handler()
        {
            $this->req = $_REQUEST;


            // Your Logic Here
            $data = array();

            $this->resp($data);

        }

    }

    new _NAME_();