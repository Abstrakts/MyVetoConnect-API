<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->headers->set("Access-Control-Allow-Origin", $_ENV['ALLOW_ORIGIN']);
        $response->headers->set("Access-Control-Allow-Headers", $_ENV['ALLOW_ORIGIN']);
    }

    // Cette fonction intercèpe la requête, vérifie si c'est du json et ensuite décode le contenu des données.
    public function onKernelRequest(RequestEvent $event)
    {
      $request = $event->getRequest();
      if (!$this->isApi($request)) {
        return;
      }
      if ($request->isMethod('OPTIONS')) {
        $event->setResponse(new JsonResponse(true));
      }
      if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
      }
    }

    //Cette fonction intercèpe la requête, vérifie si c'est une exception et renvoie le code et le message de l'exception
    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest();

        if (!$this->isApi($request)) {
            return;
        }
        $exception = $event->getThrowable();


        if ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $event->setResponse($response);
        }
    }

    private function isApi(Request $request)// Intercèpe l'URL et vérifie s'il y a /api dans l'URL
    {
        return strpos($request->getRequestUri(), '/api') === 0;
    }
}