<?php
// 2026-11-24 11:24:22

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:
Prepared statements separate SQL code from data, preventing SQL injection attacks. PDO (PHP Data Objects) provides a consistent interface for interacting with many database systems. You first prepare the SQL with placeholders, then bind values and execute the statement. This approach also allows the database to cache the query plan, improving performance for repeated executions. Errors can be caught using exceptions, making debugging easier.

Code Example:
<?php
// Enable PDO exceptions for error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO connection (replace with your DSN, username, password)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass', $options);

// The SQL query with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";

try {
    // Prepare the statement once
    $stmt = $pdo->prepare($sql);

    // Sample data to insert
    $data = [
        'username' => 'johndoe',
        'email'    => 'john@example.com',
    ];

    // Bind the values and execute the statement
    $stmt->execute($data);

    echo "User inserted with ID: " . $pdo->lastInsertId();
} catch (PDOException $e) {
    // Handle any errors
    echo "Database error: " . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:  
Form Request Validation is a dedicated class that encapsulates validation logic, keeping controllers clean and reusable.  
You generate a request class with artisan, define rules and custom messages, and type‑hint it in controller methods.  
When the request is injected, Laravel automatically validates the incoming data before the controller code runs.  
If validation fails, a JSON response (for API) or a redirect with errors (for web) is returned without extra code.  
This approach promotes single responsibility, testability, and centralized error handling.

Code Example:

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Return true to allow all users; adjust as needed.
        return true;
    }

    // Define validation rules for the incoming request data.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'tags'    => 'array',
            'tags.*'  => 'integer|exists:tags,id',
        ];
    }

    // Optional: custom error messages.
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'body.required'  => 'Please provide the post content.',
        ];
    }
}

// ------------------------------------------------------------

<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // Store a new blog post using the validated request data.
    public function store(StorePostRequest $request)
    {
        // At this point, the request data has already passed validation.
        $validated = $request->validated();

        // Create the post with the validated attributes.
        $post = Post::create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            // Additional fields can be added here.
        ]);

        // Attach tags if any were provided.
        if (!empty($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        // Return a JSON response indicating success.
        return response()->json([
            'message' => 'Post created successfully.',
            'post'    => $post,
        ], 201);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
- A CTE is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
- It is defined using the WITH clause and improves readability by allowing you to break complex queries into logical parts.  
- Recursive CTEs enable hierarchical or tree‑like data processing, such as traversing parent‑child relationships.  
- The recursion anchor query runs first, then the recursive member repeatedly executes until no new rows are produced.  
- Recursive CTEs are useful for organizational charts, bill‑of‑materials, or generating series of numbers.  

Code example (MySQL 8.0+):

-- Create a sample table representing an employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL   -- NULL for top‑level manager
);

INSERT INTO employees VALUES
(1, 'Alice', NULL),
(2, 'Bob',   1),
(3, 'Carol', 1),
(4, 'Dave',  2),
(5, 'Eve',   2),
(6, 'Frank', 3);

-- Recursive CTE to list each employee with their hierarchy level
WITH RECURSIVE emp_hierarchy AS (
    -- Anchor member: top‑level managers (level 1)
    SELECT emp_id, emp_name, manager_id, 1 AS lvl
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to their manager's rows
    SELECT e.emp_id, e.emp_name, e.manager_id, eh.lvl + 1
    FROM employees e
    JOIN emp_hierarchy eh ON e.manager_id = eh.emp_id
)
SELECT emp_id,
       emp_name,
       manager_id,
       lvl AS hierarchy_level
FROM emp_hierarchy
ORDER BY lvl, manager_id, emp_id;
*/

/* JavaScript
Topic: JavaScript Closures  

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures are created each time a function is defined, capturing the lexical environment at that point.  
They enable data privacy, function factories, and maintaining state across multiple invocations without using global variables.  
Understanding closures is essential for mastering asynchronous patterns, callbacks, and module design in JavaScript.  

Code Example:  
function makeCounter() {                 // outer function creates a private variable  
    let count = 0;                       // this variable is captured by the inner function  

    return function() {                  // the inner function forms a closure  
        count++;                         // it can read and modify 'count' even after makeCounter ends  
        console.log('Current count:', count);  
    };  
}  

const counterA = makeCounter(); // each call to makeCounter produces a new independent closure  
counterA(); // Current count: 1  
counterA(); // Current count: 2  

const counterB = makeCounter(); // separate closure with its own 'count' variable  
counterB(); // Current count: 1   (independent from counterA)  
*/

/* AI
Topic: Function Calling with OpenAI’s Chat Completion API  

Explanation:  
Function calling lets a language model invoke a predefined function when it detects a user request that matches the function’s purpose. This bridges natural language understanding with exact, machine‑readable output, reducing hallucination. The developer registers a JSON schema for each function, and the model returns a “function_call” object instead of plain text. The client then executes the function and can feed the result back to the model for a seamless interactive loop. This pattern is especially useful for tasks like retrieving weather data, database queries, or code execution.

Code example (Python, uses openai library):
import os
import openai

# Set your API key – replace with your actual key or use environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a function the model may call
def get_current_weather(location: str, unit: str = "celsius"):
    """
    Simulated weather lookup – in a real app you would query a weather service.
    Returns a dictionary matching the schema below.
    """
    # Dummy data for illustration
    return {
        "location": location,
        "temperature": 22,
        "unit": unit,
        "forecast": "Partly cloudy"
    }

# Register the function schema for the model
weather_function = {
    "name": "get_current_weather",
    "description": "Get the current weather for a given city",
    "parameters": {
        "type": "object",
        "properties": {
            "location": {"type": "string", "description": "Name of the city"},
            "unit": {
                "type": "string",
                "enum": ["celsius", "fahrenheit"],
                "description": "Temperature unit"
            }
        },
        "required": ["location"]
    }
}

# Send a user message that may trigger the function
messages = [{"role": "user", "content": "What's the weather like in Paris right now?"}]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",                 # any model that supports function calling
    messages=messages,
    functions=[weather_function],
    function_call="auto"                  # let the model decide when to call
)

# Check if the model decided to call the function
if response.choices[0].message.get("function_call"):
    fc = response.choices[0].message["function_call"]
    function_name = fc["name"]
    arguments = eval(fc["arguments"])    # convert JSON string to dict (use json.loads in production)

    # Execute the matched function
    if function_name == "get_current_weather":
        function_response = get_current_weather(**arguments)

    # Send the function result back to the model for a final answer
    follow_up = [
        {"role": "assistant", "content": None, "function_call": fc},
        {"role": "function", "name": function_name, "content": str(function_response)}
    ]

    final_response = openai.ChatCompletion.create(
        model="gpt-4o-mini",
        messages=follow_up
    )

    print(final_response.choices[0].message["content"])
else:
    # Model responded without needing a function call
    print(response.choices[0].message["content"])
*/

