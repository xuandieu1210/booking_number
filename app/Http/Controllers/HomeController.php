<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrentStatus;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getNextNumber()
    {
        $curent_date = date('Y-m-d');
        $date = $this->getCheckDate();
        $date->current_get ++;
        // $date->current_serve = ++ $date->current_serve ;
        $date->current_date = $curent_date ;

        $date->save();
        $newNumber =  CurrentStatus::latest('updated_at')->first();
        $endpoint = env('NODE_URL')."print";
        $client = new Client();
        //dd($newNumber);
        $response = $client->request('GET', $endpoint, ['query' => [
            'current' => str_pad($newNumber->current_serve, 3, '0', STR_PAD_LEFT),
            'number' => str_pad($newNumber->current_get, 3, '0', STR_PAD_LEFT),
        ]]);


        return $newNumber;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function callNumber()
    {
        $curent_date = date('Y-m-d');
        $date = $this->getCheckDate();
        if ($date->current_get <= $date->current_serve && $date->current_get != 0) {
            return 0;
        }
        // $date->current_get = ++ $date->current_get ;
        if ($date->current_get != 0) {
            $date->current_serve ++ ;
            $date->current_date = $curent_date ;
    
            $date->save();
        }


        $newNumber =  CurrentStatus::latest('updated_at')->first();

        $endpoint =  env('NODE_URL')."call";
        $client = new Client();

        $response = $client->request('GET', $endpoint, ['query' => [
                'number' => str_pad($newNumber->current_serve, 3, '0', STR_PAD_LEFT),
        ]]);

        return $newNumber;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function repeatNumber()
    {
        $newNumber = $this->getCheckDate();
        // $newNumber =  CurrentStatus::latest('updated_at')->first();
        if ($newNumber->current_get < $newNumber->current_serve) {
            return 0;
        }

        $endpoint =  env('NODE_URL')."call"; 
        $client = new Client();

        $response = $client->request('GET', $endpoint, ['query' => [
                'number' => str_pad($newNumber->current_serve, 3, '0', STR_PAD_LEFT),
        ]]);

        return $this->getCheckDate();
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCheckDate()
    {
        $curent_date = date('Y-m-d');
        $date = CurrentStatus::latest('updated_at')->first();
        if ($date !=  null) {
            if ($curent_date ==  $date->current_date->format('Y-m-d')) {
            
                return $date;
            }
        }
        
        $curent_status = new CurrentStatus;
        $curent_status->current_get = 0;
        $curent_status->current_serve = 0;
        $curent_status->current_date = $curent_date ;
        $curent_status->save();
        return CurrentStatus::latest('updated_at')->first();
        
    }

}
