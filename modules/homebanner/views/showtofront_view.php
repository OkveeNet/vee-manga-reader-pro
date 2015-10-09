<?php if ( isset($banner_img) && $banner_img != null ): ?>
	<?php if ( isset($banner_url) && $banner_url != null ): ?><a href="<?php echo $banner_url; ?>"><?php endif; ?>
	<img src="<?php echo base_url().$banner_img; ?>" alt="banner" />
	<?php if ( isset($banner_url) && $banner_url != null ): ?></a><?php endif; ?>
<?php else: ?>
	<img src="<?php echo base_url(); ?>/client/images/banner/home-default-banner.jpg" alt="banner" />
<?php endif; ?>
