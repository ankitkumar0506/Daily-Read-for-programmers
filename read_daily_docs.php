<?php
// 2026-10-04 07:13:31

/* PHP
PHP Generators (yield)

Explanation:
- Generators provide a simple way to implement iterators without the overhead of building an array in memory.  
- Using the `yield` keyword, a function can return values one at a time, preserving its execution state between each call.  
- This is especially useful when working with large data sets, such as reading big files or processing database rows.  
- Generators improve performance and reduce memory consumption because only a single value is held in memory at any moment.  
- They can be combined with `foreach` loops just like regular arrays, making them easy to integrate into existing code.

Code example with comments:

function readLargeFile(string $filePath) {
    // Open the file for reading
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        throw new RuntimeException("Cannot open file: $filePath");
    }

    // Loop until end of file
    while (($line = fgets($handle)) !== false) {
        // Yield each line to the caller, preserving the function state
        yield $line;
    }

    // Close the file after all lines have been processed
    fclose($handle);
}

// Usage of the generator
foreach (readLargeFile('large_text_file.txt') as $lineNumber => $content) {
    // Process each line individually without loading the entire file into memory
    echo "Line " . ($lineNumber + 1) . ": " . $content;
}
*/

/* Laravel
Topic: Laravel Eloquent Polymorphic Relationships

Explanation:
Polymorphic relationships allow a model to belong to more than one other model on a single association. This is useful when different models share a common feature, such as comments that can belong to posts, videos, or products. Laravel handles the underlying foreign keys and type columns automatically, simplifying queries and data integrity. You define the relationship methods on each model and use morphMany or morphTo depending on the direction. When retrieving related records, Laravel returns the appropriate model instances without extra manual checks.

Code example (Comment model, Post model, migration, and usage):

<?php
// app/Models/Comment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Inverse side of the polymorphic relation
    public function commentable()
    {
        // Laravel will look for commentable_id and commentable_type columns
        return $this->morphTo();
    }
}

// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // A post can have many comments
    public function comments()
    {
        // Laravel will use commentable_id and commentable_type to match this post
        return $this->morphMany(Comment::class, 'commentable');
    }
}

// database/migrations/2024_10_04_000000_create_comments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            // Polymorphic fields
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            $table->timestamps();

            // Optional index for faster lookups
            $table->index(['commentable_type', 'commentable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}

// Using the polymorphic relationship
use App\Models\Post;
use App\Models\Comment;

// Create a new post
$post = Post::create(['title' => 'Laravel Polymorphism', 'content' => '...']);

// Add a comment to the post via the polymorphic relation
$post->comments()->create(['body' => 'Great article!']);

// Retrieve the comment and access its parent model
$comment = Comment::first();
$parent = $comment->commentable; // Returns the Post instance
echo $parent->title; // Outputs: Laravel Polymorphism

// You can also attach comments to other models (e.g., Video) using the same fields
?>
*/

/* MySQL
Topic: MySQL Transactions and ACID Compliance

Explanation:  
A transaction groups several SQL statements into a single logical unit of work, ensuring that either all changes are applied or none at all. MySQL enforces the ACID properties—Atomicity, Consistency, Isolation, Durability—to guarantee data integrity even in the presence of errors or concurrent access. By default, InnoDB tables support transactions; you can explicitly begin, commit, or roll back a transaction. Proper use of isolation levels (e.g., READ COMMITTED, REPEATABLE READ) controls how concurrent transactions interact. Transactions are essential for financial operations, inventory updates, and any scenario where partial updates could corrupt business logic.

Code example (with comments):
-- Start a new transaction
START TRANSACTION;

-- Insert a new order record
INSERT INTO orders (order_id, customer_id, total_amount, status)
VALUES (101, 25, 199.99, 'pending');

-- Decrease product stock based on the order
UPDATE products
SET stock = stock - 1
WHERE product_id = 57 AND stock > 0;

-- If the stock update affected no rows, something is wrong; roll back
IF ROW_COUNT() = 0 THEN
    ROLLBACK;  -- Undo the INSERT and any other changes
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient stock';
ELSE
    COMMIT;    -- All statements succeeded; make changes permanent
END IF;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is created when an inner function accesses variables from an outer function that has already finished executing. The inner function retains a reference to the outer scope’s variables, forming a persistent lexical environment. This allows data privacy, function factories, and the emulation of private state in JavaScript. Closures are fundamental for callbacks, event handlers, and module patterns. Understanding closures helps avoid common pitfalls such as unintended variable sharing in loops.

Code example:
// Outer function that defines a private counter
function createCounter(initialValue) {
    let count = initialValue;               // 'count' is scoped to createCounter

    // Inner function forms a closure over 'count'
    return function increment(step = 1) {
        count += step;                      // Accesses and updates the outer variable
        return count;                       // Returns the updated count
    };
}

// Create two independent counters
const counterA = createCounter(0);
const counterB = createCounter(10);

// Use the counters
console.log(counterA());    // 1
console.log(counterA(5));   // 6
console.log(counterB());    // 11
console.log(counterB(2));   // 13

// Each counter retains its own private 'count' variable because the inner
// function closes over the lexical environment that existed when it was created.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example interactions before the new user query, guiding the assistant’s style and content. By embedding these examples directly in the prompt, you can influence tone, formatting, and domain knowledge without fine‑tuning. This technique works well for Q&A, code assistance, or any task where consistent responses are desired. The examples act as a “soft” instruction set that the model follows for the subsequent request. Using the Chat Completion endpoint, you can programmatically build the prompt, append the user’s question, and retrieve a tailored answer.

Code example (Python, with comments):  
import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Create a few‑shot prompt containing two solved examples  
few_shot_prompt = """You are a helpful assistant.

User: How do I reverse a list in Python?  
Assistant: You can use the reverse() method or slicing: my_list.reverse() or my_list[::-1].

User: What is the time complexity of binary search?  
Assistant: The time complexity is O(log n).

User: """  

# The new user question we want the model to answer  
new_question = "Explain the difference between deep copy and shallow copy in Python."  

# Append the new question to the prompt, leaving the assistant’s response open  
full_prompt = few_shot_prompt + f"User: {new_question}\nAssistant:"  

# Call the ChatCompletion endpoint with the constructed prompt  
response = openai.ChatCompletion.create(  
    model="gpt-3.5-turbo",  
    messages=[  
        {"role": "system", "content": "You are a helpful assistant."},  
        {"role": "user", "content": full_prompt}  
    ],  
    temperature=0.7,   # modest creativity  
    max_tokens=150     # limit length of answer  
)  

# Print the assistant’s answer extracted from the API response  
print(response["choices"][0]["message"]["content"])
*/

