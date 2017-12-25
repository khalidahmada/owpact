<?php
    class BaseEmail{

        /**
         * BaseEmail constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        public function boot()
        {
            require_once('ConfigEmails.php');
        }
    }

    new BaseEmail();

