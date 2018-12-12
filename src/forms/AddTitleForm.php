<?php
namespace Drupal\gws_workshops\forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * GWS Add Title for Workshop Form
 */

class AddTitleForm extends FormBase{

    /**
    * {@inheritdoc}
    */
    public function getFormId(){
        return 'gws_workshops_title_add';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $params=NULL){

        // disable caching
        //$form['#cache']['max-age'] = 0;
        //disables html 5 validation and hides error messages until user hits submit
        //$form['#attributes']['novalidate'] = 'novalidate';
        
        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Title'),
            '#required' => TRUE,
        ];

        $form['description'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Description'),
            '#required' => TRUE, 
        ];
        
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
          ];

        //attach library to array
        //$form['#attached']['library'][] = 'aleks_sso_form/aleks_sso_form_library';
        return $form;
    }

    /**
    * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {

        //check each field for proper input

  }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state){

        // grab form input and put titles in DB
        $connection = \Drupal::database();
        $connection->insert('gws_workshop_title')->fields(
          array(
            'title' => $form_state->getValue('title'),
            'description' => $form_state->getValue('description'),
          )
        )->execute();
        
        $form_state->setRedirect('gws_workshops.view-workshops');
    }
}