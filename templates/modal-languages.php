<div class="modal__container" id="modal-languages"  scrollbar>
	<?php
		$title = __('Seleziona la lingua', 'catellani');
		$languages = apply_filters('wpml_active_languages', null);
		if($languages):?>
	<nav class="modal__nav modal__nav--languages modal__nav--languages-shrink">
		<header class="modal__header modal__header--grow-md-bottom">
			<h4 class="modal__title modal__title--small modal__title--small-light"><?php echo $title; ?></h4>	
		</header>
		<?php foreach($languages as $l) :
			$language_link = $l['url'];
            $language_code = $l['language_code'];
                
		?>
		<a href="<?php echo $language_link;  ?>" class="modal__button modal__button--lang modal__button--lang-upper<?php  echo ($language_code == ICL_LANGUAGE_CODE) ? ' modal__button--lang-active' : ''; ?>" target="_blank" ng-if="!lang_menu"><span class="lang"><?php echo $language_code; ?></span></a>
		<?php endforeach; ?>	
		<a ng-href="{{l.href}}" class="modal__button modal__button--lang modal__button--lang-upper" ng-class="{'modal__button--lang-active':l.lang == '<?php echo ICL_LANGUAGE_CODE; ?>'}" target="_blank" ng-if="lang_menu" ng-repeat="l in lang_menu"><span class="lang" ng-bind-html="l.lang"></span></a>
	</nav>
	<?php endif;?>
</div>