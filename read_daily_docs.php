<?php
// 2026-11-17 09:07:37

/* PHP
Topic: PHP PDO (PHP Data Objects) for Secure Database Access  

Explanation:  
PHP PDO provides a consistent interface for accessing different databases, allowing you to write portable code.  
It supports prepared statements, which protect against SQL injection by separating query logic from data.  
PDO handles connection errors and can throw exceptions, making error handling more robust.  
You can bind parameters by name or position, improving readability and maintainability of queries.  
The extension also offers transaction support, letting you commit or roll back multiple operations as a single unit.  

Code Example (with comments):  

<?php
// Enable PDO exceptions for better error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace placeholders with real credentials)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Handle connection errors
    die('Connection failed: ' . $e->getMessage());
}

// Example of a prepared SELECT statement using named placeholders
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

// Output the results
foreach ($users as $user) {
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";
}

// Example of a transaction: insert a new user and log the action
try {
    $pdo->beginTransaction();

    // Insert new user
    $insertSql = 'INSERT INTO users (name, email, status, created_at) VALUES (:name, :email, :status, NOW())';
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([
        ':name'   => 'Jane Doe',
        ':email'  => 'jane@example.com',
        ':status' => 'active',
    ]);

    // Log the insertion
    $logSql = 'INSERT INTO audit_log (action, user_id, performed_at) VALUES (:action, :user_id, NOW())';
    $logStmt = $pdo->prepare($logSql);
    $logStmt->execute([
        ':action'  => 'User Created',
        ':user_id' => $pdo->lastInsertId(),
    ]);

    // Commit both statements as a single unit
    $pdo->commit();
} catch (Exception $e) {
    // Roll back if anything goes wrong
    $pdo->rollBack();
    echo 'Transaction failed: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. It resolves classes from the container, injecting any required dependencies defined in their constructors. You can bind interfaces to concrete implementations, allowing you to swap implementations without changing the consuming code. The container also supports contextual binding, which lets you specify different implementations for the same interface in different parts of the application. Using the container leads to cleaner, more testable code by decoupling class responsibilities.

Code example (with inline comments):

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;          // Interface
use App\Services\StripeGateway;            // Concrete implementation
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $paymentGateway;

    // Laravel will automatically resolve the PaymentGateway implementation from the container
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway; // Assigned for use in controller methods
    }

    public function process(Request $request)
    {
        $amount = $request->input('amount');

        // Use the injected gateway to charge the customer
        $result = $this->paymentGateway->charge($amount);

        if ($result->successful()) {
            return response()->json(['status' => 'Payment successful']);
        }

        return response()->json(['status' => 'Payment failed'], 422);
    }
}

// In a service provider (e.g., App\Providers\AppServiceProvider)

public function register()
{
    // Bind the interface to a concrete class so the container knows what to inject
    $this->app->bind(
        \App\Contracts\PaymentGateway::class,
        \App\Services\StripeGateway::class
    );

    // Example of contextual binding: use PayPal for orders, Stripe for subscriptions
    $this->app->when(SubscriptionController::class)
              ->needs(PaymentGateway::class)
              ->give(PayPalGateway::class);
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a named temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by separating complex subqueries from the main query logic.  
When a CTE references itself, it becomes a recursive CTE, which is useful for traversing hierarchical data such as organization charts or folder structures.  
Recursive CTEs consist of an anchor member (the starting rows) and a recursive member that repeatedly references the CTE until no new rows are produced.  
MySQL 8.0+ fully supports both non‑recursive and recursive CTEs, allowing you to write elegant, set‑based solutions for problems that previously required procedural code.  

Code example:  
WITH RECURSIVE OrgChart AS (  
    -- Anchor member: select the top‑level manager(s)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL  
    UNION ALL  
    -- Recursive member: find employees reporting to the current level  
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1  
    FROM employees e  
    INNER JOIN OrgChart oc ON e.manager_id = oc.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM OrgChart  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: JavaScript Closures and Lexical Scope

Explanation: A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical environment that captures the variables it references. Closures enable data privacy, allowing you to create private state that cannot be accessed directly from outside. They are fundamental for patterns such as module creation, function factories, and partial application. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across function calls.

Code example with comments:
function makeCounter() {
    let count = 0; // private variable that lives in the closure
    return function() {
        count++; // modify the private variable each time the inner function runs
        return count; // expose the current count value
    };
}

// Create first independent counter
const counterA = makeCounter();
console.log(counterA()); // 1
console.log(counterA()); // 2

// Create second independent counter, its count starts from 0
const counterB = makeCounter();
console.log(counterB()); // 1

// counterA still retains its own count
console.log(counterA()); // 3
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small number of example interactions to guide the model’s behavior without extensive fine‑tuning. By embedding a few user‑assistant turns in the system or user message, you can shape the tone, format, or domain knowledge the model applies. This technique works well for tasks like data extraction, code generation, or style‑consistent writing. The examples act as in‑context demonstrations, letting the model infer the desired pattern. It is lightweight, portable across models, and easily adjustable by adding or swapping examples.

Code example (Python, using the openai library):  

import openai  

# Replace with your actual API key  
openai.api_key = "sk-YOUR_API_KEY"  

# Define a few‑shot prompt: a system instruction plus two example Q&A pairs  
messages = [  
    {"role": "system", "content": "You are a helpful assistant that converts plain English descriptions of arithmetic problems into valid Python expressions."},  
    {"role": "user", "content": "What is the sum of 12 and 7?"},  
    {"role": "assistant", "content": "12 + 7"},  
    {"role": "user", "content": "Multiply 8 by 5 and then subtract 3."},  
    {"role": "assistant", "content": "(8 * 5) - 3"},  
    # New query that will follow the demonstrated pattern  
    {"role": "user", "content": "Divide 45 by 9 and add 2."}  
]  

response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0.0  # deterministic output for code-like tasks  
)  

# The model’s reply should be a Python expression matching the pattern  
print(response["choices"][0]["message"]["content"].strip())  
*/

