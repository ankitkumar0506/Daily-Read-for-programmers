<?php
// 2026-10-10 07:31:57

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Access

Explanation:
Prepared statements separate SQL logic from data, preventing SQL injection attacks. PDO (PHP Data Objects) provides a uniform interface for many database systems, making code portable. You first prepare the SQL with placeholders, then bind values and execute. This approach also improves performance when the same statement runs multiple times with different parameters. Errors can be caught via exceptions, allowing graceful handling of database issues.

Code Example:
// Create a PDO instance (replace DSN, username, password with real credentials)
$pdo = new PDO('mysql:host=localhost;dbname=example_db;charset=utf8mb4', 'db_user', 'db_pass');
// Enable exceptions for error handling
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Define the SQL with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$data = [
    ['username' => 'alice',   'email' => 'alice@example.com'],
    ['username' => 'bob',     'email' => 'bob@example.com'],
    ['username' => 'charlie', 'email' => 'charlie@example.com']
];

// Loop through data and execute the prepared statement for each row
foreach ($data as $row) {
    // Bind values to the named placeholders and execute
    $stmt->execute([
        ':username' => $row['username'],
        ':email'    => $row['email']
    ]);
}

// Fetch rows using a prepared SELECT statement
$select = $pdo->prepare("SELECT id, username, email FROM users WHERE email LIKE :domain");
$select->execute([':domain' => '%@example.com']);
$users = $select->fetchAll(PDO::FETCH_ASSOC);

// Output the retrieved users
foreach ($users as $user) {
    echo "ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}\n";
}
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection  

Explanation:  
- The service container is the core of Laravel’s inversion of control (IoC) system, managing class dependencies and performing automatic resolution.  
- When a class type‑hints another class in its constructor, the container resolves and injects an instance automatically.  
- You can bind abstractions (interfaces) to concrete implementations, allowing you to swap implementations without changing dependent code.  
- Singleton bindings ensure the same instance is reused throughout the request lifecycle.  
- The container can be accessed via the `app()` helper or type‑hinted in controller methods, routes, or jobs.  

Code Example:  

<?php
namespace App\Services;

interface PaymentGatewayContract
{
    public function charge(float $amount);
}

// Concrete implementation for Stripe
class StripePaymentGateway implements PaymentGatewayContract
{
    public function charge(float $amount)
    {
        // Here you would call Stripe’s API
        return "Charged \${$amount} via Stripe.";
    }
}

// Concrete implementation for PayPal
class PayPalPaymentGateway implements PaymentGatewayContract
{
    public function charge(float $amount)
    {
        // Here you would call PayPal’s API
        return "Charged \${$amount} via PayPal.";
    }
}

// Service Provider where bindings are defined
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentGatewayContract;
use App\Services\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to a concrete class
        $this->app->bind(PaymentGatewayContract::class, function ($app) {
            // You could decide based on config or environment
            return new StripePaymentGateway();
        });

        // Example of a singleton binding
        $this->app->singleton('logger', function ($app) {
            return new \Monolog\Logger('app');
        });
    }
}

// Using dependency injection in a controller
namespace App\Http\Controllers;

