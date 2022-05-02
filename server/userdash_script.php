<?php

/**
 * REST API for managing agents, users, rooms and chats in CLE Video Chat
 *
 * @author  CLE <info@new-dev.com>
 *
 * @since 1.0
 *
 */
session_start();
include_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(405);
}

function checkHeaders() {
    global $apiSecret, $apiHashMethod;
    if ($apiSecret && $apiHashMethod && (!isset($_SESSION["username"]) || !$_SESSION["username"]) ) {
        if (isset(getallheaders()['Authorization'])) {
            $hmac = hash_hmac($apiHashMethod, json_encode($_POST, JSON_UNESCAPED_UNICODE), $apiSecret);
            if ($hmac === getallheaders()['Authorization']) {
                return true;
            }
        }
        http_response_code(401);
        exit;
    }
    return true;
}


function uploads($filename, $room_id, $email, $upload_type) {
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ?');
        $stmt->execute([$email]);
        $agent = $stmt->fetch();
        
        if (isset($agent))  {
            $agentid = $agent['agent_id'];
        }
       
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (isset($user)) {
            $userid = $user['user_id'];
        }

        // insert data in lsv_file_uploads table --vj  
        $sql = "INSERT INTO " . $dbPrefix . "file_uploads (filename, agent_id, user_id, room_id, email, date_created, upload_type) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$filename, $agentid, $userid, $room_id, $email, date('Y-m-d H:i:s'), $upload_type]);

         return 200;
       
    } catch (Exception $e) {
        return false;
    }
}
/**
 * Method to check login of an user. Returns 200 code for successful login.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $username
 * @param type $pass
 * @return boolean|int
 */
//modify function by vj
function checkLogin($username, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND password = ? AND is_blocked = 0');
        $stmt->execute([$username, md5($pass)]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION["tenant"] = 'user';
            $_SESSION["username"] = $user['username'];
            $_SESSION["agent"] = array('user_id' => $user['user_id'], 'first_name' => $user['first_name'], 'last_name' => $user['last_name'], 'tenant' => 'user', 'email' => $user['username']);
            return true;
        } else {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND password = ?');
            $stmt->execute([$username, md5($pass)]);
            $user = $stmt->fetch();
            if($user['is_blocked'] == 1){ return 'blocked'; };
            return false;
        }
    } catch (Exception $e) {
        return $e->getMessage();
        //return false;
    }
}

