<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'console.command.translation_debug' shared service.

$this->services['console.command.translation_debug'] = $instance = new \Symfony\Bundle\FrameworkBundle\Command\TranslationDebugCommand(${($_ = isset($this->services['translator.default']) ? $this->services['translator.default'] : $this->getTranslator_DefaultService()) && false ?: '_'}, ${($_ = isset($this->services['translation.reader']) ? $this->services['translation.reader'] : $this->load('getTranslation_ReaderService.php')) && false ?: '_'}, ${($_ = isset($this->services['translation.extractor']) ? $this->services['translation.extractor'] : $this->load('getTranslation_ExtractorService.php')) && false ?: '_'}, '/html/translations', '/html/templates');

$instance->setName('debug:translation');

return $instance;
