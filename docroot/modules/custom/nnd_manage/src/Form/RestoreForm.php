<?php
/**
 * @file
 * Contains \Drupal\workspace_ccms\Form\PurgeForm.
 */

namespace Drupal\nnd_manage\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RestoreForm extends ConfirmFormBase {

  /**
   * @var \Drupal\Core\Entity\ContentEntityInterface
   */
  protected $entity;

  /**
   * The entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'restore_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to restore "@label"?', ['@label' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('The @entity "@label" will be restored.', ['@entity' => $this->entity->getEntityType()->get('label'), '@label' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Restore');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    try {
      return Url::fromUserInput(\Drupal::destination()->get());
    } catch (\Exception $e) {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $entity_type_id = NULL, $entity_id = NULL) {
    if (!empty($entity_type_id)) {
      /** @var \Drupal\multiversion\Entity\Storage\ContentEntityStorageInterface $storage */
      $storage = $this->entityTypeManager->getStorage($entity_type_id);

      if (!$this->entity = $storage->loadDeleted($entity_id)) {
        \Drupal::messenger()->addMessage(t('Unable to load deleted entity.'), 'error');
        return;
      }
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->_deleted = FALSE;
    if ($entity->save()) {
      \Drupal::messenger()->addMessage(t('The @entity "@label" has been restored.', ['@entity' => $this->entity->getEntityType()->get('label'), '@label' => $this->entity->label()]));
    }
  }

}
