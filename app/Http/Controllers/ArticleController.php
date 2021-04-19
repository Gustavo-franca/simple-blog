<?php


namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleFormRequest;

class ArticleController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }
    public function index()
    {
        //fetch 5 posts from database which are active and latest
        $posts = Article::where('active',1)->orderBy('created_at','desc')->paginate(5);
        //page heading
        $title = 'Latest Posts';
        //return home.blade.php template from resources/views folder
        return view('blog.articles')->withPosts($posts)->withTitle($title);
    }
    public function create(Request $request)
    {
      // 
      if ($request->user()->can_post()) {
        return view('blog.create');
      } else {
        return redirect('/')->withErrors('You have not sufficient permissions for writing post');
      }
    }
    public function store(ArticleFormRequest $request)
    {
      $post = new Article();
      $post->title = $request->get('title');
      $post->body = $request->get('body');
      $post->slug = Str::slug($post->title);
  
      $duplicate = Article::where('slug', $post->slug)->first();
      if ($duplicate) {
        return redirect('new-post')->withErrors('Title already exists.')->withInput();
      }
  
      $post->author_id = $request->user()->id;
      if ($request->has('save')) {
        $post->active = 0;
        $message = 'Post saved successfully';
      } else {
        $post->active = 1;
        $message = 'Post published successfully';
      }
      $post->save();
      return redirect('edit/' . $post->slug)->withMessage($message);
    }

    public function show($slug)
  {
    $post = Article::where('slug',$slug)->first();
    if(!$post)
    {
       return redirect('/')->withErrors('requested page not found');
    }
    $comments = $post->comments;
    return view('blog.show')->withPost($post)->withComments($comments);
  }

  public function edit(Request $request,$slug)
  {
    $post = Article::where('slug',$slug)->first();
    if($post && ($request->user()->id == $post->author_id || $request->user()->is_admin()))
      return view('blog.edit')->with('post',$post);
    return redirect('/')->withErrors('you have not sufficient permissions');
  }


  public function update(Request $request)
  {
    //
    $post_id = $request->input('post_id');
    $post = Article::find($post_id);
    if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
      $title = $request->input('title');
      $slug = Str::slug($title);
      $duplicate = Article::where('slug', $slug)->first();
      if ($duplicate) {
        if ($duplicate->id != $post_id) {
          return redirect('edit/' . $post->slug)->withErrors('Title already exists.')->withInput();
        } else {
          $post->slug = $slug;
        }
      }

      $post->title = $title;
      $post->body = $request->input('body');

      if ($request->has('save')) {
        $post->active = 0;
        $message = 'Post saved successfully';
        $landing = 'edit/' . $post->slug;
      } else {
        $post->active = 1;
        $message = 'Post updated successfully';
        $landing = $post->slug;
      }
      $post->save();
      return redirect($landing)->withMessage($message);
    } else {
      return redirect('/')->withErrors('you have not sufficient permissions');
    }
  }

  public function destroy(Request $request, $id)
  {
    //
    $post = Article::find($id);
    if($post && ($post->author_id == $request->user()->id || $request->user()->is_admin()))
    {
      $post->delete();
      $data['message'] = 'Post deleted Successfully';
    }
    else 
    {
      $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
    }
    return redirect('/')->with($data);
  }
}
