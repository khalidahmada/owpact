<?php
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
    }