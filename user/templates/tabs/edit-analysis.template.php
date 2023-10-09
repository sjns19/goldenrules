<form id="edit-analysis-form" enctype="multipart/form-data" data-token="<?= $csrf_token ?>">
	<div class="txt-grey mb-2">
		<i class="txt-grey txt-sm">This trading analysis was posted by <span class="txt-gold"><?= $this->editable_analysis_data['author'] ?></span> on <?= date('F j, Y', $this->editable_analysis_data['posted_date_unix']) ?></i>
		<p class="mt-1">Make changes to the following contents to edit this trading analysis</p>
	</div>
	<div class="input-group">
		<label for="analysis-title" class="input-label">Edit Title <small class="input-sub-label pull-right">Max 100 chars - Min 30 chars</small></label>
		<input type="text" class="input" id="edit-analysis-title" placeholder="What is the title of this trading analysis?" value="<?= $this->editable_analysis_data['title'] ?>" autocomplete="off" title="Type the title of this analysis to edit" required>
		<span class="input-sub-label"></span>
	</div>
	<div class="input-group">
		<label for="analysis-thumbnail" class="input-label">
			<?= ($this->editable_analysis_data['has_thumbnail']) ? 'Edit' : 'Upload' ?> Analysis Chart
		</label>
		<input type="file" class="input-upload-btn" id="edit-analysis-thumb" name="analysis_thumbnail" data-max-size="1024" accept="image/jpg,image/jpeg,image/png">
		<label class="mt-1" for="edit-analysis-thumb" title="Select an image to upload"><i class="fa fa-image"></i>select image</label>                    
		<span class="input-sub-label"></span>
		<p class="txt-grey txt-sm mt-1">
			<?= (!$this->editable_analysis_data['has_thumbnail']) ? 'This analysis has no chart image, select an image to upload.' : 'Select an image to replace the chart of this analysis' ?> 
		</p>
	</div>
	<div id="cropped-thumbnail-preview">
<?php if ($this->editable_analysis_data['has_thumbnail']): ?>
		<img src="/media/thumbnails/analysis/<?= $this->editable_analysis_data['thumbnail_url'] ?>">
<?php endif; ?>
	</div>
	<div class="input-group">
		<label for="analysis-body-contents" class="input-label">Edit Contents</label>
		<div class="trumbowyg-dark">
			<textarea id="edit-analysis-body-contents" cols="10" rows="10" placeholder="Type analysis contents here..."></textarea>
		</div>
	</div>
</form>

<div class="my-1">
	<button type="submit" class="btn btn-md btn-gold" form="edit-analysis-form" id="save-analysis-btn"title="Save changes">Save</button>
	<button class="btn btn-md btn-gold-o" id="preview-edited-analysis-btn" value="Preview" title="Preview changes">Preview</button>
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
				<?php if ($this->editable_analysis_data['author_avatar_url'] != 'none'): ?>
						<img class="avatar-img" src="/media/admin-avatars/<?= $this->editable_analysis_data['author_avatar_url'] ?>">
				<?php else: ?>
						<div class="avatar-no-img" style="background-color:<?= $this->editable_analysis_data['author_avatar_color'] ?>"><?= $this->editable_analysis_data['author_first_letter'] ?></div>
				<?php endif; ?>
					</div>
					<div class="author">
						<h3 class="author-name">By <span class="txt-gold txt-bold"><?= $this->editable_analysis_data['author'] ?></span> • <?= date('M j, Y', $this->editable_analysis_data['posted_date_unix']) ?></h3>
						<p class="author-sub-text">Edited <?= date('M j, Y') ?> by <?= $name ?></p>
					</div>
				</div>
				<div class="article-body" id="preview-article-body"></div>
			</div>
		</div>
	</div>
</div>