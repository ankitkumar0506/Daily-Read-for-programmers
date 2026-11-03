<?php
// 2026-11-03 08:17:17

/* PHP
Topic: Prepared Statements with PDO

Explanation:
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
PDO (PHP Data Objects) provides a uniform interface for accessing many databases.  
You first prepare the SQL query with placeholders, then bind values and execute.  
The database parses the query only once, improving performance for repeated executions.  
Errors can be caught as exceptions, making debugging easier and code more robust.

Code example (PHP):
<?php
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$username = 'johndoe';
$email = 'john@example.com';
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Check how many rows were inserted
echo $stmt->rowCount() . ' row(s) inserted.'; // Should output: 1 row(s) inserted.
?>
*/

/* Laravel
Topic: Service Container and Dependency Injection  

Explanation:  
The service container is the core of Laravel’s inversion of control (IoC) system, managing class dependencies throughout the application.  
When a class is type‑hinted in a constructor or method, the container automatically resolves and injects the required objects.  
By binding an interface to a concrete implementation, you can replace the underlying class without modifying the code that uses it.  
Contextual bindings let you specify different implementations for the same interface depending on which class is requesting it.  
Using the container makes your code more testable because you can bind mock implementations during testing.  
Objects can be resolved manually with the app() helper or automatically by Laravel when they appear in routes, controllers, or jobs.  

Code example with comments:  

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

class StripeGateway implements PaymentGateway
{
    // Charge a given amount using Stripe's API
    public function charge(float $amount)
    {
        // Imagine API call here
        return "Charged $$amount with Stripe.";
    }
}
?>  

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete class so the container knows what to inject
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}
?>  

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $gateway;

    // The container injects the concrete implementation of PaymentGateway here
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function charge(Request $request)
    {
        $amount = $request->input('amount');
        // Use the injected gateway to process the payment
        $result = $this->gateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}
?>  

<?php
// Manual resolution using the app() helper (e.g., in a closure route)
Route::get('/test-charge', function () {
    // Resolve the bound implementation of PaymentGateway
    $gateway = app(\App\Contracts\PaymentGateway::class);
    return $gateway->charge(50.00);
});
?>
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:
Stored procedures are pre‑compiled collections of SQL statements that reside on the MySQL server.  
They allow you to encapsulate complex business logic, reduce round‑trip traffic, and improve security by limiting direct table access.  
Procedures can accept input parameters, return output parameters, and use control‑flow statements such as IF, LOOP, and WHILE.  
Because they are compiled once, repeated executions are faster than sending the same ad‑hoc queries from a client.  
They also make it easier to maintain code, as changes are made in one place rather than scattered across application layers.

Code example:
CREATE PROCEDURE GetCustomerOrders(IN cust_id INT, OUT total_orders INT)
BEGIN
    -- Count the number of orders for the given customer
    SELECT COUNT(*) INTO total_orders
    FROM orders
    WHERE customer_id = cust_id;

    -- If the customer has no orders, set total_orders to zero explicitly
    IF total_orders IS NULL THEN
        SET total_orders = 0;
    END IF;
END;

-- Call the procedure and retrieve the output value
SET @order_count = 0;
CALL GetCustomerOrders(12345, @order_count);
SELECT @order_count AS NumberOfOrders;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to the variables of its lexical scope even after that outer function has finished executing. This allows the inner function to remember and manipulate private data, creating encapsulation without exposing the variables directly. Closures are created every time a function is defined, and they are essential for patterns like function factories, data privacy, and partial application. Understanding closures helps avoid common pitfalls related to variable mutability and asynchronous callbacks. They are a core concept that enables powerful functional programming techniques in JavaScript.

Code example with comments:
function createCounter() {
    // Private variable that will be captured by the closure
    let count = 0;

    // The inner function forms a closure over 'count'
    return function increment() {
        // Modify the captured variable each time the function is called
        count++;
        console.log('Current count:', count);
    };
}

// Obtain a counter instance; the outer function has already returned
const counter = createCounter();

counter(); // Current count: 1
counter(); // Current count: 2
counter(); // Current count: 3

// Each call to createCounter() produces an independent closure with its own 'count'
const anotherCounter = createCounter();
anotherCounter(); // Current count: 1   (separate from the first counter)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you steer a large language model by providing a small number of example interactions within the same request. Each example pairs a user message with an assistant response, establishing a pattern the model can follow for new inputs. This technique is lightweight compared to fine‑tuning and works well for tasks like formatting, style imitation, or domain‑specific Q&A. By carefully choosing diverse yet concise examples, you can improve consistency and reduce hallucinations. The OpenAI chat API supports this natively: just include a list of message objects that alternate roles.  

Code example (Python, using the openai package):  

import os  
import openai  

# Load your API key from an environment variable for safety  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt with two example exchanges  
few_shot_messages = [  
    {"role": "system", "content": "You are a helpful assistant that formats data as CSV."},  
    {"role": "user", "content": "List the names and ages of Alice (30) and Bob (25)."},  
    {"role": "assistant", "content": "name,age\nAlice,30\nBob,25"},  
    {"role": "user", "content": "Provide a CSV of product IDs and prices for items: A100 ($10), B200 ($15)."}  
]  

# Send the request; the model will infer the CSV format from the examples  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=few_shot_messages,  
    temperature=0.0  
)  

# Extract and print the assistant’s reply  
csv_output = response.choices[0].message.content.strip()  
print(csv_output)  
*/

