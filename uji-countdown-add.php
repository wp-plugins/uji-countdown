<?php

////////////////////////////////////ADD NEW STYLE////////////////////////////////////

function sel_size($sz){
	$ujic_txt_sz = array("Very Small"=>"15", "Small"=>"25", "Medium"=>"35", "Large"=>"45", "Very Large"=>"55");
	$select = '<option value="" class="seltit"> - Select Countdown Size - </option>';
	foreach($ujic_txt_sz as $size=>$key){
		$sel = ((int)$key == $sz) ? ' selected="selected"' : '';
		$select .= '<option value="'.$key.'" class="seltit" '.$sel.'>'.$size.'</option>';
	}
	return $select;
}
function rad_pos($ipos){
	$ujic_pos = array("none", "left", "center", "right");
	$pos = '';
	$i=0;
	foreach($ujic_pos as $p){
		$sel = ($p == $ipos) ? ' checked="checked"' : '';
		$pos .= '<input name="ujic_pos" type="radio" value="'.$p.'" '.$sel.' />
	            <span id="uji_pos_'.$i.'">'.ucfirst($p).' </span>';
				$i++;
	}
	return $pos;
}
function chk_pos($id, $ichk){
		$sel = ($ichk == 1) ? ' checked="checked"' : '';
		$chk = '<input name="'.$id.'" id="'.$id.'" type="checkbox" value="1" '.$sel.' />';
	return $chk;
}
function ujic_add_new(){
	ujic_save_newstyle();
	$ujic_name = !empty($_POST['ujic_name']) ? $_POST['ujic_name'] : '';
	$ujic_txt_size = !empty($_POST['ujic_txt_size']) ? $_POST['ujic_txt_size'] : 35;
	$ujic_col_dw = !empty($_POST['ujic_col_dw']) ? $_POST['ujic_col_dw'] : '#3A3A3A';
	$ujic_col_up = !empty($_POST['ujic_col_up']) ? $_POST['ujic_col_up'] : '#635b63';
	$ujic_pos = !empty($_POST['ujic_pos']) ? $_POST['ujic_pos'] : 'none';
	$ujic_col_txt = !empty($_POST['ujic_col_txt']) ? $_POST['ujic_col_txt'] : '#FFFFFF';
	$ujic_col_sw = !empty($_POST['ujic_col_sw']) ? $_POST['ujic_col_sw'] : '#000000';
	$ujic_ani = !empty($_POST['ujic_ani']) ? $_POST['ujic_ani'] : '0';
	$ujic_txt = !empty($_POST['ujic_txt']) ? $_POST['ujic_txt'] : '0';
	$ujic_maint = "Add New Countdown Style";
	$ujic_title = "Create New Timer Style";
	$ujic_save = "Save this style";
	$ujic_update = '';
	$ujic_form = 'options-general.php?page=ujic-count&save=ok';
	$ujic_cancel = '';
	
	if(isset($_GET['edit']) && !empty($_GET['edit'])){
		$id = $_GET['edit'];
		global $wpdb;
		$table_name = $wpdb->prefix ."uji_counter";
		$ujic_data = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $id");
		$ujic_name = $ujic_data->title;
		$ujic_txt_size = $ujic_data->size;
		$ujic_col_dw = $ujic_data->col_dw;
		$ujic_col_up = $ujic_data->col_up;
		$ujic_pos = $ujic_data->ujic_pos;
		$ujic_col_txt = $ujic_data->col_txt;
		$ujic_col_sw = $ujic_data->col_sw;
		$ujic_ani =  $ujic_data->ujic_ani;
		$ujic_txt =  $ujic_data->ujic_txt;
		$ujic_maint = "Edit Countdown Style";
		$ujic_title = "Edit style: <strong>".$ujic_data->title."</strong>";
		$ujic_save = "Update this style";
		$ujic_update = ' ujic-update"';
		$ujic_form = 'options-general.php?page=ujic-count&edit='.$id;
		$ujic_cancel = '<a href="options-general.php?page=ujic-count" class="button-primary cancel"> Cancel Add New </a>';
	}
?>
<div class="wrap" id="ujic_box">
	<h2> <?php echo $ujic_maint ?></h2>
    
<div class="metabox-holder">
<div class="postbox">
	<div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class="hndle"><span><?php echo $ujic_title ?></span></h3>
        <div class="inside">    
	<div id="ujic-add" class="form-table <?php echo $ujic_update ?>">
    	<form action="<?php echo $ujic_form ?>" method="post">
        <div>
            <label>Timer Title:</label>
            <input name="ujic_name" id="ujic_name" type="text" class="normal-text" value="<?php echo $ujic_name ?>"/><br/>
            </div>
            <div>
            <label>Countdown Size:</label>
            <select id="ujic_txt_sz" name="ujic_txt_sz">
                <?php echo sel_size($ujic_txt_size); ?>
              
            </select>
            </div>     
            <div>
            <label>Select Box Color:</label>
            <div class="fleft">
            <span> Bottom: </span>
            <div id="colorSelector"><div style="background-color: <?php echo $ujic_col_dw ?>"></div></div>
            <input name="ujic_col_dw" id="ujic_col_dw" type="text" value="<?php echo $ujic_col_dw ?>" class="small-text"/>
            </div>
            <div class="fleft">
            <span> Up: </span>
            <div id="colorSelector2"><div style="background-color: <?php echo $ujic_col_up ?>"></div></div>
            <input name="ujic_col_up" id="ujic_col_up" type="text" value="<?php echo $ujic_col_up ?>" class="small-text"/>
            </div>
            </div>
            <div>
            <label>Alignment:</label>
                <?php echo rad_pos($ujic_pos) ?>
            </div>
            <div>
            <label>Text Color:</label>
         	 <div class="fleft">
            <span> Number Color: </span>
            <div id="colorSelector3"><div style="background-color: <?php echo $ujic_col_txt ?>"></div></div>
            <input name="ujic_col_txt" id="ujic_col_txt" type="text" value="<?php echo $ujic_col_txt ?>" class="small-text"/>
            </div>
            <div class="fleft">
            <span> Shadow Color: </span>
            <div id="colorSelector4"><div style="background-color: <?php echo $ujic_col_sw ?>"></div></div>
            <input name="ujic_col_sw" id="ujic_col_sw" type="text" value="<?php echo $ujic_col_sw ?>" class="small-text"/>
            </div>
            </div>    
            <div>
            <label>Animation for seconds:</label>
            <div class="fleft">
            <?php echo chk_pos('ujic_ani', $ujic_ani) ?>
            </div>
            </div>
            <div>
            <label>Display time label text:</label>
            <div class="fleft">
            <?php echo chk_pos('ujic_txt', $ujic_txt) ?>
            </div>
            <input type="hidden" name="ujic_save" value="save" />
            </div>
            <p class="submit">
            <input class="button-primary" type="submit" name="submit" value="<?php echo $ujic_save ?>" />
            <?php echo $ujic_cancel; ?>
            </p>
        </form>
    </div>
            </div>
</div>
<h3>Preview Your Style:</h3>
 <div id="ujic-pre">
    <div id="ujiCountdown" class="hasCountdown">
        <span class="countdown_row countdown_show4">
            <span class="countdown_section">
                <span class="countdown_amount">3</span>
                <span class="countdown_amount">4</span>
                <span class="countdown_txt">Days</span>
            </span>
            <span class="countdown_section">
                <span class="countdown_amount">1</span>
                <span class="countdown_amount">0</span>
                <span class="countdown_txt">Hours</span>
            </span>
            <span class="countdown_section">
                <span class="countdown_amount">2</span>
                <span class="countdown_amount">3</span>
                <span class="countdown_txt">Minutes</span>
            </span>
            <span class="countdown_section">
                <span class="countdown_amount">5</span>
                <span class="countdown_amount">9</span>
                <span class="countdown_txt">Seconds</span>
            </span>
        </span>
    </div>
 </div>   
<?php ujic_saved(); ?>
</div>
<div class="metabox-holder">
<div class="postbox">
	<div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class="hndle"><span>Add Countdown in Post or Page</span></h3>
        <div class="inside">
           <img src="<?php echo UJI_PLUGIN_URL. '/images/ujic-ps.jpg'; ?>" />
        </div>
</div>
<div class="postbox">
	<div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class="hndle"><span>Support this plugin</span></h3>
        <div class="inside">
        	<div id="donate">
           <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="BHFJEZ6CFKSAJ">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
			</div>
            <a href="http://wpmanage.com/Contact-Us" target="_blank" id="hire">HIRE ME &#8250;</a>
        </div>
</div>
<div class="postbox">
	<div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class="hndle"><span>Wordpress Theme Gallery</span></h3>
        <div class="inside">
           <a href="http://www.wprelease.com/" target="_blank"><img src="<?php echo UJI_PLUGIN_URL. '/images/wprelease.jpg'; ?>" style="padding-left:28px" /></a>
        </div>
</div>
</div>
<?php }
function ujic_save_newstyle(){
	global $wpdb;
	$table_name = $wpdb->prefix ."uji_counter";	
	
	if(isset($_GET['del']) && !empty($_GET['del'])){
		$cname = $wpdb->get_var("SELECT title FROM $table_name WHERE id = '".$_GET['del']."'");
		$wpdb->query("DELETE FROM $table_name WHERE id = '".$_GET['del']."'");
		echo '<div class="updated">Your countdown style named: '.$cname .' was deleted </div>';
	}
	if(isset($_POST['ujic_save'])){
	
	$ujic_form_err = '';
	
	if(!empty($_POST['ujic_name']) && (!isset($_GET['edit']) || empty($_GET['edit']))){
	$cname = $wpdb->get_var("SELECT title FROM $table_name WHERE title = '".$_POST['ujic_name']."'");
		if(!empty($cname)){
			$ujic_form_err .=  'This name already exist. Please change name  <br/>';
		}
	}
	
	if(empty($_POST['ujic_name'])){
		$ujic_form_err .=  'Please complete Timer Title <br/>';
	}
	if(empty($_POST['ujic_txt_sz'])){
		$ujic_form_err .=  'Please complete Countdown Size:  <br/>';
	}
	if(empty($_POST['ujic_col_dw']) || empty($_POST['ujic_col_up'])){
		$ujic_form_err .=  'Please select Box Color  <br/>';
	}
	if(empty($_POST['ujic_col_txt']) || empty($_POST['ujic_col_sw'])){
		$ujic_form_err .=  'Please select Text Color  <br/>';
	}
	if(!empty($ujic_form_err)) echo '<div class="error">'.$ujic_form_err.' </div>';
	else if (empty($ujic_form_err)){
		if(!isset($_GET['edit']) || empty($_GET['edit'])){
		$ujic_ani = isset($_POST['ujic_ani']) ? $_POST['ujic_ani'] : 0;
		$ujic_txt = isset($_POST['ujic_txt']) ? $_POST['ujic_txt'] : 0;

		$wpdb->query($wpdb->prepare("INSERT INTO $table_name(time, title, size, col_dw, col_up, ujic_pos, col_txt, col_sw, ujic_ani, ujic_txt)
		    			VALUES (utc_timestamp(), %s, %d, %s, %s, %s, %s, %s, %d, %d)",
						trim($_POST['ujic_name']), $_POST['ujic_txt_sz'], $_POST['ujic_col_dw'], $_POST['ujic_col_up'], $_POST['ujic_pos'], $_POST['ujic_col_txt'], $_POST['ujic_col_sw'], $ujic_ani, $ujic_txt ));

		echo '<div class="updated">Your countdown style was added </div>';
		
		}else{
		$ujic_ani = isset($_POST['ujic_ani']) ? $_POST['ujic_ani'] : 0;
		$ujic_txt = isset($_POST['ujic_txt']) ? $_POST['ujic_txt'] : 0;	
		$wpdb->query( $wpdb->prepare("UPDATE $table_name SET title=%s, size=%d, col_dw=%s, col_up=%s, ujic_pos=%s, col_txt=%s, col_sw=%s, ujic_ani=%d, ujic_txt=%d WHERE id=%d", trim($_POST['ujic_name']), $_POST['ujic_txt_sz'], $_POST['ujic_col_dw'], $_POST['ujic_col_up'], $_POST['ujic_pos'], $_POST['ujic_col_txt'], $_POST['ujic_col_sw'], $ujic_ani, $ujic_txt, $_GET['edit']) );
		echo '<div class="updated">Your countdown style was updated </div>';
		}
	}
	}
}
function ujic_saved(){
	global $wpdb;
	$table_name = $wpdb->prefix ."uji_counter";
	$ujic_datas = $wpdb->get_results("SELECT * FROM $table_name ORDER BY `time` DESC");
	if(!empty($ujic_datas)){
?>
	<h3>Countdown Styles:</h3>
	<table class="widefat">   
    <thead>
        <tr>
            <th>Added in</th>
            <th>Title</th>
            <th>Change</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
          <th>Added in</th>
           <th>Title</th>
           <th>Change</th>
       </tr>
    </tfoot>
    <tbody>
<?php
	$ujictab='';
	foreach($ujic_datas as $ujic)
		{
		$ujictab .='<tr><td>'.$ujic->time.'</td><td>'.$ujic->title.'</td><td><a href="options-general.php?page=ujic-count&edit='.$ujic->id.'">Edit</a> | <a href="options-general.php?page=ujic-count&del='.$ujic->id.'">Delete</a></td></tr>';
		}
	echo $ujictab;
?>
    </tbody>
    </table>
<?php

	}
}

?>