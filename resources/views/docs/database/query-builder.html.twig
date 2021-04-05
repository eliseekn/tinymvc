<?php $this->layout('layouts/docs', [
    'page_title' => 'Query Builder | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header">Query Builder</div>

    <div class="card-body">
        <p>TinyMVC's query builder provides a convenient way to creating and running SQL queries.</p>

        <p class="font-weight-bold">Retrieving rows</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users
$users = Builder::select()->from('users')->fetchAll(); //retrieves all rows

//SELECT id,name FROM users
$user = Builder::select('id', 'name')->from('users')->fetch(); //retrieves single row</code></pre>
        </div>

        <p class="font-weight-bold">WHERE clauses</p>
        <p>Basic WHERE clause :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE active = 1
$users = Builder::select()
    ->from('users')
    ->where('active', '=', 1)
    ->fetchAll();</code></pre>
        </div>

        <p>WHERE NOT clause :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE NOT active = 1
$users = Builder::select()
    ->from('users')
    ->whereNot('active', '=', 1)
    ->fetchAll();</code></pre>
        </div>

        <p>WHERE IS NULL and WHERE IS NOT NULL clauses :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE name IS NULL
$users = Builder::select()
    ->from('users')
    ->whereColumn('name')->isNull()
    ->fetchAll();
    
//SELECT * FROM users WHERE name IS NOT NULL
$users = Builder::select()
    ->from('users')
    ->whereColumn('name')->notNull()
    ->fetchAll();</code></pre>
        </div>

        <p>WHERE IN and WHERE NOT IN clauses :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE id IN (1, 2, 3)
$users = Builder::select()
    ->from('users')
    ->whereColumn('id')->in([1, 2, 3])
    ->fetchAll();
    
//SELECT * FROM users WHERE id NOT IN (1, 2, 3)
$users = Builder::select()
    ->from('users')
    ->whereColumn('id')->notIn([1, 2, 3])
    ->fetchAll();</code></pre>
        </div>

        <p>WHERE BETWEEN and WHERE NOT BETWEEN clauses :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE id BETWEEN 1 AND 3
$users = Builder::select()
    ->from('users')
    ->whereColumn('id')->between(1, 3)
    ->fetchAll();
    
//SELECT * FROM users WHERE id NOT BETWEEN 1 AND 3
$users = Builder::select()
    ->from('users')
    ->whereColumn('id')->notBetween(1, 3)
    ->fetchAll();</code></pre>
        </div>

        <p>WHERE LIKE and WHERE NOT LIKE clauses :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE name LIKE '%admin%'
$users = Builder::select()
    ->from('users')
    ->whereColumn('name')->like('admin')
    ->fetchAll();
    
//SELECT * FROM users WHERE name NOT LIKE '%admin%'
$users = Builder::select()
    ->from('users')
    ->whereColumn('name')->notLike('admin')
    ->fetchAll();</code></pre>
        </div>

        <p class="font-weight-bold">HAVING clause</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users HAVING id > 2
$users = Builder::select()
    ->from('users')
    ->having('id', '>', 2)
    ->fetchAll();</code></pre>
        </div>

        <p class="font-weight-bold">AND and OR clauses</p>
        <p>AND clauses :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE active = 1 AND id <> 1
$users = Builder::select()
    ->from('users')
    ->where('active', '=', 1)
    ->and('id', '!=', 1)
    ->fetchAll();

//SELECT * FROM users WHERE active = 1 AND name IS NULL
$users = Builder::select()
    ->from('users')
    ->where('active', '=', 1)
    ->andColumn('id')->isNull()
    ->fetchAll();

//SELECT * FROM users HAVING id > 2 AND name LIKE '%admin%'
$users = Builder::select()
    ->from('users')
    ->having('id', '>', 2)
    ->andColumn('name')->like('admin')
    ->fetchAll();

//SELECT * FROM users HAVING id > 2 AND id BETWEEN 3 AND 5
$users = Builder::select()
    ->from('users')
    ->having('id', '>', 2)
    ->andColumn('id')->between(3, 5)
    ->fetchAll();</code></pre>
        </div>

        <p>OR clauses :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE active = 1 OR id > 2
$users = Builder::select()
    ->from('users')
    ->where('active', '=', 1)
    ->or('id', '>', 2)
    ->fetchAll();

//SELECT * FROM users WHERE active = 1 OR name IS NULL
$users = Builder::select()
    ->from('users')
    ->where('active', '=', 1)
    ->orColumn('id')->isNull()
    ->fetchAll();

//SELECT * FROM users HAVING id > 2 OR name LIKE '%admin%'
$users = Builder::select()
    ->from('users')
    ->having('id', '>', 2)
    ->orColumn('name')->like('admin')
    ->fetchAll();

//SELECT * FROM users HAVING id > 2 OR id BETWEEN 3 AND 5
$users = Builder::select()
    ->from('users')
    ->having('id', '>', 2)
    ->orColumn('id')->between(3, 5)
    ->fetchAll();</code></pre>
        </div>

        <p>AND clause :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE active = 1 OR id > 2
$users = Builder::select()
    ->from('users')
    ->where('active', '=', 1)
    ->or('id', '>', 2)
    ->fetchAll();</code></pre>
        </div>

        <p class="font-weight-bold">Raw expressions :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">//SELECT * FROM users WHERE active = 1
$users = Builder::select()
    ->from('users')
    ->whereRaw('active = ?', [1])
    ->fetchAll();</code></pre>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('docs/database/query-builder') ?>">Model</a></span>
        <span>Previous: <a href="<?= absolute_url('docs/guides/redirections') ?>">URL Redirections</a></span>
    </div>
</div>

<?php $this->stop() ?>