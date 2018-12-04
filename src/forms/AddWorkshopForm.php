<?php
namespace Drupal\gws_workshops\forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * GWS add Workshop Form
 */

class AddWorkshopForm extends FormBase{

    /**
    * {@inheritdoc}
    */
    public function getFormId(){
        return 'gws_workshops_add';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $params=NULL){

        // disable caching
        //$form['#cache']['max-age'] = 0;
        //disables html 5 validation and hides error messages until user hits submit
        //$form['#attributes']['novalidate'] = 'novalidate';
        
        $form['workshopTitle'] = [
            '#type' => 'select',
            '#title' => t('Workshop Title'),
            '#options' => [
              '' => 'Select Workshop',
              'workshop1' => 'title1',
              'workshop2' => 'title2',
              'workshop3' => 'title3',
            ],
            '#required' => TRUE,
        ];
        
        $form['startDate'] = [
            '#type' => 'date',
            '#title' => $this->t('Start Date'),
            '#required' => TRUE,
        ];

        $form['startTime'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Start Time'),
            '#required' => TRUE,
        ];

        $form['endTime'] = [
            '#type' => 'textfield',
            '#title' => $this->t('End Time'),
            '#required' => TRUE,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#prefix' => '<div class="form-group">',
            '#suffix' => '</div>',
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

        // grab form input and put workshops in DB
        $connection = \Drupal::database();
        $connection->insert('gws_workshop')->fields(
          array(
            'workshopTitle' => $form_state->getValue('workshopTitle'),
            'startDate' => $form_state->getValue('startDate'),
            'startTime' => $form_state->getValue('startTime'),
            'endTime' => $form_state->getValue('endTime'),
          )
        )->execute();

        $form_state->setRedirect('gws_workshops.view');
    }
}