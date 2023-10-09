<?php if (!$email_verified): ?>
	<div class="alert alert-danger alert-icon">
		<i class="fa fa-exclamation-triangle"></i>
		<h1>email verification required</h1>
		<p>You must verify your email address before you can start posting your trading analysis.</p>
	</div>
<?php else: ?>
	<div class="py-1">
		<form id="analysis-form" enctype="multipart/form-data"method="POST" data-token="<?= $csrf_token ?>">
			<p class="txt-grey mb-2">Fill the following fields to post a trading analysis chart</p>
			<div class="input-group">
				<label for="analysis-title" class="input-label">Title <small class="input-sub-label pull-right mr-1">Max 100 chars - Min 30 chars</small></label>
				<input type="text" class="input" id="analysis-title" placeholder="What is the title of this trading analysis?" autocomplete="off" title="Type the title of this trading analysis post" required>
				<span class="input-sub-label"></span>
			</div>
			<div class="input-group">
				<label for="analysis-thumbnail" class="input-label">Upload Analysis Chart <span class="txt-dark-grey">(Required)</span></label>
				<input type="file" class="input-upload-btn" id="analysis-upload-thumb" name="analysis_thumbnail" data-max-size="1024" accept="image/jpg,image/jpeg,image/png">
				<label class="mt-1" for="analysis-upload-thumb" title="Select an image to upload"><i class="fa fa-image"></i>select file</label>
				<div id="cropped-thumbnail-preview"></div>
			</div>
			<div class="input-group">
				<label for="analysis-body-contents" class="input-label">Description</label>
				<div class="trumbowyg-dark">
				<textarea id="analysis-body-contents" cols="10" rows="10" placeholder="Type the analysis description here..."></textarea>
				</div>
			</div>
		</form>
		<div class="mb-1">
			<button type="submit" class="btn btn-md btn-gold" id="post-analysis-btn" form="analysis-form" title="Post this trading analysis" disabled>Post</button>
			<button class="btn btn-md btn-gold-o" id="preview-analysis-btn" title="Preview changes" disabled>Preview</button>
		</div>
	</div>
<?php endif; ?>

<div id="preview-article">
	<div class="wrapper">
		<div class="card">
			<div class="card-header flex flex-no-break p-2">
				<h1 class="txt-uppercase">preview</h1>
				<div id="preview-article-close" title="Close">&#10005;</div>
			</div>
			<div class="card-body">
				<div class="article">
					<div class="article-header">
						article-title" id="preview-article-title"></h1>
					</div>
					<div class="article-stats">
					<div class="avatar avatar-md">
				<?php if ($avatar_url != 'none'): ?>
						<img class="avatar-img" src="/media/admin-avatars/<?= $avatar_url ?>">
				<?php else: ?>
						<div class="avatar-no-img" style="background-color:<?= $avatar_color ?>"><?= App::getFirstLetterFromName($name) ?></div>
				<?php endif; ?>
					</div>
					<div class="author">
						<h3 class="author-name">By <span class="txt-gold txt-bold"><?= $name ?></span> • <?= date('M j, Y') ?></h3>
					</div>
					</div>
					<div class="article-body" id="preview-article-body"></div>
				</div>
			</div>
		</div>
	</div>
</div>