<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); extract((array) $this->news_data); ?>

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

					<?php if ($edited_date_unix) :?>
						<p class="author-sub-text">Edited <?= date('M j, Y', $edited_date_unix) ?> by <?= $editor ?></p>
					<?php endif; ?>
						
					</div>
				</div>
				<ul class="article-share mb-3">
					<i class="icon icon-sm icon-share" title="Share this news"></i></li>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?= $this->news_data['share_url'] ?>" class="article-share-link" title="Share this news on Facebook" target="_blank" rel="nofollow"><i class="icon icon-sm icon-facebook"></i></a>
					<a href="https://twitter.com/intent/tweet?text=<?= urlencode($this->news_data['title']) ?>&url=<?= $this->news_data['share_url'] ?>" class="article-share-link" title="Tweet this news on Twitter" target="_blank" rel="nofollow"><i class="icon icon-sm icon-twitter" target="_blank" rel="nofollow"></i></a>
				</ul>
				<div class="article-body"><?= $body ?></div>
			</div>
		</div>
	</section>
	
<?php if ($this->related_news && count($this->related_news)): ?>
	<section class="section section-primary">
		<div class="wrapper">
			<div class="section-header">
				<h1 class="section-title txt-grad-gold">related news</h1>
			</div>
			<div class="grid">
<?php foreach ($this->related_news as $key => $val): ?>
				<div class="grid-item">
					<a href="<?= $val['url'] ?>" class="card card-link card-grey-bordered" title="<?= $val['title'] ?>">
						<div class="card-thumbnail">
							<img 
								src="<?= App::resizeImage($val['thumbnail_url'], 4, 4) ?>"
								data-src="<?= $val['thumbnail_url'] ?>"
								class="card-thumbnail-img grt-lzy"
								alt="A thumbnail for news titled '<?= $val['title'] ?>'"
							/>
						</div>
						<div class="card-body">
							<h3 class="txt-grad-gold"><?= $val['title'] ?></h3>
							<p class="txt-grey txt-sm"><?= $val['body'] ?></p>
						</div>
					</a>
				</div>
<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

</div>