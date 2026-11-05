<?php
// 2026-11-05 08:13:31

/* PHP
Topic: PDO Prepared Statements for Secure Database Access

Explanation:
PDO (PHP Data Objects) provides a consistent interface for accessing databases in PHP. Using prepared statements with PDO helps prevent SQL injection by separating SQL code from data. The query is compiled by the database server first, then the parameters are bound and sent separately. This approach also allows for easier reuse of statements and better performance when executing the same query multiple times. PDO supports many database drivers, making it a versatile choice for modern PHP applications.

Code Example:
<?php
// Create a new PDO instance to connect to a MySQL database
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'secure_password';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Handle connection error
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the named parameters
$stmt->bindValue(':username', 'john_doe', PDO::PARAM_STR);
$stmt->bindValue(':email', 'john@example.com', PDO::PARAM_STR);

// Execute the prepared statement
if ($stmt->execute()) {
    echo 'New user inserted successfully.';
} else {
    echo 'Failed to insert user.';
}

// Example of a SELECT with a bound parameter
$selectSql = 'SELECT id, username, email FROM users WHERE email = :email';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':email' => 'john@example.com']);
$user = $selectStmt->fetch();

if ($user) {
    echo "\nUser ID: " . $user['id'];
    echo "\nUsername: " . $user['username'];
    echo "\nEmail: " . $user['email'];
} else {
    echo "\nNo user found with that email.";
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:
The service container is the core of Laravel’s inversion of control system, allowing you to bind abstractions to concrete implementations. By registering bindings, you let Laravel automatically resolve class dependencies when they are needed. This promotes loose coupling, easier testing, and clearer code organization. Dependency injection works by type‑hinting classes in controller constructors or method signatures, and the container injects the appropriate instance. You can bind singletons for shared state or use contextual bindings for specific implementations in different parts of the application.

Code example (app/Providers/AppServiceProvider.php):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
use App\Services\PaypalPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway contract to Stripe implementation globally
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);

        // Example of a singleton binding – the same instance is reused
        $this->app->singleton('logger', function ($app) {
            return new \Monolog\Logger('app');
        });

        // Contextual binding: use PayPal when injecting into CheckoutController
        $this->app->when(\App\Http\Controllers\CheckoutController::class)
                  ->needs(PaymentGateway::class)
                  ->give(PaypalPaymentGateway::class);
    }

    public function boot()
    {
        //
    }
}
?>

Code example (app/Http/Controllers/OrderController.php):
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container injects the appropriate PaymentGateway implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        // Use the injected payment gateway to process the payment
        $this->paymentGateway->charge($request->amount, $request->payment_method);

        return response()->json(['status' => 'payment processed']);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to define subqueries once and reuse them, avoiding deeply nested SELECT statements.  
When the WITH clause includes the RECURSIVE keyword, the CTE can refer to itself, enabling hierarchical or tree‑structured data traversal.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until a termination condition is met.  
They are useful for tasks such as generating number sequences, processing organizational charts, or flattening bill‑of‑materials structures.

Code example (MySQL 8.0+):

WITH RECURSIVE numbers AS (  
    -- Anchor member: start with 1  
    SELECT 1 AS n  
    UNION ALL  
    -- Recursive member: add 1 to the previous value until 10 is reached  
    SELECT n + 1 FROM numbers WHERE n < 10  
)  
SELECT n FROM numbers ORDER BY n;   -- Result: rows 1 through 10, one per line  
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical environment that includes references to the outer scope’s variables. Closures enable data privacy, function factories, and the creation of partially applied functions. They are a core concept for asynchronous programming and event handling in JavaScript. Understanding closures helps avoid common pitfalls such as unintended variable sharing in loops.

Code Example:
// outer function creates a private counter
function createCounter(initialValue) {
    let count = initialValue;               // variable in the outer scope

    // inner function forms a closure over `count`
    return function increment(step = 1) {
        count += step;                      // can modify the outer variable
        return count;                       // returns the updated value
    };
}

// using the closure
const counterA = createCounter(0);
console.log(counterA());    // 1  (default step of 1)
console.log(counterA(5));   // 6  (adds 5)
console.log(counterA());    // 7

const counterB = createCounter(10);
console.log(counterB());    // 11 (independent counter, own closure)
*/

/* AI
Topic: Few-Shot Prompt Engineering for GPT‑4 (Zero‑Shot vs. Few‑Shot)

Explanation:
This technique shows how to improve model output quality by providing a few example input‑output pairs within the prompt. By demonstrating the desired format and reasoning style, GPT‑4 can infer the pattern and apply it to new queries. Few‑shot prompting works especially well for classification, transformation, or generation tasks where the model has not seen the exact domain during training. The approach is lightweight—no fine‑tuning required—and can be dynamically adjusted at runtime. Careful selection of examples (representative, concise, and error‑free) dramatically affects consistency and reduces hallucinations.

Code example (Python, using OpenAI’s API):

import os
import openai

# Set your API key – ensure it is stored securely, e.g., in an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def few_shot_classify(text):
    """
    Classify a short user message as 'question' or 'statement' using a few‑shot prompt.
    """
    prompt = """Classify the following sentences as Question or Statement.

Example 1:
Input: "What time does the train leave?"
Output: Question

Example 2:
Input: "The train leaves at 9 am."
Output: Statement

Now classify the new input.

Input: "{}"
Output:""".format(text)

    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # fast, cost‑effective variant
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,               # deterministic for classification
        max_tokens=10,
    )
    # Extract the model's raw text response
    answer = response.choices[0].message.content.strip()
    return answer

# Example usage
print(few_shot_classify("Can you send me the report?"))   # Expected: Question
print(few_shot_classify("I sent the report yesterday.")) # Expected: Statement
*/

