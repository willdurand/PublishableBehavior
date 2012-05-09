<?php if ($isPublishedByDefault) : ?>
$this-><?php echo $isPublishedColumnSetter ?>(true);
<?php else: ?>
if (false === $this->forcePublish) {
    $this-><?php echo $isPublishedColumnSetter ?>(false);
}
<?php endif; ?>
