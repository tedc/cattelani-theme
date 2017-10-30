<div class="modal__container modal__container--search" collection-search id="modal-search" lang="<?php echo ICL_LANGUAGE_CODE; ?>">
	<div class="search">
		<div class="search__loader" ng-hide="isSearchEnded">
			<div class="search__spinner"></div>
		</div>
		<div class="search__filters search__filters--grow-top" ng-class="{'search__filters--visible':isSearchEnded}"> 
			<div class="search__select" click-outside="isSelect['collezioni']=false" ng-class="{'search__select--filled' : search.collezioni}" ng-click="$event.stopPropagation();isSelect['collezioni']=!isSelect['collezioni']">
				<!-- <span class="search__value" ng-bind-html="(search.collezioni ? select['collezioni'] : '<?php _e('Collezione', 'catellani'); ?>')"></span> -->
				<span class="search__value"><?php _e('Collezione', 'catellani'); ?></span>
				<span class="search__icons">
					<i class="icon-select"></i>
				</span>
				<select ng-model="search.collezioni" ng-options="opt.id as opt.name for opt in collections">			
					<option value=""></option>
				</select>
				<ul class="search__options" ng-class="{'search__options--visible':isSelect['collezioni']}">
					<li class="search__option"></li>
					<li class="search__option" ng-repeat="collection in collections" ng-bind="collection.name" ng-click="$event.stopPropagation();change('collezioni', collection)" ng-class="{'search__option--selected':selected('collezioni', collection.name)}" ng-attr-data-select="{{collection.name}}"></li>
				</ul>
			</div>

			<div class="search__select" click-outside="isSelect['posizioni']=false" ng-class="{'search__select--filled' : search.posizioni}" ng-click="isSelect['posizioni']=!isSelect['posizioni']">
				<!-- <span class="search__value" ng-bind-html="(search.posizioni ? select['posizioni'] : '<?php _e('Tipologia', 'catellani'); ?>')"></span> -->
				<span class="search__value"><?php _e('Tipologia', 'catellani'); ?></span>
				<span class="search__icons">
					<i class="icon-select"></i>
				</span>
				<select ng-model="search.posizioni" ng-options="opt.id as opt.name for opt in positions">			
					<option value=""></option>
				</select>
				<ul class="search__options" ng-class="{'search__options--visible':isSelect['posizioni']}">
					<li class="search__option"></li>
					<li class="search__option" ng-repeat="position in positions" ng-bind="position.name" ng-click="$event.stopPropagation();change('posizioni', position)" ng-class="{'search__option--selected':selected('posizioni', position.name)}" ng-attr-data-select="{{position.name}}"></li>
				</ul>
			</div>

			<div class="search__select" click-outside="isSelect['fonti']=false" ng-class="{'search__select--filled' : search.fonti}" ng-click="isSelect['fonti']=!isSelect['fonti']">
				<!-- <span class="search__value" ng-bind-html="(search.fonti ? select['fonti'] : '<?php _e('Fonte luminosa', 'catellani'); ?>')"></span> -->
				<span class="search__value"><?php _e('Fonte luminosa', 'catellani'); ?></span>
				<span class="search__icons">
					<i class="icon-select"></i>
				</span>
				<select ng-model="search.fonti" ng-options="opt.id as opt.name for opt in sources">			
					<option value=""></option>
				</select>
				<ul class="search__options" ng-class="{'search__options--visible':isSelect['fonti']}">
					<li class="search__option"></li>
					<li class="search__option" ng-repeat="source in sources" ng-bind="source.name" ng-click="$event.stopPropagation();change('fonti', source)" ng-class="{'search__option--selected':selected('fonti', source.name)}" ng-attr-data-select="{{source.name}}"></li>
				</ul>
			</div>
			<div class="search__select" click-outside="isOrder=false" ng-click="isOrder=!isOrder">
				<span class="search__value"><?php _e('Ordina', 'catellani'); ?>: {{orderValue()}}</span>
				<span class="search__icons">
					<i class="icon-select"></i>
				</span>
				<ul class="search__options" ng-class="{'search__options--visible':isOrder}">
					<li class="search__option" ng-class="{'search__option--selected':order=='+title'}" ng-click="$event.stopPropagation();changeOrder('+title')">A-Z</li>
					<li class="search__option" ng-class="{'search__option--selected':order=='-title'}" ng-click="$event.stopPropagation();changeOrder('-title')">Z-A</li>
				</ul>
			</div>
		</div>
		<div class="search__results" ng-if="select.collezioni.length > 0 || select.posizioni.length > 0 || select.fonti.length > 0">
			<div class="search__result" ng-if="select.collezioni.length > 0"><span><?php _e('Collezioni'); ?>:</span> <strong ng-repeat="c in select.collezioni">{{($index > 0) ? ', ' : ' '}}{{c}} <i class="icon-close" ng-click="clear(c)"></i></strong></div>
			<div class="search__result" ng-if="select.posizioni.length > 0"><span class="search__sep" ng-if="select.collezioni.length > 0">&mdash;</span><span><?php _e('Tipologie', 'catellani'); ?>:</span> <strong ng-repeat="p in select.posizioni">{{($index > 0) ? ', ' : ' '}}{{p}} <i class="icon-close" ng-click="clear( p)"></i></strong></div>
			<div class="search__result" ng-if="select.fonti.length > 0"><span class="search__sep" ng-if="select.collezioni.length > 0 || select.posizioni.length > 0">&mdash;</span><span><?php _e('Fonti'); ?>:</span> <strong ng-repeat="f in select.fonti">{{($index > 0) ? ', ' : ' '}}{{f}} <i class="icon-close" ng-click="clear(f)"></i></strong></div>

		</div>
		<div class="search__items search__items--shrink-fw" scrollbar="search" speed="1.5">
			<p class="search__cell search__cell--found search__cell--grow-md" ng-if="filtered() && isLoading">
				<span><?php _e('Nessuna lampada per la ricerca effettuata', 'catellani'); ?></span>
			</p>
			<a class="search__cell search__cell--grow-md" ng-repeat="item in items | taxSearch:search | orderBy:order" ui-sref="app.page({slug : item.slug, lang : '<?php echo ICL_LANGUAGE_CODE; ?>'})" on-finish-render="search_ended">
				<span class="search__content">
					<span class="search__figure" ng-lazy-img="{{image(item).url}}">
						<img ng-src="{{image(item).url}}" ng-attr-alt="{{image(item).alt}}" />
						<span class="search__mask"></span>
					</span>
					<span class="search__title search__title--name">
						<span ng-bind-html="item.title"></span>
					</span>
				</span>
			</a>
		</div>
	</div>
</div>