<?php
// 2026-11-19 09:06:49

/* PHP
Topic: PDO Prepared Statements for Secure Database Interaction  

Explanation:  
- PDO (PHP Data Objects) provides a uniform interface for accessing multiple databases.  
- Using prepared statements separates SQL code from data, preventing SQL injection attacks.  
- Placeholders in the query are bound to actual values at execution time, allowing the driver to handle proper escaping.  
- Prepared statements can be reused multiple times with different parameters, improving performance.  
- Error handling with PDO exceptions makes debugging easier and keeps code clean.  

Code example (with comments):  

<?php
// Enable PDO exceptions for error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace DSN, username, password with your credentials)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass', $options);

// Define an INSERT query with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$users = [
    ['username' => 'alice', 'email' => 'alice@example.com'],
    ['username' => 'bob',   'email' => 'bob@example.org'],
];

// Execute the prepared statement for each user
foreach ($users as $user) {
    // Bind values to the named placeholders and execute
    $stmt->execute([
        ':username' => $user['username'],
        ':email'    => $user['email'],
    ]);
}

// Retrieve data using a SELECT with positional placeholders
$selectSql = "SELECT id, username, email FROM users WHERE created_at > ?";
$stmtSelect = $pdo->prepare($selectSql);
$stmtSelect->execute([date('Y-m-d H:i:s', strtotime('-1 day'))]);

// Fetch and display results
while ($row = $stmtSelect->fetch()) {
    echo "ID: {$row['id']}, User: {$row['username']}, Email: {$row['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Custom Form Request Validation  

Explanation:  
Laravel’s Form Request classes encapsulate validation logic, keeping controllers clean and focused on handling requests.  
You create a Form Request with artisan, define rules and custom messages, and type‑hint it in a controller method.  
When the request is injected, Laravel automatically runs the validation before the controller logic executes.  
If validation fails, a JSON response (or redirect) is generated based on the request type.  
Using Form Requests also provides an authorized() method for permission checks on the incoming data.  

Code Example (app/Http/Requests/StorePostRequest.php):  
<?php  
namespace App\Http\Requests;  

use Illuminate\Foundation\Http\FormRequest;  

class StorePostRequest extends FormRequest  
{  
    // Determine if the user is authorized to make this request  
    public function authorize()  
    {  
        // Example: only allow logged‑in users  
        return auth()->check();  
    }  

    // Validation rules for the request data  
    public function rules()  
    {  
        return [  
            'title'   => 'required|string|max:255',  
            'content' => 'required|string',  
            'tags'    => 'array',  
            'tags.*'  => 'integer|exists:tags,id',  
        ];  
    }  

    // Custom error messages (optional)  
    public function messages()  
    {  
        return [  
            'title.required' => 'A title is required for the post.',  
            'content.required' => 'Please provide the post content.',  
        ];  
    }  
}  

Usage in a controller (app/Http/Controllers/PostController.php):  
<?php  
namespace App\Http\Controllers;  

use App\Http\Requests\StorePostRequest;  
use App\Models\Post;  

class PostController extends Controller  
{  
    // Store a new blog post using the custom request for validation  
    public function store(StorePostRequest $request)  
    {  
        // Validation has already passed at this point  

        // Retrieve only the validated fields  
        $data = $request->validated();  

        // Create the post and associate tags if provided  
        $post = Post::create([  
            'title'   => $data['title'],  
            'content' => $data['content'],  
            'user_id' => auth()->id(),  
        ]);  

        if (!empty($data['tags'])) {  
            $post->tags()->attach($data['tags']); // assuming a many‑to‑many relation  
        }  

        return response()->json([  
            'message' => 'Post created successfully.',  
            'post'    => $post,  
        ], 201);  
    }  
}  
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
When the CTE is defined with the RECURSIVE keyword, it can refer to itself, enabling hierarchical or iterative data processing.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until a termination condition is met.  
They are especially useful for traversing tree structures such as organizational charts, category hierarchies, or graph paths.  

Code example:  
-- Create a simple table to store an employee hierarchy  
CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    emp_name VARCHAR(50),  
    manager_id INT NULL   -- NULL for top‑level manager  
);  

-- Insert sample data  
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES  
(1, 'Alice', NULL),   -- CEO  
(2, 'Bob', 1),        -- reports to Alice  
(3, 'Carol', 1),      -- reports to Alice  
(4, 'Dave', 2),       -- reports to Bob  
(5, 'Eve', 2),        -- reports to Bob  
(6, 'Frank', 3);      -- reports to Carol  

-- Recursive CTE to list each employee together with the chain of managers above them  
WITH RECURSIVE manager_path AS (  
    -- Anchor member: start with each employee and their immediate manager  
    SELECT emp_id, emp_name, manager_id, CAST(emp_name AS CHAR(200)) AS path  
    FROM employees  
    WHERE manager_id IS NULL   -- top‑level managers (e.g., CEO)  

    UNION ALL  

    -- Recursive member: join employees to the previous level to build the path  
    SELECT e.emp_id, e.emp_name, e.manager_id,  
           CONCAT(mp.path, ' -> ', e.emp_name) AS path  
    FROM employees e  
    JOIN manager_path mp ON e.manager_id = mp.emp_id  
)  
SELECT emp_id, emp_name, path  
FROM manager_path  
ORDER BY emp_id;   -- Result shows each employee with the full reporting chain  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing. This allows the inner function to retain a reference to the outer scope’s variables, preserving their values between calls. Closures are useful for data encapsulation, creating private state, and implementing function factories. Because the variables are kept alive by the closure, they are not garbage‑collected until the inner function itself becomes unreachable. Understanding closures helps avoid common pitfalls such as unintended sharing of mutable state in loops.

Code Example:
function makeCounter(start) {
    let count = start;                 // count is private to makeCounter
    return function() {
        count += 1;                    // inner function forms a closure over count
        return count;
    };
}

const counterA = makeCounter(0);       // creates a new closure with its own count
console.log(counterA());               // 1
console.log(counterA());               // 2

const counterB = makeCounter(10);      // separate closure, independent count
console.log(counterB());               // 11
console.log(counterA());               // 3  (counterA’s count continues independently)
*/

