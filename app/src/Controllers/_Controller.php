<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\DataAccess\_DataAccess;

/**
 * Class _Controller.
 */
class _Controller
{      

     private $slim;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \App\DataAccess
     */
    protected $dataaccess;

    /**
     * @param \Psr\Log\LoggerInterface       $logger
     * @param \App\DataAccess                $dataaccess
     */
    public function __construct(LoggerInterface $logger, _DataAccess $dataaccess, $slim)
    {       
        $this->logger = $logger;
        $this->dataaccess = $dataaccess;
        $this->slim = $slim;
    }



    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $request_data = $request->getParsedBody();

        $userGivenPassword = $request_data['password'];
        $userID = $request_data['ra'];

        $result = $this->dataaccess->get('alunos', array('ra' => $userID));

        
        if ($result['senha'] == $userGivenPassword){


            $oAuthData = array('tableName' => 'oauth_clients', 'data' => array('client_id' => '141b22', 'client_secret' => 'f3fec09ff9a7b5133bb7307261e50b779db63164774fd1f0e3f7ba9fc08d0436', 'grant_types' => 'client_credentials', 'redirect_uri' => 'http://fatecapi.tk/public'));




            $isInserted = $this->dataaccess->add($oAuthData['tableName'], $oAuthData['data']);

            if (!$isInserted) { 
                $oAuthClient = $this->dataaccess->get('oauth_clients', array('client_id' => $userID));
                if ($oAuthClient){
                    if ($oAuthClient['client_secret'] != $userGivenPassword) {
                    // check and update the client password if is different
                    } 
                     
                } 
            }  

            $data = "client_id=" . $userID .  "&client_secret=" . $userGivenPassword . "&grant_type=client_credentials" ;

            $token = $this->slim->subRequest('POST', '/oauth/token', '' , ['Content-Type' => 'application/x-www-form-urlencoded'], [], $data,  new \Slim\Http\Response());
                    $token = $token->getBody();





        $responseBody = array ('error' => false, 'test' => 'successfully logged!');           


        } else {
            $responseBody = array ('error' => true, 'test' => 'Something bad happened');

        } 

        return $response->write(json_encode($responseBody))
                        ->withStatus(201);
    }



    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAll(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        
        $path = explode('/', $request->getUri()->getPath());

        $arrparams = $request->getParams();

		return $response->write(json_encode($this->dataaccess->getAll($path[0], $arrparams)));
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath());

        $result = $this->dataaccess->get($path, $args);
        if ($result == null) {
            return $response ->withStatus(404);
        } else {
            return $response->write(json_encode($result));
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function add(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $oAuthData = array('tableName' => 'oauth_clients', 'data' => array('client_id' => '141b22', 'client_secret' => 'f3fec09ff9a7b5133bb7307261e50b779db63164774fd1f0e3f7ba9fc08d0436', 'grant_types' => 'client_credentials', 'redirect_uri' => 'http://fatecapi.tk/public'));

            $last_inserted_id = $this->dataaccess->add($oAuthData['tableName'], $oAuthData['data']);
        if ($last_inserted_id > 0) {
            $RequesPort = '';
		    if ($request->getUri()->getPort()!='')
		    {
		        $RequesPort = '.'.$request->getUri()->getPort();
		    }
            $LocationHeader = $request->getUri()->getScheme().'://'.$request->getUri()->getHost().$RequesPort.$request->getUri()->getPath().'/'.$last_inserted_id;

            return $response ->withHeader('Location', $LocationHeader)
                             ->withStatus(201);
        } else {
            return $response ->withStatus(403);
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];
        $request_data = $request->getParsedBody();

        $isupdated = $this->dataaccess->update($path, $args, $request_data);
        if ($isupdated) {
            return $response ->withStatus(200);
        } else {
            return $response ->withStatus(404);
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];

        $isdeleted = $this->dataaccess->delete($path, $args);
        if ($isdeleted) {
            return $response ->withStatus(204);
        } else {
            return $response ->withStatus(404);
        }
    }
}
