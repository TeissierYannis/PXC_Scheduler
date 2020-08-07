<?php


namespace App\Calendar;


class Calendar{

    private $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    private $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    private $currentDay, $currentMonth, $currentYear = null;

    public function __construct(){

        $this->currentDay = date('d', time());
    }

    /**
     * Not realy a view creation, but return all the necessary to create easly a calendar
     * @param null $month
     * @param null $year
     * @return array
     */
    public function createView($month = null, $year = null){

        // Définir le mois et l'année
        if($month == null && $year == null){
            $this->currentMonth = date('m', time());
            $this->currentYear = date('Y', time());
        }else{
            $this->currentMonth = $month;
            $this->currentYear = $year;
        }

        return [
            'daysInMonths' => $this->getDaysInMonth($this->currentMonth, $this->currentYear),
            'days' => $this->getdays(),
            'months' => $this->getMonths(),
            'current_day' => $this->currentDay
        ];
    }

    private function getdays(){
        return $this->days;
    }

    private function getMonths(){
        return $this->months;
    }


    /**
     * Get number of days in month
     * @param $month
     * @param $year
     * @return int
     */
    private function getDaysInMonth($month, $year){
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }



}