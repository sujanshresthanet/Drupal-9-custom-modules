<?php

namespace Drupal\blog\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\blog\BlogInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\blog\Entity\Blog;
use Drupal\project\Entity\Project;
use Drupal\news_article\Entity\NewsArticle;

/**
 * Returns responses for blog module routes.
 */
class BlogController extends ControllerBase {

  /**
   * Route title callback.
   *
   * @param \Drupal\blog\BlogInterface $blog
   *   The blog entity.
   *
   * @return string
   *   The blog label.
   */
  public function blogTitle(BlogInterface $blog) {
    return $blog->label();
  }

  function get_data() {
    $blog_data = array();
    $keyword = \Drupal::request()->query->get('q');
    $entity_type = \Drupal::request()->query->get('type');

    $entity_ids = \Drupal::entityQuery($entity_type)
    ->condition('status', 1)
    ->condition('title', '%'.$keyword.'%', 'LIKE')
    ->execute();
    if(!empty($entity_ids)) {
      foreach ($entity_ids as $entity_id) {
        switch ($entity_type) {
          case 'blog':
          $entity = Blog::load($entity_id);
          break;

          case 'project':
          $entity = Project::load($entity_id);
          break;

          case 'news_article':
          $entity = NewsArticle::load($entity_id);
          break;
        }
        if($entity) {
          $entity_data[] = array(
            'id' => $entity_id,
            'title' => $entity->label(),
          );
        }
      }
    }
    return new JsonResponse($entity_data);
  }

}
