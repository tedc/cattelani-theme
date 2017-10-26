<div  class="posts__cell posts__cell--grow-md posts__cell--s6" ng-repeat="post in posts | limitTo: 2">
	<article ng-class="post.post_class">
		<img ng-src="{{image(post).url}}" ng-attr-alt="{{image(post).alt}}" />
		<header class="type-post__header type-post__header--shrink">
			<time class="type-post__updated--grow-md type-post__updated" ng-attr-datetime="{{post.post_date}}"><span ng-bind-html="post.post_date"></span></time>
			<h2 class="type-post__title post__title--medium-alternate"><a ng-href="{{post.link}}" ui-sref="app.page({slug : post.slug})" ng-bind-html="post.title.rendered"></a></h2>
		</header>
		<div class="type-post__summary type-post__summary--shrink type-post__summary--grow-md" ng-bind-html="post.excerpt.rendered">
		</div>
		<footer class="type-post__more type-post__more--shrink">
			
			<a ng-href="{{post.link}}" ui-sref="app.page({slug : post.slug})" class="type-post__send"><?php _e('Leggi tutto'); ?></a>
		</footer>
	</article>
</div>
<div class="posts__cell--grow-md posts__cell--facebook posts__cell--facebook-s12" ng-if="posts.length > 2">
<?php get_template_part( 'builder/facebook', null );
 ?>
</div>
<div  class="posts__cell posts__cell--s6" ng-repeat="post in posts | limitTo: 2:2">
	<article ng-class="post.post_class">
		<img ng-src="{{image(post).url}}" ng-attr-alt="{{image(post).alt}}" />
		<header class="type-post__header type-post__header--shrink">
			<time class="type-post__updated--grow-md type-post__updated" ng-attr-datetime="{{post.post_date}}"><span ng-bind-html="post.post_date"></span></time>
			<h2 class="type-post__title post__title--medium-alternate"><a ng-href="{{post.link}}" ui-sref="app.page({slug : post.slug})" ng-bind-html="post.title.rendered"></a></h2>
		</header>
		<div class="type-post__summary type-post__summary--shrink type-post__summary--grow-md" ng-bind-html="post.excerpt.rendered">
		</div>
		<footer class="type-post__more type-post__more--shrink">
			
			<a ng-href="{{post.link}}" ui-sref="app.page({slug : post.slug})" class="type-post__send"><?php _e('Leggi tutto'); ?></a>
		</footer>
	</article>
</div>
<div class="posts__cell posts__cell--instagram posts__cell--instagram-s12" ng-if="posts.length > 2">
	<?php get_template_part( 'builder/instagram', null );
	 ?>
</div>
<div  class="posts__cell posts__cell--s6" ng-repeat="post in posts | limitTo: (posts.length - 4):4">
	<article ng-class="post.post_class">
		<img ng-src="{{image(post).url}}" ng-attr-alt="{{image(post).alt}}" />
		<header class="type-post__header type-post__header--shrink">
			<time class="type-post__updated--grow-md type-post__updated" ng-attr-datetime="{{post.post_date}}"><span ng-bind-html="post.post_date"></span></time>
			<h2 class="type-post__title post__title--medium-alternate"><a ng-href="{{post.link}}" ui-sref="app.page({slug : post.slug})" ng-bind-html="post.title.rendered"></a></h2>
		</header>
		<div class="type-post__summary type-post__summary--shrink type-post__summary--grow-md" ng-bind-html="post.excerpt.rendered">
		</div>
		<footer class="type-post__more type-post__more--shrink">
			
			<a ng-href="{{post.link}}" ui-sref="app.page({slug : post.slug})" class="type-post__send"><?php _e('Leggi tutto'); ?></a>
		</footer>
	</article>
</div>