# Laravel(?) API Responses
This package is intend to help create standardized JSON responses using Laravel | PHP applications.

### Example Response Structure

```JSON
{
    "message": "User retrieved!",
    "data": {
        "Name": "Jhon Doe"
    },
    "status": {
        "code": 200,
        "message": "OK"
    },
    "meta": {
        "timestamp": "2024-09-21 14:36:24"
    }
}
```

## Instalation
### Requirements
 - PHP 8.3+

 Install with composer:  
 ```bash
 composer require athosbenther/laravelapiresponses
 ```

 ## Usage

 Add the trait `ApiResponses` to your class and use it to generate default responses. Or create a new `ApiResponse` directly.

 The return class `ApiResponse` inherits from `Symfony\Component\HttpFoundation\Response`, and can be further configured using it's functions.

 ### Using the Trait

 ```php
 <?php

use AthosBenther\LaravelApiResponses\Traits\ApiResponses;

class UserController
{
    use ApiResponses;

    public function getUser(Request $request, $id)
    {
        $user = User::find($id);

        return $this->Response(
            'User retrieved',
            $user,
            200
        );
    }

    public function addUser(AddUserRequest $request)
    {
        $user = new User($request->all());



        return $this->Response(
            'User created',
            $user,
            201
        );
    }
}
 ```

 ### Using the Class

 ```php
 <?php

use AthosBenther\LaravelApiResponses\ApiResponse;

class UserController
{
    use ApiResponses;

    public function getUser(Request $request, $id)
    {
        $user = User::find($id);

        return new ApiResponse(
            'User retrieved',
            $user,
            200
        );
    }

    public function addUser(AddUserRequest $request)
    {
        $user = new User($request->all());



        return new ApiResponse(
            'User created',
            $user,
            201
        );
    }
}
 ```