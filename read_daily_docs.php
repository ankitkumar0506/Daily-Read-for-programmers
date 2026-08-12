<?php
// 2026-08-12 13:39:53
/*
**Topic: Using Laravel Eloquent Relationships in a Real World Example**

Laravel Eloquent relationships allow you to easily interact with related models in your database. This topic will explore how to use Eloquent relationships to create a simple blog where each post has many comments, and each comment belongs to one post.

**Code Example:**

```php
// Post Model
class Post extends Model
{
    protected $fillable = ['title', 'content'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

// Comment Model
class Comment extends Model
{
    protected $fillable = ['name', 'body', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

// Creating a new post
$post = new Post();
$post->title = 'Example Post';
$post->content = 'This is an example post';
$post->save();

// Adding comments to the post
$comment1 = new Comment();
$comment1->name = 'John';
$comment1->body = 'Great post!';
$comment1->post_id = $post->id;
$comment1->save();

$comment2 = new Comment();
$comment2->name = 'Jane';
$comment2->body = 'Love the article!';
$comment2->post_id = $post->id;
$comment2->save();

// Retrieving comments for the post
$comments = $post->comments;

foreach ($comments as $comment) {
    echo $comment->name . ': ' . $comment->body . ' (ID: ' . $comment->id . ')' . PHP_EOL;
}

// Retrieving the post with its comments
$postWithComments = Post::with('comments')->find($post->id);

foreach ($postWithComments->comments as $comment) {
    echo $comment->name . ': ' . $comment->body . ' (ID: ' . $comment->id . ')' . PHP_EOL;
}
```

In this example, we're creating two models, Post and Comment, each with the necessary relationships defined using Eloquent's hasMany and belongsTo methods. We then create a new post and add two comments to it, and finally retrieve the comments for the post and the post with its comments using Eloquent's with method.
*/
