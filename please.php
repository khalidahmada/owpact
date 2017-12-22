<?php
    class OWPactPlease{

        /**
         * OWPactPlease constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        private function boot()
        {

            $this->RegisterModules();

            echo PublishHandler::checkDir();

            die();
        }

        private function RegisterModules()
        {
            $this->RegisterModule('base');
            $this->RegisterModule('pleaseHandler/PublishHandler');

        }

        private function RegisterModule($module)
        {
            require_once $module.'.php';
        }

    }

    new OWPactPlease();