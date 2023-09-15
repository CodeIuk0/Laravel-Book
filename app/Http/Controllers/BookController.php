<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comments;
use App\Models\Library;
use App\Models\Books;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    static function DeleteBook(Request $request, int $bookId) {

        $book = Library::where(["book_id"=>$bookId,"user_id"=>Auth::user()->id])->first();

        $comment_id = $book->comment_id;

        if($comment_id)
               Comments::where('id', $comment_id)->delete();
        $book->delete();
        return response(null);
    }

    static function AddCommentBook(Request $request) {

        $bookId = $request->get("bookId");
        $rate = $request->get("rate");
        $comment = $request->get("comment");

        if(!$bookId) {
            return response(null);
        }

        $user = Auth::user();
        try {
            DB::beginTransaction();

            $commentTable = $user->comments()->forBook($bookId)->first();

            if(!$commentTable)
            $commentTable = new Comments([
                "user_id"=>$user->id,
                "book_id"=>$bookId
            ]);

            if($comment) {
            $commentTable->comment = $comment;
            }
            if($rate) {
            $commentTable->note = $rate;
            }
            $commentTable->comment_updated_at = now();
            $commentTable->save();

            $library = Library::where(["user_id"=>$user->id,"book_id"=>$bookId])->first();

            $library->comment_id = $commentTable->id;

            $library->save();

            DB::commit();
            return response(null);
        } catch(\Exception $e) {
            DB::rollBack();
        }
        return response(["error"=>"Error has been occurred."]);
    }

    static function AddBook (Request $request) {

        $user =  Auth::user();
        $bookId = $request->input("bookId");

        $userBook = Library::where("user_id",$user->id)->find($bookId);

        if($userBook) {
            if($userBook->book) {
            return response(null);
            }
        }

        $newLibrary = new Library(["user_id"=>Auth::user()->id,"book_id"=>$bookId,"user_take_book_when"=>now(),"advancement"=>0]);
        $newLibrary ->save();

        return response(null);
    }
    static function AddLibraryBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required','max:255'],
            'pages' => ['required'],
            'summary' => ['required','max:545'],
            'editors' => ['required'],
            'tags' => ['required']
        ], [
            'title.required' => 'Le champ titre est obligatoire.',
            'title.max' => 'Le champ titre ne doit pas dépasser 255.',
            'pages.required' => 'Le champ pages est obligatoire.',
            'summary.required' => 'Le champ résumé est obligatoire.',
            'summary.max' => 'Le champ résumé ne doit pas dépasser 545.',
            'editor.required' => 'Le champ éditeur est obligatoire.',
            'tags.required' => 'Le champ tags est obligatoire.'
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(["error" => $firstError]);
        }

        $title = $request->input('title');
        $pages = $request->input('pages');
        $summary = $request->input('summary');
        $editors = $request->input('editors');
        $tags = $request->input('tags');


        if($editors != Auth::user()->name)
        return response()->json(null);

        $newBook = new Books([
            "title"=> $title,
            "pages"=> $pages,
            "summary"=> $summary,
            "tags" => $tags,
            "editors"=> $editors
        ]);

        $newBook->save();

        return response()->json(["errors"=>false]);

    }
}
