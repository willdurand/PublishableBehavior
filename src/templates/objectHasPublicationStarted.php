
/**
 * has the publication already started
 */
public function hasPublicationStarted()
{
    if (!$this-><?php echo $publishedAtColumnGetter ?>()) {
<?php if (!$required): ?>
        return false;
<?php else: ?>
        return true;
<?php endif ?>
    }
    $now = new PropelDateTime('now');
    return $now->format('U') >= $this-><?php echo $publishedAtColumnGetter ?>('U');
}
