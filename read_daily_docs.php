<?php
// 2026-09-14 06:42:39

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:
Prepared statements separate SQL code from data, preventing SQL injection attacks. PDO (PHP Data Objects) provides a consistent API for many database systems, making your code portable. You first prepare the SQL with placeholders, then bind values and execute the statement. This approach also improves performance when the same query is run multiple times with different data. Errors can be caught using exceptions, allowing graceful handling of database issues.

Code Example:
// Create a new PDO connection (replace DSN, username, and password with your own values)
$pdo = new PDO('mysql:host=localhost;dbname=example_db;charset=utf8mb4', 'db_user', 'db_pass');
// Set error mode to exception for better error handling
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// SQL statement with named placeholders
$sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$data = [
    ':username'      => 'johndoe',
    ':email'         => 'johndoe@example.com',
    ':password_hash' => password_hash('secret123', PASSWORD_DEFAULT)
];

// Execute the prepared statement with the bound values
$stmt->execute($data);

// If you need to insert multiple rows, you can reuse the same prepared statement:
$moreUsers = [
    ['alice', 'alice@example.com', 'alicePass'],
    ['bob',   'bob@example.com',   'bobPass']
];

foreach ($moreUsers as $user) {
    $stmt->execute([
        ':username'      => $user[0],
        ':email'         => $user[1],
        ':password_hash' => password_hash($user[2], PASSWORD_DEFAULT)
    ]);
}

// Close the connection (optional, PHP does this automatically at script end)
$pdo = null.
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:
Form Request Validation separates validation logic from controllers, keeping them clean and focused on handling requests. You generate a custom request class that contains authorization and rule definitions. When the request class is type‑hinted in a controller method, Laravel automatically validates the incoming data before the method runs. If validation fails, a redirect with error messages is generated automatically. This approach also allows you to reuse validation rules across multiple controllers and to add custom validation messages in one place.

Code example (app/Http/Requests/StorePostRequest.php):
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Return true to allow all users, or add your own logic.
        return true;
    }

    // Define the validation rules that apply to the request.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'tags'    => 'array',
            'tags.*'  => 'string|distinct',
        ];
    }

    // Optional: customize the error messages.
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'body.required'  => 'Please provide the post content.',
        ];
    }
}
?>

Controller usage (app/Http/Controllers/PostController.php):
<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // Store a new blog post using the validated request data.
    public function store(StorePostRequest $request)
    {
        // Since validation passed, we can safely create the post.
        $post = Post::create($request->validated());

        // Attach tags if they were provided.
        if ($request->filled('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        // Redirect or return a response.
        return redirect()->route('posts.show', $post);
    }
}
?>
*/

/* MySQL
Topic: Composite Indexes in MySQL

Explanation:
A composite index is an index that covers two or more columns of a table.  
MySQL can use the leftmost prefix of a composite index to satisfy queries, so the column order matters.  
They improve query performance when filtering, sorting, or joining on the indexed columns together.  
If a query uses only the second column of the composite index, MySQL cannot use the index efficiently.  
Creating the right composite index can reduce the number of rows examined and speed up execution dramatically.  

Code example with comments:
-- Create a sample table for a blog application
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    created_at DATETIME NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    INDEX idx_author_category_date (author_id, category_id, created_at)  -- composite index on three columns
);

-- Query that can benefit from the composite index
SELECT post_id, title
FROM posts
WHERE author_id = 42            -- uses first column of the index
  AND category_id = 7           -- uses second column as well
ORDER BY created_at DESC;       -- ordering can be satisfied by the third column of the index

-- Another query that uses only the leftmost part of the index
SELECT COUNT(*) 
FROM posts
WHERE author_id = 42;           -- MySQL uses the index for the author_id filter only

-- Query that cannot use the composite index efficiently (starts with the second column)
SELECT post_id, title
FROM posts
WHERE category_id = 7
ORDER BY created_at DESC;       -- MySQL will likely perform a full scan because the index does not start with category_id.
*/

/* JavaScript
Topic: JavaScript Closures  

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context. It allows inner functions to remember the variables of their outer functions, enabling data privacy and function factories. Closures are created each time a function is defined, capturing the current state of the surrounding variables. They are essential for patterns such as memoization, event handlers, and module encapsulation. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state between calls.

Code example:
// Outer function creates a private counter variable
function createCounter(initialValue) {
    let count = initialValue;               // This variable is captured by the inner function

    // The returned inner function forms a closure over 'count'
    return function increment(step = 1) {
        count += step;                      // Modifies the captured variable
        return count;                       // Returns the updated value
    };
}

// Using the closure
const counterA = createCounter(0);          // Starts at 0
console.log(counterA()); // 1
console.log(counterA(5)); // 6

const counterB = createCounter(10);         // Independent closure, starts at 10
console.log(counterB()); // 11
console.log(counterB()); // 12

// Each counter maintains its own private 'count' variable, inaccessible from the outside.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s ChatCompletion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example interactions inside the prompt, teaching it the desired input‑output pattern without any fine‑tuning. By carefully crafting these examples you can steer the model to follow custom formats, adhere to domain‑specific vocabularies, or perform step‑by‑step reasoning. The technique works well for tasks like data extraction, code generation, or converting natural language to structured queries. It is lightweight, requires only the API call, and can be iteratively refined based on observed outputs. When combined with temperature control you can balance creativity and determinism for reliable results.  

Code example (Python, using openai library):  

import os  
import openai  

# Set your OpenAI API key (ensure it is stored securely)  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model to turn a plain request into a SQL query  
prompt = """You are an assistant that translates natural‑language questions into PostgreSQL queries.  
Example 1:  
Q: How many users signed up in the last 30 days?  
A: SELECT COUNT(*) FROM users WHERE signup_date >= CURRENT_DATE - INTERVAL '30 days';  

Example 2:  
Q: List the top 5 products by sales amount.  
A: SELECT product_id, SUM(sales) AS total_sales FROM orders GROUP BY product_id ORDER BY total_sales DESC LIMIT 5;  

Now translate the following question:  
Q: """  

user_question = "What is the average order value for orders placed in 2023?"  

# Combine the prompt with the user’s question  
full_prompt = prompt + user_question  

# Call the ChatCompletion endpoint with temperature=0 for deterministic output  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=[{"role": "user", "content": full_prompt}],  
    temperature=0,  
    max_tokens=150,  
)  

# Extract and print the generated SQL query  
sql_query = response.choices[0].message.content.strip()  
print("Generated SQL query:")  
print(sql_query)  
*/

