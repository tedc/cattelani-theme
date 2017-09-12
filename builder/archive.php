<div class="projects projects--grow-lg" post-type-archive post-type="<?php the_sub_field('post_type'); ?>" lang="<?php echo ICL_LANGUAGE_CODE; ?>">
	<div class="projects__filters projects__filters--gray projects__filters--grow-md projects__filters--grid"> 
		<div class="projects__select" click-outside="isSelect['collezioni']=false" ng-class="{'projects__select--filled' : projects.collezioni}" ng-click="$event.stopPropagation();isSelect['collezioni']=!isSelect['collezioni']">
			<span class="projects__value" ng-bind-html="(projects.collezioni ? select['collezioni'] : '<?php _e('Scegli una collezione', 'catellani'); ?>')"></span>
			<span class="projects__icons">
				<i class="icon-select"></i>
				<i class="icon-close" ng-click="$event.stopPropagation();change('collezioni', false)"></i>
			</span>
			<select ng-model="projects.collezioni" ng-options="opt.id as opt.name for opt in collections">			
				<option value=""></option>
			</select>
			<ul class="projects__options" ng-class="{'projects__options--visible':isSelect['collezioni']}">
				<li class="projects__option"></li>
				<li class="projects__option" ng-repeat="collection in collections" ng-bind="collection.name" ng-click="$event.stopPropagation();change('collezioni', collection)" ng-class="{'projects__option--selected':projects.collezioni==collection.id}"></li>
			</ul>
		</div>
		<div class="projects__select" click-outside="isSelect['tipologie']=false" ng-class="{'projects__select--filled' : projects.tipologie}" ng-click="isSelect['tipologie']=!isSelect['tipologie']">
			<span class="projects__value" ng-bind-html="(projects.tipologie ? select['tipologie'] : '<?php _e('Scegli una tipologia', 'catellani'); ?>')"></span>
			<span class="projects__icons">
				<i class="icon-select"></i>
				<i class="icon-close" ng-click="$event.stopPropagation();change('tipologie', false)"></i>
			</span>
			<select ng-model="projects.tipologie" ng-options="opt.id as opt.name for opt in tipologie">			
				<option value=""></option>
			</select>
			<ul class="projects__options" ng-class="{'projects__options--visible':isSelect['tipologie']}">
				<li class="projects__option"></li>
				<li class="projects__option" ng-repeat="tipologia in tipologie" ng-bind="tipologia.name" ng-click="change('tipologie', tipologia)" ng-class="{'projects__option--selected':projects.tipologie==tipologia.id}"></li>
			</ul>
		</div>
		<div class="projects__select" click-outside="isSelect['periodi']=false" ng-class="{'projects__select--filled' : select['periodi']!=false}" ng-click="isSelect['periodi']=!isSelect['periodi']">
			<span class="projects__value" ng-bind-html="(select['periodi'] ? select['periodi'] : '<?php _e('Scegli un periodo', 'catellani'); ?>')"></span>
			<span class="projects__icons">
				<i class="icon-select"></i>
				<i class="icon-close" ng-click="$event.stopPropagation();select['periodi'] = false;beforeAfter({before : false, after : false})"></i>
			</span>
			<ul class="projects__options" ng-class="{'projects__options--visible':isSelect['periodi']}">
				<li class="projects__option" ng-click="select['periodi']=2000;beforeAfter({before : 2000, after : false})" ng-class="{'projects__option--selected':select['periodi']==2000}"><?php _e('Pre 2000', 'catellani'); ?></li>
				<li class="projects__option" ng-click="select['periodi']=2005;beforeAfter({after : 2000, before : 2010})" ng-class="{'projects__option--selected':select['periodi']==2005}">2000 - 2010</li>
				<li class="projects__option" ng-click="select['periodi']=2012;beforeAfter({after : 2010, before : 2015})" ng-class="{'projects__option--selected':select['periodi']==2012}">2010 - 2015</li>
				<li class="projects__option" ng-click="select['periodi']=2015;beforeAfter({after : 2015, before : false})" ng-class="{'projects__option--selected':select['periodi']==2015}"><?php _e('Post 2015', 'catellani'); ?></li>
			</ul>
		</div>
	</div>
	<div class="projects__items projects__items--grow-lg projects__items--shrink-fw projects__items--grid">
		<a class="projects__cell projects__cell--s6 projects__cell--grow-md" ng-repeat="item in items | filter:search:strict" ng-href="{{item.link}}" ui-sref="app.page({slug : item.slug, lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})" clicked-element>
			<span class="projects__content" ng-style="{'background-image' : 'url({{image(item).url}})'}">
			<img ng-src="{{image(item).url}}" ng-attr-alt="{{image(item).alt}}" />
			<span class="projects__title" ng-bind-html="item.title.rendered"></span>
			<span class="projects__location" ng-bind-html="item.acf.citta+', '+item.acf.stato+', '+(item.date | date: 'yyyy')"></span>
			</span>
		</a>
	</div>
</div>