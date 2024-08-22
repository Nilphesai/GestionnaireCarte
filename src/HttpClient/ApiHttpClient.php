<?php
namespace App\HttpClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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

    public function getCardsName(string $name): array{
        if($name){
            $response = $this->httpClient->request('GET','?fname='.$name, [
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