<?php
namespace Drupal\gws_workshops\forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Drupal;
use Drupal\Core\Form\FormInterface;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Url;
use Drupal\gws_workshops\workshopStorage;


/**
 * GWS Edit a workshop Form
 */

class EditWorkshopForm extends Formbase{

    /**
    * {@inheritdoc}
    */
    public function getFormId(){
        return 'gws_workshops_edit';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $workshop=NULL){

        // disable caching
        //$form['#cache']['max-age'] = 0;
        //disables html 5 validation and hides error messages until user hits submit
        //$form['#attributes']['novalidate'] = 'novalidate';

        //check to see if workshop exists, if not, redirect to view all page
        if ($workshop) {
            if ($workshop == 'invalid') {
                drupal_set_message(t('Invalid workshop record'), 'error');
                return new RedirectResponse(Drupal::url('gws_workshops.view-workshops'));
            }
        }
         $form['workshopTitle'] = [
                '#type' => 'textfield',
                '#title' => $this->t('Workshop Title'),
                '#required' => TRUE,
                '#value' => $workshop->workshopTitle,
            ];

            $form['startDate'] = [
                '#type' => 'date',
                '#title' => $this->t('Start Date'),
                '#required' => TRUE,
                '#value' => $workshop->startDate,
            ];

            $form['startTime'] = [
                '#type' => 'textfield',
                '#title' => $this->t('Start Time'),
                '#required' => TRUE,
                '#value' => $workshop->startTime,
            ];

            $form['endTime'] = [
                '#type' => 'textfield',
                '#title' => $this->t('End Time'),
                '#required' => TRUE,
                '#default_value' => ($workshop) ? $workshop->endTime : '',
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