<?php
// 2026-08-30 07:04:59

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:
Prepared statements separate SQL code from data, preventing SQL injection attacks by treating user input as parameters rather than raw query text. PDO (PHP Data Objects) provides a uniform interface for accessing different databases, and its prepare/execute methods handle the binding of values safely. When a statement is prepared, the database parses and compiles the SQL once, then you can execute it multiple times with different data, which also improves performance for repetitive queries. Parameters can be bound by position (question marks) or by name (named placeholders), giving flexibility in how you organize your code. Errors are easier to catch because PDO can be set to throw exceptions, allowing you to handle failures cleanly.

Code example (with comments):
<?php
// 1. Create a PDO instance (replace DSN, username, password with your own values)
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// 2. Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO products (name, price, created_at) VALUES (:name, :price, :created_at)";
$stmt = $pdo->prepare($sql);

// 3. Bind values and execute the statement
$data = [
    ':name'       => 'Wireless Mouse',
    ':price'      => 29.99,
    ':created_at' => date('Y-m-d H:i:s')
];
$stmt->execute($data);

// 4. Optionally retrieve the ID of the newly inserted row
$newProductId = $pdo->lastInsertId();
echo "New product inserted with ID: $newProductId";
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. It resolves class instances by inspecting constructor type hints, allowing you to decouple your code and promote testability. When a class is requested, the container builds it, injecting any required dependencies recursively. You can bind interfaces to concrete implementations, giving you flexibility to swap implementations without changing consumer code. The container is also accessible via the app() helper, enabling manual resolution when needed.

Code Example:

// App/Services/ReportGenerator.php
<?php

namespace App\Services;

class ReportGenerator
{
    // Generates a simple report string
    public function generate(): string
    {
        return 'Report generated at ' . now();
    }
}

// App/Http/Controllers/ReportController.php
<?php

namespace App\Http\Controllers;

use App\Services\ReportGenerator;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportGenerator;

    // Laravel automatically injects ReportGenerator via the service container
    public function __construct(ReportGenerator $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
    }

    // Action that returns the generated report
    public function show(Request $request)
    {
        // Use the injected service to create the report
        $report = $this->reportGenerator->generate();

        // Return a plain text response
        return response($report, 200)
                ->header('Content-Type', 'text/plain');
    }
}

// routes/web.php
<?php

use App\Http\Controllers\ReportController;

// Register a route that uses the ReportController
Route::get('/report', [ReportController::class, 'show']);
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTEs) for Hierarchical Data

Explanation:
A Recursive CTE lets you query hierarchical or tree‑structured data in a single SELECT statement. It consists of an anchor query that returns the root rows and a recursive part that repeatedly joins the CTE to its own result set to walk down the hierarchy. MySQL 8.0+ supports this feature, enabling you to retrieve all descendants, calculate depth levels, or produce indented output without using stored procedures. The recursion stops automatically when the recursive query returns no new rows, preventing infinite loops. Proper indexing on the parent‑child columns improves performance dramatically.

Code example (employees table with id, name, manager_id):

-- Anchor member: select top‑level employees (no manager)
WITH RECURSIVE emp_hierarchy AS (
    SELECT
        id,
        name,
        manager_id,
        1 AS level          -- root level is 1
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join children to their parent rows
    SELECT
        e.id,
        e.name,
        e.manager_id,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN emp_hierarchy eh
        ON e.manager_id = eh.id
)
SELECT
    REPEAT('    ', level - 1) || name AS indented_name,
    id,
    manager_id,
    level
FROM emp_hierarchy
ORDER BY level, manager_id, id;   -- order to show hierarchy clearly  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context. This means the inner function can reference variables declared in the outer function after the outer function has finished running. Closures are created automatically each time a function is created, allowing private state and data encapsulation. They are commonly used for data privacy, partial application, and creating function factories. Understanding closures helps avoid common pitfalls with variable binding in asynchronous code.  

Code example:  
function createCounter(initialValue) {                // outer function creates a private variable  
    let count = initialValue;                        // this variable is captured by the inner function  

    return function() {                             // the inner function forms a closure  
        count += 1;                                  // it can modify the captured variable  
        return count;                               // and return the updated value  
    };                                               // end of inner function  

}                                                    // end of outer function  

const counterA = createCounter(0); // counterA has its own private count  
console.log(counterA()); // 1  
console.log(counterA()); // 2  

const counterB = createCounter(10); // counterB starts from a different value  
console.log(counterB()); // 11  
console.log(counterA()); // 3   (counterA’s count is independent of counterB)  
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with Large Language Models

Explanation:  
Few‑shot prompting lets a language model learn a new task from only a handful of examples supplied in the prompt. By carefully designing the prompt structure—task description, example input‑output pairs, and a clear delimiter—you guide the model to infer the pattern and apply it to new inputs. This technique works without any model fine‑tuning, making it fast and inexpensive for prototyping. The quality of the examples, consistency of formatting, and explicit instruction all influence the model’s performance. Using a deterministic temperature (e.g., 0) often yields more reliable outputs for structured tasks.

Code example (Python, using OpenAI’s ChatCompletion API):

import os
import json
import openai

# Load API key from environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define the few‑shot prompt
prompt = """You are an assistant that extracts the city name from a travel sentence.
Example 1:
Sentence: "I will be flying to Paris next week."
City: Paris

Example 2:
Sentence: "Our vacation starts in Tokyo on Monday."
City: Tokyo

Now extract the city from the new sentence.

Sentence: "The conference will be held in Berlin tomorrow."
City:"""

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",               # lightweight model suitable for prompting
    messages=[{"role": "user", "content": prompt}],
    temperature=0,                     # deterministic output for consistency
    max_tokens=20                      # enough for a short city name
)

# Parse and display the model's answer
city = response.choices[0].message.content.strip()
print("Extracted city:", city)   # Expected output: Berlin

# Optional: wrap in a function for reuse
def extract_city(sentence: str) -> str:
    """Return the city name extracted from a travel‑related sentence using few‑shot prompting."""
    few_shot = f"""You are an assistant that extracts the city name from a travel sentence.
Example 1:
Sentence: "I will be flying to Paris next week."
City: Paris

Example 2:
Sentence: "Our vacation starts in Tokyo on Monday."
City: Tokyo

Now extract the city from the new sentence.

Sentence: "{sentence}"
City:"""
    resp = openai.ChatCompletion.create(
        model="gpt-4o-mini",
        messages=[{"role": "user", "content": few_shot}],
        temperature=0,
        max_tokens=20
    )
    return resp.choices[0].message.content.strip()

print("Another test:", extract_city("We are meeting friends in Sydney tonight."))   # Expected: Sydney
*/

