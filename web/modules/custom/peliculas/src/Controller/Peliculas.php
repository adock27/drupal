<?php

namespace Drupal\peliculas;

use Drupal\Core\Database\Database;

/**
 * Clase para gestionar películas.
 */
class Peliculas
{

  /**
   * ID de la película.
   *
   * @var int
   */
  public $peliculas_id;

  /**
   * Título de la película.
   *
   * @var string
   */
  public $titulo;

  /**
   * Año de la película.
   *
   * @var int
   */
  public $ano;

  /**
   * Duración de la película en minutos.
   *
   * @var int
   */
  public $duracion;

  /**
   * Género de la película.
   *
   * @var string
   */
  public $genero;

  /**
   * Sinopsis de la película.
   *
   * @var string
   */
  public $sinopsis;

  /**
   * Poster de la película.
   *
   * @var string
   */
  public $poster;

  /**
   * Crea una nueva instancia de Peliculas.
   *
   * @param array $registro
   *   Array con los datos de la película.
   */
  public function __construct(array $registro)
  {
    foreach ($registro as $propiedad => $valor) {
      $this->$propiedad = $valor;
    }
  }

  /**
   * Carga una película por su ID.
   *
   * @param int $peliculas_id
   *   ID de la película a cargar.
   *
   * @return static|null
   *   Una instancia de Peliculas si la película existe, o NULL si no existe.
   */
  public static function cargar($peliculas_id)
  {
    $conexion = Database::getConnection();
    $query = $conexion->select('peliculas', 'p')
      ->fields('p')
      ->condition('peliculas_id', $peliculas_id)
      ->range(0, 1);
    $resultado = $query->execute();
    $registro = $resultado->fetchAssoc();
    if ($registro) {
      return new static($registro);
    }
    return NULL;
  }

  /**
   * Guarda una película en la base de datos.
   */
  public function guardar()
  {
    $conexion = Database::getConnection();
    if ($this->peliculas_id) {
      // Actualizar película existente.
      $conexion->update('peliculas')
        ->fields([
          'titulo' => $this->titulo,
          'ano' => $this->ano,
          'duracion' => $this->duracion,
          'genero' => $this->genero,
          'sinopsis' => $this->sinopsis,
          'poster' => $this->poster,
        ])
        ->condition('peliculas_id', $this->peliculas_id)
        ->execute();
    } else {
      // Insertar nueva película.
      $conexion->insert('peliculas')
        ->fields([
          'titulo' => $this->titulo,
          'ano' => $this->ano,
          'duracion' => $this->duracion,
          'genero' => $this->genero,
          'sinopsis' => $this->sinopsis,
          'poster' => $this->poster,
        ])
        ->execute();
      $this->peliculas_id = $conexion->lastInsertId();
    }}
    /**
     * Elimina una película de la base de datos.
     */
    public function eliminar()
  {
    $conexion = Database::getConnection();
    $conexion->delete('peliculas')
      ->condition('peliculas_id', $this->peliculas_id)
      ->execute();
  }

}
