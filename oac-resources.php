<?php
/**
* Plugin Name: OAC Resources
* Plugin URI: https://www.leveragedigitalmedia.com/
* Description: This is the OAC Resources plugin.
* Version: 1.0
* Author: Wuilly Vargas
* Author URI: https://www.linkedin.com/in/wuilly-vargas/
**/

/* ---------------------------------------------------------------------------
 * Function to load script and css files
 * --------------------------------------------------------------------------- */
function load_tabs_file(){
    wp_enqueue_style('tabs-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), '2.42' );
    wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ) . 'js/tabs-ajax.js', array('jquery'), '2.42');
    wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script('jquery-ui-autocomplete');
}
add_action( 'wp_enqueue_scripts','load_tabs_file' );

add_image_size( 'item-grid', 250, 250, true );

/* ---------------------------------------------------------------------------
 * Function to cut text strings
 * --------------------------------------------------------------------------- */
function wv_trim_text($text, $limit=100){   
    $text = trim($text);
    $text = strip_tags($text);
    $size = strlen($text);
    $result = '';
    if($size <= $limit){
        return $text;
    }else{
        $text = substr($text, 0, $limit);
        $words = explode(' ', $text);
        $result = implode(' ', $words);
        $result .= '...';
    }   
    return $result;
}

/* ---------------------------------------------------------------------------
 * [wv_tab_resource_ajax]
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'wv_tab_resource_ajax' ) ){
    function wv_tab_resource_ajax( $attr, $content = null )
    {
        ob_start();
        extract(shortcode_atts(array(
            'count'             => 12,
            'excerpt'           => '1',
            'cat_list'          => ''
        ), $attr));
        
        $args = array(
            'post_type'             => 'resources',
            'posts_per_page'        => intval($count),
            'no_found_rows'         => 1,
            'post_status'           => array( 'publish'),
            'ignore_sticky_posts'   => 0,
        );
        $query = new WP_Query( $args );?>
        <input type="hidden" value="<?php echo $cat_list; ?>" id="cat_list">
        <div id="resource-box">
            <?php echo do_shortcode('[wv_filtersTop cat_list='.$cat_list.']'); ?>               
            <div class="row">
                <div class="col-sm-3">
                    <div class="filter-sidebar">
                        <h3>Filter Results</h3>
                        <ul id="video-resources" class="acordion">
                            <li class="static level-0 selected"><a class="static menu-item-li" href="#">
                                    <span class="fa-stack fa-lg">
                                      <i class="fa fa-circle-thin fa-stack-2x"></i>
                                      <i class="fa fa-chevron-down fa-stack-1x"></i>
                                    </span>Topic Area
                                </a>
                                <?php 
                                    $terms_aiovg = get_terms( 'aiovg_categories' );
                                    echo '<ul>';
                                        foreach( $terms_aiovg as $item ){
                                          if($item->parent == 0){
                                            $child = '';
                                            foreach($terms_aiovg as $itm) if($item->term_id == $itm->parent){
                                              $child .= '<p class="mb-0"><label class="label-checkbox" for="'.$itm->term_id.'"><input class="input-checkbox input-aiovg" type="checkbox" value="'.$itm->name.'" name="aiovg" id="'.$itm->term_id.'" data-slug="'.$itm->slug.'" data-taxonomy="aiovg_categories">' . $itm->name.'</label></p>';
                                            }
                                            echo '<li class="selected">';
                                            echo '<label class="label-checkbox" for="'.$item->term_id.'"><input class="input-checkbox input-aiovg" type="checkbox" value="'.$item->name.'" name="aiovg" id="'.$item->term_id.'" data-slug="'.$item->slug.'" data-taxonomy="aiovg_categories">';
                                             echo $item->name.'</label>';
                                            if($child){
                                              echo '<a class="has-child" href="#">&nbsp;<i class="fa child-opened" aria-hidden="true"></i></a>';
                                              echo '<div class="cat-child ml-3" style="display: none">' . $child . '</div>';
                                            }
                                            echo '</li>';
                                          }
                                      }
                                    echo '</ul>';                         
                                ?>
                            </li>                            
                        </ul>
                        <ul id="advocasy-resources" class="acordion">
                            <li class="static level-0 selected"><a class="static menu-item-li" href="#">
                                    <span class="fa-stack fa-lg">
                                      <i class="fa fa-circle-thin fa-stack-2x"></i>
                                      <i class="fa fa-chevron-down fa-stack-1x"></i>
                                    </span>Advocacy Resources
                                </a>
                                <?php 
                                    $terms_advocasy = get_terms( array('taxonomy' => 'advocacy_resources') );
                                    echo '<ul>';
                                        foreach( $terms_advocasy as $item ){
                                            if($item->parent == 0){
                                              $child = '';
                                              foreach($terms_advocasy as $itm) if($item->term_id == $itm->parent){
                                                $child .= '<p class="mb-0"><label class="label-checkbox" for="'.$itm->term_id.'"><input class="input-checkbox input-advocasy" type="checkbox" value="'.$itm->name.'" name="advocasy" id="'.$itm->term_id.'" data-slug="'.$itm->slug.'" data-taxonomy="advocacy_resources">' . $itm->name.'</label></p>';
                                              }
                                              echo '<li class="selected">';
                                              echo '<label class="label-checkbox" for="'.$item->term_id.'"><input class="input-checkbox input-advocasy" type="checkbox" value="'.$item->name.'" name="advocasy" id="'.$item->term_id.'" data-slug="'.$item->slug.'" data-taxonomy="advocacy_resources">';
                                              echo $item->name.'</label>';
                                              if($child){
                                                echo '<a class="has-child" href="#">&nbsp;<i class="fa child-opened" aria-hidden="true"></i></a>';
                                                echo '<div class="cat-child ml-3" style="display: none">' . $child . '</div>';
                                              }
                                              echo '</li>';
                                            }
                                        }
                                    echo '</ul>';
                                ?>
                            </li>                            
                        </ul>
                        <ul id="languages" class="acordion">
                            <li class="static level-0"><a class="static menu-item-li" href="#">
                                    <span class="fa-stack fa-lg">
                                      <i class="fa fa-circle-thin fa-stack-2x"></i>
                                      <i class="fa fa-chevron-down fa-stack-1x"></i>
                                    </span>Languages
                                </a>
                                <?php 
                                    $terms_languages = get_terms( 'resources_languages' );
                                    echo '<ul>';
                                        foreach( $terms_languages as $item ) : 
                                            echo '<li class="selected">';
                                                echo '<label class="label-checkbox" for="'.$item->term_id.'"><input class="input-checkbox input-language" type="checkbox" value="'.$item->name.'" name="language" id="'.$item->term_id.'" data-slug="'.$item->slug.'" data-taxonomy="resources_languages">';
                                                echo $item->name.'</label>';
                                            echo '</li>';
                                        endforeach;
                                    echo '</ul>';                            
                                ?>
                            </li>
                        </ul>
                        <a href="#" class="btn btn-block btn-clear">CLEAR ALL FILTERS</a>
                    </div>                        
                </div>
                <div class="col-sm-9">
                    <div class="resource-grid-container">
                        <?php
                        if($_COOKIE['oac_banner_close'] != 'yes' ){
                        ?>
                        <div class="banner" style="background-image: url('<?php echo get_field('background_image')['url']?>');">
                            <a href="#" id="close-banner" class="close-banner"><i class="fa fa-close"></i></a>
                            <?php //echo get_the_post_thumbnail('33542','full',array('class'=>'img-responsive','id' => 'img-banner'));?>
                            <div id="banner-text"><?php echo get_field('welcome_text');?></div>
                        </div>
                        <?php
                        }
                        ?>
                        <div id="container-tab-resource" class="">
                            <?php 
                                $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
                                $taxonomy = 'resources_types';
                                $terms = get_terms( array(
                                          'taxonomy' => $taxonomy,
                                          'include' => $cat_list,
                                          'hide_empty'  => false, 
                                        ) );                        
                                foreach( $terms as $term ) { 
                                    $count = 0;
                                    ?>
                                    <div class="box-type">
                                        <h2><?php echo $term->name; ?></h2>
                                        <?php 
                                        $posts = new WP_Query( 
                                            array( 
                                                'taxonomy'       => $taxonomy, 
                                                'term'           => $term->slug, 
                                                'posts_per_page' => 3,
                                                'post_status'    => array( 'publish'),
                                                'paged'          => $paged 
                                            )
                                        );
                                        if( $posts->have_posts() ) {
                                            echo '<div id="box-'.strtolower($term->name).'" class="row">';
                                            while( $posts->have_posts() ) {
                                                $posts->the_post();
                                                require('partials/content-loop.php');
                                                $count++;
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                        <?php if ($count>=3): ?>
                                            <div class="sectloadmore">
                                                <div class="row">
                                                    <div class="col-sm-4 offset-sm-4">
                                                        <a href="#" class="btn btn-block btn-morepost" id="show-more-post" data-type="<?php echo strtolower($term->name);?>" data-id="<?php echo $term->term_id;?>">Load More <?php echo $term->name;?></a>
                                                    </div>
                                                </div> 
                                            </div>
                                        <?php endif; ?>  
                                        <input type="hidden" id="paged-<?php echo strtolower($term->name);?>" value="<?php echo $paged; ?>">                               
                                    </div>
                                    <?php
                                }               
                                wp_reset_postdata();
                                $args = array(/*
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'resources_types',
                                            'terms' => array($id),
                                            'field' => 'term_id',
                                        ),
                                    ),*/
                                    'post_type'         => 'aiovg_videos',
                                    'posts_per_page'    => 3,
                                    'post_status'       => array( 'publish'),
                                    'paged'             => $paged,
                                    'order'             => $order,
                                    'orderby'           => $orderby,
                                );

                                $query = new WP_Query($args);
                                echo '<div class="box-type">';
                                    echo '<h2>Videos</h2>';
                                    if ($query->have_posts()) {
                                        $count = 0;
                                        
                                            echo '<div id="box-videos" class="row">';
                                            
                                            while ($query->have_posts()) {
                                                $query->the_post(); 
                                                $term->name = 'Videos';
                                                require 'partials/content-loop.php'; 
                                                $count++; 
                                            }
                                            echo '</div>';
                                            if ($count>=3): ?>
                                                <div class="sectloadmore">
                                                    <div class="row">
                                                        <div class="col-sm-4 offset-sm-4">
                                                            <a href="#" class="btn btn-block" id="showmorepost-video" data-type="videos" data-id="<?php echo $id;?>">Load More Videos</a>
                                                        </div>
                                                    </div> 
                                                </div>
                                            <?php endif;
                                            
                                    }else{
                                        echo "<p>Sorry videos not found</p>";
                                    }
                                    echo '<input type="hidden" id="paged-videos" value="'.$paged.'">';
                                echo '</div>';
                                wp_reset_postdata();
                            ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        return ob_get_clean();
    }
}
add_shortcode( 'wv_tab_resource_ajax', 'wv_tab_resource_ajax' );


