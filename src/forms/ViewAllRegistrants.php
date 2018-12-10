<?php
namespace Drupal\gws_workshops\forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * GWS View Workshop Form
 */

class ViewAllRegistrants extends FormBase{

    /**
    * {@inheritdoc}
    */
    public function getFormId(){
        return 'registrants_table_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $params=NULL){
        //attach library to array
        //$form['#attached']['library'][] = 'aleks_sso_form/aleks_sso_form_library';

        //get workshop info from db and display in table
        $allWorkshopsQuery = db_select('gws_workshop', 'ws');
        $workshopData = $allWorkshopsQuery
        ->fields('ws', array('id', 'workshopTitle','startDate','startTime', 'endTime'))
        ->orderBy('id', 'DESC')
        ->execute()->fetchAll();


        $header = ['Workshop Title', 'Start Date', 'Start Time', 'End Time', ' button '];
        $rows = [];

        //loop through each DB row and assign to value and output in a table row
        foreach ($workshopData as $record){
            $btn = '<html><button>'.$record->id.'</button></html>';
            $workshopTitle = $record->workshopTitle; 
            $startDate = $record->startDate;
            $startTime = $record->startTime;
            $endTime = $record->endTime;

            //build each row as it is taken from the database
            $rows[] = [
                    $workshopTitle, $startDate, $startTime, $endTime, $btn
                    //$form['edit'] = [ '#type' => 'textfield', '#value' => $this->t($id),]
                ];
        }

        $form[] = [
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
        ];
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

        header('Location: '.$url);
        exit();

    }


}

