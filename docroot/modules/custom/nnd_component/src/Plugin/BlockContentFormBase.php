<?php
/**
 * Created by PhpStorm.
 * User: dadi
 * Date: 2021/4/9
 * Time: 2:44 PM
 */

namespace Drupal\nnd_component\Plugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\views\Views;

class BlockContentFormBase extends ContentFormBase {

  /**
   * @var \Drupal\Core\Entity\ContentEntityBase
   */
  private $entity;

  /**
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  private $fieldManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->fieldManager = \Drupal::service('entity_field.manager');
  }

  /**
   * {@inheritdoc}
   */
  public function formAlter(&$form, FormStateInterface $form_state) {
    $this->entity = $form_state->getFormObject()->getEntity();
    $this->alterViewReferenceElement($form, $form_state, $this->entity);
    // TODO: Implement buildForm() method.
    if (isset($form['icon']) && isset($form['title_style'])) {
      $form['icon']['#states'] = [
        'visible' => ['select[name="title_style"]' => ['value' => 'style-v2']],
      ];
      $form['icon']['widget'][0]['value']['#states'] = [
        'required' => ['select[name="title_style"]' => ['value' => 'style-v2']],
      ];
    }
  }


  /**
   * Visual Settings views argument.
   * @param $form
   * @param FormStateInterface $form_state
   * @param string $parent_field
   * @param int $index
   * @param ContentEntityBase $entity
   */
  private function alterViewReferenceElement(&$form, FormStateInterface $form_state, $entity, $parent_field = '', $index = 0) {

    foreach ($form as $field => &$form_item) {
      if (strpos($field, "#") === 0 || $form_item['#type'] != 'container') {
        continue;
      }
      $type = $form_item['#attributes']['class'][0];

      switch ($type) {
        case 'field--type-viewsreference':
          //Alter views.
          foreach ($form_item['widget'] as $key => &$widget) {
            if (!is_numeric($key) || !isset($form_item['widget'][$key]['options']['argument'])) {
              continue;
            }
            if ($parent_field) {
              $this->alterSubFormViewItemArgument($form, $form_state, $field, $key, $parent_field, $index, $entity);
            } else {
              $this->alterViewItemArgument($form, $form_state, $field, $key);
            }
          }
          break;
        case  'field--type-entity-reference-revisions':
          $referenced = $entity ? $entity->get($field)->referencedEntities() : null;
          foreach ($form_item['widget'] as $key => &$widget) {
            if (!is_numeric($key)) {
              continue;
            }
            $sub_form = $widget['subform'];
            $this->alterViewReferenceElement($sub_form, $form_state, isset($referenced[$key]) ? $referenced[$key] : null, $field, $key);
            $widget['subform'] = $sub_form;
          }
          break;
      }
    }
  }