function tab_resource() {

    $id = $_POST['term_id'];
    $name = $_POST['name'];
    $order = $_POST['order'];
    $orderby = $_POST['orderby'];
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $args = array(
        'tax_query' => array(
            array(
                'taxonomy' => 'resources_types',
                'terms' => array($id),
                'field' => 'term_id',
            ),
        ),
        'post_type'         => 'resources',
        'posts_per_page'    => 9,
        'post_status'       => array( 'publish'),
        'paged'             => $paged,
        'order'             => $order,
        'orderby'           => $orderby,
    );

    $query = new WP_Query($args);
    echo '<div class="box-type">';
        echo '<h2>'.$name.'</h2>';
        if ($query->have_posts()) {
            $count = 0;
            
                echo '<div id="box-'.strtolower($name).'" class="row">';
                
                while ($query->have_posts()) {
                    $query->the_post(); 
                    require 'partials/content-loop.php'; 
                    $count++; 
                }
                echo '</div>';
                if ($count>=3): ?>
                    <div class="sectloadmore">
                        <div class="row">
                            <div class="col-sm-4 offset-sm-4">
                                <a href="#" class="btn btn-block btn-morepost" id="show-more-post" data-type="<?php echo str_replace(' ','-',strtolower($name));?>" data-id="<?php echo $id;?>">Load More <?php echo $name;?></a>
                            </div>
                        </div> 
                    </div>
                <?php endif;
                
        }else{
            echo "<p>Sorry ".$name." not found</p>";
        }
        echo '<input type="hidden" id="paged-'.str_replace(' ','-',strtolower($name)).'" value="'.$paged.'">';
    echo '</div>';
    wp_reset_postdata();
    die(); 
}
add_action( 'wp_ajax_tab_resource', 'tab_resource' );
add_action( 'wp_ajax_nopriv_tab_resource', 'tab_resource' );

