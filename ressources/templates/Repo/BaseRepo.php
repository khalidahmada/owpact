<?php
    class BaseRepo {

        /**
         * WP_query
         * @param array $args
         * @return WP_Query
         */
        protected static function Q($args){
            return new WP_Query($args);
        }

        /**
         * @param $wp_query
         * @return array posts or false
         */
        protected static function response( WP_Query $wp_query){
            if($wp_query->found_posts){
                return $wp_query->posts;
            }else{
                return false;
            }
        }

        /**
         * Generate meta query object
         * @param $array
         * @param string $operator
         * @return array
         */
        protected static function generate_meta_query($array, $operator = 'IN'){
            $arr = array('relation' => 'AND');

            if($array){
                foreach($array as $item){

                    if(isset($_REQUEST[$item])){
                        $cval = $_REQUEST[$item];
                        if($cval != -1){
                            if(is_array($cval)){
                                $arr[] = array(
                                    'key' => $item,
                                    'value' => $cval,
                                    'compare' => 'IN'
                                );
                            }else{
                                if($cval!=''){
                                    $arr[] = array(
                                        'key' => $item,
                                        'value' => $cval,
                                        'compare' => '='
                                    );
                                }

                            }
                        }

                    }

                }
            }

            return $arr;
        }


        /**
         * Generate Tax query by vars into method Get
         * @param $items
         * @param array $filterTerm
         * @return array
         */
        protected static function generate_tax_query_byvar_get($items, $filterTerm = array()){

            $query_tax = array();

            if($items){
                $query_tax = array('relation' => 'AND');


                foreach($items as $item){
                    if(!in_array($item,$filterTerm)){

                        if( isset($_GET[$item]) && isset($item) && $_GET[$item] != '-1' && $_GET[$item] != -1){
                            if( is_array($_GET[$item]) ){
                                if($_GET[$item][0] != -1 || $_GET[$item][0] != '-1'){
                                    $query_tax[] = array(
                                        'taxonomy' => $item,
                                        'field' => 'slug',
                                        'terms' =>  $_GET[$item],
                                        'operator' => ( is_array($_GET[$item] && count($_GET[$item]) > 1) ? 'OR' : 'IN' )
                                    );
                                }
                            }else{
                                $query_tax[] = array(
                                    'taxonomy' => $item,
                                    'field' => 'slug',
                                    'terms' =>  $_GET[$item],
                                    'operator' => ( is_array($_GET[$item] && count($_GET[$item]) > 1) ? 'OR' : 'IN' )
                                );
                            }

                        }
                    }
                }
            }

            if($filterTerm){
                foreach ($filterTerm as $chekbox => $slug){
                    if(!$items){
                        $query_tax = array('relation' => 'AND');
                    }
                    $query_tax[] = array(
                        'taxonomy' => $chekbox,
                        'field' => 'slug',
                        'terms' =>  $slug,
                        'operator' =>  'IN'
                    );
                }
            }

            return $query_tax;
        }

        /**
         * Generate tax request
         * @param $tax
         * @param $val
         * @param string $key
         * @return array
         */
        protected static function generate_tax_request($tax, $val , $key ='slug'){

            $query_tax = array('relation'=>'AND');
            $query_tax[] = array(
                'taxonomy' => $tax,
                'field' => $key,
                'terms' =>  $val,
                'operator' => ( is_array($tax && count($tax) > 1) ? 'OR' : 'IN' )
            );

            return $query_tax;
        }


        /**
         * Return the Array of Args width given ids object
         * @param $ids
         * @param $post_type
         * @return WP_Query
         */
        protected static function getWithThisIds($ids, $post_type){

            $args = array(
                'post__in'=> $ids,
                'post_type' => $post_type,
                'posts_per_page'      => -1,
                'orderby'             => 'post__in',
            );

            return static::Q($args);
        }

    }