  /**
   * Alter view item argument element.
   * @param $form
   * @param FormStateInterface $form_state
   * @param $field
   * @param $index
   */
  private function alterViewItemArgument(&$form, FormStateInterface $form_state, $field, $index) {
    $form[$field]['widget'][$index]['options']['argument']['#prefix'] = '<div class="hidden">';
    $form[$field]['widget'][$index]['options']['argument']['#suffix'] = '</div>';
    $form[$field]['widget'][$index]['options']['#states']['visible'][] = [
      ':input[name="' . $field . '[' . $index . '][display_id]"]' => ['!value' => ''],
    ];

    $form[$field]['widget'][$index]['display_id']['#ajax'] = [
      'wrapper' => "wrapper-{$field}-{$index}-view-item-custom-argument",
      'callback' => '_nnd_component_views_display_change_callback',
      'effect' => 'none',
      'event' => 'change',
    ];
    $did = '';
    if ($form_state->isRebuilding()) {
      $vid = $form_state->getValue([$field, $index, 'target_id', 0, 'target_id']);
      $did = $form_state->getValue([$field, $index, 'display_id']);
      $default_value = [];
    } else {
      if (!$this->entity->get($field)->isEmpty() && isset($this->entity->get($field)->getValue()[$index])) {
        $val = $this->entity->get($field)->getValue();
        $vid = $val[$index] ? $val[$index]['target_id'] : '';
        $did = $val[$index] ? $val[$index]['display_id'] : '';
        $default_value = $val[$index] && isset(unserialize($val[$index]['data'])['argument']) ? explode('/', unserialize($val[$index]['data'])['argument']) : [];
      }
    }
    $field_set = [
      '#type' => 'fieldgroup',
      '#prefix' => '<div id="wrapper-' . $field . '-' . $index . '-view-item-custom-argument">',
      '#suffix' => '</div>',
      '#weight' => $form[$field]['widget'][$index]['options']['argument']['#weight'],
    ];
    if ($did) {
      $view = Views::getView($vid);
      $filters = $view->getDisplay()->getOption('filters');
      $arguments = $view->getDisplay()->getOption('arguments');

      $view->setDisplay($did);
      if (!empty($view->getDisplay()->getOption('arguments'))) {
        $arguments = $view->getDisplay()->getOption('arguments');
      }
      if (!empty($view->getDisplay()->getOption('filters'))) {
        $filters = $view->getDisplay()->getOption('filters');
      }
      $entity_type = isset($filters['type']['entity_type']) ? $filters['type']['entity_type'] : '';
      $bundles = isset($filters['type']['value']) ? $filters['type']['value'] : [];

      if (!empty($entity_type) && !empty($bundles)) {
        $i = 0;
        foreach ($arguments as $key => $argument) {

          if ($argument['table'] == 'node_field_data' && isset($argument['entity_field'])) {
            $field_name = $argument['entity_field'];
          } elseif ($argument['plugin_id'] == 'taxonomy_index_tid_depth') {
            $field_name = 'category';
          } else {
            $field_name = explode("__", $argument['table'])[1];
          }
          if ($field_element = $this->getArgumentField($entity_type
            , $bundles
            , $field_name
            , isset($default_value[$i]) ? $default_value[$i] : ''
            , $argument['break_phrase'])
          ) {
            $field_set[$field_name] = $field_element;
          }
          $i++;
        }
        $form[$field]['widget'][$index]['options']['#open'] = TRUE;
      }
    }
    $form[$field]['widget'][$index]['options']['custom_argument'] = $field_set;
  }

  /**
   * Return filed element.
   * @param $entity_type
   * @param $bundles
   * @param $field
   * @param $default_value
   * @param $multiple
   * @return array
   */
  private function getArgumentField($entity_type, $bundles, $field, $default_value, $multiple) {
    $field_settings = ['#type' => '', '#handler' => []];

    foreach ($bundles as $bundle => $val) {
      $definitions = $this->fieldManager->getFieldDefinitions($entity_type, $bundle);
      if (isset($definitions[$field])) {
        $field_definition = $definitions[$field];
        $field_settings['#title'] = $field_definition->label();

        switch ($field_definition->getType()) {
          case 'entity_reference':
            switch ($field_definition->getSetting('handler')) {
              case 'default:taxonomy_term':
                $field_settings['#type'] = 'select';
                $field_settings['#handler'] += array_keys($field_definition->getSetting('handler_settings')['target_bundles']);
                break;
            }
            break;
          case 'boolean':
            $field_settings['#type'] = 'checkbox';
            break;
        }
      }
    }
    switch ($field_settings['#type']) {
      case 'select':
        return [
          '#type' => 'select',
          '#title' => $field_settings['#title'],
          '#options' => $this->getTerms($field_settings['#handler']),
          '#default_value' => explode("+", $default_value),
          '#multiple' => $multiple,
          '#ajax' => [
            'callback' => '_nnd_component_views_argument_change_callback',
            'progress' => 'none',
            'event' => 'change',
          ],
        ];
        break;
      case 'checkbox':
        return [
          '#type' => 'checkbox',
          '#title' => $field_settings['#title'],
          '#default_value' => $default_value,
          '#ajax' => [
            'callback' => '_nnd_component_views_argument_change_callback',
            'progress' => 'none',
            'event' => 'change',
          ],
        ];
        break;
      default:
        return [];
    }
  }

  /**
   * Return terms by tid.
   * @param $tid
   * @return mixed
   */
  private function getTerms($tid) {
    $options = [];
    $options['all'] = t('All');
    $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    foreach ($tid as $id) {
      $terms = $storage->loadTree($id);
      foreach ($terms as $term) {
        $options[$term->tid] = str_pad("", $term->depth, "-") . $term->name;
      }
    }
    return $options;
  }