function getQuizCategoryWise($room= false){
    global $dbPrefix, $pdo;
    try {
      
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId =? AND is_active = 1');
        $stmt->execute([$room]);
        $room = $stmt->fetch();
        
        // $additional = '';
        // $array = [];
        // if ($agentId && $agentId != 'false') {
        //     $additional = ' WHERE agent_id = ? ';
        //     $array = [$agentId];
        // }
        // if(!empty($room['quiz_categories'])){
            $categories = $room['quiz_categories'];
        //---- groupwise in array categoriesformat solution ----
            // $categories = json_decode($categories);
            // $categories = implode('","' , $categories);
            // $categories = '"'.$categories.'"';
            // var_dump($categories);
            // $stmt = $pdo->prepare('SELECT category, COUNT(category) as question_count FROM ' . $dbPrefix . 'quiz WHERE category IN ('.$categories.') GROUP BY category ORDER BY id DESC');
            $stmt = $pdo->prepare('SELECT category, COUNT(category) as question_count FROM ' . $dbPrefix . 'quiz WHERE category = "'.$categories.'" GROUP BY category ORDER BY id DESC');
            $stmt->execute();
            $rows = array();
        // }    
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

//visitor metting stop function for logs tracking
function quizaccessupdate($email, $quiz_total_score, $quiz_your_score, $category, $roomId) {
    // var_dump($quiz_total_score);
    global $dbPrefix, $pdo;
    try {
        // var_dump($category);
         //user data
         $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND is_blocked = 0');
         $stmt->execute([$email]);
         $user = $stmt->fetch();
         $user_id = $user['user_id'];

         
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id =?  AND is_eligible = 1 AND roomId = ? AND total_score = 0 ORDER BY id DESC LIMIT 1' );
        $stmt->execute([$user_id, $roomId]);
        $vquizrep = $stmt->fetch();
    //    var_dump($vquizrep);
    //    die;
        $score = $quiz_your_score ;
        $total_score = $quiz_total_score;
       
        // update data in visitorquiz_report table  --vj  
        if($vquizrep){
            $sql1 = "UPDATE " . $dbPrefix . 'visitorquiz_report SET score=?, total_score=?, quiz_category=? WHERE id =?';
            $stmt = $pdo->prepare($sql1)->execute([$score, $total_score,  $category, $vquizrep['id']]);

           
            $stmt = $pdo->prepare('SELECT lsv_users.credits,lsv_visitorquiz_report.*, lsv_category.minimum_score, lsv_rooms.credits_for_room 
            FROM ' . $dbPrefix . 'visitorquiz_report
            LEFT JOIN lsv_users ON lsv_users.user_id = lsv_visitorquiz_report.user_id
            LEFT JOIN lsv_category ON lsv_category.name = lsv_visitorquiz_report.quiz_category
            LEFT JOIN lsv_rooms ON lsv_rooms.roomId = lsv_visitorquiz_report.roomId
            WHERE lsv_visitorquiz_report.user_id =?  AND is_eligible = 1 AND lsv_visitorquiz_report.roomId = ? 
            ORDER BY lsv_visitorquiz_report.id DESC LIMIT 1' );
            $stmt->execute([$user_id, $roomId]);
            $vquizrep = $stmt->fetch();

            $user_update = $vquizrep['score'] > $vquizrep['minimum_score']? true:false;
           
            if($user_update == true){
                $total_credits = $vquizrep['credits'] + $vquizrep['credits_for_room'];
               
                $sql1 = "UPDATE " . $dbPrefix . 'users SET credits=?  WHERE user_id =? ';
                // var_dump($sql1);
                $stmt = $pdo->prepare($sql1)->execute([$total_credits, $vquizrep['user_id']]);
            }
            
        }
         return json_encode($vquizrep);
       
    } catch (Exception $e) {
        return false;
    }
}

function getEssayQuiz($category)
{
    global $dbPrefix, $pdo;
    try {
        // $additional = '';
        // $array = [];
        // if ($agentId && $agentId != 'false') {
        //     $additional = ' WHERE agent_id = ? ';
        //     $array = [$agentId];
        // }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz WHERE category="' . $category . '" AND status=1 ORDER BY quiz_essay_id DESC');
        $stmt->execute();
        $rows = array();

        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function addEssayQuizAnswer($category, $data, $agent_id=null, $user_id=null)
{
    global $dbPrefix, $pdo;
    try {
        // $additional = '';
        // $array = [];
        // if ($agentId && $agentId != 'false') {
        //     $additional = ' WHERE agent_id = ? ';
        //     $array = [$agentId];
        // }
        $data=json_decode($data, true);
        $data = $data['formasd'];
        var_dump($data);
        die;
        foreach($data as $key => $value){
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz_answer WHERE quiz_essay_id='.$key.'  ORDER BY id DESC');
            $result = $stmt->execute();
            $result = $stmt->fetch();
            // var_dump($result);
            if($result === false){
              
                $sql = "INSERT INTO " . $dbPrefix . "essay_type_quiz_answer (quiz_essay_id, quiz_answer, user_id)"
                    . "VALUES (?, ?, ?)";
                $pdo->prepare($sql)->execute([$key, $value, $user_id]);
            }else{
                $sql1 = "UPDATE " . $dbPrefix . 'essay_type_quiz_answer SET quiz_answer=? WHERE quiz_essay_id=? AND user_id=?';
                $pdo->prepare($sql1)->execute([$value, $key]);    
            }
        } 
        
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz_answer ORDER BY id DESC');
        $stmt->execute();
        // var_dump($stmt->fetch());
        // die;
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}  

function getEssayTypeQuizUsersAnswer($user_id)
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare(
            // 'SELECT"quiz_essay_id,category,question_name,question_text, default_marks, gernal_feedback,agent_id, quiz_answer, manual_marks, is_deleted, timestamp" 
            'SELECT *
            FROM ' . $dbPrefix . 'essay_type_quiz_answer 
            LEFT JOIN lsv_essay_type_quiz ON lsv_essay_type_quiz.quiz_essay_id=lsv_essay_type_quiz_answer.quiz_essay_id
            LEFT JOIN lsv_users ON lsv_users.user_id = lsv_essay_type_quiz_answer.user_id 
            WHERE lsv_essay_type_quiz_answer.user_id = '.$user_id.'
            ORDER BY id DESC');
        $stmt->execute();
        $rows = array();

        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
/**
 * Method to check the token in a broadcasting session.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $token
 * @param type $roomId
 * @param type $isAdmin
 * @return boolean
 */
function checkLoginToken($token, $roomId, $isAdmin = false) {
    global $dbPrefix, $pdo;
    try {
        if ($isAdmin) {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE token = ? AND roomId = ?');
            $stmt->execute([$token, $roomId]);
        } else {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE token = ? AND roomId = ? AND is_blocked = 0');
            $stmt->execute([$token, $roomId]);
        }

        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Method to add a room. 
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agent
 * @param type $visitor
 * @param type $agenturl
 * @param type $visitorurl
 * @param type $pass
 * @param type $session
 * @param type $datetime
 * @param type $duration
 * @param type $shortagenturl
 * @param type $shortvisitorurl
 * @param type $agentId
 * @param type $agenturl_broadcast
 * @param type $visitorurl_broadcast
 * @param type $shortagenturl_broadcast
 * @param type $shortvisitorurl_broadcast
 * @param type $is_active
 * @return string|int
 */
function insertScheduling($agent, $visitor, $agenturl, $visitorurl, $pass, $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId = null, $agenturl_broadcast = null, $visitorurl_broadcast = null, $shortagenturl_broadcast = null, $shortvisitorurl_broadcast = null, $is_active = true) {
    global $dbPrefix, $pdo;

    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? or shortagenturl = ? or shortvisitorurl = ?');
    $stmt->execute([$session, $shortagenturl, $shortvisitorurl]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }
    $is_active = ($is_active == 'true') ? 1 : 0;

    try {
        $sql = "INSERT INTO " . $dbPrefix . "rooms (agent, visitor, agenturl, visitorurl, password, roomId, datetime, duration, shortagenturl, shortvisitorurl, agent_id, agenturl_broadcast, visitorurl_broadcast, shortagenturl_broadcast, shortvisitorurl_broadcast, is_active) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$agent, $visitor, $agenturl, $visitorurl, md5($pass), $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId, $agenturl_broadcast, $visitorurl_broadcast, $shortagenturl_broadcast, $shortvisitorurl_broadcast, (int) $is_active]);
        return 200;
    } catch (Exception $e) {
        return 'Error';
    }
}

/**
 * Add a room and generate URLs from PHP directly. 
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $lsRepUrl
 * @param type $agentId
 * @param type $roomId
 * @param type $agentName
 * @param type $visitorName
 * @param type $agentShortUrl
 * @param type $visitorShortUrl
 * @param type $password
 * @param type $config
 * @param type $dateTime
 * @param type $duration
 * @param type $disableVideo
 * @param type $disableAudio
 * @param type $disableScreenShare
 * @param type $disableWhiteboard
 * @param type $disableTransfer
 * @param type $is_active
 * @return boolean|string|int
 */
function addRoom($lsRepUrl, $agentId = null, $roomId = null, $agentName = null, $visitorName = null, $agentShortUrl = null, $visitorShortUrl = null, $password = null, $config = 'config.json', $dateTime = null, $duration = null, $disableVideo = false, $disableAudio = false, $disableScreenShare = false, $disableWhiteboard = false, $disableTransfer = false, $is_active = true) {
    global $dbPrefix, $pdo;

    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? or shortagenturl = ? or shortvisitorurl = ?');
    $stmt->execute([$roomId, $agentShortUrl, $visitorShortUrl]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }
    $is_active = ($is_active == 'true') ? 1 : 0;

    try {

        function generateRand($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        $roomId = ($roomId) ? $roomId : generateRand(10);
        $str = [];
        $str['lsRepUrl'] = $lsRepUrl;

        if ($agentName) {
            $str['names'] = $agentName;
        }
        if ($visitorName) {
            $str['visitorName'] = $visitorName;
        }
        if ($config) {
            $str['config'] = $config;
        }
        if ($agentId) {
            $str['agentId'] = $agentId;
        }
        if ($agentId) {
            $str['agentId'] = $agentId;
        }


        if ($agentShortUrl) {
            $agentShortUrl = $agentShortUrl;
            $agentShortUrl_b = $agentShortUrl . '_b';
        } else {
            $agentShortUrl = generateRand(6);
            $agentShortUrl_b = generateRand(6);
        }
        if ($visitorShortUrl) {
            $visitorShortUrl = $visitorShortUrl;
            $visitorShortUrl_b = $visitorShortUrl . '_b';
        } else {
            $visitorShortUrl = generateRand(6);
            $visitorShortUrl_b = generateRand(6);
        }
        if ($dateTime) {
            $str['agentId'] = $dateTime;
        }
        if ($duration) {
            $str['duration'] = $duration;
        }
        if ($disableVideo) {
            $str['disableVideo'] = $disableVideo;
        }
        if ($disableAudio) {
            $str['disableAudio'] = $disableAudio;
        }
        if ($disableWhiteboard) {
            $str['disableWhiteboard'] = $disableWhiteboard;
        }
        if ($disableScreenShare) {
            $str['disableScreenShare'] = $disableScreenShare;
        }
        if ($disableTransfer) {
            $str['disableTransfer'] = $disableTransfer;
        }
        $encodedString = base64_encode(json_encode($str));


        $visitorUrl = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString;
        $viewerBroadcastLink = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString . '&broadcast=1';

        if ($password) {
            $str['pass'] = $password;
        }
        if (isset($str['vistorName'])) {
            unset($str['vistorName']);
        }
        $str['isAdmin'] = 1;
        $encodedString = base64_encode(json_encode($str));
        $agentUrl = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString . '&isAdmin=1';
        $agentBroadcastUrl = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString . '&isAdmin=1&broadcast=1';


        $sql = "INSERT INTO " . $dbPrefix . "rooms (agent, visitor, agenturl, visitorurl, password, roomId, datetime, duration, shortagenturl, shortvisitorurl, agent_id, agenturl_broadcast, visitorurl_broadcast, shortagenturl_broadcast, shortvisitorurl_broadcast, is_active) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$agentName, $visitorName, $agentUrl, $visitorUrl, md5($password), $roomId, $dateTime, $duration, $agentShortUrl, $visitorShortUrl, $agentId, $agentBroadcastUrl, $viewerBroadcastLink, $agentShortUrl_b, $visitorShortUrl_b, (int) $is_active]);
        return 200;
    } catch (Exception $e) {
        return 'Error';
    }
}

/**
 * Method to edit a room.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agent
 * @param type $visitor
 * @param type $agenturl
 * @param type $visitorurl
 * @param type $pass
 * @param type $session
 * @param type $datetime
 * @param type $duration
 * @param type $shortagenturl
 * @param type $shortvisitorurl
 * @param type $agentId
 * @param type $agenturl_broadcast
 * @param type $visitorurl_broadcast
 * @param type $shortagenturl_broadcast
 * @param type $shortvisitorurl_broadcast
 * @param type $is_active
 * @return int
 */
function editRoom($roomId, $agent, $visitor, $agenturl, $visitorurl, $pass, $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId = null, $agenturl_broadcast = null, $visitorurl_broadcast = null, $shortagenturl_broadcast = null, $shortvisitorurl_broadcast = null, $is_active = 1) {
    global $dbPrefix, $pdo;
    try {
        $is_active = ($is_active == 'true') ? 1 : 0;
        $sql = "UPDATE " . $dbPrefix . "rooms set agent=?, visitor=?, agenturl=?, visitorurl=?, password=?, roomId=?, datetime=?, duration=?, shortagenturl=?, shortvisitorurl=?, agent_id=?, agenturl_broadcast=?, visitorurl_broadcast=?, shortagenturl_broadcast=?, shortvisitorurl_broadcast=?, is_active=?"
                . " WHERE room_id = ?;";
        $pdo->prepare($sql)->execute([$agent, $visitor, $agenturl, $visitorurl, md5($pass), $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId, $agenturl_broadcast, $visitorurl_broadcast, $shortagenturl_broadcast, $shortvisitorurl_broadcast, (int) $is_active, $roomId]);
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}

/**
 * Update room state
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $is_active
 * @return int
 */
function updateRoomState($roomId, $is_active) {
    global $dbPrefix, $pdo;
    try {
        $is_active = ($is_active == 'true') ? 1 : 0;
        $sql = "UPDATE " . $dbPrefix . "rooms set is_active=?"
                . " WHERE room_id = ?;";
        $pdo->prepare($sql)->execute([(int) $is_active, $roomId]);
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}

/**
 * Method to add a recording after video session ends.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $file
 * @param type $agentId
 * @return int
 */
function insertRecording($roomId, $file, $agentId) {
    global $dbPrefix, $pdo;
    try {

        $sql = "INSERT INTO " . $dbPrefix . "recordings (`room_id`, `filename`, `agent_id`, `date_created`) "
                . "VALUES (?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$roomId, $file, $agentId, date("Y-m-d H:i:s")]);
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}

function videoTracking($video_id, $video_duration, $previous_time, $current_time, $username, $agent=Null)
{
    global $dbPrefix, $pdo;
    try {
        //time differnce for previous and current time --------
        $a = new DateTime($current_time);
        $b = new DateTime($previous_time);
        $interval1 = $a->diff($b);

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'video_tracking where username = ? AND video_id = ? AND timestamp LIKE "%'.date('Y-m-d').'%" ');
        $stmt->execute([$username, $video_id]);
        $result = $stmt->fetch(); 
        var_dump($result['username']);
        if($result['username'] == false){
        $sql = "INSERT INTO " . $dbPrefix . "video_tracking (video_id, video_duration, video_watched_time, username, agent)"
            . "VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$video_id, $video_duration, $current_time, $username, $agent]);
    }else{
        //time format for watched time--------
        $time = $result['video_watched_time'];
        $time2 = $interval1->format("%H:%I:%S");
        $secs = strtotime($time2)-strtotime("00:00:00");
        $total_watched_time = date("H:i:s",strtotime($time)+$secs);

        $sql = 'UPDATE ' . $dbPrefix . 'video_tracking SET video_watched_time=? WHERE username = ? AND video_id = ?  AND timestamp LIKE "%'.date('Y-m-d').'%"';
        $pdo->prepare($sql)->execute([$total_watched_time, $username, $video_id]);
    }
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}


/**
 * Method to delete a recording from the database and delete the file.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $recordingId
 * @return boolean
 */
function deleteRecording($recordingId) {
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'recordings WHERE recording_id = ?');
        $stmt->execute([$recordingId]);
        $rec = $stmt->fetch();

        if ($rec) {
            unlink('../server/recordings/' . $rec['filename']);
        }

        $array = [$recordingId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'recordings WHERE recording_id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function deleteUploads($uploadsId) {
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'file_uploads WHERE id = ?');
        $stmt->execute([$uploadsId]);
        $rec = $stmt->fetch();

        if ($rec) {
            unlink('../server/uploads/' . $rec['filename']);
        }

        $array = [$uploadsId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'file_uploads WHERE id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}


/**
 * Returns all recordings.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @return type
 */
function getRecordings() {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'recordings order by date_created desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('recordings/' . $r['filename'])) {
                    $rows[] = $r;
                }
                if (file_exists('recordings/' . $r['filename'] . '.mp4')) {
                    $r['filename'] = $r['filename'] . '.mp4';
                    $rows[] = $r;
                }
            }
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getVideosTrackingByroom() {
    global $dbPrefix, $pdo;
    try {
        $username =  $_SESSION["username"];
        $stmt = $pdo->prepare('SELECT *, SEC_TO_TIME( SUM( TIME_TO_SEC(lsv_video_tracking.video_watched_time) ) ) as total_watched_time, lsv_video_tracking.username as username FROM ' . $dbPrefix . 'video_tracking 
        INNER JOIN lsv_recordings ON lsv_video_tracking.video_id=lsv_recordings.recording_id
        LEFT JOIN lsv_rooms ON lsv_rooms.roomId = lsv_recordings.room_id
        WHERE ' . $dbPrefix . 'video_tracking.username = "'.$username.'"
        GROUP BY lsv_recordings.room_id
        ORDER BY timestamp desc');
        // var_dump( $stmt);
        // die;
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('recordings/' . $r['filename'])) {
                    $rows[] = $r;
                }
                if (file_exists('recordings/' . $r['filename'] . '.mp4')) {
                    $r['filename'] = $r['filename'] . '.mp4';
                    $rows[] = $r;
                }
            }
        }
        // $rows[] = $stmt->fetch();
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getVideosTrackingByvideo($video_id) {
    global $dbPrefix, $pdo;
    try {
        
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'video_tracking 
        INNER JOIN lsv_recordings ON lsv_video_tracking.video_id=lsv_recordings.recording_id
        WHERE ' . $dbPrefix . 'video_tracking.video_id = "'.$video_id.'"
        
        ORDER BY timestamp desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('recordings/' . $r['filename'])) {
                    $rows[] = $r;
                }
                if (file_exists('recordings/' . $r['filename'] . '.mp4')) {
                    $r['filename'] = $r['filename'] . '.mp4';
                    $rows[] = $r;
                }
            }
        }
        // $rows[] = $stmt->fetch();
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getVideosTracking($video_id, $username,$room_id) {
    global $dbPrefix, $pdo;
    try {
        $username = $_SESSION["username"];
        $stmt = $pdo->prepare('SELECT *, SEC_TO_TIME( SUM( TIME_TO_SEC(lsv_video_tracking.video_watched_time) ) ) as total_watched_time FROM ' . $dbPrefix . 'video_tracking 
        INNER JOIN lsv_recordings ON lsv_video_tracking.video_id=lsv_recordings.recording_id
        INNER JOIN lsv_rooms ON lsv_rooms.roomId=lsv_recordings.room_id
        WHERE  lsv_video_tracking.username =  "'.$username.'" AND  lsv_recordings.room_id ="'.$room_id.'" 
        GROUP BY lsv_video_tracking.video_id
        ORDER BY timestamp desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('recordings/' . $r['filename'])) {
                    $rows[] = $r;
                }
                if (file_exists('recordings/' . $r['filename'] . '.mp4')) {
                    $r['filename'] = $r['filename'] . '.mp4';
                    $rows[] = $r;
                }
            }
        }
        // $rows[] = $stmt->fetch();
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

// agent uploads
function getUpload($username) {
    
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE tenant IN ("admin", "agent", "teacher")');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        
        $ids = [];
        foreach($rows as $data){
            $ids[] = $data['agent_id'];
        }
 
        if ($user) {
            $user_id = $user['user_id'];
        }
        
        $categories = implode('","' , $ids);
        $ids = '"'.$categories.'"';
        $stmt = $pdo->prepare('SELECT *,lsv_users.name,lsv_agents.first_name FROM ' . $dbPrefix . 'file_uploads
            LEFT JOIN lsv_users On lsv_users.user_id = lsv_file_uploads.user_id 
            LEFT JOIN lsv_agents On lsv_agents.agent_id = lsv_file_uploads.agent_id 
            where lsv_file_uploads.user_id ='.$user_id.' or 
            lsv_file_uploads.agent_id IN ('.$ids.') order by date_created desc');
        
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('uploads/' . $r['filename'])) {
                    $rows[] = $r;
                }
                
            }
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}


/**
 * Adds a chat message.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $message
 * @param type $agent
 * @param type $from
 * @param type $participants
 * @param type $agentId
 * @param type $system
 * @param type $avatar
 * @param type $datetime
 * @return string|int
 */
function insertChat($roomId, $message, $agent, $from, $participants, $agentId = null, $system = null, $avatar = null, $datetime = null) {
    global $dbPrefix, $pdo;
    try {

        $sql = "INSERT INTO " . $dbPrefix . "chats (`room_id`, `message`, `agent`, `agent_id`, `from`, `date_created`, `participants`, `system`, `avatar`) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$roomId, $message, $agent, $agentId, $from, date("Y-m-d H:i:s", strtotime($datetime)), $participants, $system, $avatar]);
        return 200;
    } catch (Exception $e) {
        return 'Error';
    }
}

/**
 * Return chat messages by roomId and participants.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $sessionId
 * @param type $agentId
 * @return boolean
 */
function getChat($roomId, $sessionId, $agentId = null) {

    global $dbPrefix, $pdo;
    try {

        $additional = '';
        $array = [$roomId, "%$sessionId%"];
        if ($agentId && $agentId != 'false') {
            $additional = ' AND agent_id = ?';
            $array = [$roomId, $agentId, "%$sessionId%"];
        }
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "chats WHERE (`room_id`= ? or `room_id` = 'dashboard') $additional and participants like ? order by date_created asc");
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $r['date_created'] = strtotime($r['date_created']);
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all information about agent by tenant
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $tenant
 * @return boolean
 */
function getAgent($tenant) {

    global $dbPrefix, $pdo;
    try {
        $array = [$tenant];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "agents WHERE `tenant`= ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns agent info by agent_id.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $id
 * @return boolean|type
 */
function getAdmin($id) {

    global $dbPrefix, $pdo;
    try {
        $array = [$id];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "agents WHERE `agent_id`= ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns user info by user_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $id
 * @return boolean|type
 */
function getUser($id) {

    global $dbPrefix, $pdo;
    try {
        $array = [$id];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "users WHERE `user_id`= ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns room information by room identifier
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @return boolean|type
 */
function getRoom($roomId) {

    global $dbPrefix, $pdo;
    try {
        $array = [$roomId];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "rooms WHERE `roomId`= ? AND `is_active` = 1");
        $stmt->execute($array);
        $room = $stmt->fetch();
        if ($room) {
            return json_encode($room);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns room information by room_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @return boolean|type
 */
function getRoomById($roomId) {

    global $dbPrefix, $pdo;
    try {
        $array = [$roomId];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "rooms WHERE `room_id`= ? AND `is_active` = 1");
        $stmt->execute($array);
        $room = $stmt->fetch();
        if ($room) {
            return json_encode($room);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all rooms by agent_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @return boolean|type
 */
function getRooms($agentId = false, $currentdatetime) {

    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            // $additional = ' WHERE agent_id = ? ';
            
            $additional = ' WHERE datetime < ? ';
            $array = [$currentdatetime];
        }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms ' . $additional . ' order by datetime desc');
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

function getSubRooms($agentId = false, $currentdatetime) {

    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            // $additional = ' WHERE agent_id = ? ';
            
            $additional = '  WHERE parent_id > ?';
            $array = [0];
        }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms ' . $additional . ' order by datetime desc');
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

function getRoomsForQuiz($agentId = false)
{

    global $dbPrefix, $pdo;
    try {
        
        $additional = '';
        $array = [];
        // if ($agentId && $agentId != 'false') {
        //     $additional = ' WHERE agent_id = ? ';
        //     $array = [$agentId];
        // }
        $userid =  $_SESSION["agent"]["user_id"];
        // $userid =  4;
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "visitorquiz_report WHERE user_id ='$userid' AND `is_eligible` = 1 ");
        
        $stmt->execute($array);
        // $room = $stmt->fetch();   
        while ($r = $stmt->fetch()) {
            $rows[] = $r['roomId'];
        }
        $rooms = implode('","' , $rows);
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms ' . $additional . ' WHERE roomId IN ("'.$rooms.'") order by roomId desc');
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}
/**
 * Deletes a room by room_id and agent_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agentId
 * @return boolean
 */
function deleteRoom($roomId, $agentId = false) {
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [$roomId];
        if ($agentId && $agentId != 'false') {
            $additional = ' AND agent_id = ?';
            $array = [$roomId, $agentId];
        }
        $sql = 'DELETE FROM ' . $dbPrefix . 'rooms WHERE room_id = ?' . $additional;
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all agents
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @return boolean|type
 */
function getAgents() {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents order by agent_id desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Deletes an agent
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @return boolean
 */
function deleteAgent($agentId) {
    global $dbPrefix, $pdo;
    try {

        $sql = 'DELETE FROM ' . $dbPrefix . 'agents WHERE agent_id = ?';
        $pdo->prepare($sql)->execute([$agentId]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates an agent
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @param type $firstName
 * @param type $lastName
 * @param type $email
 * @param type $tenant
 * @param type $pass
 * @param type $usernamehidden
 * @return boolean
 */
function editAgent($agentId, $firstName, $lastName, $email, $tenant, $pass = null, $usernamehidden = null) {
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ? and agent_id <> ?');
        $stmt->execute([$email, $agentId]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }

        $array = [$firstName, $lastName, $email, $tenant, $agentId];
        $additional = '';
        if ($pass) {
            $additional = ', password = ?';
            $array = [$firstName, $lastName, $email, $tenant, md5($pass), $agentId];
        }

        $sql = 'UPDATE ' . $dbPrefix . 'agents SET first_name=?, last_name=?, email=?, tenant=? ' . $additional . ' WHERE agent_id = ?';

        if ($_SESSION["username"] == $usernamehidden) {
            $_SESSION["agent"] = array('agent_id' => $agentId, 'first_name' => $firstName, 'last_name' => $lastName, 'tenant' => $tenant, 'email' => $email);
        }

        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Method to end the meeting and set it inactive.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agentId
 * @return boolean
 */
function endMeeting($roomId, $agentId = null) {
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [$roomId];
        if ($agentId) {
            $additional = ' AND agent_id = ?';
            $array = [$roomId, $agentId];
        }
//        $sql = 'UPDATE ' . $dbPrefix . 'rooms SET is_active=0 WHERE roomId = ? ' . $additional;
//        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Blocks an user by username. 
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $username
 * @return boolean
 */
function blockUser($username) {
    global $dbPrefix, $pdo;
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'users SET is_blocked=1 WHERE username = ?';

        $pdo->prepare($sql)->execute(array($username));
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates an admin agent.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @param type $firstName
 * @param type $lastName
 * @param type $email
 * @param type $tenant
 * @param type $pass
 * @return boolean
 */
function editAdmin($agentId, $firstName, $lastName, $email, $tenant, $pass = null) {
    global $dbPrefix, $pdo;
    try {
        
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ? and agent_id <> ?');
        $stmt->execute([$email, $agentId]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }
        
        $array = [$firstName, $lastName, $email, $tenant, $agentId];
        $additional = '';
        if ($pass) {
            $additional = ', password = ?';
            $array = [$firstName, $lastName, $email, $tenant, md5($pass), $agentId];
        }

        $sql = 'UPDATE ' . $dbPrefix . 'agents SET first_name=?, last_name=?, email=?, tenant=? ' . $additional . ' WHERE agent_id = ?';
        $_SESSION["agent"] = array('agent_id' => $agentId, 'first_name' => $firstName, 'last_name' => $lastName, 'tenant' => $tenant, 'email' => $email);
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds an agent.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $user
 * @param type $pass
 * @param type $firstName
 * @param type $lastName
 * @param type $email
 * @param type $tenant
 * @return boolean
 */
function addAgent($user, $pass, $firstName, $lastName, $email, $tenant) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ? or email = ?');
        $stmt->execute([$user, $email]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }

        $sql = 'INSERT INTO ' . $dbPrefix . 'agents (username, password, first_name, last_name, email, tenant) VALUES (?, ?, ?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([$user, md5($pass), $firstName, $lastName, $email, $tenant]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds a feedback.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $sessionId
 * @param type $roomId
 * @param type $rate
 * @param type $text
 * @param type $userId
 * @return boolean
 */
function addFeedback($sessionId, $roomId, $rate, $text = '', $userId = '') {
    global $dbPrefix, $pdo;
    try {

        $sql = 'INSERT INTO ' . $dbPrefix . 'feedbacks (session_id, room_id, rate, text, user_id, date_added) VALUES (?, ?, ?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([$sessionId, $roomId, $rate, $text, $userId, date("Y-m-d H:i:s")]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all users.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $userId
 * @return boolean
 */
function getUsers() {

    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE user_id ='. $_SESSION["agent"]["user_id"].' order by user_id desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        //return $e->getMessage()
        return false;
    }
}

function getUsersHours() {

    global $dbPrefix, $pdo;
    try {
        
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users_logged WHERE username ="'.$_SESSION["username"].'" ORDER BY `lsv_users_logged`.`date_created` ASC');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

function getvisitorquizindex() {

    global $dbPrefix, $pdo;
    try {
        
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id ="'.$_SESSION["agent"]["user_id"].'" ORDER BY `timestamp` ASC');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}
/**
 * Deletes an user by user_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $userId
 * @return boolean
 */
function deleteUser($userId) {
    global $dbPrefix, $pdo;
    try {
        $sql = 'DELETE FROM ' . $dbPrefix . 'users WHERE user_id = ?';
        $pdo->prepare($sql)->execute([$userId]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates an user by user_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $sessionUserId
 * @param type $userId
 * @param type $name
 * @param type $user
 * @param type $pass
 * @param type $blocked
 * @return boolean
 */
function editUser($userId, $name, $user, $pass, $blocked) {
    global $dbPrefix, $pdo;
    $additional = '';
    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? and user_id <> ?');
    $stmt->execute([$user, $userId]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }

    $array = [$user, $name, $blocked, $userId];
    if ($pass) {
        $additional = ', password = ?';
        $array = [$user, $name, $blocked, md5($pass), $userId];
    }
    try {
        if($_SESSION["agent"]["user_id"] == $userId){
        $sql = 'UPDATE ' . $dbPrefix . 'users SET username=?, name=?, is_blocked=? ' . $additional . ' WHERE user_id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * Updates a configuration file properties.
 * 
 * @param type $postData
 * @param type $file
 * @return boolean
 */
function updateConfig($postData, $file) {

    try {

        $jsonString = file_get_contents('../config/' . $file . '.json');
        $data = json_decode($jsonString, true);

        foreach ($postData as $key => $value) {
            $val = explode('.', $key);
            if (isset($val[1]) && $value == 'true') {
                $data[$val[0]][$val[1]] = true;
            } else if (isset($val[1]) && $value == 'false') {
                $data[$val[0]][$val[1]] = false;
            } else if (isset($val[1]) && $value) {
                $data[$val[0]][$val[1]] = $value;
            } else if (isset($val[1])) {
                unset($data[$val[0]][$val[1]]);
            } else {
                $data[$key] = $value;
            }
        }
        $newJsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents('../config/' . $file . '.json', $newJsonString);


        $currentVersion = file_get_contents('../pages/version.txt');
        $curNumber = explode('.', $currentVersion);
        if (count($curNumber) == 3) {
            $currentVersion = $currentVersion . '.1';
        } else {
            $currentVersion = $curNumber[0] . '.' . $curNumber[1] . '.' . $curNumber[2] . '.' . ((int) $curNumber[3] + 1);
        }
        file_put_contents('../pages/version.txt', $currentVersion);


        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Add a configuration file
 * 
 * @param type $fileName
 * @return boolean
 */
function addConfig($fileName) {

    try {

        $jsonString = file_get_contents('../config/config.json');
        file_put_contents('../config/' . $fileName . '.json', $jsonString);

        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates a locale file.
 * 
 * @param type $postData
 * @param type $file
 * @return boolean
 */
function updateLocale($postData, $file) {

    try {

        $jsonString = file_get_contents('../locales/' . $file . '.json');
        $data = json_decode($jsonString, true);

        foreach ($postData as $key => $value) {
            $val = explode('.', $key);
            if (isset($val[1]) && $value == 'true') {
                $data[$val[0]][$val[1]] = true;
            } else if (isset($val[1]) && $value == 'false') {
                $data[$val[0]][$val[1]] = false;
            } else if (isset($val[1]) && $value) {
                $data[$val[0]][$val[1]] = $value;
            } else if (isset($val[1])) {
                unset($data[$val[0]][$val[1]]);
            } else {
                $data[$key] = $value;
            }
        }
        $newJsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents('../locales/' . $file . '.json', $newJsonString);


        $currentVersion = file_get_contents('../pages/version.txt');
        $curNumber = explode('.', $currentVersion);
        if (count($curNumber) == 3) {
            $currentVersion = $currentVersion . '.1';
        } else {
            $currentVersion = $curNumber[0] . '.' . $curNumber[1] . '.' . $curNumber[2] . '.' . ((int) $curNumber[3] + 1);
        }
        file_put_contents('../pages/version.txt', $currentVersion);


        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds a locale file.
 * 
 * @param type $fileName
 * @return boolean
 */
function addLocale($fileName) {

    try {

        $jsonString = file_get_contents('../locales/en_US.json');
        file_put_contents('../locales/' . $fileName . '.json', $jsonString);

        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds an user.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $user
 * @param type $name
 * @param type $pass
 * @param type $firstName
 * @param type $lastName
 * @return boolean
 */
function addUser($user, $name, $pass, $firstName = null, $lastName = null) {

    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?');
        $stmt->execute([$user]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }


        $sql = 'INSERT INTO ' . $dbPrefix . 'users (username, name, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([$user, $name, md5($pass), $firstName, $lastName]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Login method for an agent.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $username
 * @param type $pass
 * @return boolean
 */
function loginAgent($username, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ? AND password=?');
        $stmt->execute([$username, md5($pass)]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION["tenant"] = ($user['is_master']) ? 'lsv_mastertenant' : $user['tenant'];
            $_SESSION["username"] = $user['username'];
            $_SESSION["agent"] = array('agent_id' => $user['agent_id'], 'first_name' => $user['first_name'], 'last_name' => $user['last_name'], 'tenant' => $user['tenant'], 'email' => $user['email']);
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Login method for admin agent
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $email
 * @param type $pass
 * @return boolean|int
 */
function loginAdmin($email, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ? AND password = ?');
        $stmt->execute([$email, md5($pass)]);
        $user = $stmt->fetch();

        if ($user) {
            return 200;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all the chats for agent_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @return type
 */
function getChats($agentId = false) {
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            $additional = ' WHERE agent_id = ? ';
            $array = [$agentId];
        }
        $stmt = $pdo->prepare('SELECT max(room_id) as room_id, max(date_created) as date_created, max(agent) as agent FROM ' . $dbPrefix . 'chats ' . $additional . ' group by room_id order by date_created desc');
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $stmt1 = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'chats where room_id=? order by date_created asc ');
            $stmt1->execute([$r['room_id']]);
            $rows1 = '<table>';
            while ($r1 = $stmt1->fetch()) {
                $rows1 .= '<tr><td><small>' . $r1['date_created'] . '</small></td><td>' . $r1['from'] . ': ' . $r1['message'] . '</td></tr>';
            }
            $rows1 .= '</table>';
            $r['messages'] = $rows1;
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getPassphrase($agentId = false) {
    global $pasPhrase;
    return $pasPhrase;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayTypeQuizUsersAnswer') {
        echo getEssayTypeQuizUsersAnswer($_POST['user_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getVideosTrackingByvideo') {
        echo getVideosTrackingByvideo($_POST['video_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'videoTracking') {
        echo videoTracking($_POST['video_id'], $_POST['video_duration'], $_POST['previous_time'], $_POST['current_time'], $_POST['user']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getVideosTrackingByroom') {
        echo getVideosTrackingByroom();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getRoomsForQuiz') {
        echo getRoomsForQuiz(@$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addEssayQuizAnswer') {
        echo addEssayQuizAnswer($_POST['category'], $_POST['data'], $_POST['agent_id'], $_POST['user_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayQuiz') {
        echo getEssayQuiz($_POST['category']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getQuizCategoryWise') {
        echo getQuizCategoryWise($_POST['room']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getVideosTracking') {
        echo getVideosTracking($_POST['video_id'], $_POST['username'], $_POST['room']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getSubRooms') {
        echo getSubRooms(@$_POST['agentId'], @$_POST['currentdatetime']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'uploads') {
        echo uploads($_POST['filename'], $_POST['room_id'], $_POST['email'], $_POST['upload_type']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getvisitorquizindex') {
        echo getvisitorquizindex();
    }
    // visitor quiz access methods
    if (isset($_POST['type']) && $_POST['type'] == 'quizaccessupdate') {
        echo quizaccessupdate($_POST['username'], $_POST['quiz_total_score'], $_POST['quiz_your_score'], $_POST['category'],$_POST['roomId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getUpload') {
        echo getUpload($_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'login') {
        echo checkLogin($_POST['email'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'logintoken') {
        echo checkLoginToken($_POST['token'], $_POST['roomId'], @$_POST['isAdmin']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'scheduling') {
        echo insertScheduling($_POST['agent'], $_POST['visitor'], $_POST['agenturl'], $_POST['visitorurl'], $_POST['password'], $_POST['session'], $_POST['datetime'], $_POST['duration'], $_POST['shortAgentUrl'], $_POST['shortVisitorUrl'], $_POST['agentId'], @$_POST['agenturl_broadcast'], @$_POST['visitorurl_broadcast'], @$_POST['shortAgentUrl_broadcast'], @$_POST['shortVisitorUrl_broadcast'], @$_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addroom') {
        echo addRoom($_POST['lsRepUrl'], @$_POST['agentId'], @$_POST['roomId'], @$_POST['agentName'], @$_POST['visitorName'], @$_POST['agentShortUrl'], @$_POST['visitorShortUrl'], @$_POST['password'], @$_POST['config'], @$_POST['dateTime'], @$_POST['duration'], @$_POST['disableVideo'], @$_POST['disableAudio'], @$_POST['disableScreenShare'], @$_POST['disableWhiteboard'], @$_POST['disableTransfer'], @$_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editroom') {
        echo editRoom($_POST['room_id'], $_POST['agent'], $_POST['visitor'], $_POST['agenturl'], $_POST['visitorurl'], $_POST['password'], $_POST['session'], $_POST['datetime'], $_POST['duration'], $_POST['shortAgentUrl'], $_POST['shortVisitorUrl'], $_POST['agentId'], @$_POST['agenturl_broadcast'], @$_POST['visitorurl_broadcast'], @$_POST['shortAgentUrl_broadcast'], @$_POST['shortVisitorUrl_broadcast'], @$_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'changeroomstate') {
        echo updateRoomState($_POST['room_id'], $_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addchat') {
        echo insertChat($_POST['roomId'], $_POST['message'], $_POST['agent'], $_POST['from'], $_POST['participants'], @$_POST['agentId'], @$_POST['system'], @$_POST['avatar'], @$_POST['datetime']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getchat') {
        echo getChat($_POST['roomId'], $_POST['sessionId'], @$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getrooms') {
        echo getRooms(@$_POST['agentId'], @$_POST['currentdatetime']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getchats') {
        echo getChats(@$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteroom') {
        echo deleteRoom($_POST['roomId'], $_POST['agentId']);
    }

    if (isset($_POST['type']) && $_POST['type'] == 'getagents') {
        echo getAgents();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteagent') {
        echo deleteAgent($_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editagent') {
        echo editAgent($_POST['agentId'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['tenant'], $_POST['password'], @$_POST['usernamehidden']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editadmin') {
        echo editAdmin($_POST['agentId'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['tenant'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'loginagent') {
        echo loginAgent($_POST['username'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'loginadmin') {
        echo loginAdmin($_POST['email'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addagent') {
        echo addAgent($_POST['username'], $_POST['password'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['tenant']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addrecording') {
        echo insertRecording($_POST['roomId'], $_POST['filename'], $_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getrecordings') {
        echo getRecordings();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleterecording') {
        echo deleteRecording($_POST['recordingId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getusers') {
        echo getUsers();
    }

    if (isset($_POST['type']) && $_POST['type'] == 'usershours') {
        echo getUsersHours();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteuser') {
        echo deleteUser($_POST['userId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'edituser') {
        echo editUser($_POST['userId'], $_POST['name'], $_POST['username'], @$_POST['password'], @$_POST['isBlocked']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'adduser') {
        echo addUser($_POST['username'], $_POST['name'], $_POST['password'], @$_POST['firstName'], @$_POST['lastName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'updateconfig') {
        echo updateConfig($_POST['data'], $_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addconfig') {
        echo addConfig($_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'updatelocale') {
        echo updateLocale($_POST['data'], $_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addlocale') {
        echo addLocale($_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getagent') {
        echo getAgent($_POST['tenant']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getuser') {
        echo getUser($_POST['id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getadmin') {
        echo getAdmin($_POST['id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'blockuser') {
        echo blockUser($_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'feedback') {
        echo addFeedback($_POST['sessionId'], $_POST['roomId'], $_POST['rate'], @$_POST['text'], @$_POST['userId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getroom') {
        echo getRoom($_POST['roomId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getroombyid') {
        echo getRoomById($_POST['room_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'endmeeting') {
        echo endMeeting($_POST['roomId'], @$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getpassphrase') {
        echo getPassphrase();
    }
}