function tab_video() {

    $id = $_POST['term_id'];
    $name = $_POST['name'];
    $order = $_POST['order'];
    $orderby = $_POST['orderby'];
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $args = array(/*
        'tax_query' => array(
            array(
                'taxonomy' => 'resources_types',
                'terms' => array($id),
                'field' => 'term_id',
            ),
        ),*/
        'post_type'         => 'aiovg_videos',
        'posts_per_page'    => 9,
        'post_status'       => array( 'publish'),
        'paged'             => $paged,
        'order'             => $order,
        'orderby'           => $orderby,
    );

    $query = new WP_Query($args);
    echo '<div class="box-type">';
        echo '<h2>'.$name.'</h2>';
        if ($query->have_posts()) {
            $count = 0;
            
                echo '<div id="box-'.strtolower($name).'" class="row">';
                
                while ($query->have_posts()) {
                    $query->the_post(); 
                    require 'partials/content-loop.php'; 
                    $count++; 
                }
                echo '</div>';
                if ($count>=3): ?>
                    <div class="sectloadmore">
                        <div class="row">
                            <div class="col-sm-4 offset-sm-4">
                                <a href="#" class="btn btn-block" id="showmorepost-video" data-type="<?php echo strtolower($name);?>" data-id="<?php echo $id;?>">Load More <?php echo $name;?></a>
                            </div>
                        </div> 
                    </div>
                <?php endif;
                
        }else{
            echo "<p>Sorry ".$name." not found</p>";
        }
        echo '<input type="hidden" id="paged-'.strtolower($name).'" value="'.$paged.'">';
    echo '</div>';
    wp_reset_postdata();
    die(); 
}
add_action( 'wp_ajax_tab_video', 'tab_video' );
add_action( 'wp_ajax_nopriv_tab_video', 'tab_video' );

