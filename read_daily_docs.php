<?php
// 2026-10-02 07:17:41

/* PHP
PHP Topic: Using PDO Prepared Statements for Secure Database Access  

Explanation:  
PDO (PHP Data Objects) provides a uniform interface for accessing different databases.  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
You can bind parameters by name or position, allowing automatic type handling.  
The statement is prepared once and can be executed multiple times with different values.  
Error handling with exceptions makes debugging easier and keeps code clean.  

Code Example (with inline comments):  

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// Create a new PDO connection (adjust DSN, username, password as needed)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8', 'dbuser', 'dbpass', $options);

try {
    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())'
    );

    // Bind values to the placeholders
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);

    // Sample data to insert
    $username = 'alice';
    $email = 'alice@example.com';

    // Execute the prepared statement
    $stmt->execute();

    echo "User inserted with ID: " . $pdo->lastInsertId();
} catch (PDOException $e) {
    // Handle any errors gracefully
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container Binding and Resolution

Explanation:
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection. By binding an interface or abstract class to a concrete implementation, you tell the container how to resolve the dependency when it is needed. This allows you to swap implementations without changing the consuming code, facilitating testing and adherence to the SOLID principles. Bindings are typically defined in service providers, and the container resolves them automatically when type‑hinted in constructors or controller methods. You can also bind singletons to ensure only one instance of a class is created throughout the request lifecycle.

Code example (placed in a service provider, e.g., App\Providers\AppServiceProvider.php):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;          // Interface
use App\Services\StripePaymentGateway;     // Concrete implementation

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the service container.
     */
    public function register()
    {
        // Bind the interface to the concrete class.
        // When PaymentGateway is requested, Laravel will resolve StripePaymentGateway.
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You can pull configuration values or other services from the container here.
            $apiKey = config('services.stripe.secret');
            return new StripePaymentGateway($apiKey);
        });

        // Example of a singleton binding – only one instance will be created.
        // $this->app->singleton(PaymentGateway::class, StripePaymentGateway::class);
    }
}
?>

Usage in a controller (Laravel will inject the bound implementation automatically):
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel injects the StripePaymentGateway instance here.
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $orderData = $request->all();

        // Use the payment gateway to process payment.
        $this->paymentGateway->charge($orderData['amount'], $orderData['currency']);

        // Continue with order creation logic...
        return response()->json(['status' => 'order created']);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and can improve readability by breaking complex queries into logical building blocks. Recursive CTEs allow you to perform hierarchical or iterative processing, such as traversing parent‑child relationships. The CTE exists only for the duration of the statement, so it does not affect the underlying tables. Recursive CTEs must contain an anchor member (the base case) and a recursive member that references the CTE itself, with a termination condition to prevent infinite loops.

Code example (MySQL 8.0+):

-- Define a recursive CTE to generate a simple number series from 1 to 10
WITH RECURSIVE numbers AS (
    SELECT 1 AS n                     -- Anchor member: start with 1
    UNION ALL
    SELECT n + 1 FROM numbers        -- Recursive member: add 1 to the previous value
    WHERE n < 10                     -- Termination condition: stop at 10
)
SELECT n
FROM numbers
ORDER BY n;                          -- Result: 1,2,3,4,5,6,7,8,9,10

-- Example of a hierarchical query using a CTE on an employee table
-- Assume a table employees(id INT PRIMARY KEY, name VARCHAR(50), manager_id INT)
WITH RECURSIVE org_chart AS (
    SELECT id, name, manager_id, 0 AS level
    FROM employees
    WHERE manager_id IS NULL               -- Anchor: top‑level executives
    UNION ALL
    SELECT e.id, e.name, e.manager_id, oc.level + 1
    FROM employees e
    JOIN org_chart oc ON e.manager_id = oc.id   -- Recursive step: link subordinates
)
SELECT id, name, manager_id, level
FROM org_chart
ORDER BY level, manager_id;               -- Shows the hierarchy with indentation level.
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing.  
Closures allow you to preserve state between function calls without using global variables.  
They are essential for data encapsulation, memoization, and implementing private members.  
Because the inner function retains a reference to the outer scope’s variables, those variables stay alive in memory.  
Understanding closures helps avoid common pitfalls such as unexpected variable sharing in loops.  

Code example (with comments):  
function makeCounter() {               // outer function creates a private count variable  
    let count = 0;                     // this variable is not accessible from outside  
    return function() {                // inner function forms a closure over 'count'  
        count++;                       // modify the private variable each call  
        console.log('Current count:', count); // use the private state  
    };                                 // the returned function keeps a reference to 'count'  
}                                      // end of makeCounter  

const counterA = makeCounter();        // each call creates a separate closure  
const counterB = makeCounter();  

counterA(); // Output: Current count: 1  
counterA(); // Output: Current count: 2  
counterB(); // Output: Current count: 1   (independent from counterA)  
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Zero‑Shot Text Classification  

Explanation:  
This technique uses a small number of labeled examples directly in the prompt to guide a large language model (LLM) toward the desired classification task. By presenting the model with a few input‑output pairs, we “show” it the format and decision boundaries without any fine‑tuning. The approach works well for tasks where labeled data is scarce or when rapid prototyping is needed. It leverages the LLM’s ability to infer patterns from context, making it flexible across domains. The prompt can be adapted to any classification problem by changing the example sentences and labels.  

Code example (Python, using OpenAI’s ChatCompletion API):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt with two example sentences and their categories  
few_shot_prompt = """Classify the following sentences as Positive, Negative, or Neutral.  

Sentence: "I love the new update, it works flawlessly!"  
Label: Positive  

Sentence: "The app crashes every time I open it, very frustrating."  
Label: Negative  

Sentence: "The interface is okay, nothing special."  
Label: Neutral  

Now classify this sentence: "{}"  
Label:"""  

def classify_sentence(sentence: str) -> str:  
    # Insert the user sentence into the prompt template  
    prompt = few_shot_prompt.format(sentence)  

    # Call the ChatCompletion endpoint with the constructed prompt  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,               # deterministic output for classification  
        max_tokens=10,                 # we only need the label word  
    )  

    # Extract the model's reply and strip whitespace/newlines  
    label = response.choices[0].message.content.strip()  
    return label  

# Example usage  
test_sentence = "The battery life could be better, but the screen is great."  
print(f"Sentence: {test_sentence}")  
print("Predicted label:", classify_sentence(test_sentence))  
*/

