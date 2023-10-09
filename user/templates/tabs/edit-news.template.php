<form id="edit-news-form" enctype="multipart/form-data" data-token="<?= $csrf_token ?>">
	<div class="txt-grey mb-2">
		<i class="txt-grey txt-sm">This news was posted by <span class="txt-gold"><?= $this->editable_news_data['author'] ?></span> on <?= date('F j, Y', $this->editable_news_data['posted_date_unix']) ?></i>
		<p class="mt-1">Make changes to the following contents to edit this news</p>
	</div>
	<div class="input-group">
		<label for="news-title" class="input-label">Edit Title <small class="input-sub-label pull-right mr-1">Max 100 chars - Min 30 chars</small></label>
		<input type="text" class="input" id="edit-news-title" placeholder="What is the title of this news?" value="<?= $this->editable_news_data['title'] ?>" autocomplete="off" title="Type the title of this news to edit" required>
		<span class="input-sub-label"></span>
	</div>
	<div class="input-group">
		<label for="news-thumbnail" class="input-label">
			<?= ($this->editable_news_data['has_thumbnail']) ? 'Edit' : 'Upload' ?> Thumbnail
		</label>
		<input type="file" class="input-upload-btn" id="edit-news-thumb" name="news_thumbnail" data-max-size="1024" accept="image/jpg,image/jpeg,image/png">
		<label class="mt-1" for="edit-news-thumb" title="Select an image to upload"><i class="fa fa-image"></i>select image</label>
		<div class="input-hint txt-grey mt-1">
			<small><?= (!$this->editable_news_data['has_thumbnail']) ? 'This news has no thumbnail, select an image to upload.' : 'Select an image to change the thumbnail of this news' ?></small>
		</div>

<?php if ($this->editable_news_data['has_thumbnail']): ?>
		<div class="mt-2">
			<a href='javascript:' class="link-grey link-no-underline" form="undefined" title="Remove this thumbnail" id="remove-thumbnail"><i class="fa fa-times"></i> Remove Thumbnail</a>
		</div>
<?php endif; ?>

		<div id="cropped-thumbnail-preview">

	<?php if ($this->editable_news_data['xhas_thumbnail']): ?>
			<img src="/media/thumbnails/news/<?= $this->editable_news_data['thumbnail_url'] ?>">
	<?php endif; ?>

		</div>
	</div>
	<div class="input-group">
		<div class="trumbowyg-dark">
			<label for="news-body-contents" class="input-label">Edit Contents</label>
			<textarea id="edit-news-body-contents" cols="10" rows="10" placeholder="Type news contents here..."></textarea>
		</div>
	</div>
</form>

<div class="my-1">
	<button type="submit"class="btn btn-md btn-gold" id="save-news-btn" form="edit-news-form" title="Save changes">Save</button>
	<button class="btn btn-md btn-gold-o" id="preview-edited-news-btn" title="Preview changes">Preview</button>
</div>

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
					<h1 class="article-title" id="preview-article-title"></h1>
				</div>
				<div class="article-stats">
					<div class="avatar avatar-md">
				<?php if (!is_null($this->editable_news_data['author_avatar'])): ?>
						<img class="avatar-img" src="/media/admin-avatars/<?= $this->editable_news_data['author_avatar'] ?>" />
				<?php else: ?>
						<div class="avatar-no-img" style="background-color:<?= $this->editable_news_data['author_avatar_color'] ?>"><?= $this->editable_news_data['author_first_letter'] ?></div>
				<?php endif; ?>
					</div>
					<div class="author">
						<h3 class="author-name">By <span class="txt-gold"><?= $this->editable_news_data['author'] ?></span> • <?= date('M j, Y', $this->editable_news_data['posted_date_unix']) ?></h3>
						<p class="author-sub-text">Edited <?= date('M j, Y') ?> by <?= $name ?></p>
					</div>
				</div>
			</div>
			<div class="article-body" id="preview-article-body"></div>
		</div>
	</div>
</div>