/* AI
Topic: Prompt Engineering with Few‑Shot Learning for Text Classification

Explanation:  
Few‑shot prompting lets a language model learn a task from just a handful of examples embedded in the prompt. By carefully formatting the examples and the query, you can guide the model to produce accurate classifications without any fine‑tuning. This approach is especially useful when labeled data is scarce or when rapid prototyping is needed. The prompt typically includes a clear instruction, several input‑label pairs (the "shots"), and finally the new input whose label the model must predict. Adjusting the number of shots, the phrasing of the instruction, and the delimiters can have a big impact on performance. Below is a Python example using the OpenAI Chat Completion API to classify sentiment with three few‑shot examples.

Code example (Python, requires openai package and a valid API key):
import os
import openai

# Set your OpenAI API key (ensure it is kept secret)
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Construct a few‑shot prompt with instruction, examples, and the target input
    prompt = """You are an assistant that classifies the sentiment of a sentence as Positive, Negative, or Neutral.

Example 1:
Sentence: I love the new design of this app!
Sentiment: Positive

Example 2:
Sentence: The update broke my workflow and caused many errors.
Sentiment: Negative

Example 3:
Sentence: The app works as expected.
Sentiment: Neutral

Now classify the following sentence:
Sentence: {} 
Sentiment:""".format(text)

    # Call the chat completion endpoint with the prompt as a user message
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",               # choose a suitable model
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,                   # deterministic output for classification
        max_tokens=10                      # limit response length
    )
    # Extract and clean the model's answer
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
sample = "I'm not sure if this feature is useful."
print("Input:", sample)
print("Predicted Sentiment:", classify_sentiment(sample))
*/

