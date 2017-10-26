<?php if (get_field('header_video')) { ?>       
<div class="main-video" ng-player id="video_<?php echo get_the_ID(); ?>" ng-class="{'main-video--open' : isOpen}">
    <span class="main-video__close main-video__close--shrink main-video__close--grow-md-top" ng-click="close()">
        <span class="main-video__label"><?php _e('Chiudi', 'catellani'); ?></span>
        <span class="main-video__close-sign"><span class="main-video__line"></span></span>
    </span>
	<?php the_field('header_video'); ?>
    <div class="main-video__controls">
        <span class="main-video__play-pause" ng-click="isPaused=!isPaused;play(isPaused)" ng-class="{'main-video__play-pause--paused': !isPaused}">
            <i class="icon-play"></i>
            <i class="icon-pause"></i>
        </span>
        <span class="main-video__time" ng-bind-html="progress"></span>
        <div class="main-video__status" ng-click="skipTo($event)">
            <div class="main-video__buffer"></div>
            <div class="main-video__mask"></div>
        </div>
        <span class="main-video__time" ng-bind-html="time"></span>
        <span class="main-video__volume">
            <span ng-click="player.setVolume(<?php echo 1/3; ?>)" ng-class="{active : volume >= 0.25}"></span>
            <span ng-click="player.setVolume(<?php echo 1/2; ?>)" ng-class="{active : volume >= <?php echo 1/2; ?>}"></span>
            <span ng-click="player.setVolume(1)" ng-class="{active : volume == 1}"></span>
        </span>
    </div> 
</div>     
<?php } ?>