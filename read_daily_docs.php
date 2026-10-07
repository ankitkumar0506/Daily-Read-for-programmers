<?php
// 2026-10-07 07:29:57

/* PHP
PHP PDO Prepared Statements  
Prepared statements allow you to execute the same SQL query repeatedly with different parameters while keeping the query structure separate from the data. This improves security by preventing SQL injection, because the database driver handles proper escaping of input values. PDO (PHP Data Objects) provides a consistent API for many databases, so the same code works with MySQL, PostgreSQL, SQLite, etc. You first prepare the statement, then bind values or pass them directly when executing. After execution you can fetch results as objects, associative arrays, or numeric arrays. Using prepared statements also often yields better performance for repeated queries because the database can cache the execution plan.

<?php
// Create a new PDO connection (adjust DSN, username, and password as needed)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the named parameters and execute
$stmt->execute([
    ':username' => 'alice',
    ':email'    => 'alice@example.com'
]);

// Prepare a SELECT statement to retrieve the newly inserted row
$selectSql = 'SELECT id, username, email, created_at FROM users WHERE username = :username';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':username' => 'alice']);

// Fetch the result as an associative array
$user = $selectStmt->fetch();

echo 'User ID: ' . $user['id'] . PHP_EOL;
echo 'Username: ' . $user['username'] . PHP_EOL;
echo 'Email: ' . $user['email'] . PHP_EOL;
echo 'Created At: ' . $user['created_at'] . PHP_EOL;
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. By binding interfaces to concrete implementations, you decouple your code and make it easier to test. When a class is resolved from the container, Laravel inspects its constructor and injects the required dependencies. This mechanism works transparently for controllers, event listeners, jobs, and any class resolved via the container. Using the container promotes a clean, maintainable architecture and adheres to the SOLID principles.

Code Example:
// app/Contracts/PaymentGateway.php
<?php
namespace App\Contracts;
interface PaymentGateway
{
    public function charge(float $amount);
}

// app/Services/StripePaymentGateway.php
<?php
namespace App\Services;
use App\Contracts\PaymentGateway;
class StripePaymentGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Logic to charge via Stripe API
        return "Charged $$amount using Stripe.";
    }
}

// app/Providers/AppServiceProvider.php
<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete implementation
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
    }
}

// app/Http/Controllers/OrderController.php
<?php
namespace App\Http\Controllers;
use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    protected $paymentGateway;
    // Laravel automatically injects the bound implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }
    public function store(Request $request)
    {
        $amount = $request->input('total');
        $result = $this->paymentGateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}
*/

/* MySQL
Topic: MySQL Stored Procedures  

Explanation:  
A stored procedure is a named set of SQL statements that are stored in the database server and can be executed repeatedly.  
It allows you to encapsulate complex logic, loop constructs, and conditional flow without moving data to the application layer.  
Parameters can be passed in (IN), out (OUT), or both (INOUT) to exchange values with the caller.  
Using stored procedures improves performance by reducing network round‑trips and enables better security through privilege control.  
They are especially useful for batch processing, data validation, and implementing business rules directly in the database.  

Code example with comments:  

CREATE PROCEDURE GetTopCustomers (  
    IN p_limit INT,                 -- number of rows to return  
    OUT p_total INT)                -- total number of customers in the result set  
BEGIN  
    -- Declare a local variable to hold the count  
    DECLARE v_count INT;  

    -- Calculate the total number of customers that meet the criteria  
    SELECT COUNT(*) INTO v_count  
    FROM customers  
    WHERE status = 'active';  

    SET p_total = v_count;  

    -- Return the top N active customers ordered by total_spent  
    SELECT customer_id, name, total_spent  
    FROM customers  
    WHERE status = 'active'  
    ORDER BY total_spent DESC  
    LIMIT p_limit;  
END;  



-- Example call:  
CALL GetTopCustomers(5, @totalCustomers);  
SELECT @totalCustomers AS total_active_customers;  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is created when an inner function accesses variables from an outer function that has already finished execution. The inner function retains a reference to the outer scope’s variables, preserving their values across multiple calls. This mechanism enables data encapsulation, private state, and function factories. Closures are fundamental for patterns such as currying, memoization, and module design. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state.

Code example with comments:  
function makeCounter(start) {               // outer function defines a private variable
    let count = start;                     // this variable is captured by the inner function
    return function() {                    // the inner function forms a closure over count
        count += 1;                         // modify the private count each time it's called
        return count;                       // expose the updated value
    };
}
const counterA = makeCounter(0);            // create a new counter instance
console.log(counterA()); // 1               // first call, count becomes 1
console.log(counterA()); // 2               // second call, count becomes 2

const counterB = makeCounter(10);           // a separate instance with its own private count
console.log(counterB()); // 11              // independent of counterA's state
console.log(counterA()); // 3               // counterA continues where it left off  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with GPT‑4  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs, guiding it toward the desired behavior without any fine‑tuning. By carefully choosing diverse and representative examples, you can steer the model to follow specific formats, apply domain‑specific logic, or emulate a particular tone. This technique works especially well with large language models like GPT‑4, which can infer patterns from just a handful of demonstrations. The prompt typically consists of a system message (defining the role), several user‑assistant exchanges as examples, and finally the new user query. Adjusting the number and quality of examples can dramatically affect accuracy and consistency.  

Code example (Python, using OpenAI’s Chat Completion API):

import os
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def few_shot_translate(text):
    # Construct a few‑shot prompt: system message + three examples + new query
    messages = [
        {"role": "system", "content": "You are a helpful assistant that translates English sentences into French, preserving tone and style."},
        # Example 1
        {"role": "user", "content": "Good morning, how are you?"},
        {"role": "assistant", "content": "Bonjour, comment ça va ?"},
        # Example 2
        {"role": "user", "content": "I would like to book a table for two at 7 pm."},
        {"role": "assistant", "content": "Je voudrais réserver une table pour deux à 19h."},
        # Example 3
        {"role": "user", "content": "The weather looks perfect for a hike."},
        {"role": "assistant", "content": "Le temps semble parfait pour une randonnée."},
        # New user request
        {"role": "user", "content": text}
    ]

    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # choose the appropriate GPT‑4 model
        messages=messages,
        temperature=0.2               # low temperature for more deterministic output
    )
    # Extract and return the assistant’s reply
    return response.choices[0].message.content.strip()

# Example usage
english_sentence = "Could you please send me the latest sales report by Friday?"
french_translation = few_shot_translate(english_sentence)
print("English:", english_sentence)
print("French :", french_translation)
*/

