<?php
    trait TraitRepo{

        // Return Basic Args
        public static function args($per_page=-1)
        {
            return array(
                'post_type' => static::$type,
                'posts_per_page' => $per_page,
                'post_status' => 'publish'
            );
        }

        /*
         * Get All
         */
        public static function getAll($per_page=-1,$exclude=array(),$use_paginate=true){

            $args = static::args($per_page);

            if($exclude){
                $args['post__not_in'] = $exclude;
            }

            if($use_paginate){
                $args['paged'] = get_query_var( 'paged' )  > 1  ? absint( get_query_var( 'paged' ) ) : 1;
            }

            $query = static::Q($args);

            return $query;
        }
    }