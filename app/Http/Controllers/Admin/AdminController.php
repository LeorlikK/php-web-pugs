<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Database\DB;
use DateTime;
use Views\View;

class AdminController extends Controller
{
    public function main():View
    {
        $result = DB::select("SELECT COUNT(id) FROM users", [])->fetch();
        $dateTime = new DateTime();
        $dateOneWeekAgo = $dateTime->modify('-1 week')->format('Y-m-d H:i:s');
        $dateTwoWeekAgo = $dateTime->modify('-2 week')->format('Y-m-d H:i:s');
        $result['date'] = DB::select("SELECT COUNT(id),(SELECT COUNT(id) FROM users WHERE created_at > ? AND created_at < ?) AS two_week
            FROM users WHERE created_at > ?", [$dateTwoWeekAgo, $dateOneWeekAgo, $dateOneWeekAgo])->fetch();
        $result['banned'] = DB::select("SELECT COUNT(id) FROM users WHERE banned = true", [])->fetch();

        $media = DB::select("SELECT * FROM media_size")->fetchAll();
        $result['image'] = array_values(array_filter($media, function ($item){
           return $item['name'] === 'image';
        }))[0]['size'];
        $result['video'] = array_values(array_filter($media, function ($item){
            return $item['name'] === 'video';
        }))[0]['size'];
        $result['audio'] = array_values(array_filter($media, function ($item){
            return $item['name'] === 'audio';
        }))[0]['size'];
        $result['sum'] = $result['image'] + $result['video'] + $result['audio'];
        $result['image_percent'] = 0;
        $result['video_percent'] = 0;
        $result['audio_percent'] = 0;
        if ($result['sum'] !== 0){
            if ($result['image'] !== 0){
                $result['image_percent']= $result['image']/$result['sum']*100;
            }
            if ($result['video'] !== 0){
                $result['video_percent'] = $result['video']/$result['sum']*100;
            }
            if ($result['audio'] !== 0){
                $result['audio_percent'] = $result['audio']/$result['sum']*100;
            }
        }

        $usersOneWeekAgo = $result['date']['count'];
        $usersTwoWeekAgo = $result['date']['two_week'];

//        var_dump($usersOneWeekAgo, $usersTwoWeekAgo);
//        exit();
        if ($usersTwoWeekAgo !== 0){
            $percentOneWeekTwoWeek = round((($usersOneWeekAgo - $usersTwoWeekAgo)/$usersTwoWeekAgo)*100, 2);
            if ($percentOneWeekTwoWeek > 0) $result['percentChangeArrow'] = 'plus';
            elseif($percentOneWeekTwoWeek < 0) $result['percentChangeArrow'] = 'minus';
            $result['percentChange'] = $percentOneWeekTwoWeek;
        }else{
            $result['percentChange'] = 0;
            $result['percentChangeArrow'] = 'null';
        }

        return new View('admin.main', ['result' => $result]);
    }
}