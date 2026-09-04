<?php
// 2026-09-04 06:16:33

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
PDO (PHP Data Objects) provides a uniform interface for accessing different databases.  
You first prepare the SQL statement with placeholders, then bind values and execute.  
This approach also improves performance when the same statement is run multiple times.  
Error handling can be managed with exceptions, making debugging easier.

Code example with comments:

<?php
// Create a new PDO instance for a MySQL database
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Handle connection errors
    die('Connection failed: ' . $e->getMessage());
}

// SQL with named placeholders
$sql = 'SELECT id, name, email FROM users WHERE status = :status AND created_at > :date';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$status = 'active';
$date   = '2023-01-01';
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':date',   $date,   PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Fetch all matching rows
$users = $stmt->fetchAll();

// Process the results
foreach ($users as $user) {
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. By binding abstractions to concrete implementations, you can easily swap implementations without changing the consuming code. When a class is resolved, the container inspects its constructor and injects the required dependencies. This promotes loose coupling and makes testing simpler, as mock objects can be bound in the container during tests. Automatic injection works for controllers, jobs, listeners, and any class resolved through the container.

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

class StripePaymentGateway implements PaymentGateway
{
    // Charge a customer using Stripe's API
    public function charge(float $amount)
    {
        // Imagine Stripe API call here
        return "Charged \${$amount} via Stripe.";
    }
}
?>

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete class in the container
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
    }
}
?>

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container will automatically inject the concrete implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $amount = $request->input('total');
        // Use the injected payment service
        $result = $this->paymentGateway->charge($amount);

        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
They can be recursive, enabling hierarchical data processing such as organizational charts or tree traversals.  
The WITH clause defines the CTE and is placed before the main query that consumes it.  
Recursive CTEs require a UNION ALL between an anchor member (the base case) and a recursive member (the iteration).  

Code example with comments:
WITH RECURSIVE OrgChart AS ( 
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)
    SELECT employee_id, manager_id, employee_name, 1 AS level 
    FROM employees 
    WHERE employee_id = 1 

    UNION ALL 

    -- Recursive member: find employees whose manager is in the previous level
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1 
    FROM employees e 
    JOIN OrgChart oc ON e.manager_id = oc.employee_id 
) 
SELECT employee_id, manager_id, employee_name, level 
FROM OrgChart 
ORDER BY level, employee_id;
*/

/* JavaScript
Topic: Debounce Function in JavaScript  

Explanation:  
A debounce utility limits how often a function can be executed by postponing its call until a specified wait time has elapsed since the last invocation. This is especially useful for performance‑heavy events such as window resizing, scrolling, or keypress handling, where frequent calls would degrade responsiveness. The returned wrapper function keeps track of a timer; each new call resets the timer, ensuring the original function runs only after the user has stopped triggering the event for the defined interval. Debouncing can be implemented with closures, making it reusable across different parts of an application. By controlling the execution frequency, you reduce unnecessary computations and improve overall UI smoothness.  

Code example (with comments):  
function debounce(func, wait) {  
  let timeoutId = null;                         // holds the pending timeout  
  return function(...args) {                    // returned wrapper receives any arguments  
    clearTimeout(timeoutId);                    // cancel any previously scheduled execution  
    timeoutId = setTimeout(() => {              // schedule a new execution after wait ms  
      func.apply(this, args);                   // preserve context and forward arguments  
    }, wait);  
  };  
}  

// Usage example: log the window width after the user stops resizing for 300 ms  
const logWidth = () => console.log('Window width:', window.innerWidth);  
window.addEventListener('resize', debounce(logWidth, 300));  
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets a language model learn a new task from only a handful of examples included directly in the prompt. By carefully formatting the examples and the instruction, you can guide the model to produce the desired output without any fine‑tuning. This approach works well for classification, data extraction, or transformation tasks where a large labeled dataset is unavailable. The key is to keep the prompt concise, maintain consistent formatting, and provide a clear “output” label for the model to follow. Using the Chat Completion endpoint, you can embed these examples in the system or user messages and retrieve the model’s response programmatically.

Code example (Python, using the openai library):

import openai

# Set your API key – replace with your actual key or use an environment variable
openai.api_key = "sk-YOUR_API_KEY"

# Define a few‑shot prompt for sentiment analysis
examples = [
    {"input": "I love the new design of the app!", "output": "Positive"},
    {"input": "The recent update caused many crashes.", "output": "Negative"},
    {"input": "It's okay, not the best but works.", "output": "Neutral"}
]

# Build the prompt string with a clear instruction and the examples
prompt = "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"
for ex in examples:
    prompt += f"Sentence: {ex['input']}\nSentiment: {ex['output']}\n\n"
# The new sentence we want classified
new_sentence = "The customer support was very helpful."
prompt += f"Sentence: {new_sentence}\nSentiment:"

# Call the Chat Completion API
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",                 # lightweight model for prompt‑engineering tasks
    messages=[
        {"role": "system", "content": "You are a helpful assistant that follows the given format exactly."},
        {"role": "user", "content": prompt}
    ],
    temperature=0.0,                     # deterministic output for classification
    max_tokens=10
)

# Extract and print the model’s answer
sentiment = response.choices[0].message.content.strip()
print(f"Sentiment: {sentiment}")  # Expected output: "Positive", "Negative", or "Neutral"
*/

