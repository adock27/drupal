<?php

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class ExampleController extends ControllerBase {

  public function example() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, world!'),
    ];
  }


  public function add() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, world!'),
    ];
  }
}
