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

        //get registrants info from db and display in table
        $allRegQuery = db_select('gws_workshop_registrants', 'reg');
        $regData = $allRegQuery
        ->fields('reg', array('id', 'fname','lname','phone', 'email', 'address', 'city', 'state', 'workshopTitle', 'comments'))
        ->orderBy('workshopTitle', 'DESC')
        ->execute()->fetchAll();


        $header = ['First Name', 'Last Name','Phone', 'Email', 'Address', 'City', 'State', 'Workshop', 'Comments'];
        $rows = [];

        //loop through each DB row and assign to value and output in a table row
        foreach ($regData as $record){
            $fname = $record->fname;
            $lname = $record->lname;
            $phone = $record->phone;
            $email = $record->email;
            $address = $record->address;
            $city = $record->city;
            $state = $record->state;
            $workshopId = $record->workshopTitle;
            $comments = $record->comments;

            //find workshop title and return its title
            //$workshopTitle = $this->findWorkshopTitle($workshopId);

            //build each row as it is taken from the database
            $rows[] = [
                    $fname, $lname, $phone, $email, $address, $city, $state, $this->findWorkshopTitle($workshopId), $comments
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
      /**
    * {@inheritdoc}
    */
      /**
    * {@inheritdoc}
    */
    private function findWorkshopTitle($testId){

        //get workshop info from db
        $allWorkshopsQuery = db_select('gws_workshop', 'ws');
        $workshopData = $allWorkshopsQuery
        ->fields('ws', array('id', 'workshopTitle','startDate','startTime', 'endTime'))
        ->orderBy('id', 'DESC')
        ->execute()->fetchAll();

        foreach ($workshopData as $record){
            if($testId == $record->id){
                return $record->workshopTitle; 
            }
        }
    }
}
