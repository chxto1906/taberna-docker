<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerUm8tx2x\appDevDebugProjectContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerUm8tx2x/appDevDebugProjectContainer.php') {
    touch(__DIR__.'/ContainerUm8tx2x.legacy');

    return;
}

if (!\class_exists(appDevDebugProjectContainer::class, false)) {
    \class_alias(\ContainerUm8tx2x\appDevDebugProjectContainer::class, appDevDebugProjectContainer::class, false);
}

return new \ContainerUm8tx2x\appDevDebugProjectContainer(array(
    'container.build_hash' => 'Um8tx2x',
    'container.build_id' => '641a5aed',
    'container.build_time' => 1554131342,
), __DIR__.\DIRECTORY_SEPARATOR.'ContainerUm8tx2x');