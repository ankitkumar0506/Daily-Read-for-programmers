<?php
// 2026-11-09 08:26:06

/* PHP
Topic: PDO Prepared Statements  

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing databases in PHP.  
Prepared statements separate the SQL code from the data, which prevents SQL injection attacks.  
They allow the database engine to compile the query once and execute it multiple times with different parameters, improving performance.  
Placeholders in the SQL are bound to PHP variables, and the driver takes care of proper quoting and escaping.  
Using PDO also makes it easy to switch between different database systems with minimal code changes.  

Code example with comments:  

<?php
// Create a new PDO instance to connect to a MySQL database
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$username = 'alice';
$email = 'alice@example.com';
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Get the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: $newUserId\n";

// Example of a SELECT with positional placeholders
$sqlSelect = 'SELECT id, username, email FROM users WHERE id > ?';
$stmtSelect = $pdo->prepare($sqlSelect);
$minId = 0;
$stmtSelect->execute([$minId]);

// Fetch and display the results
while ($row = $stmtSelect->fetch()) {
    echo "ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}\n";
}
?>
*/

/* Laravel
Laravel Service Container & Dependency Injection

The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. It resolves objects by reading type hints in constructors or method signatures, allowing you to decouple code and adhere to the inversion of control principle. You can bind interfaces to concrete implementations, customize the resolution process, and even create contextual bindings for specific scenarios. This mechanism simplifies testing, as you can easily swap implementations with mock objects. Understanding the container is essential for building modular, maintainable Laravel applications.

<?php
// App/Contracts/PaymentGateway.php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}

// App/Services/StripeGateway.php
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Simulate a Stripe charge
        return "Charged \${$amount} using Stripe.";
    }
}

// App/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to a concrete class
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}

// App/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The service container injects the bound implementation automatically
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $amount = $request->input('total');
        // Use the injected payment gateway to process payment
        $result = $this->paymentGateway->charge($amount);

        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and can improve query readability by separating complex logic from the main query.  
Recursive CTEs allow you to query hierarchical or graph‑structured data, such as organizational charts or bill‑of‑materials.  
The recursion is achieved by having the CTE reference itself, with an anchor member that provides the starting rows and a recursive member that builds upon them.  
MySQL 8.0 and later support both non‑recursive and recursive CTEs, making it easier to write self‑joins for tree traversal without deep nesting.

Code example with comments:  

-- Sample table representing an employee hierarchy  
CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    emp_name VARCHAR(50),  
    manager_id INT NULL   -- references emp_id of the manager; NULL for top‑level boss  
);  

INSERT INTO employees (emp_id, emp_name, manager_id) VALUES  
(1, 'Alice', NULL),      -- CEO  
(2, 'Bob', 1),           -- reports to Alice  
(3, 'Carol', 1),         -- reports to Alice  
(4, 'Dave', 2),          -- reports to Bob  
(5, 'Eve', 2),           -- reports to Bob  
(6, 'Frank', 3);         -- reports to Carol  

-- Recursive CTE to list all subordinates of a given manager (e.g., manager_id = 1)  
WITH RECURSIVE subordinates AS (  
    -- Anchor member: start with the direct reports of the chosen manager  
    SELECT emp_id, emp_name, manager_id, 1 AS level  
    FROM employees  
    WHERE manager_id = 1  
    UNION ALL  
    -- Recursive member: find employees whose manager is already in the result set  
    SELECT e.emp_id, e.emp_name, e.manager_id, s.level + 1  
    FROM employees e  
    INNER JOIN subordinates s ON e.manager_id = s.emp_id  
)  
SELECT emp_id, emp_name, manager_id, level  
FROM subordinates  
ORDER BY level, emp_id;  

-- The result shows the hierarchy under Alice, with 'level' indicating depth in the tree.
*/

/* JavaScript
Topic: JavaScript Proxies – Intercepting Object Operations

Explanation:
A Proxy is a special object that wraps another object (the target) and intercepts fundamental operations such as property lookup, assignment, enumeration, function invocation, and more. By defining handler functions (traps) for these operations, you can customize behavior, enforce validation, log activity, or implement virtual properties. Proxies are powerful for creating reactive data structures, debugging tools, and API wrappers without modifying the original object. The syntax requires a target object and a handler object that contains the traps you want to implement. Proxies work transparently, so code that interacts with the proxy can use it like a regular object.

Code example with comments:
// Original object that we want to protect
const user = {
    name: "Alice",
    age: 30
};

// Handler defining traps for get and set operations
const handler = {
    // Intercept property reads
    get(target, prop, receiver) {
        console.log(`Reading property "${prop}"`);
        // Return the original value
        return Reflect.get(target, prop, receiver);
    },
    // Intercept property writes
    set(target, prop, value, receiver) {
        console.log(`Attempting to set "${prop}" to ${value}`);
        // Example validation: age must be a positive number
        if (prop === "age" && (typeof value !== "number" || value <= 0)) {
            throw new TypeError("Age must be a positive number");
        }
        // Perform the assignment
        return Reflect.set(target, prop, value, receiver);
    }
};

// Create the proxy that wraps the user object
const userProxy = new Proxy(user, handler);

// Using the proxy
console.log(userProxy.name);      // Logs read operation, then prints "Alice"
userProxy.age = 31;               // Logs set operation, updates age
// userProxy.age = -5;            // Would throw a TypeError because of validation
console.log(userProxy.age);      // Logs read operation, then prints 31
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs before the actual user query, guiding it toward the desired response style. This technique works well for tasks like data extraction, text classification, or generating code snippets where the model benefits from concrete illustrations. By carefully crafting the examples and using clear separators, you can control temperature, stop tokens, and token limits to achieve consistent results. The approach is lightweight—no fine‑tuning required—and can be applied to any model that supports chat‑style interactions. It’s especially useful for programmers who need reliable, repeatable outputs from a generative AI service.  

Code example (Python, using the openai library):  

import os
import openai

# Load your API key from an environment variable for safety
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt with two examples of converting English dates to ISO format
messages = [
    {"role": "system", "content": "You are a helpful assistant that converts human‑readable dates into ISO 8601 format (YYYY‑MM‑DD)."},
    {"role": "user",   "content": "Convert the date: March 5, 2023"},
    {"role": "assistant", "content": "2023-03-05"},
    {"role": "user",   "content": "Convert the date: 12th July 1999"},
    {"role": "assistant", "content": "1999-07-12"},
    # New user query that the model should answer using the same pattern
    {"role": "user",   "content": "Convert the date: November 9, 2026"}
]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # Choose a model that supports chat completions
    messages=messages,
    temperature=0.0,              # Deterministic output for precise formatting
    max_tokens=20,                # Small limit because the answer is short
)

# Print the model's answer
print(response["choices"][0]["message"]["content"].strip())   # Expected output: 2026-11-09  
*/

