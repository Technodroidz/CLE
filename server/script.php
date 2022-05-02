<?php

/**
 * REST API for managing agents, users, rooms and chats in LiveSmart Video Chat
 *
 * @author  LiveSmart <contact@livesmart.video>
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

function guid() {
    return bin2hex(openssl_random_pseudo_bytes(20));
}

function secondsToTime($inputSeconds) {
    $secondsInAMinute = 60;
    $secondsInAnHour = 60 * $secondsInAMinute;
    $secondsInADay = 24 * $secondsInAnHour;

    // Extract days
    $days = floor($inputSeconds / $secondsInADay);

    // Extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // Extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // Extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // Format and return
    $timeParts = [];
    $sections = [
        'day' => (int) $days,
        'hour' => (int) $hours,
        'minute' => (int) $minutes,
        'second' => (int) $seconds,
    ];

    foreach ($sections as $name => $value) {
        if ($value > 0) {
            $nm = $name . ($value == 1 ? '' : 's');
            $timeParts[] = $value . ' <span data-localize="' . $nm . '"></span> ';
        }
    }

    return implode(', ', $timeParts);
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
function checkLogin($username, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND password = ? AND is_blocked = 0');
        $stmt->execute([$username, md5($pass)]);
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
// function insertScheduling($agent, $visitor, $agenturl, $visitorurl, $pass, $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId = null, $agenturl_broadcast = null, $visitorurl_broadcast = null, $shortagenturl_broadcast = null, $shortvisitorurl_broadcast = null, $is_active = true) {
//     global $dbPrefix, $pdo;
//     checkHeaders();
//     $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? or shortagenturl = ? or shortvisitorurl = ?');
//     $stmt->execute([$session, $shortagenturl, $shortvisitorurl]);
//     $userName = $stmt->fetch();
//     if ($userName) {
//         return false;
//     }
//     $is_active = ($is_active == 'true') ? 1 : 0;

//     try {
//         $sql = "INSERT INTO " . $dbPrefix . "rooms (agent, visitor, agenturl, visitorurl, password, roomId, datetime, duration, shortagenturl, shortvisitorurl, agent_id, agenturl_broadcast, visitorurl_broadcast, shortagenturl_broadcast, shortvisitorurl_broadcast, is_active) "
//                 . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//         $pdo->prepare($sql)->execute([$agent, $visitor, $agenturl, $visitorurl, md5($pass), $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId, $agenturl_broadcast, $visitorurl_broadcast, $shortagenturl_broadcast, $shortvisitorurl_broadcast, (int) $is_active]);
//         return 200;
//     } catch (Exception $e) {
//         return 'Error';
//     }
// }

function insertScheduling($agent, $visitor, $agenturl, $visitorurl, $pass, $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId = null, $agenturl_broadcast = null, $visitorurl_broadcast = null, $shortagenturl_broadcast = null, $shortvisitorurl_broadcast = null, $is_active = true, $parent_id, $categories, $minimum_time_of_quiz, $minimum_time_for_video, $credits_for_room)
{
    global $dbPrefix, $pdo;
    // if($categories != "None"){$categories = json_encode($categories);}else{$categories ='None'; }
    // var_dump($categories);
    // die;
    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? or shortagenturl = ? or shortvisitorurl = ?');
    $stmt->execute([$session, $shortagenturl, $shortvisitorurl]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }
    $is_active = ($is_active == 'true') ? 1 : 0;

    try {
        $sql = "INSERT INTO " . $dbPrefix . "rooms (agent, visitor, agenturl, visitorurl, password, roomId, datetime, duration, shortagenturl, shortvisitorurl, agent_id, agenturl_broadcast, visitorurl_broadcast, shortagenturl_broadcast, shortvisitorurl_broadcast, is_active, parent_id, username, quiz_categories, minimum_time_of_quiz, minimum_time_for_video, credits_for_room) "
            . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$agent, $visitor, $agenturl, $visitorurl, md5($pass), $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId, $agenturl_broadcast, $visitorurl_broadcast, $shortagenturl_broadcast, $shortvisitorurl_broadcast, (int) $is_active, $parent_id, $_SESSION["username"], $categories, $minimum_time_of_quiz, $minimum_time_for_video, $credits_for_room]);
        return 200;
    } catch (Exception $e) {
        return $e->getMessage();
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
            $str['datetime'] = $dateTime;
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
        $id = $pdo->lastInsertId();
        return $id;
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
    checkHeaders();
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
    checkHeaders();
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
    checkHeaders();
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'recordings WHERE recording_id = ?');
        $stmt->execute([$recordingId]);
        $rec = $stmt->fetch();

        if ($rec) {
            @unlink('../server/recordings/' . $rec['filename']);
            @unlink('../server/recordings/' . $rec['filename'] . '.mp4');
        }

        $array = [$recordingId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'recordings WHERE recording_id = ?';
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
function getRecordings($draw, $start = 0, $offset = 10, $order = false, $search = false) {
    global $dbPrefix, $pdo;
    checkHeaders();
    try {
        $additional = '';
        $array = [];
        
        if ($search['value']) {
            if (!$additional) {
                $additional = ' WHERE';
            }
            $additional .= ' filename like "%' . $search['value'] . '%" OR room_id like "%' . $search['value'] . '%" OR agent_id like "%' . $search['value'] . '%" OR date_created like "%' . $search['value'] . '%" ';
        }
        $start = $start ?? 0;
        $offset = $offset ?? 10;
        $orderBy = array('filename', 'room_id', 'agent_id', 'date_created');
        $orderBySql = '';
        if (isset($order[0]['column'])) {
            $orderBySql = ' order by ' . $orderBy[$order[0]['column']] . ' ' . $order[0]['dir'] . ' ';
        }
        
        $total = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'recordings' . $additional);
        $total->execute($array);
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'recordings' . $additional . $orderBySql . ' LIMIT ' . $start . ',' . $offset);
        $stmt->execute($array);
        if ($draw) {
            $data['draw'] = $draw;
        }
        $data['recordsTotal'] = $total->rowCount();
        $data['recordsFiltered'] = $total->rowCount();
        
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('recordings/' . $r['filename'] . '.mp4')) {
                    $r['filename'] = $r['filename'] . '.mp4';
                }
                $rows[] = $r;
            }
        }
        $data['data'] = $rows;
        return json_encode($data);
        
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
 * Adds a log.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $message
 * @param type $agent
 * @param type $agentId
 * @param type $datetime
 * @param type $session
 * @param type $constraint
 * @param type $ua
 * @param type $attendee
 * @return string|int
 */
