<?php
namespace App\HttpClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

class ApiHttpClient extends AbstractController{
    private $httpclient;

    public function __construct(HttpClientInterface $jph){
        $this->httpClient = $jph;
    }

    public function getCards(){
        
            $response = $this->httpClient->request('GET','?num=15&offset=0', [
                'verify_peer' => false,
            ]);
            return $response->toArray();
    }

    public function getCardsById(string $id):array{
        if($id){
            $response = $this->httpClient->request('GET','?id='.$id, [
                'verify_peer' => false,
            ]);
            return $response->toArray();
        }
        else{
            $response = $this->httpClient->request('GET','?num=15&offset=0', [
                'verify_peer' => false,
            ]);
            return $response->toArray();
        }
    }

    public function getCardsByFilter(Array $searchedCard): array{
        if($searchedCard){
            
            $name = "fname=".$searchedCard[0];
            $type = "type=".$searchedCard[1];
            $attribute = "attribute=".$searchedCard[2];
            $level = "level=".$searchedCard[3];
            $race = "race=".$searchedCard[4];
            $att = "atk=".$searchedCard[5];
            $def = "def=".$searchedCard[6];
            $link = "link=".$searchedCard[7];
            $scale = "scale=".$searchedCard[8];
            $linkmarker = "linkmarker=".$searchedCard[9];

            $search ="";

            if($searchedCard[0]){
                $search .= $name."&"; 
            }
            if ($searchedCard[1]){
                $search .= $type."&"; 
            }
            if ($searchedCard[2]){
                $search .= $attribute."&"; 
            }
            if($searchedCard[3]){
                $search .= $level."&"; 
            }
            if($searchedCard[4]){
                $search .= $race."&"; 
            }
            if($searchedCard[5] || $searchedCard[5] == 0){
                $search .= $att."&"; 
            }
            if($searchedCard[6] || $searchedCard[6] == 0){
                $search .= $def."&"; 
            }
            if($searchedCard[7]){
                $search .= $link."&"; 
            }
            if($searchedCard[8]){
                $search .= $scale."&"; 
            }
            if($searchedCard[9]){
                $search .= $linkmarker."&"; 
            }
            
            $response = $this->httpClient->request('GET','?'.$search."sort=name&num=15&offset=0", [
                'verify_peer' => false,
            ]);
            
            return $response->toArray();    
        }
        else{
            $response = $this->httpClient->request('GET','?num=15&offset=0', [
                'verify_peer' => false,
            ]);
            return $response->toArray();
        }
    }

    public function getCardsByUrl($url): array{
        $response = $this->httpClient->request('GET',$url, [
            'verify_peer' => false,
        ]);
        
        return $response->toArray();   

    }

    
}