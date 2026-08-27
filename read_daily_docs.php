<?php
// 2026-08-27 11:48:09

/* PHP
Topic: PDO Prepared Statements for Secure Database Access

Explanation:
- PDO (PHP Data Objects) provides a uniform interface for accessing many different databases.
- Using prepared statements separates SQL code from data, preventing SQL injection attacks.
- Placeholders in the query are bound to values at execution time, ensuring proper escaping.
- PDO supports both named (:name) and positional (?) placeholders for flexibility.
- Errors can be handled via exceptions, making debugging and error reporting easier.

Code example:
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'secure_pass';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindParam(':username', $userName);
$stmt->bindParam(':email', $userEmail);

// Sample data
$userName = 'alice';
$userEmail = 'alice@example.com';

// Execute the statement
try {
    $stmt->execute();
    echo 'New user added successfully.';
} catch (PDOException $e) {
    echo 'Insert failed: ' . $e->getMessage();
}
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection. It resolves objects automatically, allowing you to type‑hint classes in constructors or controller methods. When a class is requested, the container inspects its constructor and provides the needed dependencies recursively. This promotes loose coupling and makes testing easier, as you can bind interfaces to concrete implementations. You can also bind custom classes or singletons manually for more control over their lifecycle.  

Code example (app/Http/Controllers/ReportController.php):  

<?php  
namespace App\Http\Controllers;  

use App\Services\ReportService;  
use Illuminate\Http\Request;  

class ReportController extends Controller  
{  
    // The ReportService will be automatically injected by the container  
    protected $reportService;  

    public function __construct(ReportService $reportService)  
    {  
        $this->reportService = $reportService; // store for later use  
    }  

    // Example action that uses the injected service  
    public function generate(Request $request)  
    {  
        $type = $request->input('type', 'summary');  
        // The service handles the business logic, keeping the controller thin  
        $result = $this->reportService->createReport($type);  

        return response()->json([  
            'status' => 'success',  
            'data'   => $result,  
        ]);  
    }  
}  

// app/Services/ReportService.php  

<?php  
namespace App\Services;  

class ReportService  
{  
    // This method could pull data from models, format it, etc.  
    public function createReport(string $type)  
    {  
        // Placeholder logic – in a real app you would have complex operations here  
        if ($type === 'summary') {  
            return ['title' => 'Summary Report', 'content' => '...'];  
        }  

        return ['title' => 'Detailed Report', 'content' => '...'];  
    }  
}  

// Binding an interface to the concrete service (optional) – typically placed in a service provider  

<?php  
namespace App\Providers;  

use Illuminate\Support\ServiceProvider;  
use App\Contracts\ReportGenerator;  
use App\Services\ReportService;  

class AppServiceProvider extends ServiceProvider  
{  
    public function register()  
    {  
        // Bind the interface to the concrete implementation  
        $this->app->bind(ReportGenerator::class, ReportService::class);  
    }  
}  

// Now you could type‑hint ReportGenerator instead of ReportService, and the container will still resolve it.  
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
- A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
- CTEs improve readability by allowing you to break complex queries into logical building blocks.  
- They are defined using the WITH clause and can be referenced multiple times in the main query.  
- Recursive CTEs enable hierarchical data processing, such as traversing parent‑child relationships.  
- MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.

Code example (calculating an employee hierarchy depth):

-- Define a recursive CTE named org_chart
WITH RECURSIVE org_chart AS (
    -- Anchor member: select top‑level managers (no manager_id)
    SELECT 
        employee_id,
        name,
        manager_id,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to their managers
    SELECT 
        e.employee_id,
        e.name,
        e.manager_id,
        oc.level + 1 AS level
    FROM employees e
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id
)
-- Final query: list each employee with their hierarchical level
SELECT 
    employee_id,
    name,
    manager_id,
    level
FROM org_chart
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
- A closure is created when an inner function accesses variables from an outer function that has already finished executing.  
- The inner function retains a reference to the outer scope’s variables, preserving their values across calls.  
- Closures enable data encapsulation, allowing private state without exposing it to the global scope.  
- They are fundamental for patterns like factories, module design, and event handlers.  
- Understanding closures helps avoid common pitfalls such as unintended variable sharing in loops.

Code Example with comments:
function makeCounter() {
    let count = 0; // variable in the outer function, private to the closure
    return function() {
        count++; // modifies the captured variable each time the inner function runs
        return count; // returns the current value of the private count
    };
}

// Create a new counter instance; each call to makeCounter gets its own closed-over count
const counter = makeCounter();

console.log(counter()); // 1 – first call increments count from 0 to 1
console.log(counter()); // 2 – second call increments count from 1 to 2
console.log(counter()); // 3 – count continues to persist across calls

// A second counter has its own independent closure
const anotherCounter = makeCounter();
console.log(anotherCounter()); // 1 – separate private count starts at 0 for this instance.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s GPT API  

Explanation:  
Few‑shot prompting provides the model with a small number of example input‑output pairs within the same request, guiding it to produce responses that follow the demonstrated pattern. This technique works well for tasks like classification, transformation, or generating structured data without fine‑tuning. By carefully designing the examples and the instruction, you can achieve higher accuracy and more reliable outputs. The prompt remains a single string, so the API call stays simple while the model leverages the contextual examples. Use this method when you need quick, on‑the‑fly customization of GPT behavior.

Code example (Python, using the OpenAI library):

import os
import openai

# Set your API key – replace with your actual key or configure via environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Define a few‑shot prompt with two labeled examples and a new query placeholder
    prompt = (
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"
        "Sentence: I love the new design of the app.\n"
        "Sentiment: Positive\n\n"
        "Sentence: The update caused many crashes.\n"
        "Sentiment: Negative\n\n"
        f"Sentence: {text}\n"
        "Sentiment:"
    )

    # Call the chat completion endpoint using the gpt‑3.5‑turbo model
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,      # deterministic output for classification
        max_tokens=10         # only need a short label
    )
    # Extract the model’s reply and strip whitespace/newlines
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
print(classify_sentiment("The customer support was helpful and quick."))   # Expected: Positive
print(classify_sentiment("I waited an hour for a response; not happy."))  # Expected: Negative
print(classify_sentiment("The product arrived on time."))                # Expected: Neutral (or Positive depending on interpretation)
*/

