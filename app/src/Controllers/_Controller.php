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
     * @param \App\$app                      $slim
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
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' Login triggered!');

        $requestData = $request->getParsedBody();

        if (!array_key_exists('password', $requestData) || !array_key_exists('ra', $requestData)){
            $responseBody = array('error' => 'true', 'description' => 'Must give field /ra/ and field /password/');
            return $response->write(json_encode($responseBody))
                            ->withStatus(400);
        } else if ($requestData['ra'] == '' || $requestData['password'] == '' )
            {
                $responseBody = array('error' => 'true', 'description' => 'ra and password must have userID and hashed password (sha-256) respectively!');                
                return $response->write(json_encode($responseBody))
                            ->withStatus(401);
            }

        $userGivenPassword = $requestData['password'];
        $userID = $requestData['ra'];
        $studentData = $this->dataaccess->get('alunos', array('ra' => $userID));

        if (!array_key_exists('senha', $studentData)){
            $responseBody = array('error' => 'true', 'description' => 'Something happened while retrieving data from db!');
            return $response->write(json_encode($responseBody))
                            ->withStatus(500);
        }

        
        if ($studentData['senha'] === $userGivenPassword){
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User authenticated!'); 

        $oAuthData = array('tableName' => 'oauth_clients', 'data' => array('client_id' => $userID, 'client_secret' => $userGivenPassword, 'grant_types' => 'client_credentials'));


        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' Attempt to insert user to oAuth client table!');  

        $isInserted = $this->dataaccess->add($oAuthData['tableName'], $oAuthData['data']);
            if (!$isInserted) { 
                $this->logger->notice(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' Client not inserted!');  
                $oAuthClient = $this->dataaccess->get('oauth_clients', array('client_id' => $userID));
                if ($oAuthClient){
                    $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' Client already exists!');  
                    if ($oAuthClient['client_secret'] != $userGivenPassword) {
                    // check and update the client password if is different
                    }                      
                } 
            }  else {
                $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' Client added successfully!');  
            }

            $data = "client_id=" . $userID .  "&client_secret=" . $userGivenPassword . "&grant_type=client_credentials" ;

            $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' Attempt to generate token!'); 
            $tokenRequest = $this->slim->subRequest('POST', '/oauth/token', '' , ['Content-Type' => 'application/x-www-form-urlencoded'], [], $data,  new \Slim\Http\Response()); 
            preg_match('({.*?})', ((string) $tokenRequest), $tokenData); 

            $tokenData = json_decode($tokenData[0], TRUE);
            $responseBody = array ('error' => false, 'description' => 'Successfully authenticated!','studentName' => $studentData['nome'] ,'access_token' => $tokenData['access_token'], 'expireshin' => $tokenData['expires_in'] ,'token_type' => $tokenData['token_type'], 'scope' => $tokenData['scope']); 

        } else {
            $responseBody = array ('error' => true, 'description' => 'Something bad happened');
            return $response->write(json_encode($responseBody))
                        ->withStatus(404);

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
    public function grade(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $requestData = $request->getParsedBody();

        if(!array_key_exists('ra', $requestData)){
            //check for ra in the request
            $responseBody = array('error' => 'true', 'description' => 'Invalid request, must give user_id!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(400);
        }

        //prepare $args
        $token = str_replace('Bearer ', '', $request->getServerParams()['HTTP_AUTHORIZATION']);

        $oAuthTokenData = $this->dataaccess->get('oauth_access_tokens', array('access_token' => $token));

        //check validity of token against the user_id
        if ($oAuthTokenData && $oAuthTokenData['client_id'] === $requestData['ra']){

        $studentGrade = $this->dataaccess->getJoin(
            array('joinON' =>'coddisciplina', 'historico' => array('ra', 'coddisciplina', 'semestre', 'ano', 'faltastot', 'notas1', 'notas2', 'media'), 'disciplinas' => array('disciplina')), array('ra' => $requestData['ra']));

        $semester = explode('/',date('n/Y', time()))[0] <= 6 ?'1' : '2';
        $ano = explode('/',date('n/Y', time()))[1];

         for ($i = 0;$i < count($studentGrade);$i++){
            if ($studentGrade[$i]['semestre'] && $studentGrade[$i]['ano']){
               if ($studentGrade[$i]['ano'] != $ano || $studentGrade[$i]['semestre'] != $semester){                
                unset($studentGrade[$i]);
                $studentGrade = array_values($studentGrade);
                $i--;
                continue;
               } 
            }            
        }

        $response->write(json_encode($studentGrade))
                 ->withStatus(200);

        } else if($oAuthTokenData && $oAuthTokenData['client_id'] != $requestData['ra']){
            
            $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $oAuthTokenData['client_id'].' \'s token was used in a attempt to get another user\'s grade (' . $requestData['ra'] . ').');

            $responseBody = array('error' => 'true', 'description' => 'This token belongs to another user, admin was reported!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(401);
        } else {
             $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $requestData['ra'] .' tried to request info using a invalid token.');

            $responseBody = array('error' => 'true', 'description' => 'This token is invalid or no longer exists.');

            return $response->write(json_encode($responseBody))
                            ->withStatus(440);
        }

    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function gradeSchedule(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $requestData = $request->getParsedBody();

        if(!array_key_exists('ra', $requestData)){
            //check for ra in the request
            $responseBody = array('error' => 'true', 'description' => 'Invalid request, must give user_id!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(400);
        }

        //prepare $args
        $token = str_replace('Bearer ', '', $request->getServerParams()['HTTP_AUTHORIZATION']);

        $oAuthTokenData = $this->dataaccess->get('oauth_access_tokens', array('access_token' => $token));

        //check validity of token against the user_id
        if ($oAuthTokenData && $oAuthTokenData['client_id'] === $requestData['ra']){

        $studentGrade = $this->dataaccess->getJoin(
            array('joinON' =>'coddisciplina', 'historico' => array('ra', 'coddisciplina', 'semestre', 'ano'), 'disciplinas' => array('disciplina')), array('ra' => $requestData['ra']));
        $semester = explode('/',date('n/Y', time()))[0] <= 6 ?'1' : '2';
        $ano = explode('/',date('n/Y', time()))[1];
        $studentDisciplines = array();

        for ($i = 0;$i < count($studentGrade);$i++){
            if ($studentGrade[$i]['semestre'] && $studentGrade[$i]['ano']){
               if ($studentGrade[$i]['ano'] != $ano || $studentGrade[$i]['semestre'] != $semester){                
                unset($studentGrade[$i]);
                $studentGrade = array_values($studentGrade);
                $i--;
                continue;
               } 
            array_push($studentDisciplines, array('coddisciplina' => $studentGrade[$i]['coddisciplina']));
            }            
        }

        $studentSchedule = $this->dataaccess->get(array('table' => 'horario', 'columns' => array('coddisciplina', 'horario', 'periododia', 'periodo as diadasemana', 'ano', 'semestre') ), $studentDisciplines, TRUE);


        for ($i = 0; $i < count($studentSchedule);$i++){    

            for ($j = 0;$j < count($studentGrade);$j++){
                if($studentGrade[$j]['coddisciplina'] == $studentSchedule[$i]['coddisciplina'])
                {
                $studentSchedule[$i] = array('disciplina' => $studentGrade[$j]['disciplina']) + $studentSchedule[$i];

                }
            }
        } 

        usort($studentSchedule, function ($a, $b) { return strnatcmp($a['coddisciplina'], $b['coddisciplina']); });          



        return $response->write(json_encode($studentSchedule))
                        ->withStatus(200);


        } else if($oAuthTokenData && $oAuthTokenData['client_id'] != $requestData['ra']){
            
            $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $oAuthTokenData['client_id'].' \'s token was used in a attempt to get another user\'s grade (' . $requestData['ra'] . ').');

            $responseBody = array('error' => 'true', 'description' => 'This token belongs to another user, admin was reported!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(401);
        } else {
             $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $requestData['ra'] .' tried to request info using a invalid token.');

            $responseBody = array('error' => 'true', 'description' => 'This token is invalid or no longer exists.');

            return $response->write(json_encode($responseBody))
                            ->withStatus(440);
        }

    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function files(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $requestData = $request->getParsedBody();

        if(!array_key_exists('ra', $requestData)){
            //check for ra in the request
            $responseBody = array('error' => 'true', 'description' => 'Invalid request, must give user_id!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(400);
        }

        //prepare $args
        $token = str_replace('Bearer ', '', $request->getServerParams()['HTTP_AUTHORIZATION']);

        $oAuthTokenData = $this->dataaccess->get('oauth_access_tokens', array('access_token' => $token));

        //check validity of token against the user_id
        if ($oAuthTokenData && $oAuthTokenData['client_id'] === $requestData['ra']){

        $studentGrade = $this->dataaccess->getJoin(
            array('joinON' =>'coddisciplina', 'historico' => array('ra', 'coddisciplina', 'semestre', 'ano'), 'disciplinas' => array('disciplina')), array('ra' => $requestData['ra']));
        $semester = explode('/',date('n/Y', time()))[0] <= 6 ?'1' : '2';
        $ano = explode('/',date('n/Y', time()))[1];

        $whereClause = array();

        for ($i = 0;$i < count($studentGrade);$i++){
            if ($studentGrade[$i]['semestre'] && $studentGrade[$i]['ano']){
               if ($studentGrade[$i]['ano'] != $ano || $studentGrade[$i]['semestre'] != $semester){                
                unset($studentGrade[$i]);
                $studentGrade = array_values($studentGrade);
                $i--;
                continue;
               } 
            array_push($whereClause, array('coddisciplina' => $studentGrade[$i]['coddisciplina']));
            }            
        }
        array_push($whereClause, array('status' => 1));

        $studentFiles = $this->dataaccess->getJoin(
            array('joinON' =>'id_pasta', 'intranet_pastas' => array('pasta', 'coddisciplina'), 'intranet_arquivos' => array('arquivo')), $whereClause);


        // for ($i = 0; $i < count($studentFiles);$i++){   

        //     for ($j = 0;$j < count($studentGrade);$j++){
        //         if($studentGrade[$j]['coddisciplina'] == $studentFiles[$i]['coddisciplina'])
        //         {
        //         $studentFiles[$i] = array('disciplina' => $studentGrade[$j]['disciplina']) + $studentFiles[$i];
        //         break;

        //         }
        //     }
        // } 

        for ($i = 0; $i < count($studentFiles);$i++){ 
            for ($j = 0;$j < count($studentGrade);$j++){
                if($studentGrade[$j]['coddisciplina'] == $studentFiles[$i]['coddisciplina'])
                {
                $tempFiles = array();
                for ($y = 0; $y < count($studentFiles);$y++){
                if ($studentFiles[$y]['coddisciplina'] == $studentFiles[$i]['coddisciplina'])
                    $tempFiles[] = $studentFiles[$y]['arquivo'];
                }
                $files[] = array('disciplina' => $studentGrade[$j]['disciplina'], 'pasta' => $studentFiles[$i]['pasta'], 'arquivos' => $tempFiles);

                break;
                }
            }
        } 


        return $response->write(json_encode(array_values(array_unique($files, SORT_REGULAR))))
                        ->withStatus(200);


        } else if($oAuthTokenData && $oAuthTokenData['client_id'] != $requestData['ra']){
            
            $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $oAuthTokenData['client_id'].' \'s token was used in a attempt to get another user\'s grade (' . $requestData['ra'] . ').');

            $responseBody = array('error' => 'true', 'description' => 'This token belongs to another user, admin was reported!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(401);
        } else {
             $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $requestData['ra'] .' tried to request info using a invalid token.');

            $responseBody = array('error' => 'true', 'description' => 'This token is invalid or no longer exists.');

            return $response->write(json_encode($responseBody))
                            ->withStatus(440);
        }

    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function changePassword(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $requestData = $request->getParsedBody();

        if(!array_key_exists('ra', $requestData)){
            //check for ra in the request
            $responseBody = array('error' => 'true', 'description' => 'Invalid request, must give user_id!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(400);
        }

        //prepare $args
        $token = str_replace('Bearer ', '', $request->getServerParams()['HTTP_AUTHORIZATION']);

        $oAuthTokenData = $this->dataaccess->get('oauth_access_tokens', array('access_token' => $token));

        //check validity of token against the user_id
        if ($oAuthTokenData && $oAuthTokenData['client_id'] === $requestData['ra']){


            $studentData = $this->dataaccess->get('alunos', array('ra' => $requestData['ra']));

            if(($requestData['rg'] && $requestData['rg'] === $studentData['rg']) && ($requestData['datanascimento'] && $requestData['datanascimento'] === date('d/m/Y', strtotime($studentData['datanascimento'])))){
                
                if($requestData['password'] === $requestData['newpassword'])
                {
                    //all correct, change the password
                    $isupdated = $this->dataaccess->update('alunos', array('ra' => $requestData['ra']), array('senha' => $requestData['password'])) && $this->dataaccess->update('oauth_clients', array('client_id' => $requestData['ra']), array('client_secret' => $requestData['password']));
                    // 

                    if ($isupdated) {
                        return $response->write(json_encode(array('error' => 'false', 'description' => 'Password updated successfully!')))
                                        ->withStatus(200);
                    } else {

                        return $response->write(json_encode(array('error' => 'true', 'description' => 'Password not updated!')))
                                        ->withStatus(200);
                    }
                    // $oAuthUpdatePassword = $this->dataaccess->update('alunos', array('ra' => $userID));
                } else {
                    return $response->write(json_encode(array('error' => 'true', 'description' => 'Password and new password must match!')))
                                        ->withStatus(200);
                }              
            } else if(($requestData['rg'] && $requestData['rg'] != $studentData['rg']) || ($requestData['datanascimento'] && $requestData['datanascimento'] != date('d/m/Y', strtotime($studentData['datanascimento'])))){
                 return $response->write(json_encode(array('error' => 'true', 'description' => 'RG and/or birth date doesn\'t match.'), JSON_UNESCAPED_SLASHES))
                                        ->withStatus(200);
            }   

            else if(!$requestData['rg'] || !$requestData['datanascimento']){
                 return $response->write(json_encode(array('error' => 'true', 'description' => 'No RG and/or birth date provided!'), JSON_UNESCAPED_SLASHES))
                                        ->withStatus(200);
            }    



        } else if($oAuthTokenData && $oAuthTokenData['client_id'] != $requestData['ra']){
            
            $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $oAuthTokenData['client_id'].' \'s token was used in a attempt to get another user\'s grade (' . $requestData['ra'] . ').');

            $responseBody = array('error' => 'true', 'description' => 'This token belongs to another user, admin was reported!');

            return $response->write(json_encode($responseBody))
                            ->withStatus(401);
        } else {
             $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__. ' User_ID '. $requestData['ra'] .' tried to request info using a invalid token.');

            $responseBody = array('error' => 'true', 'description' => 'This token is invalid or no longer exists.');

            return $response->write(json_encode($responseBody))
                            ->withStatus(440);
        }

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
    public function update(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];
        $requestData = $request->getParsedBody();

        $isupdated = $this->dataaccess->update($path, $args, $requestData);
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
