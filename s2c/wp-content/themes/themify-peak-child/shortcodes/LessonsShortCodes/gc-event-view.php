<?php
use RRule\RRule;
use RRule\RSet;

// function to run when shortcode google_calendar_events is called
function display_google_calendar_events($atts)
{
    require_once 'vendor/autoload.php';
    // Include the RRule library

    //gets the calendar id to retrive the events
    $calendar_id = get_field('gc_id');

    if (empty($calendar_id)) {
        //return if wrong/empty
        return 'Calendar ID not found.';
    }
    $api_key = GOOGLE_API_KEY;
    $api_url = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events?key={$api_key}";
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return "Error fetching events.";
    }

    $body = wp_remote_retrieve_body($response);
    $events_data = json_decode($body, true);

    if (isset($events_data['items'])) {
        $eventList = $events_data['items'];
        //function to display teh events!
        if (count($eventList) == 0) {
            echo 'Δεν υπάρχουν ακόμα events';
            return;
        }
        return displayEvents($eventList);
    } else {
        return 'No events found.';
    }
}

function displayEvents($eventList)
{
    foreach ($eventList as $event) {
        // get the title of the event if exists else put No title
        $summary = isset($event['summary']) ? $event['summary'] : 'No title?';
        // get the description of the event if exists
        $description = isset($event['description']) ? ' - ' . $event['description'] : '';
        //declare start Date for the weeks counter 
        $startDate = null;
        // days of the week , ex. Monday
        $daysOfWeek = getDaysOfWeek($event);
        // variable to add Every or not if its repeated event or not (Firday/Every Friday)
        $every = '';
        // number of weeks for repeated events
        $numberOfWeeks = '';
        // what time the event takes place
        $time = '';

        //Google Calendar has event.start.dateTime for events with time and start.date for all day events

        if (isset($event['start']['dateTime'])) {
            //calculates the time based on event start/end time 
            $startDate = $event['start']['dateTime'];
            $startDateTime = new DateTime($event['start']['dateTime']);
            $endDateTime = new DateTime($event['end']['dateTime']);
            //formatTimeInGreek function to return greek πμ/μμ
            $time = formatTimeInGreek($startDateTime) . ' - ' . formatTimeInGreek($endDateTime) . ' ';
            //ex time = 09:00PM - 11:00PM
        } else {
            //else all day
            $startDate = $event['start']['date'];
            $time = '(Όλη μέρα) ';
        }
        // no check if is recurrence event or onetime
        if (isset($event['recurrence'])) {
            $rruleString = $event['recurrence'][0];
            // get number of weeks ( 5 Weeks , 1 Week , etc)
            $numberOfWeeks = getRRuleInfo($rruleString, $startDate);
            //add Every for recurrence
            //ex. Every Monday 
            $every = "Κάθε ";
        } else {
            $numberOfWeeks = 'On-Time';
        }
        // string that holds the full date 
        // ex.
        // 17 Junary for one time evetns
        // 30 January - 27 February for recurrence

        $fullEventDate = formatEventDate($event);
        // format the results
        echo ' 
        <a class="custom-event" href=' . $event['htmlLink'] . '>
            <div class="tb_text_wrap">
                <p style="text-align: left">
                    <span style="color: #ff0000">
                    ' . $summary . ': Άννα Μαντά  – Θεατρικό Εργαστήρι Υποκριτικής (' . $numberOfWeeks . ') - ' . $fullEventDate->seasonWithYear . ' <br />
                    </span>
                    <span style="color: #000000">
                    ' . $every . $daysOfWeek . ' ' . $time . $fullEventDate->formattedDate . '<br/>
                    </span>
                </p>
            </div>
        </a>';



    }

}
function formatTimeInGreek($time)
{
    $hour = $time->format('g');
    $minutes = $time->format('i');
    $period = $time->format('a');

    // Translate "am" and "pm" to Greek
    $period = ($period === 'am') ? 'π.μ.' : 'μ.μ.';

    if ($minutes === '00') {
        return "$hour$period";
    } else {
        return "$hour:$minutes$period";
    }
}

function formatEventDate($event)
{
    $start = new DateTime(isset($event['start']['dateTime']) ? $event['start']['dateTime'] : $event['start']['date']);
    $repeated = false;

    if (isset($event['recurrence'])) {
        $repeated = true;
        $recurrenceRule = $event['recurrence'][0];

        if (preg_match('/UNTIL=([0-9TZ]+)/', $recurrenceRule, $matches)) {
            $endDate = new DateTime($matches[1]);
        } else {
            if (preg_match('/COUNT=(\d+)/', $recurrenceRule, $matches)) {
                $count = intval($matches[1]);
                $endDate = clone $start;
                for ($i = 1; $i < $count; $i++) {
                    $endDate->add(new DateInterval('P1D'));
                }
            }
        }
    }
    //get the sesaon!
    $startMonth = $start->format('F');
    $seasonWithYear = getSeason($startMonth) . ' ' . $start->format('Y');

    if ($repeated) {
        $formattedStartDate = $start->format('j F');
        $formattedEndDate = $endDate->format('j F');
        $formattedDate = "$formattedStartDate - $formattedEndDate";
    } else {
        $formattedDate = $start->format('j F');
    }
    return (object) array(
        'formattedDate' => $formattedDate,
        'seasonWithYear' => $seasonWithYear
    );
}

