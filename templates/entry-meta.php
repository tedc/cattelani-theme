<time class="<?php $margin = (!is_single()) ? ' type-post__updated--grow-md' : ''; echo (is_single()) ? 'container' : 'type-post'; ?>__updated<?php echo $margin; ?>" datetime="<?= get_post_time('c', true); ?>"<?php //scrollmagic('"pin":{"pushFollowers":false},"triggerElement":"#container_'.get_the_ID().'","triggerHook":"onLeave","duration":"100%","durationElement":"#container_'.get_the_ID().'"'); ?>><?= get_the_date(); ?></time>
