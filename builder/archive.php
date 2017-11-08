<?php
	$collezioni = get_terms(array('post_types' => get_sub_field('post_type'), 'taxonomy' => 'collezioni')); 
	$tipologie = get_terms(array('post_types' => get_sub_field('post_type'), 'taxonomy' => 'tipologie'));
	$first = get_posts(
		array(
			'numberposts' => -1,
			'order' => 'ASC',
			'post_status' => 'publish',
			'post_type' => get_sub_field('post_type')
		)
	)[0];
	$year = intval(ucfirst(get_the_time('Y', $first->ID))) + 1;
?>
<div class="projects projects--grow-lg" post-type-archive post-type="<?php the_sub_field('post_type'); ?>" lang="<?php echo ICL_LANGUAGE_CODE; ?>">
	<div class="projects__filters projects__filters--gray projects__filters--grow-md"> 
		<div class="projects__select" click-outside="isSelect['collezioni']=false" ng-class="{'projects__select--filled' : projects.collezioni}" ng-click="$event.stopPropagation();isSelect['collezioni']=!isSelect['collezioni']">
			<span class="projects__value" ng-bind-html="(projects.collezioni ? select['collezioni'] : '<?php _e('Scegli una collezione', 'catellani'); ?>')"></span>
			<span class="projects__icons">
				<i class="icon-select"></i>
				<span class="close" ng-click="$event.stopPropagation();change('collezioni', false)">
					<i class="icon-close"></i>
				</span>
			</span>
			<ul class="projects__options" ng-class="{'projects__options--visible':isSelect['collezioni']}">
				<li class="projects__option"></li>
				<?php foreach($collezioni as $collezione) : ?>
				<li class="projects__option" ng-click="$event.stopPropagation();change('collezioni', {id : <?php echo $collezione->term_id; ?>, name : '<?php echo addslashes($collezione->name); ?>'})" ng-class="{'projects__option--selected':projects.collezioni==<?php echo $collezione->term_id; ?>}"><?php echo $collezione->name; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="projects__select" click-outside="isSelect['tipologie']=false" ng-class="{'projects__select--filled' : projects.tipologie}" ng-click="isSelect['tipologie']=!isSelect['tipologie']">
			<span class="projects__value" ng-bind-html="(projects.tipologie ? select['tipologie'] : '<?php _e('Scegli una tipologia', 'catellani'); ?>')"></span>
			<span class="projects__icons">
				<i class="icon-select"></i>
				<span class="close" ng-click="$event.stopPropagation();change('tipologie', false)">
					<i class="icon-close"></i>
				</span>
			</span>
			<ul class="projects__options" ng-class="{'projects__options--visible':isSelect['tipologie']}">
				<li class="projects__option"></li>
				<?php foreach($tipologie as $tipologia) : ?>
				<li class="projects__option" ng-click="$event.stopPropagation();change('tipologie', {id : <?php echo $tipologia->term_id; ?>, name : '<?php echo addslashes($tipologia->name); ?>'})" ng-class="{'projects__option--selected':projects.tipologie==<?php echo $tipologia->term_id; ?>}"><?php echo $tipologia->name; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="projects__select" click-outside="isSelect['periodi']=false" ng-class="{'projects__select--filled' : select['periodi']!=false}" ng-click="isSelect['periodi']=!isSelect['periodi']">
			<span class="projects__value" ng-bind-html="(select['periodi'] ? select['periodi'] : '<?php _e('Scegli un periodo', 'catellani'); ?>')"></span>
			<span class="projects__icons">
				<i class="icon-select"></i>
				<span class="close" ng-click="$event.stopPropagation();select['periodi'] = false;beforeAfter({before : false, after : false})">
					<i class="icon-close"></i>
				</span>
			</span>
			<ul class="projects__options" ng-class="{'projects__options--visible':isSelect['periodi']}">
				<li class="projects__option" ng-click="$event.stopPropagation();select['periodi']='<?php echo $year; ?>';beforeAfter({before : '<?php echo $year; ?>', after : false})" ng-class="{'projects__option--selected':select['periodi']=='<?php echo $year; ?>'}"><?php _e('Pre', 'catellani'); ?> <?php echo $year; ?></li>
				<li class="projects__option" ng-click="$event.stopPropagation();select['periodi']='<?php echo $year; ?> - 2010';beforeAfter({after : '<?php echo $year; ?>', before : '2010'})" ng-class="{'projects__option--selected':select['periodi']=='<?php echo $year; ?> - 2010'}"> <?php echo $year; ?> - 2010</li>
				<li class="projects__option" ng-click="$event.stopPropagation();select['periodi']='2010 - 2015';beforeAfter({after : '2010', before : '2015'})" ng-class="{'projects__option--selected':select['periodi']=='2010 - 2015'}">2010 - 2015</li>
				<li class="projects__option" ng-click="$event.stopPropagation();select['periodi']='<?php _e('Post 2015', 'catellani'); ?>';beforeAfter({after : '2015', before : false})" ng-class="{'projects__option--selected':select['periodi']=='<?php _e('Post 2015', 'catellani'); ?>'}"><?php _e('Post 2015', 'catellani'); ?></li>
			</ul>
		</div>
	</div>
	<div class="projects__items projects__items--grow-lg projects__items--shrink-fw projects__items--grid">
		<span class="projects__cell projects__cell--found" ng-if="items.length <= 0 && firstLoad" ng-class="{'projects__cell--found-visible':firstLoad}"><span><?php _e('Ci dispiace, ma nessuna combinazione soddisfa i filtri selezionati.<br/>Puoi scegliere se resettare i filtri e riprovare oppure contattarci per creare insieme un nuovo fantastico progetto.', 'catellani'); ?></span></span>
		<a class="projects__cell projects__cell--s6 projects__cell--grow" ng-repeat="item in items | filter:search:strict" ng-href="{{item.link}}" ui-sref="app.page({slug : item.slug, lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})" clicked-element>
			<span class="projects__content" ng-style="{'background-image' : 'url({{image(item).url}})'}" ng-lazy-img="{{image(item).url}}">
			<img ng-src="{{image(item).url}}" ng-attr-alt="{{image(item).alt}}" />
			<span class="projects__title" ng-bind-html="item.title.rendered"></span>
			<span class="projects__location" ng-bind-html="item.acf.citta+', '+item.acf.stato+', '+(item.date | date: 'yyyy')"></span>
			</span>
		</a>
		<div class="projects__loader projects__loader--middle" ng-class="{'projects__loader--middle-visible':isLoading}"><div class="projects__spinner"></div></div>
	</div>
	<?php loader('projects', 'loadProjects'); ?>
</div>