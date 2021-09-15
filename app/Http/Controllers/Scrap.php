<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Goutte\Client;
use App\Numbers;

set_time_limit(7200);

class Scrap extends Controller
{
    //07011715910
    protected $client;
    protected $links;
    protected $append = "https://us.soccerway.com";
    protected $myDate;

    public function __construct(){
        $this->client = new Client();

        $guzzleClient = new \GuzzleHttp\Client([
            'timeout' => 7200,
            'verify' => false,
            //'proxy' => 'https://125.162.36.87:56078',
        ]);
        $this->client->setClient($guzzleClient);

        $this->myDate = date('Y/m/d');
        //$this->myDate = "2019/05/04";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = array();
        $today = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)->where('Away_MP', '>', 4)->where(function($query){$query->where('Home_AGS', '>', 2)->orWhere('Home_AGC', '<', 0.6);})->get();

        return view('home')->with('today', $today);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
                //First crawl the "Soccerway" home-page.
                $crawler = $this->client->request('GET', 'https://us.soccerway.com');
                $crawler->filter('th[class="competition-link"] > a[href*="regular-season"]')->each(function($node){
                    //Try: $crawler->filter('th[class="competition-link"] > a:not(a[href*="group"], a[href*="final"], a[href*="cup"])')->each(function($node){
                    //$node->attr('href');

                    //get matches for each link
                    $compLink = $this->append.$node->attr('href');
                    $crawler1 = $this->client->request('GET', $compLink);
                    $crawler1->filter("a[href^=\"/matches/$this->myDate\"][href*=\"ICID\"]")->each(function ($node){
                        //i added this parameter into the filter which i believe directed to many irrelevant pages: , a[href^=\"/national/\"][href*=\"ICID\"]

                        //go to the match url
                        $matches = $this->append.$node->attr('href');
                        $matches = $this->client->request('GET', $matches);
                        $matches->filter("a[href^=\"/matches/$this->myDate\"][href$=\"head2head/\"]")->each(function($node){
    
                            //go to head2head page, get the data and save into database
                            $numbers = new Numbers;
    
                            $head2head = $this->append.$node->attr('href');
                            //database url
                            $numbers->URL = $head2head;
    
                            $head2head = $this->client->request('GET', $head2head);
                            $H_MP = $head2head->filterXPath('//*[@id="page_match_1_block_h2hsection_team_9_block_h2h_general_statistics_1"]/table/tbody/tr[2]/td[2]')->each(function($node){
                                //echo "Home team average matches played: ".$node->text()."<br>";
                                return trim($node->text());
                            });
                            $numbers->Home_MP = $H_MP[0];
    
                            $A_MP = $head2head->filterXPath('//*[@id="page_match_1_block_h2hsection_team_9_block_h2h_general_statistics_1"]/table/tbody/tr[2]/td[6]')->each(function($node){
                                //echo "Away team average matches played: ".$node->text()."<br>";
                                return trim($node->text());
                            }); 
                            $numbers->Away_MP = $A_MP[0];
    
                            $H_AGS = $head2head->filterXPath('//*[@id="page_match_1_block_h2hsection_team_9_block_h2h_general_statistics_1"]/table/tbody/tr[10]/td[2]')->each(function($node){
                                //echo "Home team average goals scored: ".$node->text()."<br>";
                                return trim($node->text());
                            });
                            $numbers->Home_AGS = $H_AGS[0];
    
                            $A_AGS = $head2head->filterXPath('//*[@id="page_match_1_block_h2hsection_team_9_block_h2h_general_statistics_1"]/table/tbody/tr[10]/td[6]')->each(function($node){
                                //echo "Away team average goals scored: ".$node->text()."<br>";
                                return trim($node->text());
                            });
                            $numbers->Away_AGS = $A_AGS[0];                            
    
                            $H_AGC = $head2head->filterXPath('//*[@id="page_match_1_block_h2hsection_team_9_block_h2h_general_statistics_1"]/table/tbody/tr[11]/td[2]')->each(function($node){
                                //echo "Home team average goals conceded: ".$node->text()."<br>";
                                return trim($node->text());
                            });
                            $numbers->Home_AGC = $H_AGC[0];
    
                            $A_AGC = $head2head->filterXPath('//*[@id="page_match_1_block_h2hsection_team_9_block_h2h_general_statistics_1"]/table/tbody/tr[11]/td[6]')->each(function($node){
                                //echo "Away team average goals conceded: ".$node->text()."<br>";
                                return trim($node->text());
                            });

                            $numbers->Away_AGC = $A_AGC[0];

                            $numbers->save();
    
                            //echo "<br> <br>";
    
                        });
                    });


                });
        
        
        
