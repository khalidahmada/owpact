<?php
    abstract class BaseAjax{

        /**
         * BaseAjax constructor.
         * @var object of request
         */
        protected $req;

        /**
         * Error map
         * @var array
         * Error
         */
        protected $errors_messages = array();



        public function __construct()
        {

        }

        /**
         * Return response
         * @param $data
         * @return void
         */
        public function resp($data)
        {
            echo json_encode($data);
            die();
        }


        /**
         *  get props
         * @param $param
         * @return mixed|bool
         */
        protected function prop($param){

            if(isset($this->req[$param])) return $this->req[$param];
            return false;
        }


        /**
         * Check of nonce is verify
         * @return false|int
         */
        protected function nonce_verify(){
            return wp_verify_nonce($_REQUEST['_wpnonce'],static::$nonce_verify);
        }

        /**
         * Return the action name
         * @return mixed
         */
        public static function action(){
            return static::$action_ajax;
        }

        /**
         * Return the nonce name
         * @return mixed
         */
        public static function nonce(){
            return static::$nonce_verify;
        }

        /**
         * Push to error array errors
         * @param $error
         * @return array
         */
        protected function push_error($error){
            $this->errors_messages[] = $error;
            return $this->errors_messages;
        }

    }