function tab_all_resource() {
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $taxonomy = 'resources_types';
    $cat_list = $_POST['cat_list'];
    $terms = get_terms( array(
              'taxonomy' => $taxonomy,
              'include' => $cat_list,
              'hide_empty'  => false, 
            ) );                         
    foreach( $terms as $term ) { 
        $count = 0;
        ?>
        <div class="box-type">
            <h2><?php echo $term->name; ?></h2>
            <?php 
            $posts = new WP_Query( 
                array( 
                    'taxonomy'       => $taxonomy, 
                    'term'           => $term->slug, 
                    'posts_per_page' => 3,
                    'post_status'    => array( 'publish'),
                    'paged'          => $paged,
                    'order'          => $order,
                    'orderby'        => $orderby,
                )
            );
            if( $posts->have_posts() ) {
                echo '<div id="box-'.strtolower($term->name).'" class="row">';
                while( $posts->have_posts() ) {
                    $posts->the_post();
                    require('partials/content-loop.php');
                    $count++;
                }
                echo '</div>';
            }
            ?>
            <?php if ($count>=3): ?>
                <div class="sectloadmore">
                    <div class="row">
                        <div class="col-sm-4 offset-sm-4">
                            <a href="#" class="btn btn-block btn-morepost" id="show-more-post" data-type="<?php echo strtolower($term->name);?>" data-id="<?php echo $term->term_id;?>">Load More <?php echo $term->name;?></a>
                        </div>
                    </div> 
                </div>
            <?php endif; ?>  
            <input type="hidden" id="paged-<?php echo strtolower($term->name);?>" value="<?php echo $paged; ?>">                                     
        </div>
        <?php
    }                 
    wp_reset_postdata();
    $args = array(/*
        'tax_query' => array(
            array(
                'taxonomy' => 'resources_types',
                'terms' => array($id),
                'field' => 'term_id',
            ),
        ),*/
        'post_type'         => 'aiovg_videos',
        'posts_per_page'    => 3,
        'post_status'       => array( 'publish'),
        'paged'             => $paged,
        'order'             => $order,
        'orderby'           => $orderby,
    );

    $query = new WP_Query($args);
    echo '<div class="box-type">';
        echo '<h2>Videos</h2>';
        if ($query->have_posts()) {
            $count = 0;
            
                echo '<div id="box-videos" class="row">';
                
                while ($query->have_posts()) {
                    $query->the_post(); 
                    $term->name = 'Videos';
                    require 'partials/content-loop.php'; 
                    $count++; 
                }
                echo '</div>';
                if ($count>=3): ?>
                    <div class="sectloadmore">
                        <div class="row">
                            <div class="col-sm-4 offset-sm-4">
                                <a href="#" class="btn btn-block" id="showmorepost-video" data-type="videos" data-id="<?php echo $id;?>">Load More Videos</a>
                            </div>
                        </div> 
                    </div>
                <?php endif;
                
        }else{
            echo "<p>Sorry videos not found</p>";
        }
        echo '<input type="hidden" id="paged-videos" value="'.$paged.'">';
    echo '</div>';
    wp_reset_postdata();
    die(); 
}
add_action( 'wp_ajax_tab_all_resource', 'tab_all_resource' );
add_action( 'wp_ajax_nopriv_tab_all_resource', 'tab_all_resource' );

