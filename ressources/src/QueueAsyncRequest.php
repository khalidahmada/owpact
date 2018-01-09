<?php

    class _QUEUE_NAME extends WP_Async_Request{
        /**
         * @var string
         */
        protected $action = '_ACTION_NAME';

        /**
         * Handle
         *
         * Override this method to perform any actions required
         * during the async request.
         */
        protected function handle() {
            // Actions to perform
        }
    }