function getSeason($startMonth)
{
    $startMonth = strtolower($startMonth);

    switch ($startMonth) {
        case 'december':
        case 'january':
        case 'february':
            $season = 'Χειμώνας';
            break;
        case 'march':
        case 'april':
        case 'may':
            $season = 'Άνοιξη';
            break;
        case 'june':
        case 'july':
        case 'august':
            $season = 'Καλοκαίρι';
            break;
        case 'september':
        case 'october':
        case 'november':
            $season = 'Φθινόπωρο';
            break;
        default:
            $season = 'Άγνωστο';
    }

    return "$season";
}

function formatRecurringEventDate($event)
{
    if (isset($event['start']['date']) && isset($event['recurrence'])) {
        $startDate = new DateTime($event['start']['date']);
    } else {
        $startDate = new DateTime($event['start']['dateTime']);
    }

    $firstOccurrence = null;
    $lastOccurrence = null;

    foreach ($event['recurrence'] as $recurrenceRule) {
        $rrule = new RRule($recurrenceRule);
        $occurrences = $rrule->getOccurrences();

        if (empty($occurrences)) {
            continue;
        }

        $firstOccurrence = $firstOccurrence ?? $occurrences[0];
        $lastOccurrence = end($occurrences);
    }

    if ($firstOccurrence && $lastOccurrence) {
        $startFormatted = $startDate->format('j F');
        $lastFormatted = $lastOccurrence->format('j F');

        return $startFormatted . ' - ' . $lastFormatted;
    }

    return 'Recurring Event Format Not Supported';
}

function getRRuleInfo($rruleString, $startDate)
{
    $rrule = new RRule($rruleString);
    if (strpos($rruleString, 'UNTIL=') !== false) {
        if (!$startDate) {
            return " - ";
        }
        $until = new DateTime();
        preg_match('/UNTIL=([0-9T:Z]+)/', $rruleString, $matches);
        if (isset($matches[1])) {
            $until = new DateTime($matches[1]);
        }

        $start = new DateTime($startDate);
        $interval = new DateInterval('P1W');
        $period = new DatePeriod($start, $interval, $until);
        $weekCount = iterator_count($period);
        $suffixes = "Εβδομάδες";
        if ($weekCount == 1)
            $suffixes = "Εβδομάδα";
        return " $weekCount $suffixes";
    } else {

        $recurrenceDates = $rrule->getOccurrences();
        $uniqueWeeks = count(array_unique(array_map(function ($date) {
            return $date->format('Y-W');
        }, $recurrenceDates)));
        $suffixes = "Εβδομάδες";
        if ($uniqueWeeks == 1)
            $suffixes = "Εβδομάδα";
        return "$uniqueWeeks $suffixes";
    }
}


function getDaysOfWeek($event)
{
    $dayTranslations = array(
        'Monday' => 'Δευτέρα',
        'Tuesday' => 'Τρίτη',
        'Wednesday' => 'Τετάρτη',
        'Thursday' => 'Πέμπτη',
        'Friday' => 'Παρασκευή',
        'Saturday' => 'Σάββατο',
        'Sunday' => 'Κυριακή'
    );

    $translatedDaysOfWeek = array();


    $daysOfWeek = [];
    $addedDays = [];

    if (isset($event['start']['dateTime'])) {
        $startDateTime = new DateTime($event['start']['dateTime']);
        $day = $startDateTime->format('l');
        $daysOfWeek[] = $day;
    } elseif (isset($event['start']['date'])) {
        if (isset($event['recurrence'])) {
            // Event is recurring
            foreach ($event['recurrence'] as $recurrenceRule) {
                $rrule = new RRule($recurrenceRule);
                $occurrences = $rrule->getOccurrences();

                foreach ($occurrences as $occurrence) {
                    $day = $occurrence->format('l');
                    if (!in_array($day, $addedDays)) {
                        $daysOfWeek[] = $day;
                        $addedDays[] = $day;
                    }
                }
            }
        } else {
            // Event is not recurring
            $day = (new DateTime($event['start']['date']))->format('l');
            $daysOfWeek[] = $day;
        }
    }



    // Now, $daysOfWeek will contain all occurrences of Monday and Tuesday as they appear in the RRULE.


    foreach ($daysOfWeek as $day) {
        $translatedDay = isset($dayTranslations[$day]) ? $dayTranslations[$day] : $day;
        $translatedDaysOfWeek[] = $translatedDay;
    }

    // Convert the translated days to a comma-separated string
    return implode(', ', $translatedDaysOfWeek);
}

add_shortcode('google_calendar_events', 'display_google_calendar_events');

?>