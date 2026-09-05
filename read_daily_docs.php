<?php
// 2026-09-05 06:05:41

/* PHP
Topic: Prepared Statements using PDO

Explanation:
Prepared statements separate SQL code from the data values, which prevents SQL injection attacks.  
When a statement is prepared, the database parses and compiles the query once, then you can execute it many times with different parameters.  
PDO (PHP Data Objects) provides a consistent interface for prepared statements across many database systems.  
You bind values to placeholders using bindParam() or bindValue(), or pass an array directly to execute().  
After execution, you can fetch results with fetch(), fetchAll(), or iterate over the statement object.

Code example (PHP):
<?php
// Create a PDO connection (replace DSN, username, password with your own values)
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Return rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$usernameValue = 'alice';
$emailValue = 'alice@example.com';
$stmt->bindParam(':username', $usernameValue, PDO::PARAM_STR);
$stmt->bindParam(':email', $emailValue, PDO::PARAM_STR);

// Execute the statement; the bound values are sent to the database safely
$stmt->execute();

// Get the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: $newUserId\n";

// Example of a SELECT with positional placeholders
$selectSql = 'SELECT id, username, email FROM users WHERE email = ?';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([$emailValue]); // Pass parameters as an array

// Fetch and display the result
$user = $selectStmt->fetch();
if ($user) {
    echo "Found user: {$user['username']} ({$user['email']})\n";
} else {
    echo "No user found with that email.\n";
}
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel  

Explanation:  
Form Request classes encapsulate validation logic for incoming HTTP requests, keeping controllers clean and focused on business logic. They are generated via artisan and automatically resolve through Laravel’s service container when type‑hinted in controller methods. Inside the Form Request you define authorization rules and a set of validation rules that Laravel applies before the controller runs. If validation fails, Laravel redirects back with error messages; otherwise the validated data is available via the request instance. This approach promotes reuse, testability, and a single source of truth for request validation.  

Code example:  

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Return true to allow all users, or implement your own logic.
        return true;
    }

    // Define the validation rules that apply to the request.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|max:2048',
        ];
    }

    // Customize the validation error messages (optional).
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'content.required' => 'Please provide the post content.',
        ];
    }
}

// In a controller, type‑hint the Form Request to trigger validation.

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        // At this point, the request has already been validated.
        $validated = $request->validated(); // Returns only the validated fields.

        // Create a new post using the validated data.
        $post = Post::create($validated);

        // Return a response or redirect.
        return redirect()->route('posts.show', $post->id)
                         ->with('success', 'Post created successfully.');
    }
}
*/

/* MySQL
Topic: MySQL Stored Procedures with IN, OUT, and INOUT Parameters

Explanation:  
Stored procedures let you encapsulate reusable SQL logic on the server side, reducing client‑side round trips.  
They can accept input values (IN), return values (OUT), or both modify and return values (INOUT).  
Using parameters improves code readability and allows dynamic behavior without constructing raw queries.  
Procedures run in a single transaction context, so you can control commits and rollbacks inside them.  
Properly handling NULLs and default values in parameters helps avoid unexpected errors.

Code example (with inline comments):
CREATE PROCEDURE GetEmployeeStats (
    IN  p_department_id INT,          -- input: department to filter
    OUT p_total_salary DECIMAL(10,2),-- output: sum of salaries in that department
    INOUT p_employee_count INT       -- input/output: initial count, returns final count
)
BEGIN
    -- Initialize output variables
    SET p_total_salary = 0;
    SET p_employee_count = IFNULL(p_employee_count, 0);

    -- Calculate total salary and employee count
    SELECT 
        SUM(salary)   INTO p_total_salary,
        COUNT(*)      INTO p_employee_count
    FROM employees
    WHERE department_id = p_department_id;

    -- If no employees found, ensure outputs are zero
    IF p_total_salary IS NULL THEN
        SET p_total_salary = 0;
    END IF;
END;

-- Example call:
-- Declare variables to receive output
SET @dept_id = 3;
SET @total = 0;
SET @count = 0;

CALL GetEmployeeStats(@dept_id, @total, @count);
SELECT @total AS total_salary, @count AS employee_count;
*/

/* JavaScript
Topic Name: Closures in JavaScript

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. JavaScript creates a lexical environment for each function call, storing references to variables that were in scope at the time the function was defined. Closures allow private state, function factories, and the creation of modules without exposing internal variables. Because the inner function holds a reference to the outer variables, those variables are not garbage‑collected until the closure itself is no longer reachable. Understanding closures is essential for writing efficient asynchronous code, callbacks, and maintaining encapsulation in larger codebases.

Code Example with Comments:
function createCounter(initialValue) {               // Outer function defines a private variable
    let count = initialValue;                       // This variable is captured by the inner function
    return function increment(step = 1) {           // The returned function forms a closure over 'count'
        count += step;                              // It can read and modify 'count' each call
        console.log(`Current count: ${count}`);    // Demonstrates that the state persists
    };
}
const counterA = createCounter(5);  // counterA has its own private 'count' starting at 5
counterA();                         // Output: Current count: 6
counterA(3);                        // Output: Current count: 9

const counterB = createCounter(10); // counterB is independent of counterA
counterB();                         // Output: Current count: 11
counterA();                         // Output: Current count: 10   (counterA's state continues)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you teach a language model a new task by providing a few example input‑output pairs directly in the prompt. This technique is powerful because it requires no fine‑tuning and works across many domains such as classification, translation, or data extraction. The key is to format the examples clearly and to keep the final user query distinct from the demonstrations. By adjusting temperature, max_tokens, and the stop sequence, you can control the model’s determinism and output length. This approach is especially useful for programmers who need quick, ad‑hoc AI assistance without managing large training pipelines.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt for sentiment analysis  
few_shot_prompt = """You are a helpful assistant that classifies the sentiment of a sentence as Positive, Negative, or Neutral.  

Example 1:  
Sentence: I love the new design of the website!  
Sentiment: Positive  

Example 2:  
Sentence: The update caused many bugs and crashes.  
Sentiment: Negative  

Example 3:  
Sentence: The documentation was okay, nothing special.  
Sentiment: Neutral  

Now classify the following sentence:  
Sentence: The customer support response time was satisfactory.  
Sentiment:"""  

# Call the Chat Completion endpoint with a deterministic temperature  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=[{"role": "user", "content": few_shot_prompt}],  
    temperature=0.0,          # deterministic output  
    max_tokens=10,            # short answer expected  
    stop=None                 # let the model stop at its own end of line  
)  

# Extract and print the model's answer  
sentiment = response.choices[0].message.content.strip()  
print("Predicted Sentiment:", sentiment)  
*/

