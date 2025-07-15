<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \App\Module\Common\Config\GlobalStateConfig $globalState
 * @var int<1, 3> $stepIndicator
 */

if ($globalState->configured) {
    return;
}

$stepModifier = static fn(int $step): string => match (true) {
    $step < $stepIndicator => 'completed',
    $step === $stepIndicator => 'active',
    default => '',
};
?>

<div class="app-header">
    <h1 class="app-title">Happy Wife - Happy Life</h1>
    <p class="app-subtitle">Помощник в построении гармоничных отношений</p>
</div>

<div class="step-indicator">
    <div class="step <?= $stepModifier(1) ?>">1</div>
    <div class="step-line  <?= $stepModifier(1) ?>"></div>
    <div class="step <?= $stepModifier(2) ?>">2</div>
    <div class="step-line <?= $stepModifier(2) ?>"></div>
    <div class="step <?= $stepModifier(3) ?>">3</div>
    <div class="step-line <?= $stepModifier(3) ?>"></div>
    <div class="step <?= $stepModifier(4) ?>">4</div>
</div>