function checkTaxonomy(){
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;    
    $custom_taxonomy = $_POST['taxonomy'];
    $term_id = $_POST['term_id'];
    if(!empty($term_id)){        
        $tax_array = explode( ',', $term_id );
    }else{
        $tax_array = '';
    }
    $taxonomy = 'resources_types';
    $cat_list = $_POST['cat_list'];

    $terms = get_terms( array(
              'taxonomy' => $taxonomy,
              'include' => $cat_list,
              'hide_empty'  => false, 
            ) );
    foreach ($terms as $term) {
        $count = 0;
        ?>
        <div class="box-type">
            <h2><?php echo $term->name; ?></h2>
            <?php 
            wp_reset_query();
            $args = array(
                'post_type'      => 'resources',
                'posts_per_page' => 3,
                'paged'          => $paged,
                'post_status'    => array( 'publish'),
            );

            if(!empty($tax_array)){
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $term->slug
                    ),
                    array(
                        'taxonomy' => $custom_taxonomy,
                        'field' => 'term_id',
                        'terms' => $tax_array,                    
                    ),
                );            
            }else{
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $term->slug
                    ),
                );  
            }

            $loop = new WP_Query($args);
            if($loop->have_posts()) {
                echo '<div id="box-'.strtolower($term->name).'" class="row" data-taxonomy="'.$cat_list.'">';
                while($loop->have_posts()) : $loop->the_post();
                    require('partials/content-loop.php');
                    $count++;
                endwhile;
                echo '</div>';
            }else{
                echo "<p>Sorry ".$term->name." not found</p>";
            }
            ?>
            <?php if ($count>=3): ?>
                <div class="sectloadmore">
                    <div class="row">
                        <div class="col-sm-4 offset-sm-4">
                            <a href="#" class="btn btn-block btn-morepost" id="show-more-post" data-type="<?php echo strtolower($term->name);?>" data-id="<?php echo $term->term_id;?>">Load More <?php echo $term->name;?></a>
                        </div>
                    </div> 
                </div>
            <?php endif; ?>  
            <input type="hidden" id="paged-<?php echo strtolower($term->name);?>" value="<?php echo $paged; ?>">                                     
        </div>
        <?php
    }
    wp_reset_postdata();
    $args = array(
        'post_type'         => 'aiovg_videos',
        'posts_per_page'    => 3,
        'post_status'       => array( 'publish'),
        'paged'             => $paged,
        'order'             => $order,
        'orderby'           => $orderby,
    );

    if(!empty($tax_array)){
        $args['tax_query'] = array(
            array(
                'taxonomy' => $custom_taxonomy,
                'terms' => $tax_array,
                'field' => 'term_id',
            ),
        );
    }

    $query = new WP_Query($args);
    echo '<div class="box-type">';
        echo '<h2>Videos</h2>';
        if ($query->have_posts()) {
            $count = 0;            
                echo '<div id="box-videos" class="row">';                
                while ($query->have_posts()) {
                    $query->the_post(); 
                    $term->name = 'Videos';
                    require 'partials/content-loop.php'; 
                    $count++; 
                }
                echo '</div>';
                if ($count>=3): ?>
                    <div class="sectloadmore">
                        <div class="row">
                            <div class="col-sm-4 offset-sm-4">
                                <a href="#" class="btn btn-block" id="showmorepost-video" data-type="videos" data-id="<?php echo $id;?>">Load More Videos</a>
                            </div>
                        </div> 
                    </div>
                <?php endif;
                
        }else{
            echo "<p>Sorry videos not found</p>";
        }
        echo '<input type="hidden" id="paged-videos" value="'.$paged.'">';
    echo '</div>';
    wp_reset_postdata();
    die();
}
add_action( 'wp_ajax_checkTaxonomy', 'checkTaxonomy' );
add_action( 'wp_ajax_nopriv_checkTaxonomy', 'checkTaxonomy' );


