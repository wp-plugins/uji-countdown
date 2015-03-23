<?php

// loads the shortcodes class, wordpress is loaded with it
require_once( 'shortcodes.class.php' );

// get popup type
$popup = trim( $_GET['popup'] );
$shortcode = new Ujic_shortcodes( $popup );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>
<div id="ujic-popup">

	<div id="ujic-shortcode-wrap">
		
		<div id="ujic-sc-form-wrap">
		
			<div id="ujic-sc-form-head">
			
				<?php echo $shortcode->popup_title; ?>
			
			</div>
			<!-- /#ujic-sc-form-head -->
			
			<form method="post" id="ujic-sc-form">
            
            <p style="font-size:11px">
              Only one timer on page is allowed. <br>Check the <a href="http://wpmanage.com/uji-countdown" target="_blank">Premium version</a> for multiple countdown timers on the same page.
            </p>
			
				<table id="ujic-sc-form-table">
				
					<?php echo $shortcode->output; ?>
					
					<tbody>
						<tr class="form-row">
							<?php if( ! $shortcode->has_child ) : ?><td class="label">&nbsp;</td><?php endif; ?>
							<td class="field"><a href="#" class="button-primary ujic-insert">Insert Shortcode</a></td>							
						</tr>
					</tbody>
				
				</table>
				<!-- /#ujic-sc-form-table -->
				
			</form>
			<!-- /#ujic-sc-form -->
		
		</div>
		<!-- /#ujic-sc-form-wrap -->
		
		<div class="clear"></div>
		
	</div>
	<!-- /#ujic-shortcode-wrap -->

</div>
<!-- /#ujic-popup -->

</body>
</html>