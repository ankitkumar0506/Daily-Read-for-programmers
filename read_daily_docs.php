<?php
// 2026-11-23 09:30:00

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:  
Prepared statements separate SQL code from the data that will be inserted, updated, or queried. This prevents SQL injection by sending the query structure to the database first, then binding the user‑supplied values safely. PDO provides a unified API for many database engines, making the code portable. The prepare() method returns a statement object that can be executed multiple times with different parameters. Errors are handled via exceptions, allowing clean error management without manual checks after each query.

Code example with comments:
<?php
// Create a new PDO instance (replace DSN, username, and password with your own)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'secret';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute the statement
$userdata = [
    ':username' => 'alice',
    ':email'    => 'alice@example.com'
];
$stmt->execute($userdata);

// Prepare a SELECT statement to retrieve the newly inserted row
$selectSql = "SELECT id, username, email, created_at FROM users WHERE username = :username";
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':username' => $userdata[':username']]);

// Fetch and display the result
$user = $selectStmt->fetch();
if ($user) {
    echo "User ID: " . $user['id'] . PHP_EOL;
    echo "Username: " . $user['username'] . PHP_EOL;
    echo "Email: " . $user['email'] . PHP_EOL;
    echo "Created at: " . $user['created_at'] . PHP_EOL;
} else {
    echo "No user found." . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Service Container & Automatic Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. It allows you to bind interfaces to concrete classes, resolve objects, and share instances throughout the application. By type‑hinting dependencies in constructors or controller methods, Laravel will automatically inject the appropriate objects. This promotes loose coupling, easier testing, and cleaner code. Understanding how to register bindings and resolve them is essential for building maintainable Laravel applications.

Code example (Service Provider binding and a controller using the binding):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;          // Interface
use App\Services\StripePaymentGateway;    // Concrete implementation

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete class so the container knows what to inject
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
    }
}
?>

<?php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}
?>

<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Here you would interact with Stripe's API
        return "Charged $$amount via Stripe.";
    }
}
?>

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;   // The interface is type‑hinted
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically injects the concrete implementation (StripePaymentGateway)
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $amount = $request->input('amount');
        $result = $this->paymentGateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:
Stored procedures are pre‑compiled SQL code blocks that reside in the database and can be invoked with a single CALL statement.  
They allow you to encapsulate complex logic, reduce network round‑trips, and enforce business rules centrally.  
Parameters can be defined as IN, OUT, or INOUT, enabling both input to the procedure and output back to the caller.  
Within a procedure you can use control‑flow statements such as IF, CASE, WHILE, and LOOP to implement conditional and iterative logic.  
Error handling is possible with DECLARE ... HANDLER clauses, letting you manage exceptions like duplicate keys or data‑type mismatches.

Code example with comments:

DELIMITER $$                     -- Change delimiter to allow semicolons inside the procedure
CREATE PROCEDURE AddEmployee(   -- Procedure definition begins
    IN p_name VARCHAR(100),     -- Input parameter: employee name
    IN p_salary DECIMAL(10,2), -- Input parameter: employee salary
    OUT p_emp_id INT)          -- Output parameter: generated employee ID
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION   -- Error handler for any SQL exception
    BEGIN
        ROLLBACK;                           -- Undo any changes if an error occurs
        SET p_emp_id = NULL;                -- Return NULL to indicate failure
    END;

    START TRANSACTION;                      -- Begin a transaction for atomicity

    INSERT INTO employees (name, salary)    -- Insert new employee record
    VALUES (p_name, p_salary);

    SET p_emp_id = LAST_INSERT_ID();       -- Capture the auto‑generated employee ID

    COMMIT;                                 -- Finalize the transaction
END$$
DELIMITER ;                                 -- Restore default delimiter

-- Example call:
CALL AddEmployee('Alice Johnson', 75000.00, @new_id);
SELECT @new_id;  -- Retrieves the ID of the newly inserted employee.
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures enable data privacy by allowing inner functions to manipulate hidden variables that cannot be reached from the global scope.  
They are created each time a function is defined, capturing the current lexical environment.  
Common uses include function factories, maintaining state in event handlers, and implementing the module pattern.  
Understanding closures is essential for writing efficient asynchronous code and avoiding common pitfalls like unintended variable sharing.

Code Example:
// Function that creates a counter using a closure
function createCounter(initialValue) {
    // 'count' is private to the returned function
    let count = initialValue;

    // The inner function forms a closure over 'count'
    return function increment(step = 1) {
        count += step;          // Modify the private variable
        return count;           // Expose the updated value
    };
}

// Create two independent counters
const counterA = createCounter(0);
const counterB = createCounter(10);

// Use the counters
console.log(counterA()); // 1  (0 + 1)
console.log(counterA(5)); // 6  (1 + 5)
console.log(counterB()); // 11 (10 + 1)
console.log(counterB(2)); // 13 (11 + 2)

// The variables 'count' inside each closure are separate and not accessible directly
// Attempting to read 'count' from outside would result in ReferenceError.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you guide a language model by providing a handful of example input‑output pairs within the same request. This technique is especially useful when you need the model to follow a specific format, style, or set of rules without fine‑tuning. By placing the examples before the user’s actual query, the model infers the desired behavior from the pattern. The approach works across many tasks—data extraction, code generation, or conversational agents—while keeping latency low because it uses the standard completion endpoint. Adjust the number and clarity of examples to balance token usage and performance.

Code example (Python, using the openai package):

import os
import openai

# Load your API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt that teaches the model how to convert
# informal sentences into formal business language
few_shot_prompt = """Convert the following informal sentences to formal business language.

Informal: "Hey, can you send me the report ASAP?"
Formal: "Could you please provide the report at your earliest convenience?"

Informal: "I think we should try a new approach."
Formal: "It may be beneficial to explore an alternative methodology."

Informal: "Let me know if you need anything."
Formal:"""

# The user’s new informal sentence
user_input = "I need the budget numbers by tomorrow."

# Combine the few‑shot examples with the new request
full_prompt = f"{few_shot_prompt} {user_input}"

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # Choose a suitable model
    messages=[{"role": "user", "content": full_prompt}],
    temperature=0.2,               # Low temperature for deterministic output
    max_tokens=60
)

# Print the formal version returned by the model
print(response.choices[0].message.content.strip())
*/