use App\Services\PaymentGatewayContract;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container injects the concrete implementation automatically
    public function __construct(PaymentGatewayContract $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store()
    {
        $amount = 99.99;
        $result = $this->paymentGateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}

// Directly resolving from the container elsewhere
$gateway = app(PaymentGatewayContract::class);
echo $gateway->charge(45.00); // Outputs: Charged $45 via Stripe. 

?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to define subqueries up front and give them a meaningful name.  
They are defined using the WITH clause and can be chained together for multiple CTEs.  
When the CTE is recursive, it can reference itself to produce hierarchical or sequential data, such as organizational charts or number series.  
Recursive CTEs consist of an anchor member (the starting rows) and a recursive member (the query that builds upon the previous result).  
The recursion stops when the recursive member returns an empty set or reaches a defined limit.

Code example (MySQL 8.0+):
-- Generate a list of integers from 1 to 10 using a recursive CTE
WITH RECURSIVE numbers AS (
    SELECT 1 AS n                 -- Anchor member: start with 1
    UNION ALL
    SELECT n + 1                  -- Recursive member: add 1 to the previous value
    FROM numbers
    WHERE n < 10                  -- Stop condition: stop when n reaches 10
)
SELECT n
FROM numbers
ORDER BY n;                       -- Result: 1,2,3,4,5,6,7,8,9,10

-- Example of a hierarchical query: retrieve an employee hierarchy
-- Assume a table employees(id, name, manager_id)
WITH RECURSIVE org_chart AS (
    SELECT id, name, manager_id, 0 AS level
    FROM employees
    WHERE manager_id IS NULL            -- Anchor: top‑level managers
    UNION ALL
    SELECT e.id, e.name, e.manager_id, oc.level + 1
    FROM employees e
    JOIN org_chart oc ON e.manager_id = oc.id   -- Recursive join to build the tree
)
SELECT id, name, manager_id, level
FROM org_chart
ORDER BY level, manager_id;               -- Shows employees grouped by hierarchy level.
*/

/* JavaScript
Topic: JavaScript Closures and Lexical Scoping

Explanation:  
A closure is created when an inner function retains access to the variables of its outer (enclosing) function after that outer function has finished executing. This works because JavaScript uses lexical scoping: a function’s scope is determined by its physical location in the source code, not by where it is called. Closures enable data privacy, allowing you to expose only the functions you want while keeping internal variables hidden. They are commonly used for module patterns, partial application, and maintaining state in asynchronous callbacks. Understanding closures is essential for writing robust, memory‑efficient code.

Code example:  
function createCounter(initial) {               // outer function with a private variable  
    let count = initial;                        // this variable is captured by the inner function  

    return function increment(step = 1) {       // inner function forms a closure over count  
        count += step;                          // can read and modify the captured variable  
        console.log('Current count:', count);  // side effect: display the current value  
        return count;                           // return the updated count  
    };                                           // end of inner function  

}                                                // end of outer function  

const counterA = createCounter(0);               // each call creates a separate closure  
counterA();           // Current count: 1  
counterA(5);          // Current count: 6  

const counterB = createCounter(10);              // independent private state  
counterB(2);          // Current count: 12  
counterB();           // Current count: 13   (counterA’s count is unaffected)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example interactions that illustrate the desired behavior, letting it infer the pattern without fine‑tuning. By placing the examples in the system or user messages, you guide the model to generate outputs that follow the same format, tone, or logic. This technique works well for tasks like data extraction, style transfer, or custom Q&A where a full dataset for training is unavailable. The prompt must be concise yet clear, and the examples should cover edge cases you expect the model to handle. Adjusting temperature and max_tokens helps control creativity versus precision in the generated response.  

Code example (Python, using the openai library):  

import openai  

# Set your OpenAI API key (replace with your actual key or use env variable)  
openai.api_key = "sk-YOUR_API_KEY"  

# Define a few‑shot prompt that extracts a product’s price from a description  
few_shot_prompt = [  
    {  
        "role": "system",  
        "content": "You are an assistant that extracts the price (in USD) from a product description and returns only the numeric value."  
    },  
    {  
        "role": "user",  
        "content": "The sleek wireless headphones cost $199.99 and offer noise cancellation."  
    },  
    {  
        "role": "assistant",  
        "content": "199.99"  
    },  
    {  
        "role": "user",  
        "content": "Our new coffee maker is priced at $89 and comes with a 2‑year warranty."  
    },  
    {  
        "role": "assistant",  
        "content": "89"  
    }  
]  

# New query to which the model should apply the same extraction rule  
new_query = {  
    "role": "user",  
    "content": "Buy the ergonomic office chair for $349.50 and enjoy free shipping."  
}  

# Combine the prompt and the new query  
messages = few_shot_prompt + [new_query]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",   # lightweight model suitable for structured tasks  
    messages=messages,  
    temperature=0.0,       # deterministic output for extraction  
    max_tokens=10          # limit response length to the price only  
)  

# Print the extracted price  
print(response.choices[0].message.content.strip())   # Expected output: 349.50  
*/

