<?php

namespace app\controllers\site;

use app\core\Controller;
use app\core\Application;
use app\models\Products;
use Swoole\Http\Response;
use app\core\Request;

class SiteCartController extends Controller
{
  
  //POST
  public function submitCartData(Request $request, Response $response)
  {
    $response->header('Content-Type', 'application/json');
    
    $response->setStatusCode(200);
    
    $requestBody = Application::$app->request->getRequestBody()['body'];
    
    $response->end( $requestBody );
    //echo json_encode( Application::$app->request->getRequestBody() );
  }
}

?>
