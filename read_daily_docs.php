<?php
// 2026-09-29 07:14:20

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:
Prepared statements allow you to execute the same SQL query multiple times with different parameters while keeping the query structure separate from the data. This separation prevents SQL injection because user‑supplied values are bound to placeholders rather than concatenated into the query string. PDO provides a uniform API for many databases, making your code portable across MySQL, PostgreSQL, SQLite, etc. You first prepare the statement, then bind values (or pass an array), and finally execute it. Errors can be caught with exceptions, giving you fine‑grained control over failure handling.

Code Example:
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'secure_pass';

try {
    // Enable exceptions for error handling
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())'
    );

    // Sample data to insert
    $data = [
        ':username' => 'alice',
        ':email'    => 'alice@example.com',
    ];

    // Execute the statement with the bound parameters
    $stmt->execute($data);

    echo 'User inserted with ID: ' . $pdo->lastInsertId();
} catch (PDOException $e) {
    // Handle any errors (e.g., connection issues, query failures)
    echo 'Database error: ' . $e->getMessage();
}
*/

/* Laravel
Laravel Service Container & Dependency Injection  

The service container is the central piece of Laravel’s inversion of control system. It manages class dependencies and performs automatic resolution of objects. By binding abstractions to concrete implementations, you can easily swap implementations without changing consuming code. Dependency injection lets you type‑hint classes in constructors or methods, and Laravel will automatically provide the resolved instance. This pattern promotes testability, loose coupling, and cleaner architecture throughout your application.  

<?php  

namespace App\Providers;  

use Illuminate\Support\ServiceProvider;  
use App\Contracts\PaymentGateway;  
use App\Services\StripePaymentGateway;  

class AppServiceProvider extends ServiceProvider  
{  
    public function register()  
    {  
        // Bind the interface to a concrete class in the container  
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);  
    }  

    public function boot()  
    {  
        //   
    }  
}  

<?php  

namespace App\Contracts;  

interface PaymentGateway  
{  
    // Define a contract for processing payments  
    public function charge(float $amount, string $currency);  
}  

<?php  

namespace App\Services;  

use App\Contracts\PaymentGateway;  

class StripePaymentGateway implements PaymentGateway  
{  
    // Implement the charge method using Stripe’s SDK (pseudo‑code)  
    public function charge(float $amount, string $currency)  
    {  
        // Here you would call Stripe’s API to create a charge  
        // return Stripe::charge([...]);  
        return "Charged {$amount} {$currency} via Stripe";  
    }  
}  

<?php  

namespace App\Http\Controllers;  

use App\Contracts\PaymentGateway;  

class OrderController extends Controller  
{  
    protected $paymentGateway;  

    // Laravel automatically injects the concrete implementation  
    public function __construct(PaymentGateway $paymentGateway)  
    {  
        $this->paymentGateway = $paymentGateway;  
    }  

    public function store()  
    {  
        // Use the injected service to process a payment  
        $result = $this->paymentGateway->charge(99.99, 'USD');  

        // Handle the result (e.g., save order, return response)  
        return response()->json(['message' => $result]);  
    }  
}  
*/

/* MySQL
Topic: Using Prepared Statements with Parameter Binding in MySQL

Explanation:
Prepared statements allow the database server to parse, optimize, and cache the execution plan of a query once, then reuse it many times with different input values. This improves performance for repetitive operations and protects against SQL injection by separating code from data. In MySQL, you can prepare a statement with placeholders (?), bind values to those placeholders, and then execute the statement repeatedly. After finishing, the statement should be deallocated to free resources. The approach works with the MySQL command‑line client, scripts, or any programming language that supports the MySQL C API or connectors.

Code example (MySQL client syntax):
-- Create a sample table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Prepare the INSERT statement with placeholders
PREPARE stmt_insert FROM 'INSERT INTO users (username, email) VALUES (?, ?)';

-- First execution: bind actual values and execute
SET @u1 = 'alice';
SET @e1 = 'alice@example.com';
EXECUTE stmt_insert USING @u1, @e1;

-- Second execution: reuse the same prepared statement with different data
SET @u2 = 'bob';
SET @e2 = 'bob@example.org';
EXECUTE stmt_insert USING @u2, @e2;

-- Verify the inserted rows
SELECT * FROM users;

-- Clean up: deallocate the prepared statement
DEALLOCATE PREPARE stmt_insert;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to the variables from its lexical scope even after that outer function has finished executing.  
Closures enable data encapsulation, allowing you to create private state that cannot be reached from outside the function.  
They are created every time a function is defined, capturing the surrounding environment at that moment.  
Common uses include factories, memoization, and implementing modules without exposing internal variables.  
Understanding closures is essential for mastering asynchronous patterns and functional programming in JavaScript.  

Code Example (with comments):
function makeCounter() {                // Outer function creates a private variable
    let count = 0;                     // This variable is not accessible from the outside
    return function() {               // The inner function forms a closure over 'count'
        count++;                       // It can read and modify the private variable
        console.log('Current count:', count); // Output the current value
    };
}
const counter = makeCounter();          // 'counter' now holds the inner function
counter(); // Current count: 1           // First call, count becomes 1
counter(); // Current count: 2           // Second call, count becomes 2
counter(); // Current count: 3           // Subsequent calls continue to update the private state  
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models  

Explanation:  
Few‑shot prompting supplies a small number of example input–output pairs within the same request, guiding the model toward the desired response style. It is useful when you cannot fine‑tune a model but need consistent formatting, classification, or transformation. By carefully selecting diverse yet representative examples, the model learns the pattern and applies it to new queries. This technique works across many tasks such as code generation, summarization, or data extraction. Be aware that too many examples increase token cost and can dilute the signal if examples are noisy.  

Code example (Python, OpenAI Chat Completion API):  

import os, json  
import openai  

# Set your API key – keep it secure!  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# System message defines the assistant’s role  
system_msg = {"role": "system", "content": "You are a helpful assistant that converts plain English descriptions of arithmetic operations into Python code."}  

# Few‑shot examples: each example consists of a user query and the assistant’s ideal response  
example_1 = {  
    "role": "user",  
    "content": "Add the numbers 12 and 7 together."  
}  
response_1 = {  
    "role": "assistant",  
    "content": "result = 12 + 7"  
}  

example_2 = {  
    "role": "user",  
    "content": "Multiply 5 by 9 and subtract 3."  
}  
response_2 = {  
    "role": "assistant",  
    "content": "result = (5 * 9) - 3"  
}  

# New user request we want the model to answer in the same style  
new_query = {"role": "user", "content": "Divide 100 by 4 and then add 15."}  

# Assemble the message list: system prompt, examples, and the new query  
messages = [system_msg, example_1, response_1, example_2, response_2, new_query]  

# Call the API with a temperature of 0 for deterministic output  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0  
)  

# Extract and print the generated code snippet  
generated_code = response["choices"][0]["message"]["content"]  
print("Generated Python code:")  
print(generated_code)  
*/

