<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>

<div class="section-container">
	<section class="section">
		<div class="wrapper">
			<div class="section-header">
				<h1 class="section-title txt-grad-gold">trading analysis</h1>
				<p class="section-paragraph txt-grey mt-1">Here are all the trading analysis from our GoldenRules members.</p>
			</div>
			<div class="grid pb-2">

<?php if ($this->analysis_list && count($this->analysis_list)): ?>
<?php foreach ($this->analysis_list as $key => $val): ?>
				<div class="grid-item">
					<a href="<?= $val['url'] ?>" class="card card-link card-grey-bordered card-animated" title="<?= $val['title'] ?>">
						<div class="card-thumbnail">
							<img 
								src="<?= App::resizeImage($val['thumbnail_url'], 4, 4) ?>"
								data-src="<?= $val['thumbnail_url'] ?>"
								class="card-thumbnail-img grt-lzy"
								alt="A thumbnail for news titled '<?= $val['title'] ?>'"
							/>
						</div>
						<div class="card-body">
							<div class="flex align-items-center flex-no-break avatar-container">
								<div class="avatar avatar-sm">

						<?php if (!is_null($val['author_avatar_url'])): ?>
									<img 
										class="avatar-img grt-lzy" 
										src="<?= App::resizeImage($val['author_avatar_url'], 4, 4) ?>" 
										data-src="<?= $val['author_avatar_url'] ?>" 
										alt="<?= $author ?>'s avatar">
						<?php else: ?>
									<div class="avatar-no-img" style="background-color:<?= $val['author_avatar_color'] ?>;"><?= $val['author_first_letter'] ?></div>
						<?php endif; ?>

								</div>
								<small class="txt-grey txt-xs txt-uppercase"><?= $val['posted_date'] ?></small>
							</div>
							<p class="txt-grad-gold txt-md txt-bold txt-break"><?= $val['title'] ?></p>
						</div>
					</a>
				</div>
<?php endforeach; ?>
<?php else: ?>
				<div class="card pb-5">
					<div class="card-body">
						<h3 class="card-title txt-uppercase mb-1">nothing to show</h3>
						<p class="txt-grey">It seems, there are currently no trading analysis posted.</p>
						<p class="txt-grey">Please check back later.</p>
					</div>
				</div>
<?php endif; ?>
			</div>
<?php
	$pages = $this->number_of_pages;
	$active_page = $this->active_page_index;
	$dots = false;
?>

<?php if ($pages > 1): ?>
			<ul class="pagination">
			<?php if (1 < $active_page): ?>
				<li class="pagination-item">
					<a href="/news/?page=<?= ($active_page - 1) ?>" class="pagination-link" title="Previous">«</a>
				</li>
			<?php endif; ?>
<?php for ($p = 1, $total = $pages; $p <= $total; $p++): ?>
	<?php if ($p == $active_page): $dots = true; ?>
				<li class="pagination-item">
					<a href="" class="pagination-link active"><?= $p ?></a>
				</li>
	<?php else: ?>
		<?php if ($p <= 1 || ($active_page && $p >= ($active_page - 1) && $p <= $active_page + 1) || $p > ($pages - 1)): $dots = true; ?>
				<li class="pagination-item">
					<a href="/news/?page=<?= $p ?>" class="pagination-link" title="Go to page <?= $p ?>"><?= $p ?></a>
				</li>
		<?php elseif ($dots): $dots = false; ?>
				<li class="pagination-item">...</li>
		<?php endif; ?>
	<?php endif; ?>
<?php endfor; ?>
<?php if ($active_page < $pages || -1 == $pages): ?>
				<li class="pagination-item">
					<a href="/news/?page=<?= ($active_page + 1) ?>" class="pagination-link" title="Next">»</a>
				</li>
<?php endif; ?>
			</ul>
<?php endif; ?>
		</div>
	</section>
</div>