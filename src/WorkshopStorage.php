<?php
//todo make this work
namespace Drupal\employee;

/**
 * DAO class for employee table.
 */
class workshopStorage {
    /**
   * called from edit workshop to grab the url data
   *
   * @param array $fields
   *   An array conating the workshop data in key value pair.
   */
    public static function add(array $fields) {
        return \Drupal::database()->insert('gws-workshops')->fields($fields)->execute();
    } 

    public static function getAllWorkshops(array $fields){
        $allWorkshopsQuery = db_select('gws_workshop', 'ws');
        $workshopData = $allWorkshopsQuery
        ->fields('ws', array('id', 'workshopTitle','startDate','startTime', 'endTime'))
        ->orderBy('id', 'DESC')
        ->execute()->fetchAll();

        return $workshopData;
    }

}

