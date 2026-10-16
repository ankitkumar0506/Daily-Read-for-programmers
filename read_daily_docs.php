<?php
// 2026-10-16 06:07:24

/* PHP
PHP Topic: Using PDO for Secure Database Access

Explanation:
PDO (PHP Data Objects) provides a consistent interface for accessing many different databases. It supports prepared statements, which help protect against SQL injection attacks. With PDO you can bind parameters, fetch results as objects or associative arrays, and handle errors via exceptions. It also allows easy switching between database drivers without changing your code logic. Using PDO makes database interactions more secure, portable, and maintainable.

Code Example (MySQL connection, prepared SELECT, fetch as objects):
<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
];

// Create a new PDO instance (replace credentials as needed)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare a SELECT statement with a named placeholder
$sql = 'SELECT id, name, email FROM users WHERE status = :status';
$stmt = $pdo->prepare($sql);

// Bind the value to the placeholder and execute the query
$status = 'active';
$stmt->execute([':status' => $status]);

// Fetch all matching rows as objects
$users = $stmt->fetchAll();

// Iterate over the results
foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
}

// Close the connection (optional, PDO closes automatically at script end)
$pdo = null;
?>
*/

/* Laravel
Laravel Service Container and Binding  
The Service Container is the core of Laravel’s dependency injection system. It resolves class dependencies automatically, allowing you to type‑hint objects in constructors or controller methods. You can bind interfaces to concrete implementations, enabling easy swapping of implementations for testing or future changes. Bindings can be registered as singletons (one shared instance) or as regular bindings (new instance each time). The container also supports contextual binding, where a specific implementation is provided only for a particular class. Using the container promotes loose coupling and adheres to the SOLID principles.

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
        // Bind the PaymentGateway interface to Stripe implementation globally
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);

        // Register a singleton for a heavy service that should be shared
        $this->app->singleton('analytics', function ($app) {
            return new \App\Services\AnalyticsService($app['config']['analytics']);
        });

        // Contextual binding: use PayPal only when injecting into OrderController
        $this->app->when(\App\Http\Controllers\OrderController::class)
                  ->needs(PaymentGateway::class)
                  ->give(PaypalPaymentGateway::class);
    }

    public function boot()
    {
        // No boot logic needed for this example
    }
}

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container automatically injects the PayPal implementation because of contextual binding
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $orderData = $request->all();

        // Use the injected payment gateway to process payment
        $this->paymentGateway->charge($orderData['amount'], $orderData['currency']);

        // Continue with order creation...
        return response()->json(['status' => 'order placed']);
    }
}

<?php
// Example interface definition
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, string $currency);
}

<?php
// Concrete implementation for Stripe
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    public function charge(float $amount, string $currency)
    {
        // Logic to charge via Stripe API
    }
}

<?php
// Concrete implementation for PayPal
namespace App\Services;

use App\Contracts\PaymentGateway;

class PaypalPaymentGateway implements PaymentGateway
{
    public function charge(float $amount, string $currency)
    {
        // Logic to charge via PayPal API
    }
}
*/

/* MySQL
Topic: MySQL Stored Procedures with IN, OUT, and INOUT Parameters

Explanation: 
A stored procedure is a reusable set of SQL statements stored on the server, which can accept input values, return output values, or both.  
IN parameters pass values into the procedure and cannot be modified inside it.  
OUT parameters are used to send a value back to the caller after the procedure finishes.  
INOUT parameters act as both input and output, allowing the procedure to read the initial value and then modify it.  
Using these parameter types helps encapsulate business logic, reduce client‑side code, and improve performance by minimizing round trips.

Code example with comments:

CREATE PROCEDURE CalculateDiscount (
    IN  p_price DECIMAL(10,2),        -- input price
    IN  p_rate  DECIMAL(5,2),         -- input discount rate (e.g., 0.10 for 10%)
    OUT p_discounted_price DECIMAL(10,2)  -- output discounted price
)
BEGIN
    DECLARE v_discount DECIMAL(10,2);
    SET v_discount = p_price * p_rate;               -- compute discount amount
    SET p_discounted_price = p_price - v_discount;   -- set output parameter
END;

-- Declare a variable to receive the output
SET @final_price = 0;

-- Call the procedure with price 150.00 and a 15% discount
CALL CalculateDiscount(150.00, 0.15, @final_price);

-- Retrieve the result
SELECT @final_price AS DiscountedPrice;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation: A closure is a function that retains access to the variables of its lexical scope even after that outer function has finished executing. This means the inner function can read and modify those variables whenever it is called later. Closures are created every time a function is defined, but they become especially useful for data encapsulation and private state. They enable patterns such as function factories, memoization, and module-like structures without requiring classes. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across instances.

Code example with comments:
// Outer function creates a private variable and returns an inner function
function createCounter(initialValue) {
    // This variable is local to createCounter but will be captured by the inner function
    let count = initialValue;

    // The returned function forms a closure over the 'count' variable
    return function increment(step = 1) {
        // Modify the captured variable each time the inner function is called
        count += step;
        // Return the current value of the private counter
        return count;
    };
}

// Create two independent counters using the factory function
const counterA = createCounter(0);
const counterB = createCounter(10);

console.log(counterA()); // 1  (0 + 1)
console.log(counterA(2)); // 3  (1 + 2)
console.log(counterB()); // 11 (10 + 1)
console.log(counterB(5)); // 16 (11 + 5)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
1. Few‑shot prompting supplies the model with a small number of example input‑output pairs inside the same request, guiding it toward the desired format.  
2. By placing these examples in the system or user messages, the model learns the pattern without any fine‑tuning.  
3. This technique works well for tasks like data extraction, text transformation, or generating code snippets on the fly.  
4. The prompt must be concise, clearly separate examples, and end with the new user query to avoid ambiguity.  
5. Adjusting temperature, max_tokens, and stop sequences further refines the consistency of the generated output.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt: two examples of converting a CSV line to JSON  
few_shot_prompt = [  
    {  
        "role": "system",  
        "content": "You are a helpful assistant that converts CSV records into JSON objects."  
    },  
    {  
        "role": "user",  
        "content": "Input: name,age,city\\nAlice,30,Seattle\\nOutput:"  
    },  
    {  
        "role": "assistant",  
        "content": '[{"name":"Alice","age":30,"city":"Seattle"}]'  
    },  
    {  
        "role": "user",  
        "content": "Input: product,price,stock\\nWidget,19.99,150\\nOutput:"  
    },  
    {  
        "role": "assistant",  
        "content": '[{"product":"Widget","price":19.99,"stock":150}]'  
    },  
    # New request – the model should follow the same pattern  
    {  
        "role": "user",  
        "content": "Input: title,author,year\\nThe Great Gatsby,F. Scott Fitzgerald,1925\\nOutput:"  
    }  
]  

response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",            # or any chat‑compatible model you have access to  
    messages=few_shot_prompt,  
    temperature=0.0,                # low temperature for deterministic output  
    max_tokens=150,  
    stop=None                       # let the model stop when it finishes the JSON array  
)  

# Extract and print the assistant’s answer  
generated_json = response.choices[0].message.content.strip()  
print(generated_json)   # Expected: [{"title":"The Great Gatsby","author":"F. Scott Fitzgerald","year":1925}]  
*/

