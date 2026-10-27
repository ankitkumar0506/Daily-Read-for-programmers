<?php
// 2026-10-27 06:38:21

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:
Prepared statements separate SQL logic from data, which prevents SQL injection attacks by treating user input as literal values rather than executable code. PDO (PHP Data Objects) provides a consistent interface for many database systems and supports named or positional placeholders. When a statement is prepared, the database parses the query once, then you can execute it multiple times with different parameters, improving performance. Binding values allows you to specify data types, ensuring correct handling of integers, strings, and nulls. After execution, you can fetch results using fetch modes that suit your application, such as associative arrays or objects.

Code example (with comments):
<?php
// Create a new PDO instance to connect to a MySQL database
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define an SQL query with named placeholders for safety
$sql = 'SELECT id, name, email FROM users WHERE status = :status AND created_at > :date';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders with explicit data types
$status = 'active';
$date   = '2023-01-01';
$stmt->bindValue(':status', $status, PDO::PARAM_STR);
$stmt->bindValue(':date',   $date,   PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Fetch all matching rows
$users = $stmt->fetchAll();

// Iterate over results
foreach ($users as $user) {
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:
The Laravel service container is a powerful tool for managing class dependencies and performing automatic resolution. It allows you to bind abstractions (interfaces) to concrete implementations, making your code more testable and decoupled. When a class is type‑hinted in a controller or another class, the container resolves and injects the appropriate instance automatically. You can also bind singleton instances that should be shared across the entire request lifecycle. Using the container promotes the Inversion of Control (IoC) principle, leading to cleaner architecture and easier maintenance.

Code example with comments:
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    // Register bindings in the service container
    public function register()
    {
        // Bind the PaymentGateway interface to the StripeGateway concrete class
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}

// ---------------------------------------------------------

namespace App\Contracts;

interface PaymentGateway
{
    // Define a contract for processing payments
    public function charge(float $amount, string $currency);
}

// ---------------------------------------------------------

namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    // Implementation of the charge method using Stripe's API
    public function charge(float $amount, string $currency)
    {
        // Imagine Stripe SDK call here
        return "Charged {$amount} {$currency} via Stripe.";
    }
}

// ---------------------------------------------------------

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $paymentGateway;

    // The service container injects the concrete StripeGateway automatically
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $amount = $request->input('amount');
        $currency = $request->input('currency', 'USD');

        // Use the injected gateway to process the payment
        $result = $this->paymentGateway->charge($amount, $currency);

        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) in MySQL

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and can improve query readability, especially for complex subqueries or recursive operations.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  
Non‑recursive CTEs are useful for breaking down a query into logical steps without repeating code.  
Recursive CTEs enable hierarchical data processing, such as traversing parent‑child relationships.  
CTEs exist only for the duration of the statement that defines them and do not create permanent objects.

Code example (non‑recursive CTE with comments):

-- Define a CTE named top_sales that selects the top 5 sales records
WITH top_sales AS (
    SELECT
        sales_id,
        customer_id,
        amount,
        sale_date
    FROM sales
    ORDER BY amount DESC
    LIMIT 5
)
-- Use the CTE to retrieve detailed customer information for those top sales
SELECT
    ts.sales_id,
    ts.amount,
    ts.sale_date,
    c.customer_name,
    c.email
FROM top_sales ts
JOIN customers c ON ts.customer_id = c.customer_id
ORDER BY ts.amount DESC;
*/

/* JavaScript
Topic: Debouncing a Function in JavaScript  

Explanation:  
- Debouncing limits how often a function can be executed by postponing its call until after a specified wait time has elapsed since the last invocation.  
- It is especially useful for performance‑critical events such as window resizing, scrolling, or keystroke handling.  
- The technique prevents the original function from running too frequently, reducing unnecessary calculations and DOM updates.  
- A debounced function returns a new wrapper that manages a timer; each call resets the timer, and the original function runs only when the timer completes without interruption.  
- Implementing debounce improves responsiveness and conserves resources in web applications.  

Code example (with comments):  

function debounce(func, wait) {  
    // Store the timeout ID between calls  
    let timeoutId;  

    // Return a new function that will be called in place of the original  
    return function(...args) {  
        // Capture the context to preserve “this” inside func  
        const context = this;  

        // Clear any existing timer to restart the wait period  
        clearTimeout(timeoutId);  

        // Set a new timer that will invoke func after wait milliseconds  
        timeoutId = setTimeout(() => {  
            func.apply(context, args); // Execute the original function with the correct context and arguments  
        }, wait);  
    };  
}  

// Example usage: log the window width after the user stops resizing for 300 ms  
const handleResize = debounce(() => {  
    console.log('Window width:', window.innerWidth);  
}, 300);  

window.addEventListener('resize', handleResize);  
*/

/* AI
Topic: OpenAI Function Calling with GPT‑4 (AI APIs)

Explanation:  
Function calling lets a language model decide when to invoke external code and pass arguments in JSON format.  
You define the function signature (name, description, parameters) and send it to the ChatCompletion endpoint.  
When the model detects a need, it returns a “function_call” object instead of plain text.  
Your program then runs the requested function, captures its result, and feeds that result back to the model as a “function” role message.  
This pattern enables safe, structured retrieval of up‑to‑date data (e.g., weather, stock prices) without the model hallucinating facts.

Code example (Python, requires openai ≥ 0.27 and an API key):

import openai, json  

openai.api_key = "YOUR_API_KEY"  

def get_weather(city):  
    # Mock implementation – replace with a real API call if needed  
    return {"city": city, "temperature": 22, "unit": "C", "description": "Sunny"}  

# User query  
messages = [  
    {"role": "user", "content": "What's the weather in Paris?"}  
]  

# Describe the function the model may call  
functions = [  
    {  
        "name": "get_weather",  
        "description": "Retrieve the current weather for a given city",  
        "parameters": {  
            "type": "object",  
            "properties": {  
                "city": {"type": "string", "description": "Name of the city"}  
            },  
            "required": ["city"]  
        }  
    }  
]  

# Ask the model, allowing it to decide whether to call the function  
response = openai.ChatCompletion.create(  
    model="gpt-4-0613",  
    messages=messages,  
    functions=functions,  
    function_call="auto"          # let the model choose when to call  
)  

# If the model wants to call a function, execute it and send the result back  
if response.choices[0].finish_reason == "function_call":  
    call = response.choices[0].message["function_call"]  
    fn_name = call["name"]  
    args = json.loads(call["arguments"])  

    if fn_name == "get_weather":  
        result = get_weather(args["city"])  

        # Append the model's function request and the function's response to the conversation  
        follow_up = openai.ChatCompletion.create(  
            model="gpt-4-0613",  
            messages=messages + [  
                response.choices[0].message,                      # the function call request  
                {"role": "function", "name": fn_name, "content": json.dumps(result)}  # function output  
            ]  
        )  

        # The final answer from the model, now grounded in real data  
        print(follow_up.choices[0].message["content"])  
else:  
    # Model responded directly without needing a function call  
    print(response.choices[0].message["content"])
*/

