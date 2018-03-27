<?php
/*
 * @note
 * ********************        Important        ****************************************** /
 * ************ Please don't add nothing here this will override on update TraitRepo ******
 * ************ If you want add custom trait create your own Trait    *********************
 *****************************************************************************************/
    trait TraitRepo{

        /**
         * Return base params of Args for WP_Query
         * @param int $per_page
         * @param array $args
         * @return array
         */
        public static function args($per_page=-1,$args = array()){

            $defaults =  array(
                'post_type' => static::$type,
                'posts_per_page' => $per_page,
                'post_status' => 'publish'
            );

           $args =  wp_parse_args($args,$defaults);

            return $args;
        }

        /**
         * Get
         * @param int $per_page
         * @param array $exclude
         * @param bool $use_paginate
         * @return WP_Query
         */
        public static function getAll($per_page=-1, $exclude=array(), $use_paginate=true){

            $args = static::args($per_page);

            if($exclude) $args['post__not_in'] = $exclude;

            if($use_paginate)$args['paged'] = get_query_var( 'paged' )  > 1  ? absint( get_query_var( 'paged' ) ) : 1;

            $query = static::Q($args);

            return $query;
        }

        /**
         * Return meta query of provided key.
         *
         * acf meta query true/false
         * @param $key
         * @return array meta query.
         */
        protected static function is($key)
        {
            return array(
                'key'=>$key,
                'value'=>'1',
                'compare' => '=='
            );
        }

        /**
         * return Object term for tax query
         * @param $tx_name
         * @param $val
         * @param string $field
         * @param string $operator
         * @return array
         */
        protected static function tx_item($tx_name, $val, $field='term_id', $operator='='){
            return array(
                'taxonomy' => $tx_name,
                'field'    => $field,
                'terms'    => $val,
                'operator' => $operator,
            );
        }

        /**
         * meta query item
         * @param $key
         * @param $value
         * @param string $compare
         * @return array
         */
        protected static function mq_item($key, $value, $compare='='){
            return array(
                'key'       => $key,
                'value'     => $value,
                'compare'   => $compare
            );
        }

        /**
         * get All with extra args
         * @param array $args
         * @return mixed
         */
        public static function all($args = array())
        {
            $_args = self::args(-1,$args);

            return self::Q($_args);
        }

        /**
         * Find to send Extra params
         * @param $args
         * @return mixed
         */
        public static function find($args)
        {
            $_args = self::args(-1,$args);

            return self::Q($_args);
        }

        /**
         * First Element
         * @param array $args
         * @return bool
         */
        public static function first($args = array())
        {

            $_args = self::args(1,$args);

            $results = self::Q($_args);

            if($posts = $results->posts) return $posts[0];

            return false;

        }
        /**
         * last Element
         * @param array $args
         * @return bool
         */
        public static function last($args = array())
        {

            $_args = self::args(-1,$args);

            $results = self::Q($_args);

            if($posts = $results->posts) return end($posts);

            return false;

        }
    }