<?php

namespace Drupal\peliculas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\peliculas\Peliculas;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Formulario de agregar o editar películas.
 */
class PeliculasForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'peliculas_formulario';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Peliculas $pelicula = NULL)
  {
    $form['titulo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#required' => TRUE,
      '#default_value' => $pelicula ? $pelicula->getTitulo() : '',
    ];
    $form['ano'] = [
      '#type' => 'number',
      '#title' => $this->t('Año'),
      '#required' => TRUE,
      '#default_value' => $pelicula ? $pelicula->getAno() : '',
    ];
    $form['duracion'] = [
      '#type' => 'number',
      '#title' => $this->t('Duración'),
      '#required' => TRUE,
      '#default_value' => $pelicula ? $pelicula->getDuracion() : '',
    ];
    $form['genero'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Género'),
      '#required' => TRUE,
      '#default_value' => $pelicula ? $pelicula->getGenero() : '',
    ];
    $form['sinopsis'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Sinopsis'),
      '#required' => TRUE,
      '#default_value' => $pelicula ? $pelicula->getSinopsis() : '',
    ];

    $form['poster'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Poster'),
      '#required' => TRUE,
      '#default_value' => $pelicula ? $pelicula->getPoster() : '',];

    $form['acciones'] = [
      '#type' => 'actions',
    ];

    $form['acciones']['submit'] = [
      '#type' => 'submit',
      '#value' => $pelicula ? $this->t('Guardar cambios') : $this->t('Agregar película'),
    ];

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this
        ->t('Add'),
    );
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $valores = $form_state->getValues();

    if (!is_numeric($valores['ano'])) {
      $form_state->setErrorByName('ano', $this->t('El año debe ser un número.'));
    }
    if (!is_numeric($valores['duracion'])) {
      $form_state->setErrorByName('duracion', $this->t('La duración debe ser un número.'));
    }
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $valores = $form_state->getValues();

    $pelicula = Peliculas::crear([
      'titulo' => $valores['titulo'],
      'ano' => $valores['ano'],
      'duracion' => $valores['duracion'],
      'genero' => $valores['genero'],
      'sinopsis' => $valores['sinopsis'],
      'poster' => $valores['poster'],
    ]);
    $pelicula->guardar();

    drupal_set_message('La película ha sido guardada.');

    $form_state->setRedirect('peliculas.list');
  }


}
