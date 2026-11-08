<?php
// 2026-11-08 07:46:06

/* PHP
Topic: Prepared Statements with PDO

Explanation:
Prepared statements are a secure way to execute SQL queries that include user‑supplied data. By separating the query structure from its parameters, PDO prevents SQL injection attacks. The statement is compiled once by the database server, then executed multiple times with different values. This approach also improves performance for repeated queries. PDO supports named and positional placeholders, making the code clear and maintainable.

Code example with comments:
<?php
// Create a new PDO instance (replace DSN, username, and password with real values)
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch results as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define an SQL query with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)';

// Prepare the statement (the query is sent to the server for compilation)
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username',  $userName, PDO::PARAM_STR);
$stmt->bindValue(':email',     $userEmail, PDO::PARAM_STR);
$stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// To fetch data using a prepared SELECT statement
$selectSql = 'SELECT id, username, email FROM users WHERE email = :email';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':email' => $userEmail]); // Pass parameters as an array
$user = $selectStmt->fetch(); // Retrieve the matching row

// Output the fetched data (for demonstration purposes)
if ($user) {
    echo 'User ID: ' . $user['id'] . PHP_EOL;
    echo 'Username: ' . $user['username'] . PHP_EOL;
    echo 'Email: ' . $user['email'] . PHP_EOL;
} else {
    echo 'No user found with that email.' . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. When you type‑hint a class in a controller constructor or method, the container resolves the required instance, handling any nested dependencies as well. This promotes loose coupling and makes testing easier because concrete implementations can be swapped via bindings. You can bind interfaces to concrete classes in service providers, allowing the container to inject the appropriate implementation wherever needed. The container also supports contextual bindings, singleton bindings, and deferred service loading for optimized performance.

Code Example (a controller using automatic injection and a binding in a service provider):

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container will inject the concrete class bound to PaymentGateway
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store()
    {
        // Use the injected payment gateway to process a payment
        $this->paymentGateway->charge(1500, 'USD');
        return response()->json(['status' => 'payment processed']);
    }
}

// -------------------------------------------------
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to a concrete implementation
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could read config values to decide which gateway to use
            return new StripePaymentGateway(config('services.stripe.secret'));
        });
    }

    public function boot()
    {
        //
    }
}

// -------------------------------------------------
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey; // Store the secret key for Stripe API calls
    }

    public function charge(int $amount, string $currency)
    {
        // Here you would interact with Stripe's SDK to create a charge
        // This is a placeholder to illustrate the method implementation
        echo "Charging {$amount} {$currency} using Stripe with key {$this->apiKey}";
    }
}

// -------------------------------------------------
namespace App\Contracts;

interface PaymentGateway
{
    // Define the contract that all payment gateways must implement
    public function charge(int $amount, string $currency);
}
*/

/* MySQL
Topic: MySQL Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by allowing you to break complex queries into logical building blocks.  
Recursive CTEs enable you to perform hierarchical or tree‑like traversals, such as processing an employee‑manager relationship or generating a calendar.  
The CTE is scoped to the statement that defines it, so it does not persist beyond that query execution.  
You can reference the same CTE multiple times within the main query, which avoids repeating subqueries and can improve performance.

Code example with comments:

-- Create a sample table to hold an employee hierarchy
CREATE TABLE employees (
    emp_id   INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL   -- NULL indicates top‑level manager
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),   -- CEO
(2, 'Bob',   1),
(3, 'Carol', 1),
(4, 'Dave',  2),
(5, 'Eve',   2),
(6, 'Frank', 4);

-- Recursive CTE to list each employee with their chain of managers
WITH RECURSIVE hierarchy AS (
    -- Anchor member: start with top‑level managers (no manager_id)
    SELECT
        emp_id,
        emp_name,
        manager_id,
        CAST(emp_name AS CHAR(200)) AS path,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to their direct reports
    SELECT
        e.emp_id,
        e.emp_name,
        e.manager_id,
        CONCAT(h.path, ' -> ', e.emp_name) AS path,
        h.level + 1
    FROM employees e
    JOIN hierarchy h ON e.manager_id = h.emp_id
)
SELECT
    emp_id,
    emp_name,
    manager_id,
    path,
    level
FROM hierarchy
ORDER BY path;

-- The result shows each employee, their manager chain (path), and depth level in the hierarchy.
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. Closures enable data privacy, allowing you to expose only the functionality you want while keeping internal state hidden. They are created every time a function is defined, capturing the surrounding lexical environment at that moment. Common uses include function factories, module patterns, and maintaining state in asynchronous callbacks. Understanding closures is essential for mastering scope, callbacks, and functional programming techniques in JavaScript.  

Code example:  
function createCounter(initialValue) {  
    // This variable is local to createCounter but will be captured by the inner function  
    let count = initialValue;  

    // The returned function forms a closure over `count`  
    return function () {  
        // Each call updates and returns the private `count` variable  
        count += 1;  
        return count;  
    };  
}  

// Create two independent counters  
const counterA = createCounter(0);  
const counterB = createCounter(10);  

console.log(counterA()); // 1  
console.log(counterA()); // 2  
console.log(counterB()); // 11  
console.log(counterB()); // 12  

// The internal `count` variables are not directly accessible from outside  
// Attempting to read `count` would result in ReferenceError.  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies a model with a handful of example input‑output pairs inside the same request, teaching it the desired format without fine‑tuning. This technique works well for tasks like classification, data extraction, or code generation where a clear pattern can be demonstrated. By structuring the messages as a system prompt followed by user‑assistant example exchanges, the model learns to replicate the behavior for new inputs. The approach is lightweight, requires only API calls, and can be adapted on the fly for different domains. It also reduces the need for large labeled datasets, making rapid prototyping possible.

Code example (Python, using the openai library):
import os
import openai

# Load your OpenAI API key from the environment
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    """
    Uses a few‑shot prompt to classify sentiment as Positive, Negative, or Neutral.
    """
    # Define the system message that sets the overall role
    system_msg = {"role": "system", "content": "You are a helpful assistant that classifies sentiment of short English sentences."}
    
    # Provide two example interactions (few‑shot)
    examples = [
        {"role": "user", "content": "I love the new design of this app!"},
        {"role": "assistant", "content": "Positive"},
        {"role": "user", "content": "The service was disappointing and slow."},
        {"role": "assistant", "content": "Negative"}
    ]
    
    # Append the actual user query
    user_msg = {"role": "user", "content": text}
    
    # Build the full message list
    messages = [system_msg] + examples + [user_msg]
    
    # Call the Chat Completion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",   # lightweight model suitable for this task
        messages=messages,
        temperature=0.0        # deterministic output for classification
    )
    
    # Extract and return the assistant's answer
    return response.choices[0].message.content.strip()

# Example usage
if __name__ == "__main__":
    test_sentence = "The movie was okay, nothing special."
    sentiment = classify_sentiment(test_sentence)
    print(f"Input: {test_sentence}\nPredicted Sentiment: {sentiment}")
*/

