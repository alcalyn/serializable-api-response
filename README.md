# Serializable Api Response

If you use Symfony Http foundation and JMS Serializer,
this library allows to return raw objects and status code as a controller response
so that it will be serialized later by serializer and converted to a Symfony Response.

**The problem**: Symfony converts response content to string if we use Symfony Response,
or if we return a raw object and use a filter listener, then we can't pass status code.


## Installation

Install via composer

``` js
{
    "require": {
        "alcalyn/serializable-api-response": "~1.0"
    }
}
```


## Usage

Return a non-yet-serialized response in your controller:

``` php
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Alcalyn\SerializableApiResponse\ApiResponse;
use Acme\UserApi\Exception\UserAlreadyExistsException;

class UserController
{
    /**
     * @param Request $request
     *
     * @return ApiResponse
     *
     * @throws ConflictHttpException if username already exists.
     */
    public function registerUserAction(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        try {
            $user = $this->userManager->createUser($username, $password);
        } catch (UserAlreadyExistsException $e) {
            throw new ConflictHttpException('An user with username "'.$username.'" already exists.', $e);
        }

        return new ApiResponse($user, Response::HTTP_CREATED);
    }
}
```

Register ApiResponse Filter Listener:

In Silex:

``` php
use Symfony\Component\HttpKernel\KernelEvents;
use Alcalyn\SerializableApiResponse\ApiResponseFilter;

// Register reponse filter as a service
$this['acme.listener.api_response_filter'] = function () {
    $serializer = $this['serializer']; // Assuming your serializer service has this name

    return new ApiResponseFilter($serializer);
};

// Listen Kernel response to convert ApiResponse with raw object to Symfony Response with serialized data
$this->on(KernelEvents::VIEW, function ($event) {
    $this['acme.listener.api_response_filter']->onKernelView($event);
});
```


## License

This project is under [MIT Lisense](LICENSE)
