<?php
// 2026-10-21 06:20:36

/* PHP
Topic: Prepared Statements with PDO  

Explanation:  
Prepared statements allow you to execute the same SQL query multiple times with different parameters while keeping the query structure separate from the data. This improves performance because the database can cache the execution plan for the query. More importantly, it protects against SQL injection by ensuring that user‑supplied values are bound safely. PDO (PHP Data Objects) provides a consistent API for prepared statements across many database systems. Using PDO you can prepare a statement, bind values, and execute it in a clear, object‑oriented way.  

Code example:  
<?php  
// Create a new PDO connection (adjust DSN, username, and password as needed)  
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';  
$username = 'dbuser';  
$password = 'dbpass';  

try {  
    $pdo = new PDO($dsn, $username, $password, [  
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors  
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch results as associative arrays  
    ]);  
} catch (PDOException $e) {  
    die('Connection failed: ' . $e->getMessage());  
}  

// Prepare an INSERT statement with placeholders  
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';  
$stmt = $pdo->prepare($sql);  

// Sample data that might come from a form or API  
$userData = [  
    'username' => 'jane_doe',  
    'email'    => 'jane@example.com'  
];  

// Bind parameters and execute the statement  
$stmt->execute([  
    ':username' => $userData['username'], // Bind the username value  
    ':email'    => $userData['email']     // Bind the email value  
]);  

// Check how many rows were inserted  
echo $stmt->rowCount() . ' row(s) inserted.';  

// Retrieve the ID of the newly inserted record  
$newId = $pdo->lastInsertId();  
echo 'New user ID: ' . $newId;  
?>
*/

/* Laravel
Topic: Laravel Form Request Validation  

Explanation:  
Form Request classes encapsulate validation logic, keeping controllers clean and focused on handling requests. They are generated with artisan and extend the base FormRequest class, providing authorize() and rules() methods. authorize() determines if the user is permitted to make the request, while rules() returns an array of validation rules applied to the incoming data. You can also define custom error messages and attribute names by overriding messages() and attributes() methods. In a controller, type‑hinting the Form Request automatically triggers validation before the controller action runs.

Code example with comments:  

// app/Http/Requests/StorePostRequest.php  
<?php  

namespace App\Http\Requests;  

use Illuminate\Foundation\Http\FormRequest;  

class StorePostRequest extends FormRequest  
{  
    // Determine if the user is authorized to make this request  
    public function authorize()  
    {  
        // For example, only allow logged‑in users  
        return auth()->check();  
    }  

    // Validation rules that apply to the request data  
    public function rules()  
    {  
        return [  
            'title'   => 'required|string|max:255',   // Title is required, must be a string, max 255 chars  
            'body'    => 'required|string',           // Body must be present and be a string  
            'tags'    => 'array',                     // Tags should be an array if present  
            'tags.*'  => 'integer|exists:tags,id',    // Each tag ID must exist in the tags table  
        ];  
    }  

    // Optional: custom messages for validation failures  
    public function messages()  
    {  
        return [  
            'title.required' => 'Please provide a title for your post.',  
            'body.required'  => 'The post content cannot be empty.',  
        ];  
    }  

    // Optional: custom attribute names for error messages  
    public function attributes()  
    {  
        return [  
            'title' => 'post title',  
            'body'  => 'post body',  
        ];  
    }  
}  

// app/Http/Controllers/PostController.php  
<?php  

namespace App\Http\Controllers;  

use App\Http\Requests\StorePostRequest;  
use App\Models\Post;  

class PostController extends Controller  
{  
    // Store a new blog post using the validated request data  
    public function store(StorePostRequest $request)  
    {  
        // The request is already validated at this point  
        $post = Post::create([  
            'title' => $request->input('title'),  
            'body'  => $request->input('body'),  
            'user_id' => auth()->id(),  
        ]);  

        // Attach tags if they were provided  
        if ($request->filled('tags')) {  
            $post->tags()->attach($request->input('tags'));  
        }  

        return redirect()->route('posts.show', $post)->with('status', 'Post created successfully!');  
    }  
}  
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:
A stored procedure is a named set of SQL statements that are stored on the server and can be invoked repeatedly.  
It allows you to encapsulate complex logic, including control‑flow statements, loops, and conditional branching.  
Parameters can be defined as IN, OUT, or INOUT to pass values into the procedure and retrieve results.  
Stored procedures improve performance by reducing network round‑trips, as the logic runs entirely on the database server.  
They also promote code reuse and make it easier to enforce business rules centrally.

Code example (with comments):

CREATE PROCEDURE GetTopCustomers(IN p_limit INT, OUT p_total INT)
BEGIN
    -- Declare a variable to hold the sum of total purchases
    DECLARE v_sum INT DEFAULT 0;

    -- Create a temporary table to hold the top customers by total amount spent
    CREATE TEMPORARY TABLE tmp_top (
        customer_id INT,
        total_spent DECIMAL(10,2)
    );

    -- Insert the top customers into the temporary table
    INSERT INTO tmp_top (customer_id, total_spent)
    SELECT customer_id, SUM(amount) AS total_spent
    FROM orders
    GROUP BY customer_id
    ORDER BY total_spent DESC
    LIMIT p_limit;

    -- Calculate the overall total of the selected customers
    SELECT SUM(total_spent) INTO v_sum FROM tmp_top;

    -- Return the total via the OUT parameter
    SET p_total = v_sum;

    -- Return the result set of top customers to the caller
    SELECT * FROM tmp_top;

    -- Clean up the temporary table
    DROP TEMPORARY TABLE tmp_top;
END;
*/

