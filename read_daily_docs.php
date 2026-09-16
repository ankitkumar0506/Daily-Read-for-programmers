<?php
// 2026-09-16 06:26:33

/* PHP
PHP Topic: PDO Prepared Statements for Secure Database Access  

Explanation:  
PDO (PHP Data Objects) provides a uniform interface for accessing different databases. Using prepared statements separates SQL code from data, preventing SQL injection attacks. Placeholders are used in the query and bound to variables at execution time. PDO also supports transaction handling and error reporting via exceptions. This approach makes the code more maintainable and portable across MySQL, PostgreSQL, SQLite, and other DBMS.

Code example (MySQL connection, prepared SELECT, and fetching results):

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a PDO instance (replace credentials as needed)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare a SELECT statement with named placeholders
$sql = 'SELECT id, name, email FROM users WHERE status = :status AND created_at > :date';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$status = 'active';
$date   = '2023-01-01';
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':date',   $date,   PDO::PARAM_STR);

// Execute the statement
$stmt->execute();

// Fetch all matching rows
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "ID: {$user['id']} - Name: {$user['name']} - Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:
Form Request classes encapsulate validation logic, keeping controllers clean and focused on handling business flow. They extend the base FormRequest class, allowing you to define authorization rules and validation rules in dedicated methods. When a request is type‑hinted in a controller method, Laravel automatically runs the validation before the controller code executes. If validation fails, Laravel redirects back with error messages and old input; if it passes, you can safely retrieve the validated data. This pattern promotes reuse, testability, and a clear separation of concerns throughout the application.

Code Example with Comments:

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine whether the user is authorized to make this request.
    public function authorize()
    {
        // Return true to allow any authenticated user; customize as needed.
        return true;
    }

    // Define the validation rules for the incoming request data.
    public function rules()
    {
        return [
            // Title is required, must be a string, and max length of 255 characters.
            'title' => 'required|string|max:255',
            // Body is required and must be a string.
            'body' => 'required|string',
            // Tags are optional but must be an array if present.
            'tags' => 'sometimes|array',
            // Each tag ID must exist in the tags table.
            'tags.*' => 'exists:tags,id',
        ];
    }

    // (Optional) Customize the error messages returned for validation failures.
    public function messages()
    {
        return [
            'title.required' => 'Please provide a title for the post.',
            'body.required'  => 'The post content cannot be empty.',
        ];
    }
}

// In a controller, type‑hint the Form Request to trigger validation automatically.
<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        // At this point, the request data has already passed validation.
        $validatedData = $request->validated();

        // Create the post using the validated data.
        $post = Post::create($validatedData);

        // Optionally attach tags if they were provided.
        if (isset($validatedData['tags'])) {
            $post->tags()->attach($validatedData['tags']);
        }

        // Redirect with a success message.
        return redirect()->route('posts.index')
                         ->with('success', 'Post created successfully.');
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and improves readability by allowing you to break complex queries into logical building blocks. MySQL 8.0 introduced support for both non‑recursive and recursive CTEs. Recursive CTEs are useful for traversing hierarchical data such as organizational charts or folder structures. They consist of an anchor member that provides the starting rows and a recursive member that repeatedly references the CTE until no new rows are produced.

Code example (calculating a simple hierarchy of employee managers):

-- Define the CTE named employee_hierarchy
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: start with top‑level managers (no manager_id)
    SELECT 
        employee_id,
        employee_name,
        manager_id,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to their managers
    SELECT 
        e.employee_id,
        e.employee_name,
        e.manager_id,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id
)
-- Query the CTE to list each employee with their depth in the hierarchy
SELECT 
    employee_id,
    employee_name,
    manager_id,
    level
FROM employee_hierarchy
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Debouncing User Input in JavaScript  

Explanation:  
Debouncing is a technique that limits how often a function can be executed by postponing its call until after a specified waiting period has elapsed since the last invocation. It is especially useful for performance‑critical events such as window resizing, scrolling, or typing in an input field where rapid, repeated calls can cause lag. The debounce wrapper returns a new function that resets a timer each time it is invoked; the original function runs only when the timer completes without interruption. This prevents unnecessary processing and reduces load on the browser. Implementing debounce manually helps you understand closures and timer management without relying on external libraries.

Code example (with comments):

function debounce(func, wait) {
    // Holds the timeout ID across calls
    let timeoutId = null;

    // Return a new function that will be used in place of the original
    return function(...args) {
        // If a timer is already running, clear it
        if (timeoutId !== null) {
            clearTimeout(timeoutId);
        }

        // Start a new timer; when it finishes, invoke the original function
        timeoutId = setTimeout(() => {
            // Preserve the correct this context and pass through arguments
            func.apply(this, args);
        }, wait);
    };
}

// Example usage: log the input value only after the user stops typing for 300 ms
const searchInput = document.getElementById('search');
searchInput.addEventListener('input', debounce(function(event) {
    console.log('Searching for:', event.target.value);
}, 300));
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs inside the prompt, guiding it to produce the desired format for new queries. This technique is useful when you lack large labeled datasets but need consistent, structured responses. By carefully crafting the examples and using system messages to set behavior, you can achieve high accuracy on tasks such as data extraction, classification, or code generation. The approach works across GPT‑3.5‑Turbo and GPT‑4 models and can be integrated into any application that calls the OpenAI API. Adjust the examples to match the target task and monitor token usage, as each example adds to the overall cost.

Code example (Python, using the openai library):
import os
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Define a system message that sets the assistant’s role
    system_msg = {
        "role": "system",
        "content": "You are a helpful assistant that classifies sentences as Positive, Negative, or Neutral."
    }

    # Provide two few‑shot examples to illustrate the desired output format
    examples = [
        {"role": "user", "content": "I love the new phone I bought!"},
        {"role": "assistant", "content": "Positive"},
        {"role": "user", "content": "The traffic today was terrible."},
        {"role": "assistant", "content": "Negative"}
    ]

    # The actual user query to classify
    user_msg = {"role": "user", "content": text}

    # Build the full message list
    messages = [system_msg] + examples + [user_msg]

    # Call the Chat Completion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=messages,
        temperature=0.0  # deterministic output
    )

    # Extract and return the model’s classification
    classification = response.choices[0].message.content.strip()
    return classification

# Example usage
if __name__ == "__main__":
    sample = "The movie was okay, not great but not bad either."
    result = classify_sentiment(sample)
    print(f"Sentiment: {result}")
*/