function insertLog($roomId, $message, $agent, $agentId = null, $datetime = null, $session = null, $constraint = null, $ua = null, $attendee = null) {
    global $dbPrefix, $pdo;
    
    try {

        $sql = "INSERT INTO " . $dbPrefix . "logs (`room_id`, `message`, `agent`, `agent_id`, `date_created`, `session`, `constraint`, `ua`, `attendee`) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$roomId, $message, $agent, $agentId, date("Y-m-d H:i:s", strtotime($datetime)), $session, $constraint, $ua, $attendee]);
        return 200;
    } catch (Exception $e) {
        return $e->getMessage();
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
 * Reset agent password
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $email
 * @param type $username
 * @return agent object
 */
function recoverPassword($email, $username) {

    global $dbPrefix, $pdo, $fromEmail;
    try {
        $array = [$email, $username];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "agents WHERE `email`= ? and `username` = ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {
            $to = $user['email'];
            $authToken = guid();
            $expired = date("Y-m-d H:i:s", strtotime("+30 minutes"));

            $compare = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $explodeUrl = explode('/server', $compare);
            $liveSmartURL = $explodeUrl[0];
            $array = [$expired, md5($authToken), $email, $username];
            $sql = 'UPDATE ' . $dbPrefix . 'agents SET date_expired=?, recovery_token=? WHERE email = ? and username = ?';
            $pdo->prepare($sql)->execute($array);

            $subject = 'Password Recovery from LiveSmart';
            $recoveryUrl = $liveSmartURL . '/dash/rec.php?code=' . $authToken;
            $message = 'Hello<br/><br/>In order to change your password, please follow this <a href="' . $recoveryUrl . '">link</a> or copy and paste it into your browser address bar: <br/> ' . $recoveryUrl . '<br/><br/>Sincerely, <br/>LiveSmart Team.';

            $header = "From: LiveSmart Team <" . $fromEmail . "> \r\n";
            $header .= "Reply-To: " . $fromEmail . " \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $retval = mail($to, $subject, $message, $header);

            if ($retval == true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Change agent password
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $token
 * @param type $password
 * @return agent object
 */
function resetPassword($token, $password) {

    global $dbPrefix, $pdo, $fromEmail;
    
    try {
        $array = [md5($token)];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "agents WHERE `recovery_token`= ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {

            $array = [md5($password), md5($token)];

            $sql = 'UPDATE ' . $dbPrefix . 'agents SET password=? WHERE recovery_token=? ';
            $pdo->prepare($sql)->execute($array);
            return true;
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
    checkHeaders();
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
    checkHeaders();
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
    checkHeaders();
    try {
        $array = [$roomId];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "rooms WHERE `room_id`= ?");
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
// function getRooms($draw, $agentId = false, $start = 0, $offset = 10, $order = false, $search = false) {

//     global $dbPrefix, $pdo;
//     checkHeaders();
//     try {
//         $additional = '';
//         $array = [];
//         if ($agentId && $agentId != 'false') {
//             $additional = ' WHERE agent_id = ? ';
//             $array = [$agentId];
//         }
        
//         if ($search['value']) {
//             if (!$additional) {
//                 $additional = ' WHERE';
//             }
//             $additional .= ' agent like "%' . $search['value'] . '%" OR shortvisitorurl like "%' . $search['value'] . '%" OR datetime  like "%' . $search['value'] . '%" OR roomId  like "%' . $search['value'] . '%" OR shortagenturl  like "%' . $search['value'] . '%" OR title like "%' . $search['value'] . '%"';
//         }
//         $start = $start ?? 0;
//         $offset = $offset ?? 10;
//         $orderBy = array('room_id', 'agent', 'visitor', 'shortagenturl', 'shortvisitorurl', 'datetime', 'is_active');
//         $orderBySql = '';
//         if (isset($order[0]['column'])) {
//             $orderBySql = ' order by ' . $orderBy[$order[0]['column']] . ' ' . $order[0]['dir'] . ' ';
//         }
        
//         $total = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms' . $additional);
//         $total->execute($array);
//         $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms ' . $additional . $orderBySql . ' LIMIT ' . $start . ',' . $offset);
//         $stmt->execute($array);
//         if ($draw) {
//             $data['draw'] = $draw;
//         }
//         $data['recordsTotal'] = $total->rowCount();
//         $data['recordsFiltered'] = $total->rowCount();
        
//         $rows = array();
//         while ($r = $stmt->fetch()) {
//             $rows[] = $r;
//         }
//         $data['data'] = $rows;
//         return json_encode($data);
//     } catch (Exception $e) {
//         return false;
//     }
// }

function getRooms($agentId = false)
{

    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            $additional = ' WHERE agent_id = ? ';
            $array = [$agentId];
        }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms ' . $additional . ' order by room_id desc');
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
    checkHeaders();
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
 * Deletes rooms by agent ID
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agentId
 * @return boolean
 */
function deleteRoomByAgent($agentId) {
    global $dbPrefix, $pdo;
    
    try {
        $additional = '';
        $array = [$agentId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'rooms WHERE agent_id = ?';
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

function getAgents()
{
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

//j7

// function getAgents($draw, $start = 0, $offset = 10, $order = false, $search = false) {
//     global $dbPrefix, $pdo;
//     checkHeaders();
//     try {
        
//         $additional = '';
//         $array = [];
        
//         if ($search['value']) {
//             if (!$additional) {
//                 $additional = ' WHERE';
//             }
//             $additional .= ' first_name like "%' . $search['value'] . '%" OR last_name like "%' . $search['value'] . '%" OR username like "%' . $search['value'] . '%" OR tenant like "%' . $search['value'] . '%" OR email like "%' . $search['value'] . '%"';
//         }
//         $start = $start ?? 0;
//         $offset = $offset ?? 10;
//         $orderBy = array('username', 'first_name', 'tenant', 'email');
//         $orderBySql = '';
//         if (isset($order[0]['column'])) {
//             $orderBySql = ' order by ' . $orderBy[$order[0]['column']] . ' ' . $order[0]['dir'] . ' ';
//         }
        
//         $total = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents' . $additional);
//         $total->execute($array);
//         $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents' . $additional . $orderBySql . ' LIMIT ' . $start . ',' . $offset);
//         $stmt->execute($array);
//         if ($draw) {
//             $data['draw'] = $draw;
//         }
//         $data['recordsTotal'] = $total->rowCount();
//         $data['recordsFiltered'] = $total->rowCount();
        
//         $rows = array();
//         while ($r = $stmt->fetch()) {
//             $rows[] = $r;
//         }
//         $data['data'] = $rows;
//         return json_encode($data);
        
//     } catch (Exception $e) {
//         return false;
//     }
// }

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
    checkHeaders();
    try {

        $sql = 'DELETE FROM ' . $dbPrefix . 'agents WHERE agent_id = ?';
        $pdo->prepare($sql)->execute([$agentId]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Deletes an agent by username
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $userna,e
 * @return boolean
 */
function deleteAgentByUsername($username) {
    global $dbPrefix, $pdo;
    
    try {

        $sql = 'DELETE FROM ' . $dbPrefix . 'agents WHERE username = ?';
        $pdo->prepare($sql)->execute([$username]);
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
    checkHeaders();
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
    checkHeaders();
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
 * @return boolean
 */

function getUsers()
{

    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users order by user_id desc');
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

 //j7

// function getUsers($draw, $start = 0, $offset = 10, $order = false, $search = false) {

//     global $dbPrefix, $pdo;
//     checkHeaders();
//     try {
//         $additional = '';
//         $array = [];
        
//         if ($search['value']) {
//             if (!$additional) {
//                 $additional = ' WHERE';
//             }
//             $additional .= ' name like "%' . $search['value'] . '%" OR username like "%' . $search['value'] . '%"';
//         }
//         $start = $start ?? 0;
//         $offset = $offset ?? 10;
//         $orderBy = array('name', 'username');
//         $orderBySql = '';
//         if (isset($order[0]['column'])) {
//             $orderBySql = ' order by ' . $orderBy[$order[0]['column']] . ' ' . $order[0]['dir'] . ' ';
//         }
        
//         $total = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users' . $additional);
//         $total->execute($array);
//         $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users' . $additional . $orderBySql . ' LIMIT ' . $start . ',' . $offset);
//         $stmt->execute($array);
//         if ($draw) {
//             $data['draw'] = $draw;
//         }
//         $data['recordsTotal'] = $total->rowCount();
//         $data['recordsFiltered'] = $total->rowCount();
        
//         $rows = array();
//         while ($r = $stmt->fetch()) {
//             $rows[] = $r;
//         }
//         $data['data'] = $rows;
//         return json_encode($data);
        
//     } catch (Exception $e) {
//         return false;
//     }
// }

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
    checkHeaders();
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
 * @param type $userId
 * @param type $name
 * @param type $user
 * @param type $pass
 * @param type $blocked
 * @return boolean
 */



function editUser($userId, $name, $user, $pass, $blocked, $credits, $minimum_credit_score)
{
    
    global $dbPrefix, $pdo;
    $additional = '';
    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? and user_id <> ?');
    $stmt->execute([$user, $userId]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }

    $array = [$user, $name, $credits, $minimum_credit_score, $blocked, $userId];
   
    if ($pass) {
        $additional = ', password = ?';
        $array = [$user, $name, $credits, $minimum_credit_score, $blocked, md5($pass), $userId];
    }
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'users SET username=?, name=?, credits=?, minimum_credit_score=?, is_blocked=? ' . $additional . ' WHERE user_id = ?';
        $pdo->prepare($sql)->execute($array);
       
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

 //j7


// function editUser($userId, $name, $user, $pass, $blocked) {
//     global $dbPrefix, $pdo;
//     checkHeaders();
//     $additional = '';
//     $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? and user_id <> ?');
//     $stmt->execute([$user, $userId]);
//     $userName = $stmt->fetch();
//     if ($userName) {
//         return false;
//     }

//     $array = [$user, $name, $blocked, $userId];
//     if ($pass) {
//         $additional = ', password = ?';
//         $array = [$user, $name, $blocked, md5($pass), $userId];
//     }
//     try {
//         $sql = 'UPDATE ' . $dbPrefix . 'users SET username=?, name=?, is_blocked=? ' . $additional . ' WHERE user_id = ?';
//         $pdo->prepare($sql)->execute($array);
//         return true;
//     } catch (Exception $e) {
//         return $e->getMessage();
//     }
// }

/**
 * Updates a configuration file properties.
 * 
 * @param type $postData
 * @param type $file
 * @return boolean
 */
function updateConfig($postData, $file) {
    checkHeaders();
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
    checkHeaders();
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
    checkHeaders();
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
    checkHeaders();
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

 function addUser($user, $name, $pass, $firstName = null, $lastName = null, $credits, $minimum_credit_score)
{

    global $dbPrefix, $pdo;


    try {
       
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?');
        $stmt->execute([$user]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }


        $sql = 'INSERT INTO ' . $dbPrefix . 'users (username, name, password, first_name, last_name, credits, minimum_credit_score) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([$user, $name, md5($pass), $firstName, $lastName, $credits, $minimum_credit_score]);
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
 //j7

// function addUser($user, $name, $pass, $firstName = null, $lastName = null) {
//     global $dbPrefix, $pdo;
//     checkHeaders();
//     try {

//         $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?');
//         $stmt->execute([$user]);
//         $userName = $stmt->fetch();
//         if ($userName) {
//             return false;
//         }


//         $sql = 'INSERT INTO ' . $dbPrefix . 'users (username, name, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)';
//         $pdo->prepare($sql)->execute([$user, $name, md5($pass), $firstName, $lastName]);
//         return true;
//     } catch (Exception $e) {
//         return false;
//     }
// }

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
 * Login method for an agent.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $username
 * @param type $pass
 * @return boolean
 */
function loginAgentExt($username, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ? AND password=?');
        $stmt->execute([$username, md5($pass)]);
        $user = $stmt->fetch();

        if ($user) {
            $arr = array('agent_id' => $user['agent_id'], 'first_name' => $user['first_name'], 'last_name' => $user['last_name'], 'tenant' => $user['tenant'], 'email' => $user['email'], 'username' => $user['username'], 'is_master' => $user['is_master']);
            return json_encode($arr);
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
function getChats($draw, $agentId = false, $start = 0, $offset = 10, $order = false, $search = false) {
    global $dbPrefix, $pdo;
    checkHeaders();
    try {
        $additional = '';
        $array = [];

        if ($agentId && $agentId != 'false') {
            $additional = ' WHERE agent_id = ? ';
            $array = [$agentId];
        }

        if ($search['value']) {
            if (!$additional) {
                $additional = ' WHERE';
            }
            $additional .= ' agent like "%' . $search['value'] . '%" OR date_created like "%' . $search['value'] . '%" OR date_created  like "%' . $search['value'] . '%"';
        }
        $orderBy = array('date_created', 'room_id');
        $total = $pdo->prepare('SELECT max(room_id) as room_id, max(date_created) as date_created, max(agent) as agent FROM ' . $dbPrefix . 'chats ' . $additional . ' group by room_id');
        $total->execute($array);
        $stmt = $pdo->prepare('SELECT max(room_id) as room_id, max(date_created) as date_created, max(agent) as agent FROM ' . $dbPrefix . 'chats ' . $additional . ' group by room_id order by ' . $orderBy[$order[0]['column']] . ' ' . $order[0]['dir'] . ' LIMIT ' . $start . ',' . $offset);
        $stmt->execute($array);
        if ($draw) {
            $data['draw'] = $draw;
        }
        $data['recordsTotal'] = $total->rowCount();
        $data['recordsFiltered'] = $total->rowCount();

        $data['recordsTotal'] = $total->rowCount();
        $data['recordsFiltered'] = $total->rowCount();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $stmt1 = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'chats where room_id=? order by date_created asc ');
            $stmt1->execute([$r['room_id']]);
            $rows1 = '<table>';
            while ($r1 = $stmt1->fetch()) {
                $rows1 .= '<tr><td><small>' . $r1['date_created'] . '</small></td><td>' . $r1['from'] . ': ' . $r1['message'] . '</td></tr>';
            }
            $rows1 .= '</table>';
//            $r['messages'] = $rows1;

            $r['messages'] = '<div class="modal fade" id="ex' . $r['room_id'] . '" tabindex="-1" role="dialog" aria-labelledby="ex' . $r['room_id'] . '" aria-hidden="true"><div class="modal-dialog modal-lgr" role="document"><button type="button" data-toggle="modal" class="closeDocumentModal" data-target="#ex' . $r['room_id'] . '" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button><div class="modal-content">' . $rows1 . '</div>     </div> </div><a href="" class="fas fa-fw fa-list" data-toggle="modal" data-target="#ex' . $r['room_id'] . '"></a>';


            $rows[] = $r;
        }
        $data['data'] = $rows;
        return json_encode($data);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * Returns all the logs for agent_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @return type
 */
function getLogs($draw, $agentId = false, $start = 0, $offset = 10, $order = false, $search = false) {
    global $dbPrefix, $pdo;
    checkHeaders();
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            $additional = ' WHERE agent_id = ? ';
            $array = [$agentId];
        }

        if ($search['value']) {
            if (!$additional) {
                $additional = ' WHERE';
            }
            $additional .= ' agent like "%' . $search['value'] . '%" OR date_created like "%' . $search['value'] . '%" OR date_created  like "%' . $search['value'] . '%"';
        }
        $orderBy = array('date_created', 'room_id');
        $total = $pdo->prepare('SELECT max(room_id) as room_id, max(date_created) as date_created, max(agent) as agent FROM ' . $dbPrefix . 'logs ' . $additional . ' group by room_id');
        $total->execute($array);
        $stmt = $pdo->prepare('SELECT max(room_id) as room_id, max(date_created) as date_created, max(agent) as agent FROM ' . $dbPrefix . 'logs ' . $additional . ' group by room_id order by ' . $orderBy[$order[0]['column']] . ' ' . $order[0]['dir'] . ' LIMIT ' . $start . ',' . $offset);
        $stmt->execute($array);
        if ($draw) {
            $data['draw'] = $draw;
        }
        $data['recordsTotal'] = $total->rowCount();
        $data['recordsFiltered'] = $total->rowCount();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $stmt1 = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'logs where room_id=? order by date_created asc ');
            $stmt1->execute([$r['room_id']]);

            $action = '';
            $rows1 = '<table width="100%">';
            $startDate = $total = 0;
            while ($r1 = $stmt1->fetch()) {
                if ($action == $r1['message'] && $r1['message'] == 'end') {
                    continue;
                }
                if ($r1['message'] == 'start') {
                    $total++;
                    $startDate = $r1['date_created'];
                }
                if ($r1['message'] == 'start' || $r1['message'] == 'join' || $r1['message'] == 'leave' || $r1['message'] == 'audio muted' || $r1['message'] == 'audio unmuted' || $r1['message'] == 'video muted' || $r1['message'] == 'start recording' || $r1['message'] == 'end recording' || $r1['message'] . substr(0, 4) == 'error' || $r1['message'] == 'video unmuted' || $r1['message'] == 'start screenshare' || $r1['message'] == 'stop screenshare') {
                    $vis = ($r1['message'] == 'start') ? $r1['agent'] : $r1['attendee'];
                    $rows1 .= '<tr><td width="25%"><small>' . $r1['date_created'] . '</small></td><td width="75%"><small>' . $vis . ' ' . $r1['message'] . '<br/>' . $r1['ua'] . '</small></td></tr>';
                } else {
                    $rows1 .= '<tr><td width="25%"><small>' . $r1['date_created'] . '</small></td><td width="75%"><small>' . $r1['message'] . '</small></td></tr>';
                }
                if ($startDate > 0 && $r1['message'] == 'end') {
                    $endDate = $r1['date_created'];
                    $rows1 .= '<tr><td><small><b data-localize="duration"></b></small></td><td><small><b>' . secondsToTime(strtotime($endDate) - strtotime($startDate)) . '</b></small></td></tr>';
                }
                $action = $r1['message'];
            }
            $rows1 .= '<tr><td><small><b data-localize="total"></b></small></td><td><small><b>' . $total . '</b></small></td></tr>';
            $rows1 .= '</table>';
            $r['messages'] = '<div class="modal fade" id="ex' . $r['room_id'] . '" tabindex="-1" role="dialog" aria-labelledby="ex' . $r['room_id'] . '" aria-hidden="true"><div class="modal-dialog modal-lgr" role="document"><button type="button" data-toggle="modal" class="closeDocumentModal" data-target="#ex' . $r['room_id'] . '" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button><div class="modal-content">' . $rows1 . '</div>     </div> </div><a href="" class="fas fa-fw fa-list" data-toggle="modal" data-target="#ex' . $r['room_id'] . '"></a>';
            $rows[] = $r;
        }

        $data['data'] = $rows;
        return json_encode($data);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getPassphrase() {
    global $pasPhrase;
    return $pasPhrase;
}

function getPk() {
    global $setVal;
    if (isset($setVal)) {
        return $setVal;
    } else {
        return '';
    }
}

function getVirtualImages() {
    $filenameArray = [];

    $handle = opendir('../img/virtual');
    while ($file = readdir($handle)) {
        if ($file !== '.' && $file !== '..') {
            array_push($filenameArray, $file);
        }
    }

    return json_encode($filenameArray);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type']) && $_POST['type'] == 'login') {
        echo checkLogin($_POST['email'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'logintoken') {
        echo checkLoginToken($_POST['token'], $_POST['roomId'], @$_POST['isAdmin']);
    }
    // if (isset($_POST['type']) && $_POST['type'] == 'scheduling') {
    //     echo insertScheduling($_POST['agent'], $_POST['visitor'], $_POST['agenturl'], $_POST['visitorurl'], $_POST['password'], $_POST['session'], $_POST['datetime'], $_POST['duration'], $_POST['shortAgentUrl'], $_POST['shortVisitorUrl'], $_POST['agentId'], @$_POST['agenturl_broadcast'], @$_POST['visitorurl_broadcast'], @$_POST['shortAgentUrl_broadcast'], @$_POST['shortVisitorUrl_broadcast'], @$_POST['is_active']);
    // }

    if (isset($_POST['type']) && $_POST['type'] == 'scheduling') {
        echo insertScheduling($_POST['agent'], $_POST['visitor'], $_POST['agenturl'], $_POST['visitorurl'], $_POST['password'], $_POST['session'], $_POST['datetime'], $_POST['duration'], $_POST['shortAgentUrl'], $_POST['shortVisitorUrl'], $_POST['agentId'], @$_POST['agenturl_broadcast'], @$_POST['visitorurl_broadcast'], @$_POST['shortAgentUrl_broadcast'], @$_POST['shortVisitorUrl_broadcast'], @$_POST['is_active'], @$_POST['parent_id'], @$_POST['categories'], @$_POST['minimum_time_of_quiz'], @$_POST['minimum_time_for_video'], @$_POST['credits_for_room']);
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
    if (isset($_POST['type']) && $_POST['type'] == 'addlog') {
//        echo $_POST['roomId']. ' ' .  $_POST['message']. ' ' .  $_POST['agent']. ' ' .  @$_POST['agentId']. ' ' .  @$_POST['datetime']. ' ' .  @$_POST['session']. ' ' .  @$_POST['constraint'] . ' ' . @$_POST['ua'];
        echo insertLog($_POST['roomId'], $_POST['message'], $_POST['agent'], @$_POST['agentId'], @$_POST['datetime'], @$_POST['session'], @$_POST['constraint'], @$_POST['ua'], @$_POST['attendee']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getchat') {
        echo getChat($_POST['roomId'], $_POST['sessionId'], @$_POST['agentId']);
    }
    // if (isset($_POST['type']) && $_POST['type'] == 'getrooms') {
    //     echo getRooms(@$_POST['draw'], @$_POST['agentId'], @$_POST['start'], @$_POST['length'], @$_POST['order'], @$_POST['search']);
    // }

    if (isset($_POST['type']) && $_POST['type'] == 'getrooms') {
        echo getRooms(@$_POST['agentId']);
    }

    if (isset($_POST['type']) && $_POST['type'] == 'getchats') {
        echo getChats(@$_POST['draw'], @$_POST['agentId'], @$_POST['start'], @$_POST['length'], @$_POST['order'], @$_POST['search']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getlogs') {
        echo getLogs(@$_POST['draw'], @$_POST['agentId'], @$_POST['start'], @$_POST['length'], @$_POST['order'], @$_POST['search']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteroom') {
        echo deleteRoom($_POST['roomId'], $_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteroombyagent') {
        echo deleteRoomByAgent($_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getagents') {
        echo getAgents(@$_POST['draw'], @$_POST['start'], @$_POST['length'], @$_POST['order'], @$_POST['search']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteagent') {
        echo deleteAgent($_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteagentbyusername') {
        echo deleteAgentByUsername($_POST['username']);
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
    if (isset($_POST['type']) && $_POST['type'] == 'loginagentext') {
        echo loginAgentExt($_POST['username'], $_POST['password']);
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
        echo getRecordings(@$_POST['draw'], @$_POST['start'], @$_POST['length'], @$_POST['order'], @$_POST['search']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleterecording') {
        echo deleteRecording($_POST['recordingId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getusers') {
        echo getUsers(@$_POST['draw'], @$_POST['start'], @$_POST['length'], @$_POST['order'], @$_POST['search']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteuser') {
        echo deleteUser($_POST['userId']);
    }

    if (isset($_POST['type']) && $_POST['type'] == 'edituser') {
        echo editUser($_POST['userId'], $_POST['name'], $_POST['username'], @$_POST['password'], @$_POST['isBlocked'], $_POST['credits'], $_POST['minimum_credit_score']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'adduser') {
        echo addUser($_POST['username'], $_POST['name'], $_POST['password'], @$_POST['firstName'], @$_POST['lastName'], $_POST['credits'], $_POST['minimum_credit_score']);
    }
    
    //j7

    // if (isset($_POST['type']) && $_POST['type'] == 'edituser') {
    //     echo editUser($_POST['userId'], $_POST['name'], $_POST['username'], @$_POST['password'], @$_POST['isBlocked']);
    // }
    // if (isset($_POST['type']) && $_POST['type'] == 'adduser') {
    //     echo addUser($_POST['username'], $_POST['name'], $_POST['password'], @$_POST['firstName'], @$_POST['lastName']);
    // }
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
    if (isset($_POST['type']) && $_POST['type'] == 'getpk') {
        echo getPk();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'recoverpassword') {
        echo recoverPassword($_POST['email'], $_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'resetpassword') {
        echo resetPassword($_POST['token'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getvirtualimages') {
        echo getVirtualImages();
    }

    if (isset($_POST['type']) && $_POST['type'] == 'agentshoursByagentsDetails') {
        echo getAgentsHoursByagentsDetails($_POST['email'], $_POST['roomId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'agentshoursByagents') {
        echo getAgentsHoursByagents($_POST['roomId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'agentshoursByrooms') {
        echo getAgentsHoursByrooms();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'agentStopMetting') {
        echo agentStopMetting($_POST['email'], $_POST['password'], $_POST['message'], $_POST['system'], $_POST['room_id'], $_POST['status'], $_POST['endtime'], $_POST['room_datetime']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getRecordingsByCategory') {
        echo getRecordingsByCategory();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getFeedbacks') {
        echo getFeedbacks();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getUsersHoursByusersDetailsReport') {
        echo getUsersHoursByusersDetailsReport($_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getUsersDetailedReport') {
        echo getUsersDetailedReport();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'importEssayTypeQuiz') {
        echo importEssayTypeQuiz($_POST['final_data'], $_POST['filesize'], $_POST['upload_type']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'importMcq') {
        echo importMcq($_POST['final_data'], $_POST['filesize'], $_POST['upload_type']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getuserwiseCertificationAllow') {
        echo getuserwiseCertificationAllow();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getvisitorCreditIndex') {
        echo getvisitorCreditIndex($_POST['user_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getvisitorCreditIndexUserwise') {
        echo getvisitorCreditIndexUsewise();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getvisitorquizindex') {
        echo getvisitorquizindex($_POST['user_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getvisitorQuizindexUsewise') {
        echo getvisitorQuizindexUsewise();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getVideosTracking') {
        echo getVideosTracking($_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getVideosTrackingByroom') {
        echo getVideosTrackingByroom();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getVideosTrackingByusers') {
        echo getVideosTrackingByusers($_POST['room_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'usershoursByusersDetails') {
        echo getUsersHoursByusersDetails($_POST['username'], $_POST['roomId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'usershoursByusers') {
        echo getUsersHoursByusers($_POST['roomId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'usershoursByrooms') {
        echo getUsersHoursByrooms();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'usershours') {
        echo getUsersHours();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayTypeQuizUsersAnswer') {
        echo getEssayTypeQuizUsersAnswer($_POST['user_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayTypeQuizUsers') {
        echo getEssayTypeQuizUsers();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editEssayTypeQuizAnswer') {
        echo editEssayTypeQuizAnswer($_POST['quiz_essay_id'], $_POST['agent_id'], $_POST['user_id'], $_POST['manual_marks']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayTypeQuizAnswerById') {
        echo getEssayTypeQuizAnswerById($_POST['quiz_essay_id'], $_POST['agent_id'], $_POST['user_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayTypeQuizAnswer') {
        echo getEssayTypeQuizAnswer();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addEssayQuizAnswer') {
        echo addEssayQuizAnswer($_POST['category'], $_POST['data'], $_POST['agent_id'], $_POST['user_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayQuiz') {
        echo getEssayQuiz($_POST['category']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'videoTracking') {
        echo videoTracking($_POST['video_id'], $_POST['video_duration'], $_POST['previous_time'], $_POST['current_time'], $_POST['user']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getSettingById') {
        echo getSettingById($_POST['id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editSetting') {
        echo editSetting($_POST['id'], $_POST['subject'], $_POST['description'], $_POST['key_name'], $_POST['key_value']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addSetting') {
        echo addSetting($_POST['subject'], $_POST['description'], $_POST['key_name'], $_POST['key_value']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getSetting') {
        echo getSetting();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getSubRooms') {
        echo getSubRooms(@$_POST['agentId'],  @$_POST['currentdatetime']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'import') {
        echo importUsers($_POST['final_data'], $_POST['filesize'], $_POST['upload_type']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getQuizCategoryWise') {
        echo getQuizCategoryWise();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteEssayTypeQuiz') {
        echo deleteEssayTypeQuiz($_POST['quiz_essay_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editEssayTypeQuiz') {
        echo editEssayTypeQuiz($_POST['quiz_essay_id'], $_POST['category'], $_POST['question_name'], $_POST['question_text'], $_POST['default_marks'], $_POST['gernal_feedback'], $_POST['status']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayTypeQuizById') {
        echo getEssayTypeQuizById($_POST['quiz_essay_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getEssayTypeQuiz') {
        echo getEssayTypeQuiz();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addEssayTypeQuiz') {
        echo addEssayTypeQuiz($_POST['category'], $_POST['question_name'], $_POST['question_text'], $_POST['default_marks'], $_POST['gernal_feedback'], $_POST['status']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getCategorydropdown') {
        echo getCategorydropdown();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteQuiz') {
        echo deleteQuiz($_POST['quizId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteCategory') {
        echo deleteCategory($_POST['categoryId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getCategory') {
        echo getCategory();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getCategoryById') {
        echo getCategoryById($_POST['id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editcategory') {
        echo editcategory($_POST['id'], $_POST['category_name'], $_POST['minimum_score']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addcategory') {
        echo addcategory($_POST['category_name'], $_POST['minimum_score']);
    }
    // visitor quiz access methods
    if (isset($_POST['type']) && $_POST['type'] == 'quizaccessupdate') {
        echo quizaccessupdate($_POST['email'], $_POST['room_id']);
    }
    // visitor quiz status report methods
    if (isset($_POST['type']) && $_POST['type'] == 'addquizreport') {
        echo addquizreport($_POST['email'], $_POST['room_id'], $_POST['quiz_access_type']);
    }
    // visitor add quiz and timer
    if (isset($_POST['type']) && $_POST['type'] == 'addTimer') {
        echo addTimer($_POST['min'], $_POST['sec']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getQuiz') {
        echo getQuiz($_POST['category']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getQuizById') {
        echo getQuizById($_POST['id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addQuiz') {
        echo addQuiz($_POST['category'], $_POST['status'], $_POST['question'], $_POST['correct_answer'], $_POST['wrong_answer1'], $_POST['wrong_answer2'], $_POST['wrong_answer3']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editQuiz') {
        echo editQuiz($_POST['id'], $_POST['category'], $_POST['status'], $_POST['question'], $_POST['correct_answer'], $_POST['wrong_answer1'], $_POST['wrong_answer2'], $_POST['wrong_answer3']);
    }
    // visitor stop mettting
    if (isset($_POST['type']) && $_POST['type'] == 'deleteuploads') {
        echo deleteUploads($_POST['uploadsId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getUpload') {
        echo getUpload($_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getUploads') {
        echo getUploads();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'uploads') {
        echo uploads($_POST['filename'], $_POST['room_id'], $_POST['email'], $_POST['upload_type']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'visitorstopmetting') {
        echo visitorStopMetting($_POST['email'], $_POST['password'], $_POST['message'], $_POST['system'], $_POST['room_id'], $_POST['status'], $_POST['endtime'], $_POST['room_datetime']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'exportcsv') {
        echo exportCSV();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'exportcsvagentshours') {
        echo exportCSVagentsHours();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'exportcsvuserhourslogs') {
        echo exportCSVuserHoursLogs();
    }
}






//j7 jawedSiddiqui



function addcategory($category_name, $minimum_score)
{
    global $dbPrefix, $pdo;
    try {
       
        if (!$category_name == '') {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'category WHERE name = ?');
            $stmt->execute([$category_name]);
            $category = $stmt->fetch();

            if (!$category) {
                $sql = "INSERT INTO " . $dbPrefix . "category (name, minimum_score)"
                    . "VALUES (?,?)";
                $pdo->prepare($sql)->execute([$category_name, $minimum_score]);
               
                return 200;
            } else if ($category) {
                return 201;
            }
        }
        return 202;
    } catch (Exception $e) {
        return false;
    }
}

function editCategory($id, $category_name, $minimum_score)
{
    global $dbPrefix, $pdo;

    $array = [$category_name, $minimum_score, $id];
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'category SET name=?, minimum_score=? WHERE id=?';
        $result = $pdo->prepare($sql)->execute($array);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getQuizCategoryWise()
{
    global $dbPrefix, $pdo;
    try {
        // $additional = '';
        // $array = [];
        // if ($agentId && $agentId != 'false') {
        //     $additional = ' WHERE agent_id = ? ';
        //     $array = [$agentId];
        // }
        $stmt = $pdo->prepare('SELECT category, COUNT(category) as question_count FROM ' . $dbPrefix . 'quiz GROUP BY category ORDER BY id DESC');
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

function getQuiz($category)
{
    global $dbPrefix, $pdo;
    try {
        // $additional = '';
        // $array = [];
        // if ($agentId && $agentId != 'false') {
        //     $additional = ' WHERE agent_id = ? ';
        //     $array = [$agentId];
        // }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'quiz WHERE category="' . $category . '" ORDER BY id DESC');
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
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz WHERE category="'.$category.'" AND status = 1  ORDER BY quiz_essay_id DESC');
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
        // var_dump($data);
        // die;
        $data=json_decode($data, true);
        $data = $data['formasd'];
        
        foreach($data as $key => $value){
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz_answer WHERE quiz_essay_id='.$key.' AND agent_id ='.$agent_id.' ORDER BY id DESC');
            $result = $stmt->execute();
            $result = $stmt->fetch();
            // var_dump($result);
            if($result === false){
              
                $sql = "INSERT INTO " . $dbPrefix . "essay_type_quiz_answer (quiz_essay_id, quiz_answer, agent_id)"
                    . "VALUES (?, ?, ?)";
                $pdo->prepare($sql)->execute([$key, $value, $agent_id]);
            }else{
                $sql1 = "UPDATE " . $dbPrefix . 'essay_type_quiz_answer SET quiz_answer=? WHERE quiz_essay_id=?  AND agent_id=?';
                $pdo->prepare($sql1)->execute([$value, $key, $agent_id]);    
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
function getQuizById($id)
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'quiz WHERE id="' . $id . '" ORDER BY id DESC');
        $stmt->execute();
        $rows = array();

        while ($r = $stmt->fetch()) {
            $rows = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getEssayTypeQuiz()
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz ORDER BY quiz_essay_id DESC');
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

function getEssayTypeQuizUsers()
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare(
            // 'SELECT"quiz_essay_id,category,question_name,question_text, default_marks, gernal_feedback,agent_id, quiz_answer, manual_marks, is_deleted, timestamp" 
            'SELECT *, COUNT(lsv_essay_type_quiz_answer.quiz_essay_id) as ques_count,if(lsv_essay_type_quiz_answer.manual_marks > manual_marks,"Yes","No") AS pending_marks
            FROM ' . $dbPrefix . 'essay_type_quiz_answer 
            INNER JOIN lsv_essay_type_quiz ON lsv_essay_type_quiz.quiz_essay_id=lsv_essay_type_quiz_answer.quiz_essay_id
            LEFT JOIN lsv_users ON lsv_users.user_id = lsv_essay_type_quiz_answer.user_id 
            GROUP BY lsv_essay_type_quiz_answer.user_id ORDER BY id DESC');
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

function getEssayTypeQuizAnswer()
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare(
            // 'SELECT"quiz_essay_id,category,question_name,question_text, default_marks, gernal_feedback,agent_id, quiz_answer, manual_marks, is_deleted, timestamp" 
            'SELECT * 
            FROM ' . $dbPrefix . 'essay_type_quiz_answer 
            INNER JOIN lsv_essay_type_quiz ON lsv_essay_type_quiz.quiz_essay_id=lsv_essay_type_quiz_answer.quiz_essay_id
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

function getEssayTypeQuizById($quiz_essay_id)
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz where quiz_essay_id=?');
        $stmt->execute([$quiz_essay_id]);
        $rows = array();

        while ($r = $stmt->fetch()) {
            $rows = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getEssayTypeQuizAnswerById($quiz_essay_id, $agent_id=null, $user_id)
{
    global $dbPrefix, $pdo;
    try {
        // var_dump($user_id);
        // die;
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz_answer 
        LEFT JOIN lsv_essay_type_quiz ON lsv_essay_type_quiz.quiz_essay_id=lsv_essay_type_quiz_answer.quiz_essay_id
        where lsv_essay_type_quiz_answer.quiz_essay_id=? AND lsv_essay_type_quiz_answer.user_id=?');
        $stmt->execute([$quiz_essay_id, $user_id]);
        $rows = array();

        while ($r = $stmt->fetch()) {
            $rows = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getCategorydropdown()
{
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        // if ($agentId && $agentId != 'false') {
        //     $additional = ' WHERE agent_id = ? ';
        //     $array = [$agentId];
        // }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'category  order by id desc');
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


function importUsers($final_data, $size, $upload_type)
{

    global $dbPrefix, $pdo;
    try {

        $final_data = json_decode($final_data);
        foreach ($final_data as $key => $data_value) {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?');
            $stmt->execute([$data_value[0]]);
            $userName = $stmt->fetch();
            if (empty($userName)) {
                $sql = 'INSERT INTO ' . $dbPrefix . 'users (username, password, name, roomId, first_name, last_name, token, is_blocked, credits, status, endtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $pdo->prepare($sql)->execute([$data_value[0], md5($data_value[1]), ($data_value[2]), $data_value[3], $data_value[4], $data_value[5], $data_value[6], $data_value[7], $data_value[8], $data_value[9], $data_value[10]]);
            }
        }
        return true;
    } catch (Exception $e) {
       // print_r($e->getMessage());
        return false;
    }
}

function importMcq($final_data, $size, $upload_type)
{

    global $dbPrefix, $pdo;
    try {

        $final_data = json_decode($final_data);
        $duplicate = [];
        foreach ($final_data as $key => $data_value) {
            
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'quiz WHERE que = ?');
            $stmt->execute([$data_value[0]]);
            
            $quiz = $stmt->fetch();
            

            if (empty($quiz)) {
                
                $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'category WHERE name = ?');
                $stmt->execute([$data_value[8]]);
                $category = $stmt->fetch();
               
                if($category){
                    $sql = 'INSERT INTO ' . $dbPrefix . 'quiz (que, option1, option2, option3, option4, ans, userans, user_id, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    $pdo->prepare($sql)->execute([$data_value[0], $data_value[1], $data_value[2], $data_value[3], $data_value[4], $data_value[5], $data_value[6], $data_value[7], $category['name'], $data_value[9]]);
                }else{
                    return $data_value[8];            
                }
            }else{
                // if question already there
                $duplicate[] = $data_value[0];
            }
        }
        
      $duplicate = $duplicate ? $duplicate = $duplicate : $duplicate = false;
      if($duplicate != false){
        return 2;
      }
        return true;
        
    } catch (Exception $e) {
        return false;
    }
}

function importEssayTypeQuiz($final_data, $size, $upload_type)
{

    global $dbPrefix, $pdo;
    try {

        $final_data = json_decode($final_data);
        $duplicate = [];
        foreach ($final_data as $key => $data_value) {
            
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz WHERE question_name = ? AND question_text = ?');
            $stmt->execute([$data_value[1],$data_value[2]]);
            
            $quiz = $stmt->fetch();
           
            if (empty($quiz)) {
                $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'category WHERE name = ?');
                $stmt->execute([$data_value[0]]);
                $category = $stmt->fetch();
                if($category){
                    $sql = 'INSERT INTO ' . $dbPrefix . 'essay_type_quiz (category, question_name, question_text, default_marks, gernal_feedback, status) VALUES (?, ?, ?, ?, ?, ?)';
                    $pdo->prepare($sql)->execute([$category['name'], $data_value[1], $data_value[2], $data_value[3], $data_value[4], $data_value[5]]);
                }else{
                    return $data_value[0];            
                }
            }else{
                // if question already there
                $duplicate[] = $data_value[0];
            }
        }
        $duplicate = $duplicate ? $duplicate = $duplicate : $duplicate = false;
        if($duplicate != false){
            return 2;
        }
            return true;

    } catch (Exception $e) {
       // print_r($e->getMessage());
        return false;
    }
}


function editEssayTypeQuiz($quiz_essay_id, $category, $question_name, $question_text, $default_marks, $gernal_feedback, $status)
{
    global $dbPrefix, $pdo;

    $array = [$category, $question_name, $question_text, $default_marks, $gernal_feedback, $status, $quiz_essay_id];
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'essay_type_quiz SET category=?, question_name=?, question_text=?, default_marks=?, gernal_feedback=?, status=? WHERE quiz_essay_id = ?';
        $result = $pdo->prepare($sql)->execute($array);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}


function editEssayTypeQuizAnswer($quiz_essay_id, $agent_id=null, $user_id=null, $marks)
{
    // var_dump($marks);
    // die;
    global $dbPrefix, $pdo;
    try {
        $array = [$marks, $quiz_essay_id, $user_id ];
        $sql = 'UPDATE ' . $dbPrefix . 'essay_type_quiz_answer SET manual_marks=? WHERE quiz_essay_id = ? AND user_id = ?';
        $result = $pdo->prepare($sql)->execute($array);
            return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}



function addSetting($subject, $description, $key_name, $key_value)
{
    global $dbPrefix, $pdo;
    try {
        if (!$key_name == '') {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'setting WHERE key_name = ?');
            $stmt->execute([$key_name]);
            $key_name = $stmt->fetch();

            if (!$key_name) {
                $sql = "INSERT INTO " . $dbPrefix . "setting (subject, description, key_name, key_value)"
                    . "VALUES (?, ?, ?, ?)";
                $pdo->prepare($sql)->execute([$subject, $description, $key_name, $key_value]);

                return 200;
            } else if ($key_name) {
                return 201;
            }
        }
        return 202;
    } catch (Exception $e) {
        return false;
    }
}

function editSetting($id, $subject, $description, $key_name, $key_value)
{
    global $dbPrefix, $pdo;

    $array = [$subject, $description, $key_name, $key_value, $id];
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'setting SET subject=?, description=?, key_name=?, key_value=? WHERE id=?';
        $result = $pdo->prepare($sql)->execute($array);
        return $result;
    } catch (Exception $e) {
        return 202;
        // return $e->getMessage();
    }
}
//agent metting stop function for logs tracking
function agentStopMetting($email, $pass, $message, $system, $room_id, $status, $endtime, $room_datetime)
{
    global $dbPrefix, $pdo;
    try {
        $room_id = str_replace('%20', ' ', $room_id);
        
        // agent already logged in status check here and update room status for number of visitors
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ?');
        $stmt->execute([$email]);
        $agent = $stmt->fetch();
       
        if($agent['status'] == 1 ){
            $stmt1 = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? AND is_active = 1');
            $stmt1->execute([$room_id]);
            $roominfo = $stmt1->fetch();
            
            // update and insert data in lsv_users & users_logged table   --vj  
            $sql1 = "UPDATE " . $dbPrefix . 'agents SET status=?, endtime=? WHERE email =?';
            $pdo->prepare($sql1)->execute([(int) $status, $endtime, $email]);
            $sql = "INSERT INTO " . $dbPrefix . "agents_logged (email, message, system_msg, date_created, room_id) "
                . "VALUES (?, ?, ?, ?, ?)";
            $pdo->prepare($sql)->execute([$email, $message . ' left', $system, date('Y-m-d H:i:s'), $room_id]);
        
            //user data
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
        }
        //room data
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? AND is_active = 1');
        $stmt->execute([$room_id]);
        $room = $stmt->fetch();

        return 200;

    } catch (Exception $e) {
        return false;
    }
}

//visitor metting stop function for logs tracking
function visitorStopMetting($username, $pass, $message, $system, $room_id, $status, $endtime, $room_datetime)
{
    global $dbPrefix, $pdo;
    try {
        $room_id = str_replace('%20', ' ', $room_id);
        
        // user already logged in status check here and update room status for number of visitors
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?  AND is_blocked = 0');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        // var_dump($user);
        if($user['status'] == 1 ){
            $stmt1 = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? AND is_active = 1');
            $stmt1->execute([$room_id]);
            $roominfo = $stmt1->fetch();
            $numberof_visitors = ($roominfo['numberof_visitors'] >= 1) ? $roominfo['numberof_visitors'] - 1 : $roominfo['numberof_visitors'] + 0;
            $sql1 = "UPDATE " . $dbPrefix . 'rooms SET   numberof_visitors=?  WHERE roomId =?';
            $pdo->prepare($sql1)->execute([(int) $numberof_visitors, $room_id]);

            // update and insert data in lsv_users & users_logged table   --vj  
           
            // $sql1 = "UPDATE " . $dbPrefix . 'users SET status=?, endtime=? WHERE username =?';
            // $pdo->prepare($sql1)->execute([(int) $status, $endtime, $username]);
            // $sql = "INSERT INTO " . $dbPrefix . "users_logged (username, message, system_msg, date_created, room_id) "
            //     . "VALUES (?, ?, ?, ?, ?)";
            // $pdo->prepare($sql)->execute([$username, $message . ' left', $system, date('Y-m-d H:i:s'), $room_id]);
        
            //user data
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND is_blocked = 0');
            $stmt->execute([$username]);
            $user = $stmt->fetch();
        }
        //room data
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? AND is_active = 1');
        $stmt->execute([$room_id]);
        $room = $stmt->fetch();

        //visitorquiz_report
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id = ? AND room_datetime = ?  AND is_eligible = 0');
        $stmt->execute([$user['user_id'], $room['datetime']]);
        $vquizrep = $stmt->fetch();

        // $spending_time = date('Y-m-d H:i:s') - date($room_datetime);
        $start_date = new DateTime($vquizrep['visitor_joining_time']);
        $spending_time = $start_date->diff(new DateTime());
        $minimum_time = $vquizrep['minimum_time'];

        // $spending_time->i its denote the spending minutes
        ($spending_time->i >= $minimum_time) ? $is_eligible = 1 : $is_eligible = 0;

        if ($vquizrep) {

            // update data in lsv_visitorquiz_report table for quiz access requirement time  --vj  
            $sql = "UPDATE " . $dbPrefix . 'visitorquiz_report SET visitor_left_time = ?, spending_time = ?, is_eligible = ? WHERE id =?';
            $result = $pdo->prepare($sql)->execute([date('Y-m-d H:i:s'), $spending_time->i, $is_eligible, $vquizrep['id']]);
        }

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE roomId = ? AND room_datetime = ? AND user_id = ? AND is_eligible = 1');
        $stmt->execute([$room_id, $room['datetime'], $user['user_id']]);
        $vquizrep_updated = $stmt->fetch();
        if ($vquizrep_updated) {
            return json_encode($vquizrep_updated);
        }
        return json_encode($vquizrep_updated);
        //  return 200;

    } catch (Exception $e) {
        return false;
    }
}

//visitor metting stop function for logs tracking
function addquizreport($email, $room_id, $quiz_access_type)
{
    global $dbPrefix, $pdo;
    try {
        $room_id = str_replace('%20', ' ', $room_id);
        // var_dump( $room_id);
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND is_blocked = 0');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        $user ? $user_id = $user['user_id'] : $user_id = '';

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? AND is_active = 1');
        $stmt->execute([$room_id]);
        $room = $stmt->fetch();

        $room ? $room_duration = $room['duration'] : $room_duration = '';
        $room ? $roomId = $room['roomId'] : $roomId = '';
        $room ? $room_datetime = $room['datetime'] : $room_datetime = '';
        // $minimum_time  = $room_duration * 70 / 100;
        $minimum_time  = $room['minimum_time_of_quiz'];


        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE roomId = ? AND room_datetime = ? AND user_id = ?');
        $stmt->execute([$room_id, $room_datetime, $user_id]);
        $vquizrep = $stmt->fetch();

        if (!$vquizrep) {
            $sql = "INSERT INTO " . $dbPrefix . "visitorquiz_report (user_id, roomId, room_datetime, visitor_joining_time, minimum_time, is_eligible, quiz_access_type)"
                . "VALUES (?, ?, ?, ?, ?, ?, ?)";
            if($quiz_access_type == 'video_tracking'){
                $pdo->prepare($sql)->execute([$user_id, $roomId, $room_datetime, date('Y-m-d H:i:s'), $minimum_time, 1 ,$quiz_access_type]);
            }else{
                $pdo->prepare($sql)->execute([$user_id, $roomId, $room_datetime, date('Y-m-d H:i:s'), $minimum_time, 0 ,$quiz_access_type]);
            }    
            
        }
        return $minimum_time;
        // return 200;
    } catch (Exception $e) {
        return false;
    }
}

//visitor metting stop function for quizaccess update tracking
function quizaccessupdate($email, $room_id)
{
    global $dbPrefix, $pdo;
    try {

        //user data
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND is_blocked = 0');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        $user_id = $user['user_id'];

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? AND is_active = 1');
        $stmt->execute([$room_id]);
        $room = $stmt->fetch();

        $room ? $roomId = $room['roomId'] : $roomId = '';
        $room ? $room_datetime = $room['datetime'] : $room_datetime = '';

        // update data in lsv_users table for endtime & status  --vj  
        // $sql1 = "UPDATE " . $dbPrefix . 'users SET status=?, endtime=? WHERE username =?';
        // $pdo->prepare($sql1)->execute([(int) $status, $endtime, $username]);
        // var_dump($room_datetime);
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE roomId = ? AND room_datetime = ? AND user_id = ? AND is_eligible = 1');
        $stmt->execute([$room_id, $room_datetime, $user_id]);
        $vquizrep = $stmt->fetch();
        if ($vquizrep) {

            // insert data in lsv_users_logged table & also passed message system & room_id in function --vj  

        }
        // var_dump($vquizrep);
        // die();
        return json_encode($vquizrep);
    } catch (Exception $e) {
        return false;
    }
}

/* uploads*/
function uploads($filename, $room_id, $email, $upload_type)
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ?');
        $stmt->execute([$email]);
        $agent = $stmt->fetch();

        if (isset($agent)) {
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
        var_dump($result['id']);
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

        // $sql = 'UPDATE ' . $dbPrefix . 'video_tracking SET video_watched_time=? WHERE username = ? AND video_id = ?  AND timestamp LIKE "%'.date('Y-m-d').'%"';
        $sql = 'UPDATE ' . $dbPrefix . 'video_tracking SET video_watched_time=? WHERE id = ?';
        // $pdo->prepare($sql)->execute([$total_watched_time, $username, $video_id]);
        $pdo->prepare($sql)->execute([$total_watched_time, $result['id']]);
    }
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}



function deleteUploads($uploadsId)
{
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'file_uploads WHERE id = ?');
        $stmt->execute([$uploadsId]);
        $rec = $stmt->fetch();

        if ($rec) {
            unlink('../server/uploads/' . $rec['filename']);
            unlink('../server/uploads/' . $rec['filename'] . '.zip');
        }

        $array = [$uploadsId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'file_uploads WHERE id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function deleteCategory($categoryId)
{
    global $dbPrefix, $pdo;
    try {
        $array = [$categoryId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'category WHERE id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function deleteQuiz($id)
{
    global $dbPrefix, $pdo;
    try {
        $array = [$id];
        $sql = 'DELETE FROM ' . $dbPrefix . 'quiz WHERE id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function deleteEssayTypeQuiz($quiz_essay_id)
{
    global $dbPrefix, $pdo;
    try {

        $array = [$quiz_essay_id];
        $sql = 'DELETE FROM ' . $dbPrefix . 'essay_type_quiz WHERE quiz_essay_id = ?';
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
function getRecordingsByCategory()
{
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT *,COUNT(quiz_category) as video_count FROM ' . $dbPrefix . 'recordings GROUP BY quiz_category order by date_created desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('../server/recordings/' . $r['filename'])) {
                    $rows[] = $r;
                }
                if (file_exists('../server/recordings/' . $r['filename'] . '.mp4')) {
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



function getFeedbacks()
{
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'feedbacks order by date_added desc');
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

function getVideosTracking($username) {
    global $dbPrefix, $pdo;
    try {
        // echo $_SESSION["username"];
        $stmt = $pdo->prepare('SELECT *,SEC_TO_TIME( SUM( TIME_TO_SEC(lsv_video_tracking.video_watched_time) ) ) as total_watched_time  FROM ' . $dbPrefix . 'video_tracking 
        INNER JOIN lsv_recordings ON lsv_video_tracking.video_id=lsv_recordings.recording_id
        WHERE lsv_video_tracking.username = "'.$username.'"
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

function getVideosTrackingByroom() {
    global $dbPrefix, $pdo;
    try {
        // echo $_SESSION["username"];
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'video_tracking 
        INNER JOIN lsv_recordings ON lsv_video_tracking.video_id=lsv_recordings.recording_id
        GROUP BY lsv_recordings.room_id
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

function getVideosTrackingByusers($room_id) {
    global $dbPrefix, $pdo;
    try {
        // echo $_SESSION["username"];
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'video_tracking 
        INNER JOIN lsv_recordings ON lsv_video_tracking.video_id=lsv_recordings.recording_id
        WHERE lsv_recordings.room_id ="'.$room_id.'" 
        GROUP BY lsv_video_tracking.username
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
// admin all uploads
function getUploads()
{
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'file_uploads order by date_created desc');
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

// agent uploads
function getUpload($username)
{

    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ?');
        $stmt->execute([$username]);
        $agent = $stmt->fetch();

        if ($agent) {
            $agent_id = $agent['agent_id'];
        }

        // $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'file_uploads where agent_id =' . $agent_id . ' order by date_created desc');
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'file_uploads order by date_created desc');
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


function getCategory()
{

    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "category  order by name asc");
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            // $r['date_created'] = strtotime($r['date_created']);
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}
function getCategoryById($id)
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'category where id=?');
        $stmt->execute([$id]);
        $rows = array();

        while ($r = $stmt->fetch()) {
            $rows = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getSetting()
{

    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "setting  order by id asc");
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            // $r['date_created'] = strtotime($r['date_created']);
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}
function getSettingById($id)
{
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'setting where id=?');
        $stmt->execute([$id]);
        $rows = array();

        while ($r = $stmt->fetch()) {
            $rows = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getSubRooms($agentId = false, $currentdatetime)
{

    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            // echo $_SESSION["username"];
            $additional = ' WHERE agent_id = ? AND username = ? AND parent_id > 0 ';
            $array = [$agentId, $_SESSION["username"]];
        } else {
            $additional = ' WHERE parent_id > 0 ';
        }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms ' . $additional . ' order by room_id desc');
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


//for adding quiz through admin
function addQuiz($category, $status, $question, $correct_answer, $wrong_answer1, $wrong_answer2, $wrong_answer3)
{
    global $dbPrefix, $pdo;
    try {
        $options_array = array($wrong_answer1, $wrong_answer2, $wrong_answer3, $correct_answer);
        shuffle($options_array);
        // print_r($options_array);
        $wrong_answer1 = $options_array[0];
        $wrong_answer2 = $options_array[1];
        $wrong_answer3 = $options_array[2];
        $wrong_answer4 = $options_array[3];

        $sql = 'INSERT INTO ' . $dbPrefix . 'quiz (category, status, que, option1, option2, option3, option4, ans) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $pdo->prepare($sql)->execute([$category, $status, $question, $wrong_answer4, $wrong_answer1, $wrong_answer2, $wrong_answer3, $correct_answer]);

        return true;
    } catch (Exception $e) {
        return  false;
    }
}

function editQuiz($id, $category, $status, $question, $correct_answer, $wrong_answer1, $wrong_answer2, $wrong_answer3)
{
    global $dbPrefix, $pdo;

    $array = [$category, $status, $question, $correct_answer, $wrong_answer1, $wrong_answer2, $wrong_answer3, $id];
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'quiz SET category=?, status=?, que=?, option4=?, option1=?, option2=?, option3=? WHERE id = ?';
        $result = $pdo->prepare($sql)->execute($array);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function addEssayTypeQuiz($category, $question_name, $question_text, $default_marks, $gernal_feedback, $status)
{
    global $dbPrefix, $pdo;
    try {
        $array = [$question_name, $question_text, $default_marks];
        foreach ($array as $key => $value) {
            $value = trim($value);
            if (empty($value))
                $validation = false;
            else
                $validation = true;
        }

        if($validation){
            $options_array = array($category, $question_name, $question_text, $default_marks, $gernal_feedback, $status);
            $sql = 'INSERT INTO ' . $dbPrefix . 'essay_type_quiz (category, question_name, question_text, default_marks, gernal_feedback, status) VALUES (?, ?, ?, ?, ?, ?)';
            $pdo->prepare($sql)->execute($options_array);
            return true;
        }    
       
    } catch (Exception $e) {
        return  false;
    }
}


function addTimer($min, $sec)
{
    global $dbPrefix, $pdo;
    try {

        $timer = $min . ':' . $sec;
        $array = [$timer];

        $sql = 'UPDATE ' . $dbPrefix . 'quiz SET timer=? WHERE id = 1';
        $pdo->prepare($sql)->execute($array);

        return true;
    } catch (Exception $e) {
        return  false;
    }
}



function getUsersDetailedReport()
{

    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users order by user_id desc');
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
function getUsersHours() {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users_logged WHERE username ="visual333@gmail.com" ORDER BY `lsv_users_logged`.`date_created` ASC');
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

function getAgentsHoursByrooms() {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents_logged  GROUP BY `lsv_agents_logged`.`room_id` ORDER BY `lsv_agents_logged`.`date_created` ASC');
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

 function exportCSVagentsHours(){
     global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT logged_id,room_id FROM ' . $dbPrefix . 'agents_logged  GROUP BY `lsv_agents_logged`.`room_id` ORDER BY `lsv_agents_logged`.`date_created` ASC');
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
function getAgentsHoursByagents($roomId) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents_logged Where room_id="'.$roomId.'" GROUP BY `lsv_agents_logged`.`email` ORDER BY `lsv_agents_logged`.`date_created` ASC');
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
function getAgentsHoursByagentsDetails($email, $roomId) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents_logged WHERE email="'.$email.'" AND room_id="'.$roomId.'" ORDER BY `lsv_agents_logged`.`date_created` ASC');
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

function getUsersHoursByrooms() {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users_logged  GROUP BY `lsv_users_logged`.`room_id` ORDER BY `lsv_users_logged`.`date_created` ASC');
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

 function exportCSV(){
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT logged_id,room_id FROM ' . $dbPrefix . 'users_logged  GROUP BY `lsv_users_logged`.`room_id` ORDER BY `lsv_users_logged`.`date_created` ASC');
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

function getUsersHoursByusers($roomId) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users_logged Where room_id="'.$roomId.'" GROUP BY `lsv_users_logged`.`username` ORDER BY `lsv_users_logged`.`date_created` ASC');
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

function getUsersHoursByusersDetails($username, $roomId) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users_logged WHERE username="'.$username.'" AND room_id="'.$roomId.'" ORDER BY `lsv_users_logged`.`date_created` ASC');
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

function getUsersHoursByusersDetailsReport($username) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT *,COUNT(room_id) as room_count  FROM ' . $dbPrefix . 'users_logged
        
        WHERE username="'.$username.'" GROUP BY room_id ORDER BY `lsv_users_logged`.`date_created` ASC');
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

function getvisitorQuizindexUsewise() {

    global $dbPrefix, $pdo;
    try {
        
        // $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id ="'.$_SESSION["agent"]["user_id"].'" ORDER BY `timestamp` ASC');
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report 
        LEFT JOIN lsv_users on lsv_users.user_id = lsv_visitorquiz_report.user_id
        Group BY lsv_users.username ORDER BY `timestamp` ASC');
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
function getvisitorCreditIndexUsewise() {

    global $dbPrefix, $pdo;
    try {
        
        // $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id ="'.$_SESSION["agent"]["user_id"].'" ORDER BY `timestamp` ASC');
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report 
        LEFT JOIN lsv_users on lsv_users.user_id = lsv_visitorquiz_report.user_id
        Group BY lsv_users.username ORDER BY `timestamp` ASC');
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
function getuserwiseCertificationAllow() {

    global $dbPrefix, $pdo;
    try {
        
        // $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id ="'.$_SESSION["agent"]["user_id"].'" ORDER BY `timestamp` ASC');
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users 
        ORDER BY `user_id` ASC');
        
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
function getvisitorquizindex($user_id) {

    global $dbPrefix, $pdo;
    try {
        
        // $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id ="'.$_SESSION["agent"]["user_id"].'" ORDER BY `timestamp` ASC');
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report 
        LEFT JOIN lsv_users ON lsv_users.user_id = lsv_visitorquiz_report.user_id
        WHERE lsv_visitorquiz_report.user_id ="'.$user_id.'" 
        ORDER BY `timestamp` ASC');
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
function getvisitorCreditIndex($user_id) {

    global $dbPrefix, $pdo;
    try {
        
        // $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'visitorquiz_report WHERE user_id ="'.$_SESSION["agent"]["user_id"].'" ORDER BY `timestamp` ASC');
        $stmt = $pdo->prepare('SELECT *,lsv_visitorquiz_report.roomId as roomId, lsv_users.username as username FROM ' . $dbPrefix . 'visitorquiz_report 
        LEFT JOIN lsv_users ON lsv_users.user_id = lsv_visitorquiz_report.user_id
        LEFT JOIN lsv_category ON lsv_category.name = lsv_visitorquiz_report.quiz_category
        LEFT JOIN lsv_rooms ON lsv_rooms.roomId = lsv_visitorquiz_report.roomId
        WHERE lsv_visitorquiz_report.user_id ="'.$user_id.'" AND total_score > 0   
        ORDER BY lsv_visitorquiz_report.timestamp ASC');
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