                return view('home');
    }

    public function win(){
        $todayWin = array();

        //->where('Home_AGS', '>', 2.3)
        $todayWin = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Home_AGS', '>', 2.3)->where('Home_AGC', '<=', 0.8)
        ->where('Away_AGS', '<=', 1.2)->where('Away_AGC', '>=', 1.5)
        ->get();

        $todayWin2 = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Away_AGS', '>', 2.3)->where('Away_AGC', '<=', 0.6)
        ->where('Home_AGS', '<=', 1.2)->where('Home_AGC', '>=', 1.5)
        ->get();

        return view('win')->with('todayWin', $todayWin)->with('todayWin2', $todayWin2);
    }

    public function btts(){
        $todayBTTS = array();

        $todayBTTS = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Home_AGS', '>=', 2)->where('Home_AGC', '>=', 1.2)
        ->where('Away_AGS', '>=', 1.5)->where('Away_AGC', '>=', 1.5)
        ->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->get();

        list($todayBTTS, $todayBTTSi) = $todayBTTS->partition(function($matches){
            return (($matches->Home_AGS - $matches->Away_AGS) < 1);
        });



        $todayBTTS2 = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)->where('Away_MP', '>', 4)
        ->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->where('Home_AGC', '<', function($query){return $query->select('Away_AGC');})
        ->where('Home_AGC', '>=', 1.5)
        ->where('Away_AGC', '>=', 1.5)
        ->get();

        //I used the code below to select only the matches that Home_AGS and Away_AGS is less than 
        //0.6, this is because the query itself couldn't check two columns at the same time. 
        //This is was the only solution online that i could find: https://laravel.com/docs/5.4/collections#method-partition
        list($todayBTTS2, $todayOver1i) = $todayBTTS2->partition(function($matches){
            return (($matches->Home_AGS - $matches->Away_AGS) < 0.7);
        });

        list ($todayBTTS2, $todayOver1i) = $todayBTTS2->partition(function($matches){
            return (($matches->Away_AGC - $matches->Home_AGC) < 1);
        });

        /*I used the function above to query the database because it expects a value from 
            the programmer.
        */

        return view('btts')->with('todayBTTS', $todayBTTS)->with('todayBTTS2', $todayBTTS2);
    }

    public function noBTTS(){
        $todayNoBTTS = array();

        $todayNoBTTS = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Home_AGC', '<', 0.5)
        ->where('Away_AGS', '<', 0.5)
        //->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->get();

        $todayNoBTTS2 = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Away_AGC', '<', 0.5)
        ->where('Home_AGS', '<', 0.7)
        //->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->get();

        //NOTE: This is also good for 1x
        return view('noBTTS')->with('todayNoBTTS', $todayNoBTTS)->with('todayNoBTTS2', $todayNoBTTS2);
    }

    public function over(){
        $todayOver = array();


        $todayOver = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Home_AGS', '>=', 2)->where('Home_AGC', '>=', 1.2)
        ->where('Away_AGS', '>=', 1.2)->where('Away_AGC', '>=', 2)
        ->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->get();
        
        
        $todayOver2 = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Away_AGS', '>=', 2)->where('Away_AGC', '>=', 1.2)
        ->where('Home_AGS', '>=', 1.2)->where('Home_AGC', '>=', 1.5)
        ->where('Away_AGS', '>', function($query){return $query->select('Home_AGS');})
        ->get();


        //NOTE THIS IS NOT UNDER BUT OVER 2.5
        $todayUnder2 = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->get();

        list($todayUnder2, $todayOver1i) = $todayUnder2->partition(function($matches){
            return (($matches->Home_AGS - $matches->Away_AGS) < 0.2);
        });

        list($todayUnder2, $todayOver1i) = $todayUnder2->partition(function($matches){
            return (($matches->Away_AGC - $matches->Home_AGC) < 0.2);
        });

        return view('over')->with('todayOver', $todayOver)->with('todayOver2', $todayOver2)->with('todayUnder2', $todayUnder2);
    }

    public function under(){
        $todayUnder = array();

        $todayUnder = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)
        ->where('Home_AGS', '<=', 1.3)->where('Home_AGC', '<=', 0.5)
        ->where('Away_AGS', '<', 1)->where('Away_AGC', '<=', 1)
        ->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->where('Away_AGC', '>=', function($query){return $query->select('Home_AGC');})
        ->get();

        

        

        /*
        //THIS IS FOR STRAIGHT WINNING BUT THE BATTERY IS GOING DOWN ANYTIME FORM NOW
        $todayOver1 = Numbers::whereDate('created_at', $this->myDate)->where('Home_MP', '>', 4)->where('Away_MP', '>', 4)
        ->where('Home_AGS', '>', function($query){return $query->select('Away_AGS');})
        ->where('Home_AGC', '<', function($query){return $query->select('Away_AGC');})
        ->where('Home_AGC', '<', 1)
        ->get();

        //I used the code below to select only the matches that Home_AGS and Away_AGS is less than 
        //0.6, this is because the query itself couldn't check two columns at the same time. 
        //This is was the only solution online that i could find: https://laravel.com/docs/5.4/collections#method-partition
        list($todayUnder2, $todayOver1i) = $todayOver1->partition(function($matches){
            return (($matches->Home_AGS - $matches->Away_AGS) > 2);
        });

        //list ($todayOver1, $todayUnder2) = $todayOver1->partition(function($matches){
            //return (($matches->Away_AGC - $matches->Home_AGC) > 1);
        //});
        */

        return view('under')->with('todayUnder', $todayUnder);
    }
    

}
