# TinyMVC

TinyMVC is a PHP framework based on MVC architecture that helps you build easly and quickly powerful web applications and RESTful API.

## Requirements
```PHP v7.2 or greater```

## License
[MIT](https://opensource.org/licenses/MIT)

## Copyright
2019-2020 © N'Guessan Kouadio Elisée (eliseekn@gmail.com)

# Installation

1\. Download your TinyMVC framework copy

[Download](https://github.com/eliseekn/TinyMVC/archive/master.zip)

2\. Setup configuration files

```
app/config
```

3\. Setup web server

For ***Apache*** server, edit your ```.htaccess``` with the following lines: 

```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php
```

For ***Nginx*** server, add the following to your server declaration

```
server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}
```

***Note:*** You must give ```write permission``` to extracted folder if you are going to build a blog like application.

# Configuration

## Application

Main application configuration is located in ```app/config/app.php```. You can define the applicatin root folder. See the example below:

```php
//define application base path to server root
define('APP_ROOT', '/');

//define application base path to a subfolder
define('APP_ROOT', '/tinymvc/');
```

## Routing

Routes configuration file is located in ```app/config/routes.php```. Default routes are set with controllers files class and associated actions. You can set the defaults controller and action names. Add your custom routes  using ```$routes``` variable. See the example below:

```php
$routes['home/'] = 'home/index';
$routes['administration/'] = 'admin/index'; 
$routes['posts/slug'] = 'posts/index';
```

***Note:*** Parameters are added automatically to controller class's action name.

## Database

Database configuration file is located in ```app/config/database.php```.

# Controllers

Controllers files are stored in ```app/controllers```. To create a ```controller```, create a file with the controller name in lowercase. Then inside the controller file, create a class using the controller name following by the word ```Controller```. The class name must be first letter uppercase. To add an action, just create a method with a name in lowercase. See the example below:

```php
/**
 * controller class of app/controllers/home.php
 */
class HomeController
{
    /**
     * manage home page display
     * 
     * @param  int $page requested page id
     * @return void
     */
    public function index(int $page = 1): void
    {
        //Code to execute
    }
}
```

# Views

Views files are stored in ```app/views```. Views are separated into, ```layouts``` and ```templates```. To render a view page, use the function ```load_template``` located in ```app/core/loader.php```. See the example below:

```php
/**
 * controller class of app/controllers/home.php
 */
class HomeController
{
    /**
     * manage home page display
     * 
     * @return void
     */
    public function index(): void
    {
        load_template(
            'home', //template file 
            'main', //layout file
            array('page_title' => 'My home page') //optional variables to be passed  
        );
    }
}
```

***Note:*** You must add the ```$page_content``` variable into layout page to include template page. See the example below:

```html
<?php //layout page ?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $page_title ?></title>
</head>

<body>

    <?= $page_content ?> <!-- template file content -->

</body>

</html>
```

# Models

Models files are stored in ```app/models```. To create a ```model```, create a file with the model name in lowercase. Then inside the model file, create a class with the model name following by the word ```Model```. The class name must be first letter uppercase. The class is extended from ```Model``` (see **Model** class in **app/core/model.php** for more infos). See the example below:

```php
/**
 * model class of app/controllers/posts.php
 */
class PostsModel extends Model
{
    /**
     * initialize parent
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * retrieves all posts from database
     * 
     * @return array
     */
    public function get_all(): array
    {
        return $this->select('*')
            ->from('posts')
            ->fetch_array();
    }
}
```

You can load a ```model``` by using the ```load_model``` function located in ```app/core/loader```. See the example below:

```php
/**
 * controller class of app/controllers/posts.php
 */
class PostsController
{
    /**
     * initialize class and load models
     * 
     * @return void
     */
    public function __construct()
    {
        $this->posts = load_model('posts'); //loads PostsModel class of app/models/posts.php
        $this->comments = load_model('comments'); //loads CommentsModel class of app/models/comments.php
    }

    /**
     * manage posts page display
     * 
     * @return void
     */
    public function index(string $slug): void
    {
        load_template(
            'posts',
            'main',
            array(
                'page_title' => 'My posts page',
                'posts' => $this->posts->get_posts($slug),
                'comments' => $this->comments->get_all($slug) 
            ) //optional variables to be passed  
        );
    }
}
```

# Model

```Model``` class help you manage easily operations to database. This class use chaining functons method to generate and execute safe sql queries. This class is stored in ```app/core/model.php```. See the example below:

1\. Select data queries

```php
//select first row from posts table 
$posts = $this->select('*') //select all columns
    ->from('posts') //table name
    ->fetch(); //return one row

//select all rows from posts table from range 2-5, order them by id in descending 
$posts = $this->select('*')
    ->from('posts')
    ->order_by('id', 'DESC') //order row by ASC or DESC
    ->limit(5, 2) //add limit and offset
    ->fetch_all(); //return all rows

//select all rows from posts table, where id is is equal to $id and title to $title
$posts = $this->select('*')
    ->from('posts')
    ->where('id', '=', $id) //you can use < or >
    ->and('title', '=', $title) //you can use also or
    ->fetch_all();

//perform a inner join select query
$posts = $this->select(
    'posts.*', 
    'users.name') //select many column
        ->from('posts')
        ->inner_join('users', 'posts.user_id', 'users.id') //you can use also left_join, right_join, full_join
        ->fetch_all();

//perform a like and or like select query
$posts = $this->select('*')
    ->from('posts')
    ->like('title', $query) //LIKE query
    ->or_like('content', $query) //OR LIKE query
    ->fetch_all()
```

2\. Insert data query

```php
$this->insert(
    'posts', //table name
    array(
        'title' => $title,
        'content' => $content
    ) //data to insert
)->execute_query();
```

3\. Update data qurey

```php
$this->update('posts') //table name
    ->set(
        array(
            'title' => $title,
            'content' => $content
        ) //data to update
    )
    ->where('id', '=', $id)
    ->execute_query();
```

4\. Delete data query

```php
$this->delete_from('posts') //delete from table name
    ->where('id', '=', $id)
    ->execute_query();
```

4\. Get rows count

```php
$total_posts = $this->select('*')
    ->from('posts')
    ->rows_count();
```

5\. Get and set query string

```php
//get generated query string
$query_string = $this->select('*')
    ->from('posts')
    ->get_query_string();

//set custom query string without arguments
$this->set_query_string('SELECT * FROM posts');
    ->fetch_all();

//set custom query string with arguments
$this->set_query_string(
    'INSERT FROM posts (title, content) VALUES (?, ?)', 
    array($title, $content)
)->execute_query();
```

# HttpRequests and HttpResponses

```HttpRequests``` and ```HttpResponses``` class are located in ```app/core/http.php```. ```HttpRequests``` helps you manage ```headers```, ```GET```, ```POST``` and ```raw data``` sent from HTTP requests. ```HttpResponses``` helps you send HTTP responses with ```headers```, ```body``` and ```status code```. See the example below:

```php
/**
 * controller class of app/controllers/admin.php
 */
class AdminController
{
    /**
     * initialize class and load models
     * 
     * @return void
     */
    public function __construct()
    {
        $this->admin = load_model('admin'); //loads AdminModel class of app/models/admin.php
    }

    /**
     * manage admin login
     * 
     * @return void
     */
    public function login(): void
    {
        $username = HttpRequests::post('username');
        $password = HttpRequests::post('password');

        if ($this->admin->login($username, $password)) { //check if user exists in database
            redirect_to('dashboard');
        } else {
            redirect_to('login');
        }
    }
}
```

# Helpers

TinyMVC comes with a set of utils functions that extends the use of the framework. Helpers files are stored in ```app/helpers```. You can load an helper by using ```load_helpers``` function located in ```app/core/loaders```. See the example below:

```php
//in index.php file

load_helpers(
    'cookies', //manage cookies
    'debug', //set of debug utils functions
    'email', //send email
    'files', //set of files utils functions
    'pagination', //generate pagination parameters
    'curl', //send HTTP post and get requests with curl
    'security', //set of security utils functions
    'session', //manage sessions
    'url', //set of url utils functions
    'utils' //set of miscellaneous utils functions
);
```

***Note:*** You can create and use your own helpers.

# Errors and Debugging

You can enable or disable errors display with the ```DISPLAY_ERRORS``` constant in ```app/core/app.php```. The ```404``` error display page is handled by ```error_404``` action of ```ErrorController``` class. You can customize the ```Error 404``` page located in ```app/views/templates/error_404.php```. You can easily debug your code by using the ```dump_exit``` and ```save_log``` functions located in ```app/helpers/debug.php```.

# Public

The public folder contains styles, scripts and images files. 

# Demos application
See ```demos``` folder for a demos application.

# Contribution

Feel free to contribute to this project by opening a pull request. Thanks :)
