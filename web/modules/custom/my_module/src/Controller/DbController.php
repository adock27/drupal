<?php

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DbController extends ControllerBase
{

  private $db;

  public function __construct(Connection $database)
  {
    $this->db = $database;

  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database')
    );
  }

//  LISTAR SERVICIOS
  public function servicios()
  {

    $formulario = $this->formBuilder()->getForm('\Drupal\my_module\Form\MyModuleForm');

    $query = $this->db->select('servicios', 's');
    $query->fields('s', ['id', 'nombre', 'descripcion']);
    $query->orderBy('s.nombre', 'DESC');
    $result = $query->execute();
    $data = $result->fetchAll();
    \Drupal::messenger()->addMessage('Dato agregado correctamente');
    return [
      '#theme' => 'plantilla_servicios',
      '#form' => $formulario,
      '#data' => $data,
    ];
  }

//  OBTENER SERVICIOS POR ID
  public function getServicioById($id)
  {



    $query = $this->db->select('servicios', 's');
    $query->fields('s', ['id', 'nombre', 'descripcion']);
    $query->condition('id', $id);
    $result = $query->execute();
    $data = $result->fetchAll();

//verifica la id si tiene datos

    if (!empty($data)) {
      $nombre = $data[0]->nombre;
      $descripcion = $data[0]->descripcion;

      $build = "<h2>Detalles: $nombre</h2>
                    <p>$descripcion</p>
                    <a class='button--primary button' href='/servicios/$id/actualizar'>Editar</a>";
    } else {
      $build = "<h2>No hay datos para este id: $id</h2>";
    }


    return [
      '#type' => 'markup',
      '#markup' => $build,
    ];
  }

//  ACTUALIZAR SERVICIOS POR ID
  public function actualizarServicio($id)
  {

    $formulario = $this->formBuilder()->getForm('\Drupal\my_module\Form\MyModuleForm');


    $markup = ['#markup' => $this->t('esta es la pagina con el formulario'),];

    $build[] = $formulario;
    $build[] = $markup;
    return $build;
  }

//  ELIMINAR SERVICIOS POR ID
  public function eliminarServicio($id)
  {

    $this->db->delete('servicios')
      ->condition('id',$id)
      ->execute();

    \Drupal::messenger()->addMessage('Dato eliminado correctamente');

    $build = "<h2>Eliminado correctamente</h2>
                    <a href='/servicios'>Regresar</a>";
    return [
      '#type' => 'markup',
      '#markup' => $build,
    ];
  }


}













/*
  public function formController()
  {

    $formulario = $this->formBuilder()->getForm('\Drupal\my_module\Form\MyModuleForm');



    $markup = ['#markup' => $this->t('esta es la pagina con el formulario'),];

    $build[] = $formulario;
    $build[] = $markup;
    return $build;
  }
*/
