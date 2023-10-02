<div class="py-1">
  <form id="news-form" enctype="multipart/form-data" data-token="<?= $csrf_token ?>">
    <p class="txt-grey mb-2">Fill the following fields to post a news article</p>
    <div class="input-group">
      <label for="news-title" class="input-label">Title <small class="input-sub-label pull-right mr-1">Max 100 chars - Min 30 chars</small></label>
      <input type="text" class="input" id="news-title" placeholder="What is the title of this news?" autocomplete="off" title="Type the title of this news post" required>
      <span class="input-sub-label"></span>
    </div>
    <div class="input-group">
      <label for="news-thumbnail" class="input-label">Thumbnail <span class="txt-dark-grey">(Optional)</span></label>
      <input type="file" class="input-upload-btn" id="news-upload-thumb" name="news_thumbnail" data-max-size="1024" accept="image/jpg,image/jpeg,image/png">
      <label for="news-upload-thumb" title="Select an image to upload"><i class="fa fa-image"></i>select image</label>
      <span class="input-sub-label"></span>
      <div id="cropped-thumbnail-preview"></div>
    </div>
    <div class="input-group">
      <div class="trumbowyg-dark">
        <label for="news-body-contents" class="input-label">Contents</label>
        <textarea id="news-body-contents" cols="10" rows="10" placeholder="Type the news contents here..."></textarea>
      </div>
    </div>
  </form>
  <div class="mb-1">
    <button type="submit"class="btn btn-md btn-gold" id="post-news-btn" form="news-form" title="Post this news" disabled>Post</button>
    <button class="btn btn-md btn-gold-o" id="preview-news-btn" title="Preview changes" disabled>Preview</button>
  </div>
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