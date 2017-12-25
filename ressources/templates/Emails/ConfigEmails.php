<?php

    class ConfigEmails {
        /*
         * Prepare Config Email
         */
        public function __construct()
        {
            $this->boot();
        }

        private function boot(){
            $this->PrepareEmailCofnig();
        }

        private function PrepareEmailCofnig()
        {
            add_filter( 'wp_mail_content_type',array($this,'AddHtmlSuportEmail') );
            // Add SMTP on local

            if(isLocal()){
                $this->AddSMTP();
            }
        }

        /*
         * ADD Html Support
         */
        public function AddHtmlSuportEmail()
        {
            return "text/html";
        }


        /*
         * Add SMTP
         */
        private function AddSMTP()
        {
            add_action( 'phpmailer_init', array($this,'configEmailSMTP') );
        }


        /*
         * Config MailTramp SMTP
         */
        public function configEmailSMTP(PHPMailer $phpmailer)
        {

            $phpmailer->IsSMTP();
            $phpmailer->Host = WPTCONF::get()->email->smtp->provider;
            $phpmailer->Port = WPTCONF::get()->email->smtp->port; // could be different
            $phpmailer->Username = WPTCONF::get()->email->smtp->username; // if required
            $phpmailer->Password = WPTCONF::get()->email->smtp->password; // if required
            $phpmailer->SMTPAuth = true; // if required

        }
    }

    new ConfigEmails();