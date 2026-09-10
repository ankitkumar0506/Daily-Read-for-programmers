<?php
// 2026-09-10 06:21:03

/* PHP
Topic: PDO Prepared Statements  

Explanation:  
Prepared statements in PHP allow you to execute the same SQL query repeatedly with different parameters while keeping the query structure separate from the data. This improves security by preventing SQL injection, as user input is never directly concatenated into the SQL string. PDO (PHP Data Objects) provides a consistent interface for prepared statements across many database systems. You can bind parameters by name or by position, and the driver handles the proper escaping. Using prepared statements also enables the database to cache the query plan, which can improve performance for repeated executions.

Code example with comments:
<?php
// Create a new PDO instance to connect to a MySQL database
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch results as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', $newUsername, PDO::PARAM_STR);
$stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Check how many rows were inserted
$rowsInserted = $stmt->rowCount();
echo "Inserted $rowsInserted row(s) into the users table.\n";

// Example of fetching data with a prepared SELECT statement
$selectSql = 'SELECT id, username, email FROM users WHERE email = :email';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':email' => $newEmail]); // Pass parameters as an array

// Fetch the result
$user = $selectStmt->fetch();
if ($user) {
    echo "User found: ID {$user['id']}, Username {$user['username']}, Email {$user['email']}\n";
} else {
    echo "No user found with email $newEmail.\n";
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. When a class is type‑hinted in a controller method or another class constructor, the container resolves the required instance and injects it. This eliminates the need for manual object creation and promotes loose coupling. Bindings can be registered in service providers to customize how abstractions are resolved. Using contextual binding, you can tell the container to return different implementations based on where they are needed.

Code example with comments:

<?php

namespace App\Http\Controllers;

use App\Services\PaymentGatewayInterface;
use App\Services\StripeGateway;
use Illuminate\Http\Request;

// Register a binding in a service provider (e.g., App\Providers\AppServiceProvider)
// $this->app->bind(PaymentGatewayInterface::class, StripeGateway::class);

class OrderController extends Controller
{
    // Laravel automatically injects the concrete class that implements the interface
    public function store(Request $request, PaymentGatewayInterface $paymentGateway)
    {
        // Validate request data (omitted for brevity)
        $orderData = $request->all();

        // Use the injected payment gateway to process payment
        $paymentResult = $paymentGateway->charge($orderData['amount'], $orderData['currency']);

        // Handle the result (store order, return response, etc.)
        if ($paymentResult->successful()) {
            // Order creation logic here
            return response()->json(['message' => 'Order placed successfully.']);
        }

        return response()->json(['error' => 'Payment failed.'], 422);
    }
}

// Example of the interface and concrete implementation:

namespace App\Services;

interface PaymentGatewayInterface
{
    public function charge(float $amount, string $currency);
}

class StripeGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency)
    {
        // Here you would interact with Stripe's SDK.
        // For demonstration, we return a simple stdClass object.
        $result = new \stdClass();
        $result->status = 'succeeded';
        $result->successful = fn() => $this->status === 'succeeded';
        return $result;
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to define subqueries once and reuse them multiple times in the same statement.  
They are defined using the WITH clause and can be either non‑recursive (simple subquery) or recursive (self‑referencing).  
Recursive CTEs are useful for traversing hierarchical data such as organizational charts, category trees, or graph paths.  
MySQL supports CTEs starting from version 8.0, and they must be placed before the main query they belong to.  

Code Example (finding all employees under a manager using a recursive CTE):

-- Define the recursive CTE named employee_hierarchy
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: select the manager whose subordinates we want
    SELECT employee_id, manager_id, employee_name, 1 AS level
    FROM employees
    WHERE manager_id = 5          -- manager_id = 5 is the top‑level manager

    UNION ALL

    -- Recursive member: select employees whose manager appears in the previous level
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1
    FROM employees e
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id
)
-- Final query: retrieve the full hierarchy with indentation based on level
SELECT REPEAT('    ', level - 1) || employee_name AS indented_name,
       employee_id,
       manager_id,
       level
FROM employee_hierarchy
ORDER BY level, employee_name;
*/

/* JavaScript
Topic: Event Delegation in the DOM

Explanation:  
Event delegation is a technique where a single event listener is attached to a parent element instead of many listeners on individual child elements. It works because events bubble up from the target element to its ancestors, allowing the parent to intercept them. This reduces memory usage and improves performance, especially in dynamic lists where items are added or removed frequently. By checking the event target, the handler can respond only to events originating from specific child elements. It also simplifies code maintenance since the logic is centralized in one place.

Code Example:  
function handleClick(event) {  
    // Only respond if the clicked element has the class "item"  
    if (event.target && event.target.classList.contains('item')) {  
        console.log('Clicked item:', event.target.textContent);  
    }  
}  

// Attach a single listener to the container instead of each .item  
var container = document.getElementById('listContainer');  
container.addEventListener('click', handleClick);  

// Example HTML structure (for reference):  
// <ul id="listContainer">
//     <li class="item">Item 1</li>
//     <li class="item">Item 2</li>
//     <li class="item">Item 3</li>
// </ul   (the closing bracket is omitted to keep plain text)  
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat API

Explanation:  
1. Few‑shot prompting gives the model a small number of input‑output examples so it can infer the desired pattern without fine‑tuning.  
2. The prompt must be clear, consistent, and include delimiters that separate each example.  
3. Adding a final “question” after the examples cues the model to produce the next output in the same format.  
4. Temperature near zero makes the response deterministic, which is useful for structured tasks like translation.  
5. This technique works with any ChatGPT model and can be adapted to classification, code generation, or data extraction.  

Code example (Python, using the OpenAI API):  

import os  
import openai  

# Load the API key from the environment (set OPENAI_API_KEY beforehand)  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Construct a few‑shot prompt that teaches the model to translate English sentences to French  
prompt = """Translate English to French.  
English: Hello, how are you?  
French: Bonjour, comment ça va?  
English: I love programming.  
French:"""  

# Call the ChatCompletion endpoint with a low temperature for consistent output  
response = openai.ChatCompletion.create(  
    model="gpt-3.5-turbo",  
    messages=[{"role": "user", "content": prompt}],  
    temperature=0.2,          # low randomness  
    max_tokens=60,            # limit the length of the translation  
)  

# Extract and print the model’s French translation of the second English sentence  
print(response["choices"][0]["message"]["content"].strip())  
*/

