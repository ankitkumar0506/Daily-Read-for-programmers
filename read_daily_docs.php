<?php
// 2026-08-24 02:46:22

/* PHP
Topic: PDO Prepared Statements

Explanation:
Prepared statements are a feature of the PDO (PHP Data Objects) extension that allow you to execute the same SQL query repeatedly with different parameters while keeping the query structure separate from the data. This separation protects against SQL injection because the driver handles proper escaping of the input values. Using prepared statements also improves performance when the same query runs many times, as the database can reuse the compiled query plan. You create a statement template with placeholders, bind actual values, and then execute the statement. PDO supports both named and positional placeholders, giving you flexibility in how you write your queries.

Code example:
<?php
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'secure_password';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Data to insert
$userData = [
    ':username' => 'alice',
    ':email'    => 'alice@example.com',
];

// Execute the prepared statement with the bound values
$stmt->execute($userData);

// Prepare a SELECT statement with positional placeholders
$stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE email = ?");

// Execute the statement, passing the email as a parameter
$stmt->execute(['alice@example.com']);

// Fetch the result
$user = $stmt->fetch();

if ($user) {
    echo "User ID: " . $user['id'] . "\n";
    echo "Username: " . $user['username'] . "\n";
    echo "Email: " . $user['email'] . "\n";
} else {
    echo "No user found.\n";
}
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:
Form Request Validation separates validation logic from controllers, keeping code clean and reusable. You generate a custom request class that defines authorization rules and validation rules for incoming data. The request is type‑hinted in a controller method, and Laravel automatically validates before the method runs. If validation fails, a redirect with error messages is generated automatically. This approach also allows you to customize error messages and add conditional validation rules in a single place.

Code example (app/Http/Requests/StorePostRequest.php):
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determines if the user is authorized to make this request
    public function authorize()
    {
        return true; // change to actual permission logic as needed
    }

    // Validation rules for the incoming request data
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'tags'    => 'array',
            'tags.*'  => 'string|distinct',
            'publish_at' => 'nullable|date|after:now',
        ];
    }

    // Custom messages (optional)
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'body.required'  => 'Please provide the content of the post.',
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
    // Store a new blog post
    public function store(StorePostRequest $request)
    {
        // Validation has already passed at this point
        $validated = $request->validated();

        // Create the post using the validated data
        $post = Post::create($validated);

        // Attach tags if they were provided
        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('posts.show', $post)
                         ->with('status', 'Post created successfully!');
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs improve readability by allowing you to break complex queries into logical building blocks. They are defined using the WITH clause and can be either non‑recursive or recursive. Recursive CTEs are useful for traversing hierarchical data such as organization charts or bill‑of‑materials structures. MySQL 8.0 and later support both types, making it easier to write clear, maintainable queries without relying on temporary tables or subqueries.  

Code Example:  
-- Non‑recursive CTE to get the top 5 salespeople by total sales  
WITH top_sales AS (  
   SELECT salesperson_id, SUM(amount) AS total_sales  
   FROM sales  
   GROUP BY salesperson_id  
   ORDER BY total_sales DESC  
   LIMIT 5  
)  
SELECT * FROM top_sales;  

-- Recursive CTE to build an employee hierarchy (org chart)  
WITH RECURSIVE org_chart AS (  
   -- Anchor member: start with top‑level managers (no manager)  
   SELECT employee_id, manager_id, 1 AS depth  
   FROM employees  
   WHERE manager_id IS NULL  

   UNION ALL  

   -- Recursive member: find direct reports of the current level  
   SELECT e.employee_id, e.manager_id, oc.depth + 1  
   FROM employees e  
   JOIN org_chart oc ON e.manager_id = oc.employee_id  
)  
SELECT employee_id, manager_id, depth  
FROM org_chart  
ORDER BY depth, manager_id;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures allow inner functions to remember the variables of outer functions, enabling data privacy and function factories.  
They are created every time a function is defined, capturing the surrounding environment at that moment.  
Common uses include emulating private variables, creating partial applications, and maintaining state in asynchronous callbacks.  
Understanding closures is essential for writing modular, maintainable JavaScript code.  

Code example (with comments):  

function createCounter(start) {               // outer function with a parameter
    let count = start;                       // private variable, not directly accessible outside
    return function increment(step = 1) {    // inner function forms a closure over 'count'
        count += step;                       // modifies the captured variable
        console.log('Current count:', count);
        return count;                        // returns the updated value
    };
}                                            // end of createCounter

const counterA = createCounter(5);            // counterA has its own 'count' starting at 5
counterA();                                   // prints: Current count: 6
counterA(3);                                  // prints: Current count: 9

const counterB = createCounter(10);           // a separate closure with its own 'count'
counterB(2);                                   // prints: Current count: 12
counterB();                                    // prints: Current count: 13

// The two counters operate independently because each call to createCounter
// creates a new lexical environment that the returned function closes over.
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with GPT‑4  

Explanation:  
Few‑shot prompting lets a language model learn a new task from only a handful of examples embedded in the prompt. By carefully formatting the examples and clearly stating the instruction, the model can infer the pattern and apply it to unseen inputs. The technique is especially useful when a dedicated fine‑tuned model is unavailable or when rapid prototyping is needed. Choosing delimiters that the model recognises (e.g., “Q:” and “A:”) reduces ambiguity. Combining a concise system message with a few demonstration pairs often yields higher accuracy than a single‑shot prompt.  

Code example (Python, using the OpenAI API):  

import os  
import openai  

# Load your API key from an environment variable or configuration file  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text: str) -> str:  
    # Build a few‑shot prompt that teaches the model the sentiment task  
    prompt = (  
        "You are a helpful assistant that classifies the sentiment of short sentences as Positive, Negative, or Neutral.\n\n"  
        "Q: I love the new design of the app!\n"  
        "A: Positive\n\n"  
        "Q: The update caused many bugs.\n"  
        "A: Negative\n\n"  
        "Q: The app loads quickly.\n"  
        "A: Neutral\n\n"  
        f"Q: {text}\n"  
        "A:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,            # deterministic output for classification  
        max_tokens=5,               # we only need a short label  
    )  

    # Extract the model's answer and strip whitespace/newlines  
    answer = response.choices[0].message["content"].strip()  
    return answer  

# Example usage  
if __name__ == "__main__":  
    sample = "The recent price increase makes me unhappy."  
    sentiment = classify_sentiment(sample)  
    print(f"Input: {sample}\nPredicted sentiment: {sentiment}")  
*/

