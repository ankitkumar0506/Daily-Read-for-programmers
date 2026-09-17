<?php
// 2026-09-17 06:27:33

/* PHP
Topic: PDO Prepared Statements  

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing databases.  
Prepared statements separate the SQL query from its parameters, improving security by preventing SQL injection.  
They also allow the database engine to cache the query plan, which can boost performance for repeated executions.  
Using placeholders (named or positional) makes the code more readable and easier to maintain.  
When a statement is executed, PDO automatically binds the supplied values to the placeholders.  

Code example:  
<?php  
// Create a new PDO instance (replace DSN, username, and password with your own values)  
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass');  

// Enable exceptions for error handling  
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

// Prepare an INSERT statement with named placeholders  
$stmt = $pdo->prepare('INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)');  

// Data to be inserted  
$data = [  
    'username'   => 'johndoe',  
    'email'      => 'johndoe@example.com',  
    'created_at' => date('Y-m-d H:i:s')  
];  

// Execute the prepared statement with the data array; PDO binds the values automatically  
$stmt->execute($data);  

// Fetch the ID of the newly inserted row  
$insertedId = $pdo->lastInsertId();  
echo "New user ID: " . $insertedId;  
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Resolution

Explanation:  
The Service Container is the core of Laravel’s inversion of control (IoC) system, allowing you to bind abstractions to concrete implementations. When a class type‑hint is encountered, Laravel automatically resolves its dependencies from the container. This enables clean, testable code by decoupling class responsibilities. You can bind interfaces to concrete classes in a service provider, and the container will inject the appropriate implementation wherever needed. Automatic resolution works for class constructors, controller methods, and even route closures.

Code Example (with inline comments):

<?php
namespace App\Services;

// Define an interface for a payment gateway
interface PaymentGatewayInterface
{
    public function charge(float $amount);
}

// Concrete implementation using Stripe
class StripePaymentGateway implements PaymentGatewayInterface
{
    public function charge(float $amount)
    {
        // Here you would call Stripe's API to charge the amount
        return "Charged \${$amount} via Stripe.";
    }
}

// Service provider where bindings are registered
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentGatewayInterface;
use App\Services\StripePaymentGateway;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete class so the container knows what to inject
        $this->app->bind(PaymentGatewayInterface::class, StripePaymentGateway::class);
    }
}

// A controller that receives the dependency automatically
namespace App\Http\Controllers;

use App\Services\PaymentGatewayInterface;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // Laravel resolves the concrete implementation based on the binding above
    public function process(Request $request, PaymentGatewayInterface $gateway)
    {
        $amount = $request->input('amount');
        $result = $gateway->charge((float) $amount);
        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) for Recursive Hierarchical Queries

Explanation:
A Common Table Expression (CTE) allows you to define a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
Recursive CTEs are useful for traversing hierarchical data such as organizational charts, categories, or tree structures stored in a single table.  
The CTE consists of an anchor member (the base level) and a recursive member that repeatedly joins the CTE to the original table.  
Each iteration adds a new level to the result set until no more rows satisfy the recursive join condition.  
MySQL supports recursive CTEs starting from version 8.0, enabling powerful queries without the need for stored procedures or multiple round‑trips.

Code Example (with comments):
-- Sample table for a simple employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL   -- NULL for top‑level manager
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),    -- CEO
(2, 'Bob', 1),         -- reports to Alice
(3, 'Carol', 1),       -- reports to Alice
(4, 'Dave', 2),        -- reports to Bob
(5, 'Eve', 2),         -- reports to Bob
(6, 'Frank', 3);       -- reports to Carol

-- Recursive CTE to retrieve the full reporting chain for a given employee
WITH RECURSIVE reporting_chain AS (
    -- Anchor member: start with the employee of interest (e.g., Eve, emp_id = 5)
    SELECT emp_id, emp_name, manager_id, 0 AS level
    FROM employees
    WHERE emp_id = 5

    UNION ALL

    -- Recursive member: join to the manager of the current row
    SELECT e.emp_id, e.emp_name, e.manager_id, rc.level + 1
    FROM employees e
    INNER JOIN reporting_chain rc ON e.emp_id = rc.manager_id
)
SELECT emp_id, emp_name, manager_id, level
FROM reporting_chain
ORDER BY level;

-- The result shows Eve (level 0), her manager Bob (level 1), and the top‑level manager Alice (level 2).
*/

/* JavaScript
Topic: Async/Await Error Handling in JavaScript

Explanation:
Async functions let you write asynchronous code that looks synchronous, but you still need to manage failures. Using try…catch around an await expression captures any promise rejection, keeping error handling centralized. This pattern avoids the “callback hell” of nested then/catch chains and makes stack traces clearer. You can also rethrow errors or transform them before they propagate to higher‑level handlers. Proper error handling is essential for reliable network requests, file I/O, and any operation that may fail at runtime.

Code Example:
// An async function that fetches JSON data from a URL
async function fetchJson(url) {
    try {
        // Await the fetch promise; if it fails, control jumps to catch
        const response = await fetch(url);
        // Check for HTTP errors manually
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        // Await the JSON parsing promise
        const data = await response.json();
        return data;                     // Successful result
    } catch (err) {
        // Log the error and optionally transform it
        console.error('Failed to fetch JSON:', err.message);
        // Rethrow to allow callers to handle it further
        throw err;
    }
}

// Using the async function with its own error handling
(async () => {
    try {
        const result = await fetchJson('https://api.example.com/data');
        console.log('Received data:', result);
    } catch (e) {
        console.error('Top‑level error handling:', e);
    }
})();
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example interactions to steer its behavior without fine‑tuning. By embedding these examples directly in the message list, you can teach the model the desired input‑output pattern, tone, or domain‑specific knowledge. This technique works well for classification, data extraction, or code generation tasks where a full dataset isn’t available. The prompt remains lightweight, making it suitable for real‑time API calls. Adjusting the number and quality of examples lets you balance accuracy against token cost.

Code example (Python, using the openai package):
import os
import openai

# Set your API key – normally you’d load this from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few-shot prompt: two examples of converting a natural‑language request into a SQL query
messages = [
    {"role": "system", "content": "You are an assistant that translates English questions into PostgreSQL queries."},
    {"role": "user", "content": "Show me the total sales for each product category in 2023."},
    {"role": "assistant", "content": "SELECT category, SUM(sales) FROM orders WHERE year = 2023 GROUP BY category;"},
    {"role": "user", "content": "List the names of customers who placed more than five orders."},
    {"role": "assistant", "content": "SELECT customer_name FROM orders GROUP BY customer_name HAVING COUNT(*) > 5;"},
    # The actual user query we want the model to answer
    {"role": "user", "content": "Give me the average rating for each movie released after 2020."}
]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",        # Choose a suitable model
    messages=messages,
    temperature=0.0             # Deterministic output for code generation
)

# Extract and print the generated SQL query
generated_sql = response.choices[0].message.content.strip()
print("Generated SQL query:")
print(generated_sql)
*/

