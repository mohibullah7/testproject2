<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class postsController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    //we have make simple query in the model to access draft and publishes piosts here is teh example 

    // In PostController@index
// $publishedPosts = Post::published()->get();

// In PostController@homepage  
// $latestPosts = Post::published()->latest()->take(5)->get();

// In DashboardController
// $publishedCount = Post::published()->count();

// In UserController  
// $userPosts = Post::where('user_id', $userId)->published()->get();

// In SearchController
// $results = Post::published()->where('title', 'like', '%search%')->get();

    //  public function index()
    // {
    //     // Get all posts with user relationship, ordered by latest first
    //     $posts = Post::with('user')
    //                  ->orderBy('created_at', 'desc')
    //                  ->paginate(10);
        
    //     return view('posts.index', compact('posts'));
    // }

    public function index()
{
    if (request()->ajax()) {

        $posts = Post::with('user');

        // 🔍 FILTER: status
        if (request()->filled('status')) {
            $posts->where('status', request('status'));
        }

        return DataTables::of($posts)
            ->addIndexColumn()

            ->addColumn('image', function ($post) {
                if ($post->image) {
                    return '<img src="'.asset('storage/'.$post->image).'" style="width:50px;height:50px;border-radius:5px;">';
                }
                return '<span>No Image</span>';
            })

            ->addColumn('author', function ($post) {
                return $post->user->name ?? 'N/A';
            })

            ->addColumn('status', function ($post) {
                if ($post->status == 'published') {
                    return '<span class="badge bg-label-success">Published</span>';
                }
                return '<span class="badge bg-label-warning">Draft</span>';
            })

            ->addColumn('views', function ($post) {
                return number_format($post->views);
            })

            ->addColumn('created_at', function ($post) {
                return $post->created_at->format('M d, Y');
            })

                ->addColumn('actions', function ($post) {
        return '
        <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="'.route('posts.show', $post->id).'">
                    <i class="bx bx-show me-1"></i> View
                </a>
                <a class="dropdown-item" href="'.route('posts.edit', $post->id).'">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <a class="dropdown-item" href="'.route('posts.destroy', $post->id).'">
                    <i class="bx bx-trash-alt me-1"></i> Delete
                </a>
            </div>
        </div>';
    })


            ->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }

    return view('posts.index');
}

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {

     if (!auth()->user()->can('Add post')) {
                return redirect()->route('posts.index')
                    ->with('error', 'You do not have permission to Add posts.');
            }
        return view('posts.create');
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        try {
            // Generate slug from title
            $slug = Str::slug($validated['title']);
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
            }
            
            // Create post
            $post = Post::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'slug' => $slug,
                'body' => $validated['body'],
                'image' => $imagePath,
                'status' => $validated['status'],
                'published_at' => $validated['status'] == 'published' ? ($validated['published_at'] ?? now()) : null,
                'views' => 0,
            ]);
            
            return redirect()
                ->route('posts.index')
                ->with('success', 'Post created successfully!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create post. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('user')->findOrFail($id);
        

            // Check permission - Different logic for viewing
        
            // Anyone can view published posts
            if (!auth()->user()->can('View Post')) {
                return redirect()->route('posts.index')
                    ->with('error', 'You do not have permission to view posts.');
            }
        
        
        // Increment views
        $post->increment('views');
        
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        
        // Check if user owns the post or is admin
        // if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
        //     return redirect()->route('posts.index')
        //         ->with('error', 'You do not have permission to edit this post.');
        // }

         if (!auth()->user()->can('Edit Post')) {
                return redirect()->route('posts.index')
                    ->with('error', 'You do not have permission to Edit posts.');
            }

        
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        
        // Check permission
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return redirect()->route('posts.index')
                ->with('error', 'You do not have permission to update this post.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);
        
        try {
            // Update slug if title changed
            if ($post->title !== $validated['title']) {
                $validated['slug'] = Str::slug($validated['title']);
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($post->image && Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                }
                $validated['image'] = $request->file('image')->store('posts', 'public');
            }
            
            // Update published_at if status changed to published
            if ($validated['status'] == 'published' && !$post->published_at) {
                $validated['published_at'] = now();
            }
            
            $post->update($validated);
            
            return redirect()
                ->route('posts.index')
                ->with('success', 'Post updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update post. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        
        // Check permission
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return redirect()->route('posts.index')
                ->with('error', 'You do not have permission to delete this post.');
        }
        
        try {
            // Delete image if exists
            // if ($post->image && \Storage::exists('public/' . $post->image)) {
            //     \Storage::delete('public/' . $post->image);
            // }

             if ($post->image && Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                }
            
            $post->delete();
            
            return redirect()
                ->route('posts.index')
                ->with('success', 'Post deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('posts.index')
                ->with('error', 'Failed to delete post.');
        }
    }


    public function testqueries(){
         
    

             $arr1 = ['a','b','c'];
             $arr2 = [1,2,3,4];

            //  $product = Arr::crossJoin($arr1,$arr2);
             $product = Arr::divide($arr1,$arr2);
            //  prettyphpinfo($product);


            //  $post = Post::sortRecursive();

            //  dump($product);

            // dump($post);

    }



}
