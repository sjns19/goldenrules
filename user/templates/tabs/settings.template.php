<div class="flex flex-dir-col" id="settings" data-token="<?= $csrf_token ?>">
	<div class="card">
		<div class="card-header flex py-5">
			<div class="flex align-items-center">
        		<div class="avatar avatar-lg avatar-bordered dropdown-toggle" data-target="#avatar-dropdown" title="Click to edit avatar" style="background-color: <?= $avatar_color ?>;">
				<?php if ($avatar_url != 'none'): ?>
					<img class="avatar-img" src="/media/avatars/<?= $avatar_url ?>" alt="<?= $name ?>'s profile picture">
				<?php else: ?>
					<div class="avatar-no-img" style="background-color:<?= $avatar_color ?>"><?= $first_letter ?></div>
				<?php endif; ?>
        		</div>
	
				<input type="file" class="hidden" id="avatar-input" name="admin_avatar" accept="image/jpg,image/jpeg,image/png">
				<div class="dropdown dropdown-center" id="avatar-dropdown">
					<div class="dropdown-contents">
						<label for="avatar-input" class="dropdown-link" title="Change avatar">
							<i class="fa fa-refresh"></i> Change
						</label>
				<?php if ($avatar_url != 'none'): ?>
						<a href="javascript:" class="dropdown-link" id="remove-avatar" title="Remove avatar">
							<i class="fa fa-trash"></i> Remove
						</a>
				<?php endif; ?>
					</div>
				</div>
				<h3 class="p-2"><?= $name ?></h3>
			</div>
		</div>
 	 </div>

  	<a href="javascript:" class="card card-link card-animated <?= ($email_verified) ?: 'force-disabled' ?> mb-1" id="change-email" title="Change your email">
   	 	<div class="card-body flex flex-centered flex-no-break txt-grey p-2">
      		<i class="fa fa-envelope txt-md"></i>
      		<div class="ml-2">
        		<p>Change Email</p>
		<?php if (!$this->user['email_verified']): ?>
        		<p class="txt-danger-light txt-sm">You must verify your current email address before you can change it.</p>
		<?php endif; ?>
      		</div>
    	</div>
	</a>
  	<a class="card card-link card-animated" id="change-password" title="Change your password">
    	<div class="card-body flex flex-centered flex-no-break txt-grey p-2">
      		<i class="fa fa-key txt-md"></i>
      		<p class="ml-2">Change Password</p>
    	</div>  
  	</a>
</div>