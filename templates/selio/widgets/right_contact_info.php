<?php
/*
Widget-title: Contact info
Widget-preview-image: /assets/img/widgets_preview/right_contact_info.webp
 */
?>

<div class="contact_info widget_edit_enabled">
    <h3><?php echo lang_check('Contact Information');?></h3>
        <ul class="cont_info">
                <li><i class="la la-map-marker"></i> <a href="https://maps.google.com/maps?q=<?php echo urlencode($settings_address); ?>" target="_blank" title="<?php echo lang_check('View on Google Maps'); ?>"><?php echo $settings_address; ?></a></li>
                <?php if(!empty($settings_phone)):?>
                <?php 
                    $justNums = preg_replace("/[^0-9]/", '',  _ch($settings_phone,'#'));
                ?>
                <li><i class="la la-phone"></i><a href="tel://<?php echo $justNums;?>"><?php echo $settings_phone;?></a></li>
                <?php endif;?>
                <?php if(!empty($settings_email)):?>
                <li><i class="la la-envelope"></i><a href="mailto:<?php echo $settings_email;?>" title="<?php echo $settings_email;?>"><?php echo $settings_email;?></a></li>
                <?php endif;?>
        </ul>
        <ul class="social_links">
                <li><a href="#" title=""><i class="fa fa-facebook"></i></a></li>
                <li><a href="#" title=""><i class="fa fa-twitter"></i></a></li>
                <li><a href="https://www.instagram.com/immopark.germany?igsh=OGlycnR1dzk1bGV0" target="_blank" title=""><i class="fa fa-instagram"></i></a></li>
                <li><a href="#" title=""><i class="fa fa-linkedin"></i></a></li>
        </ul>
</div><!--contact_info end-->