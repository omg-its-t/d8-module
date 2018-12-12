<?php
namespace Drupal\gws_workshops\forms;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Drupal\gws_workshops\WorkshopStorage;


/**
 * GWS View Registrants Form
 */

class ViewAllWorkshops extends FormBase{

    /**
    * {@inheritdoc}
    */
    public function getFormId(){
        return 'workshop_table_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state, $params=NULL){

        //get workshop info from db and display in table
        $allWorkshopsQuery = db_select('gws_workshop', 'ws');
        $workshopData = $allWorkshopsQuery
        ->fields('ws', array('id', 'workshopTitle','startDate','startTime', 'endTime'))
        ->orderBy('id', 'DESC')
        ->execute()->fetchAll();

        $header = ['Workshop Title', 'Description', 'Start Date', 'Start Time', 'End Time','Button'];
        $rows = [];

        //loop through each DB row and assign to value and output in a table row
        foreach ($workshopData as $record){
            $id = $record->id;
            $workshopId = $record->workshopTitle; 
            $startDate = $record->startDate;
            $startTime = $record->startTime;
            $endTime = $record->endTime;

            //build each row as it is taken from the database
            $rows[] = [
                $this->findWorkshopTitle($workshopId), $this->getWorkshopDesc($workshopId), $startDate, $startTime, $endTime, $id,
            ];
        }

        $form[] = [
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
        ];

        $form['submit'] =[
            '#type' => 'submit',
            '#title' =>'Delete',
            '#value' => $id,
            '#class' => 'delete',
        ];

        //attach library to array
        $form['#attached']['library'][] = 'gws_workshops/gws_workshops_library';
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

        $form_state->setRedirect('gws_workshops.edit-workshop');
    }
    private function findWorkshopTitle($testId){

        //get workshop info from db
        $allTitleQuery = db_select('gws_workshop_title', 'ws');
        $titleData = $allTitleQuery
        ->fields('ws', array('id', 'title'))
        ->execute()->fetchAll();

        foreach ($titleData as $record){
            if($testId == $record->id){
                return $record->title; 
            }
        }
    }

    private function getWorkshopDesc($testId){

        //get workshop info from db
        $allTitleQuery = db_select('gws_workshop_title', 'ws');
        $titleData = $allTitleQuery
        ->fields('ws', array('id','description'))
        ->execute()->fetchAll();

        foreach ($titleData as $record){
            if($testId == $record->id){
                return $desc = $record->description;
            }
        }

    }

}

