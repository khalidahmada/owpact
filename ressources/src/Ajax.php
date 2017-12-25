<?php
    class _NAME_ extends BaseAjax
    {

        /**
         * _NAME_ constructor.
         */
        public function __construct()
        {
            add_action('wp_ajax__ACTION_',array($this,'_ACTION_Handler'));
            add_action('wp_ajax_nopriv__ACTION_',array($this,'_ACTION_Handler'));
        }

        /*
         * Your Logic Here
         */
        public function _ACTION_Handler()
        {
            $this->q = $_REQUEST;


            // Your Logic Here
            $data = [];

            $this->resp($data);

        }

    }