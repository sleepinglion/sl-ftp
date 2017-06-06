<form action="" id="locale_form" class="form-inline">
  <select name="language" class="form-control">
    <option value="english"<?php if ($language=='english'): ?> selected="selected"<?php endif ?>><?php echo _('english') ?></option>
    <!--<option value="chineses"<?php if ($language=='chineses'): ?> selected="selected"<?php endif ?>><?php echo _('chineses') ?></option>-->
    <option value="korean"<?php if ($language=='korean'): ?> selected="selected"<?php endif ?>><?php echo _('korean') ?></option>
    <!-- <option value="japaneses"<?php if ($language=='japaneses'): ?> selected="selected"<?php endif ?>><?php echo _('japaneses') ?></option>-->
  </select>
  <input type="submit" class="btn" value="<?php echo _('submit') ?>" />
</form>
