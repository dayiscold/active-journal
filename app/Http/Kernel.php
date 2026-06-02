protected $routeMiddleware = [
// ...
'role' => \App\Http\Middleware\CheckRole::class,
'admin' => \App\Http\Middleware\CheckRole::class . ':admin',
'teacher' => \App\Http\Middleware\CheckRole::class . ':teacher',
'student' => \App\Http\Middleware\CheckRole::class . ':student',
'dean' => \App\Http\Middleware\CheckRole::class . ':dean',
];
