<?php 

namespace App\Data;


class StaticFunctions {
    

    public function retrievePerfData()
    {
        $formData = [];

        foreach ($_POST["performances"] as $key => $value) {
            $formData = [
                'last_name' => htmlspecialchars($_POST["performances"]['last_name']),
                'first_name' => htmlspecialchars($_POST["performances"]['first_name']),
                'phoneNumber' => htmlspecialchars($_POST["performances"]['phoneNumber']),
                'email' => htmlspecialchars($_POST["performances"]['email']),
                'event_type' => htmlspecialchars($_POST["performances"]["event_type"]),
                'location_name' => htmlspecialchars($_POST["performances"]["location_name"]),
                'event_date' => $_POST["performances"]["event_date"],
                'hosts_number' => htmlspecialchars($_POST["performances"]["hosts_number"]),
                'end_event_time' => $_POST["performances"]["end_event_time"],
                'celebration' => htmlspecialchars($_POST["performances"]["celebration"]),
                'cocktail_location' => htmlspecialchars($_POST["performances"]["cocktail_location"]),
                'diner_dancefloor_separated' => htmlspecialchars($_POST["performances"]["diner_dancefloor_separated"]),
                'close_distant_spaces' => htmlspecialchars($_POST["performances"]["close_distant_spaces"]),
                'perf_comment' => htmlspecialchars($_POST["performances"]["perf_comment"])
            ];
        }
        return $formData;
    }
}