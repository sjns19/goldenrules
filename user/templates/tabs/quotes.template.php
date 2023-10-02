<div class="my-2">
	<h3 class="txt-grey"><i class="fa fa-plus"></i> Add New</h3>
	<form class="mt-2" id="quote-form" data-token="<?= $csrf_token ?>">
		<div class="input-group input-group-inlined">
			<div class="input-inlined">
				<label for="quote-author" class="input-label">Author First Name</label>
				<input type="text" class="input mt-1" id="quote-author-firstname" placeholder="Type the author's first name" autocomplete="off" autocapitalize="off" title="Type the name of this quote's author" required>
				<span class="input-sub-label"></span>
			</div>
			<div class="input-inlined">
				<label for="quote-author" class="input-label">Author Last Name</label>
				<input type="text" class="input mt-1" id="quote-author-lastname" placeholder="Type the author's last name" autocomplete="off" autocapitalize="off" title="Type the name of this quote's author" required>
				<span class="input-sub-label"></span>
			</div>
		</div>
		<div class="input-group">
			<label for="quote-text" class="input-label">Quote <small class="input-sub-label pull-right">Max 155 chars - Min 10 chars</small></label>
			<textarea class="textarea" rows="6" id="quote-text" maxlength="155" placeholder="Type the quote text here" autocomplete="off" title="Type the quote here" required></textarea>
			<span class="input-sub-label"></span>
		</div>
		<button type="submit" class="btn btn-md btn-gold txt-uppercase" id="add-quote-btn" disabled>Add</button>
	</form>
</div>
<div class="my-4">
	<h3 class="txt-grey"><i class="fa fa-list"></i> All Quotes <span id="quote-count"></span></h3>
	<div class="table-wrapper" id="quote-list" data-token=<?= $csrf_token ?>>
		<div class="spinner"></div>
		<div class="txt-align-center txt-grey">Loading...</div>
	</div>
</div>