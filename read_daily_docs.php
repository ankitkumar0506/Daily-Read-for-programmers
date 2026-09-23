<?php
// 2026-09-23 06:18:24

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:  
Prepared statements separate SQL code from data, preventing malicious input from altering the query structure. PDO (PHP Data Objects) provides a consistent interface for working with many database systems. By preparing a statement once and executing it multiple times with different parameters, you improve performance and security. Placeholders in the SQL are bound to PHP variables, and the driver handles proper escaping. This method protects against SQL injection and makes code easier to read and maintain.

Code Example with Comments:
<?php
// Create a new PDO instance (adjust DSN, username, password as needed)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'secure_pass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays
];

$pdo = new PDO($dsn, $username, $password, $options);

// Define the SQL with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute
$users = [
    ['alice', 'alice@example.com'],
    ['bob',   'bob@example.org'],
    ['carol', 'carol@example.net'],
];

foreach ($users as $user) {
    // Bind each value; PDO will handle proper escaping
    $stmt->bindParam(':username', $user[0]);
    $stmt->bindParam(':email',    $user[1]);
    $stmt->execute(); // Execute the prepared statement with current bindings
}

// Fetch data using a prepared SELECT statement
$selectSql = 'SELECT id, username, email FROM users WHERE email LIKE :domain';
$selectStmt = $pdo->prepare($selectSql);
$domain = '%@example.com';
$selectStmt->bindParam(':domain', $domain);
$selectStmt->execute();
$results = $selectStmt->fetchAll();

foreach ($results as $row) {
    echo "User ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Form Request Validation

Explanation:  
Form Request classes encapsulate validation logic, keeping controllers clean and reusable. They extend the base FormRequest class and define rules() for validation rules and authorize() to control access. When a Form Request is type‑hinted in a controller method, Laravel automatically validates the incoming request before the method runs. If validation fails, a JSON response with errors is returned for API routes or a redirect with error messages for web routes. This approach centralizes validation, makes it testable, and provides custom messages through the messages() method.

Code Example:  

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // You can add permission checks here.
        return true;
    }

    // Define validation rules for the request data.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'tags'    => 'array',
            'tags.*'  => 'exists:tags,id',
        ];
    }

    // Optional: custom error messages.
    public function messages()
    {
        return [
            'title.required'   => 'A title is required for the post.',
            'content.required' => 'Please provide the post content.',
        ];
    }
}

---  

<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // The StorePostRequest is automatically validated before this method runs.
    public function store(StorePostRequest $request)
    {
        // Validated data can be accessed via $request->validated().
        $data = $request->validated();

        // Create a new post using the validated data.
        $post = Post::create([
            'title'   => $data['title'],
            'content' => $data['content'],
        ]);

        // Sync tags if they were provided.
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        // Return a JSON response (good for APIs) or redirect for web.
        return response()->json([
            'message' => 'Post created successfully.',
            'post'    => $post,
        ], 201);
    }
}
*/

/* MySQL
Topic: MySQL Stored Procedures with IN, OUT, and INOUT parameters  

Explanation:  
Stored procedures allow you to encapsulate reusable SQL logic on the server side, reducing client‑side code duplication.  
They can accept input values (IN), return values (OUT), or both modify and return values (INOUT).  
Using parameters makes the procedure flexible for different data without changing its definition.  
Procedures run in a single transaction context, so you can control commits and rollbacks inside them.  
Properly handling NULLs and data types in parameters is essential for reliable execution.  

Code example with comments:  
CREATE PROCEDURE GetEmployeeStats(  
    IN dept_id INT,            -- input: department identifier  
    OUT emp_count INT,        -- output: number of employees in the department  
    INOUT total_salary DECIMAL(10,2)  -- input/output: running total salary, will be updated  
)  
BEGIN  
    -- Calculate the number of employees in the given department  
    SELECT COUNT(*) INTO emp_count  
    FROM employees  
    WHERE department_id = dept_id;  
  
    -- Add the sum of salaries in this department to the running total  
    SELECT IFNULL(SUM(salary),0) INTO total_salary  
    FROM employees  
    WHERE department_id = dept_id;  
  
    -- Update the INOUT parameter with the new total (adds to previous value)  
    SET total_salary = total_salary + (SELECT IFNULL(SUM(salary),0) FROM employees WHERE department_id = dept_id);  
END;  

-- Example call:  
SET @total = 0;  
CALL GetEmployeeStats(3, @cnt, @total);  
SELECT @cnt AS employee_count, @total AS cumulative_salary;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even after the outer function has finished executing.  
It allows inner functions to remember the variables of the outer function across multiple calls.  
Closures are created every time a function is defined, enabling data encapsulation and private state.  
They are frequently used for factory functions, module patterns, and event handlers.  
Understanding closures helps avoid common pitfalls like unintended shared references.  

Code example with comments:
function createCounter(initialValue) {                     // outer function that sets up a private variable
    let count = initialValue;                            // this variable is captured by the inner function
    return function increment() {                       // inner function forms a closure over 'count'
        count += 1;                                      // modify the captured variable
        console.log('Current count:', count);           // observe the updated value
    };
}
const counterA = createCounter(0);                        // each call gets its own independent closure
const counterB = createCounter(10);
counterA(); // Current count: 1
counterA(); // Current count: 2
counterB(); // Current count: 11
counterA(); // Current count: 3   (counterA’s private state is preserved)
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Code Generation  

Explanation:  
1. Few‑shot prompting supplies the language model with a small number of example input‑output pairs to guide its behavior on new, unseen tasks.  
2. By carefully selecting representative code snippets and corresponding natural‑language descriptions, you can steer the model to generate syntactically correct and idiomatic code.  
3. This technique works well with large LLMs (e.g., GPT‑4, Claude) because they can infer patterns from minimal context.  
4. The prompt is structured as a series of “User:” and “Assistant:” turns, ending with a fresh user query that the model completes.  
5. Effective few‑shot prompts balance clarity, diversity of examples, and brevity to stay within token limits while maximizing relevance.  

Code example (Python, using OpenAI’s chat completion API):  

import os  
import json  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def generate_code(user_query: str) -> str:  
    # Define a few‑shot prompt with two example pairs  
    messages = [  
        {"role": "system", "content": "You are a helpful assistant that writes Python code based on natural‑language requests."},  
        {"role": "user", "content": "Write a function that returns the factorial of a number."},  
        {"role": "assistant", "content": "def factorial(n):\n    return 1 if n == 0 else n * factorial(n-1)"},  
        {"role": "user", "content": "Create a function that checks if a string is a palindrome."},  
        {"role": "assistant", "content": "def is_palindrome(s):\n    s = s.lower().replace(' ', '')\n    return s == s[::-1]"},  
        # New user request – model will generate the answer  
        {"role": "user", "content": user_query}  
    ]  

    # Call the chat completion endpoint  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # lightweight model suitable for code generation  
        messages=messages,  
        temperature=0.2,              # low temperature for deterministic code  
        max_tokens=300                # enough space for a short function  
    )  

    # Extract and return the generated code block  
    return response.choices[0].message.content.strip()  

# Example usage  
if __name__ == "__main__":  
    query = "Write a Python function that merges two sorted lists into a single sorted list."  
    generated_code = generate_code(query)  
    print("Generated code:\n", generated_code)
*/

