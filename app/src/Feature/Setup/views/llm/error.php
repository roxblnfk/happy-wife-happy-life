<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var \App\Module\Common\Config\GlobalStateConfig $globalState
 * @var \Throwable $exception
 * @var null|\App\Module\LLM\Config\LLMConfig $LLMConfig
 */

use App\Feature\Setup\Controller;

?>

<div class="mt-4">
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Ошибка:</strong><br>
        <?= \htmlspecialchars($exception->getMessage()) ?>
    </div>

    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary"
                hx-get="<?= $router->uri(Controller::ROUTE_SETUP, $globalState->configured ? [] : ['page' => 'relation']) ?>"
                hx-target="#app-content">
            Назад
        </button>
        <?php if (!$globalState->configured): ?>
            <button type="button" class="btn btn-outline-secondary" hx-get="/setup/relation" hx-target="#app-content">
                Назад
            </button>
        <?php endif; ?>
    </div>
</div>
