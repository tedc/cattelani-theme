<?php 
$current_url = home_url(add_query_arg(array(),$wp->request)); ?>
<form id="contactForm" class="form" name="contactForm" novalidate action="https://catellanismith.agilecrm.com/formsubmit" method="GET">
	<div style="display:none;visibility:hidden;height:0;width:0;">
	<label for="email_form"><?php _e('Email form', 'catellani'); ?></label>
	<input class="form__input" type="email" id="email_form" ng-model="formData.email_form" placeholder="<?php _e('Email form', 'catellani'); ?>">
	<label for="website"><?php _e('Website', 'catellani'); ?></label>
	<input class="form__input" type="text" id="website" ng-model="formData.website" placeholder="<?php _e('Website', 'catellani'); ?>">
	<input type="hidden" id="_agile_form_name" name="_agile_form_name" value="Contacts">
	<input type="hidden" id="_agile_domain" name="_agile_domain" value="catellanismith">
	<input type="hidden" id="_agile_api" name="_agile_api" value="657thtrj1go8jk32u0c8rkbnc9">
	<input type="hidden" id="_agile_redirect_url" name="_agile_redirect_url" value="https://www.catellanismith.com/en/#contact">
	<input type="hidden" id="_agile_document_url" name="_agile_document_url" value="">
	<input type="hidden" id="_agile_confirmation_msg" name="_agile_confirmation_msg" value="">
	<input type="hidden" id="_agile_form_id_tags" name="tags" value="EN">
	<input type="hidden" id="_agile_form_id" name="_agile_form_id" value="5638830484881408">
	</div>
	<p><input class="form__input" type="text" name="first_name" required ng-model="formData.first_name" placeholder="<?php _e('Name (required)', 'catellani'); ?>"></p>
	<p><input class="form__input" type="text" name="last_name" required ng-model="formData.last_name" placeholder="<?php _e('Last Name (required)', 'catellani'); ?>"></p>
	<p><input class="form__input" type="email" name="email" required ng-model="formData.email" placeholder="<?php _e('Email (required)', 'catellani'); ?>"></p>
	<p><input class="form__input" type="tel" name="phone" required ng-model="formData.tel" placeholder="<?php _e('Phone Number (required)', 'catellani'); ?>"></p>
	<div class="form__select" click-outside="isContactSelected=false">
		<span class="form__span form__span--grid-nowrap" ng-click="$event.stopPropagation();isContactSelected=true;">
			<span class="form__value" ng-bind-html="(formData.select) ? formData.select : '<?php _e('Select', 'catellani'); ?>'"></span>
			<span class="form__icons">
				<i class="icon-select"></i>
			</span>
		</span>
		<?php $data = array('Select', 'Commercial', 'Custom Projects', 'Customer Care', 'Other'); ?>
		<select ng-model="formData.select" required name="tags">			
			<?php for($i = 0; $i < count($data); $i++) : ?>
			<option value="<?php echo ($i==0) ? '' : $data[$i]; ?>"><?php echo $data[$i]; ?></option>
			<?php endfor; ?>
		</select>
		<ul class="form__options" ng-class="{'form__options--visible':isContactSelected}">
			<?php 
				for($j = 0; $j < count($data); $j++) : 
				if($j == 0) :
			?>
			<li class="form__option" ng-click="$event.stopPropagation();isContactSelected=false;formData.select=false" ng-class="{'form__option--selected':!formData.select}"><?php echo $data[$j]; ?></li>
			<?php else: ?>
			<li class="form__option" ng-click="$event.stopPropagation();isContactSelected=false;formData.select='<?php echo $data[$j]; ?>';" ng-class="{'form__option--selected':formData.select=='<?php echo $data[$j]; ?>'}"><?php echo $data[$j]; ?></li>
			<?php endif; endfor; ?>
		</ul>
	</div>
	<p><textarea class="form__textarea" name="note" ng-model="formData.message" placeholder="<?php _e('Message', 'catellani'); ?>"></textarea></p>
	<em class="form__privacy"><?php _e('By sending this form I authorize the treatment of my personal Data according to the Italian D. Lgs nr. 196/03.', 'catellani'); ?></em>
	<p class="form__footer">
		<button type="submit" class="form__send" ng-disabled="contactForm.$invalid" onclick="dataLayer.push({'event':'submit'})">
			<span><?php _e('Send', 'catellani'); ?></span>
		</button>
	</p>
</form>
<script type="text/javascript">
(function(a){var b=a.onload,p=true;isCaptcha=false;if(p){a.onload="function"!=typeof b?function(){try{_agile_load_form_fields()}catch(a){}}:function(){b();try{_agile_load_form_fields()}catch(a){}}};var formLen=document.forms.length;for(i=0;i<formLen;i++){if(document.forms.item(i).getAttribute("id")== "contactForm"){a.document.forms.item(i).onsubmit=function(a){a.preventDefault();try{_agile_synch_form_v5(this)}catch(b){this.submit()}}}}})(window);
</script>
