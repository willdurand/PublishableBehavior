
/**
 * has the publication already ended
 */
public function hasPublicationEnded()
{
    if (!$this-><?php echo $publishedUntilColumnGetter ?>()) {
<?php if (!$required): ?>
        return false;
<?php else: ?>
        return true;
<?php endif ?>
    }
    $now = new PropelDateTime('now');
    return $now->format('U') > $this-><?php echo $publishedUntilColumnGetter ?>('U');
}