  /**
   * Alter sub form view item argument element.
   * @param $form
   * @param FormStateInterface $form_state
   * @param $field
   * @param $index
   * @param $parent
   * @param $p_index
   * @param $entity
   */
  private function alterSubFormViewItemArgument(&$form, FormStateInterface $form_state, $field, $index, $parent, $p_index, $entity) {
    $form[$field]['widget'][$index]['options']['argument']['#prefix'] = '<div class="hidden">';
    $form[$field]['widget'][$index]['options']['argument']['#suffix'] = '</div>';
    $form[$field]['widget'][$index]['options']['#states']['visible'][] = [
      ':input[name="' . $parent . '[' . $p_index . '][subform][' . $field . '][' . $index . '][display_id]"]' => ['!value' => ''],
    ];

    $form[$field]['widget'][$index]['display_id']['#ajax'] = [
      'wrapper' => "wrapper-{$parent}-{$p_index}-{$field}-{$index}-view-item-custom-argument",
      'callback' => '_nnd_component_views_display_change_callback',
      'effect' => 'none',
      'event' => 'change',
    ];

    if ($form_state->isRebuilding()) {
      $default_value = [];
      $vid = $form[$field]['widget'][$index]['target_id']['#default_value'][0];
      $did = $form[$field]['widget'][$index]['display_id']['#default_value'];
      if ($argument = $form[$field]['widget'][$index]['options']['argument']['#default_value']) {
        $default_value = explode("/", $argument);
      }
      if ($value = $form_state->getValue([$parent, $p_index, 'subform', $field, $index, 'target_id', 0, 'target_id'])) {
        $vid = $value;
      }
      if ($value = $form_state->getValue([$parent, $p_index, 'subform', $field, $index, 'display_id'])) {
        $did = $value;
      }
    } else {
      $vid = $form[$field]['widget'][$index]['target_id']['#default_value'] ? $form[$field]['widget'][$index]['target_id']['#default_value'][0] : '';
      $did = $form[$field]['widget'][$index]['display_id']['#default_value'];
      if ($entity && !$entity->get($field)->isEmpty() && isset($entity->get($field)->getValue()[$index])) {
        $val = $entity->get($field)->getValue();
        $vid = $val[$index]['target_id'];
        $did = $val[$index]['display_id'];
        $default_value = isset(unserialize($val[$index]['data'])['argument']) ? explode('/', unserialize($val[$index]['data'])['argument']) : [];
      }
    }
    $field_set = [
      '#type' => 'fieldgroup',
      '#prefix' => "<div id='wrapper-{$parent}-{$p_index}-{$field}-{$index}-view-item-custom-argument'>",
      '#suffix' => '</div>',
      '#weight' => $form[$field]['widget'][$index]['options']['argument']['#weight'],
    ];

    if ($did) {
      $view = Views::getView($vid);
      $filters = $view->getDisplay()->getOption('filters');
      $arguments = $view->getDisplay()->getOption('arguments');

      $view->setDisplay($did);
      if (!empty($view->getDisplay()->getOption('arguments'))) {
        $arguments = $view->getDisplay()->getOption('arguments');
      }
      if (!empty($view->getDisplay()->getOption('filters'))) {
        $filters = $view->getDisplay()->getOption('filters');
      }
      $entity_type = isset($filters['type']['entity_type']) ? $filters['type']['entity_type'] : '';
      $bundles = isset($filters['type']['value']) ? $filters['type']['value'] : [];

      if (!empty($entity_type) && !empty($bundles)) {
        $i = 0;
        foreach ($arguments as $key => $argument) {

          if ($argument['table'] == 'node_field_data' && isset($argument['entity_field'])) {
            $field_name = $argument['entity_field'];
          } elseif ($argument['plugin_id'] == 'taxonomy_index_tid_depth') {
            $field_name = 'category';
          } else {
            $field_name = explode("__", $argument['table'])[1];
          }
          if ($field_element = $this->getArgumentField($entity_type
            , $bundles
            , $field_name
            , isset($default_value[$i]) ? $default_value[$i] : ''
            , $argument['break_phrase'])
          ) {
            $field_set[$field_name] = $field_element;
          }
          $i++;
        }
        $form[$field]['widget'][$index]['options']['#open'] = TRUE;
      }
    }
    $form[$field]['widget'][$index]['options']['custom_argument'] = $field_set;
  }

}