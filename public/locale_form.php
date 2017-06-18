<form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="locale_form" class="form-inline">
  <select name="language" class="form-control">
    <option value="en"<?php if ($language=='en'): ?> selected="selected"<?php endif ?>>English</option>
    <!-- <option value="fr"<?php if ($language=='fr'): ?> selected="selected"<?php endif ?>>français</option>-->
    <option value="zh-CN"<?php if ($language=='zh-CN'): ?> selected="selected"<?php endif ?>>汉语</option>
    <option value="ko"<?php if ($language=='ko'): ?> selected="selected"<?php endif ?>>한국어</option>
    <!-- <option value="jp"<?php if ($language=='jp'): ?> selected="selected"<?php endif ?>>日本語</option>-->
  </select>
  <input type="submit" class="btn" value="<?php echo _('submit') ?>" />
</form>
