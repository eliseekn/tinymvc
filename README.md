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

[Download](https://github.com/eliseekn/TinyMVC)

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

# Routing

Default routes are set by default whith controllers file class with associated actions. You can add custom routes in ```app/config/routes.php``` that redirects to these defaults routes, like this:

```php
$routes['home/'] = 'home/index';
$routes['administration/'] = 'admin/index'; 
$routes['posts/slug'] = 'posts/index';
```

***Note:*** HTTP GET and POST requests methods are handle by default. Body content of POST method is adding as parameters to action controller. 

# Controllers

Controllers files are stored in ```app/controllers``` folder. To create a ```controller```, create a file with your controller name in lowercase. Then in your controller file, create a class with the name of your controller in first letter uppercase following by the word ```Controller```. To add an action, just create a method to your 
class with the action name in lowercase. See the example below:

```php
/**
 * controller class of home.php
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
 * controller class of home.php
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

***Note:*** Layouts and templates names are according to you. But don't add file extension ```.php``` when using 
```load_template``` function.

# Models

Models files are stored in ```app/models``` folder. You can edit database configuration in ```app/config/database.php```. To create a ```model```, create a file with your model name in lowercase. Then in your model file, create a class with the name of your model in first letter uppercase following by the word ```Model```. 
The class is extended from ```Model``` (see **Model class** for more infos). See the example below:

```php
/**
 * model class of posts.php
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

# Model

Class model help you manage easily operations to database. This class use chaining functons method to generate 
and execute safe sql query. Model class is stored in ```app/core/model.php``` file. How it's work:

1\. Select data queries

```php
$posts = $this->select('*') //select all columns
    ->from('posts') //table name
    ->limit(5, 2) //add limit and offset
    ->fetch(); //return one row

//----

$posts = $this->select('*')
    ->from('posts')
    ->order_by('id', 'DESC') //order row by ASC or DESC
    ->fetch_all(); //return all rows

//----

$posts = $this->select('*')
    ->from('posts')
    ->where('id', '=', $id) //where columnn name id is equal to $id (you can use < or > too)
    ->and('title', '=', $title) //add AND query. You can use also or_like
    ->fetch_all();

//----

$posts = $this->select(
    'posts.*', 
    'users.name') //select many column
        ->from('posts')
        ->inner_join('users', 'posts.user_id', 'users.id') //inner join posts and users tables. You can use also left_join, right_join, full_join
        ->fetch_all();

//----

$posts = $this->select('*')
    ->from('posts')
    ->like('title', $query) //user LIKE query
    ->or_like('content', $query) //user OR LIKE query
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
$this->update(
    'posts', //table name
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

# Helpers

TinyMVC comes with a set of utils functions that extends the use of the framework. Helpers files are stored in ```app/helpers``` folder. You can load an helper file you need in your application by loading it via ```load_helpers``` funciton in file ```index.php```. See the example below:

```php
//in index.php file
load_helpers(
    'cookies', //manage cookies
    'debug', //set of debug utils functions
    'email', //send email
    'files', //set of files utils functions
    'pagination', //generate pagination parameters
    'request', //send HTTP post and get requests
    'security', //set of security utils functions
    'session', //manage sessions
    'url', //set of url utils functions
    'utils' //set of miscellaneous utils functions
);
```

***Note:*** You can add and use your own helpers as TinyMVC is extensible.

# Hello world!
Check ```demo``` folder for a demo application 