/* JavaScript
Topic: Asynchronous Functions with async/await

Explanation:  
Async/await is syntax sugar built on top of JavaScript Promises, allowing asynchronous code to be written in a synchronous style. By declaring a function with the async keyword, it automatically returns a Promise and enables the use of await inside it. The await operator pauses the function execution until the awaited Promise resolves or rejects, simplifying error handling with try/catch. This approach makes complex asynchronous flows, such as sequential API calls, easier to read and maintain. It is especially useful in modern web development where network requests and I/O operations are frequent.

Code Example (with comments):

// Simulate an asynchronous operation that resolves after a delay
function fetchData(id) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            // Resolve with a mock result object
            resolve({ id: id, value: "Data for " + id });
        }, 1000);
    });
}

// Async function that retrieves two pieces of data sequentially
async function loadCombinedData() {
    try {
        // Await the first fetch; execution pauses here until the Promise resolves
        const first = await fetchData(1);
        console.log("First result:", first);

        // Await the second fetch; runs after the first completes
        const second = await fetchData(2);
        console.log("Second result:", second);

        // Combine and return the results
        return { first, second };
    } catch (error) {
        // Handle any errors that occurred during the async calls
        console.error("Error loading data:", error);
        throw error; // Re‑throw if further handling is needed
    }
}

// Call the async function and handle its returned Promise
loadCombinedData()
    .then(combined => console.log("Combined data:", combined))
    .catch(err => console.error("Failed to load combined data:", err));
*/

/* AI
Topic: Prompt Engineering for Structured JSON Output with LLMs  

Explanation:  
Prompt engineering is the practice of crafting input text to guide large language models (LLMs) toward desired behavior. By explicitly specifying the output format, role, and examples, you can coax the model to return well‑structured data such as JSON, which is easy to parse programmatically. Including a brief system instruction, a clear user request, and a few-shot example reduces ambiguity and improves consistency across calls. This technique is especially useful when integrating LLMs into pipelines that require downstream processing, validation, or storage. Properly designed prompts also help mitigate hallucinations by constraining the model’s creative freedom to the required schema.  

Code example (Python, using OpenAI’s ChatCompletion API):  

import os  
import json  
from openai import OpenAI  

# Initialize client – assumes OPENAI_API_KEY is set in environment  
client = OpenAI()  

# Define a system message that sets the role and output expectations  
system_msg = {  
    "role": "system",  
    "content": (  
        "You are a helpful assistant that extracts key information from a user’s description and returns it as a JSON object. "  
        "The JSON must contain the fields: title (string), keywords (list of strings), and summary (string). "  
        "Do not include any additional text or explanations."  
    )  
}  

# Provide a few‑shot example to illustrate the required format  
example_user = {  
    "role": "user",  
    "content": "Describe a recent project where you built a recommendation system for e‑commerce."  
}  

example_assistant = {  
    "role": "assistant",  
    "content": (  
        '{\n'  
        '  "title": "E‑commerce Recommendation System",\n'  
        '  "keywords": ["collaborative filtering", "product ranking", "Python", "Spark"],\n'  
        '  "summary": "Developed a real‑time recommendation engine using collaborative filtering, improving click‑through rate by 12%."\n'  
        '}'\n" )  
}  

# Actual user query  
user_query = {  
    "role": "user",  
    "content": "Explain a tool you created to automate data cleaning for CSV files, highlighting its main features."  
}  

# Send the messages to the model  
response = client.chat.completions.create(  
    model="gpt-4o-mini",  
    messages=[system_msg, example_user, example_assistant, user_query],  
    temperature=0.0,   # deterministic output  
)  

# The model’s raw reply (should be a JSON string)  
raw_output = response.choices[0].message.content.strip()  

# Parse the JSON safely  
try:  
    result = json.loads(raw_output)  
    print("Parsed JSON:", result)  
except json.JSONDecodeError as e:  
    print("Failed to parse JSON:", e)  
    print("Raw model output:", raw_output)  
*/

