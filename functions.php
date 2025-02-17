<?php
include 'config.php';

function token() {
    global $domain, $mac;
    $url = "http://$domain/stalker_portal/server/load.php?type=stb&action=handshake";
    $data = json_decode(file_get_contents($url), true);
    return $data['js']['token'];
}

function getToken() {
    $token = token();
    validateToken($token);
    return $token;
}

function validateToken($token) {
    return getProfile($token) ? "Success Token Validation" : "Error Token Validation";
}

function getProfile($token = "") {
    $token = empty($token) ? token() : $token;
    global $domain, $mac, $d1, $d2, $sn, $model;
    $url = "http://$domain/stalker_portal/server/load.php?type=stb&action=get_profile&hd=1&ver=ImageDescription%3A+0.2.18-r14-pub-" . str_replace("MAG", "", $model) . "%3B+ImageDate%3A+Fri+Jan+15+15%3A20%3A44+EET+2016%3B+PORTAL+version%3A+5.1.0%3B+API+Version%3A+JS+API+version%3A+328%3B+STB+API+version%3A+134%3B+Player+Engine+version%3A+0x566&num_banks=2&sn=$sn&stb_type=$model&image_version=218&video_out=hdmi&device_id=$d1&device_id2=$d2&signature=&auth_second_step=1&hw_version=1.7-BD-00&not_valid_token=0&client_type=STB&hw_version_2=". md5(bin2hex(random_bytes(16))). "&timestamp=". time() ."&api_signature=&metrics=%7B%22mac%22%3A%22". urlencode($mac) ."%22%2C%22sn%22%3A%22$sn%22%2C%22model%22%3A%22$model%22%2C%22type%22%3A%22STB%22%2C%22uid%22%3A%22%22%2C%22random%22%3A%22%22%7D&JsHttpRequest=1-xml";
    $fetchData  = curl_init();
    curl_setopt_array($fetchData , 
        [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIE => "mac=$mac",
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $token")
        ]);
    $data = curl_exec($fetchData); 
    curl_close($fetchData);
    return $data;
}

function getTvdata($id) {
    $uri = "stalker_portal/server/load.php?type=itv&action=create_link&cmd=ffrt%20http://localhost/ch/$id";
    $link = getWorldTvData($uri);
    return isset($link['js']['cmd']) ? $link['js']['cmd'] : "";
}

function getWorldTvData($uri, $mod=true) {
    if(empty($uri)) return "";
    global $domain, $mac;
    $url = $mod ? "https://$domain/$uri" : $uri;
    $token = getToken();
    $fetchData  = curl_init();
    curl_setopt_array($fetchData , 
        [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIE => "mac=$mac",
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $token")
        ]);
    $data = json_decode(curl_exec($fetchData), true); 
    curl_close($fetchData);
    return $data;
}

function getWorldTvGenreList() {
    $genreUrl = "stalker_portal/server/load.php?type=itv&action=get_genres";
    $data = getWorldTvData($genreUrl);
    return $data["js"];
}

function getWorldTvChannelList() {
    $channelListUrl = "stalker_portal/server/load.php?type=itv&action=get_all_channels";
    $data = getWorldTvData($channelListUrl);
    return $data["js"]["data"];
}

function getPlaylist($m3u) {
    global $file;
    $datas = getWorldTvChannelsInfo($m3u);
    $playlist = "#EXTM3U\n\n\n";
    foreach($datas as $data) {
        $playlist .= $data . "\n\n";
    }
    file_put_contents("$file.m3u", $playlist);
    echo "<center><span style='color: green; font-weight: bold; font-size: 16px;'>Successfully Saved Playlist. URL : https://yourdomian.com/yourscriptfolder/$file.m3u</span></center>";
}

function getWorldTvChannelsInfo($m3u = false) {
    global $domain, $logoUrl;
    $genreList = getWorldTvGenreList();
    $channelList = getWorldTvChannelList();
    $channelsInfo = array();
    foreach($channelList as $channel) {
        $logo = !empty($channel["logo"]) ? "https://$domain/stalker_portal/misc/logos/320/" . $channel["logo"] : $logoUrl; 
        $channelName = $channel["name"];
        $genreId = $channel["tv_genre_id"];
        $cmds = $channel["cmds"];
        $id = $cmds[0]["id"];
        $groupTitle = "";
        foreach($genreList as $gList) {
            if($genreId === $gList["id"]) {
                $groupTitle = $gList["title"]; break;
            }
        }
        if($m3u) {
            array_push($channelsInfo,
                "#EXTINF:-1 tvg-id=\"$id\" tvg-logo=\"$logo\" group-title=\"$groupTitle\",$channelName \n". (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://" . $_SERVER['HTTP_HOST'] . explode("?", $_SERVER['REQUEST_URI'])[0] ."?id=$id"
            );
        } else {    
            array_push($channelsInfo,[
                "tvg-id" => $id,
                "channel_name" => $channelName,
                "group-title" => $groupTitle,
                "tvg-logo" => $logo
            ]);
        }
    }
    if($m3u) {
        return $channelsInfo;
    }
    $contents = group_by("group-title", $channelsInfo);
    return json_encode($contents);
}

function getEPGDetails() {
    $url = "stalker_portal/server/load.php?type=itv&action=get_epg_info";
    $epg = getWorldTvData($url);
    $epg = $epg['js']['data'];
    if($epg) $epg["dateTime"] = new DateTime();
    return json_encode($epg);
}

function group_by($key, $data) {
    $result = array();
    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        } else {
            $result[""][] = $val;
        }
    }
    return $result;
}
?>
