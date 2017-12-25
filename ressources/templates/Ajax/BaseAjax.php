<?php
    class BaseAjax{

        /**
         * BaseAjax constructor.
         */
        public $q;

        public function __construct()
        {

        }

        /*
         * Response
         */
        public function resp($data)
        {
            echo json_encode($data);
            die();
        }
    }