function load_more_resource() {

    $paged = $_POST['paged'];
    $name = $_POST['name'];
    $id = $_POST['term_id'];
    $args = array(
        'tax_query' => array(
            array(
                'taxonomy' => 'resources_types',
                'terms' => array($id),
                'field' => 'term_id',
            ),
        ),
        'post_type'         => 'resources',
        'posts_per_page'    => 3,
        'post_status'       => array( 'publish'),
        'paged'             => $paged        
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        
        while ($query->have_posts()) {
            $query->the_post(); 
            require 'partials/content-loop.php';  
        }
            
    }
    wp_reset_postdata();
    die(); 
}
add_action( 'wp_ajax_load_more_resource', 'load_more_resource' );
add_action( 'wp_ajax_nopriv_load_more_resource', 'load_more_resource' );

function load_more_video() {

    $paged = $_POST['paged'];
    $name = $_POST['name'];
    $id = $_POST['term_id'];
    $args = array(
        /*'tax_query' => array(
            array(
                'taxonomy' => 'resources_types',
                'terms' => array($id),
                'field' => 'term_id',
            ),
        ),*/
        'post_type'         => 'aiovg_videos',
        'posts_per_page'    => 3,
        'post_status'       => array( 'publish'),
        'paged'             => $paged        
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        
        while ($query->have_posts()) {
            $query->the_post(); 
            require 'partials/content-loop.php';  
        }
            
    }
    wp_reset_postdata();
    die(); 
}
add_action( 'wp_ajax_load_more_video', 'load_more_video' );
add_action( 'wp_ajax_nopriv_load_more_video', 'load_more_video' );

/* ---------------------------------------------------------------------------
 * [wv_filtersTop]
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'wv_filtersTop' ) ){
    function wv_filtersTop( $attr, $content = null )
    {
        ob_start();
        extract(shortcode_atts(array(
            'count'             => 12,
            'excerpt'           => '1',
            'cat_list'          => ''
        ), $attr));
    ?>
        <div id="search-container" class="resource-filter-wrp">  
            <div class="row">
                <div class="col-sm-10">
                    <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                        <input type="search" class="search-field"
                                placeholder="<?php echo esc_attr_x( 'Search for Content…', 'placeholder' ) ?>"
                                value="<?php echo get_search_query() ?>" name="s"
                                title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
                        <input type="image" alt="Search" class="search-submit" src="<?php bloginfo( 'template_url' ); ?>/images/search.png" />
                    </form>
                </div>
                <div class="col-sm-2">
                    <div id="orderby" class="dropdown">
                        <button class="btn dropdown-toggle btn-block text-left btn-sortby" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="filter-option-inner-inner" style="display: inline-block;width: 92%;">Sort By:</div>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item active" href="#" data-order="DESC">Default Order</a>
                            <!-- <a class="dropdown-item " href="#" data-order="ASC" data-orderby="title">Title - a-z</a>
                            <a class="dropdown-item " href="#" data-order="DESC" data-orderby="title">Title - z-a</a> -->
                            <a class="dropdown-item " href="#" data-order="ASC" data-orderby="date">Date - Old to New</a>
                            <a class="dropdown-item " href="#" data-order="DESC" data-orderby="date">Date - New to Old</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="filter-content">
                        <ul class="resource-filter">
                            <li class="resource-filtr-cat resource-active-filtr"><div class="tax-box"><a href="" id="all-items" class="active">All</a></div></li>
                            <?php 
                                $taxonomy = 'resources_types';
                                $terms = get_terms( array(
                                          'taxonomy' => $taxonomy,
                                          'include' => $cat_list,
                                          'hide_empty'  => false, 
                                          'orderby'  => 'include', 
                                        ) );
                                if ( $terms && !is_wp_error( $terms ) ) {
                                    foreach ( $terms as $term ) { ?>
                                    <li class="resource-filtr-cat">
                                        <div class="tax-box">
                                        <?php 
                                            $image_id = get_term_meta( $term->term_id, 'image', true );
                                            $image_data = wp_get_attachment_image_src( $image_id, 'full' );
                                            $image = $image_data[0];
                                            if ( ! empty( $image ) ) {
                                                echo '<img src="' . esc_url( $image ) . '" />';
                                            }
                                        ?>
                                        <a href="<?php echo get_term_link($term->slug, $taxonomy); ?>" id="<?php echo $term->term_id; ?>"><?php echo esc_html($term->name);?></a> <span class="number">(<?php echo $term->count; ?>)</span>
                                        </div>
                                    </li>
                                <?php }
                                }

                                $count_posts = wp_count_posts( 'aiovg_videos' )->publish;
                            ?>
                            <li class="resource-filtr-cat resource-active-filtr"><div class="tax-box"><img src="<?php echo home_url( '/' ); ?>wp-content/uploads/ico-videos.jpg"><a href="" id="video-items">Videos</a> <span class="number">(<?php echo $count_posts; ?>)</span></div></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div id="aditional-categories" class="dropdown">
                        <button class="btn dropdown-toggle btn-block text-left btn-sortby" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="filter-option-inner-inner" style="display: inline-block;width: 92%;">Additional <span class="hidden-sm d-xs-inline-block">Categories</span></div>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <?php 
                                $taxonomy = 'resources_types';
                                $terms = get_terms( array(
                                          'taxonomy' => $taxonomy,
                                          'exclude' => $cat_list,
                                          'hide_empty'  => false, 
                                        ) );
                                if ( $terms && !is_wp_error( $terms ) ) {
                                    $cant = 0;
                                    foreach ( $terms as $term ) { 
                                        /*if ($cant == 0) {
                                            $classactive = 'active';
                                        }*/
                                        ?>
                                        <a class="dropdown-item <?php echo $classactive; ?>" href="#" data-order="" id="<?php echo $term->term_id; ?>"><?php echo esc_html($term->name);?></a>
                                    <?php 
                                        $cant++;

                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    <?php 
        return ob_get_clean();
    }
}
add_shortcode( 'wv_filtersTop', 'wv_filtersTop' );


