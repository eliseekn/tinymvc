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

# Routing

Default routes are set by default whith controllers file class with associated actions. You can add custom routes in ```app/config/routes.php``` that redirects to these defaults routes, like this:

```php
$routes['home/'] = 'home/index';
$routes['administration/'] = 'admin/index'; 
$routes['posts/slug'] = 'posts/index';
```

***Note:*** Don't set parameters in routes, they are added to controller class method (action).

# Controllers

Controllers files are stored in ```app/controllers``` folder. To create a ```controller```, create a file with your controller name in lowercase. Then in your controller file, create a class with the name of your controller in first letter uppercase following by the word ```Controller```. To add an action, just create a method to your 
class with the action name in lowercase. See the example below:

```php
/**
 * controller class from app/controllers/home.php
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

Views files are stored in ```app/views``` folder. Views are separated in two subfolders, ```layouts``` and 
```templates```. To display a view file, use the function ```load_template``` located in ```app/core/loader.php```. See the example below:

```php
/**
 * controller class from app/controllers/home.php
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

***Note:*** Layouts and templates names are according to you. But don't need to add file extension ```.php``` when using ```load_template``` function. Be sure to add ```$page_content``` variable into layout view file to 
load template view page. See example below:

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

Models files are stored in ```app/models``` folder. You can edit database configuration in ```app/config/database.php```. To create a ```model```, create a file with your model name in lowercase. Then in your model file, create a class with the name of your model in first letter uppercase following by the word ```Model```. 
The class is extended from ```Model``` (see **Model** class in **app/core/model.php** for more infos). See the example below:

```php
/**
 * model class from app/controllers/posts.php
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

You can load a ```model``` it's filename without ```.php``` extension. Use the ```load_model``` function located in ```app/core/loader```. See example below:

```php
/**
 * controller class from app/controllers/posts.php
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
        $this->posts = load_model('posts'); //loads PostsModel class from app/models/posts.php
        $this->comments = load_model('comments'); //loads CommentsModel class from app/models/comments.php
    }

    /**
     * manage posts page display
     * 
     * @return void
     */
    public function index(): void
    {
        load_template(
            'posts',
            'main',
            array(
                'page_title' => 'My posts page',
                'posts' => $this->posts->get_all(),
                'comments' => $this->comments->get_all() 
            ) //optional variables to be passed  
        );
    }
}
```

# Model

```Model``` class help you manage easily operations to database. This class use chaining functons method to generate and execute safe sql query. Model class is stored in ```app/core/model.php``` file. How it's work?

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
$this->set_query_stirng('SELECT * FROM posts');
    ->fetch_all();

//set custom query string with arguments
$this->set_query_stirng(
    'INSERT FROM posts (title, content) VALUES (?, ?)', 
    array($title, $content)
)->execute_query();
```

# HttpRequests and HttpResponses

```HttpRequests``` and ```HttpResponses``` are located in ```app/core/http.php``` file. ```HttpRequests``` helps you manage ```HEADERS```, ```GET```, ```POST``` and ```RAW data``` sent from HTTP requests. ```HttpResponses``` helps you send HTTP responses with ```HEADERS```, ```BODY``` and ```STATUS CODE``` responses. How it's work?

```php
/**
 * controller class from app/controllers/admin.php
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
        $this->admin = load_model('admin'); //loads AdminModel class from app/models/admin.php
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

TinyMVC comes with a set of utils functions that extends the use of the framework. Helpers files are stored in ```app/helpers``` folder. You can load an helper file you need in your application by loading it via ```load_helpers``` funciton (located in ```app/core/loaders```) in file ```index.php```. See the example below:

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

***Note:*** You can create and use your own helpers file as TinyMVC is extensible.

# Errors

You can enable or disable errors display with the ```DISPLAY_ERRORS``` constant in ```app/core/app.php``` file. The ```404``` error display is handled by ```error_404``` action of ```ErrorController``` class. You can customize the ```Error 404``` page located in ```app/views/templates/error_404.php```.

# Public

The public folder contains scripts and images files. 

# Demo application
See ```demo``` folder for a demo application.

# Contribution

Feel free to contribute to this project by opening a pull request. Thanks :)