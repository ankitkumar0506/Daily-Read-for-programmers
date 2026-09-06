<?php
// 2026-09-06 06:19:12

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:
Prepared statements allow you to separate SQL code from data values, which prevents SQL injection attacks.  
PDO provides a uniform interface for interacting with many different database systems.  
You first prepare the SQL query with placeholders, then bind the actual values before execution.  
This approach also enables the database engine to reuse the compiled query plan for better performance.  
If an error occurs, PDO can throw exceptions, making debugging easier.

Code example with comments:
<?php
// Create a new PDO instance (replace DSN, username, and password with your own values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (email, password_hash, created_at) VALUES (:email, :hash, :created)";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$email = 'alice@example.com';
$hash = password_hash('secret123', PASSWORD_DEFAULT);
$created = date('Y-m-d H:i:s');
$stmt->bindParam(':email', $email);
$stmt->bindParam(':hash', $hash);
$stmt->bindParam(':created', $created);

// Execute the statement
$stmt->execute();

// Optionally get the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: " . $newUserId;
?>
*/

/* Laravel
Laravel Service Container and Automatic Dependency Injection

The service container is the core of Laravel’s inversion of control system, allowing classes to be resolved automatically without manual instantiation. When a class type‑hint is present in a controller’s constructor, Laravel injects the appropriate instance from the container. This promotes loose coupling and makes testing easier through mock injection. Bindings can be defined in service providers to control lifecycle (singleton, transient, etc.). The container also resolves dependencies of dependencies, building full object graphs on demand.

<?php
namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ReportService will be injected automatically by the container
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // Action that utilizes the injected service
    public function index(Request $request)
    {
        // Pass request data as filters to the service method
        $reports = $this->reportService->getReports($request->all());

        // Return JSON response
        return response()->json($reports);
    }
}

namespace App\Services;

class ReportService
{
    // Example method that could query models or external APIs
    public function getReports(array $filters)
    {
        // For demonstration, return a static array
        return [
            ['id' => 1, 'title' => 'Monthly Sales'],
            ['id' => 2, 'title' => 'User Activity'],
        ];
    }
}

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ReportService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind ReportService as a singleton in the container
        $this->app->singleton(ReportService::class, function ($app) {
            return new ReportService();
        });
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by allowing you to break complex queries into logical building blocks.  
Recursive CTEs enable hierarchical or graph traversals, such as retrieving an organizational chart or processing a bill‑of‑materials tree.  
The recursion is controlled by an anchor member (the base case) and a recursive member that references the CTE itself.  
MySQL 8.0+ fully supports both non‑recursive and recursive CTEs, making it possible to write elegant queries without temporary tables.

Code example (employee hierarchy using a recursive CTE):

-- Define a CTE named emp_tree that starts with the top‑level manager (manager_id IS NULL)
-- and recursively adds direct reports until the hierarchy is fully expanded.
WITH RECURSIVE emp_tree AS (
    -- Anchor member: select the root employee(s)
    SELECT 
        employee_id,
        employee_name,
        manager_id,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to the previously built tree
    SELECT 
        e.employee_id,
        e.employee_name,
        e.manager_id,
        et.level + 1 AS level
    FROM employees e
    INNER JOIN emp_tree et ON e.manager_id = et.employee_id
)
-- Final query: retrieve the entire hierarchy ordered by level and employee name
SELECT 
    employee_id,
    employee_name,
    manager_id,
    level
FROM emp_tree
ORDER BY level, employee_name;
*/

/* JavaScript
Topic: Async/Await for handling asynchronous operations  

Explanation:  
Async/await is syntactic sugar built on top of Promises that lets you write asynchronous code that looks and behaves like synchronous code.  
An async function always returns a Promise, and the await keyword pauses execution until the Promise settles.  
Using await eliminates the need for chained .then() calls, making error handling with try/catch straightforward.  
It improves readability, especially when dealing with multiple sequential asynchronous steps.  
Be careful to only use await inside functions declared with the async keyword; otherwise a syntax error occurs.  

Code example:  
async function getUserData(userId) {  
    // Fetch user info from the API; await pauses until the fetch Promise resolves  
    const response = await fetch(`https://api.example.com/users/${userId}`);  
    // If the response is not OK, throw an error to be caught by the outer try/catch  
    if (!response.ok) {  
        throw new Error('Network response was not ok');  
    }  
    // Parse the JSON body; await ensures we wait for the parsing to complete  
    const userData = await response.json();  
    return userData; // This value becomes the resolved value of the Promise returned by the async function  
}  

// Using the async function with proper error handling  
(async () => {  
    try {  
        const data = await getUserData(42);  
        console.log('User data:', data);  
    } catch (error) {  
        console.error('Failed to fetch user data:', error);  
    }  
})();
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small number of example interactions inside the prompt to guide the model’s behavior without fine‑tuning. By formatting the prompt as a series of user‑assistant turns, the model infers the desired pattern and can generalize to new inputs. This technique works well for tasks like text classification, data extraction, or style transfer where a full training pipeline would be overkill. The key is to keep examples clear, consistent, and relevant to the target task. Adjusting the system message and temperature further refines the model’s adherence to the demonstrated style.  

Code example (Python, using the OpenAI library):  

import os  
import openai  

# Load your API key from an environment variable or directly assign it  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(user_input):  
    # Define the system message that sets the overall role of the assistant  
    system_msg = {"role": "system", "content": "You are a helpful assistant that classifies the sentiment of short sentences as Positive, Negative, or Neutral."}  

    # Provide a few example user‑assistant pairs (few‑shot)  
    examples = [  
        {"role": "user", "content": "I love the new design of the app!"},  
        {"role": "assistant", "content": "Positive"},  
        {"role": "user", "content": "The update broke my workflow."},  
        {"role": "assistant", "content": "Negative"},  
        {"role": "user", "content": "It works as expected."},  
        {"role": "assistant", "content": "Neutral"}  
    ]  

    # Append the actual user query at the end of the prompt sequence  
    user_msg = {"role": "user", "content": user_input}  

    # Call the chat completion endpoint with the assembled message list  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # choose a suitable model  
        messages=[system_msg] + examples + [user_msg],  
        temperature=0.0               # low temperature for deterministic output  
    )  

    # Extract and return the assistant’s reply (the sentiment label)  
    sentiment = response.choices[0].message.content.strip()  
    return sentiment  

# Example usage  
if __name__ == "__main__":  
    text = "The customer service was surprisingly quick and friendly."  
    print("Sentiment:", classify_sentiment(text))  
*/

