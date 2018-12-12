<?php
namespace Drupal\gws_workshops\forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * GWS add Workshop Form
 */

class RegisterForWorkshop extends FormBase{

    /**
    * {@inheritdoc}
    */
    public function getFormId(){
        return 'gws_workshops_register';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $params=NULL){

        // disable caching
        //$form['#cache']['max-age'] = 0;
        //disables html 5 validation and hides error messages until user hits submit
        //$form['#attributes']['novalidate'] = 'novalidate';

        // grab workshops from DB
        $allWorkshopsQuery = db_select('gws_workshop', 'ws');
        $workshopData = $allWorkshopsQuery
        ->fields('ws', array('id', 'workshopTitle','startDate','startTime', 'endTime'))
        ->orderBy('id', 'DESC')
        ->execute()->fetchAll();              
        
        $options = [];

        //loop through each record and assign to variable
        foreach ($workshopData as $record){
            $id = $record->id;
            $workshopId = $record->workshopTitle; 
            $startDate = $record->startDate;
            $startTime = $record->startTime;
            $endTime = $record->endTime;
            
            //format and add option to array for select list
            $options[] = ($this->findWorkshopTitle($workshopId)." on ".$startDate." at ".$startTime);
        }

        $form['fname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#required' => TRUE,
        ];

        $form['lname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#required' => TRUE,
        ];

        $form['phone'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Phone'),
            '#required' => TRUE,
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required' => TRUE,
        ];

        $form['address'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Address'),
            '#required' => TRUE,
        ];
    
        $form['city'] = [
            '#type' => 'textfield',
            '#title' => $this->t('City'),
            '#required' => TRUE,
        ];

        $form['state'] = [
            '#type' => 'textfield',
            '#title' => $this->t('State'),
            '#required' => TRUE,
        ];

        $form['workshopTitle'] = array(
            '#type' => 'select',
            '#title' => 'Workshop',
            '#options' => $options,
            '#required' => TRUE,
        );

        $form['comments'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Comments (Optional)'),
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

        // grab form input and put registrant in DB
        $connection = \Drupal::database();
        $connection->insert('gws_workshop_registrants')->fields(
          array(
            'fname' => $form_state->getValue('fname'),
            'lname' => $form_state->getValue('lname'),
            'phone' => $form_state->getValue('phone'),
            'email' => $form_state->getValue('email'),
            'address' => $form_state->getValue('address'),
            'city' => $form_state->getValue('city'),
            'state' => $form_state->getValue('state'),
            'workshopTitle' => $form_state->getValue('workshopTitle'),
            'comments' => $form_state->getValue('comments'),
          )
        )->execute();
        
        //location from routing.yml file
        $form_state->setRedirect('gws_workshops.view-registrants');
    }

    private function findWorkshopTitle($testId){

        //get workshop info from db
        $allTitleQuery = db_select('gws_workshop_title', 'ws');
        $titleData = $allTitleQuery
        ->fields('ws', array('id', 'title'))
        //->orderBy('id', 'DESC')
        ->execute()->fetchAll();

        foreach ($titleData as $record){
            if($testId == $record->id){
                return $record->title; 
            }
        }
    }
}