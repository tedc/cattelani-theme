<?php
	if($images):
	$imgs = '';
	$count=0; foreach($images as $img) :
	$comma = ($count>0) ? ',' : '';
	$imgs .= ($full) ? $comma. '{url : \''.$img['url'].'\',alt : \''.$img['alt'].'\'}' : $comma. '{url : \''.$img['url'].'\'}';
	$count++; endforeach;
?>
<ng-swiper>
<ks-swiper-container swiper="main" override-parameters="{'effect':'fade', 'fade' : {'crossFade' : true}}" show-nav-buttons="<?php echo count($images) > 1 ? 'true' : 'false'; ?>" ng-init="slider = [<?php echo $imgs; ?>]" loop="true" autoplay="5000">
	<ks-swiper-slide class="swiper-slide" ng-repeat="img in slider" ng-lazy-img="{{img.url}}">
		<figure class="swiper-slider__figure" ng-style="{'background-image':'url({{img.url}})'}"><img ng-src="{{img.url}}" ng-attr-alt="{{img.alt}}" /></figure>
	</ks-swiper-slide>
</ks-swiper-container>
</ng-swiper>
<?php endif; ?>