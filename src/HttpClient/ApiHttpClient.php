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
        
            $response = $this->httpClient->request('GET','?fname=Dark Magician', [
                'verify_peer' => false,
            ]);
            return $response->toArray();
    }

    public function getCardsName(Array $searchedCard): array{
        if($searchedCard){
            $name = "fname=".$searchedCard[0];
            $attribute = "attribute=".$searchedCard[1];
            $level = "level=".$searchedCard[2];
            $race = "race=".$searchedCard[3];
            $att = "atk=".$searchedCard[4];
            $def = "def=".$searchedCard[5];
            $link = "link=".$searchedCard[6];
            $scale = "scale=".$searchedCard[7];
            $linkmarker = "linkmarker=".$searchedCard[8];

            $search ="";

            if($searchedCard[0]){
                $search .= $name."&"; 
            }
            if ($searchedCard[1]){
                $search .= $attribute."&"; 
            }
            if($searchedCard[2]){
                $search .= $level."&"; 
            }
            if($searchedCard[3]){
                $search .= $race."&"; 
            }
            if($searchedCard[4] || $searchedCard[4] == 0){
                $search .= $att."&"; 
            }
            if($searchedCard[5] || $searchedCard[5] == 0){
                $search .= $def."&"; 
            }
            if($searchedCard[6]){
                $search .= $link."&"; 
            }
            if($searchedCard[7]){
                $search .= $scale."&"; 
            }
            if($searchedCard[8]){
                $search .= $linkmarker."&"; 
            }
            $response = $this->httpClient->request('GET','?'.$search."sort=name", [
                'verify_peer' => false,
            ]);
            return $response->toArray();    
        }
        else{
            $response = $this->httpClient->request('GET','?fname=Dark Magician', [
                'verify_peer' => false,
            ]);
            return $response->toArray();
        }
    }

    
}