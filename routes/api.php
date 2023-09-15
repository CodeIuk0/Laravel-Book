<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Comments;
use App\Models\Library;
use App\Models\Books;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthFromApi;
use App\Http\Controllers\BookController;


Route::get('/user', [AuthFromApi::class, "GetUser"]);

Route::get("/createUser",  function (Request $request) {
    $user = User::factory()->count(10)->create();
    return response()->json($user);
});

Route::get("/createComments",  function (Request $request) {
    $user = Comments::factory()->count(10)->create();
    return response()->json($user);
});

Route::get("/createBooks",  function (Request $request) {
    $user = Books::factory()->count(10)->create();
    return response()->json($user);
});


Route::get("/createLibrary",  function (Request $request) {
    $library = new Library(["user_id"=>Auth::user()->id,]);

    return response()->json($library);
});

Route::post("/auth",  function (Request $request) {

    $email =  $request->get("email");
    $password = $request->get("password");
    $remember = $request->get("remember") || false;

    if (!$email  || !$password)
        return  response()->json(["error" => "Invalid email / password"]);

    $isLogged = Auth::attempt(['email' => $email, 'password' => $password], $remember);

    if ($isLogged)
        return  response()->json(AuthFromApi::GetUser()->original);
    else
        return  response()->json(["error" => "Invalid email / password"]);
});

Route::post("/register",  function (Request $request) {

    $acceptCondition =  $request->get("acceptCondition");
    $name =  $request->get("name");
    $email =  $request->get("email");
    $password = $request->get("password");
    $confirmPassword = $request->get("confirmPassword");

    if (!$acceptCondition)
        return  response()->json(["error" => "Please accept all conditions"]);

    if ($confirmPassword != $password)
        return  response()->json(["error" => "Please enter the same password"]);

    if (!$name || !$email  || !$password || !$confirmPassword)
        return  response()->json(["error" => "Invalid name / email or password"]);

    if (User::where("email", $email)->first())
        return  response()->json(["error" => "Email already taken"]);

    if (User::where("name", $name)->first())
        return  response()->json(["error" => "Name already taken"]);

    $newUser =  new User(['name' => $name, 'email' => $email, 'password' => $password]);
    $newUser->save();

    if ($newUser) {
        Auth::loginUsingId($newUser->id);
        return  response()->json(AuthFromApi::GetUser()->original);
    } else
        return  response()->json(["error" => "Error has been occurred"]);
});

Route::post("logout", function (Request $request) {
    return  response()->json(Auth::logout());
});



Route::post("/send-email-verification", function (Request $request) {

    if (Auth::check()) {
        $user = Auth::user();
        if(!$user->hasVerifiedEmail())
        {
            Auth::user()->sendEmailVerificationNotification();
            return  response()->json(["message" => "A verification email has been sent to your email address"]);
        }
    }

    return  response()->json(null);
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware([
    'auth:sanctum',
])->group(function () {

    Route::get("/books",  function (Request $request) {
        return Books::query()->WhereNotInUser()->get();
    });

    Route::delete("/user/book/{bookId}", [BookController::class,"DeleteBook"]);


    Route::post("/user/add/book/comment",  [BookController::class,"AddCommentBook"]);
    Route::post("/user/add/book",  [BookController::class,"AddBook"]);

    Route::post("/user/add/library/book",  [BookController::class,"AddLibraryBook"]);


    Route::get("/user/comments",  function (Request $request) {
        return response()->json(Comments::all());
    });

    Route::get("/user/library",  function (Request $request) {
        return response()->json(Library::all());
    });
});
