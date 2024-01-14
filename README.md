# Elemental

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Elemental is a PHP framework developed from scratch for dynamic, user-friendly coding experiences. It incorporates various features  such as Dependency Injection  and follows the MVC architecture to streamline web development and improve code organization. Engineered with a passion for simplicity and flexibility, Elemental opens the doors to a realm where coding meets unparalleled control.


## Features:

- [Powerful Dependency Injection Container ](#dependency-injection-container)
- [Fast Routing Engine](#routing)
- [Route Model Binding](#route-model-binding)
- [Inbuilt Object Relational Mapper (ORM)](#object-relational-mapper-model)
- [Command Line Engine - Candle](#candle-command-line-engine)
- [View Composition with Components and Layouts](#views)
- [Middlewares](#middleware)
- [Custom Exception Handling](#exception-handler)
- [MVC Architecture](#controllers)
- [Facades](#facades)
- [No External Dependency](#why-choose-elemental)



## Demo - Inkwell

To showcase the capabilities of Elemental, a fully working platform called **[Inkwell](https://inkwell.anees.dev)** has been developed using Elemental. Inkwell is a unique space dedicated to the pure essence of storytelling. In line with Elemental's goal of having no external dependencies, Inkwell has been crafted using plain HTML, CSS, JS, and PHP only.

- **Explore the demo: [Inkwell](https://inkwell.anees.dev)**
- **GitHub Repository: [Inkwell GitHub](https://github.com/AneesMuzzafer/Inkwell)**

Feel free to delve into both the live platform and the corresponding codebase. Explore Inkwell's features to understand how Elemental can be harnessed for your own projects.

See the [inspiration](#inspiration) behind the creation of **Elemental**.

## Documentation:

 1. [Getting Started](#getting-started)
 2. [Dependency Injection Container](#dependency-injection-container)
 3. [Routing](#routing)
 4. [Controllers](#controllers)
 5. [Request](#request)
 6. [Responses](#responses)
 7. [Middleware](#middleware)
 8. [Views, Layouts and Components](#views)
 9. [Database](#database)
 10. [Models (ORM)](#object-relational-mapper-model)
 11. [Candle](#candle-command-line-engine)
 12. [Helpers](#helpers)
 13. [Exception Handler](#exception-handler)
 14. [Configuration](#configuration)
 15. [Facades](#facades)


## Getting Started

### Why Choose Elemental?

Elemental has been designed with the aim of having no strings attached. There are no dependencies on external libraries or frameworks. The aim is to give the developers a genuine sense of control—an invitation to independently explore and understand the magical stuff that powers the framework.

The goal? Let developers fully embrace and leverage the elegance of powerful abstractions like DI containers, ORMs, Middlewares, and more. But here's the kicker—Elemental is not just pointing the way. It is handing you the keys to unravel the mysteries, empowering you to explore how these abstractions are laid out in the code. Because coding shouldn't be a maze; it should be a journey. Let's make that journey together. 🚀

### Creating Your First Elemental App

Unlike other frameworks, Elemental doesn't rely on Composer or external libraries. It's as simple as cloning the repository and getting started with good ol' PHP installed on your system.

#### Cloning the Repository

Open your terminal and execute the following command:

```bash
git clone https://github.com/aneesmuzzafer/elemental.git
```

No worries about package managers or dependencies – Elemental is built from scratch to free you from such concerns.

#### Alternatively, via Composer

For those who prefer the Composer route, creating a new Elemental app is just a command away:

```bash
composer create-project elemental/elemental sample-app
```

This will generate a project complete with a `composer.json` file.

Once your project is ready, kickstart the Elemental local development server with the `ignite` command using our command line engine, *Candle*:

```bash
cd sample-app

php candle ignite
```

Voila! Your application is now accessible at [http://127.0.0.1:8000](http://127.0.0.1:8000/).

We've taken care of the basic setup so you can focus on the magic.

> **Let the enchantment begin!**


## Dependency Injection Container

The most important feature of the Elemental is it's Dependency Injection Container which it uses for managing class dependencies and performing dependency injection.

Dependency Injection is a design pattern in software development that deals with how components get hold of their dependencies. In a traditional system, a class is responsible for creating its own dependencies. With DI, the responsibility of creating and providing dependencies is moved outside the class. Instead of a class creating its dependencies, they are "injected" into the class from an external source.

DI helps in achieving loosely coupled and more maintainable code. It promotes the separation of concerns by allowing each class to focus on its specific functionality without worrying about how to create or obtain its dependencies.

Dependency Injection is a specific implementation of the broader concept known as Inversion of Control (IoC). IoC represents a design paradigm where the control flow of a program is inverted or handed over to an external entity, container or framework.

### Automatic Dependency Resolution

In Elemental, when you're using dependency injection (DI), if a class doesn't rely on any other classes or only relies on concrete classes (not abstract interfaces), you don't need to explicitly tell the DI container how to create an instance of that class. The DI container will automatically figure it out.

The container will attempt to create an instance of the class, and if that class has dependencies on other classes, the container will recursively try to resolve those dependencies as well. This process continues until all the necessary classes are successfully resolved. So, you don't have to manually specify how to create each class – the DI container takes care of it for you.

```php
<?php

class MailService {
    public function __construct(private MailerAgent $mailer) {
    }
}

// Inside some other class

class UserController {
    public function sendMail(MailService $mailService)
    {
        $mailService->sendMail();
    }
}

```
Here, by type-hinting the `MailService` inside the method argument, Elemental was able to resolve the class and create an instance of this class and pass it to the `sendMail` so that you can use it without worrying about what dependencies are required by the `MailService` class. As you can see, the `MailService` itself depends upon some other class `MailerAgent` , however, Elemental took care of resolving the `MailerAgent` class behind the scenes, passed that to the `MailService` while creating it's instance and provided that instance for your use.

"So, where will this sort of injecting dependencies just by type-hinting the class name work in Elemental?"
All the class `constructor` functions, all the `controller methods`, and the `handle` method of the command creation class.

### Binding

Behind the scenes, Elemental resolves a class or interface into a concrete instance by looking at any bindings that have been registered. In other words, in order to explicitly tell the framework as to how to resolve the instance of a particular class or interface, you would need to register a binding of that class or interface using the `bind` method on the `Application` instance, passing the class or interface name that we wish to register along with a closure that returns an instance of the class:

```php
app()->bind(MailService::class, function () {
    // Run some logic, for example, decide on the mail agent to be passed to its constructor depending on some factors.
    return new MailService(app()->make(MailAgent::class));
});

```

> Note that you will typically need to bind a class only when you need
> to run some additional logic for resolving a class, or you need to
> bind an interface to a concrete implementation. Otherwise, Elemental
> will resolve the class without explicitly requiring you to bind it.

#### Binding a Singleton

The `singleton` method binds a class or interface with the container, ensuring that it is resolved only once. After the initial resolution, any subsequent calls to the container for the same binding will return the same object instance.

```php
app()->singleton(DatabaseConnection::class, function () {
    return new DatabaseConnection('localhost', 'username', 'password');
});

// Later in the code
$databaseConnection1 = app()->make(DatabaseConnection::class);
$databaseConnection2 = app()->make(DatabaseConnection::class);

// $databaseConnection1 and $databaseConnection2 will reference the same instance

```

### App Service Provider

While it's perfectly fine to register a binding anywhere in the app, it's often required to bind it while the application is bootstrapping, so that other components of the app can start using it. Elemental provides a special place to register all the bindings of the app and perform any other bootstrapping logic required by your application. This is `App\Bootstrap\AppServiceProvider`. The App Service Provider contain a `register` and a `boot` method.

#### Register Method
Within the `register` method, you should bind things into the Dependency Injection Container. However, you should not try to resolve any binding. routes, or run any other piece of functionality within the `register` method. Otherwise, you may accidentally use a service inside a container which has not loaded yet.

#### Boot Method
This method is called after all other service providers have been registered, granting access to all services registered by the framework. Any initialization logic you wish to execute should be placed here.

```php
<?php

namespace App\Bootstrap;

use App\Services\Auth;

class AppServiceProvider
{
    public function register(): void
    {
        app()->singleton(Auth::class, function () {
            return new Auth();
        });
    }

    public function boot(): void
    {
        // Additional initialization logic can be placed here
    }
}

```
### Resolving a class

You may use the `make` method to resolve a class instance from the DI container. The `make` method on the application instance accepts the name of the class or interface you wish to resolve:

```php
use App\Services\MailService;

$mailService=  app()->make(MailService::class);
```
You may also get the application instance using the static method `instance` directly on the `Application` class.

```php
use Core\Main\Application;
use App\Services\MailService;

$mailService =  Application::instance()->make(MailService::class);
```

The `make` method is particularly useful when attempting to resolve a class from within a code component where it's impractical to inject a dependency using type-hinting. In such scenarios, you can explicitly request the application's Dependency Injection Container to resolve an instance for you.




## Routing

Routes are defined in the `app\routes.php` file, allowing developers to easily register various routes to handle different HTTP requests.

### Route Registration

Routes are registered by invoking the relevant method on the Route Facade, such as `Route::get()`, and involve specifying a URI pattern as the first argument. The second argument can either be a closure or an array that defines the controller and method responsible for handling the request.

For instance:

```php
<?php

use App\Controllers\AuthController;
use Core\Facade\Route;

Route::get("/settings", function () {
    // handling logic goes here
});

Route::post("/register", [AuthController::class, "register"]);
```

Whenever a request URI is matched, the corresponding closure or controller method is executed, and a response is generated and sent back to the browser.

#### Available Router Methods

You can register routes that respond to any HTTP verb using the following methods:
- `Route::get($uri, $callback);`
- `Route::post($uri, $callback);`
- `Route::put($uri, $callback);`
- `Route::patch($uri, $callback);`
- `Route::delete($uri, $callback);`
- `Route::options($uri, $callback);`

#### Route Parameters

Sometimes you will need to capture segments of the URI within your route. For example, you may need to capture a user's ID from the URL. You may do so by defining route parameters:

```php
Route::get('/user/{id}', function (string $id) {
    return 'User ' . $id;
});

Route::get("/story/{id}", function ($id) {/*...*/});
```

You may define as many route parameters as required by your route:

```php

Route::post("story/edit/{id}", [StoryController::class, "edit"]);

Route::get("story/{story_id}/comment/{comment_id}", [StoryController::class, "comment"]);
```

These will be passed into the controller method as well.

#### Dependency Injection

Elemental seamlessly handles the injection of necessary dependencies for your controller methods. This allows you to specify any dependencies required by your route in the callback signature using type-hinting. Elemental takes care of automatically resolving and injecting the declared dependencies into the callback.

For instance, if you type-hint `Core\Request\Request` within the callback, Elemental ensures that the current HTTP request is automatically injected into your route callback:

```php
<?php

use Core\Request\Request;

Route::get('/users', function (Request $request) {
    // ...
});
```

You can put the typed dependencies and route parameters in any order.

#### Route Model Binding

When you pass a model ID as a parameter to a route or controller action, the typical approach involves querying the database to fetch the corresponding model based on that ID. Elemental simplifies this process through route model binding, offering a convenient way to automatically inject model instances directly into your routes.

For instance, instead of injecting just the ID of a user into your route, you have the option to inject the entire User model instance that corresponds to the given ID.

In the context of routes or controller actions, models are defined using type-hinted variable names that match a specific segment in the route. For example:

```php
use App\Models\User;

Route::get('/users/{user}', function  (User  $user) {

return  $user->email;

});
```

#### Customizing the Key

Sometimes you may wish to resolve models using a column other than `id`. To do so, you may specify the column in the route parameter definition:

```php
use App\Models\User;

Route::get('/users/{user:email}', function  (User $user) {
    return  $user;
});
```
In this scenario, Elemental will seamlessly inject the model instance that possesses an email matching the corresponding value from the request URI.

Of course, route-model-binding also works with controller methods.

##### Model Not Found

If a matching model instance is not found in the database, a `ModelNotFoundException` will be thrown by the app. You can handle such exceptions and control the behavior of any such and other exceptions thrown by the app in the `ExceptionsHandler` class. More on that later.

#### Fallback Routes

Using the `Route::fallback` method, you may define a route that will be executed when no other route matches the incoming request.

```php
Route::fallback(function () {
    // ...
});
```

#### Route List

The `route:list` Candle command will provide the list of all the routes defined in the application:

```bash
php candle route:list
```

## Controllers


Rather than consolidating all request handling logic within closures in your route files, consider structuring this behavior through "controller" classes. Controllers allow you to organize related request handling logic into a cohesive class. For instance, a `UserController` class could manage various incoming requests related to users, such as displaying, creating, updating, and deleting users. These controller classes are conventionally stored in the `app/Controllers` directory.

### Basic Controllers

To generate a new controller, you may run the `build:controller` Candle command.

```bash
php candle build:controller UserController
```

This will generate a new file named "UserController.php" inside the `app/Controllers` directory.

A controller may have any number of public methods which will respond to incoming HTTP requests:

```php
<?php
use App\Services\Auth;

namespace App\Controllers;

class AuthController
{
    public function showRegister()
    {
        return view("Register")->withLayout("layouts.DashboardLayout");
    }

    public function logout()
    {
        Auth::logout();
        redirect("/");
    }
}
```

After creating a controller class and its methods, you can define a route to the controller method as follows:

```php
use App\Controllers\UserController;

Route::get("/register", [AuthController::class, "showRegister"]);
```

When a received request matches with the designated route URI, the `showRegister` method within the `App\Controllers\UserController` class will be called, and the method will receive the corresponding route parameters.

### Dependency Injection and Controllers

#### Constructor Injection

The Elemental service container is responsible for resolve instances of all controllers. Consequently, you can use type-hinting in the constructor of your controller to specify any dependencies it may require. The stated dependencies will be automatically resolved and injected into the controller instance

```php
<?php

namespace App\Controllers;

use Core\Database\Database;

class UserController
{
    /**
    * Create a new controller instance.
    */
    public function __construct(
        public Database $db,
    ) {}
}
```

#### Method Injection

Apart from injecting dependencies through the constructor, you can also use type-hinting for dependencies in your controller's methods. A common use-case for method injection is injecting the `Core\Request\Request` or any service instance into your controller methods:

Create and manage controllers to handle requests effectively.

```php
<?php

namespace App\Controllers;

use Core\Request\Request;
use App\Services\Auth;

class StoryController
{
    public function create(Request $request)
    {
        $data = $request->data();
        $user = Auth::user();
        $story = Story::create([...]);

        return redirect("/story/$story->id");
    }
}
```

f your controller method anticipates input from a route parameter, you have the flexibility to list your arguments in any order. For instance, consider the following route definition:

```php
Route::post("story/update/{id}", [StoryController::class, "update"]);
```

You may still type-hint the `Core\Request\Request` and access your `id` parameter by defining your controller method as follows:

```php
<?php

namespace App\Controllers;
use Core\Request\Request;

class StoryController
{
    public function update(string $id, Request $request)
    {
        // Update the story...

        return redirect("/story/$story->id");
    }
}
```

## Request

The `Core\Request\Request` class in Elemental offers an object-oriented approach for engaging with the present HTTP request managed by your application. It facilitates the retrieval of input, cookies, and files submitted along with the request.

#### Accessing the Request

To acquire the current HTTP request instance through dependency injection, you can utilize type-hinting for the `Core\Request\Request` class in your route closure or controller method. The service container will automatically inject the incoming request instance.
```php
<?php

namespace App\Controllers;

use App\Models\Category;
use Core\Request\Request;

class CategoryController
{
    public function store(Request $request)
    {
        $name = $request->data()["name"];
        $category = Category::where(["name" => $name]);

        if ($category) {
            return view("Category", ["categories" => Category::all(), "msg" => "Category already exists!"])->withLayout("layouts.DashboardLayout");
        }

        Category::create($request->data());

        redirect("/category");
    }
}
```
The service container will automatically inject the incoming request into the route closure as well.

#### Dependency Injection and Route Parameters

If your controller method anticipates input from a route parameter, you have the flexibility to list your arguments in any order. For instance, consider the following route definition:

```php
Route::post("story/update/{id}", [StoryController::class, "update"]);
```

You may still type-hint the `Core\Request\Request` and access your `id` parameter by defining your controller method as follows:

```php
<?php

namespace App\Controllers;
use Core\Request\Request;

class StoryController
{
    public function update(string $id, Request $request)
    {
        // Update the story...
        return redirect("/story/$story->id");
    }
}
```

### Request Data

You may obtain all of the incoming request's input data as an `array` using the `data()` method. This method may be used regardless of whether the incoming request is from an HTML form or is an XHR request:

```php
$data =  $request->data();
```
#### Retrieving an Input Value
You may access all of the user input from your `Request` instance without worrying about which HTTP verb was used for the request. Regardless of the HTTP verb, the `data` method may be used to retrieve user input:

```php
$name=  $request->data()["name"];
```

### Request URI, Method and Headers

The `Core\Request\Request` instance provides a variety of methods for examining the incoming HTTP request. Let's discuss a few of the most important methods below.

 #### Request Headers
You may retrieve the request headers from the `Core\Request\Request` instance using the `headers` method.
```php
$headers = $request->headers();
```
 #### Request Method
You may retrieve the request method  by calling `method` on `Core\Request\Request` instance.
```php
$method = $request->method();
```
 #### Request URI
You may retrieve the request uri from the `Core\Request\Request` instance using the `uri` method.
```php
$uri = $request->uri();
```
 #### Request Cookies
You may retrieve the request cookies from the `Core\Request\Request` instance using the `cookies` method.
```php
$cookies = $request->cookies();
```

 #### Raw Content
You may retrieve the raw content from the `Core\Request\Request` instance using the `rawContent` method.
```php
$content = $request->rawContent();
```

Be careful when dealing with the raw content of a request.

 #### Files
You may retrieve the files from the `Core\Request\Request` instance using the `files` method.
```php
$files= $request->files();
```
 #### Request IP Address
The `ip` method may be used to retrieve the IP address of the client that made the request to your application:
```php
$ipAddress  =  $request->ip();
```

 #### Port Address
The `port` method may be used to retrieve the Port address of the client that made the request to your application:
```php
$port=  $request->port();
```

 #### Content Type
You may retrieve the content-type from the `Core\Request\Request` instance using the `contentType` method.
```php
$contentType = $request->contentType();
```

 #### Query String
You may retrieve the query string of the request using the `queryString` method.
```php
$query= $request->queryString();
```
 #### Specific Content Types

 ##### Text
You may retrieve the text content of the request using the `text` method provided the content-type is set to `text/plain`
```php
$text= $request->text();
```

 ##### Javascript
You may retrieve the JS content of the request using the `js` method provided the content-type is set to `application/javascript`
```php
$js= $request->js();
```
 ##### HTML
You may retrieve the HTML content of the request using the `html` method provided the content-type is set to `text/html`
```php
$js= $request->html();
```

 ##### JSON
You may retrieve the JSON content of the request using the `json` method provided the content-type is set to `application/json`
The `$request->data()` returns all the JSON data passed to the request. However,
```php
$jsonData = $request->json();
```

The `$request->data()` contains all the JSON data along with the inputs passed through the query params in the request. However, `$request->json()` can be used to retrieve only the JSON content.

 ##### XML
You may retrieve the XML content of the request using the `xml` method provided the content-type is set to `application/json`
```php
$xmlData = $request->xml();
```

## Responses

Every route and controller is expected to produce a response for delivery to the user's browser. Elemental offers various methods for generating responses. The simplest form of response involves returning a string directly from a route or controller. The framework will seamlessly convert this string into a complete HTTP response.
```php
Route::get('/', function  () {
	return  'Hello World';
});
```

In addition to returning strings from your routes and controllers, you may also return arrays or objects. The framework will automatically convert them into a JSON response:

```php
Route::get('/', function  () {
	return [1, 2, 3];
});
```
#### Response Objects

Usually, you won't merely return straightforward strings or arrays from your route actions. Instead, you'll often return complete instances of `Core\Response\Response` or views.

Returning a full `Response` instance allows you to customize the response's HTTP status code and headers. You can inject the Response instance by type-hinting the Response instance inside your controller or route closure.

```php

use Core\Response\Response;

Route::get('/home', function(Response $response) {
	$response->setHeader("content-type", "text/plain")
			->setStatusCode(200)
			->setContent("Hello World");

	return  response;
});
```
You can ofcourse return a `view` from a controller. However,  If you need control over the response's status and headers but also need to return a `view` as the response's content, you can do that as following:

```php

use Core\Response\Response;

class UserController {
	public function register(Response $response){
		$response->setHeader("x-is_register", "true");
		return view("Register");
	}
}
```

This will automatically set the header on the view response that will be sent to the browser.

#### Response Content
Keep in mind that most response methods are chainable, allowing for the fluent construction of response instances.

You may set the content of the response by using `setContent` method on the response instance.
```php
	$response->setContent("...");
```

However, if you want to append to the content of the response, you can do so by using `appendContent` method on the response instance.
```php
	$response->appendContent("...");
```

#### Response Header

You may set a header on the response instance by using `setHeader` method
```php
	$response->setHeader("content-type", "text/plain");
```
However, if you want to set several headers simultaneously, you can do so by using `setHeaders` method and passing an array of headers.
```php
	$response->setHeaders(["content-type" => "text/html", ...]);
```
#### Response Status Code
You may directly set the status code of the response by using `setHeader` method on the response instance.
```php
	$response->setStatusCode(301);
```
A status text will be set by default for the common status codes.

### Redirects
You can generate a redirect response that contains the proper headers needed to redirect the user to another URL by invoking static method `redirect` on the `Core\Response\Response` class.
```php
use Core\Response\Response;

Route::get('/dashboard', function  () {
	return Response::redirect('home/dashboard');
});
```
However, for simplicity a helper method `redirect()` is also available globally to achieve the same functionality.
```php
use Core\Response\Response;

Route::post('/story/create', function  () {
	if(!some condition)
		return redirect('/story', 204);
});
```

#### JSON Response

You can also generate a JSON response by calling the static method `JSON` on the `Core\Response\Response` class. The data passed to the method will be converted to proper JSON. You can also optionally pass the status code and headers array as the second and third argument to the function.

```php
use Core\Response\Response;

Route::post('/post', function  () {
	$post = (...);
	return Response::JSON($post, 201, ["header"=>"value"]);
});
```


## Middleware


Middleware offers a convenient mechanism to examine and filter incoming HTTP requests to your application. For instance, you can develop middleware to validate the authentication status of your application's user. If the user is not authenticated, the middleware will redirect them to the login screen. Conversely, if the user is authenticated, the middleware will permit the request to advance deeper into the application.

You have the flexibility to create additional middleware to execute diverse tasks beyond authentication. As an illustration, a logging middleware could record all incoming requests to your application. These middleware components are housed within the `app/middlewares` directory.

### Creating Middleware

To create a new middleware, use the `build:middleware` Candle command:

```bash
php candle build:middleware IsAuthenticated
```

Executing this command will generate a fresh middleware class named "IsAuthenticated" in the `app/middlewares` directory. Within this class, a method named `handle` is created where you can articulate the logic for the middleware.

Here, we will only allow access to the route if the user is authenticated, otherwise, we will redirect the users back to the `login` URI:

```php
<?php

namespace App\Middlewares;

use App\Services\Auth;
use Closure;
use Core\Request\Request;

class IsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!(/* authentication logic */)) {
            return redirect("/login");
        }

        return $next($request);
    }
}
```

To pass the request deeper into the application, you should call the `$next` callback with the `$request`.

Consider middleware as a sequence of "layers" that HTTP requests traverse before reaching your application. Each layer has the capability to scrutinize the request and potentially reject it.

Of course, a middleware can perform tasks before or after passing the request deeper into the application. For example, this middleware would perform its task **after** the request is handled by the application:

```php
<?php

namespace App\Middlewares;

use Closure;
use Core\Request\Request;

class AfterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        // Perform action

        return $response;
    }
}
```

### Assigning Middleware to Routes

If you would like to assign middleware to specific routes, you may invoke the `middleware` method when defining the route:

```php
Route::get('/profile', function () {
    // ...
})->middleware(IsAuthenticated::class);
```

You may assign multiple middleware to the route by passing an array of middleware names to the `middleware` method:

```php
Route::get('/', function () {
    // ...
})->middleware([First::class, Second::class]);
```

### Assigning Middleware to Route Groups

You may assign middlewares to a route group by passing an array of middleware names to the attribute `middlewares` when defining the group:

```php
Route::group(["middleware" => [HasSession::class]], function () {
    Route::get("/", [StoryController::class, "index"]);
    Route::get("/story/{story}", [StoryController::class, "show"]);
});
```

You can use nested route groups to combine middlewares with their parent group. In the subsequent example, the "HasSession" middleware is applied to the `"/"` and `"/story/{story}"` routes, whereas "HasSession," "IsAuth," and "Log" middlewares get applied to the rest of the routes:

```php
Route::group(["middleware" => [HasSession::class]], function () {
    Route::get("/", [StoryController::class, "index"]);
    Route::get("/story/{story}", [StoryController::class, "show"]);

    Route::group(["middleware" => [IsAuth::class, Log::class]], function () {
        Route::get("/compose", [StoryController::class, "compose"]);
        Route::post("/compose", [StoryController::class, "create"]);
    });
});
```


## Views, Layouts, and Components

### Views

In Elemental PHP framework, it is not practical to return entire HTML document strings directly from routes and controllers. Views provide a convenient way to place all HTML in separate files.

Views play a crucial role in separating controller/application logic from presentation concerns and are stored in the `app/views` directory. These view files, written in PHP, encapsulate the markup. Consider a basic example of a view:

```php
<html>
<body>
  <h1>Hello, <?= $name ?></h1>
</body>
</html>
```

If this view is stored at `app/views/Welcome.php`, it can be returned using the global `view` helper in a route:

```php
Route::get('/', function () {
  return view('Welcome', ['name' => 'Ahmed']);
});
```

The first argument passed to the `view` helper corresponds to the name of the view file in the `resources/views` directory. The second argument can be an array of key-value pairs passed to the view. For example, in the above code, `$name` will be directly accessible and contain the value 'Ahmed'.

Views can also be returned using the static method `make` on the `Core\View\View` class:

```php
Route::get('/', function () {
  return View::make("Post", $params);
});
```

#### Nested View Directories

Views may be nested within subdirectories of the `app/views` directory. "Dot" notation may be used to reference nested views. For example, if your view is stored at `app/views/layouts/MainLayout.php`, you may return it from a route/controller like so:

```php
return view('layouts.MainLayout', $data);
```

### Layouts

Elemental provides a convenient way to maintain the same layout across multiple views, reducing code duplication. A layout is itself a view file containing a placeholder `{{ content }}`. When a view is returned with the layout, the final view is compiled by putting the view inside the layout's content.


Elemental provides a convenient way to maintain the same layout across multiple views, reducing code duplication. A layout is a view file that incorporates a designated placeholder, denoted by `{{ content }}`. When a view is returned using a specific layout, the composition is achieved by embedding the content of the view within the designated placeholder in the layout. This approach streamlines the organization of views and enhances code maintainability by centralizing common layout elements.

Below is a basic example:

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Head content -->
</head>
<body>
  <?= component("components.Navbar") ?>
  <div style="min-height: calc(100vh - 140px);">
    {{ content }}
  </div>
</body>
</html>
```

A view can be returned with a layout like this:

```php
public function compose()
{
  return view("Compose")->withLayout("layouts.DashboardLayout");
}
```

### Components

Elemental offers a powerful approach to crafting views. Every view is essentially a component, and any view can be assembled from other components. It's a symphony of composition, where each piece contributes to the creation of a harmonious and dynamic whole.

Example component file (`views/components/Logo.php`):

```php
<a class="logo" href="/">
  <span class="logo-img">
    <img src="logo.png" class="logo-text">
    LOGO
  </span>
</a>
```

This component can be used inside any other view file. For example, in `views/Login.php`:

```php
<div>
  <?= component("components.Logo") ?>
  <p>Welcome Back!</p>
  <!-- Other login form elements -->
</div>
```

Thus, Elemental empowers you with both layout and component constructs, allowing you to compose your views with the finesse of both top-down and bottom-up approaches. This flexibility enables a seamless fusion, where you can effortlessly mix and combine elements to craft an elegant and sophisticated user interface for your application.

## Database

In the modern web applications, database interaction is a fundamental aspect. Elemental has been designed to streamline this interaction seamlessly across a diverse range of supported databases, leveraging the inherent capabilities of PHP PDO. With Elemental, you have the flexibility to execute any complex query or transaction using the `Core\Database\Database` class.

Elemental offers a robust Object-Relational Mapper (ORM) that effectively abstracts away many intricacies, proving invaluable for the majority of database queries. However, the `Core\Database\Database` can be used to run more advanced SQL queries.

All the configurations for your Elemental App are located in your application's `app/config/config.php` configuration file. Here you may define all of your database connections, as well as specify which connection should be used by default. Most of the configuration options within this file are driven by the values of your application's environment variables.

### Database Configuration

All the configurations for your Elemental App are located in your application's `app/config/config.php` configuration file. Here you may define all of your database connections, as well as specify which connection should be used by default. Most of the configuration options within this file are driven by the values of your application's environment variables.

```php
<?php

return [
    "db" => [
        "driver" => getenv("DB_DRIVER") ?? "mysql",
        "host" => getenv("DB_HOST") ?? $_SERVER['SERVER_ADDR'],
        "port" => getenv("DB_PORT") ?? "3306",
        "database" => getenv("DB_DATABASE") ?? "elemental",
        "username" => getenv("DB_USERNAME") ?? "root",
        "password" => getenv("DB_PASSWORD") ?? "",
    ],
];
```

Elemental uses PDO as the underlying database handling class. All PDO functions are directly available on the `Core\Database\Database` class. You can inject an instance of `Core\Database\Database` into any constructor or controller method to call PDO methods. The default configuration for Elemental is set up for MySQL databases, but you can change the driver inside the config file.

### Running a Query

Here's an example of running a query through the `Database` instance:

```php
public function tokens(Database $db) {
    $user_id = 1;

    $sql = "SELECT * FROM access_tokens WHERE user_id = :user_id";

    $stmt = $db->prepare($sql);

    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();

    $tokens = $stmt->fetchAll();
}
```

For more information on PDO, you can refer to [PHP's PDO documentation]()

## Object Relational Mapper (Model)

Elemental includes a custom-made object-relational mapper (ORM) that makes it enjoyable to interact with the database. When using the ORM, each database table has a corresponding "Model" that is used to interact with that table. In addition to retrieving records from the database table, models allow you to insert, update, and delete records from the table as well.

### Creating Models

Models are present in the `app/models` directory and extend the `Core\Model\Model` class. You can generate a new model by using the `build:model` Candle command.

```bash
php candle build:model Post
```

### Conventions:

Models generated by the `build:model` command will be placed in the `app/Models` directory. A very basic model has the following structure:

```php
<?php

namespace App\Models;

use Core\Model\Model;

class Post extends Model
{
    // ...
}
```

**Table Name:**
By convention, the "snake case," plural name of the class will be used as the table name unless another name is explicitly specified. So, in this case, Elemental will assume the `Post` model stores records in the `posts` table.

You may manually specify the model's table name by defining a `tableName` property on the model:

```php
<?php

namespace App\Models;

use Core\Model\Model;

class Post extends Model
{
    protected $tableName = 'elemental_posts';
}
```

**Primary Key:**

Elemental will also assume that each model's corresponding database table has a primary key column named `id`. If necessary, you may define a protected `$primaryKey` property on your model to specify a different column that serves as your model's primary key:

```php
<?php

namespace App\Models;

use Core\Model\Model;

class Post extends Model
{
    protected $primaryKey = 'elemental_id';
}
```

### Model Operations

You can think of each model as a powerful query builder allowing you to fluently query the database table associated with the model.

### All

The model's `all` method will retrieve all of the records from the model's associated database table:

```php
use App\Models\Story;

foreach (Story::all() as $story) {
    echo $story["content"];
}
```

By default, the records that are fetched are represented as an array. However, you can pass a mode argument which controls how each record is represented. The mode argument takes any of PDO Fetch modes. For instance,

```php
use App\Models\Story;

foreach (Story::all() as $story) {
    echo $story->content;
}
```

### All Where

The `allWhere` method is a powerful abstraction in the model that allows executing complex queries. This method takes three arguments: `conditions`, `options`, and `fetchMode`.

#### Method Signature

```php
public static function allWhere(array $conditions, array $options = [], int $fetchMode = PDO::FETCH_ASSOC)
```

**Conditions:**
The `conditions` parameter is an array of clauses that the record must satisfy to be fetched. Each condition can be either a `[key => value]` pair or a `[key => [operator, value]]` pair.

- The `key` corresponds to a specific column in the table.
- If the condition is in the form of `[key => value]`, the default operator is `=` and the `value` is the data inside that column for the record.
- If the condition is in the form of `[key => [operator, value]]`, you can specify the operator for each condition. The supported operators are:
  - `['=', '!=', '<', '>', '<=', '>=', 'LIKE', 'IS NULL', 'IS NOT NULL']`.

**Options:**
The `options` parameter is an array that determines additional query arguments, such as `order by`, `limit`, etc. Supported constructs in the options argument include:
- `"orderBy"`
- `"limit"`
- `"offset"`
- `"sortDir"`

**FetchMode:**
The `fetchMode` parameter controls how each fetched record is represented. The mode argument takes any of the PDO Fetch modes:
- `PDO::FETCH_ASSOC`
- `PDO::FETCH_NUM`
- `PDO::FETCH_BOTH`
- `PDO::FETCH_OBJ`
- `PDO::FETCH_CLASS`
- `PDO::FETCH_INTO`
- `PDO::FETCH_LAZY`
- `PDO::FETCH_KEY_PAIR`

An example will make it more clear:

```php
use Core\Request\Request;

class StoryController {

    const PAGE_SIZE = 10;

    public function index(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category_id;

        $sortBy = $request->sort_by; // ASC or DESC, Default = ASC
        $page = $request->page;
        $orderBy = $request->order_by;

        return Story::allWhere(
            [
                "category_id" => $categoryId,
                "title" => ["LIKE", "%$search$"],
            ],
            [
                "limit" => static::PAGE_SIZE,
                "orderBy" => $orderBy,
                "sortDir" => $sortBy,
                "offset" => ($page - 1) * static::PAGE_SIZE,
            ],
            PDO::FETCH_OBJ
        );
    }
}
```

### Fetching Single Record

In addition to retrieving all of the records matching a given query, you may also retrieve single records using the `find` and `where` method. Instead of returning an array of records, these methods return a single model instance:

**Find:**
This will fetch the first record that matches the Primary key of the table.

```php
$flight = Story::find(1);
```

**Where:**
The where method takes an array of conditions that the record must satisfy to be fetched. Each condition can be either a `[key => value]` pair or a `[key => [operator, value]]` pair.

- The `key` corresponds to a specific column in the table.
- If the condition is in the form of `[key => value]`, the default operator is `=` and the `value` is the data inside that column for the record.
- If the condition is in the form of `[key => [operator, value]]`, you can specify the operator for each condition. The supported operators are:
  - `['=', '!=', '<', '>', '<=', '>=', 'LIKE', 'IS NULL', 'IS NOT NULL']`.

For Example
```php
$user = User::where(["email" => $email]);
$liked = Like::where(["user_id" => $user->id, "story_id" => $story_id]);
```

### Create

To insert a new record into the database, you can instantiate a new model instance and set attributes on the model. Then, call the `save` method on the model instance:

```php
<?php

namespace App\Controllers;

use App\Models\Story;
use Core\Request\Request;

class StoryController
{
    public function store(Request $request)
    {
        $story = new Story;
        $story->name = $request->name;
        $story->save();
        return redirect('/story');
    }
}
```

In this example, we assign the `name` field from the incoming HTTP request to the `name` attribute of the `App\Models\Story` model instance. When we call the `save` method, a record will be inserted into the database. The model's `created_at` timestamp will automatically be set when the `save` method is called, so there is no need to set it manually.

Alternatively, you may use the static `create` method to "save" a new model using a single PHP statement. The inserted model instance will be returned to you by the `create` method:

```php
use App\Models\Story;

$story = Story::create([
    'name' => 'A tale of elemental magic',
]);
```

### Update

The `save` method may also be used to update models that already exist in the database. To update a model, you should retrieve it and set any attributes you wish to update. Then, you should call the model's `save` method.

```php
use App\Models\Story;

$story = Story::find(10);
$story->name = 'An elemental tale of magic';
$story->save();
```

Alternatively, you may use the static `update` method to update a model instance. The first argument is the id of the model, and the second argument needs to be the array of column value pair.

```php
use App\Models\Story;

$story = Story::update(10, ["name" => "A tale", "content" => "Once upon a time ...."]);
```

### Delete

To delete a model, you may call the `destroy` method on the model instance:

```php
use App\Models\Story;

$story = Story::find(12);
$story->destroy();
```

However, if you know the primary key of the model, you may delete the model without explicitly retrieving it by calling the `delete` method. The `id` of the deleted record is returned.

```php
use App\Models\Story;

Story::delete(12);
```

### Data

You may call the `data` method on the model to retrieve all the attributes of a modal instance in an array form.

```php
$user = User::find(10);
$user_data = $user->data();
```


## Candle (Command Line Engine)

Candle is the command line engine of the Elemental. Candle exists at the root of your application as the `candle` script and provides a number of helpful commands designed to aid you in the development process of your application. To view a list of all available Candle commands, you may use the `help` command:

```php
php candle help
```
This will also display the custom commands that you may have created yourself.

 #### Development Server
By now, you must have already `ignited` the the Elemental's candle to run your app.  This `ignite`  command serves the app at the IP Address 127.0.0.1, searching for a free port starting from 8000. If Port 8000 is occupied, Elemental automatically attempts to bind to the next available port (e.g., 8001) and so forth.

```bash
php candle ignite
```

You have the flexibility to customize the server setup according to your requirements.

**Custom Host**
Specify a specific IP address using the `--host` argument. For instance:
```bash
php candle ingite --host=192.168.1.10
```

**Custom Port**
If you prefer binding to a specific port, use the `--port` argument:
```bash
php candle ingite --port=8080
```

To serve your application at a custom IP and port simultaneously, provide both the `--host` and `--port` arguments:

```bash
php candle ingite --host=192.168.1.10 --port=8080
```
The `--host` and `--port`	arguments can be placed in any order.

### List all Routes

To obtain a comprehensive view of all registered routes within your application, utilize the `route:list` command provided by Candle:

bash

```bash
php candle route:list
```

### Generating Files

You can use the Candle `build` command to generate files for your models, controllers, middleware and commands.

##### Generate a Model

To create a model, execute the following command:

```bash
php candle build:model Story
```
This command will generate a file named `Story.php` within the `app\models` directory, containing the `Story` class.

##### Generate a Controller

For generating a controller, the `build` command is similarly employed:

```bash Copy code
php candle build:controller StoryController
```

Executing this command will generate a file named `StoryController.php` in the `app\controllers` directory, featuring the `MyController` class.

##### Generate Middleware

To generate a middleware, utilize the `build` command as follows:

```bash
php candle build:middleware HasSession
```

This will create a file named `HasSession.php` within the `app\middleware` directory, housing the `handle` method.

##### Generate Command

For command generation, execute the `build` command with the appropriate arguments:

```bash
php candle build:command Migration
```

Executing this command will generate a file named `Migration.php` in the `app\commands` directory, containing the `Migration` class and the `handle` method.

### Custom Commands in Candle

Generating custom commands is where the Candle's power can be experienced. Commands are stored in the `app/commands` directory, and it's essential to load them inside the array returned in `app\commands\Commands.php` for proper registration within the app.

#### Command Structure

After generating a command, define values for the `key` and `description` properties of the class. The `key` is used as the argument for the command, while `description` will be displayed in the help screen. The `handle` method will be called when the command is executed, and you can place your command logic in this method.

You can type-hint any dependencies required for your command handling. Elemental's DI Container will automatically inject all dependencies type-hinted in the `handle` method's signature.

Let's take a look at an example command:

```php
<?php

namespace App\Commands;

use App\Models\User;
use App\Service\MailService;
use Core\Console\Command;

class SendEmails extends Command
{
    protected $key = 'mail:send';
    protected $description = 'Send mails to all users';

    public function handle(MailService $mailService): void
    {
        $mailService->send(User::all());
    }
}
```

To execute the command in the command line:

```php
php candle mail:send
```

#### Retrieving Input Args

You can use Elemental's `Core\Console\Commander` to retrieve any inputs passed through the command line. The `Core\Console\Commander` provides a method named `getArgs` that returns an array of inputs passed from the command line. The Commander instance can be type-hinted through the handler method and used as required.

A concrete example will make it clear:

```php
<?php

namespace App\Commands;

use Core\Console\Command;
use Core\Console\Commander;

class Migration extends Command
{
    protected $key = "migrate";
    protected $description = "Custom migration handler.";

    private $commander;

    public function handle(Commander $commander, Database $db)
    {
        $args = $commander->getArgs();

        if (!isset($args[1])) {
            $this->up();
            return;
        }

        switch ($args[1]) {
            case "fresh":
                $this->downThenUp();
                break;
            case "delete":
                $this->down();
                break;
            default:
                $this->up();
        }
    }

    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            bio TEXT,
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $db->exec($sql);
            console_log("Table 'users' created successfully!");
        } catch (\PDOException $e) {
            console_log("Table creation error: " . $e->getMessage());
        }
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS users";

        try {
            $db->exec($sql);
            console_log("Table 'users' deleted successfully!");
        } catch (\PDOException $e) {
            console_log("Table deletion error: " . $e->getMessage());
        }
    }

    public function downThenUp()
    {
        $this->down();
        $this->up();
    }
}

```


It is recommended to type-hint dependencies inside the handle method as opposed to inside the constructor of the command class.

To execute these migration commands in the command line:
```bash
php candle migrate
php candle migrate fresh
php candle migrate delete
```

As you can see, generating commands are very powerful and can be helpful to achieve a variety of functionalities. Here, a custom migration handler has been built. You can expand and organize the above structure or create a custom Migration Service that can handle your migration logic.

Commands can also be used for handling task scheduling. You may create a command that executes some logic, and then pass the command to your operating systems CRON handler.


## Helpers

Elemental includes a variety of global "helper" PHP functions. You  can use these functions in any way that is convenient to you.

#### app()
The `app` function returns the `Application` instance:

```php
$app = app();
```
This is pretty useful when you want to register your own services as well as resolve any framework or custom service.

```php
app()->bind(CustomService::class, function () {
	return new CustomService(new anotherService());
});

$service = app()-make(CustomService::class);
```

#### dump()
The `dump` function dumps the variable passed as the first argument. You can also pass an additional second argument that can serve as the identifier on screen:
```php
dump($value);
dump($user, "user");
```

#### dd()
The `dd` function dumps the given variable and ends the execution of the script:
```php
dd($value);
dd($user, "user");
```

#### console_log()
The `console_log` function serves as a unique tool for logging variables, distinct from the `dump` function. Notably, it doesn't return output to the browser; instead, it directs information to the console initiated by the script. You can pass any variable number of arguments to the `console_log` function.

```php
console_log($value);
console_log($user, $post, $image, $comment);
```
#### router()

The `router` function returns the returns the `Router` instance.

#### view()

The `view` function is used to return a view from the controller method:
```php
return view('Login');
```
#### component()
The `component` function is used to return a view as a component to be used inside another view:
```php

<body>
	<?= component("Logo")?>
	//...
</body>
```
#### redirect()
The `redirect` function returns a redirect HTTP response and is used to redirect to any other route.
```php
return  redirect('/home');
```



## Exception Handler


Elemental provides a convenient way to handle all the exceptions thrown by the app.

The `handle` method of `App\Exceptions\Handler` class is where all exceptions thrown by your application pass through before being rendered to the user. By default, exceptions thrown by the app will be formatted, and a structured response will be sent back to the browser. However, inside the handle method, you can intercept any exception and perform custom logic before the response is sent back.

You can even send back a custom view or a response.

### Handler Class

```php
<?php

namespace App\Exceptions;

use Core\Exception\ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function handle($e)
    {
        // Perform some processing here

        // You can customize the handling of exceptions based on your requirements
    }
}
```

#### Handling Specific Exceptions

Elemental has defined some specific exception classes by default:

- `AppException`
- `ModelNotFoundException`
- `RouteNotFoundException`
- `RouterException`
- `ViewNotFoundException`

If you need to handle different types of exceptions in different ways, you can modify the `handle` method accordingly:

```php
<?php

class Handler extends ExceptionHandler
{
    public function handle($e)
    {
        if ($e instanceof ModelNotFoundException || $e instanceof RouteNotFoundException) {
            return view("404")->withLayout("layouts.DashboardLayout");
        }

        if ($e instanceof ViewNotFoundException) {
            return view("Home");
        }

        // Handle other specific exceptions as needed
    }
}
```

You are free to create your own exception classes by extending from the base `Exception` class, which can then be handled as required.

Feel free to customize the `handle` method based on your application's specific needs.

## Configuration

All configuration settings for the application are centralized in the `app\config\config.php` file. These configurations cover various aspects such as database connection information and other core settings essential for your app.

### Environment-specific Configuration

To cater to different environments where the application might run, a `.env.example` file is provided in the root directory. This file outlines common environment variables that can be configured. If you are working in a team, it's recommended to include the `.env.example` file with placeholder values. This makes it clear to other developers which environment variables are required to run the application.

When your application receives a request, all the variables listed in the `.env` file will be loaded into the `$_ENV` PHP super-global. You can then use the `getenv` function to retrieve values from these variables in your configuration files.

```php
$appName = getenv("APP_NAME");
```

### Accessing Configuration Values

To access configuration values, you can use type-hinting and inject the `Core\Config\Config` class into your constructors, controller methods, or route closures.

```php
use Core\Config\Config;

class YourClass {

    public function __construct(Config $config) {
        $driver = $config->db["driver"];
        $host = $config->db["host"];
        $port = $config->db["port"];
    }

    // Your other methods or code here
}
```

By doing this, you have a clean and organized way to retrieve configuration values within your application.


This approach keeps your configuration centralized and allows for easy changes based on the environment. It also promotes a clean and maintainable codebase.



## Facades

Elemental introduces a Facade system inspired by Laravel, providing a convenient and expressive static interface to classes within the application's Dependency Injection (DI) container. Facades act as static proxies to classes in the service container, offering a balance between a concise syntax and the testability and flexibility of traditional static methods.

In Elemental, the `Core\Facade\Route` serves as a Facade, offering a static interface to the application's Router instance enabling you to use it like this in the `routes.php` file:

```php
// routes.php

<?php
use Core\Facade\Route;

Route::get("/register", [AuthController::class, "showRegister"]);
Route::get("/login", [AuthController::class, "showLogin"]);
Route::get("/logout", [AuthController::class, "logout"]);
Route::post("/register", [AuthController::class, "register"]);
```

### Creating Your Own Facade

To create a custom Facade for any class, follow these steps:

1. Create a `FacadeClass` that extends the `Core\Facade\Facade` class.
2. Inside this class, implement a static method named `getFacadeAccessor`, returning the class string for the associated instance in the DI container.

Here's an example of creating a `PaymentGateway` Facade:

```php
<?php

use Core\Facade\Facade;
use Core\Services\PaymentGateway;

class PaymentGatewayFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PaymentGateway::class;
    }
}
```

Now, you can access the instance methods of your custom class by calling static methods on the corresponding `FacadeClass`.

## Inspiration

[LARAVEL](https://laravel.com/) is ***Magic***. Like any unsuspecting Muggle, it's enchantments terrify you. Until one fine day, you dare to pick up the wand and start waving it. Then, you fall in ***love*** with it.

## License

The Elemental framework is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Contributing

All contributions are welcome. Please create an issue first for any feature request or bug. Then fork the repository, create a branch and make any changes to fix the bug or add the feature and create a pull request. That's it! Thanks!

## Support

For bug reports, feature requests, or general questions, please use the [issue tracker](https://github.com/aneesmuzzafer/elemental/issues).
