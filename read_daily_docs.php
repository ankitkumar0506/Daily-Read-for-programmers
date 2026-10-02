<?php
// 2026-10-02 06:40:10

/* PHP
Topic: Prepared Statements with PDO  

Explanation:  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
The PDO (PHP Data Objects) extension provides a consistent interface for many databases.  
You first prepare the SQL query with placeholders, then bind values and execute it.  
PDO also allows you to fetch results in various formats (objects, associative arrays, etc.).  
Using prepared statements improves performance when the same query is run multiple times with different parameters.  

Code example (comments included):
<?php
// Create a new PDO instance (adjust DSN, username, password as needed)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch results as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', 'alice', PDO::PARAM_STR);
$stmt->bindValue(':email', 'alice@example.com', PDO::PARAM_STR);
$stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Retrieve the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: " . $newUserId . PHP_EOL;

// Example of a SELECT using a prepared statement with positional placeholders
$selectSql = "SELECT id, username, email FROM users WHERE id > ?";
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([0]); // Pass an array of values for the placeholders

// Fetch all matching rows
$users = $selectStmt->fetchAll();
foreach ($users as $user) {
    echo "User ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}" . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Service Container & Dependency Injection

Explanation:
The Service Container is the heart of Laravel’s inversion of control (IoC) system. It resolves class dependencies automatically, allowing you to type‑hint dependencies in constructors or controller methods. By binding abstractions to concrete implementations, you can swap out classes without touching the consuming code. This makes testing easier because you can bind mock implementations in the container during unit tests. The container also supports contextual binding, singleton bindings, and automatic resolution of primitive parameters with default values.

Code example (app/Providers/AppServiceProvider.php):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;          // abstraction
use App\Services\StripePaymentGateway;    // concrete implementation

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete class so the container can resolve it
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could read configuration values here
            $apiKey = config('services.stripe.secret');
            return new StripePaymentGateway($apiKey);
        });
    }
}
?>

Code example (app/Http/Controllers/OrderController.php):
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;   // injected dependency
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically injects the concrete implementation bound above
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        // Use the payment gateway to process a charge
        $amount = $request->input('amount');
        $token  = $request->input('payment_token');

        $charge = $this->paymentGateway->charge($amount, $token);

        // Continue with order creation logic...
        return response()->json(['status' => 'success', 'charge_id' => $charge->id]);
    }
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:
A stored procedure is a reusable set of SQL statements that are stored on the MySQL server.  
It allows you to encapsulate complex logic, control flow, and variable handling in a single object.  
Procedures can accept input parameters, return output parameters, and be invoked repeatedly without re‑parsing the code.  
Using stored procedures improves performance by reducing network round‑trips and centralizing business rules.  
They also enhance security, because you can grant execution rights without exposing underlying tables.

Code example (create, call, and drop a simple procedure that calculates the total sales for a given product):

-- Create the procedure
CREATE PROCEDURE GetTotalSales (
    IN p_product_id INT,          -- input: product identifier
    OUT p_total DECIMAL(10,2)    -- output: total sales amount
)
BEGIN
    DECLARE v_sum DECIMAL(10,2) DEFAULT 0;
    
    -- Sum the amount from the sales table for the specified product
    SELECT IFNULL(SUM(amount),0) INTO v_sum
    FROM sales
    WHERE product_id = p_product_id;
    
    SET p_total = v_sum;          -- assign the result to the OUT parameter
END;

-- Call the procedure
CALL GetTotalSales(42, @total_sales);   -- 42 is the product_id, result stored in @total_sales
SELECT @total_sales AS TotalSales;      -- display the returned total

-- Remove the procedure when it is no longer needed
DROP PROCEDURE IF EXISTS GetTotalSales;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the lexical environment in which it was created, even after that outer function has finished executing. This allows inner functions to remember and manipulate variables from their parent scope. Closures are useful for data privacy, function factories, and maintaining state between calls without exposing variables globally. They are created automatically whenever a function references a variable defined outside its own body. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state.  

Code example:  
function makeCounter(initialValue) {                // outer function creates a private variable  
  let count = initialValue;                         // this variable is captured by the inner function  

  return function() {                               // the returned function forms a closure  
    count += 1;                                      // it can read and modify 'count'  
    return count;                                   // each call sees the updated value  
  };                                                // end of inner function  
}                                                   // end of outer function  

const counterA = makeCounter(0); // first independent counter  
const counterB = makeCounter(10); // second independent counter  

console.log(counterA()); // 1 – count starts at 0, then increments  
console.log(counterA()); // 2 – retains previous value  
console.log(counterB()); // 11 – separate closure, its own private count  
console.log(counterB()); // 12 – continues from its own state  
*/

/* AI
Topic: Chain‑of‑Thought Prompting for Complex Reasoning  

Explanation:  
1. Chain‑of‑Thought (CoT) prompting encourages the model to generate intermediate reasoning steps before giving a final answer, which improves performance on tasks requiring multi‑step logic.  
2. The technique works by appending examples that show explicit reasoning, so the model learns to “think out loud.”  
3. CoT is especially effective for math problems, symbolic reasoning, and puzzles where a single‑shot answer often fails.  
4. You can control depth of reasoning by adjusting the prompt length or adding “Let’s think step by step.”  
5. When using API calls, include the CoT examples in the system or user messages, then parse the final answer from the model’s output.  

Python code example (using OpenAI’s chat completion API) with comments:  

import os  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def solve_with_cot(question: str) -> str:  
    # Prompt that demonstrates chain‑of‑thought reasoning  
    cot_prompt = (  
        "You are a helpful assistant that solves problems by reasoning step by step.\n"  
        "Example:\n"  
        "Q: If a train travels 60 km/h for 2 hours and then 80 km/h for 3 hours, what is the total distance?\n"  
        "A: First, compute the distance for each segment.\n"  
        "   - 60 km/h * 2 h = 120 km\n"  
        "   - 80 km/h * 3 h = 240 km\n"  
        "   Then add the two distances: 120 km + 240 km = 360 km.\n"  
        "   Therefore, the total distance is 360 km.\n"  
        "\n"  
        f"Q: {question}\n"  
        "A:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # choose a model that supports chat completion  
        messages=[{"role": "user", "content": cot_prompt}],  
        temperature=0.0,               # deterministic output for math‑type tasks  
        max_tokens=300                 # enough space for reasoning steps  
    )  

    # The model returns the full reasoning text; extract the final answer line  
    answer_text = response.choices[0].message.content.strip()  
    return answer_text  

# Example usage  
question = "A rectangle has length 12 cm and width 5 cm. What is its area?"  
print(solve_with_cot(question))   # The model will show the multiplication step before the final area.
*/

