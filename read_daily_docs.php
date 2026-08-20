<?php
// 2026-08-20 02:36:26

/* PHP
Topic: Using PDO Prepared Statements for Secure Database Queries  

Explanation:  
Prepared statements separate SQL code from data, preventing attackers from injecting malicious commands.  
They are supported by the PDO extension, which works with many database systems using the same API.  
When a statement is prepared, the database parses the query once and can reuse it multiple times with different values.  
Binding parameters ensures the values are correctly escaped and typed, improving both security and performance.  
If an error occurs, PDO can throw exceptions, making debugging and error handling easier.  

Code example with comments:  

<?php
// Enable PDO exceptions for error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO connection (replace placeholders with real credentials)
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders (automatically escaped)
$stmt->bindValue(':username', 'alice');
$stmt->bindValue(':email', 'alice@example.com');

// Execute the prepared statement
$stmt->execute();

// Retrieve the ID of the newly inserted row
$lastInsertId = $pdo->lastInsertId();
echo "New user ID: " . $lastInsertId . PHP_EOL;

// Example of a SELECT with positional placeholders
$searchEmail = 'alice@example.com';
$selectSql = 'SELECT id, username FROM users WHERE email = ?';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([$searchEmail]);

// Fetch and display results
while ($row = $selectStmt->fetch()) {
    echo "User ID: {$row['id']}, Username: {$row['username']}" . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic resolution. By binding abstractions to concrete implementations, you can swap out functionality without changing the consuming code. Dependency injection allows Laravel to inject the required services into controllers, jobs, or any class resolved by the container. This promotes loose coupling, easier testing, and clearer code organization. Understanding how to register bindings and type‑hint dependencies is essential for building maintainable Laravel applications.  

Code Example:  

<?php  
// Define an interface that describes a payment gateway  
interface PaymentGateway  
{  
    public function charge(float $amount);  
}  

// Provide a concrete implementation for the interface  
class StripeGateway implements PaymentGateway  
{  
    public function charge(float $amount)  
    {  
        // Simulate charging via Stripe API  
        return "Charged $$amount using Stripe.";  
    }  
}  

// Register the binding in a service provider (e.g., AppServiceProvider)  
public function register()  
{  
    // When PaymentGateway is requested, resolve it to StripeGateway  
    $this->app->bind(PaymentGateway::class, StripeGateway::class);  
}  

// A controller that receives the dependency via constructor injection  
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
        $amount = $request->input('amount');  
        // Use the injected service to process the payment  
        $result = $this->paymentGateway->charge($amount);  

        return response()->json(['message' => $result]);  
    }  
}  
*/

/* MySQL
Topic Name: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
When the CTE is defined with the RECURSIVE keyword, it can refer to itself to produce hierarchical or tree‑like result sets.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until a termination condition is met.  
They are useful for traversing organizational charts, category trees, bill‑of‑materials structures, or generating sequences without needing procedural code.  

Code Example:  
-- Define a recursive CTE to list an employee hierarchy  
WITH RECURSIVE employee_path (emp_id, emp_name, manager_id, level) AS (  
    -- Anchor member: start with top‑level managers (no manager)  
    SELECT emp_id, emp_name, manager_id, 1  
    FROM employees  
    WHERE manager_id IS NULL  
    UNION ALL  
    -- Recursive member: join each employee to its manager and increment the level  
    SELECT e.emp_id, e.emp_name, e.manager_id, ep.level + 1  
    FROM employees e  
    JOIN employee_path ep ON e.manager_id = ep.emp_id  
)  
SELECT emp_id, emp_name, manager_id, level  
FROM employee_path  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures allow inner functions to remember the environment in which they were created, including variables from the outer function.  
They are useful for data encapsulation, creating private variables, and implementing factories or module patterns.  
Because the outer variables are kept alive by the inner function, closures can lead to memory usage considerations if not handled carefully.  
Understanding closures is essential for mastering asynchronous code, callbacks, and functional programming techniques in JavaScript.  

Code example with comments:  
function makeCounter() {                     // outer function creates a private variable
    let count = 0;                           // this variable is captured by the inner function
    return function() {                     // the inner function forms a closure
        count += 1;                          // it can read and modify 'count' each call
        console.log('Current count:', count);
    };
}
const counterA = makeCounter();              // each call to makeCounter creates a new closure
const counterB = makeCounter();
counterA(); // Current count: 1
counterA(); // Current count: 2
counterB(); // Current count: 1   (separate closure, independent count)  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a few example input‑output pairs before the actual user query, guiding the model toward the desired response style. This technique works well with chat‑based models because the examples can be embedded as prior messages in the conversation. By carefully choosing diverse but representative examples, you can reduce ambiguity and improve consistency without fine‑tuning. The approach is lightweight, requires only API calls, and can be adapted on the fly for different tasks such as classification, translation, or code generation.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few example interactions that illustrate the desired behavior  
few_shot_examples = [  
    {"role": "user", "content": "Convert the temperature 30°C to Fahrenheit."},  
    {"role": "assistant", "content": "The temperature is 86°F."},  
    {"role": "user", "content": "What is 7 multiplied by 8?"},  
    {"role": "assistant", "content": "7 multiplied by 8 equals 56."}  
]  

# The actual user query we want the model to answer using the same pattern  
new_query = {"role": "user", "content": "Translate 'Good morning' to Spanish."}  

# Combine examples and the new query into a single message list  
messages = few_shot_examples + [new_query]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",          # choose a model that supports chat  
    messages=messages,            # send the concatenated messages  
    temperature=0.2               # low temperature for more deterministic output  
)  

# Extract and print the assistant’s reply  
assistant_reply = response.choices[0].message["content"]  
print("Assistant:", assistant_reply)   # Expected: "Buenos días"  
*/

