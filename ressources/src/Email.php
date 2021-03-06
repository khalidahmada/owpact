<?php
    class __NAME__{
        private $data;
        private $title;

        /**
         * ContactEmail constructor.
         * @param $data
         */
        public function __construct($data,$title)
        {
            $this->data=$data;
            $this->title=$title;
        }

        public function to($to)
        {

            $content = file_get_contents(__DIR__.'/templates/__TEMPLATE_NAME_.php');
            $this->Parse($content);

            $subject = $this->data['subject'];
            wp_mail($to,$this->title ,$content );

        }

        private function Parse(&$content)
        {
            if($this->data){
                foreach($this->data as $key =>  $data)
                {
                    $content = str_replace("[$key]" ,$data , $content);
                }
            }
            return $content;
        }


    }