function load_search_results_depracated() {
    $query = $_POST['query'];
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    
    $args = array(
        'post_type'         => array('resources','aiovg_videos'),
        'posts_per_page'    => 12,
        'post_status'       => array( 'publish'),
        'paged'             => $paged,
        's'                 => $query
    );
    $search = new WP_Query( $args );
    
    ob_start();
    echo '<div class="box-type">';
        if ( $search->have_posts() ) : ?>
            <h2><?php printf( __( 'Search Results for: %s', 'twentyfourteen' ), $query ); ?></h2>

            <?php
                $count = 0;
                echo '<p class="mt-4 text-center"><a href="javascript: clear_search();" class="btn btn-dark">Clear Search</a><p>';          
                echo '<div id="box-resources" class="row">';
                while ( $search->have_posts() ) : $search->the_post();
                    if($search->post->post_type != 'aiovg_videos' ){
                      $terms = get_the_terms( $search->post->ID, 'resources_types' );
                      $term = $terms[0];
                    }else{
                      $name = 'Videos';
                    }
                    require 'partials/content-loop.php';
                    $count++; 
                endwhile;  
                echo '</div>';
                if ($count>=12): ?>
                    <div class="sectloadmore">
                        <div class="row">
                            <div class="col-sm-4 offset-sm-4">
                                <a href="#" class="btn btn-block" id="showmorepost-search">Load More</a>
                            </div>
                        </div> 
                    </div>
                <?php endif;

        else :
            echo "<p>Sorry resources not found</p>";
        endif;
        echo '<input type="hidden" id="paged-results" value="'.$paged.'">';
    echo '</div>';
    
    $content = ob_get_clean();
    
    echo $content;
    die();
            
}
function load_search_results() {
  global $wpdb, $post;

  $query = $_POST['query'];
  $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
  $start = $paged -1;

  $parts = explode(' ', esc_sql($query) );
  $slugs = '';
  foreach($parts as $p) $slugs .= "'" . strtolower($p) . "',";
  $slugs = trim($slugs,',');

  $qry = $wpdb->prepare("SELECT p.ID, p.post_type FROM $wpdb->posts p
  WHERE p.post_type IN ( 'resources', 'aiovg_videos')
  AND p.post_status = 'publish'
  AND p.post_title LIKE %s
  OR p.post_content LIKE %s
  OR p.ID IN (
    SELECT tr.object_id
    FROM $wpdb->terms t, $wpdb->term_relationships tr
    WHERE t.slug IN ($slugs) AND t.term_id = tr.term_taxonomy_id
  ) LIMIT $start,12", '%' . $wpdb->esc_like( $query ) . '%', '%' . $wpdb->esc_like( $query ) . '%');
  
  $rows = $wpdb->get_results( $qry );
  
  ob_start();
  echo '<div class="box-type">';
      if ( $rows ) : ?>
          <h2><?php printf( __( 'Search Results for: %s', 'twentyfourteen' ), $query ); ?></h2>

          <?php
              $count = 0;
              echo '<p class="mt-4 text-center"><a href="javascript: clear_search();" class="btn btn-dark">Clear Search</a><p>';          
              echo '<div id="box-resources" class="row">';
              foreach($rows as $row){
                  $post = get_post($row->ID);
                  if($row->post_type != 'aiovg_videos' ){
                    $terms = get_the_terms( $row->ID, 'resources_types' );
                    $term = $terms[0];
                  }else{
                    $name = 'Videos';
                  }
                  require 'partials/content-loop.php';
                  $count++; 
              }
              echo '</div>';
              if ($count>=12): ?>
                  <div class="sectloadmore">
                      <div class="row">
                          <div class="col-sm-4 offset-sm-4">
                              <a href="#" class="btn btn-block" id="showmorepost-search">Load More</a>
                          </div>
                      </div> 
                  </div>
              <?php endif;

      else :
          echo "<p>Sorry resources not found</p>";
      endif;
      echo '<input type="hidden" id="paged-results" value="'.$paged.'">';
  echo '</div>';
  
  $content = ob_get_clean();
  
  echo $content;
  die();
          
}
add_action( 'wp_ajax_load_search_results', 'load_search_results' );
add_action( 'wp_ajax_nopriv_load_search_results', 'load_search_results' );

function load_more_search_resource() {

    $paged = $_POST['paged'];
    $query = $_POST['query'];

    $args = array(
        'post_type'         => array('resources','aiovg_videos'),
        'posts_per_page'    => 12,
        'post_status'       => array( 'publish'),
        'paged'             => $paged,
        's'                 => $query
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        
        while ($query->have_posts()) {
            $query->the_post(); 
            require 'partials/content-loop.php';  
        }
            
    }
    wp_reset_postdata();
    die(); 
}
add_action( 'wp_ajax_load_more_search_resource', 'load_more_search_resource' );
add_action( 'wp_ajax_nopriv_load_more_search_resource', 'load_more_search_resource' );

// Block Access to /wp-admin for non admins.
function wv_blockusers_init() {
  register_taxonomy_for_object_type('post_tag', 'aiovg_videos');

  if ( is_admin() && !defined('DOING_AJAX') && ( 
  current_user_can('usercrp') || current_user_can('userpcp') ||  
  current_user_can('subscriber') || current_user_can('contributor') || 
  current_user_can('editor'))) {
    session_destroy();
    wp_logout();
    wp_redirect( home_url() );
   exit;
  }
 }
add_action( 'init', 'wv_blockusers_init' ); // Hook into 'init'

//Autocomplete
add_action( 'wp_ajax_auto_search', 'auto_search' );
add_action( 'wp_ajax_nopriv_auto_search', 'auto_search' );
function auto_search(){
  if(!empty($_GET['term'])){
    global $wpdb;

    $parts = explode(' ', esc_sql($_GET['term']) );
    $slugs = '';
    foreach($parts as $p) $slugs .= "'" . strtolower($p) . "',";
    $slugs = trim($slugs,',');

    $res = array();
    $qry = $wpdb->prepare("SELECT p.ID, p.post_title FROM $wpdb->posts p
    WHERE p.post_type IN ( 'resources', 'aiovg_videos')
    AND p.post_status = 'publish'
    AND p.post_title LIKE %s
    OR p.post_content LIKE %s
    OR p.ID IN (
      SELECT tr.object_id
      FROM $wpdb->terms t, $wpdb->term_relationships tr
      WHERE t.slug IN ($slugs) AND t.term_id = tr.term_taxonomy_id
    )", '%' . $wpdb->esc_like( $_GET['term'] ) . '%', '%' . $wpdb->esc_like( $_GET['term'] ) . '%');
    $rows = $wpdb->get_results( $qry );
    foreach($rows as $row) $res[] = array('id' => get_the_permalink($row->ID), 'label' => $row->post_title, 'value' => $row->post_title );
    header('Content-type: application/json');
    echo json_encode($res);
    exit();
  }
}