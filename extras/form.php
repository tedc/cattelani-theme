<?php
	include( locate_template( 'extras/vendor/autoload.php', false, false) );
	use \DrewM\MailChimp\MailChimp;
	add_action( 'wp_ajax_catellaniform', 'catellani_ajax_form' );
	add_action( 'wp_ajax_nopriv_catellaniform', 'catellani_ajax_form' );
	function is_user_subscribed($email) {
		acf_set_language_to_default();
		$mc = get_field('mailchimp', 'options');
		$list_id = $mc['list_id'];
		$api_key = $mc['api_key'];
		$user_url = $mc['user_url'];
		acf_unset_language_to_default();
		$MailChimp = new MailChimp($api_key);
		$subscriberHash = $MailChimp->subscriberHash($email);
	    $result = $MailChimp->get('lists/' . $list_id . '/members/' . $subscriberHash);
	    return ($MailChimp->success() && isset($result['id']));
	}
	function catellani_ajax_form(){
		global $post;
		check_ajax_referer( 'catellani-contact-form', '_catellani_form_nonce' ); 
        if(isset($_POST['email']) && isset($_POST['sender']) && isset($_POST['tel']) && isset($_POST['message']) ) :	 	
			acf_set_language_to_default();
			$mc = get_field('mailchimp', 'options');
			$list_id = $mc['list_id'];
			$api_key = $mc['api_key'];
			$user_url = $mc['user_url'];
			acf_unset_language_to_default();
			$MailChimp = new MailChimp($api_key);
	        $email = $_POST['email'];
	    	$name = $_POST['sender'];
	        $tel = $_POST['tel'];
	    	$sender = $name;
	        $message = $_POST['message'];
	        acf_set_language_to_default(); 
	        $pTo = get_field('email_address', 'options');
	        acf_unset_language_to_default(); 
	        //$pTo = array('e.grandinetti@bspkn.it');
	        $pSubject = __('Richiesta di contatto da') . ' ' . $sender;
	        $rSubject = __('Risposta automatica da') . ' '. get_bloginfo('name');
	        $tnx = __('Grazie per averci contattato.<br/>Ti risponderemo prima possibile','catellani');
	        $errorMessage = __('Verifica di aver compilato bene i campi o scrivi a','catellani');
	        $sent = __('Messaggio inviato correttamente','catellani');
	        $error = __('Messaggio non inviato','catellani');
	        if(empty($_POST['email'])) $fields_not_set[] = "email";
	        if(empty($_POST['tel'])) $fields_not_set[] = "tel";
	        //if(empty($_POST['security_check'])) $fields_not_set[] = "nonce";
	        $name_row = (!empty($_POST['sender'])) ? '<tr style="border-bottom: 1px solid #f6f6f6;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#7d7d7d;font-style:italic">'.__('Da','catellani').'</em><br />'.$sender.'</td></tr>' : "";
	        $email_row = (!empty($_POST['email'])) ? '<tr style="border-bottom: 1px solid #f6f6f6;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#a7a9ac;font-style:italic">Email</em><br /><a href="mailto:'.$email.'" style="text-decoration:none;font-weight:bold;color:#0b1e2d">'.$email.'</a></td></tr>' : "";
	        $tel_row = (!empty($_POST['tel'])) ? '<tr style="border-bottom: 1px solid #f6f6f6;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#7d7d7d;font-style:italic">'.__('Telefono','catellani').'</em><br />'.$tel.'</td></tr>' : "";
	        $message_row = (!empty($_POST['message'])) ? '<tr style="border-bottom: 1px solid #f6f6f6;"><td style="text-align:center;padding:20px;font-size:18px;"><em style="color:#7d7d7d;font-style:italic">'.__('Messaggio','catellani').'</em><br />'.stripslashes($message).'</td></tr>' : "";
	        $last_row = '<tr style="border-bottom: 1px solid #f6f6f6;"><td style="text-align:center;padding:20px;font-size:14px;"><em style="color:#7d7d7d;font-style:italic">'.$sender.__(' stava visitando ', 'catellani').'<a href="'.$_POST['location'].'" style="text-decoration:none;font-weight:bold;color:#0b1e2d">'.$_POST['location'].'</a></td></tr>';
	        $body = $name_row.$email_row.$tel_row.$message_row.$last_row;
	        $resp = '<tr style="border-bottom: 1px solid #f6f6f6;"><td style="text-align:center;padding:20px;"><p style="line-height:1.35">'.$tnx.'</p></td></tr>';
	        function template($body) {
	            $html = '<html><head><meta charset="utf-8" /></head><body style="background-color:#f6f6f6"><div style="background-color:#fff;font-family:\'Helvetica Neue\', Helvetica, Arial, san-serif;font-size:18px;color:#808285;max-width:500px;margin:0 auto;"><table style="width:100%;border-collapse:collapse;"><thead><tr><td style="padding: 20px;text-align:center; background-color:#fff"><a href="'.get_bloginfo('url').'" style="text-decoration:none"><img src="'.get_stylesheet_directory_uri().'/assets/images/logo.gif" style="border:0;width:100%;max-width:200px;height:auto"/></a></td></tr></thead><tfoot><tr><td style="padding:20px; text-align:center;color:#7d7d7d;font-size:11px">&copy;'.get_bloginfo('name').', '.get_field('address', 'options').'<br /><a href="'.get_bloginfo('url').'" style="text-decoration:none;font-weight:bold;color:#0b1e2d">'.str_replace(array('http://', 'https://'), '', get_bloginfo('url')).'</a></td></tr></tfoot><tbody>'.$body.'</tbody></table></div></body></html>';
	            return $html;
	        }
	        if(is_user_subscribed($email)) {
	        	$subscriber_hash = $MailChimp->subscriberHash($email);
	        	$result = $MailChimp->patch('lists/'.$list_id.'/members/'.$subscriber_hash, array(
					'merge_fields' => array('FNAME'=>$sender, 'TEL'=>$tel)
				));
	        } else {
	        	$result = $MailChimp->post('lists/'.$list_id.'/members', array(
	        		'email_address' => $email,
	        		'status' => 'subscribed',
	        		'merge_fields' => array('FNAME'=>$sender, 'TEL'=>$tel)
	        	));
	        }
	        // if(!$MailChimp->success()) {
	        // 	wp_send_json( array('mailchimpError' => $MailChimp->getLastError()) );
	        // }
	        if(empty($fields_not_set)) {
	            //$transport = Swift_MailTransport::newInstance();
	            $transport = Swift_SmtpTransport::newInstance('asms3.assolo.net', 465, 'ssl')->setUsername('contact@catellanismith.com')->setPassword('gyg725bdl');
	            $mMailer = Swift_Mailer::newInstance($transport);
	            $rEmail = Swift_Message::newInstance();
	            $mEmail = Swift_Message::newInstance();
	            $mEmail->setSubject($pSubject);
	            $mEmail->setTo($pTo);
	            $mEmail->setBcc(array('e.grandinetti@bspkn.it','hello@bspkn.it', 'direzione.commerciale@catellanismith.com', 'grafica@catellanismith.com'));
	            $mEmail->setFrom(array($email => $name));
	            $mEmail->setReplyTo(array($email));
	            $mEmail->setBody(template($body), 'text/html');
	            $rEmail->setSubject(str_replace('&amp;', '&', $rSubject));
	            $rEmail->setFrom(array($pTo => str_replace('&amp;', '&', get_bloginfo('name'))));
	            $rEmail->setTo(array($email));
	            $rEmail->setBody(template($resp), 'text/html');
	            if( $mMailer->send($mEmail) && $mMailer->send($rEmail)){
	            	$data = array('formMsg' => "<h3 class='form__title'>".$sent."</h3><p>".$tnx."</p><a ui-sref='app.root({lang : \"".ICL_LANGUAGE_CODE."\"})' class='form__send' href='".get_home_url()."'>".__('Torna alla home', 'catellani')."</a>");
	                $data = array('formMsg' => "<h3 class='form__subtitle form__subtitle'>".__('Grazie per averci contattato', 'iro')."</h3><p>".__('Ti risponderemo nel più breve tempo possibile', 'iro')."</p><a ui-sref='app.root({lang : \"".ICL_LANGUAGE_CODE."\"})' class='form__button' href='".get_home_url()."'>".__('Torna allo shop', 'iro')."</a>", 'mc' => $MailChimp->get("lists/$list_id/members/$subscriber_hash"));
	            } else {
	                $data = array('formMsg' => "<h3 class='form__subtitle--error'>".$error."</h3><p>".$errorMessage."</p><a ui-sref='app.root({lang : \"".ICL_LANGUAGE_CODE."\"})' class='form__send' href='".get_home_url()."'>".__('Torna alla home', 'catellani')."</a>");
	            }
	        }
	        wp_send_json( $data );
	        wp_die();
    	else :
    		wp_send_json( array('msg'=>'error') );
    		wp_die();
    	endif;
    }