<?php

    abstract class BaseStrateLayout
    {

        // On Layout
        abstract protected function onLayout($layout);

        protected $prefix = "";
        protected $layoutTemplate = "template-parts/strates/";
        protected $field_ACF;
        protected $page_id;


        public function __construct($field_ACF)
        {
            $this->field_ACF = $field_ACF;

        }

        /*
         * Render the bloc
         */
        protected function renderBloc($layout, $datas)
        {
            return $this->render($layout, $datas);
        }

        /*
         * Render the Bloc Method
         */
        protected function render($template, $vars, $echo=true)
        {

            $html='';

            $pathTemplate=$this->layoutTemplate . $this->prefix . $template . '.php';

            ob_start();
                render($pathTemplate, $vars);
                $html=ob_get_contents();
            ob_end_clean();

            if ($echo) {
                echo $html;
            } else {
                return $html;
            }

        }


        /*
         * Render Layout
         */

        public function RenderLayouts($page_id=false)
        {

            if(!$page_id) $page_id = get_the_ID();
            $this->page_id = $page_id;

            if (have_rows($this->field_ACF, $this->page_id)) {

                while (have_rows($this->field_ACF, $this->page_id)) {
                    the_row();
                    $layout=get_row_layout();
                    $this->onLayout($layout);

                }

            }
        }

}