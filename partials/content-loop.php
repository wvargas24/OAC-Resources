<div class="col-sm-4">
    <div id="<?php echo 'resource-'.get_the_ID(); ?>" class="columns filtr-item">
        <div class="resource-grid">
            <div class="resource-grid-content">
                <div class="resource-image-bg">
                    <div class="term-img tool-tip">
                    <?php 
                        $access_level = get_post_meta(get_the_ID(),'access_level',true);
                        /*
                        if ($term->name!='') {
                            $image_id = get_term_meta( $term->term_id, 'image', true );
                        }elseif($name!=''){
                            $image_id = get_term_meta( $id, 'image', true );
                        }else{
                            $image_id = '';
                        }
                        if ($name=='videos' || $name=='Videos' || $term->name == 'videos' || $term->name == 'Videos') {
                            $image_id = '33556';
                        }
                        
                        $image_data = wp_get_attachment_image_src( $image_id, 'full' );
                        $image = $image_data[0];
                        if ( ! empty( $image ) ) {
                            echo '<img src="' . esc_url( $image ) . '" />';
                        }
                        */
                        $n = $term->name ? $term->name : $name;
                        switch($n){
                          case 'Articles':
                            $img = '<img src="' . home_url( '/wp-content/uploads/ico-articles.gif' ) . '" />';
                            break;
                          case 'Brochures':
                            $img = '<img src="' . home_url( '/wp-content/uploads/ico-brochures.gif' ) . '" />';
                            break;
                          case 'Guides':
                            $img = '<img src="' . home_url( '/wp-content/uploads/ico-guides.gif' ) . '" />';
                            break;
                          case 'Videos':
                            $img = '<img src="' . home_url( '/wp-content/uploads/ico-videos.gif' ) . '" />';
                            break;
                          default:
                            $img = '';
                            break;
                        }
                        echo $img;
                    ?>
                      <span class="tooltiptext"><?php echo $n;?></span>
                    </div>
                    <?php //if ($access_level=='tier_3') :?>
                        <?php //the_post_thumbnail( 'resource-tab' ); ?>  
                    <?php //else: ?>  
                        <a href="<?php the_permalink();?>" title="<?php the_title(); ?>">
                            <?php the_post_thumbnail( 'resource-tab' ); ?>                    
                        </a>  
                    <?php //endif; ?>
                    <?php 
                        if ($access_level=='tier_3') {
                            echo '<div class="ico-private tool-tip"><img src="'.home_url( '/' ).'/wp-content/uploads/ico-private.gif" alt="" /><span class="tooltiptext">Premium Access</span></div>';
                        }

                     ?>
                </div>
                <h3 class="resource-title">
                    <?php //if (get_post_status()=='private' && false) :?>
                        <?php //wv_trim_text(the_title(),50); ?>
                    <?php //else: ?>  
                        <a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php wv_trim_text(the_title(),50); ?></a>
                    <?php //endif; ?>
                    
                </h3>                
            </div>
            <div class="resource-content">
                <?php 
                    $excerpt = get_the_excerpt();
                    //$excerpt = substr( $excerpt , 0, 100); 
                    $excerpt = wp_trim_words( $excerpt, 17 );
                    echo '<p>'.$excerpt.'</p>';
                    if ($term->name!='') {
                        if ($term->name=='articles' || $term->name=='Articles') {
                            $button='Read Article';
                        }elseif ($term->name=='brochures' || $term->name=='Brochures') {
                            $button='Download Brochure';
                        }elseif ($term->name=='guides' || $term->name=='Guides') {
                            $button='View Guide';
                        }elseif ($term->name=='videos' || $term->name=='Videos') {
                            $button='View Video';
                        }else{
                            $button='Read More';
                        }
                    }elseif($name!=''){                        
                        if ($name=='articles' || $name=='Articles') {
                            $button='Read Article';
                        }elseif ($name=='brochures' || $name=='Brochures') {
                            $button='Download Brochure';
                        }elseif ($name=='guides' || $name=='Guides') {
                            $button='View Guide';
                        }elseif ($name=='videos' || $name=='Videos') {
                            $button='View Video';
                        }else{
                            $button='Read More';
                        }                        
                    }else{
                        $button='Read More';
                    }
                    
                ?>
                <?php //if (get_post_status()!='private' || true) :?>
                    <a href="<?php the_permalink();?>" title="<?php the_title(); ?>" class="readmorebtn"><?php echo $button; ?></a>
                <?php //endif; ?>
            </div>
        </div>
    </div>
</div>