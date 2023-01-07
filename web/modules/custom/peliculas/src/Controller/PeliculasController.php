<?php

namespace Drupal\peliculas\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\peliculas\Peliculas;


/**
 * Controlador para las acciones CRUD de las películas.
 */
class PeliculasController extends ControllerBase
{

  /**
   * La conexión a la base de datos.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Crea una nueva instancia del controlador.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   La conexión a la base de datos.
   */
  public function __construct(Connection $database)
  {
    $this->database = $database;
  }




  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Muestra la lista de películas.
   */
  public function listar()
  {
    $query = $this->database->select('peliculas', 'p');
    $query->fields('p', ['id', 'titulo', 'ano', 'duracion', 'genero', 'sinopsis', 'poster']);
    $result = $query->execute();

    $peliculas = [];
    foreach ($result as $row) {
      $peliculas[] = [
        'id' => $row->id,
        'titulo' => $row->titulo,
        'ano' => $row->ano,
        'duracion' => $row->duracion,
        'genero' => $row->genero,
        'sinopsis' => $row->sinopsis,
        'poster' => $row->poster,
      ];
    }

    return [
      '#theme' => 'peliculas_lista',
      '#peliculas' => $peliculas,
    ];
  }


  /**
   * Muestra la lista de películas.
   *
   * @return array
   *   Lista de películas.
   */
  public function lista() {
    $peliculas = Peliculas::cargarTodas();
    return [
      '#theme' => 'peliculas_lista',
      '#peliculas' => $peliculas,
    ];
  }



  /**
   * Muestra el formulario para agregar una película.
   */
  public function agregar() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\peliculas\Form\PeliculasForm');
    return $form;
  }



  /**
   * Muestra el formulario para editar una película.
   *
   * @param int $peliculas_id
   *   ID de la película a editar.
   */
  public function editar($peliculas_id) {
//    $pelicula = Peliculas::cargar($peliculas_id);
    $form = \Drupal::formBuilder()->getForm('\Drupal\peliculas\Form\PeliculasForm', $pelicula);
    return $form;
  }



  /**
   * Elimina una película.
   *
   * @param int $pelicula_id
   *   ID de la película a eliminar.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Redirección a la página de listado de películas.
   */
  public function eliminar($pelicula_id) {
    $pelicula = Peliculas::cargar($pelicula_id);
    $pelicula->eliminar();

    drupal_set_message('La película ha sido eliminada.');

    return $this->redirect('peliculas.list');
  }

  /**
   * Muestra el formulario de agregar o editar una película.
   *
   * @param int $pelicula_id
   *   ID de la película a editar.
   *
   * @return array
   *   Array con la configuración del elemento de renderizado.
   */
  public function formulario($pelicula_id = NULL) {
    $pelicula = Peliculas::cargar($pelicula_id);

    $form = \Drupal::formBuilder()->getForm('\Drupal\peliculas\Form\PeliculasForm', $pelicula);

    return [
      '#type' => 'markup',
      '#markup' => \Drupal::service('renderer')->render($form),
    ];
  }



}
