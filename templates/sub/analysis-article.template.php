<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); extract((array) $this->analysis_data); ?>

<div class="section-container">
	<section class="section">
		<div class="wrapper py-3">
			<div class="article">
				<div class="article-header">
					<h1 class="article-title"><?= $title ?></h1>
				</div>
				<div class="article-stats">
					<div class="avatar avatar-lg">

				<?php if (!is_null($author_avatar_url)): ?>
						<img class="avatar-img grt-lzy" src="<?= App::resizeImage($author_avatar_url, 4, 4) ?>" data-src="<?= $author_avatar_url ?>" alt="<?= $author ?>'s avatar">
				<?php else: ?>
						<div class="avatar-no-img" style="background-color:<?= $author_avatar_color ?>"><?= $author_first_letter ?></div>
				<?php endif; ?>
					</div>
					
					<div class="author">
						<h3 class="author-name">By <span class="txt-gold txt-bold"><?= $author ?></span> • <?= date('M j, Y', $posted_date_unix) ?></h3>
				
				<?php if ($edited_date_unix):?>
						<p class="author-sub-text">Edited <?= date('M j, Y', $edited_date_unix) ?> by <?= $editor ?></p>
				<?php endif; ?>
				
					</div>
				</div>
				<div class="article-image-frame m-1">
					<img class="article-image grt-lzy" src="<?= SITE_URL ?>/lib/resizer.php?u=<?= urlencode($thumbnail) ?>&h=2&w=2" data-src="<?= $thumbnail ?>" alt="<?= $title ?>">
				</div>
				<div class="article-body"><?= $body ?></div>
			</div>
		</div>
	</section> 
</div>