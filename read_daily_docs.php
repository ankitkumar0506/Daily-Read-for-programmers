<?php
// 2026-09-26 06:23:07

/* PHP
Topic: PHP PDO and Prepared Statements  

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing many different databases.  
Using prepared statements with PDO helps prevent SQL injection by separating query structure from data.  
You can bind parameters by name or position, allowing the database driver to handle proper escaping.  
PDO also supports transactions, making it easy to commit or roll back a group of operations.  
Error handling with PDO can be configured to throw exceptions, simplifying debugging.

Code example with comments:  
<?php
// Create a new PDO instance for a MySQL database
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exceptions for easier debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', 'alice');
$stmt->bindValue(':email', 'alice@example.com');

// Execute the prepared statement
if ($stmt->execute()) {
    echo 'New user inserted with ID: ' . $pdo->lastInsertId();
} else {
    echo 'Insert failed.';
}

// Example of a SELECT using positional placeholders
$sqlSelect = 'SELECT id, username, email FROM users WHERE id > ?';
$stmtSelect = $pdo->prepare($sqlSelect);
$stmtSelect->execute([0]);

// Fetch all matching rows as an associative array
$users = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    echo $user['id'] . ': ' . $user['username'] . ' (' . $user['email'] . ')' . PHP_EOL;
}
?>
*/

/* Laravel
Laravel Topic: Service Container & Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic resolution. It allows you to bind abstractions (interfaces) to concrete implementations, making your code more testable and loosely coupled. When a class is resolved from the container, Laravel inspects its constructor and injects the required dependencies automatically. This mechanism underpins most of Laravel’s features, including controller injection, event listeners, and job handling. By mastering the container, you can customize how objects are built and swap implementations without changing the consuming code.

Code example (binding an interface and injecting it into a controller):

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway contract to the Stripe implementation
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could pull configuration values here if needed
            return new StripePaymentGateway(config('services.stripe.secret'));
        });
    }
}

namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, string $currency, array $metadata = []);
}

namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct(string $secretKey)
    {
        // Initialise the Stripe SDK client
        $this->stripe = new StripeClient($secretKey);
    }

    public function charge(float $amount, string $currency, array $metadata = [])
    {
        // Create a charge using Stripe's API
        return $this->stripe->charges->create([
            'amount' => $amount * 100, // amount in cents
            'currency' => $currency,
            'metadata' => $metadata,
            // In a real app you would also pass a source or customer ID
        ]);
    }
}

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $gateway;

    // Laravel automatically injects the bound implementation
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function charge(Request $request)
    {
        $amount = $request->input('amount');
        $currency = $request->input('currency', 'USD');

        // Use the injected gateway to perform the charge
        $result = $this->gateway->charge($amount, $currency, ['order_id' => $request->input('order_id')]);

        return response()->json($result);
    }
}
*/

/* MySQL
Topic Name: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by allowing you to break complex queries into logical building blocks.  
MySQL supports both non‑recursive and recursive CTEs; the latter can be used to walk hierarchical data such as organization charts or tree structures.  
Recursive CTEs consist of an anchor member (the starting rows) and a recursive member that repeatedly references the CTE itself until a termination condition is met.  
They are evaluated in a single execution plan, which can be more efficient than using procedural loops or multiple temporary tables.  

Code Example (generating numbers 1 through 10 with a recursive CTE):  

WITH RECURSIVE numbers AS (  
    -- Anchor member: start with the first number  
    SELECT 1 AS n  
    UNION ALL  
    -- Recursive member: add 1 to the previous number while less than 10  
    SELECT n + 1  
    FROM numbers  
    WHERE n < 10  
)  
SELECT n  
FROM numbers;  
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to the variables from its outer (enclosing) scope even after that outer function has finished executing.  
Closures enable data privacy, allowing you to expose only the functions you want while keeping internal state hidden.  
They are created each time a function is defined, capturing the current lexical environment.  
Common use cases include factories, module patterns, and callbacks that need persistent state.  
Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across invocations.

Code Example (with comments):
function createCounter(initialValue) {          // outer function defines a private variable
    let count = initialValue;                  // this variable is captured by the inner function

    return function increment(step) {          // the returned function forms a closure
        count += step;                         // it can read and modify 'count' each call
        console.log('Current count:', count); // displays the updated count
    };
}

const counterA = createCounter(0); // each call creates its own closure with its own 'count'
const counterB = createCounter(10);

counterA(1); // Current count: 1
counterA(2); // Current count: 3
counterB(5); // Current count: 15
counterA(3); // Current count: 6   (counterA's count is independent of counterB)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
1. Few‑shot prompting supplies a small number of example input‑output pairs inside the prompt to guide the model’s behavior without fine‑tuning.  
2. The technique works well for tasks like text classification, data extraction, or code generation where labeled data are scarce.  
3. By carefully formatting examples and using clear separators, you reduce ambiguity and improve consistency across responses.  
4. The OpenAI chat API accepts a list of messages; the system message sets the role, while user‑assistant pairs provide the demonstration examples.  
5. Adjusting temperature, max_tokens, and stop sequences helps control creativity and ensures the model stops after producing the desired output.  

Code example (Python, using the openai package):  

import openai  

# Set your API key (replace with your actual key or use environment variable)  
openai.api_key = "YOUR_API_KEY"  

# Define a system prompt that explains the overall task  
system_msg = {  
    "role": "system",  
    "content": "You are a helpful assistant that extracts the product name and price from a short e‑commerce description."  
}  

# Provide two few‑shot examples as user‑assistant pairs  
example_user_1 = {  
    "role": "user",  
    "content": "Description: \"Sleek stainless steel water bottle, 500 ml, keeps drinks cold for 24 h. Price: $19.99.\""}  

example_assistant_1 = {  
    "role": "assistant",  
    "content": "Product: water bottle\nPrice: 19.99"}  

example_user_2 = {  
    "role": "user",  
    "content": "Description: \"Organic cotton t‑shirt, size M, soft breathable fabric. Only $27.\""}  

example_assistant_2 = {  
    "role": "assistant",  
    "content": "Product: t‑shirt\nPrice: 27"}  

# New query for which we want the model to produce the same format  
new_query = {  
    "role": "user",  
    "content": "Description: \"Bluetooth wireless earbuds with noise cancellation, 30 h battery life. Cost: $89.\""}  

# Assemble the message list in order  
messages = [system_msg, example_user_1, example_assistant_1, example_user_2, example_assistant_2, new_query]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",        # choose a suitable model  
    messages=messages,  
    temperature=0,              # deterministic output for extraction tasks  
    max_tokens=50,  
    stop=None)  

# Print the extracted result  
print(response.choices[0].message.content.strip())   # Expected: "Product: earbuds\nPrice: 89"  
*/

