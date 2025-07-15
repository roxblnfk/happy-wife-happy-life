<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Throwable $exception
 * @var null|\App\Module\LLM\Config\LLMConfig $LLMConfig
 */
?>

<div class="mt-4">
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Ошибка:</strong><br>
        <?= \htmlspecialchars($exception->getMessage()) ?>
    </div>
</div>
