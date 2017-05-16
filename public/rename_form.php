<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET['dir'])) {
    $current_folder = $_GET['dir'];
}
?>
<div class="modal-dialog">
	<form action="rename.php" method="post" class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel"><?php echo _('Rename') ?></h4>
		</div>
		<div class="modal-body">
			<input type="hidden" name="dir" value="<?php if (empty($current_folder)): ?><?php else: ?><?php echo $current_folder ?><?php endif ?>" />
			<div class="form-group" style="display:none">
				<input type="hidden" />
				<label></label>
				<input type="text" class="form-control" />
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary"><?php echo _('Confirm') ?></button>
		</div>
	</form>
</div>
